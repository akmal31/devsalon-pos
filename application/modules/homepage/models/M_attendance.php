
<?php
	defined('BASEPATH') OR exit('no direct script access allowed');
	
	class M_attendance extends CI_Model {

    public function getTodayAttendance($outlet_id) {
        $today = date('Y-m-d');

        return $this->db
            ->select('a.id, u.id as user_id, u.name, a.status')
            ->from('attendance_simple a')
            ->join('users u', 'u.id = a.user_id')
            ->where('u.outlet_id', $outlet_id)
            ->where('a.date', $today)
            ->order_by('u.name', 'ASC')
            ->get()
            ->result_array();
    }

    public function countTodayAttendance($outlet_id) {
        $today = date('Y-m-d');
        return $this->db
            ->join('users u', 'u.id = attendance_simple.user_id')
            ->where('attendance_simple.date', $today)
            ->where('u.outlet_id', $outlet_id)
            ->count_all_results('attendance_simple');
    }

    public function getUsersByOutlet($outlet_id) {
        return $this->db
            ->select('u.id as user_id, u.name')
            ->from('users u')
            ->where('u.outlet_id', $outlet_id)
            ->order_by('u.name', 'ASC')
            ->get()
            ->result_array();
    }

	public function saveOrUpdate($user_id, $date, $status){
        // check if exists
        $exists = $this->db->get_where('attendance_simple', [
            'user_id' => $user_id,
            'date'    => $date
        ])->row_array();

        if ($exists) {
            // update
            return $this->db->update('attendance_simple', [
                'status'     => $status,
                'updated_at' => date('Y-m-d H:i:s')
            ], [
                'id'         => $exists['id']
            ]);
        } else {
            // insert
            return $this->db->insert('attendance_simple', [
                'user_id'    => $user_id,
                'date'       => $date,
                'status'     => $status,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }	

}

?>