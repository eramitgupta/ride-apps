<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Community extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
        $this->load->model('Curd_model');
        LoginAuthValidate();
	}

	public function list()
	{
		$qey = [
			'select' => 'tbl_group.*,tbl_login.name, tbl_group.name as GroupName, tbl_group.id as GroupID, tbl_group.status as GroupStatus',
			'from' => 'tbl_group',
			'join1' => 'tbl_login',
			'join2' => 'tbl_group.user_id = tbl_login.id',
		];
		$data['ArrayGroup'] = $this->Curd_model->SelectDataJoin($qey);
		$this->load->view('admin/group-list', $data);
	}


	public function Status()
	{
		$id = $this->input->get('id');
		$statusInput = $this->input->get('status');
		if ($statusInput == 'Active') {
			$status = 'Deactivate';
		} else {
			$status = 'Active';
		}

		if ($this->Curd_model->update('tbl_group', ['id' => $id], ['status' => $status]) == true) {
			$array_msg = array('msg' => 'Successfully Update!', 'icon' => 'success');
			$this->session->set_flashdata($array_msg);
			redirect(base_url('admin/community/list'));
		} else {
			$array_msg = array('msg' => 'Server Error!', 'icon' => 'error');
			$this->session->set_flashdata($array_msg);
			redirect(base_url('admin/community/list'));
		}
	}

	public function StatusMembers()
	{
		$id = $this->input->get('id');
		$statusInput = $this->input->get('status');
		$GroupID = $this->input->get('GroupID');
		if ($statusInput == 'Active') {
			$status = 'Deactivate';
		} else {
			$status = 'Active';
		}

		if ($this->Curd_model->update('tbl_community_group_members', ['id' => $id], ['status' => $status]) == true) {
			$array_msg = array('msg' => 'Successfully Update!', 'icon' => 'success');
			$this->session->set_flashdata($array_msg);
			redirect(base_url('admin/community/members/' . $GroupID));
		} else {
			$array_msg = array('msg' => 'Server Error!', 'icon' => 'error');
			$this->session->set_flashdata($array_msg);
			redirect(base_url('admin/community/members/' . $GroupID));
		}
	}


	public function members($id)
	{
		$qey = [
			'select' => 'tbl_community_group_members.*,tbl_login.name , tbl_community_group_members.id as CommunityID, tbl_community_group_members.role as CommunityRole , tbl_community_group_members.status as CommunityStatus, tbl_community_group_members.id as CommunityID',
			'from' => 'tbl_community_group_members',
			'join1' => 'tbl_login',
			'join2' => 'tbl_community_group_members.user_id = tbl_login.id',
			'where' => 'tbl_community_group_members.group_id = ' . $id . '',
		];
		$data['ArrayGroup'] = $this->Curd_model->Select('tbl_group', ['id' => $id]);
		$data['ArrayGroupMmembers'] = $this->Curd_model->SelectDataJoin($qey, $qey['where']);
		$this->load->view('admin/community-members-list', $data);
	}

	public function mypost($Groupid, $user_id)
	{

		$qey = [
			'select' => 'tbl_group_community_post.*,tbl_login.name , tbl_group_community_post.id as PostID , tbl_group_community_post.status as PosrtStatus',
			'from' => 'tbl_group_community_post',
			'join1' => 'tbl_login',
			'join2' => 'tbl_group_community_post.user_id = tbl_login.id',
			'where' => 'tbl_group_community_post.group_id = ' . $Groupid . ' and tbl_group_community_post.user_id = ' . $user_id . '',
		];
		$data['ArrayGroupCommunityPost'] = $this->Curd_model->SelectDataJoin($qey, $qey['where']);
		$data['ArrayGroup'] = $this->Curd_model->Select('tbl_group', ['id' => $Groupid]);
		$this->load->view('admin/post-details', $data);
	}


	public function all_group_post($Groupid)
	{
		$qey = [
			'select' => 'tbl_group_community_post.*,tbl_login.name , tbl_group_community_post.id as PostID , tbl_group_community_post.status as PosrtStatus',
			'from' => 'tbl_group_community_post',
			'join1' => 'tbl_login',
			'join2' => 'tbl_group_community_post.user_id = tbl_login.id',
			'where' => 'tbl_group_community_post.group_id = ' . $Groupid . '',
		];
		$data['ArrayGroupCommunityPost'] = $this->Curd_model->SelectDataJoin($qey, $qey['where']);
		$data['ArrayGroup'] = $this->Curd_model->Select('tbl_group', ['id' => $Groupid]);
		$this->load->view('admin/all-group-post', $data);
	}
}
