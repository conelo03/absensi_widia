<?php
defined('BASEPATH') OR die('No direct script access allowed!');

class Absensi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_login();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Absensi_model', 'absensi');
        $this->load->model('Karyawan_model', 'karyawan');
        $this->load->model('Jam_model', 'jam');
        $this->load->model('User_model', 'user');
        $this->load->helper('Tanggal');
    }

    public function index($menu)
    {
        if (is_level('Karyawan')) {
            return $this->detail_absensi();
        } else {
            return $this->list_karyawan($menu);
        }
    }

    public function list_karyawan($menu)
    {
        $data['karyawan'] = $this->karyawan->get_all();
        $data['menu'] = $menu;
        return $this->template->load('template', 'absensi/list_karyawan', $data);
    }

    public function detail_absensi()
    {
        $data = $this->detail_data_absen();
        return $this->template->load('template', 'absensi/detail', $data);
    }

    public function rekap_absensi()
    {
        $id_user = @$this->uri->segment(3) ? $this->uri->segment(3) : $this->session->userdata('id_user');
        $bulan = @$this->input->get('bulan') ? $this->input->get('bulan') : date('m');
        $tahun = @$this->input->get('tahun') ? $this->input->get('tahun') : date('Y');
        $bt = $bulan.'-'.$tahun;
        $user = $this->db->get_where('users', ['level' => 'Karyawan'])->result_array();

        $arr_rekap = [];
        foreach ($user as $key) {
            $id_user= $key['id_user'];
            $nama_user= $key['nama'];
            $div = $this->db->get_where('divisi', ['id_divisi' => $key['divisi']])->row_array();

            $hadir = $this->db->query("SELECT * from rekap_absensi where  DATE_FORMAT(tgl, '%m-%Y') = '$bt' and (keterangan is null or keterangan='Telat') and id_user='$id_user'")->num_rows();

            $alpha = $this->db->query("SELECT * from rekap_absensi where  DATE_FORMAT(tgl, '%m-%Y') = '$bt' and keterangan='Alpha' and id_user='$id_user'")->num_rows();

            $izin = $this->db->query("SELECT * from rekap_absensi where  DATE_FORMAT(tgl, '%m-%Y') = '$bt' and keterangan='Izin' and id_user='$id_user'")->num_rows();

            $sakit = $this->db->query("SELECT * from rekap_absensi where  DATE_FORMAT(tgl, '%m-%Y') = '$bt' and keterangan='Sakit' and id_user='$id_user'")->num_rows();

            array_push($arr_rekap, [$nama_user, $div['nama_divisi'], $hadir, $sakit, $izin, $alpha]);
        }
        
        $data['absen'] = $arr_rekap;
        $data['jam_kerja'] = (array) $this->jam->get_all();
        $data['all_bulan'] = bulan();
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['hari'] = hari_bulan($bulan, $tahun);

        return $this->template->load('template', 'absensi/rekap_absensi', $data);
    }

    public function check_absen()
    {
        $now = date('H:i:s');
        $data['jam'] = $this->absensi->get_jam_by_time($now)->num_rows();
        $data['absen'] = $this->absensi->absen_harian_user($this->session->id_user)->num_rows();
        return $this->template->load('template', 'absensi/absen', $data);
    }

    public function scan($ket)
    {
        $data['keterangan'] = $ket;
        return $this->template->load('template', 'absensi/scan', $data);
    }

    public function absen()
    {
        $kode = $this->uri->segment(4);
        $code = $this->db->get_where('code', ['id' => '1'])->row_array();

        if($kode != $code['code']){
            $this->session->set_flashdata('response', [
                'status' => 'error',
                'message' => 'QR Code salah!'
            ]);

            redirect('absensi/scan/'.$this->uri->segment(3));
        }

        if (@$this->uri->segment(3)) {
            $keterangan = ucfirst($this->uri->segment(3));
        } else {
            $absen_harian = $this->absensi->absen_harian_user($this->session->id_user)->num_rows();
            $keterangan = ($absen_harian < 2 && $absen_harian < 1) ? 'Masuk' : 'Pulang';
        }

        $data = [
            'tgl' => date('Y-m-d'),
            'waktu' => date('H:i:s'),
            'keterangan' => $keterangan,
            'id_user' => $this->session->id_user
        ];
        
        $get_absen_row = $this->db->get_where('absensi', ['tgl' => $data['tgl'], 'id_user' => $data['id_user'], 'keterangan' => $keterangan] )->num_rows();
        if($get_absen_row > 0){
            $this->session->set_flashdata('response', [
                    'status' => 'error',
                    'message' => 'Anda sudah absen '.$keterangan.' hari ini'
                ]);
            redirect('absensi/detail_absensi');
        }else{
            $id_user = $this->session->id_user;
            $tgl = date('Y-m-d');
            $user = $this->db->get_where('users', ['id_user' => $id_user])->row_array();
            $jam = $this->db->get_where('jam', ['id_divisi' => $user['divisi'], 'keterangan' => 'Masuk'])->row_array();
            $result = $this->absensi->insert_data($data);
            $cek_rekap = $this->db->get_where('rekap_absensi', ['id_user' => $id_user, 'tgl' => $tgl])->num_rows();

            if($cek_rekap == 0){
                $data_rekap = [
                    'id_user' => $id_user,
                    'tgl' => $tgl,
                    'jam_masuk' => date('H:i:s'),
                    'keterangan' => (date('H:i:s') > date('H:i:s', strtotime($jam['start']))) ? 'Telat' : '',
                ];

                $this->db->insert('rekap_absensi', $data_rekap);
            } else {
                $data_rekap = [
                    'jam_keluar' => date('H:i:s'),
                ];
                $this->db->where('id_user', $id_user);
                $this->db->where('tgl', $tgl);
                $this->db->update('rekap_absensi', $data_rekap);
            }

            if ($result) {
                $this->session->set_flashdata('response', [
                    'status' => 'success',
                    'message' => 'Absensi berhasil dicatat'
                ]);
            } else {
                $this->session->set_flashdata('response', [
                    'status' => 'error',
                    'message' => 'Absensi gagal dicatat'
                ]);
            }
        redirect('absensi/detail_absensi');
        }
        
    }

    public function absen_pulang()
    {
        $post = $this->input->post();
        $id_rekap = $post['id_rekap'];
        $id_user = $post['id_user'];
        $keterangan = $post['keterangan'];
        $bukti = null;
        if(!empty($_FILES['bukti']['name'])){
            $bukti = $this->upload();
            
        }

        $data_rekap = [
            'keterangan' => $post['keterangan'],
            'bukti' => $bukti,
        ];
        
        $this->db->where('id_rekap', $id_rekap);
        $result = $this->db->update('rekap_absensi', $data_rekap);

        if ($result) {
            $this->session->set_flashdata('response', [
                'status' => 'success',
                'message' => 'Absensi berhasil dicatat'
            ]);
        } else {
            $this->session->set_flashdata('response', [
                'status' => 'error',
                'message' => 'Absensi gagal dicatat'
            ]);
        }
        redirect('absensi/detail_absensi/'.$id_user);
        
    }

    private function upload()
    {
        $this->load->library('upload');
        $config['upload_path'] = './assets/upload/bukti';
        $config['allowed_types'] = 'jpg|png|jpeg|PNG';
        $config['max_size'] = 10100;
        $this->upload->initialize($config);
        $this->load->library('upload', $config);

        if(! $this->upload->do_upload('bukti'))
        {
            return '';
        }

        return $this->upload->data('file_name');
    }

    public function export_pdf()
    {
        $this->load->library('pdf');
        $data = $this->detail_data_absen();
        $html_content = $this->load->view('absensi/print_pdf', $data, true);
        $filename = 'Absensi ' . $data['karyawan']->nama . ' - ' . bulan($data['bulan']) . ' ' . $data['tahun'] . '.pdf';

        $this->pdf->loadHtml($html_content);
        $this->pdf->set_paper('a4','potrait');

        
        $this->pdf->render();
        $this->pdf->stream($filename, ['Attachment' => 1]);
    }

    public function export_rekap_pdf()
    {
        $this->load->library('pdf');
        $id_user = @$this->uri->segment(3) ? $this->uri->segment(3) : $this->session->userdata('id_user');
        $bulan = @$this->input->get('bulan') ? $this->input->get('bulan') : date('m');
        $tahun = @$this->input->get('tahun') ? $this->input->get('tahun') : date('Y');
        $bt = $bulan.'-'.$tahun;
        $user = $this->db->get_where('users', ['level' => 'Karyawan'])->result_array();

        $arr_rekap = [];
        foreach ($user as $key) {
            $id_user= $key['id_user'];
            $nama_user= $key['nama'];
            $div = $this->db->get_where('divisi', ['id_divisi' => $key['divisi']])->row_array();

            $hadir = $this->db->query("SELECT * from rekap_absensi where  DATE_FORMAT(tgl, '%m-%Y') = '$bt' and (keterangan is null or keterangan='Telat') and id_user='$id_user'")->num_rows();

            $alpha = $this->db->query("SELECT * from rekap_absensi where  DATE_FORMAT(tgl, '%m-%Y') = '$bt' and keterangan='Alpha' and id_user='$id_user'")->num_rows();

            $izin = $this->db->query("SELECT * from rekap_absensi where  DATE_FORMAT(tgl, '%m-%Y') = '$bt' and keterangan='Izin' and id_user='$id_user'")->num_rows();

            $sakit = $this->db->query("SELECT * from rekap_absensi where  DATE_FORMAT(tgl, '%m-%Y') = '$bt' and keterangan='Sakit' and id_user='$id_user'")->num_rows();

            array_push($arr_rekap, [$nama_user, $div['nama_divisi'], $hadir, $sakit, $izin, $alpha]);
        }
        
        $data['absen'] = $arr_rekap;
        $data['jam_kerja'] = (array) $this->jam->get_all();
        $data['all_bulan'] = bulan();
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['hari'] = hari_bulan($bulan, $tahun);
        $html_content = $this->load->view('absensi/print_rekap_pdf', $data, true);
        $filename = 'Rekap Absensi  - ' . bulan($data['bulan']) . ' ' . $data['tahun'] . '.pdf';

        $this->pdf->loadHtml($html_content);
        $this->pdf->set_paper('a4','landscape');
        
        $this->pdf->render();
        $this->pdf->stream($filename, ['Attachment' => 1]);
    }

    public function export_excel()
    {
            include_once APPPATH . 'third_party/PHPExcel.php';
            $data = $this->detail_data_absen();
            $hari = $data['hari'];
            $absen = $data['absen'];
            $excel = new PHPExcel();

            $excel->getProperties()
                    ->setCreator('IndoExpress')
                    ->setLastModifiedBy('IndoExpress')
                    ->setTitle('Data Absensi')
                    ->setSubject('Absensi')
                    ->setDescription('Absensi' . $data['karyawan']->nama . ' bulan ' . bulan($data['bulan']) . ', ' . $data['tahun'])
                    ->setKeyWords('data absen');

            $style_col = [
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                    'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                    'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                    'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                ]
            ];

            $style_row = [
                'alignment' => [
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                    'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                    'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                    'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                ]
            ];

            $style_row_libur = [
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => ['rgb' => '343A40']
                ],
                'font' => [
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'alignment' => [
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                    'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                    'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                    'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                ]
            ];

            $style_row_tidak_masuk = [
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => ['rgb' => 'DC3545']
                ],
                'font' => [
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'alignment' => [
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                    'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                    'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                    'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                ]
            ];

            $style_telat = [
                'font' => [
                    'color' => ['rgb' => 'DC3545']
                ]
            ];

            $style_lembur = [
                'font' => [
                    'color' => ['rgb' => '28A745']
                ]
            ];

            $excel->setActiveSheetIndex(0)->setCellValue('A1', 'Nama : ' . $data['karyawan']->nama);
            $excel->getActiveSheet()->mergeCells('A1:E1');
            $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
            $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);

            $excel->setActiveSheetIndex(0)->setCellValue('A2', 'Divisi : ' . $data['karyawan']->nama_divisi);
            $excel->getActiveSheet()->mergeCells('A2:E2');
            $excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
            $excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);

            $excel->setActiveSheetIndex(0)->setCellValue('A3', '');
            $excel->getActiveSheet()->mergeCells('A3:E3');

            $excel->setActiveSheetIndex(0)->setCellValue('A4', 'Data Absensi Bulan ' . bulan($data['bulan']) . ', ' . $data['tahun']);
            $excel->getActiveSheet()->mergeCells('A4:E4');
            $excel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);
            $excel->getActiveSheet()->getStyle('A4')->getFont()->setSize(12);

            $excel->setActiveSheetIndex(0)->setCellValue('A5', 'NO');
            $excel->setActiveSheetIndex(0)->setCellValue('B5', 'Tanggal');
            $excel->setActiveSheetIndex(0)->setCellValue('C5', 'Jam Masuk');
            $excel->setActiveSheetIndex(0)->setCellValue('D5', 'Jam Keluar');
            $excel->setActiveSheetIndex(0)->setCellValue('E5', 'Keterangan');

            $excel->getActiveSheet()->getStyle('A5')->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('B5')->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('C5')->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('D5')->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('E5')->applyFromArray($style_col);

            $numrow = 6;
            $x = 0;
            foreach ($absen as $i) {
                // $absen_harian = array_search($h['tgl'], array_column($absen, 'tgl')) !== false ? $absen[array_search($h['tgl'], array_column($absen, 'tgl'))] : '';

                $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, ($x+1));
                $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $i['tgl']);
                $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, is_weekend($i['tgl']) ? 'Libur Akhir Pekan' : check_jam(@$i['jam_masuk'], 'masuk', true,$data['karyawan']->divisi)['text']);
                $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, is_weekend($i['tgl']) ? 'Libur Akhir Pekan' : check_jam(@$i['jam_keluar'], 'pulang', true,$data['karyawan']->divisi)['text']);

                if (check_jam(@$i['jam_masuk'], 'masuk', true,$data['karyawan']->divisi)['status'] == 'telat') {
                    $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_telat);
                }

                if (check_jam(@$i['jam_keluar'], 'pulang', true,$data['karyawan']->divisi)['status'] == 'lembur') {
                    $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_lembur);
                }

                $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $i['keterangan']);

                if (is_weekend($i['tgl'])) {
                    $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row_libur);
                    $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row_libur);
                    $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row_libur);
                    $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row_libur);
                    $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row_libur);
                } elseif (is_null($i['jam_masuk']) && is_null($i['jam_keluar'])) {
                    $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row_tidak_masuk);
                    $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row_tidak_masuk);
                    $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row_tidak_masuk);
                    $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row_tidak_masuk);
                    $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row_tidak_masuk);
                } else {
                    $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
                    $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
                    $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
                    $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
                    $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
                }
                $numrow++;
            }

            $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
            $excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
            $excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
            $excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
            $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="Absensi ' . $data['karyawan']->nama . ' - ' . bulan($data['bulan']) . ' ' . $data['tahun'] . '.xlsx"'); // Set nama file excel nya
            header('Cache-Control: max-age=0');

            $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
            $write->save('php://output');
    }

    public function export_rekap_excel()
    {

            include_once APPPATH . 'third_party/PHPExcel.php';
            $data = $this->data_rekap_absen();
            $hari = $data['hari'];
            $absen = $data['absen'];
            $excel = new PHPExcel();

            $excel->getProperties()
                    ->setCreator('IndoExpress')
                    ->setLastModifiedBy('IndoExpress')
                    ->setTitle('Data Absensi')
                    ->setSubject('Absensi')
                    ->setDescription('Rekap Absensi bulan ' . bulan($data['bulan']) . ', ' . $data['tahun'])
                    ->setKeyWords('data absen');

            $style_col = [
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                    'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                    'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                    'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                ]
            ];

            $style_row = [
                'alignment' => [
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                    'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                    'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                    'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                ]
            ];

            $style_row_libur = [
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => ['rgb' => '343A40']
                ],
                'font' => [
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'alignment' => [
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                    'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                    'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                    'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                ]
            ];

            $style_row_tidak_masuk = [
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => ['rgb' => 'DC3545']
                ],
                'font' => [
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'alignment' => [
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                    'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                    'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                    'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
                ]
            ];

            $style_telat = [
                'font' => [
                    'color' => ['rgb' => 'DC3545']
                ]
            ];

            $style_lembur = [
                'font' => [
                    'color' => ['rgb' => '28A745']
                ]
            ];


            $excel->setActiveSheetIndex(0)->setCellValue('A3', '');
            $excel->getActiveSheet()->mergeCells('A3:F3');

            $excel->setActiveSheetIndex(0)->setCellValue('A4', 'Data Absensi Bulan ' . bulan($data['bulan']) . ', ' . $data['tahun']);
            $excel->getActiveSheet()->mergeCells('A4:F4');
            $excel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);
            $excel->getActiveSheet()->getStyle('A4')->getFont()->setSize(12);

            $excel->setActiveSheetIndex(0)->setCellValue('A5', 'NO');
            $excel->setActiveSheetIndex(0)->setCellValue('B5', 'Nama');
            $excel->setActiveSheetIndex(0)->setCellValue('C5', 'Tanggal');
            $excel->setActiveSheetIndex(0)->setCellValue('D5', 'Jam Masuk');
            $excel->setActiveSheetIndex(0)->setCellValue('E5', 'Jam Keluar');
            $excel->setActiveSheetIndex(0)->setCellValue('F5', 'Keterangan');

            $excel->getActiveSheet()->getStyle('A5')->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('B5')->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('C5')->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('D5')->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('E5')->applyFromArray($style_col);
            $excel->getActiveSheet()->getStyle('F5')->applyFromArray($style_col);

            $numrow = 6;
            $x =1;
            foreach ($absen as $i) {
                // $absen_harian = array_search($h['tgl'], array_column($absen, 'tgl')) !== false ? $absen[array_search($h['tgl'], array_column($absen, 'tgl'))] : '';

                $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, ($x++));
                $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $i['nama']);
                $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $i['tgl']);
                $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, check_jam(@$i['jam_masuk'], 'masuk', true, $i['divisi'])['text']);
                $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, check_jam(@$i['jam_keluar'], 'pulang', true, $i['divisi'])['text']);
                $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $i['keterangan']);
                

                $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
                $numrow++;
            }

            $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
            $excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
            $excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="Rekap Absensi  - ' . bulan($data['bulan']) . ' ' . $data['tahun'] . '.xlsx"'); // Set nama file excel nya
            header('Cache-Control: max-age=0');

            $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
            $write->save('php://output');
    }

    private function detail_data_absen()
    {
        $id_user = @$this->uri->segment(3) ? $this->uri->segment(3) : $this->session->userdata('id_user');
        $bulan = @$this->input->get('bulan') ? $this->input->get('bulan') : date('m');
        $tahun = @$this->input->get('tahun') ? $this->input->get('tahun') : date('Y');
            
        $data['karyawan'] = $this->karyawan->find($id_user);
            $data['absen'] = $this->absensi->get_absensi($id_user, $bulan, $tahun);
            $data['jam_kerja'] = (array) $this->jam->get_all();
            $data['all_bulan'] = bulan();
            $data['bulan'] = $bulan;
            $data['tahun'] = $tahun;
            $data['hari'] = hari_bulan($bulan, $tahun);

            return $data;
    }

    private function data_rekap_absen()
    {
        $id_user = @$this->uri->segment(3) ? $this->uri->segment(3) : $this->session->userdata('id_user');
        $bulan = @$this->input->get('bulan') ? $this->input->get('bulan') : date('m');
        $tahun = @$this->input->get('tahun') ? $this->input->get('tahun') : date('Y');
            
        //$data['karyawan'] = $this->karyawan->find($id_user);
            $data['absen'] = $this->absensi->get_rekap_absensi($bulan, $tahun);
            $data['jam_kerja'] = (array) $this->jam->get_all();
            $data['all_bulan'] = bulan();
            $data['bulan'] = $bulan;
            $data['tahun'] = $tahun;
            $data['hari'] = hari_bulan($bulan, $tahun);

            return $data;
    }

    public function laporan_absensi()
    {
        $bulan = @$this->input->get('bulan') ? $this->input->get('bulan') : date('m');
        $tahun = @$this->input->get('tahun') ? $this->input->get('tahun') : date('Y');
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['all_bulan'] = bulan();
        $data['absensi'] = $this->db->get_where('users',['level' => 'Karyawan'])->result_array();
        return $this->template->load('template', 'laporan_absensi', $data);
    }

    public function export_laporan_absensi()
    {
        $this->load->library('pdf');
        $bulan = @$this->input->get('bulan') ? $this->input->get('bulan') : date('m');
        $tahun = @$this->input->get('tahun') ? $this->input->get('tahun') : date('Y');
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['all_bulan'] = bulan();
        $data['absensi'] = $this->db->get_where('users',['level' => 'Karyawan'])->result_array();
        $html_content = $this->load->view('print_laporan_absensi', $data, true);
        $filename = 'Laporan Absensi Bulan '.$bulan.' '. $tahun . '.pdf';

        $this->pdf->loadHtml($html_content);

        $this->pdf->set_paper('a4','landscape');
        
        $this->pdf->render();
        $this->pdf->stream($filename, ['Attachment' => 1]);
    }
}


/* End of File: d:\Ampps\www\project\absen-pegawai\application\controllers\Absensi.php */