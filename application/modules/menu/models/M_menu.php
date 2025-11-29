<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class M_menu extends CI_Model {

    // Ambil semua menu/element yang boleh diakses oleh group
    public function getMenuByGroup($group_id) {
        $this->db->select('menu');
        $this->db->from('user_group');
        $this->db->where('id', $group_id);
        $row = $this->db->get()->row_array();
        
        if (!$row || empty($row['menu'])) {
            return [];
        }

        // Konversi string "(1,2,3)" jadi array [1, 2, 3]
        $menu_ids = array_map('intval', explode(',', str_replace(['(',')',' '], '', $row['menu'])));

        // Ambil detail menu dari tabel menu
        $this->db->select('*');
        $this->db->from('menu');
        $this->db->where_in('id', $menu_ids);
        $this->db->order_by('parent_id ASC, sort ASC');
        return $this->db->get()->result_array();
    }

    // Cari menu_id berdasarkan URI segment (nama module/menu)
    public function getSubMenuId($menu_list, $uri_segment) {
        foreach ($menu_list as $m) {
            if (strtolower($m['module_name']) == strtolower($uri_segment)) {
                return $m['id'];
            }
        }
        return null;
    }

    // Cek apakah group punya akses ke menu_id ini
    public function getAccessPrivellege($group_id, $menu_id) {
        $this->db->select('menu');
        $this->db->from('user_group');
        $this->db->where('id', $group_id);
        $row = $this->db->get()->row_array();

        if (!$row || empty($row['menu'])) {
            return false;
        }

        $menu_ids = array_map('intval', explode(',', str_replace(['(',')',' '], '', $row['menu'])));

        return in_array((int)$menu_id, $menu_ids);
    }
}
