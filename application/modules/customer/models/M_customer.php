<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_customer extends CI_Model
{

	public function searchCustomer($outlet_id = null, $keyword = "")
	{
		return $this->db
			->select('
				c.*,
				COUNT(t.id) AS total_transaction,
				COALESCE(SUM(t.grand_total),0) AS total_rupiah
			')
			->from('customers c')
			->join('transactions t', 't.customer_id = c.id', 'left')
			->group_by('c.id')
			->group_start()
				->like('c.name', $keyword)
				->or_like('c.phone', $keyword)
			->group_end()
			->order_by('total_rupiah', 'DESC')
			->get()
			->result();
	}


    // Ambil service by id (pastikan bukan yang sudah dihapus)
    public function getCustomerById($id)
    {
        $this->db->where('id', $id);
        $this->db->where('deleted_at IS NULL', null, false);
        return $this->db->get('customers')->row_array();
    }

	public function insert_service($data)
	{
		return $this->db->insert("customers", $data);
	}

    // Update service + updated_at
	public function updateCustomer($id, $data)
	{
		$updateData = [
			'name'      => $data['name'],
			'duration'  => $data['duration'],
			'price'     => $data['price'],
			'updated_at'=> date('Y-m-d H:i:s')
		];

		// Tambahkan outlet_id HANYA jika tidak null
		if (!empty($data['outlet_id'])) {
			$updateData['outlet_id'] = $data['outlet_id'];
		}

		$this->db->where('id', $id);
		return $this->db->update('customers', $updateData);
	}

    // Soft delete -> set deleted_at
    public function deleteCustomer($id)
    {
        $this->db->where('id', $id);
        return $this->db->update('customers', [
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
    }
}
