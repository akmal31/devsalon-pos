<?php
 
 header("Content-type: application/vnd-ms-excel");
 
 header("Content-Disposition: attachment; filename=$title.xls");
 
 header("Pragma: no-cache");
 
 header("Expires: 0");
 
 ?>
 
<table id="tableData" class="table color-bordered-table muted-bordered-table">
     <thead>
          <tr>
               <th>Attendance Report</th>
               <td><?=$data['name']?></td>
          </tr>
          <tr>
               <th>Periode</th>
               <td><?=$date_range?></td>
          </tr>
          <tr>
               <th></th>
          </tr>
          <tr>
               <th>Employee ID</th>
               <th>Name</th>
               <th>Email</th>
               <th>Location Name</th>
               <th>Shift</th>
               <th>Job Position</th>
               <th>Job Level</th>
               <th>Absence Date</th>
               <th>Time In</th>
               <th>Time Out</th>
               <th>Notes</th>
          </tr>
     </thead>
     <tbody>
          <?php
          if (count($data_absence) > 0) {
               $x=0;
               foreach ($data_absence as $key => $dt) {
                    echo "<tr>";
                    echo "<td>". $dt['nik'] ."</td>";
                    echo "<td>". $dt['fullname'] ."</td>";
                    echo "<td>". $dt['email'] ."</td>";
                    echo "<td>". $dt['location_name'] ."</td>";
                    echo "<td>". $dt['shift_name'] ."</td>";
                    echo "<td>". $dt['position'] ."</td>";
                    echo "<td>". $dt['level'] ."</td>";
                    echo "<td>". $dt['absence_date'] ."</td>";
                    echo "<td>". $dt['time_in'] ."</td>";
                    echo "<td>". $dt['time_out'] ."</td>";
                    echo "<td>". $dt['notes'] ."</td>";
               }
          } else {
               echo "<tr><td colspan='10' align='center'>Tidak ada data</td></tr>";
          }
          ?>
     </tbody>
</table>