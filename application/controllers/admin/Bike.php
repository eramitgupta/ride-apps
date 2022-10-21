<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Bike extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Curd_model');
        LoginAuthValidate();
    }

    public function add()
    {
        $this->load->view('admin/bike-add');
    }
    public function list()
    {
        $date['ArrayBike'] = $this->Curd_model->Select('tbl_bike');
        $this->load->view('admin/bike-list',$date);
    }

    public function bikeAdd()
    {
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('bikeModel', 'Bike Model', 'trim|strip_tags|required');
        $this->form_validation->set_rules('bikeName', 'Bike Name', 'trim|strip_tags|required');
        if ($this->form_validation->run() == false) {
            $this->load->view('admin/bike-add');
        } else {


            $date = [
                'model' => $this->security->xss_clean($this->input->post('bikeModel')),
                'brand' => $this->security->xss_clean($this->input->post('bikeName')),
                'date' => date('Y-m-d h:i:s A'),
            ];

            if ($this->Curd_model->insert('tbl_bike',$date) == false) {
                $array_msg = array('msg' => 'Server Error!', 'icon' => 'error');
                $this->session->set_flashdata($array_msg);
                redirect(base_url('admin/bike/add'));
            } else {
                $array_msg = array('msg' => 'Successfully Create!', 'icon' => 'success');
                $this->session->set_flashdata($array_msg);
                redirect(base_url('admin/bike/list'));
            }
        }
    }

    public function edit($id)
	{
		$data['ArrayBike'] = $this->Curd_model->Select('tbl_bike',['id'=>$id]);
		$this->load->view('admin/bike-edit', $data);
	}
    public function editBike()
	{
        $array = $this->security->xss_clean($this->input->post());
		if ($this->Curd_model->update('tbl_bike', ['id' => $array['id']], $array) == true) {
			$array_msg = array('msg' => 'Successfully Update!', 'icon' => 'success');
			$this->session->set_flashdata($array_msg);
			  redirect(base_url('admin/bike/list'));
		} else {
			$array_msg = array('msg' => 'Server Error!', 'icon' => 'error');
			$this->session->set_flashdata($array_msg);
			  redirect(base_url('admin/bike/list'));
		}
	}

    public function delete()
	{
		$id = $this->input->post('delete_id');
		$array = $this->Curd_model->Select('tbl_bike',['id'=>$id]);
		if (empty($array)) {
			echo json_encode(array("statusCode" => 201, "msg" => 'Data not found'));
		}
		if ($this->Curd_model->Delete('tbl_bike',['id'=>$id]) == false) {
			echo json_encode(array("statusCode" => 201, "msg" => 'Server Error!'));
		} else {
			echo json_encode(array("statusCode" => 200, "msg" => 'Deleted Successfully!'));
		}
	}


}
