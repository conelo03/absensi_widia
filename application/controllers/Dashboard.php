<?php
defined('BASEPATH') OR die('No direct script access allowed!');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_login();
    }

    public function index()
    {
        $tahun = date('Y');
        $bulan = $this->db->query("SELECT DATE_FORMAT(tgl, '%m-%Y') AS bulan from rekap_absensi where tgl like '$tahun%' group by DATE_FORMAT(tgl, '%m-%Y')")->result_array();

        $arr_sakit = [];
        $arr_izin = [];
        $arr_alpha = [];
        $arr_bulan = [];
        foreach ($bulan as $key) {
            $alpha = $this->db->query("SELECT * from rekap_absensi where  DATE_FORMAT(tgl, '%m-%Y') = '".$key['bulan']."' and keterangan='Alpha'")->num_rows();
            array_push($arr_alpha, $alpha);

            $izin = $this->db->query("SELECT * from rekap_absensi where  DATE_FORMAT(tgl, '%m-%Y') = '".$key['bulan']."' and keterangan='Izin'")->num_rows();
            array_push($arr_izin, $izin);

            $sakit = $this->db->query("SELECT * from rekap_absensi where  DATE_FORMAT(tgl, '%m-%Y') = '".$key['bulan']."' and keterangan='Sakit'")->num_rows();
            array_push($arr_sakit, $sakit);

            $pch_bln = explode('-', $key['bulan']);
            $bulan_ini = $this->bulan($pch_bln[0]);
            $bulan_jadi = $bulan_ini.' '.$pch_bln[1];
            array_push($arr_bulan, $bulan_jadi);
        }

        $data['arr_bulan'] = json_encode($arr_bulan);
        $data['arr_sakit'] = json_encode($arr_sakit);
        $data['arr_izin'] = json_encode($arr_izin);
        $data['arr_alpha'] = json_encode($arr_alpha);

        return $this->template->load('template', 'dashboard', $data);
    }

    function bulan($bulan)
    {
        $bulan=$bulan;
        switch ($bulan) {
          case '01':
            $bulan= "Januari";
            break;
          case '02':
            $bulan= "Februari";
            break;
          case '03':
            $bulan= "Maret";
            break;
          case '04':
            $bulan= "April";
            break;
          case '05':
            $bulan= "Mei";
            break;
          case '06':
            $bulan= "Juni";
            break;
          case '07':
            $bulan= "Juli";
            break;
          case '08':
            $bulan= "Agustus";
            break;
          case '09':
            $bulan= "September";
            break;
          case '10':
            $bulan= "Oktober";
            break;
          case '11':
            $bulan= "November";
            break;
          case '12':
            $bulan= "Desember";
            break;
          default:
            $bulan= "Isi variabel tidak di temukan";
            break;
        }

        return $bulan;
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect(base_url());
    }
}



/* End of File: d:\Ampps\www\project\absen-pegawai\application\controllers\Dashboard.php */