<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_services extends CI_Model
{

	public function searchServices($outlet_id = null, $keyword = "")
	{
		$this->db->select('*');
		$this->db->from('services');

		if (!empty($keyword)) {
			$this->db->like('name', $keyword);
		}
		if (!empty($outlet_id)) {
			$this->db->where('outlet_id', $outlet_id);
		}
		$this->db->where('deleted_at IS NULL', null, false);

		$this->db->order_by('name', 'ASC');

		return $this->db->get()->result();
	}

    // Ambil service by id (pastikan bukan yang sudah dihapus)
    public function getServiceById($id)
    {
        $this->db->where('id', $id);
        $this->db->where('deleted_at IS NULL', null, false);
        return $this->db->get('services')->row_array();
    }

	public function insert_service($data)
	{
		return $this->db->insert("services", $data);
	}

    // Update service + updated_at
	public function updateService($id, $data)
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
		return $this->db->update('services', $updateData);
	}

    // Soft delete -> set deleted_at
    public function deleteService($id)
    {
        $this->db->where('id', $id);
        return $this->db->update('services', [
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
    }
}
