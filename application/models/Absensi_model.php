<?php
defined('BASEPATH') OR die('No direct script access allowed!');

class Absensi_model extends CI_Model 
{
    public function get_absen($id_user, $bulan, $tahun)
    {
            $this->db->select("DATE_FORMAT(a.tgl, '%d-%m-%Y') AS tgl, a.waktu AS jam_masuk, a.id_user as id_user, (SELECT waktu FROM absensi al WHERE al.tgl = a.tgl AND id_user = '$id_user' AND al.keterangan != a.keterangan) AS jam_pulang");
            $this->db->where('id_user', $id_user);
            $this->db->where("DATE_FORMAT(tgl, '%m') = ", $bulan);
            $this->db->where("DATE_FORMAT(tgl, '%Y') = ", $tahun);
            $this->db->group_by("tgl");
            $result = $this->db->get('absensi a');
            

        return $result->result_array();
    }

    public function get_absensi($id_user, $bulan, $tahun)
    {
            $this->db->select("*");
            $this->db->where('id_user', $id_user);
            $this->db->where("DATE_FORMAT(tgl, '%m') = ", $bulan);
            $this->db->where("DATE_FORMAT(tgl, '%Y') = ", $tahun);
            $this->db->order_by("tgl", "DESC");
            $this->db->group_by("tgl");
            $result = $this->db->get('rekap_absensi');
            

        return $result->result_array();
    }

    public function get_rekap_absensi($bulan, $tahun)
    {
            $this->db->select("*");
            $this->db->join('users', 'users.id_user=rekap_absensi.id_user');
            $this->db->where("DATE_FORMAT(rekap_absensi.tgl, '%m') = ", $bulan);
            $this->db->where("DATE_FORMAT(rekap_absensi.tgl, '%Y') = ", $tahun);
            $this->db->group_by("rekap_absensi.tgl");
            $result = $this->db->get('rekap_absensi');
            

        return $result->result_array();
    }

    public function absen_harian_user($id_user)
    {
        $today = date('Y-m-d');
        $this->db->where('tgl', $today);
        $this->db->where('id_user', $id_user);
        $data = $this->db->get('absensi');
        return $data;
    }

    public function insert_data($data)
    {
        $result = $this->db->insert('absensi', $data);
        return $result;
    }

    public function get_jam_by_time($time)
    {
        $id_user = $this->session->id_user;
        $user = $this->db->get_where('users', ['id_user' => $id_user])->row_array();
        // $this->db->where('id_divisi', $user['divisi']);
        // $this->db->where('start', $time, '<=');
        // $this->db->or_where('finish', $time, '>=');
        // $data = $this->db->get('jam');

        $data = $this->db->query("SELECT * FROM jam where (start <= '".$time."' OR finish >= '".$time."') AND id_divisi = ".$user['divisi']."");

        return $data;
    }
}



/* End of File: d:\Ampps\www\project\absen-pegawai\application\models\Absensi_model.php */