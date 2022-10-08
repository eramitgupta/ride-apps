<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Api_model extends CI_Model{

    public function GetData($table,$id)
    {
        $this->db->where('id',$id);
        $query = $this->db->get($table);
        return $query->row_array();
    }

    public function insertLastId($table,$data)
    {
         $this->db->insert($table, $data);
         return $this->db->insert_id();
    }

    public function insert($table,$data)
    {
         return $this->db->insert($table, $data);
    }

    public function Update($table,$id,$data)
    {
        $this->db->where('id', $id);
        return $this->db->update($table, $data);
    }

    public function getRow($table,$username)
    {
        $where = "username = '$username' AND role = 'user' AND status='Active' OR mobile = '$username'";
        return  $this->db->where($where)->get($table)->result_array();
    }

    public function Delete($table,$id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($table);
    }

    public function multipleDelete($table,$array)
    {
        $this->db->where($array);
        return $this->db->delete($table);
    }

    public function SelectData($table,$array='')
    {
        if(!empty($array)){
            $this->db->where($array);
        }
        $query = $this->db->get($table);
        return $query->result_array();
    }

    public function AlreadyExists($table,$array)
    {
        $this->db->where($array);
        return $this->db->get($table)->row();
    }


    public function CountsData($table,$array)
    {
       return $this->db->where($array)->count_all_results($table);
    }

    public function UpdateArray($table,$array,$data)
    {
        $this->db->where($array);
        return $this->db->update($table, $data);
    }

    public function DeleteArray($table,$data)
    {
        $this->db->where($data);
        return $this->db->delete($table);
    }

    public function search($select,$where,$table,$keyword)
    {
        // select 'id,name,email,current_address,degination,education'
        // where ['status'=>'1','role'=>'2']
        // like 'name',hello
        $this->db->select($select);
        $this->db->where($where);
        $query = $this->db->get($table);
        $this->db->like($keyword);
        return  $query->result_array();


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



}
