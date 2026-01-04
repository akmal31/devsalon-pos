<?php
	defined('BASEPATH') OR exit('no direct script access allowed');

	class Pengeluaran extends MX_Controller {

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

			$this->load->model('pengeluaran/M_pengeluaran');
			
		}

		public function index()
		{
			$this->load->view('v_list');
		}


		public function add_pengeluaran(){
			$this->load->view('add_pengeluaran', $this->data);
		}

		public function filterPengeluaran()
		{
			$date = $this->input->post('date'); // YYYY-MM-DD
			$outlet_id = $this->session->userdata('logged_in')['OUTLET_ID'] ?? null;

			$pengeluaran = $this->M_pengeluaran->getAllPengeluaran($outlet_id, $date);

			echo json_encode($pengeluaran);
		}

		public function save()
		{
			$barang   = $this->input->post('barang');
			$tanggal  = $this->input->post('tanggal');
			$metode   = $this->input->post('metode'); // cash / transfer dll
			$tipe   = $this->input->post('tipe'); 
			$nominal  = (int) $this->input->post('nominal');
			$outlet_id = $this->session->userdata('logged_in')['OUTLET_ID'] ?? null;

			if (!$barang || !$tanggal || !$metode || !$nominal || !$tipe ) {
				echo json_encode(["status" => false, "message" => "Semua field wajib diisi"]);
				return;
			}

			// 1. INSERT ke pengeluaran
			$data = [
				'outlet_id' => $outlet_id,
				'name' => $barang,
				'tanggal_transaksi' => $tanggal,
				'status' => 1,
				'payment_method' => $metode,
				'tipe' => $tipe,
				'total_price' => $nominal
			];

			$pengeluaran_id = $this->M_pengeluaran->insertPengeluaran($data);

			if (!$pengeluaran_id) {
				echo json_encode(["status" => false, "message" => "Gagal insert pengeluaran"]);
				return;
			}

			// 2. KURANGI cash_laci jika metode cash
			if ($metode == "cash") {
				$this->M_pengeluaran->kurangiCashLaci($outlet_id, $nominal);
			}

			// 3. INSERT ke mutasi_outlet
			$mutasi = [
				'outlet_id'     => $outlet_id,
				'tipe_mutasi'   => 'pengeluaran',
				'reference_id'  => $pengeluaran_id,
				'nominal'       => $nominal,
				'created_at'    => $tanggal
			];

			$this->M_pengeluaran->insertMutasiOutlet($mutasi);

			echo json_encode(["status" => true]);
		}

		public function delete($id)
		{
			$result = $this->M_pengeluaran->deletePengeluaran($id);

			echo json_encode([
				'status' => $result ? true : false
			]);
		}

	}