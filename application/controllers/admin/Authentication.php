<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Authentication extends CI_Controller
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

    public function accounts_add()
    {
        $this->load->view('admin/accounts-add');
    }

    public function account_add()
    {
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('name', 'Name', 'trim|strip_tags|required');
        $this->form_validation->set_rules('role', 'Role', 'trim|strip_tags|required');
        $this->form_validation->set_rules('username', 'Username', 'trim|strip_tags|required|is_unique[tbl_login.username]');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|strip_tags|required|numeric|min_length[10]|is_unique[tbl_login.mobile]');
        $this->form_validation->set_rules('email', 'Email', 'trim|strip_tags|required|valid_email|is_unique[tbl_login.email]');
        $this->form_validation->set_rules('password', 'Password', 'trim|strip_tags|required');
        $this->form_validation->set_rules('cpassword', 'Confirm password', 'trim|strip_tags|required|matches[password]');

        if ($this->form_validation->run() == true) {

            $data['role'] =     $this->security->xss_clean($this->input->post('role'));
            $data['name'] =     $this->security->xss_clean($this->input->post('name'));
            $data['username'] = $this->security->xss_clean($this->input->post('username'));
            $data['mobile'] =   $this->security->xss_clean($this->input->post('mobile'));
            $data['email'] =    $this->security->xss_clean($this->input->post('email'));
            $data['password'] = $this->security->xss_clean(password_hash($this->input->post('cpassword'), PASSWORD_BCRYPT));
            $data['date'] = date('Y-m-d h:i:s A');


            $config['upload_path']          = 'uploads/user/';
            $config['allowed_types']        = 'jpeg|jpg|png';
            $config['max_size']             = 2000;
            $config['encrypt_name'] = TRUE;
            // $config['max_width']            = 1024;
            // $config['max_height']           = 768;

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('file')) {
                $array_msg = array('msg' => 'Upload is larger than the permitted size', 'icon' => 'error');
                $this->session->set_flashdata($array_msg);
                $this->load->view('admin/accounts-add');
            } else {
                $photo = array('upload_data' => $this->upload->data());
                $data['photo'] = $photo['upload_data']['file_name'];

                if ($this->Curd_model->insert('tbl_login',$data) == true) {
                    $array_msg = array('msg' => 'Successfully Create!', 'icon' => 'success');
                    $this->session->set_flashdata($array_msg);
                    redirect(base_url('admin/authentication/accounts-list'));
                } else {
                    $array_msg = array('msg' => 'Server Error!', 'icon' => 'error');
                    $this->session->set_flashdata($array_msg);
                    redirect(base_url('admin/authentication/accounts-list'));
                }
            }
        } else {
            $this->load->view('admin/accounts-add');
        }
    }

    public function accounts_list()
    {
        $LoginList = $this->Curd_model->loginGet();
        $data['loginArray'] = $LoginList;
        $this->load->view('admin/accounts-list', $data);
    }

    public function accounts_edit($id)
    {
        $data['AccountArray'] = $this->Curd_model->getAccounts($id);
        $this->load->view('admin/accounts-edit', $data);
    }

    public function password_change($id)
    {
        $data['AccountArray'] = $this->Curd_model->getAccounts($id);
        $this->load->view('admin/password-change', $data);
    }

    public function passwordUpdateCode()
    {
        $id = $this->security->xss_clean($this->input->post('id'));
        $CurrentPassword = $this->security->xss_clean($this->input->post('CurrentPassword'));
        $password = $this->security->xss_clean($this->input->post('password'));
        $Cpassword = $this->security->xss_clean($this->input->post('cpassword'));
        $data = $this->Curd_model->getAccounts($id);
        if ($data['role'] == 'admin') {
            $url = base_url() . 'admin/authentication/accounts-list';
        }
        if (password_verify($CurrentPassword, $data['password']) == true) {
            if ($password == $Cpassword) {
                $cpass    = $this->security->xss_clean(password_hash($Cpassword, PASSWORD_BCRYPT));
                if ($this->Curd_model->update('tbl_login', ['id' => $id], ['password' => $cpass]) == true) {
                    echo json_encode(array("statusCode" => 200, "msg" => 'Successfully Update Password', "url" => $url));
                } else {
                    echo json_encode(array("statusCode" => 201, "msg" => 'Server Error'));
                }
            } else {
                echo json_encode(array("statusCode" => 201, "msg" => 'Password Not Match'));
            }
        } else {
            echo json_encode(array("statusCode" => 201, "msg" => 'Password is incorrect'));
        }
    }



    public function AccountEditCode()
    {
        $array = $this->security->xss_clean($this->input->post());
        if ($this->Curd_model->update('tbl_login', ['id' => $array['id']], $array) == true) {
            $array_msg = array('msg' => 'Successfully Update!', 'icon' => 'success');
            $this->session->set_flashdata($array_msg);
            redirect(base_url('admin/authentication/accounts-list'));
        } else {
            $array_msg = array('msg' => 'Server Error!', 'icon' => 'error');
            $this->session->set_flashdata($array_msg);
            redirect(base_url('admin/authentication/accounts-list'));
        }
    }


    public function loginStatus()
    {
        $id = $this->input->get('id');

        $statusInput = $this->input->get('status');
        if ($statusInput == 'Active') {
            $status = 'Deactivate';
        } else {
            $status = 'Active';
        }
        $admin = $this->session->userdata('id');
        if ($admin['id'] == $id) {
            $array_msg = array('msg' => 'Can`t update myself !', 'icon' => 'error');
            $this->session->set_flashdata($array_msg);
            redirect(base_url('admin/authentication/accounts-list'));
        } else {

            if ($this->Curd_model->update('tbl_login', ['id' => $id], ['status' => $status]) == true) {
                $array_msg = array('msg' => 'Successfully Update!', 'icon' => 'success');
                $this->session->set_flashdata($array_msg);
                redirect(base_url('admin/authentication/accounts-list'));
            } else {
                $array_msg = array('msg' => 'Server Error!', 'icon' => 'error');
                $this->session->set_flashdata($array_msg);
                redirect(base_url('admin/authentication/accounts-list'));
            }
        }
    }

    public function deleteLogin()
	{
		$id = $this->input->post('delete_id');
		$admin = $this->Curd_model->getAccounts($id);
		if (empty($admin)) {
			echo json_encode(array("statusCode" => 201, "msg" => 'Admin not found'));
		}
		$admin = $this->session->userdata('id');
		if ($admin['id'] == $id) {
			echo json_encode(array("statusCode" => 201, "msg" => 'Can`t delete myself !'));
		} else {
			$this->Curd_model->Delete('tbl_login', ['id'=>$id]);
			echo json_encode(array("statusCode" => 200, "msg" => 'Deleted Successfully!', "url" => base_url() . 'admin/accounts-list'));
		}
	}



}