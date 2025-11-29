<?php
	defined('BASEPATH') OR exit('no direct script access allowed');

	class Transaction extends MX_Controller {

		public function __construct(){

			parent::__construct();
			if(!$this->session->userdata('logged_in')) {
				redirect('login','index');
			}
			
			$this->load->library('form_validation');

			$this->data = array();

			//--Start Get User Group Privillege
			$this->load->model('menu/M_menu');
			$this->load->model('user_group/M_user_group');
			
			// Ambil menu privilege sesuai group user
			$menuPrivillege = $this->M_menu->getMenuByGroup(
				$this->session->userdata('logged_in')['USER_GROUP_ID']
			);

			$this->data['menu_privillege'] = $menuPrivillege;
			$this->data['user_profile'] = $this->session->userdata('logged_in');

			// Ambil menu_id dari URI segment
			$menu_id = $this->M_menu->getSubMenuId($menuPrivillege, $this->uri->segment(1));

			// Cek akses
			if ($this->M_menu->getAccessPrivellege(
				$this->session->userdata('logged_in')['USER_GROUP_ID'],
				$menu_id
			)) {
				$this->data['access_privillege'] = true;
			} else {
				$this->load->view('partial/v_forbidden', $this->data);
			}

			
			$this->data['msg'] = "";

			$this->load->model('transaction/M_transaction');
			
		}

		public function index()
		{
			$this->load->view('v_list');
		}


		public function add_transaction(){
			$this->data["all_karyawan"] = $this->M_transaction->getListCapsterStylist();
			$this->load->view('add_transaction', $this->data);
		}

		public function preview_struk($transaction_id = null)
		{
			if(!$transaction_id){
				show_error("Transaction ID tidak valid");
				return;
			}

			// data cabang
			$data['cabang'] = $this->M_transaction->getCabang();
			// Ambil data transaksi
			$transaction = $this->M_transaction->getTransactionById($transaction_id);
			if(!$transaction){
				show_error("Transaksi tidak ditemukan");
				return;
			}

			$data['cart']         = $this->M_transaction->getTransactionDetails($transaction_id);
			$data['grand_total']  = $transaction['grand_total'];
			$data['metode_bayar'] = $transaction['payment_method'];
			$data['tips']         = $transaction['tips'];
			$data['uang_bayar']   = $transaction['uang_bayar'];
			$data['kembalian']    = $transaction['kembalian'];

			$this->load->view('preview_struk', $data);
		}

		public function filterTransactions()
		{
			$date = $this->input->post('date'); // YYYY-MM-DD
			$outlet_id = $this->session->userdata('logged_in')['OUTLET_ID'] ?? null;

			$transactions = $this->M_transaction->getAllTransactions($outlet_id, $date);

			echo json_encode($transactions);
		}


	}



