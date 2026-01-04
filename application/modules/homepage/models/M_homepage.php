<?php
	defined('BASEPATH') OR exit('no direct script access allowed');
	
	class M_homepage	extends CI_Model {
		
		public function __construct(){
			parent::__construct();
		}

		public function getListServices() {
			$this->db->select('*');
			$this->db->from('services');
			$this->db->where('outlet_id', $this->session->userdata('logged_in')['OUTLET_ID']);
			return $this->db->get()->result_array();
		}

		public function getListPackagesWithServices() {
			$outlet = $this->session->userdata('logged_in')['OUTLET_ID'];

			// Ambil package
			$this->db->select('package_id, name, price');
			$this->db->from('package');
			$this->db->where('outlet_id', $outlet);
			$this->db->where('deleted_at IS NULL', null, false);
			$this->db->order_by('name', 'ASC');
			$packages = $this->db->get()->result_array();

			// Ambil detail untuk setiap paket
			foreach ($packages as &$p) {
				$this->db->select('s.id, s.name, pd.price, s.price as original_price');
				$this->db->from('package_detail pd');
				$this->db->join('services s', 's.id = pd.service_id');
				$this->db->where('pd.package_id', $p['package_id']);
				$this->db->order_by('s.name', 'ASC');

				$p['services'] = $this->db->get()->result_array();
			}

			return $packages;
		}

		public function getListCabang() {
			$this->db->select('*');
			$this->db->from('outlet');
			return $this->db->get()->result_array();
		}
		
		public function getListCapsterStylist() {
			
			$result = null;
			$this->db->select('a.*');
			$this->db->from('users a');
			$this->db->where_in('user_group_id', [3,4]);
			$this->db->where('outlet_id', $this->session->userdata('logged_in')['OUTLET_ID']);
			$query = $this->db->get();
			
			if ($query->num_rows() > 0) {
				foreach ($query->result_array() as $row) {
					$result[] = $row;
				}
			}	
			
			return $result;
		} 
		
		public function getCabang() {
			
			$result = null;
			$this->db->select('a.*');
			$this->db->from('outlet a');
			$this->db->where('outlet_id', $this->session->userdata('logged_in')['OUTLET_ID']);
			$this->db->limit(1);
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				$row = $query->result_array();
				$result = $row[0];
			}
			
			return $result;
		}
		
		public function getCustomer($phone) {
			
			$result = null;
			$this->db->select('a.*');
			$this->db->from('customers a');
			$this->db->where('phone', $phone);
			$this->db->limit(1);
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				$row = $query->result_array();
				$result = $row[0];
			}
			
			return $result;
		}
		
		public function simpan_customer($arr){					
			$result = false;
			$insert_id = null;

			if (count($arr) > 0) {
				$active = (isset($arr['active']) && $arr['active'] == 'on') ? 1 : 0;

				$this->db->trans_start();

				// Insert
				$this->db->set('name', $arr['name']);
				$this->db->set('phone', $arr['phone']);
				$this->db->set('birthday', $arr['birthday']);

				$this->db->insert('customers');

				// Ambil ID terakhir
				$insert_id = $this->db->insert_id();

				$this->db->trans_complete();

				if ($this->db->trans_status() === false) {
					$this->db->trans_rollback();
					$result = false;
					$insert_id = null;
				} else {
					$this->db->trans_commit();
					$result = true;
				}
			}

			// Kembalikan array berisi status + id
			return ['status' => $result, 'id' => $insert_id];
		}

		
		public function simpanTransaksi()
		{
			$cart_data    = json_decode($this->input->post('cart_data'), true); // array cart
			$member_id    = $this->input->post('memberId') ?: null;
			$user_id      = $this->session->userdata('user_id'); // akun yg input transaksi
			$outlet_id    = $this->session->userdata('logged_in')['OUTLET_ID'];
			$payment      = $this->input->post('metode_bayar'); // cash/qris/card
			$tips         = (int) $this->input->post('tips') ?: 0;
			$uang_bayar   = (int) $this->input->post('uang_bayar') ?: 0;
			$kembalian    = (int) $this->input->post('kembalian') ?: 0;

			if(empty($cart_data)) {
				echo json_encode(['status' => 'error', 'message' => 'Cart kosong']);
				return;
			}

			$this->db->trans_start();

			// hitung total
			$total_price = 0;
			foreach($cart_data as $item){
				$total_price += $item['total']; 
			}

			// 1. Insert ke transactions
			$trans = [
				'customer_id'   => $member_id,
				'user_id'       => $user_id,
				'outlet_id'     => $outlet_id,
				'total_price'   => $total_price,
				'grand_total'   => $total_price, 
				'payment_method'=> $payment,
				'tips'          => $tips,
				'uang_bayar'    => $uang_bayar,
				'kembalian'     => $kembalian,
				'created_at'    => date('Y-m-d H:i:s')
			];

			$this->db->insert('transactions', $trans);
			$transaction_id = $this->db->insert_id();

			// 2. Insert ke transaction_details & transaction_staff
			foreach($cart_data as $item){
				$detail = [
					'transaction_id' => $transaction_id,
					'service_id'     => $item['id'],      
					'price'          => $item['price'],
					'quantity'       => $item['quantity'],
					'discount'       => $item['discount'] ?? 0,
					'total'          => $item['total']
				];
				$this->db->insert('transaction_details', $detail);
				$detail_id = $this->db->insert_id();

				// 3. transaction_staff
				if(isset($item['staff_id'])){
					$staff = [
						'transaction_detail_id' => $detail_id,
						'user_id'              => $item['staff_id'],
						'komisi'               => $item['komisi'] ?? 0
					];
					$this->db->insert('transaction_staff', $staff);
				}
			}

			$this->db->trans_complete();

			if ($this->db->trans_status() === false) {
				$this->db->trans_rollback();
				echo json_encode(['status'=>'error','message'=>'Gagal menyimpan transaksi']);
			} else {
				$this->db->trans_commit();
				echo json_encode(['status'=>'success','transaction_id'=>$transaction_id]);
			}
		}

		public function getTransactionById($id){
			return $this->db->get_where('transactions', ['id'=>$id])->row_array();
		}

		public function getTransactionDetails($transaction_id)
		{
			$this->db->select("
				td.*,
				CASE 
					WHEN td.type = 'service' THEN s.name
					WHEN td.type = 'product' THEN pr.name
					WHEN td.type = 'package' THEN p.name
				END AS item_name
			");

			$this->db->from('transaction_details td');
			$this->db->join('services s', 's.id = td.reference_id AND td.type = "service"', 'left');
			$this->db->join('services pr', 'pr.id = td.reference_id AND td.type = "product"', 'left');
			$this->db->join('package p', 'p.package_id = td.reference_id AND td.type = "package"', 'left');

			$this->db->where('td.transaction_id', $transaction_id);
			return $this->db->get()->result_array();
		}


		public function getTodayStats($outlet_id = null)
		{
			// ==========================
			// 1. Ambil pendapatan hari ini
			// ==========================
			$this->db->select('
				SUM(grand_total) as todayRevenue,
				COUNT(DISTINCT customer_id) as todayCustomer
			');
			$this->db->from('transactions');

			if ($outlet_id) {
				$this->db->where('outlet_id', $outlet_id);
			}

			$this->db->where('DATE(created_at)', date('Y-m-d'));

			$trxRow = $this->db->get()->row();

			$todayRevenue  = (float)($trxRow->todayRevenue ?? 0);
			$todayCustomer = (int)($trxRow->todayCustomer ?? 0);


			// ==========================
			// 2. Ambil total pengeluaran cash hari ini
			// ==========================
			$this->db->select('SUM(total_price) AS todayExpense');
			$this->db->from('pengeluaran');
			$this->db->where('tipe', 'pengeluaran');

			if ($outlet_id) {
				$this->db->where('outlet_id', $outlet_id);
			}

			$this->db->where_in('status', [1, 2]); // pengajuan & approve
			$this->db->where('DATE(tanggal_transaksi)', date('Y-m-d'));

			$expRow = $this->db->get()->row();
			$todayExpense = (float)($expRow->todayExpense ?? 0);


			// ==========================
			// 3. Return lengkap
			// ==========================
			return [
				'todayRevenue'  => $todayRevenue,
				'todayCustomer' => $todayCustomer,
				'todayExpense'  => $todayExpense
			];
		}

		public function getThisMonthStats($outlet_id = null)
		{
			// range bulan ini
			$start = date('Y-m-01 00:00:00');
			$end   = date('Y-m-t 23:59:59');

			// ==========================
			// 1. Pendapatan bulan ini
			// ==========================
			$this->db->select('
				SUM(grand_total) AS monthRevenue,
				COUNT(DISTINCT customer_id) AS monthCustomer
			');
			$this->db->from('transactions');

			if ($outlet_id) {
				$this->db->where('outlet_id', $outlet_id);
			}

			$this->db->where('created_at >=', $start);
			$this->db->where('created_at <=', $end);

			$trxRow = $this->db->get()->row();

			$monthRevenue  = (float)($trxRow->monthRevenue ?? 0);
			$monthCustomer = (int)($trxRow->monthCustomer ?? 0);

			// ==========================
			// 2. Pengeluaran cash bulan ini
			// ==========================
			$this->db->select('SUM(total_price) AS monthExpense');
			$this->db->from('pengeluaran');
			$this->db->where('tipe', 'pengeluaran');

			if ($outlet_id) {
				$this->db->where('outlet_id', $outlet_id);
			}

			// $this->db->where('payment_method', 'cash');
			$this->db->where_in('status', [1, 2]); // pengajuan & approve
			$this->db->where('tanggal_transaksi >=', $start);
			$this->db->where('tanggal_transaksi <=', $end);

			$expRow = $this->db->get()->row();
			$monthExpense = (float)($expRow->monthExpense ?? 0);

			// ==========================
			// 3. Return
			// ==========================
			return [
				'monthRevenue'  => $monthRevenue,
				'monthCustomer' => $monthCustomer,
				'monthExpense'  => $monthExpense
			];
		}

		public function getTodayTransactions($outlet_id = null)
		{
			$this->db->select('t.id, t.grand_total, t.payment_method, t.created_at, c.name as customer_name');
			$this->db->from('transactions t');
			$this->db->join('customers c', 't.customer_id = c.id', 'left');

			if ($outlet_id) {
				$this->db->where('t.outlet_id', $outlet_id);
			}

			// transaksi hari ini
			$this->db->where('DATE(t.created_at)', date('Y-m-d'));

			// urut dari terbaru
			$this->db->order_by('t.created_at', 'DESC');

			$query = $this->db->get();
			return $query->result_array();
		}

	}