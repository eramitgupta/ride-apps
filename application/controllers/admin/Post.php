<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Post extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
        $this->load->model('Curd_model');
        LoginAuthValidate();
	}

    public function list()
	{
		// 'tbl_login', 'tbl_create_ride.user_id = tbl_login.id'
		$qey = [
			'select' => 'tbl_create_ride.*,tbl_login.name',
			'from' => 'tbl_create_ride',
			'join1' => 'tbl_login',
			'join2' => 'tbl_create_ride.user_id = tbl_login.id',
		];
		$array = $this->Curd_model->SelectDataJoin($qey);
		$data['PostArray'] = $array;
		$this->load->view('admin/post-list', $data);
	}


    public function GetViewPost()
	{
		$id = $this->input->post('id');
		$qey = [
			'select' => 'tbl_create_ride.*,tbl_login.name',
			'from' => 'tbl_create_ride',
			'join1' => 'tbl_login',
			'join2' => 'tbl_create_ride.user_id = tbl_login.id',
			'where' => 'tbl_create_ride.id = ' . $id . '',
		];

		$array = $this->Curd_model->SelectDataJoin($qey, $qey['where']);
		$arrayLike = $this->Curd_model->CountsData('tbl_likes', ['post_id' => $array[0]['id']]);
		$arrayComment = $this->Curd_model->CountsData('tbl_ride_comment', ['post_id' => $array[0]['id']]);
		$arrayShare = $this->Curd_model->CountsData('tbl_share_post', ['post_id' => $array[0]['id']]);

		echo '<table class="table align-middle table-nowrap">
					<thead>
							<tr>
								<th colspan="10">Description</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td  colspan="10">' . $array[0]['text'] . '</td>
							</tr>
						</tbody>
						<thead>
							<tr>
								<th scope="col">Name</th>
								<th scope="col">Image</th>
								<th scope="col">Location</th>
								<th scope="col">Feeling</th>
								<th scope="col">Activity</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>' . $array[0]['name'] . '</td>
								<td>' . $array[0]['image'] . '</td>
								<td>' . $array[0]['location'] . '</td>
								<td>' . $array[0]['feeling'] . '</td>
								<td>' . $array[0]['activity'] . '</td>

							</tr>
						</tbody>
						<thead>
							<tr>
								<th scope="col">Spinner</th>
								<th scope="col">Shere With</th>
								<th scope="col">Date Time</th>
								<th scope="col">Status</th>
								<th scope="col">Delete Status</th>
							</tr>
						</thead>
						<tbody>
						<tr>
							<td>' . $array[0]['spinner'] . '</td>
							<td>' . $array[0]['sherewith'] . '</td>
							<td>' . $array[0]['date_time'] . '</td>
							<td>' . $array[0]['status'] . '</td>
							<td>' . $array[0]['delete_status'] . '</td>
						</tr>
						<thead>
							<tr>
								<th colspan="2">Like</th>
								<th colspan="2">Comment</th>
								<th colspan="2">Share</th>
							</tr>
						</thead>
						<tbody>
						<tr>
							<td colspan="2">' . $arrayLike . '</td>
							<td colspan="2">' . $arrayComment . '</td>
							<td colspan="2">' . $arrayShare . '</td>
						</tr>
					</tbody>
				</table>
			';
	}

	public function PostStatus()
	{
		$id = $this->input->get('id');

		$statusInput = $this->input->get('status');
		if ($statusInput == 'Show') {
			$status = 'Hide';
		} else {
			$status = 'Show';
		}
		if ($this->Curd_model->update('tbl_create_ride', ['id' => $id], ['status' => $status]) == true) {
			$array_msg = array('msg' => 'Successfully Update!', 'icon' => 'success');
			$this->session->set_flashdata($array_msg);
			redirect(base_url('admin/post/list'));
		} else {
			$array_msg = array('msg' => 'Server Error!', 'icon' => 'error');
			$this->session->set_flashdata($array_msg);
			redirect(base_url('admin/post/list'));
		}
	}

	public function PostStatusDelete()
	{
		$id = $this->input->get('id');

		$statusInput = $this->input->get('status');
		if ($statusInput == 'Yes') {
			$status = 'No';
		} else {
			$status = 'Yes';
		}
		if ($this->Curd_model->update('tbl_create_ride', ['id' => $id], ['delete_status' => $status]) == true) {
			$array_msg = array('msg' => 'Successfully Update!', 'icon' => 'success');
			$this->session->set_flashdata($array_msg);
			redirect(base_url('admin/post/list'));
		} else {
			$array_msg = array('msg' => 'Server Error!', 'icon' => 'error');
			$this->session->set_flashdata($array_msg);
			redirect(base_url('admin/post/list'));
		}
	}
    public function comment_view($id)
	{
		$array = $this->Curd_model->JoinTable($id);
		$data['ArrayComment'] = $array;
		$this->load->view('admin/comment-view', $data);
	}




}
