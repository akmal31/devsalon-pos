<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_thermal extends CI_Model {

    public function update_kasir_ip($user_id, $ip_address)
    {
        $data = [
            'kasir_id'   => $user_id,
            'ip_address' => $ip_address,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // replace = insert kalau belum ada, update kalau sudah ada (berdasarkan kasir_id)
        $this->db->replace('printer_kasir', $data);
        
        return true;
    }

    // Fungsi buat ambil IP kasir (dipakai di library nanti)
    public function get_kasir_ip($user_id)
    {
        $query = $this->db->get_where('printer_kasir', ['kasir_id' => $user_id]);
        if ($query->num_rows() > 0) {
            return $query->row()->ip_address;
        }
        return null; // atau return false;
    }
}