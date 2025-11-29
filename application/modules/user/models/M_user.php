<?php
defined('BASEPATH') or exit('no direct script access allowed');

class M_user extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function getList()
	{

		$result = null;
		$this->db->select('a.*, e.name as role_name');
		$this->db->from('users a');
		$this->db->join('user_group e', 'e.id=a.user_group_id', 'left');
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$result[] = $row;
			}
		}

		return $result;
	}

	public function getListMin()
	{
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
	
	public function getCountAll()
	{
		return $this->db->count_all_results('employees');
	}

	public function getCountActive()
	{
		$this->db->where('deleted_at is NULL', NULL, FALSE);
		$this->db->where('resign_date is NULL', NULL, FALSE);
		return $this->db->count_all_results('employees');
	}

	public function insert_user($arr)
	{
		$result = false;
		if (count($arr) > 0) {
			$active = ($arr['active'] == 'on' ? 1 : 0);
			$this->db->trans_start();

			//insert to user
			$this->db->set('username', $arr['username']);
			$this->db->set('password', md5($arr['password']));
			$this->db->set('description', $arr['description']);
			$this->db->set('user_group_id', $arr['user_group_id']);
			$this->db->set('active', $active);
			$this->db->set('user_id_inserted', $this->session->userdata('logged_in')['USER_ID']);
			$this->db->set('date_inserted', date("Y-m-d H:i:s"));
			$this->db->insert('users');
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$result = false;
		} else {
			$this->db->trans_commit();
			$result = true;
		}

		return $result;
	}
	
	public function update_user($arr)
	{
		$result = false;
		if (count($arr) > 0) {
			$this->db->trans_start();

			//insert to user
			$this->db->set('user_group_id', $arr['user_group_id']);
			$this->db->set('user_id_updated', $this->session->userdata('logged_in')['USER_ID']);
			$this->db->set('date_updated', date("Y-m-d H:i:s"));
			$this->db->where('user_id', $arr['user_id']);
			$this->db->update('users');
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$result = false;
		} else {
			$this->db->trans_commit();
			$result = true;
		}

		return $result;
	}

	public function delete($user_id = 0)
	{
		$result = false;

		if ($user_id != 0) {
			$this->db->trans_start();

			$this->db->where('user_id', $user_id);
			$this->db->delete('users');

			$this->db->trans_complete();
		}

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$result = false;
		} else {
			$this->db->trans_commit();
			$result = true;
		}

		return $result;
	}
	

	public function reset_device($user_id)
	{
		$result = false;
		if ($user_id != 0) {
			$this->db->trans_start();
			$this->db->set('device_id', '');
			$this->db->where('id', $user_id);
			$this->db->update('users');
			$this->db->trans_complete();
		}

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$result = false;
		} else {
			$this->db->trans_commit();
			$result = true;
		}

		return $result;
	}

	public function publish($user_id, $publish)
	{

		$result = false;

		if ($user_id != 0) {
			$this->db->trans_start();

			$this->db->set('active', $publish);
			$this->db->where('user_id', $user_id);
			$this->db->update('users');

			$this->db->trans_complete();
		}

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$result = false;
		} else {
			$this->db->trans_commit();
			$result = true;
		}

		return $result;
	}
	
	public function getById($user_id=0) {
		$result = array();
		$this->db->select('a.*, b.name as group_name');
		$this->db->from('users a');
		$this->db->join('user_group b', 'a.user_group_id=b.id', 'left');
		
		$this->db->where('b.id', $user_id);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$row = $query->result_array();
			$result = $row[0];
		}
		
		return $result;
	}
	
	public function getAbsenceById($user_id=0, $month=0,$year=0) {
		$result = array();
		$this->db->select('*');
		$this->db->from('vw_employee_absences');
		$this->db->where('user_id', $user_id);
		if($month!=0){
		$this->db->where('EXTRACT(MONTH FROM absence_date)=', $month);
		$this->db->where('EXTRACT(YEAR FROM absence_date)=', $year);
		}
		$this->db->order_by('absence_date', 'desc');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$result[] = $row;
			}
		}	
		
		return $result;
	}

	public function getAbsenceByAbsenceId($absence_id=0) {
		$result = array();
		$this->db->select('*');
		$this->db->from('vw_employee_absences');
		$this->db->where('absence_id', $absence_id);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$row = $query->result_array();
			$result = $row[0];
		}
		
		return $result;
	}

	public function getEventByAbsenceId($absence_id=0) {
		$result = array();
		$this->db->select('*');
		$this->db->from('vw_employee_events');
		$this->db->where('absence_id', $absence_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$result[] = $row;
			}
		}	
		
		return $result;
	}

	public function getAbsenceById2($user_id=0, $month=0) {
		$result = array();
		$this->db->select('a.id, e.name as location_name, d.name as shift_name, a.date as schedule_date, b.date as absence_date, b.time_in,b.time_out, b.notes');
		$this->db->from('employee_schedules a');
		$this->db->join('absences b', 'a.id=b.employee_schedule_id', 'left');
		$this->db->join('shift_locations c', 'c.id=a.shift_location_id', 'left');
		$this->db->join('shifts d', 'd.id=c.shift_id', 'left');
		$this->db->join('client_locations e', 'e.id=c.location_id', 'left');
		$this->db->join('employees f', 'f.id=a.employee_id', 'left');
		$this->db->where('f.user_id', $user_id);
		if($month!=0){
		$this->db->where('EXTRACT(MONTH FROM a.date)=', $month);
		}
		$this->db->order_by('a.date', 'desc');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$result[] = $row;
			}
		}	
		
		return $result;
	}

	public function getUserByUsername($username = 0)
	{
		$result = array();
		$this->db->from('users');
		$this->db->where('username', $username);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$row = $query->result_array();
			$result = $row[0];
		}

		return $result;
	}

	public function getUserProfile($userId)
	{
		$this->db->select('*, name as USERNAME');
		$this->db->from('users');
		$this->db->where('id', $userId);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}

	function get_tables_query($query,$cari,$where,$iswhere,$isGroupBy)
	{
		// Ambil data yang di ketik user pada textbox pencarian
		$search = htmlspecialchars($_POST['search']['value']);
		// Ambil data limit per page
		$limit = preg_replace("/[^a-zA-Z0-9.]/", '', "{$_POST['length']}");
		// Ambil data start
		$start =preg_replace("/[^a-zA-Z0-9.]/", '', "{$_POST['start']}"); 

		if($where != null)
		{
			$setWhere = array();
			foreach ($where as $key => $value)
			{
				$setWhere[] = $key." ".$value."";
			}
			$fwhere = implode(' AND ', $setWhere);

			if(!empty($iswhere))
			{
				$sql = $this->db->query($query." WHERE  $iswhere AND ".$fwhere);
				
			}else{
				$sql = $this->db->query($query." WHERE ".$fwhere);
			}
			$sql_count = $sql->num_rows();

			$cari = implode(" LIKE '%".$search."%' OR ", $cari)." LIKE '%".$search."%'";
			
			// Untuk mengambil nama field yg menjadi acuan untuk sorting
			$order_field = $_POST['order'][0]['column']; 

			// Untuk menentukan order by "ASC" atau "DESC"
			$order_ascdesc = $_POST['order'][0]['dir']; 
			$order = " ORDER BY ".$_POST['columns'][$order_field]['data']." ".$order_ascdesc;

			if(!empty($iswhere))
			{
				$sql_data = $this->db->query($query." WHERE $iswhere AND ".$fwhere." AND (".$cari.")".$order." LIMIT ".$limit." OFFSET ".$start);
			}else{
				$sql_data = $this->db->query($query." WHERE ".$fwhere." AND (".$cari.") ".$order." LIMIT ".$limit." OFFSET ".$start);
			}
			
			if(isset($search))
			{
				if(!empty($iswhere))
				{
					$sql_cari =  $this->db->query($query." WHERE $iswhere AND ".$fwhere." AND (".$cari.")");
				}else{
					$sql_cari =  $this->db->query($query." WHERE ".$fwhere." AND (".$cari.")");
				}
				$sql_filter_count = $sql_cari->num_rows();
			}else{
				if(!empty($iswhere))
				{
					$sql_filter = $this->db->query($query." WHERE $iswhere AND ".$fwhere."");
				}else{
					$sql_filter = $this->db->query($query." WHERE ".$fwhere."");
				}
				$sql_filter_count = $sql_filter->num_rows();
			}
			$data = $sql_data->result_array();

		}else{
			if(!empty($iswhere))
			{
				$sql = $this->db->query($query." WHERE  $iswhere ");
			}else{
				$sql = $this->db->query($query);
			}
			$sql_count = $sql->num_rows();

			$cari = implode(" LIKE '%".$search."%' OR ", $cari)." LIKE '%".$search."%'";
			
			// Untuk mengambil nama field yg menjadi acuan untuk sorting
			$order_field = $_POST['order'][0]['column']; 

			// Untuk menentukan order by "ASC" atau "DESC"
			$order_ascdesc = $_POST['order'][0]['dir']; 
			$order = " ORDER BY ".$_POST['columns'][$order_field]['data']." ".$order_ascdesc;

			if(!empty($iswhere))
			{                
				$sql_data = $this->db->query($query." WHERE $iswhere AND (".$cari.") ".$order." LIMIT ".$limit." OFFSET ".$start);
			}else{
				$sql_data = $this->db->query($query." WHERE (".$cari.") ".$order." LIMIT ".$limit." OFFSET ".$start);
			}

			if(isset($search))
			{
				if(!empty($iswhere))
				{     
					$sql_cari =  $this->db->query($query." WHERE $iswhere AND (".$cari.")");
				}else{
					$sql_cari =  $this->db->query($query." WHERE (".$cari.")");
				}
				$sql_filter_count = $sql_cari->num_rows();
			}else{
				if(!empty($iswhere))
				{
					$sql_filter = $this->db->query($query." WHERE $iswhere");
				}else{
					$sql_filter = $this->db->query($query);
				}
				$sql_filter_count = $sql_filter->num_rows();
			}
			$data = $sql_data->result_array();
		}
		
		$callback = array(    
			'draw' => $_POST['draw'], // Ini dari datatablenya    
			'recordsTotal' => $sql_count,    
			'recordsFiltered'=>$sql_filter_count,    
			'data'=>$data
		);
		return json_encode($callback); // Convert array $callback ke json
	}
}
