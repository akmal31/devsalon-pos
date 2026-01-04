<?php
	defined('BASEPATH') OR exit('no direct script access allowed');
	
	class M_pengeluaran extends CI_Model {
		
		public function __construct(){
			parent::__construct();
		}

		public function getPengeluaranById($id)
		{ 
			return $this->db->get_where('pengeluaranmutasi_outlet', ['id'=>$id])->row_array(); 
		}

		public function getAllPengeluaran($outlet_id = null, $date = null)
		{
			$this->db->select('p.*, o.name as outlet_name, o.address'); // sesuaikan kolom tabel outlet
			$this->db->from('pengeluaran p');
			$this->db->join('outlet o', 'o.outlet_id = p.outlet_id', 'left');

			if (!empty($outlet_id)) {
				$this->db->where('p.outlet_id', $outlet_id);
			}

			if (!empty($date)) {
				$this->db->where('DATE(p.tanggal_transaksi)', $date);
			}

			$this->db->order_by('p.tanggal_transaksi', 'DESC');

			return $this->db->get()->result_array();
		}

		public function insertPengeluaran($data)
		{
			$this->db->insert('pengeluaran', $data);
			return $this->db->insert_id();
		}

		public function insertMutasiOutlet($data)
		{
			return $this->db->insert('mutasi_outlet', $data);
		}

		public function kurangiCashLaci($outlet_id, $nominal)
		{
			// Kurangi cash_laci pada tabel outlet
			$this->db->set('cash_laci', "cash_laci - {$nominal}", false);
			$this->db->where('outlet_id', $outlet_id);
			return $this->db->update('outlet');
		}

		public function deletePengeluaran($id)
		{
			// Ambil data pengeluaran dulu
			$pengeluaran = $this->db->get_where('pengeluaran', ['id' => $id])->row();

			if (!$pengeluaran) return false;

			// Jika metode cash → kembalikan saldo laci
			if ($pengeluaran->payment_method == 'cash') {

				// Ambil outlet_id dari pengeluaran
				$outlet_id = $pengeluaran->outlet_id;
				$nominal   = $pengeluaran->total_price;

				// Update cash_laci (tambah nominal kembali)
				$this->db->set('cash_laci', 'cash_laci + ' . (int)$nominal, FALSE);
				$this->db->where('outlet_id', $outlet_id);
				$this->db->update('outlet');
			}

			// Hapus pengeluaran
			$this->db->delete('pengeluaran', ['id' => $id]);

			// Hapus mutasi terhubung
			$this->db->delete('mutasi_outlet', [
				'reference_id' => $id,
				'tipe_mutasi'  => 'pengeluaran'
			]);

			return true;
		}

		public function approve_pengeluaran($id)
		{
			// ambil data dulu (opsional tapi bagus)
			$pengeluaran = $this->db
				->get_where('pengeluaran', ['id' => $id])
				->row();

			if (!$pengeluaran) {
				return false;
			}

			// update status jadi approve (2)
			$this->db->where('id', $id);
			$this->db->update('pengeluaran', [
				'status'     => 2
			]);

			return $this->db->affected_rows() > 0;
		}


	}