<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';

class AppApi extends RestController
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Api_model');
  }

  public function LoginDataGet_get()
  {
    $response = $this->Api_model->GetData('tbl_login', $this->input->get('id'));
    if (!empty($response)) {
      if (!empty($response['photo'])) {
        $response['image'] = base_url() . 'uploads/user/' . $response['photo'];
        unset($response['photo']);
      }
      $this->response(array(
        "status" => 200,
        "message" => "Login Data found",
        "data" => $response
      ), RestController::HTTP_OK);
    } else {
      $this->response(array(
        "status" => 201,
        "message" => "Not found",
      ), RestController::HTTP_NOT_FOUND);
    }
  }

  public function Signup_post()
  {
    // form validation for inputs
    $this->form_validation->set_rules('name', 'Name', 'trim|strip_tags|required');
    $this->form_validation->set_rules('username', 'Username', 'trim|strip_tags|required|is_unique[tbl_login.username]');
    $this->form_validation->set_rules('mobile', 'Mobile', 'trim|strip_tags|required|numeric|min_length[10]|is_unique[tbl_login.mobile]');

    // checking form submittion have any error or not
    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {

      $config['upload_path']          = 'uploads/user/';
      $config['allowed_types']        = 'jpeg|jpg|png';
      $config['max_size']             = 2000;
      $config['encrypt_name'] = TRUE;
      $this->load->library('upload', $config);

      if (!$this->upload->do_upload('file')) {
        $this->response(array(
          "status" => 201,
          "message" => 'Please select a valid image file',
        ), RestController::HTTP_NOT_FOUND);
      } else {
        $photo = array('upload_data' => $this->upload->data());
        $filePhoto = $photo['upload_data']['file_name'];

        $data = [
          'role' => 'user',
          'name' => $this->security->xss_clean($this->input->post('name')),
          'username' => $this->security->xss_clean($this->input->post('username')),
          'mobile' => $this->security->xss_clean($this->input->post('mobile')),
          'photo' => $this->security->xss_clean($filePhoto),
          'date' => date('Y-m-d h:i:s A'),
        ];
      }
      $id = $this->Api_model->insertLastId('tbl_login', $data);
      if (!empty($id)) {
        $responseData = $this->Api_model->GetData('tbl_login', $id);
        $this->response(array(
          "status" => 200,
          "message" => 'Successfully Sign Up',
          "data" => $responseData
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => 'Server Error',
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }


  public function googleWith_post()
  {
    // form validation for inputs
    $this->form_validation->set_rules('name', 'Name', 'trim|strip_tags|required');
    $this->form_validation->set_rules('email', 'Email', 'trim|strip_tags|required|is_unique[tbl_login.email]');
    $this->form_validation->set_rules('photo_url', 'Photo URL', 'trim|strip_tags|required|is_unique[tbl_login.email]');

    $Arr = $this->Api_model->SelectData('tbl_login', ['email' => $this->input->post('email')]);
    // checking form submittion have any error or not
    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
        "data" => $Arr,
      ), RestController::HTTP_NOT_FOUND);
    } else {

      $data = [
        'role' => 'user',
        'name' => $this->security->xss_clean($this->input->post('name')),
        'username' => $this->security->xss_clean($this->input->post('username')),
        'email' => $this->security->xss_clean($this->input->post('email')),
        'photo_url' => $this->security->xss_clean($this->input->post('photo_url')),
        'date' => date('Y-m-d h:i:s A'),
      ];

      if (!empty($this->Api_model->insertLastId('tbl_login', $data))) {
        $this->response(array(
          "status" => 200,
          "message" => 'Successfully Insert',
          "data" => $this->Api_model->insertLastId('tbl_login', $data),
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => 'Server Error',
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }


  public function Login_post()
  {
    $this->form_validation->set_rules('username', 'Username OR Mobile', 'trim|strip_tags|required');
    if ($this->form_validation->run() == FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {
      $username = $this->security->xss_clean($this->input->post('username'));
      $res = $this->Api_model->getRow('tbl_login', $username);
      if (!empty($res)) {
        $this->Api_model->multipleDelete('tbl_otp', ['sent' => $res[0]['mobile']]);
        $OTP = str_pad(rand(0, pow(10, 4) - 1), 4, '0', STR_PAD_LEFT);
        $data = [
          'otp' => $OTP,
          'sent' => $res[0]['mobile'],
          'date_time' => date('Y-m-d h:i:s A'),
        ];
        $this->Api_model->insert('tbl_otp', $data);
        $this->response(array(
          "status" => 200,
          "otp sent by" => $res[0]['mobile'],
          "message" => 'OTP Sent Successfully',
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => 'Please Enter Valid Details',
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }


  public function loginVerify_post()
  {
    $this->form_validation->set_rules('username', 'Username OR Mobile', 'trim|strip_tags|required');
    $this->form_validation->set_rules('otp', 'OTP', 'trim|strip_tags|required');
    if ($this->form_validation->run() == FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {
      $username = $this->security->xss_clean($this->input->post('username'));
      $otp = $this->security->xss_clean($this->input->post('otp'));
      $res = $this->Api_model->getRow('tbl_login', $username);
      if (!empty($res)) {
        $otpArray = $this->Api_model->SelectData('tbl_otp', ['sent' => $res[0]['mobile']]);
        if ($otpArray[0]['otp'] == $otp) {
          $this->Api_model->multipleDelete('tbl_otp', ['sent' => $res[0]['mobile']]);
          $this->response(array(
            "status" => 200,
            "message" => 'OTP Verify Successfully',
            "data" => $res,
          ), RestController::HTTP_OK);
        } else {
          $this->response(array(
            "status" => 201,
            "message" => 'Invalid OTP',
          ), RestController::HTTP_NOT_FOUND);
        }
      } else {
        $this->response(array(
          "status" => 201,
          "message" => 'Please Enter Valid Details',
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }

  public function otpResent_post()
  {
    $this->form_validation->set_rules('username', 'Username OR Mobile', 'trim|strip_tags|required');
    if ($this->form_validation->run() == FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {
      $username = $this->security->xss_clean($this->input->post('username'));
      $res = $this->Api_model->getRow('tbl_login', $username);
      if (!empty($res)) {
        $this->Api_model->multipleDelete('tbl_otp', ['sent' => $res[0]['mobile']]);
        $OTP = str_pad(rand(0, pow(10, 4) - 1), 4, '0', STR_PAD_LEFT);
        $data = [
          'otp' => $OTP,
          'sent' => $res[0]['mobile'],
          'date_time' => date('Y-m-d h:i:s A'),
        ];
        $this->Api_model->insert('tbl_otp', $data);
        $this->response(array(
          "status" => 200,
          "message" => 'OTP Sent Successfully',
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => 'Please Enter Valid Details',
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }


  public function otpList_post()
  {
    $data = $this->Api_model->SelectData('tbl_otp');
    if (empty($data)) {
      $this->response(array(
        "status" => 201,
        "message" => 'Not Found',
      ), RestController::HTTP_NOT_FOUND);
    } else {
      $this->response(array(
        "status" => 200,
        "message" => 'OTP Found',
        "data" => $data,
      ), RestController::HTTP_OK);
    }
  }

  public function ProfileEdit_post()
  {
    // form validation for inputs
    $this->form_validation->set_rules('id', 'Login ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('name', 'Name', 'trim|strip_tags|required');
    $this->form_validation->set_rules('email', 'Email', 'trim|strip_tags|required|valid_email');


    // checking form submittion have any error or not
    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</pre>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {
      $id = $this->security->xss_clean($this->input->post('id'));
      $responseData = $this->Api_model->GetData('tbl_login', $id);

      if (empty($_FILES['file']['name'])) {
        $filePhoto =     $this->security->xss_clean($this->input->post('oldprofile'));
      } else {
        $config['upload_path']          = 'uploads/user/';
        $config['allowed_types']        = 'jpeg|jpg|png';
        $config['max_size']             = 2000;
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file')) {
          $this->response(array(
            "status" => 201,
            "message" => 'Please select a valid image file',
          ), RestController::HTTP_NOT_FOUND);
        } else {
          if (!empty($responseData['photo'])) {
            if ($responseData['photo'] != 'default.png') {
              unlink("uploads/user/$responseData[photo]");
            }
          }
          $photo = array('upload_data' => $this->upload->data());
          $filePhoto = $photo['upload_data']['file_name'];
        }
      }
      $photo_url = $this->security->xss_clean($this->input->post('photo_url'));
      $data = [
        'role' => 'user',
        'name' => $this->security->xss_clean($this->input->post('name')),
        'gender' => $this->security->xss_clean($this->input->post('gender')),
        'aboutme' => $this->security->xss_clean($this->input->post('aboutme')),
        'bdy' => $this->security->xss_clean($this->input->post('bdy')),
        'location' => $this->security->xss_clean($this->input->post('location')),
        'timezone' => $this->security->xss_clean($this->input->post('timezone')),
        'language' => $this->security->xss_clean($this->input->post('language')),
        'notification' => $this->security->xss_clean($this->input->post('notification')),
        'family_member_name' => $this->security->xss_clean($this->input->post('family_member_name')),
        'contact_no' => $this->security->xss_clean($this->input->post('contact_no')),
        'email' => $this->security->xss_clean($this->input->post('email')),
        'photo' => $this->security->xss_clean($filePhoto),
        'photo_url' => $this->security->xss_clean($photo_url),
      ];
      if ($this->Api_model->Update("tbl_login", $id, $data) == true) {
        $this->response(array(
          "status" => 200,
          "message" => 'Update Successfully',
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => 'Server Error',
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }

  public function CreateRide_post()
  {
    $this->form_validation->set_rules('user_id', 'Login ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('location', 'Location', 'trim|strip_tags|required');
    $this->form_validation->set_rules('text', 'Text', 'trim|strip_tags|required');
    $this->form_validation->set_rules('feeling', 'Feeling', 'trim|strip_tags|required');
    $this->form_validation->set_rules('activity', 'Activity', 'trim|strip_tags|required');
    $this->form_validation->set_rules('spinner', 'Spinner', 'trim|strip_tags|required');
    $this->form_validation->set_rules('sherewith', 'Shere With', 'trim|strip_tags|required');


    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {

      $config['upload_path']          = 'uploads/create_ride/';
      $config['allowed_types']        = 'jpeg|jpg|png';
      $config['max_size']             = 2000;
      $config['encrypt_name'] = TRUE;
      $this->load->library('upload', $config);

      if (!$this->upload->do_upload('file')) {
        $this->response(array(
          "status" => 201,
          "message" => 'Please select a valid image file',
        ), RestController::HTTP_NOT_FOUND);
      } else {
        $photo = array('upload_data' => $this->upload->data());
        $filePhoto = $photo['upload_data']['file_name'];
      }



      $data = [
        'user_id' => $this->security->xss_clean($this->input->post('user_id')),
        'image' => $this->security->xss_clean($filePhoto),
        'location' => $this->security->xss_clean($this->input->post('location')),
        'text' => $this->security->xss_clean($this->input->post('text')),
        'feeling' => $this->security->xss_clean($this->input->post('feeling')),
        'activity' => $this->security->xss_clean($this->input->post('activity')),
        'spinner' => $this->security->xss_clean($this->input->post('spinner')),
        'sherewith' => $this->security->xss_clean($this->input->post('sherewith')),
        'date_time' => date('Y-m-d h:i:s A'),
      ];

      if ($this->Api_model->insert('tbl_create_ride', $data) == true) {
        $this->response(array(
          "status" => 200,
          "message" => 'Successfully Create',
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => 'Server Error',
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }

  public function RideEdit_post()
  {
    $this->form_validation->set_rules('id', 'ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('user_id', 'Login ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('location', 'Location', 'trim|strip_tags|required');
    $this->form_validation->set_rules('text', 'Text', 'trim|strip_tags|required');
    $this->form_validation->set_rules('feeling', 'Feeling', 'trim|strip_tags|required');
    $this->form_validation->set_rules('activity', 'Activity', 'trim|strip_tags|required');
    $this->form_validation->set_rules('spinner', 'Spinner', 'trim|strip_tags|required');
    $this->form_validation->set_rules('sherewith', 'Shere With', 'trim|strip_tags|required');

    $id = $this->security->xss_clean($this->input->post('id'));
    $responseData = $this->Api_model->GetData('tbl_create_ride', $id);

    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {

      $config['upload_path']          = 'uploads/create_ride/';
      $config['allowed_types']        = 'jpeg|jpg|png';
      $config['max_size']             = 2000;
      $config['encrypt_name'] = TRUE;
      $this->load->library('upload', $config);

      if (!$this->upload->do_upload('file')) {
        $this->response(array(
          "status" => 201,
          "message" => 'Please select a valid image file',
        ), RestController::HTTP_NOT_FOUND);
      } else {
        if (!empty($responseData['image'])) {
          if ($responseData['image'] != 'default.png') {
            unlink("uploads/create_ride/$responseData[image]");
          }
        }
        $photo = array('upload_data' => $this->upload->data());
        $filePhoto = $photo['upload_data']['file_name'];
      }

      $data = [
        'user_id' => $this->security->xss_clean($this->input->post('user_id')),
        'image' => $this->security->xss_clean($filePhoto),
        'location' => $this->security->xss_clean($this->input->post('location')),
        'text' => $this->security->xss_clean($this->input->post('text')),
        'feeling' => $this->security->xss_clean($this->input->post('feeling')),
        'activity' => $this->security->xss_clean($this->input->post('activity')),
        'spinner' => $this->security->xss_clean($this->input->post('spinner')),
        'sherewith' => $this->security->xss_clean($this->input->post('sherewith')),
        'date_time' => date('Y-m-d h:i:s A'),
      ];

      if ($this->Api_model->Update("tbl_create_ride", $id, $data) == true) {
        $this->response(array(
          "status" => 200,
          "message" => 'Successfully Update',
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => 'Server Error',
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }


  public function RideDelete_post()
  {
    $this->form_validation->set_rules('id', 'id', 'trim|strip_tags|required');

    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {

      $id = $this->security->xss_clean($this->input->post('id'));
      $responseData = $this->Api_model->GetData('tbl_create_ride', $id);
      if (empty($responseData)) {
        $this->response(array(
          "status" => 201,
          "message" => 'Not Found Data',
        ), RestController::HTTP_NOT_FOUND);
      } else {
        if ($this->Api_model->Delete("tbl_create_ride", $id) == true) {
          $this->response(array(
            "status" => 200,
            "message" => 'Delete Successfully',
          ), RestController::HTTP_OK);
        } else {
          $this->response(array(
            "status" => 201,
            "message" => 'Server Error',
          ), RestController::HTTP_NOT_FOUND);
        }
      }
    }
  }

  public function GetRide_post()
  {
    $this->form_validation->set_rules('user_id', 'User ID', 'trim|strip_tags|required');

    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {
      $user_id = $this->security->xss_clean($this->input->post('user_id'));
      $array = array('user_id' => $user_id);
      $response = $this->Api_model->SelectData('tbl_create_ride', $array);
      $rowCount = $this->Api_model->CountsData('tbl_create_ride', $array);
      if (!empty($response)) {

        for ($i = 0; $i < count($response); $i++) {
          if (!empty($response[$i]['image'])) {
            $response[$i]['photo'] = base_url() . 'uploads/create_ride/' . $response[$i]['image'];
            unset($response[$i]['image']);
          }
        }

        $this->response(array(
          "status" => 200,
          "message" => "Create Ride found",
          "total Create Ride" => $rowCount,
          "data" => $response,
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => "Not found",
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }

  public function Like_post()
  {
    $this->form_validation->set_rules('user_id', 'User ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('post_id', 'Post ID', 'trim|strip_tags|required');

    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {

      $data = [
        'user_id' => $this->security->xss_clean($this->input->post('user_id')),
        'post_id' => $this->security->xss_clean($this->input->post('post_id')),
        'date_time' => date('Y-m-d h:i:s A'),
      ];

      if ($this->Api_model->insert('tbl_likes', $data) == true) {
        $this->response(array(
          "status" => 200,
          "message" => 'Successfully Like',
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => 'Server Error',
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }

  public function Unlike_post()
  {
    $this->form_validation->set_rules('id', 'Like ID', 'trim|strip_tags|required');

    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {

      $id = $this->security->xss_clean($this->input->post('id'));
      $responseData = $this->Api_model->GetData('tbl_likes', $id);

      if (empty($responseData)) {
        $this->response(array(
          "status" => 201,
          "message" => 'Not Found Data',
        ), RestController::HTTP_NOT_FOUND);
      } else {
        if ($this->Api_model->Delete("tbl_likes", $id) == true) {
          $this->response(array(
            "status" => 200,
            "message" => 'Successfully Delete',
          ), RestController::HTTP_OK);
        } else {
          $this->response(array(
            "status" => 201,
            "message" => 'Server Error',
          ), RestController::HTTP_NOT_FOUND);
        }
      }
    }
  }

  public function GetLike_post()
  {
    $this->form_validation->set_rules('user_id', 'User ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('post_id', 'Post ID', 'trim|strip_tags|required');

    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {
      $user_id = $this->security->xss_clean($this->input->post('user_id'));
      $post_id = $this->security->xss_clean($this->input->post('post_id'));
      $array = array('user_id' => $user_id, 'post_id' => $post_id);
      $response = $this->Api_model->CountsData('tbl_likes', $array);
      if (!empty($response)) {
        $this->response(array(
          "status" => 200,
          "message" => "Like found",
          "data" => $response
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => "Not found",
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }

  public function CommentPost_post()
  {
    $this->form_validation->set_rules('user_id', 'Login ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('post_id', 'Post ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('comments', 'Comments', 'trim|strip_tags|required');

    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {

      $data = [
        'user_id' => $this->security->xss_clean($this->input->post('user_id')),
        'post_id' => $this->security->xss_clean($this->input->post('post_id')),
        'comments' => $this->security->xss_clean($this->input->post('comments')),
        'date_time' => date('Y-m-d h:i:s A'),
      ];

      if ($this->Api_model->insert('tbl_ride_comment', $data) == true) {
        $this->response(array(
          "status" => 200,
          "message" => 'Successfully Comment',
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => 'Server Error',
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }

  public function EditCommentPost_post()
  {
    $this->form_validation->set_rules('id', 'ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('comments', 'Comments', 'trim|strip_tags|required');

    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {

      $id = $this->security->xss_clean($this->input->post('id'));
      $responseData = $this->Api_model->GetData('tbl_ride_comment', $id);

      $data = [
        'comments' => $this->security->xss_clean($this->input->post('comments')),
      ];

      if ($this->Api_model->Update("tbl_ride_comment", $responseData['id'], $data) == true) {
        $this->response(array(
          "status" => 200,
          "message" => 'Successfully Update Comment',
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => 'Server Error',
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }

  public function DeleteComment_post()
  {
    $this->form_validation->set_rules('id', 'id', 'trim|strip_tags|required');

    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {

      $id = $this->security->xss_clean($this->input->post('id'));
      $responseData = $this->Api_model->GetData('tbl_ride_comment', $id);
      if (empty($responseData)) {
        $this->response(array(
          "status" => 201,
          "message" => 'Not Found Data',
        ), RestController::HTTP_NOT_FOUND);
      } else {
        if ($this->Api_model->Delete("tbl_ride_comment", $id) == true) {
          $this->response(array(
            "status" => 200,
            "message" => 'Delete Successfully',
          ), RestController::HTTP_OK);
        } else {
          $this->response(array(
            "status" => 201,
            "message" => 'Server Error',
          ), RestController::HTTP_NOT_FOUND);
        }
      }
    }
  }

  public function GetComment_post()
  {
    $this->form_validation->set_rules('user_id', 'User ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('post_id', 'Post ID', 'trim|strip_tags|required');

    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {

      $user_id = $this->security->xss_clean($this->input->post('user_id'));
      $post_id = $this->security->xss_clean($this->input->post('post_id'));
      $array = array('user_id' => $user_id, 'post_id' => $post_id);
      $response = $this->Api_model->SelectData('tbl_ride_comment', $array);
      $rowCount = $this->Api_model->CountsData('tbl_ride_comment', $array);
      if (!empty($response)) {
        $this->response(array(
          "status" => 200,
          "message" => "Comment found",
          "total comment" => $rowCount,
          "data" => $response,
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => "Not found",
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }

  public function SharePost_post()
  {
    $this->form_validation->set_rules('user_id', 'Login ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('post_id', 'Post ID', 'trim|strip_tags|required');

    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {

      $data = [
        'user_id' => $this->security->xss_clean($this->input->post('user_id')),
        'post_id' => $this->security->xss_clean($this->input->post('post_id')),
      ];

      if ($this->Api_model->insert('tbl_share_post', $data) == true) {
        $this->response(array(
          "status" => 200,
          "message" => 'Successfully Share Post',
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => 'Server Error',
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }

  public function GetSharePost_post()
  {
    $this->form_validation->set_rules('user_id', 'User ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('post_id', 'Post ID', 'trim|strip_tags|required');

    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {
      $user_id = $this->security->xss_clean($this->input->post('user_id'));
      $post_id = $this->security->xss_clean($this->input->post('post_id'));
      $array = array('user_id' => $user_id, 'post_id' => $post_id);
      $response = $this->Api_model->CountsData('tbl_share_post', $array);
      if (!empty($response)) {
        $this->response(array(
          "status" => 200,
          "message" => "Share Post found",
          "data" => $response
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => "Not found",
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }


  public function DeleteSharePost_post()
  {
    $this->form_validation->set_rules('id', 'id', 'trim|strip_tags|required');

    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {

      $id = $this->security->xss_clean($this->input->post('id'));
      $responseData = $this->Api_model->GetData('tbl_share_post', $id);
      if (empty($responseData)) {
        $this->response(array(
          "status" => 201,
          "message" => 'Not Found Data',
        ), RestController::HTTP_NOT_FOUND);
      } else {
        if ($this->Api_model->Delete("tbl_share_post", $id) == true) {
          $this->response(array(
            "status" => 200,
            "message" => 'Delete Successfully',
          ), RestController::HTTP_OK);
        } else {
          $this->response(array(
            "status" => 201,
            "message" => 'Server Error',
          ), RestController::HTTP_NOT_FOUND);
        }
      }
    }
  }

  public function friendRequest_post()
  {
    $this->form_validation->set_rules('user_id', 'User ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('friend_id', 'friend ID', 'trim|strip_tags|required');

    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {

      if ($this->input->post('user_id')  == $this->input->post('friend_id')) {
        $string = str_replace('</p>', '', validation_errors());
        $arrError = explode('<p>', $string);
        $this->response(array(
          "status" => 201,
          "message" => 'Do not send a friend request to yourself',
        ), RestController::HTTP_NOT_FOUND);
      } else {
        $data = [
          'user_id' => $this->security->xss_clean($this->input->post('user_id')),
          'frend_id' => $this->security->xss_clean($this->input->post('friend_id')),
          'date_time' => date('Y-m-d h:i:s A'),
        ];

        if ($this->Api_model->insert('tbl_friends_request', $data) == true) {
          $this->response(array(
            "status" => 200,
            "message" => 'friend Request Sent Successfully',
          ), RestController::HTTP_OK);
        } else {
          $this->response(array(
            "status" => 201,
            "message" => 'Server Error',
          ), RestController::HTTP_NOT_FOUND);
        }
      }
    }
  }


  public function friendsRequestConfirm_post()
  {
    $this->form_validation->set_rules('id', 'ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('user_id', 'User ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('friend_id', 'friend ID', 'trim|strip_tags|required');

    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {

      $id = $this->security->xss_clean($this->input->post('id'));
      $user_id = $this->security->xss_clean($this->input->post('user_id'));
      $friend_id = $this->security->xss_clean($this->input->post('friend_id'));

      $responseData = $this->Api_model->GetData('tbl_friends_request', $id);
      if (empty($responseData)) {
        $this->response(array(
          "status" => 201,
          "message" => 'Not Found Data',
        ), RestController::HTTP_NOT_FOUND);
      } else {
        $data = [
          'status' => 'Active',
        ];
        $array = array('id' => $id, 'user_id' => $user_id, 'frend_id' => $friend_id);
        if ($this->Api_model->UpdateArray('tbl_friends_request', $array, $data) == true) {
          $this->response(array(
            "status" => 200,
            "message" => "friend Request Confirm Successfully",
          ), RestController::HTTP_OK);
        } else {
          $this->response(array(
            "status" => 201,
            "message" => "Not found",
          ), RestController::HTTP_NOT_FOUND);
        }
      }
    }
  }


  public function friendsRequestDelete_post()
  {
    $this->form_validation->set_rules('friend_id', 'Friend ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('user_id', 'User ID', 'trim|strip_tags|required');

    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {

      $user_id = $this->security->xss_clean($this->input->post('user_id'));
      $friend_id = $this->security->xss_clean($this->input->post('friend_id'));
      $responseData = $this->Api_model->SelectData('tbl_friends_request', ['user_id' => $user_id, 'frend_id' => $friend_id]);
      if (empty($responseData)) {
        $this->response(array(
          "status" => 201,
          "message" => 'Not Found Data',
        ), RestController::HTTP_NOT_FOUND);
      } else {
        if ($this->Api_model->DeleteArray("tbl_friends_request", ['id' => $responseData[0]['id']]) == true) {
          $this->response(array(
            "status" => 200,
            "message" => 'Delete Successfully',
          ), RestController::HTTP_OK);
        } else {
          $this->response(array(
            "status" => 201,
            "message" => 'Server Error',
          ), RestController::HTTP_NOT_FOUND);
        }
      }
    }
  }

  public function friendList_post()
  {
    $this->form_validation->set_rules('user_id', 'User ID', 'trim|strip_tags|required');
    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</pre>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {
      $user_id = $this->security->xss_clean($this->input->post('user_id'));
      $response = $this->Api_model->FriendList($user_id);
      if (!empty($response)) {

        for ($i = 0; $i < count($response); $i++) {
          if (!empty($response[$i]['photo'])) {
            $response[$i]['image'] = base_url() . 'uploads/user/' . $response[$i]['photo'];
            unset($response[$i]['photo']);
          }
        }

        $this->response(array(
          "status" => 200,
          "message" => "Friend list found",
          "total Friend list found" => count($response),
          "data" => $response,
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => "Not found",
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }

  public function friendRequestList_post()
  {
    $this->form_validation->set_rules('user_id', 'User ID', 'trim|strip_tags|required');
    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {
      $user_id = $this->security->xss_clean($this->input->post('user_id'));
      $response = $this->Api_model->UserFriendList($user_id);
      if (!empty($response)) {

        for ($i = 0; $i < count($response); $i++) {
          if (!empty($response[$i]['photo'])) {
            $response[$i]['image'] = base_url() . 'uploads/user/' . $response[$i]['photo'];
            unset($response[$i]['photo']);
          }
        }

        $this->response(array(
          "status" => 200,
          "message" => "Friend request list found",
          "total friend request list found" => count($response),
          "data" => $response,
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => "Not found",
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }

  public function friendListSearch_post()
  {
    $this->form_validation->set_rules('search', 'Search...', 'trim|strip_tags|required');
    $this->form_validation->set_rules('user_id', 'User ID', 'trim|strip_tags|required');
    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</pre>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {
      $user_id = $this->security->xss_clean($this->input->post('user_id'));
      $search = $this->security->xss_clean($this->input->post('search'));
    }
  }


  public function friendBlockList_post()
  {
    $this->form_validation->set_rules('user_id', 'User ID', 'trim|strip_tags|required');
    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {
      $user_id = $this->security->xss_clean($this->input->post('user_id'));
      $array = array('user_id' => $user_id, 'status' => 'Block');
      $response = $this->Api_model->SelectData('tbl_friends_request', $array);
      $rowCount = $this->Api_model->CountsData('tbl_friends_request', $array);
      if (!empty($response)) {
        $this->response(array(
          "status" => 200,
          "message" => "Friend Block list found",
          "total friend Block list found" => $rowCount,
          "data" => $response,
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => "Not found",
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }

  public function groupList_post()
  {
    $this->form_validation->set_rules('user_id', 'User ID', 'trim|strip_tags|required');
    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {
      $user_id = $this->security->xss_clean($this->input->post('user_id'));
      $array = array('user_id' => $user_id);
      $response = $this->Api_model->SelectData('tbl_group', $array);
      $rowCount = $this->Api_model->CountsData('tbl_group', $array);
      if (!empty($response)) {
        $this->response(array(
          "status" => 200,
          "message" => "Group List found",
          "total Group List found" => $rowCount,
          "data" => $response,
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => "Not found",
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }

  public function userList_post()
  {
    $response = $this->Api_model->SelectData('tbl_login', ['role' => 'user']);
    $rowCount = $this->Api_model->CountsData('tbl_login', ['role' => 'user']);
    if (!empty($response)) {
      for ($i = 0; $i < count($response); $i++) {
        if (!empty($response[$i]['photo'])) {
          $response[$i]['image'] = base_url() . 'uploads/user/' . $response[$i]['photo'];
          unset($response[$i]['photo']);
        }
      }
      $this->response(array(
        "status" => 200,
        "message" => "User List found",
        "total User List found" => $rowCount,
        "data" => $response,
      ), RestController::HTTP_OK);
    } else {
      $this->response(array(
        "status" => 201,
        "message" => "Not found",
      ), RestController::HTTP_NOT_FOUND);
    }
  }

  public function groupCreate_post()
  {
    $this->form_validation->set_rules('user_id', 'User ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('name', 'Name', 'trim|strip_tags|required|is_unique[tbl_group.name]');
    $this->form_validation->set_rules('privacy', 'Privacy', 'trim|strip_tags|required');
    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {

      $data = [
        'user_id' => $this->security->xss_clean($this->input->post('user_id')),
        'name' => $this->security->xss_clean($this->input->post('name')),
        'privacy' => $this->security->xss_clean($this->input->post('privacy')),
        'date_time' => date('Y-m-d h:i:s A'),
      ];

      $id = $this->Api_model->insertLastId('tbl_group', $data);
      if (!empty($id)) {
        $data = [
          'group_id' => $this->security->xss_clean($id),
          'user_id' => $this->security->xss_clean($this->input->post('user_id')),
          'role' => $this->security->xss_clean('Admin'),
          'date_time' => date('Y-m-d h:i:s A'),
        ];

        if ($this->Api_model->insert('tbl_community_group_members', $data) == true) {
          $this->response(array(
            "status" => 200,
            "message" => 'Successfully Create Group',
          ), RestController::HTTP_OK);
        } else {
          $this->response(array(
            "status" => 201,
            "message" => 'Server Error',
          ), RestController::HTTP_NOT_FOUND);
        }
      } else {
        $this->response(array(
          "status" => 201,
          "message" => 'Server Error',
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }

  public function groupDelete_post()
  {
    $this->form_validation->set_rules('user_id', 'User ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('id', 'Group ID', 'trim|strip_tags|required');

    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {

      $array = array('id' => $this->input->post('id'), 'user_id' => $this->input->post('user_id'));
      $DataGet = $this->Api_model->AlreadyExists('tbl_group', $array);
      if (!empty($DataGet)) {
        $community_group_members = array('group_id' => $DataGet->id);
        if ($this->Api_model->multipleDelete("tbl_community_group_members", $community_group_members) == true) {
          $this->Api_model->multipleDelete("tbl_group", $array);
          $this->response(array(
            "status" => 200,
            "message" => 'Delete Successfully',
          ), RestController::HTTP_OK);
        } else {
          $this->response(array(
            "status" => 201,
            "message" => 'Server Error',
          ), RestController::HTTP_NOT_FOUND);
        }
      } else {
        $this->response(array(
          "status" => 201,
          "message" => 'Not Found Group',
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }

  public function groupMembersAdd_post()
  {
    $this->form_validation->set_rules('group_id', 'Group ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('user_id', 'User ID', 'trim|strip_tags|required');
    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {

      $data = [
        'group_id' => $this->security->xss_clean($this->input->post('group_id')),
        'user_id' => $this->security->xss_clean($this->input->post('user_id')),
        'date_time' => date('Y-m-d h:i:s A'),
      ];

      $array = array('group_id' => $this->input->post('group_id'), 'user_id' => $this->input->post('user_id'));
      if ($this->Api_model->AlreadyExists('tbl_community_group_members', $array) == true) {
        $this->response(array(
          "status" => 201,
          "message" => 'Already Exists Group Member',
        ), RestController::HTTP_NOT_FOUND);
      } else {
        if ($this->Api_model->insert('tbl_community_group_members', $data) == true) {
          $this->response(array(
            "status" => 200,
            "message" => 'Successfully Add Group Members',
          ), RestController::HTTP_OK);
        } else {
          $this->response(array(
            "status" => 201,
            "message" => 'Server Error',
          ), RestController::HTTP_NOT_FOUND);
        }
      }
    }
  }

  public function groupCommunityPost_post()
  {
    $this->form_validation->set_rules('user_id', 'User ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('group_id', 'Group ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('text', 'Text', 'trim|strip_tags|required');
    $this->form_validation->set_rules('tag', 'Tag', 'trim|strip_tags|required');
    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {

      $data = [];
      $count = count($_FILES['files']['name']);
      for ($i = 0; $i < $count; $i++) {
        if (!empty($_FILES['files']['name'][$i])) {
          $_FILES['file']['name'] = $_FILES['files']['name'][$i];
          $_FILES['file']['type'] = $_FILES['files']['type'][$i];
          $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
          $_FILES['file']['error'] = $_FILES['files']['error'][$i];
          $_FILES['file']['size'] = $_FILES['files']['size'][$i];

          $config['upload_path'] = 'uploads/group_post';
          $config['allowed_types'] = 'jpg|jpeg|png|gif';
          $config['max_size'] = '5000';
          $config['encrypt_name'] = TRUE;
          $config['file_name'] = $_FILES['files']['name'][$i];

          $this->load->library('upload', $config);

          if ($this->upload->do_upload('file')) {
            $uploadData = $this->upload->data();
            $filename = $uploadData['file_name'];

            $data['totalFiles'][] = $filename;
          }
        }
      }

      $dataInput = [
        'user_id' => $this->security->xss_clean($this->input->post('user_id')),
        'group_id' => $this->security->xss_clean($this->input->post('group_id')),
        'text' => $this->security->xss_clean($this->input->post('text')),
        'images' => json_encode($data['totalFiles']),
        'tag' => $this->security->xss_clean($this->input->post('tag')),
        'date_time' => date('Y-m-d h:i:s A'),
      ];

      if ($this->Api_model->insert('tbl_group_community_post', $dataInput) == true) {
        $this->response(array(
          "status" => 200,
          "message" => 'Successfully Post',
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => 'Server Error',
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }


  public function groupPostGet_post()
  {
    $this->form_validation->set_rules('user_id', 'User ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('group_id', 'Group ID', 'trim|strip_tags|required');
    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {
      $user_id = $this->security->xss_clean($this->input->post('user_id'));
      $group_id = $this->security->xss_clean($this->input->post('group_id'));
      $array = array('user_id' => $user_id, 'group_id' => $group_id);
      $response = $this->Api_model->SelectData('tbl_group_community_post', $array);
      $rowCount = $this->Api_model->CountsData('tbl_group_community_post', $array);
      if (!empty($response)) {
        $this->response(array(
          "status" => 200,
          "message" => "Group Post List found",
          "total Group Post List found" => $rowCount,
          "data" => $response,
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => "Not found",
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }


  public function groupDeletePost_post()
  {
    $this->form_validation->set_rules('id', 'id', 'trim|strip_tags|required');
    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {

      $id = $this->security->xss_clean($this->input->post('id'));
      $responseData = $this->Api_model->GetData('tbl_group_community_post', $id);
      if (empty($responseData)) {
        $this->response(array(
          "status" => 201,
          "message" => 'Not Found Data',
        ), RestController::HTTP_NOT_FOUND);
      } else {
        foreach (json_decode($responseData['images']) as  $value) {
          if (!empty($value)) {
            if ($value != 'default.png') {
              unlink("uploads/group_post/$value");
            }
          }
        }
        if ($this->Api_model->Delete("tbl_group_community_post", $id) == true) {
          $this->response(array(
            "status" => 200,
            "message" => 'Delete Successfully',
          ), RestController::HTTP_OK);
        } else {
          $this->response(array(
            "status" => 201,
            "message" => 'Server Error',
          ), RestController::HTTP_NOT_FOUND);
        }
      }
    }
  }


  public function createRideGroup_post()
  {
    $this->form_validation->set_rules('user_id', 'User ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('name', 'Name', 'trim|strip_tags|required');
    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {

      $dataInput = [
        'user_id' => $this->security->xss_clean($this->input->post('user_id')),
        'name' => $this->security->xss_clean($this->input->post('name')),
        'date_time' => date('Y-m-d h:i:s A'),
      ];

      if ($this->Api_model->insert('tbl_create_ride_group', $dataInput) == true) {
        $this->response(array(
          "status" => 200,
          "message" => 'Create Ride Group Successfully',
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => 'Server Error',
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }

  public function createRideGroupShare_post()
  {
    $this->form_validation->set_rules('user_id', 'User ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('group_id', 'Group ID', 'trim|strip_tags|required');
    if (empty($this->security->xss_clean($this->input->post('user_id')))) {
      $this->response(array(
        "status" => 201,
        "message" => 'Empty User ID',
      ), RestController::HTTP_NOT_FOUND);
      exit();
    } else {
      $user_id = $this->security->xss_clean($this->input->post('user_id'));
    }

    if (empty($this->security->xss_clean($this->input->post('frend_id')))) {
      $this->response(array(
        "status" => 201,
        "message" => 'Empty Frend ID ',
      ), RestController::HTTP_NOT_FOUND);
      exit();
    } else {
      $frend_id = $this->security->xss_clean($this->input->post('frend_id'));
    }

    if (empty($this->security->xss_clean($this->input->post('group_id')))) {
      $this->response(array(
        "status" => 201,
        "message" => 'Empty Group ID ',
      ), RestController::HTTP_NOT_FOUND);
      exit();
    } else {
      $group_id = $this->security->xss_clean($this->input->post('group_id'));
    }

    $array = array('user_id' => $user_id, 'status' => 'Active');
    $response = $this->Api_model->SelectData('tbl_friends_request', $array);

    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
        "data" => $response,
      ), RestController::HTTP_NOT_FOUND);
    } else {

      for ($i = 0; $i < count($frend_id); $i++) {
        $data = [
          'user_id' => $this->security->xss_clean($this->input->post('user_id')),
          'group_id' => $this->security->xss_clean($this->input->post('group_id')),
          'frend_id' => $frend_id[$i],
          'date_time' => date('Y-m-d h:i:s A'),
        ];
        $this->Api_model->insert('tbl_create_ride_group_share', $data);
      }
      $this->response(array(
        "status" => 200,
        "message" => 'Ride Group New Add Frend Successfully',
      ), RestController::HTTP_OK);
    }
  }

  public function createRideGroupPost_post()
  {
    $this->form_validation->set_rules('user_id', 'User ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('group_id', 'Group ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('text', 'Text', 'trim|strip_tags|required');
    $this->form_validation->set_rules('tag', 'Tag', 'trim|strip_tags|required');
    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {

      $data = [];
      $count = count($_FILES['files']['name']);
      for ($i = 0; $i < $count; $i++) {
        if (!empty($_FILES['files']['name'][$i])) {
          $_FILES['file']['name'] = $_FILES['files']['name'][$i];
          $_FILES['file']['type'] = $_FILES['files']['type'][$i];
          $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
          $_FILES['file']['error'] = $_FILES['files']['error'][$i];
          $_FILES['file']['size'] = $_FILES['files']['size'][$i];

          $config['upload_path'] = 'uploads/group_post';
          $config['allowed_types'] = 'jpg|jpeg|png|gif';
          $config['max_size'] = '15000';
          $config['encrypt_name'] = TRUE;
          $config['file_name'] = $_FILES['files']['name'][$i];

          $this->load->library('upload', $config);

          if ($this->upload->do_upload('file')) {
            $uploadData = $this->upload->data();
            $filename = $uploadData['file_name'];

            $data['totalFiles'][] = $filename;
          }
        }
      }

      $dataInput = [
        'user_id' => $this->security->xss_clean($this->input->post('user_id')),
        'group_id' => $this->security->xss_clean($this->input->post('group_id')),
        'text' => $this->security->xss_clean($this->input->post('text')),
        'images' => json_encode($data['totalFiles']),
        'tag' => $this->security->xss_clean($this->input->post('tag')),
        'date_time' => date('Y-m-d h:i:s A'),
      ];

      if ($this->Api_model->insert('tbl_create_ride_group_post', $dataInput) == true) {
        $this->response(array(
          "status" => 200,
          "message" => 'Successfully Post',
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => 'Server Error',
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }

  public function createRideGroupPostList_post()
  {
    $this->form_validation->set_rules('group_id', 'Group ID', 'trim|strip_tags|required');
    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {
      $response = $this->Api_model->SelectData('tbl_create_ride_group_post', ['group_id' => $this->input->post('group_id')]);
      $rowCount = $this->Api_model->CountsData('tbl_create_ride_group_post', ['group_id' => $this->input->post('group_id')]);
      if (!empty($response)) {
        $this->response(array(
          "status" => 200,
          "message" => "Create Ride Group Post List found",
          "total Create Ride Group Post List found" => $rowCount,
          "images Base Url path" => base_url('uploads/group_post/'),
          "data" => $response,
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => "Not found",
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }


  public function createRideGroupList_post()
  {
    $this->form_validation->set_rules('user_id', 'User ID', 'trim|strip_tags|required');

    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {

      $user_id =  $this->security->xss_clean($this->input->post('user_id'));

      $array = array('user_id' => $user_id);
      $response = $this->Api_model->SelectData('tbl_create_ride_group', $array);
      $rowCount = $this->Api_model->CountsData('tbl_create_ride_group', $array);
      if (empty($response)) {
        $this->response(array(
          "status" => 201,
          "message" => 'Not Found',
        ), RestController::HTTP_NOT_FOUND);
      } else {
        $this->response(array(
          "status" => 200,
          "message" => 'Ride Group Found ' . $rowCount,
          "data" => $response,
        ), RestController::HTTP_OK);
      }
    }
  }



  public function createRideGroupDelete_post()
  {
    $this->form_validation->set_rules('frend_id', 'Frend ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('group_id', 'Group ID', 'trim|strip_tags|required');

    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {
      $array = array('group_id' => $this->input->post('group_id'), 'frend_id' => $this->input->post('frend_id'));
      $DataGet = $this->Api_model->AlreadyExists('tbl_create_ride_group_share', $array);
      if (!empty($DataGet)) {
        $community_group_members = array('group_id' => $DataGet->group_id, 'frend_id' => $DataGet->frend_id);
        if ($this->Api_model->multipleDelete("tbl_create_ride_group_share", $community_group_members) == true) {
          $this->response(array(
            "status" => 200,
            "message" => 'Delete Successfully',
          ), RestController::HTTP_OK);
        } else {
          $this->response(array(
            "status" => 201,
            "message" => 'Server Error',
          ), RestController::HTTP_NOT_FOUND);
        }
      } else {
        $this->response(array(
          "status" => 201,
          "message" => 'Not Found Group Friend',
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }

  public function CreateRideGroupMembersAdd_post()
  {
    $this->form_validation->set_rules('group_id', 'Group ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('user_id', 'User ID', 'trim|strip_tags|required');
    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {

      $data = [
        'group_id' => $this->security->xss_clean($this->input->post('group_id')),
        'user_id' => $this->security->xss_clean($this->input->post('user_id')),
        'date_time' => date('Y-m-d h:i:s A'),
      ];

      $array = array('group_id' => $this->input->post('group_id'), 'user_id' => $this->input->post('user_id'));
      if ($this->Api_model->AlreadyExists('tbl_create_ride_group_members', $array) == true) {
        $this->response(array(
          "status" => 201,
          "message" => 'Already Exists Group Member',
        ), RestController::HTTP_NOT_FOUND);
      } else {
        if ($this->Api_model->insert('tbl_create_ride_group_members', $data) == true) {
          $this->response(array(
            "status" => 200,
            "message" => 'Successfully Add Group Members',
          ), RestController::HTTP_OK);
        } else {
          $this->response(array(
            "status" => 201,
            "message" => 'Server Error',
          ), RestController::HTTP_NOT_FOUND);
        }
      }
    }
  }

  public function CreateRideGroupMembersList_post()
  {
    $this->form_validation->set_rules('group_id', 'Group ID', 'trim|strip_tags|required');
    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {

      $array = [
        'select' => 'tbl_create_ride_group_members.*,tbl_login.name, tbl_login.photo, tbl_login.photo_url',
        'from' => 'tbl_create_ride_group_members',
        'join1' => 'tbl_login',
        'join2' => 'tbl_create_ride_group_members.user_id = tbl_login.id',
        'where' => 'tbl_create_ride_group_members.group_id = ' . $this->input->post('group_id') . '',
      ];

      $response = $this->Api_model->SelectDataJoin($array, $array['where']);
      if (!empty($response)) {
        for ($i = 0; $i < count($response); $i++) {
          if (!empty($response[$i]['photo'])) {
            $response[$i]['image'] = base_url() . 'uploads/user/' . $response[$i]['photo'];
            unset($response[$i]['photo']);
          }
        }
        $this->response(array(
          "status" => 200,
          "message" => "Create Ride Group Members List found",
          "data" => $response,
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => "Not found",
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }


  public function StoriesAdd_post()
  {
    $this->form_validation->set_rules('user_id', 'User ID', 'trim|strip_tags|required');

    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {

      if (empty($_FILES['files'])) {
        $this->response(array(
          "status" => 201,
          "message" => 'Photo required',
        ), RestController::HTTP_NOT_FOUND);
      } else {
        $data = [];
        $count = count($_FILES['files']['name']);
        for ($i = 0; $i < $count; $i++) {
          if (!empty($_FILES['files']['name'][$i])) {
            $_FILES['file']['name'] = $_FILES['files']['name'][$i];
            $_FILES['file']['type'] = $_FILES['files']['type'][$i];
            $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
            $_FILES['file']['error'] = $_FILES['files']['error'][$i];
            $_FILES['file']['size'] = $_FILES['files']['size'][$i];

            $config['upload_path'] = 'uploads/stories';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['max_size'] = '5000';
            $config['encrypt_name'] = TRUE;
            $config['file_name'] = $_FILES['files']['name'][$i];

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('file')) {
              $uploadData = $this->upload->data();
              $filename = $uploadData['file_name'];

              $data['totalFiles'][] = $filename;
            }
          }
        }
      }
      $dataInput = [
        'user_id' => $this->security->xss_clean($this->input->post('user_id')),
        'text' => $this->security->xss_clean($this->input->post('text')),
        'image' => json_encode($data['totalFiles']),
        'date' => date('Y-m-d h:i:s A'),
      ];

      if ($this->Api_model->insert('tbl_stories', $dataInput) == true) {
        $this->response(array(
          "status" => 200,
          "message" => 'Successfully Add Stories',
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => 'Server Error',
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }

  public function StoriesDelete_post()
  {
    $this->form_validation->set_rules('id', 'id', 'trim|strip_tags|required');
    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {
      $id = $this->security->xss_clean($this->input->post('id'));
      $responseData = $this->Api_model->GetData('tbl_stories', $id);
      if (empty($responseData)) {
        $this->response(array(
          "status" => 201,
          "message" => 'Not Found Data',
        ), RestController::HTTP_NOT_FOUND);
      } else {

        if (!empty(array_filter(json_decode($responseData['image'])))) {
          foreach (json_decode($responseData['image']) as  $value) {
            if (!empty($value)) {
              if ($value != 'default.png') {
                unlink("uploads/stories/$value");
              }
            }
          }
        }
        if ($this->Api_model->Delete("tbl_stories", $id) == true) {
          $this->response(array(
            "status" => 200,
            "message" => 'Delete Successfully',
          ), RestController::HTTP_OK);
        } else {
          $this->response(array(
            "status" => 201,
            "message" => 'Server Error',
          ), RestController::HTTP_NOT_FOUND);
        }
      }
    }
  }

  public function StoriesAll_post()
  {
    $array = [
      'select' => 'tbl_stories.*,tbl_login.name ',
      'from' => 'tbl_stories',
      'join1' => 'tbl_login',
      'join2' => 'tbl_stories.user_id = tbl_login.id',
    ];

    $response = $this->Api_model->SelectDataJoin($array);
    if (empty($response)) {
      $this->response(array(
        "status" => 201,
        "message" => 'Not Found',
      ), RestController::HTTP_NOT_FOUND);
    } else {
      $this->response(array(
        "status" => 200,
        "message" => 'Stories Found ',
        "data" => $response,
      ), RestController::HTTP_OK);
    }
  }


  public function timeZone_post()
  {
    $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
    foreach ($timezones as $value) {
      $data[] = $value;
    }
    if (empty($data)) {
      $this->response(array(
        "status" => 201,
        "message" => 'Not Found',
      ), RestController::HTTP_NOT_FOUND);
    } else {
      $this->response(array(
        "status" => 200,
        "message" => 'Time Zone Found',
        "data" => $data,
      ), RestController::HTTP_OK);
    }
  }


  public function fellDown_post()
  {
    $this->form_validation->set_rules('user_id', 'User ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('group_id', 'Group ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('location', 'Location', 'trim|strip_tags|required');
    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {

      $dataInput = [
        'user_id' => $this->security->xss_clean($this->input->post('user_id')),
        'group_id' => $this->security->xss_clean($this->input->post('group_id')),
        'location' => $this->security->xss_clean($this->input->post('location')),
        'date_time' => date('Y-m-d h:i:s A'),
      ];

      if ($this->Api_model->insert('tbl_fell_down', $dataInput) == true) {
        $this->response(array(
          "status" => 200,
          "response message" => 'Notifications Sent Successfully',
          "message" => 'Hi do you need any Help',
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => 'Server Error',
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }

  public function fellDownResponse_post()
  {
    $this->form_validation->set_rules('user_id', 'User ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('group_id', 'Group ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('fell_down_id', 'Fell Down ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('status', 'Auto / Manual', 'trim|strip_tags|required');
    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {

      $dataInput = [
        'user_id' => $this->security->xss_clean($this->input->post('user_id')),
        'fell_down_id' => $this->security->xss_clean($this->input->post('fell_down_id')),
        'date_time' => date('Y-m-d h:i:s A'),
        'status' => $this->security->xss_clean($this->input->post('status')),
      ];

      if ($this->Api_model->insert('tbl_fell_down_response', $dataInput) == true) {
        $this->response(array(
          "status" => 200,
          "response message" => 'Success',
          "message" => 'This Username needs Help',
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => 'Server Error',
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }

  public function beepResponse_post()
  {
    $this->form_validation->set_rules('user_id', 'User ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('fell_down_response_id', 'Fell Down Response ID', 'trim|strip_tags|required');
    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {
      $dataInput = [
        'user_id' => $this->security->xss_clean($this->input->post('user_id')),
        'fell_down_response_id' => $this->security->xss_clean($this->input->post('fell_down_response_id')),
        'date_time' => date('Y-m-d h:i:s A'),
      ];

      if ($this->Api_model->insert('tbl_beep_response', $dataInput) == true) {
        $this->response(array(
          "status" => 200,
          "message" => 'Successfully',
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => 'Server Error',
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }

  public function language_post()
  {
    $data = $this->Api_model->SelectData('tbl_languages');
    if (empty($data)) {
      $this->response(array(
        "status" => 201,
        "message" => 'Not Found',
      ), RestController::HTTP_NOT_FOUND);
    } else {
      $this->response(array(
        "status" => 200,
        "message" => 'Languages Found',
        "data" => $data,
      ), RestController::HTTP_OK);
    }
  }

  public function slider_post()
  {
    $data = $this->Api_model->SelectData('tbl_slider',  ['status' => 'Show']);
    if (empty($data)) {
      $this->response(array(
        "status" => 201,
        "message" => 'Not Found',
      ), RestController::HTTP_NOT_FOUND);
    } else {
      $this->response(array(
        "status" => 200,
        "message" => 'Slider Found',
        "data" => $data,
      ), RestController::HTTP_OK);
    }
  }

  public function eventList_post()
  {
    $data = $this->Api_model->SelectData('tbl_event',  ['status' => 'Show']);
    if (empty($data)) {
      $this->response(array(
        "status" => 201,
        "message" => 'Not Found',
      ), RestController::HTTP_NOT_FOUND);
    } else {
      $this->response(array(
        "status" => 200,
        "message" => 'Event Found',
        "data" => $data,
      ), RestController::HTTP_OK);
    }
  }

  public function envetRegister_post()
  {
    $this->form_validation->set_rules('user_id', 'User ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('event_id', 'Event ID', 'trim|strip_tags|required');
    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {
      $dataInput = [
        'user_id' => $this->security->xss_clean($this->input->post('user_id')),
        'event_id' => $this->security->xss_clean($this->input->post('event_id')),
        'date_time' => date('Y-m-d h:i:s A'),
      ];

      if ($this->Api_model->insert('tbl_event_register', $dataInput) == true) {
        $this->response(array(
          "status" => 200,
          "message" => 'Successfully',
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => 'Server Error',
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }

  public function challengesJoin_post()
  {
    $this->form_validation->set_rules('user_id', 'User ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('challenges_id', 'Challenges ID', 'trim|strip_tags|required');
    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {
      $dataInput = [
        'user_id' => $this->security->xss_clean($this->input->post('user_id')),
        'challenges_id' => $this->security->xss_clean($this->input->post('challenges_id')),
        'date_time' => date('Y-m-d h:i:s A'),
      ];

      if ($this->Api_model->insert('tbl_challenges_join', $dataInput) == true) {
        $this->response(array(
          "status" => 200,
          "message" => 'Successfully Create',
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => 'Server Error',
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }

  public function myGarage_post()
  {
    $this->form_validation->set_rules('user_id', 'User ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('name', 'Name', 'trim|strip_tags|required');
    $this->form_validation->set_rules('maker', 'Maker', 'trim|strip_tags|required');
    $this->form_validation->set_rules('model', 'Model', 'trim|strip_tags|required');
    $this->form_validation->set_rules('year', 'Year', 'trim|strip_tags|required');
    $this->form_validation->set_rules('type', 'Type', 'trim|strip_tags|required');
    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {

      if (empty($_FILES['files'])) {
        $this->response(array(
          "status" => 201,
          "message" => 'Photo required',
        ), RestController::HTTP_NOT_FOUND);
      } else {
        $data = [];
        $count = count($_FILES['files']['name']);
        for ($i = 0; $i < $count; $i++) {
          if (!empty($_FILES['files']['name'][$i])) {
            $_FILES['file']['name'] = $_FILES['files']['name'][$i];
            $_FILES['file']['type'] = $_FILES['files']['type'][$i];
            $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
            $_FILES['file']['error'] = $_FILES['files']['error'][$i];
            $_FILES['file']['size'] = $_FILES['files']['size'][$i];

            $config['upload_path'] = 'uploads/mygarage';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['max_size'] = '5000';
            $config['encrypt_name'] = TRUE;
            $config['file_name'] = $_FILES['files']['name'][$i];

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('file')) {
              $uploadData = $this->upload->data();
              $filename = $uploadData['file_name'];

              $data['totalFiles'][] = $filename;
            }
          }
        }
      }


      $dataInput = [
        'user_id' => $this->security->xss_clean($this->input->post('user_id')),
        'name' => $this->security->xss_clean($this->input->post('name')),
        'maker' => $this->security->xss_clean($this->input->post('maker')),
        'model' => $this->security->xss_clean($this->input->post('model')),
        'year' => $this->security->xss_clean($this->input->post('year')),
        'type' => $this->security->xss_clean($this->input->post('type')),
        'photo' => json_encode($data['totalFiles']),
        'date' => date('Y-m-d h:i:s A'),
      ];

      if ($this->Api_model->insert('tbl_my_garage', $dataInput) == true) {
        $this->response(array(
          "status" => 200,
          "message" => 'Successfully Add Garage',
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => 'Server Error',
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }

  public function myGarageList_post()
  {
    $qey = [
      'select' => 'tbl_my_garage.*,tbl_login.name',
      'from' => 'tbl_my_garage',
      'join1' => 'tbl_login',
      'join2' => 'tbl_my_garage.user_id = tbl_login.id',
    ];

    $data = $this->Api_model->SelectDataJoin($qey);
    if (empty($data)) {
      $this->response(array(
        "status" => 201,
        "message" => 'Not Found',
      ), RestController::HTTP_NOT_FOUND);
    } else {
      $this->response(array(
        "status" => 200,
        "message" => 'Event Found',
        "data" => $data,
      ), RestController::HTTP_OK);
    }
  }

  public function myGarageUpdate_post()
  {
    $this->form_validation->set_rules('id', 'ID', 'trim|strip_tags|required');
    $this->form_validation->set_rules('name', 'Name', 'trim|strip_tags|required');
    $this->form_validation->set_rules('maker', 'Maker', 'trim|strip_tags|required');
    $this->form_validation->set_rules('model', 'Model', 'trim|strip_tags|required');
    $this->form_validation->set_rules('year', 'Year', 'trim|strip_tags|required');
    $this->form_validation->set_rules('type', 'Type', 'trim|strip_tags|required');
    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {

      $dataInput = [
        'name' => $this->security->xss_clean($this->input->post('name')),
        'maker' => $this->security->xss_clean($this->input->post('maker')),
        'model' => $this->security->xss_clean($this->input->post('model')),
        'year' => $this->security->xss_clean($this->input->post('year')),
        'type' => $this->security->xss_clean($this->input->post('type')),
      ];
      $id = $this->security->xss_clean($this->input->post('id'));
      if ($this->Api_model->Update('tbl_my_garage', $id,  $dataInput) == true) {
        $this->response(array(
          "status" => 200,
          "message" => 'Successfully Update Garage',
        ), RestController::HTTP_OK);
      } else {
        $this->response(array(
          "status" => 201,
          "message" => 'Server Error',
        ), RestController::HTTP_NOT_FOUND);
      }
    }
  }


  public function myGarageDelete_post()
  {
    $this->form_validation->set_rules('id', 'id', 'trim|strip_tags|required');
    if ($this->form_validation->run() === FALSE) {
      $string = str_replace('</p>', '', validation_errors());
      $arrError = explode('<p>', $string);
      $this->response(array(
        "status" => 201,
        "message" => array_values(array_filter($arrError)),
      ), RestController::HTTP_NOT_FOUND);
    } else {

      $id = $this->security->xss_clean($this->input->post('id'));
      $responseData = $this->Api_model->GetData('tbl_my_garage', $id);
      if (empty($responseData)) {
        $this->response(array(
          "status" => 201,
          "message" => 'Not Found Data',
        ), RestController::HTTP_NOT_FOUND);
      } else {
        foreach (json_decode($responseData['photo']) as  $value) {
          if (!empty($value)) {
            if ($value != 'default.png') {
              unlink("uploads/mygarage/$value");
            }
          }
        }
        if ($this->Api_model->Delete("tbl_my_garage", $id) == true) {
          $this->response(array(
            "status" => 200,
            "message" => 'Delete Successfully',
          ), RestController::HTTP_OK);
        } else {
          $this->response(array(
            "status" => 201,
            "message" => 'Server Error',
          ), RestController::HTTP_NOT_FOUND);
        }
      }
    }
  }
}
