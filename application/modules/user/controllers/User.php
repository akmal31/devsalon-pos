<?php
defined('BASEPATH') or exit('no direct script access allowed');

class User extends MX_Controller
{

	public function __construct()
	{

		parent::__construct();
		if (!$this->session->userdata('logged_in')) {
			redirect('login', 'index');
		}
		$this->load->library('form_validation');

		$this->data = array();

		$this->load->model('user/M_user');
		
		$this->data['user_profile'] = $this->session->userdata('logged_in');

		//--Start Get User Group Privillege
		$this->load->model('menu/M_menu');
		$this->load->model('user_group/M_user_group');

		//Group Menu Privillege
		$menuPrivillege = $this->M_menu->getMenuByGroup($this->session->userdata('logged_in')['USER_GROUP_ID']);
		$this->data['menu_privillege'] = $menuPrivillege;

		//Get Group Action Privillege
		$menu_id = $this->M_menu->getSubMenuId($menuPrivillege, $this->uri->segment(1));
		$accessPrivillege = $this->M_menu->getAccessPrivellege($this->session->userdata('logged_in')['USER_GROUP_ID'], $menu_id);
		$this->data['access_privillege'] = $accessPrivillege;
		//--End User Group Privillege

		$this->data['msg'] = "";

		$this->load->model('client/M_client');
	}

	public function index()
	{
		if (empty($this->data['access_privillege'])) {
			$this->load->view('partial/v_forbidden', $this->data);
		} else {
			$this->data["alldata"] = $this->M_user->getCountAll();
			$this->data["activedata"] = $this->M_user->getCountActive();
			$this->load->view('v_list', $this->data);
		}
	}
	
	public function detail($employee_id = 0)
	{
		$url=getenv('HOST_API')."/v1/employee/".$employee_id;
		$this->data["data"] = json_decode(httpGet($this->session->userdata('logged_in')['TOKEN'],$url),true);
		if (empty($this->data['data'])) {
			$this->load->view('partial/v_forbidden', $this->data);
		} else {
			$this->load->view('v_detail', $this->data);
		}
	}
	
	public function absence_detail($absence_id)
	{
		$this->data["absence"] = $this->M_user->getAbsenceByAbsenceId($absence_id);
		$this->data["event"] = $this->M_user->getEventByAbsenceId($absence_id);
		$token=$this->session->userdata('logged_in')['TOKEN'];
		$v=0;
		foreach ($this->data["event"] as $key => $ev) {
			$w=0;
			$evidence = json_decode($ev["evidence"], true);
			foreach ($evidence as $key => $evev) {
				$break_ev=explode(";",$evev);
				if(empty($break_ev[1])){
					$break_ev[1]="";
				}
				$url=getenv('HOST_API')."/v1/document/download?key=".$break_ev[0];
				$response_evev=json_decode(httpGet($token,$url),true);
				if(!empty($response_evev['url'])){
					$this->data["event"][$v]['gambar_evidence'][$w]['url']=$response_evev['url'];
					$this->data["event"][$v]['gambar_evidence'][$w]['time']=$break_ev[1];
				}else{
					$this->data["event"][$v]['gambar_evidence'][$w]['url']="";
					$this->data["event"][$v]['gambar_evidence'][$w]['time']=$break_ev[1];
				}
				$w=$w+1;
			}
			$v=$v+1;
		}
		$this->data["pinpoint"] = json_decode($this->data["absence"]["pinpoint"], true);
		$this->data["evidence"] = json_decode($this->data["absence"]["evidence"], true);
		if(!empty($this->data["evidence"]['in'])){
			$x=0;
			foreach ($this->data["evidence"]['in'] as $key => $dtin) {
				$url=getenv('HOST_API')."/v1/document/download?key=".$dtin;
				$response_in=json_decode(httpGet($token,$url),true);
				if(!empty($response_in['url'])){
					$this->data["evidence"]['in'][$x]=$response_in['url'];
				}else{
					$this->data["evidence"]['in'][$x]="";
				}
				$x=$x+1;
			}
		}
		if(!empty($this->data["evidence"]['out'])){
			$y=0;
			foreach ($this->data["evidence"]['out'] as $key => $dtout) {
				$url=getenv('HOST_API')."/v1/document/download?key=".$dtout;
				$response_out=json_decode(httpGet($token,$url),true);
				if(!empty($response_in['url'])){
					$this->data["evidence"]['out'][$y]=$response_out['url'];
				}else{
					$this->data["evidence"]['out'][$y]="";
				}
				$y=$y+1;
			}
		}
		$this->load->view('v_detail_absence', $this->data);
	}

	public function report_absence_detail($absence_id)
	{
		$this->data["absence"] = $this->M_user->getAbsenceByAbsenceId($absence_id);
		$this->data["event"] = $this->M_user->getEventByAbsenceId($absence_id);
		$token=$this->session->userdata('logged_in')['TOKEN'];
		$v=0;
		foreach ($this->data["event"] as $key => $ev) {
			$w=0;
			$evidence = json_decode($ev["evidence"], true);
			foreach ($evidence as $key => $evev) {
				$break_ev=explode(";",$evev);
				if(empty($break_ev[1])){
					$break_ev[1]="";
				}
				$url=getenv('HOST_API')."/v1/document/download?key=".$break_ev[0];
				$response_evev=json_decode(httpGet($token,$url),true);
				if(!empty($response_evev['url'])){
					$this->data["event"][$v]['gambar_evidence'][$w]['url']=$response_evev['url'];
					$this->data["event"][$v]['gambar_evidence'][$w]['time']=$break_ev[1];
				}else{
					$this->data["event"][$v]['gambar_evidence'][$w]['url']="";
					$this->data["event"][$v]['gambar_evidence'][$w]['time']=$break_ev[1];
				}
				$w=$w+1;
			}
			$v=$v+1;
		}
		$this->data["data"] = $this->M_user->getById($this->data["absence"]["user_id"]);
		$this->data["phone"] = json_decode($this->data["data"]["phone_number"], true);
		$this->data["address"] = json_decode($this->data["data"]["address"], true);
		$this->data["document"] = json_decode($this->data["data"]["document"], true);
		$this->data["pinpoint"] = json_decode($this->data["absence"]["pinpoint"], true);
		$this->data["evidence"] = json_decode($this->data["absence"]["evidence"], true);
		if(!empty($this->data["evidence"]['in'])){
			$x=0;
			foreach ($this->data["evidence"]['in'] as $key => $dtin) {
				$url=getenv('HOST_API')."/v1/document/download?key=".$dtin;
				$response_in=json_decode(httpGet($token,$url),true);
				if(!empty($response_in['url'])){
					$this->data["evidence"]['in'][$x]=$response_in['url'];
				}else{
					$this->data["evidence"]['in'][$x]="";
				}
				$x=$x+1;
			}
		}
		if(!empty($this->data["evidence"]['out'])){
			$y=0;
			foreach ($this->data["evidence"]['out'] as $key => $dtout) {
				$url=getenv('HOST_API')."/v1/document/download?key=".$dtout;
				$response_out=json_decode(httpGet($token,$url),true);
				if(!empty($response_out['url'])){
					$this->data["evidence"]['out'][$y]=$response_out['url'];
				}else{
					$this->data["evidence"]['out'][$y]="";
				}
				$y=$y+1;
			}
		}
		$this->load->view('v_download_detail', $this->data);
	}

	public function add()
	{
		$this->load->library('form_validation');
		$this->data["user_group_list"] = $this->M_user_group->getList();
		$this->load->view('v_form', $this->data);
	}

	public function edit($user_id = 0)
	{
		$this->load->library('form_validation');
		$this->data["data"] = $this->M_user->getById($user_id);
		$this->data["user_group_list"] = $this->M_user_group->getList();
		$this->load->view('v_form', $this->data);
	}

	public function save()
	{
		$btnSubmit = $this->input->post('btnSubmit');
		$emp_id = $this->input->post('emp_id');
		$user_id = $this->input->post('user_id');
		
		$body=array();
		$body['user_id']=(int)$user_id;
		if($this->input->post('nama')!=''){
			$body['name']=$this->input->post('nama');
		}
		if($this->input->post('email')!=''){
			$body['email']=$this->input->post('email');
		}
		if($this->input->post('no_hp')!=''){
			$body['phone_number']['no_hp']=$this->input->post('no_hp');
		}
		if($this->input->post('home_phone')!=''){
			$body['phone_number']['telp_rumah']=$this->input->post('home_phone');
		}
		if($this->input->post('tempat_lahir')!=''){
			$body['birthplace']=$this->input->post('tempat_lahir');
		}
		if($this->input->post('tanggal_lahir')!=''){
			$body['birthdate']=$this->input->post('tanggal_lahir');
		}
		if($this->input->post('alamat_ktp')!=''){
			$body['address']['alamat_ktp']=$this->input->post('alamat_ktp');
		}
		if($this->input->post('alamat_dom')!=''){
			$body['address']['alamat_domisili']=$this->input->post('alamat_dom');
		}
		if($this->input->post('ktp')!=''){
			$body['document']['ktp']=$this->input->post('ktp');
		}
		if($this->input->post('kk')!=''){
			$body['document']['kk']=$this->input->post('kk');
		}
		if($this->input->post('npwp')!=''){
			$body['document']['npwp']=$this->input->post('npwp');
		}
		if($this->input->post('gender')!=''){
			$body['gender']=$this->input->post('gender');
		}
		if($this->input->post('religion')!=''){
			$body['religion']=$this->input->post('religion');
		}
		if($this->input->post('marital_status')!=''){
			$body['marital_status']=$this->input->post('marital_status');
		}
		if($this->input->post('blood_type')!=''){
			$body['blood_type']=$this->input->post('blood_type');
		}
		$body_str=json_encode($body,true);
		$url=getenv('HOST_API')."/v1/user/".$user_id;
		$result=json_decode(httpSend($this->session->userdata('logged_in')['TOKEN'],$url,"PUT",$body_str),true);
		if($result['status']==true){
			$this->session->set_flashdata('success', 'Selamat, Data Berhasil Diubah!');
		}else{
			$this->session->set_flashdata('failed', 'Maaf ada kesalahan, silahkan coba lagi atau hubungi admin.');
		}
		redirect('user/detail/'.$emp_id);
	
	}
	public function save_work()
	{
		$btnSubmit = $this->input->post('btnSubmit');
		$id = $this->input->post('employee_id');
		
		$body=array();
		if($this->input->post('emp_id')!=''){
			$body['employee_id']=$this->input->post('emp_id');
		}
		if($this->input->post('status')!=''){
			$body['status']=$this->input->post('status');
		}
		if($this->input->post('join')!=''){
			$body['join_date']=$this->input->post('join');
		}
		if($this->input->post('manager_id')!=''){
			$body['manager_id']=$this->input->post('manager_id');
		}
		if($this->input->post('end_date')!=''){
			$body['end_date']=$this->input->post('end_date');
		}
		$body_str=json_encode($body,true);
		$url=getenv('HOST_API')."/v1/employee/".$id;
		$result=json_decode(httpSend($this->session->userdata('logged_in')['TOKEN'],$url,"PUT",$body_str),true);
		// echo $body_str."<br>";
		// var_dump($result);die;
		if($result['status']==true){
			$this->session->set_flashdata('success', 'Selamat, Data Berhasil Diubah!');
		}else{
			$this->session->set_flashdata('failed', 'Maaf ada kesalahan, silahkan coba lagi atau hubungi admin.');
		}
		redirect('user/detail/'.$id);
	
	}

	public function reset_device($id)
	{

		$arr_data = $this->M_user->getById($id);
		if (count($arr_data) > 0) {

			$reset = $this->M_user->reset_device($id);

			if ($reset) {
				$this->session->set_userdata("success", "Device ". $arr_data["name"] . " sukses direset");
				//$this->data["msg"] = "Device ". $arr_data["name"] . " sukses direset";
				redirect('user/detail/'.$id);
			} else {
				$this->session->set_userdata("failed", "Device ". $arr_data["name"] . " sukses direset");
				//$this->data["msg"] = "Device ". $arr_data["name"] . " gagal direset";
				redirect('user/detail/'.$id);
			}
		} else {
			redirect('user/detail/'.$id);
		}
	}

	public function del($id)
	{

		$arr_data = $this->M_user->getById($id);
		if (count($arr_data) > 0) {

			$delete = $this->M_user->delete($id);

			if ($delete) {
				$this->data["msg"] = $arr_data["username"] . " sukses dihapus";
				$this->data["list"] = $this->M_user->getList();
				$this->load->view('v_list', $this->data);
			} else {
				$this->data["msg"] = $arr_data["username"] . " gagal dihapus";
				$this->data["list"] = $this->M_user->getList();
				$this->load->view('v_list', $this->data);
			}
		} else {
			$this->data["msg"] = "Data gagal dihapus";
			$this->data["list"] = $this->M_user->getList();
			$this->load->view('v_list', $this->data);
		}
	}
	
	public function import_approval()
	{
		// This is the entire file that was uploaded to a temp location.
		$tmpfile = $_FILES['approval']['tmp_name'];
		$filename = basename($_FILES['approval']['name']);
		$data = array(
			'file' => curl_file_create($tmpfile, $_FILES['approval']['type'], $filename)
		);
		$data1 = array(
			'file' => '@'.$tmpfile.';filename='.$filename,
		);
		// This is the entire file that was uploaded to a temp location.
		$localFile = $_FILES['approval']['tmp_name'];

		$fp = fopen($localFile, 'r');

		$body_str=json_encode($data,true);
		$url=getenv('HOST_API')."/v1/employee/approval-line/import";

			$curl = curl_init();
			$token= array('Authorization: Bearer '.$this->session->userdata('logged_in')['TOKEN']);	
			curl_setopt_array($curl, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => $data,
				CURLOPT_HTTPHEADER => $token,
			));
			
			$response = curl_exec($curl);
			curl_close($curl);
			$result=json_decode($response,true);
			
			if($result['message']=="ok"){
				$this->session->set_flashdata('warning', 'Upload berhasil, Silahkan tunggu beberapa saat untuk mengetahui apakah insert data berhasil atau tidak!');
			}else{
				$this->session->set_flashdata('failed', 'Maaf ada kesalahan, silahkan coba lagi atau hubungi admin.');
			}
			redirect('user');
	}

	public function import_user()
	{
		// This is the entire file that was uploaded to a temp location.
		$tmpfile = $_FILES['userfile']['tmp_name'];
		$filename = basename($_FILES['userfile']['name']);
		$data = array(
			'file' => curl_file_create($tmpfile, $_FILES['userfile']['type'], $filename)
		);
		$data1 = array(
			'file' => '@'.$tmpfile.';filename='.$filename,
		);
		// This is the entire file that was uploaded to a temp location.
		$localFile = $_FILES['userfile']['tmp_name'];

		$fp = fopen($localFile, 'r');

		$body_str=json_encode($data,true);
		$url=getenv('HOST_API')."/v1/user/import";

			$curl = curl_init();
			$token= array('Authorization: Bearer '.$this->session->userdata('logged_in')['TOKEN']);	
			curl_setopt_array($curl, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => $data,
				CURLOPT_HTTPHEADER => $token,
			));
			
			$response = curl_exec($curl);
			curl_close($curl);
			
			$result=json_decode($response,true);
			
			if($result['message']=="ok"){
				$this->session->set_flashdata('warning', 'Upload berhasil, Silahkan tunggu beberapa saat untuk mengetahui apakah insert data berhasil atau tidak!');
			}else{
				$this->session->set_flashdata('failed', 'Maaf ada kesalahan, silahkan coba lagi atau hubungi admin.');
			}
			redirect('user');
	}

	public function reset($user_id,$emp_id)
	{
		$body=array();
		$body['user_id']=(int)$user_id;
		$body_str=json_encode($body,true);
		$url=getenv('HOST_API')."/v1/user/device/reset";
		$result=json_decode(httpSend($this->session->userdata('logged_in')['TOKEN'],$url,"POST",$body_str),true);
		if($result['message']=="device id successful to reset"){
			$this->session->set_flashdata('success', 'Selamat, Reset Device Berhasil!');
		}else{
			$this->session->set_flashdata('failed', 'Maaf ada kesalahan, silahkan coba lagi atau hubungi admin.');
		}
		redirect('user/detail/'.$emp_id);
	}

	public function resign()
	{
		$employee_id = $this->input->post('employee_id');
		
		$body=array();
		$body['resign_date']=$this->input->post('resign_date');
		$body['deleted_at']=date("Y-m-d h:i:s");
		$body_str=json_encode($body,true);
		$url=getenv('HOST_API')."/v1/employee/".$employee_id;
		$result=json_decode(httpSend($this->session->userdata('logged_in')['TOKEN'],$url,"PUT",$body_str),true);
		if($result['status']==true){
			$this->session->set_flashdata('success', 'Selamat, Status pegawai berhasil diubah menjadi resign!');
		}else{
			$this->session->set_flashdata('failed', 'Maaf ada kesalahan, silahkan coba lagi atau hubungi admin.');
		}
		redirect('user/detail/'.$employee_id);
	}

	public function reactivate($employee_id,$user_id)
	{
	   	$url=getenv('HOST_API')."/v1/employee/re-activate?nik=".$employee_id;
		$token=json_decode(httpSend($this->session->userdata('logged_in')['TOKEN'],$url,"PUT"),true);
		if(!empty($token['status'])){
			$this->session->set_flashdata('success', 'Selamat, Status pegawai berhasil di aktifkan kembali!');
		}else{
			$this->session->set_flashdata('failed', 'Maaf ada kesalahan, silahkan coba lagi atau hubungi admin.');
		}
		redirect('user/detail/'.$user_id);
	}

	//Chained select dropdown for Security Coverage	related with Product id
	public function publish($id, $tipe)
	{

		$arr_data = $this->M_user->getById($id);
		//print_r($arr_data);die;
		if (count($arr_data) > 0) {
			if ($tipe == 1) {
				$publish = $this->M_user->publish($id, $tipe);

				if ($publish) {
					$this->data["msg"] = "Selamat, " . $arr_data["username"] . " sukses diaktifkan";
					$this->data["list"] = $this->M_user->getList();
					$this->load->view('v_list', $this->data);
				} else {
					$this->data["msg"] = "Maaf, " . $arr_data["username"] . " gagal diaktifkan";
					$this->data["list"] = $this->M_user->getList();
					$this->load->view('v_list', $this->data);
				}
			} else {
				$publish = $this->M_user->publish($id, $tipe);
				if ($publish) {
					$this->data["msg"] = "Selamat, " . $arr_data["username"] . " sukses di non-aktifkan";
					$this->data["list"] = $this->M_user->getList();
					$this->load->view('v_list', $this->data);
				} else {
					$this->data["msg"] = "Maaf, " . $arr_data["username"] . " gagal di non-aktifkan";
					$this->data["list"] = $this->M_user->getList();
					$this->load->view('v_list', $this->data);
				}
			}
		} else {
			$this->data["msg"] = "Data gagal diubah";
			$this->data["list"] = $this->M_user->getList();
			$this->load->view('v_list', $this->data);
		}
	}

	function view_data_query()
	{
		$query  = "SELECT a.*, b.id as emp_id, b.join_date, d.name as position_name, e.name as position_level_name, g.name as location_name
					from users a 
					Left JOIN employees b ON b.user_id=a.id 
					Left JOIN job_position_level c on b.position_level_id=c.id 
					Left JOIN job_positions d on c.position_id=d.id 
					Left JOIN job_levels e on c.level_id=e.id 
					Left JOIN employee_placements f on f.employee_id=b.id 
					Left JOIN client_locations g on g.id=f.location_id 
					";
		$search = array('a.name', 'email', 'd.name', 'e.name', 'g.name');
		$where  = array('f.deleted_at' => 'is null');
		$isWhere = null;
		$isGroupBy = null;
		header('Content-Type: application/json');
		echo $this->M_user->get_tables_query($query, $search, $where, $isWhere, $isGroupBy);
	}

	function view_data_query_absence()
	{
		$employee_id = $this->input->post('employee_id');
		if($month=0){
			$month=date('n');
			$year=date('Y');
		}else{
			$month = $this->input->post('month');
			$year = $this->input->post('year');	
		}
		
		$query  = "SELECT * 
					FROM vw_employee_absences";

		$search = array('location_name');
		$where  = array('employee_id' => '='.$employee_id, 'EXTRACT(MONTH FROM absence_date)' => '='.$month, 'EXTRACT(YEAR FROM absence_date)' => '='.$year);
		$isWhere = null;
		$isGroupBy = null;
		header('Content-Type: application/json');
		echo $this->M_user->get_tables_query($query, $search, $where, $isWhere, $isGroupBy);
	}

	public function export(){
		$user_id = $this->input->post('user_id');
		$date_range = $this->input->post('daterange');
		
		$date_range_exp = explode(' - ',(string)$date_range);
		$date_start = $date_range_exp[0];
		$date_end = $date_range_exp[1];

		$period = new DatePeriod( new DateTime($date_start), new DateInterval('P1D'), new DateTime($date_end));
		
		$this->data["date_range"] = $date_range;
		$this->data["period"] = $period;
	    $this->data["data"] = $this->M_user->getById($user_id);
		$this->data["data_absence"] = $this->M_client->getExportAbsences(0,$date_start,$date_end,$user_id);
		$this->data["title"] = "Detail Absence ".$this->data["data"]["email"]." - ".$date_range;
		$this->load->view('v_download',$this->data);
   	}
	
	public function import_employee(){
		$this->data["title"] = "Import Employee";
		$this->load->view('v_import_employee', $this->data);
	}

   	public function download_template(){
		$this->data["title"] = "Import_Employee";
		$this->load->view('v_download_template', $this->data);
	}

	public function import_employee_val() {
		// This is the entire file that was uploaded to a temp location.
		$tmpfile = $_FILES['employee']['tmp_name'];
		$filename = basename($_FILES['employee']['name']);
		$data = array(
			'file' => curl_file_create($tmpfile, $_FILES['employee']['type'], $filename)
		);
		$data1 = array(
			'file' => '@'.$tmpfile.';filename='.$filename,
		);
		// This is the entire file that was uploaded to a temp location.
		$localFile = $_FILES['employee']['tmp_name'];
		$fp = fopen($localFile, 'r');

		$body_str=json_encode($data,true);
		$url=getenv('HOST_API')."/v1/user/import/validate";

			$curl = curl_init();
			$token= array('Authorization: Bearer '.$this->session->userdata('logged_in')['TOKEN']);	
			curl_setopt_array($curl, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => $data,
				CURLOPT_HTTPHEADER => $token,
			));
			
			$response = curl_exec($curl);
			curl_close($curl);
			print_r($response);
	}

	public function import_employee_confirm() {
		// This is the entire file that was uploaded to a temp location.
		$tmpfile = $_FILES['employee']['tmp_name'];
		$filename = basename($_FILES['employee']['name']);
		$data = array(
			'file' => curl_file_create($tmpfile, $_FILES['employee']['type'], $filename)
		);
		$data1 = array(
			'file' => '@'.$tmpfile.';filename='.$filename,
		);
		// This is the entire file that was uploaded to a temp location.
		$localFile = $_FILES['employee']['tmp_name'];

		$fp = fopen($localFile, 'r');

		$body_str=json_encode($data,true);
		$url=getenv('HOST_API')."/v1/user/import";

			$curl = curl_init();
			$token= array('Authorization: Bearer '.$this->session->userdata('logged_in')['TOKEN']);	
			curl_setopt_array($curl, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => $data,
				CURLOPT_HTTPHEADER => $token,
			));
			
			$response = curl_exec($curl);
			curl_close($curl);
			$result=json_decode($response,true);
			print_r($response);
	}
}
