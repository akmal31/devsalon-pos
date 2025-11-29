<?php
	defined('BASEPATH') OR exit('no direct script access allowed');
	
	class M_user_profile extends CI_Model {
		
		public function __construct(){
			parent::__construct();
		}
		
		public function getList() {
			
		$result = null;
		$this->db->select('a.*, i.username as username_inserted, j.username as username_updated');
		$this->db->from('users a');
		$this->db->join('users i', 'i.user_id=a.userid_inserted', 'left');
		$this->db->join('users j', 'j.user_id=a.userid_updated', 'left');
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$result[] = $row;
			}
		}	
		
		return $result;
		} 
		
		public function getListMin() {
			$result = null;
			$this->db->select('*');
			$this->db->from('users');
			$query = $this->db->get();
			
			if ($query->num_rows() > 0) {
				foreach ($query->result_array() as $row) {
					$result[] = $row;
				}
			}	
			
			return $result;
		}
		
		public function update($arr){												
					
			$result = false;
			
			if (count($arr) > 0) {
				$this->db->trans_start();
			
				//update
				$this->db->set('email', $arr['email']);
				$this->db->set('first_name', $arr['first_name']);
				$this->db->set('last_name', $arr['last_name']);
				$this->db->set('mobile_number', $arr['mobile_number']);
				$this->db->set('address', $arr['address']);
				$this->db->set('user_id_updated', $this->session->userdata('logged_in')['USER_ID']);
				$this->db->set('date_updated', date("Y-m-d H:i:s"));
				$this->db->where('user_id', $arr['user_id']);
				
				$this->db->update('users');
			}
			
			$this->db->trans_complete();
			
			if ($this->db->trans_status() === false) {
				$this->db->trans_rollback();
				$result = false;
			}
			else {
				$this->db->trans_commit();
				$result = true;
			}
			
			return $result;
		}
		
		public function update_password($arr){												
					
			$result = false;
			
			if (count($arr) > 0) {
				$this->db->trans_start();
			
				//update
				$this->db->set('password', $arr['password']);
				$this->db->where('user_id', $arr['user_id']);
				
				$this->db->update('users');
			}
			
			$this->db->trans_complete();
			
			if ($this->db->trans_status() === false) {
				$this->db->trans_rollback();
				$result = false;
			}
			else {
				$this->db->trans_commit();
				$result = true;
			}
			
			return $result;
		}
		
		public function getById($user_id=0) {
			$result = array();
			$this->db->select('a.*, b.employee_id, b.status, b.join_date');
			$this->db->from('users a');
			$this->db->join('employees b', 'b.user_id=a.id', 'left');
			$this->db->where('a.id', $user_id);
			$this->db->limit(1);
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				$row = $query->result_array();
				$result = $row[0];
			}
			
			return $result;
		}
		
	}