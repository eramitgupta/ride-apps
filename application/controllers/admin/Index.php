<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Index extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $admin = $this->session->userdata('id');
        $this->load->model('Curd_model');

        $CheckLogin = $this->Curd_model->loginCheck($admin['id']);
        $Login['loginData'] = $CheckLogin;
        $this->load->view('admin/template/array', $Login);
        if (empty($CheckLogin)) {
            $this->session->unset_userdata('id');
            $array_msg = array('msg' => 'Access Denied!', 'icon' => 'error');
            $this->session->set_flashdata($array_msg);
            redirect(base_url());
        }
    }

    public function welcome()
    {
        $h = date('G');
        if ($h >= 5 && $h <= 11) {
            return "Good Morning!";
        } else if ($h >= 12 && $h <= 15) {
            return "Good Afternoon!";
        } else {
            return "Good Evening!";
        }
    }

    public function index()
    {
        $data['welcome'] = $this->welcome();
        $this->load->view('admin/index', $data);
    }





}
