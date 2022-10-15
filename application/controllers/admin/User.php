<?php
defined('BASEPATH') or exit('No direct script access allowed');
class User extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
        $this->load->model('Curd_model');
        authValidate();
	}

	public function list()
	{
		$data['userArray'] = $this->Curd_model->Select('tbl_login',['role' =>'user']);
		$this->load->view('admin/user', $data);
	}

	public function user_edit($id)
	{
		$data['AccountArray'] = $this->Curd_model->getAccounts($id);
		$this->load->view('admin/user-edit', $data);
	}

	public function AccountEditCode()
	{
		$array = $this->security->xss_clean($this->input->post());
		if ($this->Curd_model->update('tbl_login', ['id' => $array['id']], $array) == true) {
			$array_msg = array('msg' => 'Successfully Update!', 'icon' => 'success');
			$this->session->set_flashdata($array_msg);
			redirect(base_url('admin/user/list'));
		} else {
			$array_msg = array('msg' => 'Server Error!', 'icon' => 'error');
			$this->session->set_flashdata($array_msg);
			redirect(base_url('admin/user/list'));
		}
	}


    public function UserloginStatus()
	{
		$id = $this->input->get('id');

		$statusInput = $this->input->get('status');
		if ($statusInput == 'Active') {
			$status = 'Deactivate';
		} else {
			$status = 'Active';
		}
		$admin = $this->session->userdata('LoginSession');
		if ($admin['id'] == $id) {
			$array_msg = array('msg' => 'Can`t update myself !', 'icon' => 'error');
			$this->session->set_flashdata($array_msg);
			redirect(base_url('admin/user/list'));
		} else {

			if ($this->Curd_model->update('tbl_login', ['id' => $id], ['status' => $status]) == true) {
				$array_msg = array('msg' => 'Successfully Update!', 'icon' => 'success');
				$this->session->set_flashdata($array_msg);
				redirect(base_url('admin/user/list'));
			} else {
				$array_msg = array('msg' => 'Server Error!', 'icon' => 'error');
				$this->session->set_flashdata($array_msg);
				redirect(base_url('admin/user/list'));
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
		$admin = $this->session->userdata('LoginSession');
		if ($admin['id'] == $id) {
			echo json_encode(array("statusCode" => 201, "msg" => 'Can`t delete myself !'));
		} else {
			$this->Curd_model->Delete('tbl_login', ['id'=>$id]);
			echo json_encode(array("statusCode" => 200, "msg" => 'Deleted Successfully!'));
		}
	}


	public function user_password_change($id)
	{
		$data['AccountArray'] = $this->Curd_model->getAccounts($id);
		$this->load->view('admin/user-password-change', $data);
	}

    public function passwordUpdateCode()
    {
        $id = $this->security->xss_clean($this->input->post('id'));
        $CurrentPassword = $this->security->xss_clean($this->input->post('CurrentPassword'));
        $password = $this->security->xss_clean($this->input->post('password'));
        $Cpassword = $this->security->xss_clean($this->input->post('cpassword'));
        $data = $this->Curd_model->getAccounts($id);
        if ($data['role'] == 'admin') {
            $url = base_url() . 'admin/accounts-list';
        } else {
            $url = base_url() . 'admin/user/list';
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

	public function friend($id)
	{
		$data['friendArray'] = $this->Curd_model->UserFriendList($id);
		$this->load->view('admin/friend',$data);
	}





}