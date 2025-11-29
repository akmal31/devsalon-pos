<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('check_session')) {
    function check_session()
    {
        $CI =& get_instance();
        $user_id = $CI->session->userdata('user_id'); // asumsi simpan user_id waktu login

        if (!$user_id) {
            // kasih peringatan sekali aja (flashdata)
            $CI->session->set_flashdata('error', 'Session sudah habis, silakan login kembali.');

            // redirect ke halaman login
            redirect('login');
            exit;
        }
    }
}
