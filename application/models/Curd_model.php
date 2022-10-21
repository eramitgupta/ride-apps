<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Curd_model extends CI_Model
{

    public function getByUsername($username)
    {
        $where = "username = '$username' AND role='admin' AND status='Active' OR mobile = '$username' OR email = '$username'";
        return  $this->db->where($where)->get('tbl_login')->row();
    }

    public function getAccounts($id)
    {
        return  $this->db->where('id', $id)->get('tbl_login')->row_array();
    }

    public function loginGet()
    {
        return $data = $this->db->where(['role' => 'admin'])->get('tbl_login')->result_array();
    }


    public function insertLastId($table, $data)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function insert($table, $data)
    {
        return $this->db->insert($table, $data);
    }

    public function Select($table, $array = '', $limit = '')
    {
        if (!empty($array)) {
            $this->db->where($array);
        }

        if (!empty($limit)) {
            $this->db->limit($limit);
        }
        // $this->db->order_by('id', 'ASC');
        $query = $this->db->get($table);
        return $query->result_array();
    }

    public function update($table, $where, $array)
    {
        $this->db->where($where);
        return $this->db->update($table, $array);
    }

    public function Delete($table, $data)
    {
        $this->db->where($data);
        return $this->db->delete($table);
    }


    public function SelectDataJoin($table, $array = '')
    {
        // 'tbl_login', 'tbl_create_ride.user_id = tbl_login.id'
        $this->db->select('' . $table['select'] . '');
        $this->db->from($table['from']);
        $this->db->join('' . $table['join1'] . '', '' . $table['join2'] . '');

        if (!empty($array)) {
            $this->db->where($array);
        }
        return $this->db->get()->result_array();
    }

    public function JoinTable($id)
    {
        return $this->db->select('tbl_create_ride.*, tbl_create_ride.status as tbl_create_ride_status, tbl_ride_comment.*, tbl_login.*, tbl_ride_comment.user_id as tbl_ride_comment_user_id')
            ->from('tbl_create_ride')
            ->join('tbl_login', 'tbl_create_ride.user_id = tbl_login.id')
            ->join('tbl_ride_comment', 'tbl_create_ride.id = tbl_ride_comment.post_id')
            ->where('tbl_create_ride.id', $id)
            ->get()
            ->result_array();
    }


    public function CountsData($table, $array)
    {
        return $this->db->where($array)->count_all_results($table);
    }

    public function UserFriendList($id)
    {
        return $this->db->select('*')
            ->from('tbl_friends_request')
            ->join('tbl_login', 'tbl_friends_request.frend_id = tbl_login.id')
            ->where('tbl_friends_request.user_id', $id)
            ->where('tbl_friends_request.status', 'Active')
            ->get()
            ->result_array();
    }
}
