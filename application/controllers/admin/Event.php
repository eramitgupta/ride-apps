<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Event extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Curd_model');
        authValidate();
    }

    public function add()
    {
        $data['title'] = 'Event Add';
        $this->load->view('admin/event-add', $data);
    }
    public function list()
    {
        $data['title'] = 'Event List';
        $data['EventArray'] =  $this->Curd_model->Select('tbl_event');
        $this->load->view('admin/event-list', $data);
    }
    public function event_dd()
    {
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('title', 'Title', 'trim|strip_tags|required');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|strip_tags|required');
        $this->form_validation->set_rules('dsc', 'Description', 'trim|strip_tags|required');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Event Add';
            $this->load->view('admin/event-add', $data);
        } else {

            $config['upload_path']          = 'uploads/event/';
            $config['allowed_types']        = 'jpeg|jpg|png';
            $config['max_size']             = 2000;
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('file')) {
                $array_msg = array('msg' => 'Upload is larger than the permitted size', 'icon' => 'error');
                $this->session->set_flashdata($array_msg);
                $data['title'] = 'Event Add';
                $this->load->view('admin/event-add', $data);
            } else {
                $photo = array('upload_data' => $this->upload->data());
                $evpic = $photo['upload_data']['file_name'];

                $data = [
                    'title' => $this->security->xss_clean($this->input->post('title')),
                    'subject' => $this->security->xss_clean($this->input->post('subject')),
                    'dsc' => $this->security->xss_clean($this->input->post('dsc')),
                    'photo' => $evpic,
                    'date_time' => date('Y-m-d h:i:s A'),
                ];

                if ($this->Curd_model->insert('tbl_event',$data) == true) {
                    $array_msg = array('msg' => 'Successfully Create!', 'icon' => 'success');
                    $this->session->set_flashdata($array_msg);
                    redirect(base_url('admin/event/list'));
                } else {
                    $array_msg = array('msg' => 'Server Error!', 'icon' => 'error');
                    $this->session->set_flashdata($array_msg);
                    redirect(base_url('admin/event/list'));
                }

            }

        }
    }


    public function status()
    {
        $id = $this->input->get('id');
        $statusInput = $this->input->get('status');
        if ($statusInput == 'Show') {
            $status = 'Hide';
        } else {
            $status = 'Show';
        }
        if ($this->Curd_model->update('tbl_event', ['id' => $id], ['status' => $status]) == true) {
            $array_msg = array('msg' => 'Successfully Update!', 'icon' => 'success');
            $this->session->set_flashdata($array_msg);
           redirect(base_url('admin/event/list'));
        } else {
            $array_msg = array('msg' => 'Server Error!', 'icon' => 'error');
            $this->session->set_flashdata($array_msg);
           redirect(base_url('admin/event/list'));
        }

    }

    public function edit($id)
    {
        $data['title'] = 'Event Edit';
        $data['EventArray'] = $this->Curd_model->Select('tbl_event', ['id' => $id]);
        $this->load->view('admin/event-edit', $data);
    }

    public function editcode(){
        $id = $this->security->xss_clean($this->input->post('id'));

        if (empty($_FILES['file']['name'])) {
            $photoNew =     $this->security->xss_clean($this->input->post('oldphoto'));
        } else {
            if($this->input->post('oldphoto') != 'default.png'){
                unlink("uploads/event/" . $this->input->post('oldphoto'));
            }
            $config['upload_path']          = 'uploads/event/';
            $config['allowed_types']        = 'jpeg|jpg|png';
            $config['max_size']             = 2000;
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('file')) {
                $array_msg = array('msg' => 'Upload is larger than the permitted size', 'icon' => 'error');
                $this->session->set_flashdata($array_msg);
                redirect(base_url('admin/event/edit/' . $id));
            } else {
                $photo = array('upload_data' => $this->upload->data());
                $photoNew = $photo['upload_data']['file_name'];
            }
        }

        $data = [
            'title' => $this->security->xss_clean($this->input->post('title')),
            'subject' => $this->security->xss_clean($this->input->post('subject')),
            'dsc' => $this->security->xss_clean($this->input->post('dsc')),
            'photo' => $photoNew,
        ];

        if ($this->Curd_model->update('tbl_event', ['id' => $id], $data) == false) {
            $array_msg = array('msg' => 'Server Error!', 'icon' => 'error');
            $this->session->set_flashdata($array_msg);
            redirect(base_url('admin/event/list'));
        } else {
            $array_msg = array('msg' => 'Successfully Update!', 'icon' => 'success');
            $this->session->set_flashdata($array_msg);
            redirect(base_url('admin/event/list'));
        }
    }

    public function delete()
    {
        $id = $this->input->post('delete_id');
        $array = $this->Curd_model->Select('tbl_event', ['id' => $id]);

        if (empty($array)) {
            echo json_encode(array("statusCode" => 201, "msg" => 'Data not found'));
        } else {
            if ($this->Curd_model->Delete('tbl_event', ['id' => $id]) == false) {
                echo json_encode(array("statusCode" => 201, "msg" => 'Server Error!'));
            } else {
                unlink("uploads/event/" . $array[0]['photo']);
                echo json_encode(array("statusCode" => 200, "msg" => 'Deleted Successfully!'));
            }
        }
    }

    public function register($id)
    {
        $data['title'] = 'Event Register List';

        $qey = [
			'select' => 'tbl_event_register.*,tbl_event.* , tbl_event.id as EventID, tbl_event.date_time as EventDate',
			'from' => 'tbl_event_register',
			'join1' => 'tbl_event',
			'join2' => 'tbl_event_register.event_id = tbl_event.id',
            'where' => 'tbl_event_register.event_id = ' . $id . '',
		];

		$data['EventArray'] = $this->Curd_model->SelectDataJoin($qey, $qey['where']);
        $this->load->view('admin/event-register-list', $data);
    }


}
