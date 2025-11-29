<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MX_Controller {
    public function __construct() {
        parent::__construct();
		$this->load->model('M_auth');
		$data = array();
    }

	public function index() {
		$msg = '';
		$email     = $this->input->post('email');
		$password  = $this->input->post('password');

		if (trim((string)$email) == '') {
			$msg .= 'ID harus diisi!<br />';
		}

		if (trim((string)$password) == '') {
			$msg .= 'Password harus diisi!';
		}

		if ($msg == '') {
			$result = $this->checkUser($email, $password);
			if ($result['result']) {
				redirect('homepage');
			} else {
				$msg .= $result['msg'];
			}
		}
		// Siapkan data ke view
		$data['msg'] = $msg;
		$data['email'] = $email;
		// Jangan isi ulang password untuk keamanan, bisa dihapus kalau ingin autoisi
		$data['password'] = ''; 

		$this->load->view('login/v_login', $data);
	}

	
	public function checkUser($keyword, $password) {
		
		$result = array();
		$result['result'] = false;
		$rs = $this->M_auth->checkUser($keyword);
		if ($rs) {
			$sess_array = array();
			if ($rs['password'] == md5($password)) {
					$sess_array = array('USER_ID' => $rs['id'],
										'USERNAME' => $rs['name'],
										'EMAIL' => $rs['email'],
										'USER_GROUP_ID' => $rs['user_group_id'],
										'OUTLET_ID' => $rs['outlet_id']
									);
					$this->session->set_userdata('logged_in', $sess_array);
					$result['result'] = true;
				}
				else {
					$result['msg'] = 'Invalid Password!';
				}
		}else {
			$result['msg'] = 'Invalid Email!';
		}
		
		return $result;
	}
	
	public function logout() {
	   $this->session->unset_userdata('logged_in');
	   session_destroy();
	   redirect('login', 'index');
	}
}
