<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_user_group extends CI_Model {
	
	public function getListMin() {
		$result = array();
		
		$this->db->select('user_group_id,name');
        $this->db->from('user_group');
		$this->db->order_by('name', 'ASC');
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$result[] = $row;
			}
		}	
		
		return $result;
	}
	
	public function getList() {
		$result = null;
		
		$this->db->select('*');
		$this->db->from('user_group');
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$result[] = $row;
			}
		}	
		
		return $result;
	}
	
	public function getFullById($user_group_id) {
		
		$result = array();
		$this->db->select('*');
		$this->db->from('user_group');
		$this->db->where('id', $user_group_id);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$row = $query->result_array();
			$result = $row[0];
		}
		
		return $result;
	}

	public function getGroupPrivillege($groupId) {
		$result = null;
		
		$this->db->select('id,menu_id,parent_id,is_view,is_insert,is_update,is_delete');
        $this->db->from('user_group_privillege');
		$this->db->where('user_group_id', $groupId);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$result[] = $row;
			}
		}	
		
		return $result;
	}
	
	public function getGroupPrivillegeView($groupId) {
		
		$result = null;		
		$sql = 'SELECT DISTINCT 
					parent_id AS menu_id
				FROM 
					user_group_privillege 
				WHERE
					user_group_id = '.$groupId. '
				AND 
					is_view = 1';
					
		$query 	= $this->db->query($sql);
		$result = $query->result_array();			
		
		return $result;
	}
	
	
	public function insert($arr=array()) {
		$result = false;
		if (count($arr) > 0) {
			$active = ($arr['active'] == 'on' ? 1 : 0);
			$this->db->trans_start();
		
			//insert to user
			$this->db->set('name', $arr['name']);
			$this->db->set('description', $arr['description']);
			$this->db->set('active', $active);
			$this->db->set('user_id_inserted', $this->session->userdata('logged_in')['USER_ID']);
			$this->db->set('date_inserted', date("Y-m-d H:i:s"));
			
			if ($this->db->insert('user_group')) {
				$user_group_id = $this->db->insert_id();
				
				//insert to privilege
				$this->load->model('Menu/M_menu');
				$menus = $this->M_menu->getList();
				
				if (count($menus) > 0) {
					foreach ($menus as $key=>$mn) {
						$this->db->set('user_group_id', $user_group_id);
						$this->db->set('menu_id', $mn['menu_id']);
						$this->db->set('is_view', 0);
						$this->db->set('is_insert', 0);
						$this->db->set('is_update', 0);
						$this->db->set('is_delete', 0);
						$this->db->set('is_rate_coverage', 0);
						$this->db->set('is_active', 0);
						$this->db->set('is_detail', 0);
						$this->db->set('user_id_inserted', $this->session->userdata('logged_in')['USER_ID']);
						$this->db->set('date_inserted', date("Y-m-d H:i:s"));
						$this->db->insert('user_group_privillege');
					}
				}
			}
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
	
	public function update($arr=array()) {
		$result = false;
		if (count($arr) > 0) {
			$active = ($arr['active'] == 'on' ? 1 : 0);
			$this->db->trans_start();
		
			//update to user group
			$this->db->set('name', $arr['name']);
			$this->db->set('description', $arr['description']);
			$this->db->set('active', $active);
			$this->db->set('user_id_updated', $this->session->userdata('logged_in')['USER_ID']);
			$this->db->set('date_updated', date("Y-m-d H:i:s"));
			$this->db->where('user_group_id', $arr['user_group_id']);
			
			$this->db->update('user_group');
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
	
	public function delete($user_group_id=0) {
		$result = false;
		
		if ($user_group_id != 0) {
			$this->db->trans_start();
			
			$this->db->where('user_group_id', $user_group_id);
			$this->db->delete('user_group_privillege');
			
			$this->db->where('user_group_id', $user_group_id);
			$this->db->delete('user_group');
			
			$this->db->trans_complete();
		}
		
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
}