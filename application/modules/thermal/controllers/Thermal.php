<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Thermal extends MX_Controller {

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

		$this->load->model('thermal/M_thermal');
		
	}	

	private function get_iphone_ip()
	{
		$user_id = $this->session->userdata('logged_in')['USER_ID'] ?? null;
		if (!$user_id) return null;

		return $this->M_thermal->get_kasir_ip($user_id);
	}	
    
	public function cetak_struk($data = [])
    {
        $iphone_ip = $this->get_iphone_ip();

        if (!$iphone_ip) {
            return "Error: IP kasir belum terdaftar. Buka halaman register printer dulu!";
        }

        // Build struk ESC/POS (sama kayak sebelumnya)
        $struk = "\x1B\x40\x1B\x61\x31TOKO KAMU\n\x1B\x61\x30";
        $struk .= "Ayam Geprek     Rp 15.000\n";
        $struk .= "Es Teh          Rp  5.000\n";
        $struk .= "----------------------------\n";
        $struk .= "Total           Rp 20.000\n\n";
        $struk .= "Terima Kasih!\n\n\n\x1D\x56\x00";

        $url = "http://{$iphone_ip}:{8080/print";

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $struk,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/octet-stream']
        ]);

        $result = curl_exec($ch);
        $http   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ($http == 200) ? "Struk berhasil dicetak!" : "Gagal cetak (HTTP $http)";
    }

	public function printer_register() {
        // Ambil IP iPhone secara otomatis
        $ip = $_SERVER['REMOTE_ADDR']; // IP pengunjung (iPhone kasir)
		$user_id = $this->session->userdata('logged_in')['USER_ID'] ?? null;
		if (!$user_id) return null;

		$this->M_thermal->update_kasir_ip($user_id, $ip);

        echo "<h2>Printer Kasir Terdaftar!</h2>";
        echo "IP kamu: <b>$ip</b><br>";
        echo "Status: <span style='color:green'>AKTIF & SIAP CETAK</span><br><br>";
        echo "Tinggal buka POS seperti biasa. Jangan tutup halaman ini kalau mau tetap akurat!";
        echo "<script>setInterval(() => location.reload(), 300000);</script>"; // refresh tiap 5 menit
    }
}