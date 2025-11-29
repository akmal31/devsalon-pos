<?php
	defined('BASEPATH') OR exit('no direct script access allowed');

	class Services extends MX_Controller {

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
			$this->load->model('services/M_services');
			$this->load->model('services/M_package');
			
		}

		public function index()
		{
			$this->load->view('v_list');
		}

		public function package()
		{
			$this->load->view('v_list_package');
		}

		public function searchServices()
		{
			$keyword = $this->input->post('keyword');
			$result = $this->M_services->searchServices($this->session->userdata('logged_in')['OUTLET_ID'],$keyword);

			echo json_encode($result);
		}

		public function searchPackage()
		{
			$keyword = $this->input->post('keyword');
			$result = $this->M_package->searchPackage($this->session->userdata('logged_in')['OUTLET_ID'],$keyword);

			echo json_encode($result);
		}

		public function save()
		{
			$barang   = $this->input->post('barang');
			$tanggal  = $this->input->post('tanggal');
			$metode   = $this->input->post('metode'); // cash / transfer dll
			$nominal  = (int) $this->input->post('nominal');
			$outlet_id = $this->session->userdata('logged_in')['OUTLET_ID'] ?? null;

			if (!$barang || !$tanggal || !$metode || !$nominal) {
				echo json_encode(["status" => false, "message" => "Semua field wajib diisi"]);
				return;
			}

			// 1. INSERT ke services
			$data = [
				'outlet_id' => $outlet_id,
				'name' => $barang,
				'tanggal_transaksi' => $tanggal,
				'status' => 1,
				'payment_method' => $metode,
				'total_price' => $nominal
			];

			$services_id = $this->M_services->insertServices($data);

			if (!$services_id) {
				echo json_encode(["status" => false, "message" => "Gagal insert services"]);
				return;
			}

			// 2. KURANGI cash_laci jika metode cash
			if ($metode == "cash") {
				$this->M_services->kurangiCashLaci($outlet_id, $nominal);
			}

			// 3. INSERT ke mutasi_outlet
			$mutasi = [
				'outlet_id'     => $outlet_id,
				'tipe_mutasi'   => 'services',
				'reference_id'  => $services_id,
				'nominal'       => $nominal,
				'created_at'    => $tanggal
			];

			$this->M_services->insertMutasiOutlet($mutasi);

			echo json_encode(["status" => true]);
		}

		public function delete($id)
		{
			$result = $this->M_services->deleteService($id);

			echo json_encode([
				'status' => $result ? true : false
			]);
		}

		public function getServiceById()
		{
			$id = $this->input->post("id");
			$data = $this->M_services->getServiceById($id);
			echo json_encode($data);
		}

		public function getPackage($id)
		{
			// Ambil data paket
			$package = $this->M_package->getPackageById($id);

			if (!$package) {
				echo json_encode(["status" => false, "message" => "Paket tidak ditemukan"]);
				return;
			}

			// Ambil detail service di paket
			$services = $this->M_package->getPackageServices($id);
			
			$package['services'] = $services;

			echo json_encode($package);
		}


		public function getOutlets()
		{
			$data = $this->db->get("outlet")->result();
			echo json_encode($data);
		}

		public function addPackage() {
			$name = $this->input->post("name");
			$services = json_decode($this->input->post("services"), true);
			$price = $this->input->post("price");
			$outlet_id = $this->input->post('outlet_id') ?? $this->session->userdata('logged_in')['OUTLET_ID'];

			$insert = [
				"name" => $name,
				"price" => $price,
				"outlet_id" => $outlet_id,
				"created_at" => date("Y-m-d H:i:s")
			];

			$package_id = $this->M_package->insertPackage($insert);

			foreach ($services as $service_id => $service_price) {
				$this->M_package->insertPackageDetail([
					"package_id" => $package_id,
					"service_id" => $service_id,
					"price" => $service_price
				]);
			}

			echo json_encode(["status" => true]);
		}

		public function updatePackage($id)
		{
			$name     = $this->input->post('name');
			$price    = $this->input->post('price');
			$services = json_decode($this->input->post('services'), true); // array

			// --- Update tabel package ---
			$dataUpdate = [
				'name'  => $name,
				'price' => $price
			];
			$this->M_package->updatePackage($id, $dataUpdate);

			// --- Update harga detail paket ---
			$this->M_package->updatePackageDetails($id, $services);

			echo json_encode(['status' => true]);
		}

		public function deletePackage() {
			$id = $this->input->post('id');

			if (!$id) {
				echo json_encode([
					'status' => false,
					'message' => 'ID tidak ditemukan'
				]);
				return;
			}

			$delete = $this->M_package->deletePackage($id);

			echo json_encode([
				'status' => $delete ? true : false,
				'message' => $delete ? 'Service berhasil dihapus' : 'Gagal menghapus service'
			]);
		}		

		public function addService()
		{
			$name      = $this->input->post('name');
			$duration  = $this->input->post('duration');
			$price     = $this->input->post('price');
			$outlet_id = $this->input->post('outlet_id') ?? $this->session->userdata('logged_in')['OUTLET_ID'];

			if (!$name || !$duration || !$price) {
				echo json_encode(["status" => false, "message" => "Data belum lengkap"]);
				return;
			}

			$insert = $this->M_services->insert_service([
				"name"       => $name,
				"duration"   => $duration,
				"price"      => $price,
				"outlet_id"  => $outlet_id
			]);

			echo json_encode([
				"status" => $insert ? true : false,
				"message" => $insert ? "Berhasil menambahkan layanan" : "Gagal menambahkan"
			]);
		}

		public function updateService()
		{
			$id = $this->input->post('id');

			$data = [
				'name'      => $this->input->post('name'),
				'duration'  => $this->input->post('duration'),
				'price'     => $this->input->post('price'),
			];

			// Jika owner (user_group_id = 1), boleh ganti outlet
			if ($this->session->userdata('logged_in')['USER_GROUP_ID'] == 1) {
				$data['outlet_id'] = $this->input->post('outlet_id');
			} else {
				$data['outlet_id'] = $this->session->userdata('logged_in')['OUTLET_ID'];
			}

			$result = $this->M_services->updateService($id, $data);

			echo json_encode([
				'status' => $result ? true : false
			]);
		}

	}