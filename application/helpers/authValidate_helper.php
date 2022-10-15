<?php

if (!function_exists('authValidate')) {
    function authValidate()
    {
        $CI = &get_instance();
        $admin = $CI->session->userdata('LoginSession');
        $CheckLogin = $CI->Curd_model->Select('tbl_login', ['id'=> $admin['id'], 'role'=> 'admin', 'status'=> 'Active']);
        $CI->load->view('admin/template/array', ['loginData'=> $CheckLogin]);
        if (empty($CheckLogin)) {
            $CI->session->unset_userdata('LoginSession');
            $array_msg = array('msg' => 'Access Denied!', 'icon' => 'error');
            $CI->session->set_flashdata($array_msg);
            redirect(base_url());
        }
    }





}
