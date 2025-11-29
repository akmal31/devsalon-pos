<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_auth extends CI_Model {
	
	public function checkUser($keyword) {
		
        $result = array();
		$this->db->select('*');
		$this->db->from('users');
		$this->db->group_start()
			->where('email', $keyword)
			->or_where('name', $keyword)
			->or_where('phone', $keyword)
			->group_end();
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() == 1) {
			$row = $query->result_array();
			$result = $row[0];
			return $result;
		}
		else {
			return false;
		}
	}

	public function getEmployeeUserByEmail($email)
	{
		$result = array();
		$this->db->select('a.*, e.id as employee_id');
		$this->db->from('users a');
		$this->db->join('employees e', 'e.user_id=a.id', 'left');
		$this->db->where('email', $email);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$row = $query->result_array();
			$result = $row[0];
		}

		return $result;
	}
	
}