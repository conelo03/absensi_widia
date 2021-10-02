<?php
defined('BASEPATH') OR die('No direct script access allowed!');

class Cek_absen extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Absensi_model', 'absensi');
        $this->load->model('Karyawan_model', 'karyawan');
        $this->load->model('Jam_model', 'jam');
        $this->load->model('User_model', 'user');
        $this->load->helper('Tanggal');
    }

    public function index()
    {
        $tanggal = date('Y-m-d');
        $karyawan = $this->db->get_where('users', ['level' => 'Karyawan'])->result_array();
        
        foreach ($karyawan as $key) {
            $id_user = $key['id_user'];
            $cek_absen_hari_ini = $this->db->get_where('rekap_absensi', ['id_user' => $id_user, 'tgl' => $tanggal])->num_rows();
            if($cek_absen_hari_ini == 0){
                $data = [
                    'id_user'   => $key['id_user'],
                    'tgl'       => $tanggal,
                    'keterangan'=> 'Alpha',
                ];

                $this->db->insert('rekap_absensi', $data);
            }
        }
    }
}
