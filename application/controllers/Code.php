<?php
defined('BASEPATH') OR die('No direct script access allowed!');

class Code extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        //is_login(true);
    }

    public function index()
    {
        $code = $this->db->get_where('code', ['id' => '1'])->row_array();

        $this->load->library('ciqrcode'); //pemanggilan library QR CODE
 
        $config['cacheable']    = true; //boolean, the default is true
        $config['cachedir']     = './assets/'; //string, the default is application/cache/
        $config['errorlog']     = './assets/'; //string, the default is application/logs/
        $config['imagedir']     = './assets/img/'; //direktori penyimpanan qr code
        $config['quality']      = true; //boolean, the default is true
        $config['size']         = '1024'; //interger, the default is 1024
        $config['black']        = array(224,255,255); // array, default is array(255,255,255)
        $config['white']        = array(70,130,180); // array, default is array(0,0,0)
        $this->ciqrcode->initialize($config);

        unlink("./assets/img/".$code['image']);
        
        $rand = $this->generateRandomString();

        $image_name=$rand.'.png'; //buat name dari qr code sesuai dengan nim
 
        $params['data'] = $rand; //data yang akan di jadikan QR CODE
        $params['level'] = 'H'; //H=High
        $params['size'] = 10;
        $params['savename'] = FCPATH.$config['imagedir'].$image_name; //simpan image QR CODE ke folder assets/images/
        $this->ciqrcode->generate($params); // fungsi untuk generate QR CODE
        
        $data = [
            'code' => $rand,
            'image' => $image_name
        ];
        $this->db->where('id', '1');
        $this->db->update('code', $data); //simpan ke database
        $data['code'] = $this->db->get_where('code', ['id' => '1'])->row_array();
        return $this->load->view('code', $data);
    }

    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
