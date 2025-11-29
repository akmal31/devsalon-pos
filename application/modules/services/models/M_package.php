<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_package extends CI_Model
{

	public function searchPackage($outlet_id = null, $keyword = "")
	{
		$this->db->select('*');
		$this->db->from('package');

		if (!empty($keyword)) {
			$this->db->like('name', $keyword);
		}
		if (!empty($outlet_id)) {
			$this->db->where('outlet_id', $outlet_id);
		}

		$this->db->where('deleted_at IS NULL', null, false);
		$this->db->order_by('name', 'ASC');

		$packages = $this->db->get()->result();

		// AMBIL DETAIL PERAWATAN TIAP PAKET
		foreach ($packages as &$p) {
			$p->services = $this->db->select("s.id, s.name, pd.price as price")
				->from("package_detail pd")
				->join("services s", "s.id = pd.service_id")
				->where("pd.package_id", $p->package_id)
				->get()
				->result();
		}

		return $packages;
	}

    // Ambil service by id (pastikan bukan yang sudah dihapus)
    public function getPackageById($id)
    {
        $this->db->where('package_id', $id);
        $this->db->where('deleted_at IS NULL', null, false);
        return $this->db->get('package')->row_array();
    }

	public function getPackageServices($package_id)
	{
		return $this->db->select("s.id, s.name, ps.price, s.price as original_price")
						->from("package_detail ps")
						->join("services s", "s.id = ps.service_id", "left")
						->where("ps.package_id", $package_id)
						->get()
						->result();
	}


    public function insertPackage($data){
        $this->db->insert("package", $data);
        return $this->db->insert_id();
    }

    public function insertPackageDetail($data){
        return $this->db->insert("package_detail", $data);
    }

	public function updatePackage($id, $data)
	{
		$updateData = [
			'name'       => $data['name'],
			'price'      => $data['price'],
			'update_at' => date('Y-m-d H:i:s')
		];

		if (!empty($data['outlet_id'])) {
			$updateData['outlet_id'] = $data['outlet_id'];
		}

		$this->db->where('package_id', $id);
		return $this->db->update('package', $updateData);
	}

	/**
	 * Update harga masing-masing service di dalam paket
	 * $servicePrices = [ service_id => harga_baru ]
	 */
	public function updatePackageDetails($id, $servicePrices)
	{
		if (empty($servicePrices)) return;

		foreach ($servicePrices as $service_id => $price) {

			$this->db->where('package_id', $id);
			$this->db->where('service_id', $service_id);
			$this->db->update('package_detail', [
				'price'      => $price,
			]);
		}
	}


    // Soft delete -> set deleted_at
    public function deletePackage($id)
    {
        $this->db->where('package_id', $id);
        return $this->db->update('package', [
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
    }
}
