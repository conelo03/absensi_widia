<?php
defined('BASEPATH') OR die('No direct script access allowed!');

class Jam extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_login();
        redirect_if_level_not('Manager');
        $this->load->model('Divisi_model', 'divisi');
        $this->load->model('Jam_model', 'jam');
    }

    public function index()
    {
        $data['jam'] = $this->jam->get_all();
        $data['divisi'] = $this->divisi->get_all();
        return $this->template->load('template', 'jam', $data);
    }

    public function add()
    {
        $post = $this->input->post();
        $data = [
            'id_divisi' => $post['divisi'],
            'start' => $post['start'],
            'finish' => $post['finish'],
            'keterangan' => $post['keterangan']
        ];

        $result = $this->jam->insert_data($data);
        if ($result) {
            $this->session->set_flashdata("success", "Jam Kerja telah ditambah!");
            redirect('jam');
           
        } else {
            $this->session->set_flashdata("error", "Jam Kerja gagal ditambah!");
            redirect('jam');
        }
    }

    public function update()
    {
        $post = $this->input->post();
        $data = [
            'id_divisi' => $post['divisi'],
            'start' => $post['start'],
            'finish' => $post['finish'],
            'keterangan' => $post['keterangan']
        ];

        $result = $this->jam->update_data($post['id_jam'], $data);
        if ($result) {
            $response = [
                'status' => 'success',
                'message' => 'Jam Kerja telah diubah!'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Jam Kerja gagal diubah!'
            ];
        }
        
        $this->session->set_flashdata('response', $response);
        redirect('jam');
        //return $this->response_json($response);
    }

    public function response_json($response)
    {
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}



/* End of File: d:\Ampps\www\project\absen-pegawai\application\controllers\Jam.php */