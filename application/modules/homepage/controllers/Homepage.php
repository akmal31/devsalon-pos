<?php
	defined('BASEPATH') OR exit('no direct script access allowed');

	class Homepage extends MX_Controller {

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
			$this->load->model('user/M_user');
			
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

			$this->load->model('homepage/M_homepage');
			
		}

		public function index()
		{
			$user = $this->session->userdata('logged_in');
			$isOwner = ($user['USER_GROUP_ID'] == 1);

			// Ambil pilihan outlet dari GET (khusus owner)
			$selectedOutlet = $this->input->get('outlet_id') ?? null;

			// Jika bukan owner, pakai outlet user
			if (!$isOwner) {
				$selectedOutlet = $user['OUTLET_ID'] ?? null;
			}

			// Ambil model
			$stats         = $this->M_homepage->getTodayStats($selectedOutlet);
			$transactions  = $this->M_homepage->getTodayTransactions($selectedOutlet);

			// Ambil list cabang — owner dapat semua, user hanya outlet-nya
			$listCabang = $isOwner 
				? $this->M_homepage->getListCabang() 
				: [$this->M_homepage->getCabang($selectedOutlet)];

			// Jika owner memilih "Semua Cabang"
			if ($isOwner && empty($selectedOutlet)) {
				// Hitung total cash laci seluruh outlet
				$cash_laci = $this->db->select_sum('cash_laci')->get('outlet')->row()->cash_laci;
				$dataCabang = ['cash_laci' => $cash_laci];
			} else {
				// Ambil data outlet tertentu
				$dataCabang = $this->db->get_where('outlet', ['outlet_id' => $selectedOutlet])->row_array();
			}

			// Data ke view
			$data = [
				'todayRevenue' => $stats['todayRevenue'],
				'todayCustomer'=> $stats['todayCustomer'],
				'todayExpense' => $stats['todayExpense'],
				'dataCabang'   => $dataCabang,
				'transactions' => $transactions,
				'listCabang'   => $listCabang,
				'selectedOutlet' => $selectedOutlet,
				'user_profile' => $this->data['user_profile']
			];

			// Pilih view berdasarkan role
			$view = $isOwner ? 'v_owner_list' : 'v_list';
			$this->load->view($view, $data);
		}

		public function add_transaction(){
			$this->data["all_karyawan"] = $this->M_homepage->getListCapsterStylist();
			$this->load->view('add_transaction', $this->data);
		}

		public function api_services_and_packages() {
			$services = $this->M_homepage->getListServices();
			$packages = $this->M_homepage->getListPackagesWithServices();

			echo json_encode([
				"status" => true,
				"data" => [
					"services" => $services,
					"packages" => $packages
				]
			]);

		}

		public function ringkasan_transaksi()
		{
			$cart_json = $this->input->post('cart_data');
			$cart = json_decode($cart_json, true);

			// ambil data karyawan dari database
			$karyawanList = $this->M_homepage->getListCapsterStylist();

			// mapping id → name
			$karyawanMap = [];
			foreach ($karyawanList as $k) {
				$karyawanMap[$k['id']] = $k['name'];
			}

			// ============================
			//    HITUNG TOTAL KESELURUHAN
			// ============================
			$grandTotal = 0;
			foreach ($cart as $item) {
				$grandTotal += $item['total'];
			}

			// kirim ke view
			$this->data['cart'] = $cart;
			$this->data['karyawanMap'] = $karyawanMap;
			$this->data['grandTotal'] = $grandTotal;

			$this->load->view('ringkasan_transaksi', $this->data);
		}

		public function ringkasan_transaksi_saved($transaction_id)
		{
			// Ambil transaksi utama
			$tr = $this->M_homepage->getTransactionById($transaction_id);
			if (!$tr) {
				echo json_encode(["status" => false, "message" => "Data tidak ditemukan"]);
				return;
			}

			// Ambil detail item (service/package)
			$details = $this->M_homepage->getTransactionDetails($transaction_id);

			$result = [];

			foreach ($details as $d) {

				// Ambil komisi per staff untuk detail ini, join ke table users
				$staffs = $this->db->select("ts.user_id, u.name, ts.price, ts.komisi")
					->from("transaction_staff ts")
					->join("users u", "u.id = ts.user_id", "left")
					->where("ts.transaction_detail_id", $d['id'])
					->get()->result_array();

				$result[] = [
					"detail_id" => $d['id'],
					"item_name" => $d['item_name'],
					"price"     => (int)$d['price'],
					"total"     => (int)$d['total'],
					"staff"     => $staffs
				];
			}

			echo json_encode([
				"status" => true,
				"data" => [
					"customer_name" => $tr['customer_name'] ?? "-",
					"details"       => $result
				]
			]);
		}

		public function update_komisi()
		{
			$json = json_decode($this->input->raw_input_stream, true);
			if (!$json) {
				echo json_encode(["status" => false, "message" => "Invalid JSON"]);
				return;
			}

			$data = $json['data'] ?? [];
			$transactionId = $json['transaction_id'] ?? null;

			if (!$transactionId || empty($data)) {
				echo json_encode(["status" => false, "message" => "Data tidak valid"]);
				return;
			}

			foreach ($data as $row) {
				$this->db->set("price", $row['price']);
				$this->db->where("transaction_detail_id", $row['transaction_detail_id']);
				$this->db->where("user_id", $row['staff_id']);
				$this->db->update("transaction_staff");
			}

			echo json_encode(["status" => true, "message" => "Komisi berhasil diperbarui"]);
		}

		public function preview_struk($transaction_id = null)
		{
			if(!$transaction_id){
				show_error("Transaction ID tidak valid");
				return;
			}

			// data cabang
			$this->data['cabang'] = $this->M_homepage->getCabang();
			// Ambil data transaksi
			$transaction = $this->M_homepage->getTransactionById($transaction_id);
			if(!$transaction){
				show_error("Transaksi tidak ditemukan");
				return;
			}

			$this->data['cart']         = $this->M_homepage->getTransactionDetails($transaction_id);
			$this->data['grand_total']  = $transaction['grand_total'];
			$this->data['metode_bayar'] = $transaction['payment_method'];
			$this->data['tips']         = $transaction['tips'];
			$this->data['uang_bayar']   = $transaction['uang_bayar'];
			$this->data['kembalian']    = $transaction['kembalian'];

			$this->load->view('preview_struk', $this->data);
		}


		public function config(){
			$this->data['user_group'] = $this->M_user_group->getFullById($this->session->userdata('logged_in')['USER_GROUP_ID']);
			$this->load->view('config', $this->data);
		}

		public function cekCustomer()
		{
			$phone = $this->input->post('phone');
			$customer = $this->M_homepage->getCustomer($phone);
			echo json_encode($customer);
		}

		public function simpanCustomer()
		{
			$data = [
				'name' => $this->input->post('name'),
				'phone' => $this->input->post('phone'),
				'birthday' => $this->input->post('birthday') ?: null, // optional
			];

			$insert = $this->M_homepage->simpan_customer($data);

			if($insert['status']){
				echo json_encode([
					'status' => 'success',
					'id'     => $insert['id'],      // id customer baru
					'name'   => $data['name'],      // nama customer
					'phone'  => $data['phone'],     // nomor HP
				]);
			} else {
				echo json_encode(['status' => 'error']);
			}
		}

		public function simpanTransaksi()
		{
			$cart_data    = json_decode($this->input->post('cart_data'), true);
			$member_id    = $this->input->post('memberId') ?: null;
			$user_id      = $this->session->userdata('user_id');
			$outlet_id    = $this->session->userdata('logged_in')['OUTLET_ID'];
			$payment      = $this->input->post('metode_bayar'); // cash/qris/card
			$tips         = (int) $this->input->post('tips') ?: 0;
			$uang_bayar   = (int) $this->input->post('uang_bayar') ?: 0;
			$kembalian    = (int) $this->input->post('kembalian') ?: 0;

			if(empty($cart_data)){
				echo json_encode(['status'=>'error','message'=>'Cart kosong']);
				return;
			}

			$this->db->trans_start();

			$total_price = 0;
			foreach($cart_data as $item){
				$total_price += $item['total'];
			}

			// Atur uang bayar & kembalian
			if($payment != 'cash'){
				$uang_bayar = $total_price;
			}

			$trans = [
				'customer_id'    => $member_id,
				'user_id'        => $user_id,
				'outlet_id'      => $outlet_id,
				'total_price'    => $total_price,
				'grand_total'    => $total_price,
				'payment_method' => $payment,
				'tips'           => $tips,
				'uang_bayar'     => $uang_bayar,
				'kembalian'      => $kembalian,
				'created_at'     => date('Y-m-d H:i:s')
			];
			$this->db->insert('transactions', $trans);
			$transaction_id = $this->db->insert_id();

			foreach($cart_data as $item){
				// Insert transaction_details
				$detail = [
					'transaction_id' => $transaction_id,
					'reference_id'   => $item['id'],
					'type'          => $item['type'],
					'price'          => $item['price'],
					'quantity'       => $item['qty'],
					'discount'       => $item['discount'] ?? 0,
					'total'          => $item['total']
				];
				$this->db->insert('transaction_details', $detail);
				$detail_id = $this->db->insert_id();

				// Transaction staff
				if(isset($item['karyawan']) && is_array($item['karyawan']) && count($item['karyawan']) > 0){
					$num_staff = count($item['karyawan']);
					$price_per_staff = $item['total'] / $num_staff;

					foreach($item['karyawan'] as $idx => $user_id){
						// Ambil commission_percent dari tabel users
						$user = $this->db->get_where('users', ['id' => $user_id])->row();
						$commission_percent = $user->commission_percent ?? 0; // default 0 kalau null

						$staff = [
							'transaction_detail_id' => $detail_id,
							'user_id'              => $user_id,
							'price'                => $price_per_staff,
							'komisi'               => $price_per_staff * ($commission_percent / 100)
						];
						$this->db->insert('transaction_staff', $staff);
					}
				}
			}

			// Tambah cash laci jika bayar cash
			if ($payment == 'cash') {

				$cashMasuk = $total_price;

				$this->db->set('cash_laci', 'cash_laci + ' . (int)$cashMasuk, FALSE);
				$this->db->where('outlet_id', $outlet_id);
				$this->db->update('outlet');

				// Tambahkan mutasi_outlet (pemasukan)
				$mutasi = [
					'outlet_id'     => $outlet_id,
					'tipe_mutasi'   => 'pemasukan',
					'reference_id'  => $transaction_id,
					'nominal'       => $cashMasuk,
					'created_at'    => date('Y-m-d H:i:s')
				];
				$this->db->insert('mutasi_outlet', $mutasi);
			}else{

				$cashKeluar = $tips;

				$this->db->set('cash_laci', 'cash_laci - ' . (int)$cashKeluar, FALSE);
				$this->db->where('outlet_id', $outlet_id);
				$this->db->update('outlet');

				// Tambahkan mutasi_outlet (pemasukan)
				$mutasi = [
					'outlet_id'     => $outlet_id,
					'tipe_mutasi'   => 'pengeluaran',
					'reference_id'  => $transaction_id,
					'nominal'       => $cashKeluar,
					'created_at'    => date('Y-m-d H:i:s')
				];
				$this->db->insert('mutasi_outlet', $mutasi);				
			}

			$this->db->trans_complete();

			if($this->db->trans_status() === false){
				$this->db->trans_rollback();
				echo json_encode(['status'=>'error','message'=>'Gagal menyimpan transaksi']);
			} else {
				$this->db->trans_commit();
				echo json_encode(['status'=>'success','transaction_id'=>$transaction_id]);
			}
		}


	}



