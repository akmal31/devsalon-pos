<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MX_Controller {
    public function __construct() {
        parent::__construct();
		$this->data = array();
    }

	public function index() {
        $this->load->library('form_validation');
		$this->load->view('v_login', $this->data);
	}
}
