<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Index extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Curd_model');
        authValidate();
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
