<?php
 
//  header("Content-type: application/vnd-ms-excel");
 
//  header("Content-Disposition: attachment; filename=coba.xls");
 
//  header("Pragma: no-cache");
 
//  header("Expires: 0");
 
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
<table class="tableData">
     <thead>
          <tr>
               <th class="tableData">Attendance Report</th>
               <td class="tableData" ><?php echo $data['name']." - ".$data['email']; ?></td>
          </tr>
          <tr>
               <th class="tableData" >Tanggal</th>
               <td class="tableData" ><?php $date=date_create($absence['absence_date']); echo date_format($date,"d F Y"); ?></td>
          </tr>
          <tr>
               <th class="tableData" >Waktu Absen Masuk</th>
               <td class="tableData" ><?php echo ($absence['time_in'])?$absence['time_in']:"Tidak Ada Absen Pulang"; ?></td>
          </tr>
          <tr>
               <th class="tableData" >Lokasi Absen Masuk</th>
                <td class="tableData" >
                <?php 
                    if(!empty($pinpoint)){
                        if($pinpoint['in']['lat']==""){
                            echo "Lokasi Absen Tidak Ditemukan";
                        }else{
                            echo $pinpoint['in']['lat']." , ".$pinpoint['in']['long']."<br><iframe src = 'https://maps.google.com/maps?q=".$pinpoint['in']['lat'].",".$pinpoint['in']['long']."&hl=es;z=14&amp;output=embed'></iframe>";
                        }
                    }else{
                        echo "Lokasi Absen Tidak Ditemukan";
                    }
                    ?>
                </td>
          </tr>
          <tr>
               <th class="tableData" >Foto Absen Masuk</th>
               <td class="tableData" >
                   <?php 
                    if(!empty($evidence['in'])){
                        foreach ($this->data["evidence"]['in'] as $key => $dtin) {
                            echo "<img src='".$dtin."' width='100px'>";
                        }
                    }else{
                        echo "Foto Absen Tidak Ditemukan";
                    }
                    ?>     
               </td>
          </tr>
          <tr>
               <th class="tableData" >Waktu Absen Pulang</th>
               <td class="tableData" ><?php echo ($absence['time_out'])?$absence['time_out']:"Tidak Ada Absen Pulang"; ?></td>
          </tr>
          <tr>
               <th class="tableData" >Lokasi Absen Pulang</th>
                <td class="tableData" >
                <?php 
                    if(!empty($pinpoint)){
                        if($pinpoint['out']['lat']==""){
                            echo "Lokasi Absen Tidak Ditemukan";
                        }else{
                            echo $pinpoint['out']['lat']." , ".$pinpoint['out']['long']."<br><iframe src = 'https://maps.google.com/maps?q=".$pinpoint['out']['lat'].",".$pinpoint['out']['long']."&hl=es;z=14&amp;output=embed'></iframe>";
                        }
                    }else{
                        echo "Lokasi Absen Tidak Ditemukan";
                    }
                    ?>
                </td>
          </tr>
          <tr>
               <th class="tableData" >Foto Absen Pulang</th>
               <td class="tableData" >
                   <?php 
                    if(!empty($evidence['out'])){
                        foreach ($this->data["evidence"]['out'] as $key => $dtin) {
                            echo "<img src='".$dtin."' width='100px'>";
                        }
                    }else{
                        echo "Foto Absen Tidak Ditemukan";
                    }
                    ?>     
               </td>
          </tr>
          <tr>
               <th class="tableData" >Catatan</th>
               <td class="tableData" ><?php echo ($absence['notes'])?$absence['notes']:"-"; ?></td>
          </tr>
    </thead>
</table>
<br>
<br>
<table class="tableData1">
    <thead>
          <tr>
               <th colspan="5">Checkpoint/Insiden</th>
          </tr>
          <tr>
                <th>Tipe</th>     
                <th>Waktu</th>
                <th>Catatan</th>
                <th>Lokasi</th>
                <th>Bukti Foto</th>
          </tr>
     </thead>
     <tbody>
          <?php
          if (count($event) > 0) {
              foreach ($event as $key => $dtev) { 
                $pinpoint_event = json_decode($dtev["pinpoint"], true);
                    echo "<tr>";
                    echo "<td>". ucwords($dtev['type']) ."</td>";
                    echo "<td>". $dtev['time'] ."</td>";
                    echo "<td>". $dtev['notes'] ."</td>";
                    if($pinpoint_event['lat']==""){
                        echo "<td>Lokasi Absen Tidak Ditemukan</td>";
                    }else{
                        echo "<td>".$pinpoint_event['lat']." , ".$pinpoint_event['long']."<br>https://maps.google.com/maps?q=".$pinpoint_event['lat'].",".$pinpoint_event['long']."<br><iframe src = 'https://maps.google.com/maps?q=".$pinpoint_event['lat'].",".$pinpoint_event['long']."&hl=es;z=14&amp;output=embed'></iframe></td>";
                    }
                    if($dtev['evidence']==""){
                        echo "Bukti Foto Tidak Ditemukan";
                    }else{
                        echo "<td>";
                        foreach ($dtev['gambar_evidence'] as $key => $dtevev) {
                            echo $dtevev['time']."<br><img  src='".$dtevev['url']."' alt='user' width='200px'/><br>";
                            // echo "<td style='background-image:url(".$dtevev['url'].");background-repeat:no-repeat;background-size:250px 180px;   width: 250px; height: 180px;'></td>";
                        }
                        echo "</td>";
                    }
               }
          } else {
               echo "<tr><td colspan='10' align='center'>Tidak ada data</td></tr>";
          }
          ?>
     </tbody>
</table>
</body>
</html>