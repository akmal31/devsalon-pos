<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_user_group_privillege extends CI_Model {
	
	public function getListByUserGroupId($user_group_id) {
		$result = array();
		$this->db->select('menu_id,is_view,is_insert,is_update,is_delete,is_rate_coverage,is_active,is_detail');
        $this->db->from('user_group_privillege');
		$this->db->where('user_group_id', $user_group_id);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$result[] = $row;
			}
		}	
		
		return $result;
	}
	
	public function getListPrivByUserGroupId($user_group_id) {
		
		$result = array();		
		$this->db->select('menu_id,name,parent_id');
		$this->db->from('menu');
		$this->db->where('active', 1);		
		$this->db->where('is_parent <>',1);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$this->db->select('user_group_privillege_id,is_view,is_insert,is_update,is_delete,is_rate_coverage,is_active,is_detail');
				$this->db->from('user_group_privillege');
				$this->db->where('user_group_id', $user_group_id);
				$this->db->where('menu_id', $row['menu_id']);
				
				$q2 = $this->db->get();
				if ($q2->num_rows() > 0) {
					foreach ($q2->result_array() as $row2) {
						
						$result[$row['menu_id']]['parent_id'] = $row['parent_id'];
						
						$result[$row['menu_id']]['menu_name'] = $row['name'];
						$result[$row['menu_id']]['is_view'] = $row2['is_view'];
						$result[$row['menu_id']]['is_insert'] = $row2['is_insert'];
						$result[$row['menu_id']]['is_update'] = $row2['is_update'];
						$result[$row['menu_id']]['is_delete'] = $row2['is_delete'];
						$result[$row['menu_id']]['is_rate_coverage'] = $row2['is_rate_coverage'];
						$result[$row['menu_id']]['is_active'] = $row2['is_active'];
						$result[$row['menu_id']]['is_detail'] = $row2['is_detail'];
					}
				}
				else {					
					$result[$row['menu_id']]['parent_id'] = $row['parent_id'];
					
					$result[$row['menu_id']]['menu_name'] = $row['name'];
					$result[$row['menu_id']]['is_view'] = 0;
					$result[$row['menu_id']]['is_insert'] = 0;
					$result[$row['menu_id']]['is_update'] = 0;
					$result[$row['menu_id']]['is_delete'] = 0;
					$result[$row['menu_id']]['is_rate_coverage'] = 0;
					$result[$row['menu_id']]['is_active'] = 0;
					$result[$row['menu_id']]['is_detail'] = 0;
				}
			}
		}	
		
		return $result;
	}
	
	public function getListParentMenu() {
		
		$result = array();		
		$sql = "SELECT menu_id,name FROM menu WHERE active = 1 AND is_parent = 1 ORDER BY parent_id,sort ASC";
		$query 	= $this->db->query($sql);
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$result[] = $row;
			}
		}	
		
		return $result;
	}
	
	public function delete($user_group_id) {
		
		$result = false;
		$this->db->where('user_group_id', $user_group_id);
		if ($this->db->delete('user_group_privillege')) $result = true;
								
		return $result;
	}
	
	public function insert($arr=array()) {
		$result = false;
		
		if (count($arr) > 0) {
			$this->db->set('user_group_id', $arr['user_group_id']);
			$this->db->set('menu_id', $arr['menu_id']);
			$this->db->set('parent_id', $arr['parent_id']);
			$this->db->set('is_view', $arr['is_view']);
			$this->db->set('is_insert', $arr['is_insert']);
			$this->db->set('is_update', $arr['is_update']);
			$this->db->set('is_delete', $arr['is_delete']);
			$this->db->set('is_rate_coverage', $arr['is_rate_coverage']);
			$this->db->set('is_active', $arr['is_active']);
			$this->db->set('is_detail', $arr['is_detail']);
			$this->db->set('user_id_inserted', $this->session->userdata('logged_in')['USER_ID']);
			$this->db->set('date_inserted', date("Y-m-d H:i:s"));
			
			if ($this->db->insert('user_group_privillege')) $result = true;
		}
		
		return $result;
	}
}