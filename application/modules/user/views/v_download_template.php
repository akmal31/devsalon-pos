<?php
 
 header("Content-type: application/vnd-ms-excel");
 
 header("Content-Disposition: attachment; filename=Import_Employee_Template.xls");
 
 header("Pragma: no-cache");
 
 header("Expires: 0");
 
 ?>
<!DOCTYPE html>
<html>
<head>
<style>
.tableData {
  border: 0px;
  border-collapse: collapse;
}
.tableData1, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
</style>
</head>
<body>
<table class="tableData1">
     <thead>
          <tr>
                <th>employee_id*</th>
                <th>fullname*</th>
                <th>barcode</th>
                <th>organization*</th>
                <th>location_id</th>
                <th>job_level*</th>
                <th>job_position*</th>
                <th>position_level_id</th>
                <th>join_date*</th>
                <th>resign_date</th>
                <th>status*</th>
                <th>end_date</th>
                <th>sign_date*</th>
                <th>email*</th>
                <th>birthdate</th>
                <th>birthplace</th>
                <th>citizen_id_address*</th>
                <th>residential_address</th>
                <th>npwp</th>
                <th>ptkp_status</th>
                <th>employee_tax_status</th>
                <th>tax_config</th>
                <th>bank_name</th>
                <th>bank_account</th>
                <th>bank_account_holder</th>
                <th>bpjs_ketenagakerjaan</th>
                <th>bpjs_kesehatan</th>
                <th>citizen_id*</th>
                <th>no_hp*</th>
                <th>telp_rumah</th>
                <th>branch_name</th>
                <th>religion*</th>
                <th>gender*</th>
                <th>marital_status*</th>
                <th>nationality_code</th>
                <th>currency</th>
                <th>length_of_service</th>
                <th>payment_schedule</th>
                <th>manager_name</th>
                <th>manager_nik</th>
                <th>grade</th>
                <th>class</th>
                <th>profile_picture</th>
                <th>group_ffi</th>
          </tr>
     </thead>
     <tbody>
     </tbody>
</table>

</body>
</html>