<?php
	defined('BASEPATH') OR exit('no direct script access allowed');
	
	class M_transaction extends CI_Model {
		
		public function __construct(){
			parent::__construct();
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

		public function getTransactionById($id){
			return $this->db->get_where('transactions', ['id'=>$id])->row_array();
		}

		public function getTransactionDetails($transaction_id)
		{
			$this->db->select("
				td.*,
				CASE 
					WHEN td.type = 'service' THEN s.name
					WHEN td.type = 'package' THEN p.name
					ELSE NULL
				END AS item_name
			");
			$this->db->from('transaction_details td');
			$this->db->join('services s', "s.id = td.reference_id AND td.type = 'service'", 'left');
			$this->db->join('packages p', "p.id = td.reference_id AND td.type = 'package'", 'left');
			$this->db->where('td.transaction_id', $transaction_id);

			return $this->db->get()->result_array();
		}

		public function getTodayStats($outlet_id = null)
		{
			// filter outlet jika ada
			$this->db->select('
				SUM(grand_total) as todayRevenue,
				COUNT(DISTINCT customer_id) as todayCustomer
			');
			$this->db->from('transactions');
			
			if($outlet_id){
				$this->db->where('outlet_id', $outlet_id);
			}

			// hanya transaksi hari ini
			$this->db->where('DATE(created_at)', date('Y-m-d'));

			$query = $this->db->get();
			$row = $query->row();

			return [
				'todayRevenue' => (float)($row->todayRevenue ?? 0),
				'todayCustomer'=> (int)($row->todayCustomer ?? 0)
			];
		}

		public function getAllTransactions($outlet_id = null, $date = null)
		{
			$this->db->select('t.id, t.grand_total, t.payment_method, t.created_at, c.name as customer_name');
			$this->db->from('transactions t');
			$this->db->join('customers c', 't.customer_id = c.id', 'left');

			if ($outlet_id) {
				$this->db->where('t.outlet_id', $outlet_id);
			}

			if ($date) {
				// Filter transaksi sesuai tanggal (format YYYY-MM-DD)
				$this->db->where('DATE(t.created_at)', $date);
			}

			$this->db->order_by('t.created_at', 'DESC');

			$query = $this->db->get();
			return $query->result_array();
		}

		
	}