<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_group extends MX_Controller {
	
    public function __construct() {
		
        parent::__construct();
		if (!$this->session->userdata('logged_in')) {
			redirect('login','index');
		}
		
		$this->load->model('user/M_user');
		$this->load->model('menu/M_menu');
		$this->load->model('M_user_group');
		$this->data = array();
		$this->data['msg'] = '';
		
		$userProfile = $this->M_user->getUserProfile($this->session->userdata('logged_in')['USER_ID']);
		$this->data['user_profile'] = $userProfile;	
		
		//--Start Get User Group Privillege
		$this->load->model('menu/M_menu');
		$this->load->model('user_group/M_user_group');

		//Group Menu Privillege
		$menuPrivillege = $this->M_menu->getMenuByGroup($this->session->userdata('logged_in')['USER_GROUP_ID']);
		$this->data['menu_privillege'] = $menuPrivillege;		
		
		//Get Group Action Privillege
		$menu_id = $this->M_menu->getSubMenuId($menuPrivillege,$this->uri->segment(1));
		$accessPrivillege = $this->M_menu->getAccessPrivellege($this->session->userdata('logged_in')['USER_GROUP_ID'],$menu_id);
		$this->data['access_privillege'] = $accessPrivillege;
		//--End User Group Privillege
		$this->data['data'] =  array();				
		
    }

	public function index() {
				
		$this->data['list'] = $this->M_user_group->getList();
		if(empty($this->data['access_privillege'])){
			$this->load->view('partial/v_forbidden', $this->data);
		}else{
			$this->load->view('v_list', $this->data);
		}
	}
	
	public function add() {
        $this->load->library('form_validation');
		$this->load->view('v_form', $this->data);
	}
	
	public function edit($user_group_id=0) {
		$this->load->library('form_validation');
		$this->data['data'] = $this->M_user_group->getFullById($user_group_id);
		$this->data['user_group_id'] = $user_group_id;
		$this->load->view('v_form', $this->data);
	}
	
	public function save() {
		$data = array();
		$btnSubmit = $this->input->post('btnSubmit');
		if ($btnSubmit == 'save') {
			$this->load->library('form_validation');
			$user_group_id = $this->input->post('user_group_id');
			
			$group_name = $this->input->post('group_name');
			$description = $this->input->post('description');
			$active = $this->input->post('active');
						
			$conf_rules = array(
								array(
										'field'=>'group_name',
										'label'=>'Group Name',
										'rules'=>'required',
										'errors'=>array(
														'required'=>"<span class='help-block'>%s can't be empty</span>"
														)
									)
							);
			$this->form_validation->set_rules($conf_rules);
			
			if ($this->form_validation->run() == false) {
				$this->data['user_group_id'] = $user_group_id;
				$this->load->view('v_form', $this->data);
			}
			else if ($user_group_id == 0) {
				//process add user group
				$dataInsert = array();
				$dataInsert['name'] = $group_name;
				$dataInsert['description'] = $description;
				$dataInsert['active'] = $active;
				
				if ($this->M_user_group->insert($dataInsert)) {
					redirect('user_group');
				}
				else {
					$this->data['user_group_id'] = $user_group_id;
					$this->load->view('v_form', $this->data);
				}
			}
			else {
				//process update
				$dataUpdate = array();
				$dataUpdate['user_group_id'] = $user_group_id;
				$dataUpdate['name'] = $group_name;
				$dataUpdate['description'] = $description;
				$dataUpdate['active'] = $active;
				
				if ($this->M_user_group->update($dataUpdate)) {
					redirect('user_group');
				}
				else {
					$this->data['user_group_id'] = $user_group_id;
					$this->load->view('v_form', $this->data);
				}
			}
		}
		else {
			redirect('user_group');
		}
	}
	
	public function delete($user_group_id=0) {
		$this->M_user_group->delete($user_group_id);
		$this->data['list'] = $this->M_user_group->getList();
		redirect('user_group');
	}

	public function priv($user_group_id=0) {
		
		$this->load->model('M_user_group_privillege');
		$this->data['data'] = $this->M_user_group->getFullById($user_group_id);
		
		$list_parent_menu = $this->M_user_group_privillege->getListParentMenu();			
		$listing = $this->M_user_group_privillege->getListPrivByUserGroupId($user_group_id);		
		foreach($list_parent_menu as $k => $v){
			foreach($listing as $klist => $vlist){
				if($vlist['parent_id'] == $v['menu_id']){
					$listing[$klist]['parent_name'] = $v['name'];					
				}
			}
		}
		$list = array();
		foreach($list_parent_menu as $k => $v){			
			foreach($listing as $klist => $vlist){				
				if($v['menu_id'] == $vlist['parent_id']){
					$list[$klist] = $vlist;					
				}
			}
		}		
		$this->data['list'] = $list;
		
		//$this->data['list'] = $this->M_user_group_privillege->getListPrivByUserGroupId($user_group_id);
		$this->data['user_group_id'] = $user_group_id;
		
		$this->load->view('v_form_priv', $this->data);
	}
	
	public function save_priv(){
		
		$data = array();
		$btnSubmit = $this->input->post('btnSubmit');
						
		if ($btnSubmit == 'save') {
			$this->load->model('M_user_group_privillege');
			$user_group_id = $this->input->post('user_group_id');
			
			$this->db->trans_start();
			
			//delete user group
			$this->M_user_group_privillege->delete($user_group_id);
			
			//insert user group privillege
			//$menus = $this->M_menu->getListMin();
			$menus = $this->M_menu->getListMinNoParent();
			foreach ($menus as $keyMenu=>$mn) {
				$is_all_name = "all_".$user_group_id."_".$mn['menu_id'];
				$is_all = $this->input->post($is_all_name);
				
				$dataInsert = array();
				$dataInsert['user_group_id'] = $user_group_id;
				$dataInsert['menu_id'] = $mn['menu_id'];
				$dataInsert['parent_id'] = $mn['parent_id'];
				
				if ($is_all == 'on') {
					/*
					$dataInsert['is_view'] = 1;
					$dataInsert['is_insert'] = 1;
					$dataInsert['is_update'] = 1;
					$dataInsert['is_delete'] = 1;
					$dataInsert['is_rate_coverage'] = 1;
					$dataInsert['is_active'] = 1;
					$dataInsert['is_detail'] = 1; */
					$dataInsert['is_view'] = ($this->input->post('is_view_'.$user_group_id.'_'.$mn['menu_id'])=='on' ? 1 : 0);
					$dataInsert['is_insert'] = ($this->input->post('is_insert_'.$user_group_id.'_'.$mn['menu_id'])=='on' ? 1 : 0);
					$dataInsert['is_update'] = ($this->input->post('is_update_'.$user_group_id.'_'.$mn['menu_id'])=='on' ? 1 : 0);
					$dataInsert['is_delete'] = ($this->input->post('is_delete_'.$user_group_id.'_'.$mn['menu_id'])=='on' ? 1 : 0);
					$dataInsert['is_rate_coverage'] = ($this->input->post('is_rate_coverage_'.$user_group_id.'_'.$mn['menu_id'])=='on' ? 1 : 0);
					$dataInsert['is_active'] = ($this->input->post('is_active_'.$user_group_id.'_'.$mn['menu_id'])=='on' ? 1 : 0);
					$dataInsert['is_detail'] = ($this->input->post('is_detail_'.$user_group_id.'_'.$mn['menu_id'])=='on' ? 1 : 0);
				}
				else {
					$dataInsert['is_view'] = ($this->input->post('is_view_'.$user_group_id.'_'.$mn['menu_id'])=='on' ? 1 : 0);
					$dataInsert['is_insert'] = ($this->input->post('is_insert_'.$user_group_id.'_'.$mn['menu_id'])=='on' ? 1 : 0);
					$dataInsert['is_update'] = ($this->input->post('is_update_'.$user_group_id.'_'.$mn['menu_id'])=='on' ? 1 : 0);
					$dataInsert['is_delete'] = ($this->input->post('is_delete_'.$user_group_id.'_'.$mn['menu_id'])=='on' ? 1 : 0);
					$dataInsert['is_rate_coverage'] = ($this->input->post('is_rate_coverage_'.$user_group_id.'_'.$mn['menu_id'])=='on' ? 1 : 0);
					$dataInsert['is_active'] = ($this->input->post('is_active_'.$user_group_id.'_'.$mn['menu_id'])=='on' ? 1 : 0);
					$dataInsert['is_detail'] = ($this->input->post('is_detail_'.$user_group_id.'_'.$mn['menu_id'])=='on' ? 1 : 0);
				}
				
				$this->M_user_group_privillege->insert($dataInsert);
			}
			
			$this->db->trans_complete();
			
			if ($this->db->trans_status() === false) {
				$this->db->trans_rollback();
				$this->data['user_group_id'] = $user_group_id;
				$this->load->view('v_form_priv', $this->data);
			}
			else {
				$this->db->trans_commit();
				redirect('user_group');
			}
		}
		else {
			redirect('user_group');
		}
	}
}	