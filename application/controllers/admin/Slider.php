<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Slider extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->load->model('Curd_model');
		authValidate();
    }


    public function add()
    {
        $data['title'] = 'Slider Add';
        $this->load->view('admin/slider-add', $data);
    }
    public function list()
    {
        $data['SliderArray'] = $this->Curd_model->Select('tbl_slider');
        $data['title'] = 'Slider List';
        $this->load->view('admin/slider-list', $data);
    }


    public function sliderAdd()
    {
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('affiliatelink', 'Affiliate Link', 'trim|strip_tags|required');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Slider Add';
            $this->load->view('admin/slider-add', $data);
        } else {
            $config['upload_path']          = 'uploads/slider/';
            $config['allowed_types']        = 'jpeg|jpg|png';
            $config['max_size']             = 2000;
            $config['encrypt_name'] = TRUE;
            // $config['max_width']            = 1024;
            // $config['max_height']           = 768;

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('file')) {
                $array_msg = array('msg' => 'Upload is larger than the permitted size', 'icon' => 'error');
                $this->session->set_flashdata($array_msg);
                $data['title'] = 'Slider Add';
                $this->load->view('admin/slider-add', $data);
            } else {
                $photo = array('upload_data' => $this->upload->data());
                $data['images'] = $photo['upload_data']['file_name'];
                $data['ahref'] =     $this->security->xss_clean($this->input->post('affiliatelink'));

                if ($this->Curd_model->insert('tbl_slider', $data) == false) {
                    $array_msg = array('msg' => 'Server Error!', 'icon' => 'error');
                    $this->session->set_flashdata($array_msg);
                    redirect(base_url('admin/slider/add'));
                } else {
                    $array_msg = array('msg' => 'Successfully Create!', 'icon' => 'success');
                    $this->session->set_flashdata($array_msg);
                    redirect(base_url('admin/slider/list'));
                }
            }
        }
    }

    public function edit($id)
    {
        $data['title'] = 'Slider Edit';
        $data['SliderArray'] = $this->Curd_model->Select('tbl_slider', ['id' => $id]);
        $this->load->view('admin/slider-edit', $data);
    }

    public function editcode()
    {
        $id = $this->security->xss_clean($this->input->post('id'));
        if (empty($_FILES['file']['name'])) {
            $data['images'] =     $this->security->xss_clean($this->input->post('oldphoto'));
        } else {
            if($this->input->post('oldphoto') != 'default.png'){
                unlink("uploads/slider/" . $this->input->post('oldphoto'));
            }
            $config['upload_path']          = 'uploads/slider/';
            $config['allowed_types']        = 'jpeg|jpg|png';
            $config['max_size']             = 2000;
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('file')) {
                $array_msg = array('msg' => 'Upload is larger than the permitted size', 'icon' => 'error');
                $this->session->set_flashdata($array_msg);
                redirect(base_url('admin/slider/edit/' . $id));
            } else {
                $photo = array('upload_data' => $this->upload->data());
                $data['images'] = $photo['upload_data']['file_name'];
            }
        }
        $data['ahref'] =  $this->security->xss_clean($this->input->post('affiliatelink'));
        if ($this->Curd_model->update('tbl_slider', ['id' => $id], $data) == false) {
            $array_msg = array('msg' => 'Server Error!', 'icon' => 'error');
            $this->session->set_flashdata($array_msg);
            redirect(base_url('admin/slider/list'));
        } else {
            $array_msg = array('msg' => 'Successfully Create!', 'icon' => 'success');
            $this->session->set_flashdata($array_msg);
            redirect(base_url('admin/slider/list'));
        }
    }
    public function delete()
    {
        $id = $this->input->post('delete_id');
        $array = $this->Curd_model->Select('tbl_slider', ['id' => $id]);

        if (empty($array)) {
            echo json_encode(array("statusCode" => 201, "msg" => 'Data not found'));
        } else {
            if ($this->Curd_model->Delete('tbl_slider', ['id' => $id]) == false) {
                echo json_encode(array("statusCode" => 201, "msg" => 'Server Error!'));
            } else {
                unlink("uploads/slider/" . $array[0]['images']);
                echo json_encode(array("statusCode" => 200, "msg" => 'Deleted Successfully!'));
            }
        }
    }

    public function Status()
    {
        $id = $this->input->get('id');

        $statusInput = $this->input->get('status');
        if ($statusInput == 'Show') {
            $status = 'Hide';
        } else {
            $status = 'Show';
        }

        if ($this->Curd_model->update('tbl_slider', ['id' => $id], ['status' => $status]) == true) {
            $array_msg = array('msg' => 'Successfully Update!', 'icon' => 'success');
            $this->session->set_flashdata($array_msg);
            redirect(base_url('admin/slider/list'));
        } else {
            $array_msg = array('msg' => 'Server Error!', 'icon' => 'error');
            $this->session->set_flashdata($array_msg);
            redirect(base_url('admin/slider/list'));
        }
    }
}
