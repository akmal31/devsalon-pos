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
			$this->db->join('package p', "p.package_id = td.reference_id AND td.type = 'package'", 'left');
			$this->db->where('td.transaction_id', $transaction_id);

			return $this->db->get()->result_array();
		}

		public function getAllTransactions($outlet_id = null, $date = null)
		{
			$this->db->select('t.id, t.grand_total, t.payment_method, t.created_at, c.name as customer_name, o.name as outlet_name');
			$this->db->from('transactions t');
			$this->db->join('customers c', 't.customer_id = c.id', 'left');
			$this->db->join('outlet o', 't.outlet_id = o.outlet_id', 'left');

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

		public function delete_transaction($transaction_id)
		{
			$this->db->trans_begin();

			// 1. Ambil data transaksi
			$transaction = $this->db
				->get_where('transactions', ['id' => $transaction_id])
				->row();

			if (!$transaction) {
				$this->db->trans_rollback();
				return false;
			}

			// 2. Jika cash → kurangi cash_laci outlet
			if ($transaction->payment_method === 'cash') {
				$this->db->set(
					'cash_laci',
					'cash_laci - ' . (float)$transaction->grand_total,
					false
				);
				$this->db->where('outlet_id', $transaction->outlet_id);
				$this->db->update('outlet');
			}

			// 3. Ambil transaction_details ID
			$detail_ids = $this->db
				->select('id')
				->from('transaction_details')
				->where('transaction_id', $transaction_id)
				->get()
				->result_array();

			if (!empty($detail_ids)) {
				$detail_ids = array_column($detail_ids, 'id');

				// 4. Hapus transaction_staff
				$this->db->where_in('transaction_detail_id', $detail_ids);
				$this->db->delete('transaction_staff');
			}

			// 5. Hapus transaction_details
			$this->db->where('transaction_id', $transaction_id);
			$this->db->delete('transaction_details');

			// 6. Hapus mutasi_outlet
			$this->db->where([
				'reference_id' => $transaction_id,
				'tipe_mutasi'  => 'pemasukan'
			]);
			$this->db->delete('mutasi_outlet');

			// 7. Hapus transaction
			$this->db->where('id', $transaction_id);
			$this->db->delete('transactions');

			// Commit / Rollback
			if ($this->db->trans_status() === false) {
				$this->db->trans_rollback();
				return false;
			}

			$this->db->trans_commit();
			return true;
		}
		
	}