<?php
	defined('BASEPATH') OR exit('no direct script access allowed');

	class User_profile extends MX_Controller {

		public function __construct(){

			parent::__construct();
			if(!$this->session->userdata('logged_in')) {
				redirect('login','index');
			}
			$this->load->library('form_validation');

			$this->data = array();
		
			$this->load->model('user/M_user');	
					
			$this->data['user_profile'] = $this->session->userdata('logged_in');
			
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
			
			$this->data['msg'] = "";

			$this->load->model('user_profile/M_user_profile');

		}

		public function index(){

			$this->data["data"] = $this->M_user_profile->getById($this->session->userdata('logged_in')['USER_ID']);
			$this->load->view('v_list', $this->data);
		}

		public function save() {
			
			$btnSubmit = $this->input->post('btnSubmit');
			$user_id = $this->session->userdata('logged_in')['USER_ID'];
			$first_name = $this->input->post('first_name');
			$last_name = $this->input->post('last_name');
			$email = $this->input->post('email');
			$address = $this->input->post('address');
			$mobile_number = $this->input->post('mobile_number');
			$password = $this->input->post('pass');

			if ($btnSubmit == 'save') {
				$this->load->library('form_validation');
				$conf_rules = array(
									array(
											'field'=>'first_name',
											'label'=>'Nama Depan',
											'rules'=>'required',
											'errors'=>array(
															'required'=>"<small class='form-control-feedback'>Please insert %s</small>"
															)
										),
									array(
											'field'=>'last_name',
											'label'=>'Nama Belakang',
											'rules'=>'required',
											'errors'=>array(
															'required'=>"<small class='form-control-feedback'>Please insert %s</small>"
															)
										),
									array(
											'field'=>'address',
											'label'=>'Alamat',
											'rules'=>'required',
											'errors'=>array(
															'required'=>"<small class='form-control-feedback'>Please insert %s</small>"
															)
										),
									array(
											'field'=>'mobile_number',
											'label'=>'No Telp',
											'rules'=>'required',
											'errors'=>array(
															'required'=>"<small class='form-control-feedback'>Please insert %s</small>"
															)
										),
									array(
											'field'=>'email',
											'label'=>'Email',
											'rules'=>'required',
											'errors'=>array(
															'required'=>"<small class='form-control-feedback'>Please insert %s</small>"
															)
										),
								);
				$this->form_validation->set_rules($conf_rules);

				if ($this->form_validation->run() == false) {
					$this->load->library('form_validation');
					$this->data["data"] = $this->M_user_profile->getById($user_id);
					$this->load->view('v_list', $this->data);
				}else{
					//process update
					$dataUpdate = array();
					$dataUpdate['first_name'] = $first_name;
					$dataUpdate['last_name'] = $last_name;
					$dataUpdate['address'] = $address;
					$dataUpdate['mobile_number'] = $mobile_number;
					$dataUpdate['user_id'] = $user_id;
					$dataUpdate['email'] = $email;
					

					if ($this->M_user_profile->update($dataUpdate)) {
						if ($password != ""){
							$dataUpdatePassword['password'] = md5($password);
							$dataUpdatePassword['user_id'] = $user_id;
							$this->M_user_profile->update_password($dataUpdatePassword);
							$this->data["msg"] = "Selamat Profile dan Password Berhasil diubah";
						}else{  
							$this->data["msg"] = "Selamat Profile Berhasil diubah";
						}
						$this->data["data"] = $this->M_user_profile->getById($user_id);
						$this->load->view('v_list', $this->data);
					}
					else {
						$this->load->library('form_validation');
						$this->data["msg"] = "Maaf ada kesalahan. silahkan coba kembali";
						$this->data["data"] = $this->M_user_profile->getById($user_id);
						$this->load->view('v_list', $this->data);
					}
				}
			}
			else {
				redirect('user_profile');
			}
		}
	}



