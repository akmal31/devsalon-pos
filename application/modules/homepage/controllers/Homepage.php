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
			$this->load->model('homepage/M_attendance');
			
		}

		public function index()
		{
			$user = $this->session->userdata('logged_in');
			$isOwner = ($user['USER_GROUP_ID'] == 1);

			$selectedOutlet = $this->input->get('outlet_id') ?? null;

			if (!$isOwner) {
				$selectedOutlet = $user['OUTLET_ID'] ?? null;
			}

			$stats         = $this->M_homepage->getTodayStats($selectedOutlet);
			$transactions  = $this->M_homepage->getTodayTransactions($selectedOutlet);

			$listCabang = $isOwner 
				? $this->M_homepage->getListCabang() 
				: [$this->M_homepage->getCabang($selectedOutlet)];

			if ($isOwner && empty($selectedOutlet)) {
				$cash_laci = $this->db->select_sum('cash_laci')->get('outlet')->row()->cash_laci;
				$dataCabang = ['cash_laci' => $cash_laci];
			} else {
				$dataCabang = $this->db->get_where('outlet', ['outlet_id' => $selectedOutlet])->row_array();
			}

			// ==================================
			// ABSENSI LOGIC MULAI DI SINI
			// ==================================

			// Cek apakah absensi hari ini ada
			$countToday = $this->M_attendance->countTodayAttendance($selectedOutlet);

			if ($countToday > 0) {
				// Sudah ada → ambil absensi
				$attendance = $this->M_attendance->getTodayAttendance($selectedOutlet);
			} else {
				// Belum ada → ambil list user
				$attendance = $this->M_attendance->getUsersByOutlet($selectedOutlet);

				// Tambahkan status default biar view tetap sama
				foreach ($attendance as &$row) {
					$row['status'] = 'tidak masuk';
				}
			}

			// ==================================

			$data = [
				'todayRevenue' => $stats['todayRevenue'],
				'todayCustomer'=> $stats['todayCustomer'],
				'todayExpense' => $stats['todayExpense'],
				'dataCabang'   => $dataCabang,
				'transactions' => $transactions,
				'listCabang'   => $listCabang,
				'selectedOutlet' => $selectedOutlet,
				'user_profile' => $this->data['user_profile'],

				// >> passing data attendance
				'attendance' => $attendance
			];

			$view = $isOwner ? 'homepage/v_owner_list' : 'homepage/v_list';
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
					"type"		=> $d['type'],
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
					'type'           => $item['type'],
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

		public function saveToday()
		{
			$user = $this->session->userdata('logged_in');
			$outlet_id = $user['OUTLET_ID'];
			$today = date('Y-m-d');
			$post = $this->input->post('status'); 

			if (!empty($post)) {
				foreach ($post as $user_id => $val) {
					$status = $val; // langsung 'masuk' atau 'tidak masuk'
					$this->M_attendance->saveOrUpdate($user_id, $today, $status);
				}
			}

			// Ambil data absensi terbaru setelah simpan
			$latest = $this->M_attendance->getTodayAttendance($outlet_id);

			echo json_encode([
				'status'  => true,
				'message' => 'Absensi hari ini sudah disimpan',
				'data'    => $latest
			]);
		}

	}