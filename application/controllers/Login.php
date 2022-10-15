<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Curd_model');
		$this->load->helper('emailsent');
	}



	public function index()
	{
		if (!empty($this->session->userdata('LoginSession'))) {
			redirect(base_url('admin/index'));
		}
		$data['title'] = 'Login';
		$this->load->view('admin/login', $data);
	}
	public function authenticate()
	{
		$this->form_validation->set_rules('username', 'Username', 'trim|strip_tags|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|strip_tags|required');

		if ($this->form_validation->run() == true) {
			$username = $this->security->xss_clean($this->input->post('username'));
			$admin = $this->Curd_model->getByUsername($username);

			if (!empty($admin)) {

				$password = $this->security->xss_clean($this->input->post('password'));
				if (password_verify($password, $admin->password) == true) {
					$adminArray['id'] = $admin->id;
					$this->session->set_userdata('LoginSession', $adminArray);
					echo json_encode(array("statusCode" => 200, "msg" => 'Successfully Login', "url" => base_url() . 'admin/index'));
				} else {
					echo json_encode(array("statusCode" => 201, "msg" => 'Enter password is incorrect'));
				}
			} else {
				echo json_encode(array("statusCode" => 201, "msg" => 'Enter username  incorrect'));
			}
		} else {
			echo json_encode(array("statusCode" => 201, "msg" => 'Enter username or password is incorrect'));
		}
	}


	public function forgot_password()
	{
		if (!empty($this->session->userdata('forgot_password_check'))) {
			redirect(base_url('login/verification'));
		}

		$data['title'] = 'Forgot your password?';
		$this->load->view('admin/front-end/forgot-password', $data);
	}

	public function forgot_password_check()
	{
		$this->form_validation->set_rules('username', 'Username', 'trim|strip_tags|required');

		if ($this->form_validation->run() == false) {
			echo json_encode(array("statusCode" => 201, "msg" => 'Enter Username'));
		} else {

			$username = $this->security->xss_clean($this->input->post('username'));
			$ArrayAdmin = $this->Curd_model->getByUsername($username);
			if (empty($ArrayAdmin)) {
				echo json_encode(array("statusCode" => 201, "msg" => 'Please Enter Valid Detail'));
			} else {
				if (empty($ArrayAdmin->email)) {
					echo json_encode(array("statusCode" => 201, "msg" => 'Does Not Exist Email'));
				} else {
					$OTP = str_pad(rand(0, pow(10, 4) - 1), 4, '0', STR_PAD_LEFT);
					$data = [
						'otp' => $OTP,
						'sent' => $ArrayAdmin->email,
						'date_time' => date('Y-m-d h:i:s A'),
					];

					$masg = 'Hi,' . ucfirst($ArrayAdmin->name) . '<br> We received a request to reset your password. Enter the following verification code to reset your password';

					$this->Curd_model->Delete('tbl_otp', ['sent' => $ArrayAdmin->email]);
					if (emialSent($OTP, $masg, $ArrayAdmin->email, 'Forgot Password OTP') == true) {

						$this->Curd_model->insert('tbl_otp', $data);
						$this->session->set_userdata('forgot_password_check', ['id' => $ArrayAdmin->id, 'email' => $ArrayAdmin->email]);

						echo json_encode(array("statusCode" => 200, "msg" => 'Sent a verification code, please check your email', "url" => base_url() . 'login/verification'));
					} else {
						echo json_encode(array("statusCode" => 201, "msg" => 'Server Error!'));
					}
				}
			}
		}
	}



	public function verification()
	{
		if (empty($this->session->userdata('forgot_password_check'))) {
			redirect(base_url());
		}

		$data['forgot_password_check'] = $this->session->userdata('forgot_password_check');
		$data['title'] = 'Otp Verification';
		$this->load->view('admin/front-end/otp-verification', $data);
	}


	public function resent_otp()
	{
		$email = $this->security->xss_clean($this->input->post('email'));
		$ArrayAdmin = $this->Curd_model->getByUsername($email);
		if (empty($ArrayAdmin)) {
			echo json_encode(array("statusCode" => 201, "msg" => 'Session Expired Please Try Again'));
		} else {
			$OTP = str_pad(rand(0, pow(10, 4) - 1), 4, '0', STR_PAD_LEFT);
			$data = [
				'otp' => $OTP,
				'sent' => $ArrayAdmin->email,
				'date_time' => date('Y-m-d h:i:s A'),
			];

			$masg = 'Hi,' . ucfirst($ArrayAdmin->name) . '<br> We received a request to reset your password. Enter the following verification code to reset your password';

			$this->Curd_model->Delete('tbl_otp', ['sent' => $ArrayAdmin->email]);
			if (emialSent($OTP, $masg, $ArrayAdmin->email, 'Forgot Password OTP Resent') == true) {

				$this->Curd_model->insert('tbl_otp', $data);
				$this->session->set_userdata('forgot_password_check', ['id' => $ArrayAdmin->id, 'email' => $ArrayAdmin->email]);

				echo json_encode(array("statusCode" => 200, "msg" => 'Sent a new verification code, check your email'));
			} else {
				echo json_encode(array("statusCode" => 201, "msg" => 'Server Error!'));
			}
		}
	}

	public function forgotPasswordSession()
	{
		$this->session->unset_userdata('forgot_password_check');
		redirect(base_url('login/forgot-password'));
	}

	public function forgotVerification()
	{
		$this->form_validation->set_rules('digit1-input', 'Otp', 'trim|strip_tags|required');
		$this->form_validation->set_rules('digit2-input', 'Otp', 'trim|strip_tags|required');
		$this->form_validation->set_rules('digit3-input', 'Otp', 'trim|strip_tags|required');
		$this->form_validation->set_rules('digit4-input', 'Otp', 'trim|strip_tags|required');

		if ($this->form_validation->run() == false) {
			echo json_encode(array("statusCode" => 201, "msg" => 'OTP Required!'));
		} else {
			$p1 = $this->security->xss_clean($this->input->post('digit1-input'));
			$p2 = $this->security->xss_clean($this->input->post('digit2-input'));
			$p3 = $this->security->xss_clean($this->input->post('digit3-input'));
			$p4 = $this->security->xss_clean($this->input->post('digit4-input'));
			$email = $this->security->xss_clean($this->input->post('email'));

			$code = $p1.$p2.$p3.$p4;

			$ArrayOTP = $this->Curd_model->Select('tbl_otp',['sent' => $email]);

			if($code == $ArrayOTP[0]['otp']){
				$this->session->unset_userdata('forgot_password_check');
				$this->Curd_model->Delete('tbl_otp', ['sent' => $email]);
				$this->session->set_userdata('newPassword', ['email' => $email]);
				echo json_encode(array("statusCode" => 200, "msg" => 'Successfully Verify!', "url" => base_url() . 'login/password-change'));
			}else{
				echo json_encode(array("statusCode" => 201, "msg" => 'Invalid OTP!'));
			}
		}
	}

	public function password_change()
	{
		$data['email'] = $this->session->userdata('newPassword');
		$data['title'] = 'New Password';
		$this->load->view('admin/front-end/password-change', $data);
	}

	public function NewPassword(){
		$this->form_validation->set_rules('password', 'Password', 'trim|strip_tags|required');
		$this->form_validation->set_rules('cpassword', 'cPassword', 'trim|strip_tags|required');
		if ($this->form_validation->run() == false) {
			echo json_encode(array("statusCode" => 201, "msg" => 'Password Required!'));
		} else {
			$email = $this->security->xss_clean($this->input->post('email'));
			$password = $this->security->xss_clean($this->input->post('password'));
			$cpassword = $this->security->xss_clean($this->input->post('cpassword'));
			$email = $this->security->xss_clean($this->input->post('email'));
			$semail = $this->session->userdata('newPassword');
			if($email == $semail['email']){

				if($password == $cpassword){

					$data = [
						'password' => $this->security->xss_clean(password_hash($cpassword, PASSWORD_BCRYPT)),
					];
					if ($this->Curd_model->update('tbl_login', ['email' => $email], $data ) == true) {
						$this->session->unset_userdata('newPassword');

						$admin = $this->Curd_model->getByUsername($email);
						$adminArray['id'] = $admin->id;
					    $this->session->set_userdata('LoginSession', $adminArray);

						$masg = 'Hi,' . ucfirst($admin->name) . '<br> Your password has been changed';

						emialSent('You can login', $masg, $admin->email, 'Successfully Change Password');

						echo json_encode(array("statusCode" => 200, "msg" => 'Successfully Update Password', "url" => base_url() . 'admin/index'));

					} else {
						echo json_encode(array("statusCode" => 201, "msg" => 'Server Error'));
					}


				}else{
					echo json_encode(array("statusCode" => 201, "msg" => 'Passwords Do Not Much!'));
				}

			}else{
				echo json_encode(array("statusCode" => 201, "msg" => 'Session Expired Please Try Again!'));
			}

		}

	}

	public function logout()
	{
		$this->session->unset_userdata('LoginSession');
		$array_msg = array('msg' => 'Logout Successfully', 'icon' => 'success');
		$this->session->set_flashdata($array_msg);
		redirect(base_url());
	}



}
