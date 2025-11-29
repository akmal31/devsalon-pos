<!DOCTYPE html>
<html lang="en">

<?php $this->load->view("partial/v_html_header"); ?>

<body class="fix-header fix-sidebar card-no-border">
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <div id="main-wrapper">
		<?php $this->load->view("partial/v_header", $user_profile); ?>
        <?php $this->load->view("partial/v_sidebar"); ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row page-titles">
                    <div class="col-md-5 col-8 align-self-center">
                        <h3 class="text-themecolor">Profile</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo base_url() ?>homepage">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo base_url() ?>user">User</a></li>
                            <li class="breadcrumb-item active"><a href="<?php echo base_url().'user/detail/'.$absence['user_id'] ?>"><?=$absence['fullname']?></a></li>
                        </ol>
                    </div>
                </div>
                <!-- Row -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-6 p-20">
                                <center class="m-t-30"> <img src="<?php echo base_url(); ?>assets/user.png" class="img-circle" width="80" />
                                    <h4 class="card-title m-t-10"><?php echo $absence['fullname']; ?></h4>
                                    <h5 class="card-title"><?php echo $absence['email']; ?></h5>
                                    <h5 class="card-subtitle"><?php echo $absence['nik']; ?></h5>
                                </center>
                            </div>
                            <div class="col-md-8 p-20">
                                <h3><?php $date=date_create($absence['absence_date']); echo date_format($date,"d F Y"); ?><small class="pull-right"><a target="_blank" href="<?php echo base_url().'user/report_absence_detail/'.$absence['absence_id']; ?>"><i class="mdi mdi-cloud-download"></i> Download Report</small></a></h3> 
                                <div class="card p-10">
                                    <h3 class="text-themecolor m-b-0 m-t-0">Absensi</h3>
                                    <h4>Absen Masuk</h4>
                                    <h5><?php echo ($absence['time_in'])?$absence['time_in']:"Tidak Ada Absen Pulang"; ?></h5>
                                    <?php if(!empty($absence['time_in'])){ ?>
                                        <small class="text-muted p-t-10 db">Lokasi Absen Masuk</small>
                                        <h5><?php 
                                        if(!empty($pinpoint)){
                                            if($pinpoint['in']['lat']==""){
                                                echo "Lokasi Absen Tidak Ditemukan";
                                            }else{
                                                echo $pinpoint['in']['lat']." , ".$pinpoint['in']['long']."<br><iframe src = 'https://maps.google.com/maps?q=".$pinpoint['in']['lat'].",".$pinpoint['in']['long']."&hl=es;z=14&amp;output=embed'></iframe>";
                                            }
                                        }else{
                                            echo "Lokasi Absen Tidak Ditemukan";
                                        }
                                        ?></h5>
                                        <small class="text-muted p-t-10 db">Foto Absen Masuk</small>
                                        <h5><?php 
                                        if(!empty($evidence['in'])){
                                            foreach ($evidence['in'] as $key => $dtin) {
                                                echo "<img src='".$dtin."' width='300px'>";
                                            }
                                        }else{
                                            echo "Foto Absen Tidak Ditemukan";
                                        }
                                        ?></h5>
                                    <?php } ?>
                                    <h4>Absen Pulang</h4>
                                    <h5><?php echo ($absence['time_out'])?$absence['time_out']:"Tidak Ada Absen Pulang"; ?></h5>
                                    <?php if(!empty($absence['time_out'])){ ?>
                                        <small class="text-muted p-t-10 db">Lokasi Absen Pulang</small>
                                        <h5><?php 
                                        if(!empty($pinpoint)){
                                            if($pinpoint['out']['lat']==""){
                                                echo "Lokasi Absen Pulang Tidak Ditemukan";
                                            }else{
                                                echo $pinpoint['out']['lat']." , ".$pinpoint['out']['long']."<br><iframe src = 'https://maps.google.com/maps?q=".$pinpoint['out']['lat'].",".$pinpoint['out']['long']."&hl=es;z=14&amp;output=embed'></iframe>";
                                            }
                                        }else{
                                            echo "Lokasi Absen Tidak Ditemukan";
                                        }
                                        ?></h5>
                                        <small class="text-muted p-t-10 db">Foto Absen Pulang</small>
                                        <h5><?php 
                                        if(!empty($evidence['out'])){
                                            foreach ($evidence['out'] as $key => $dtout) {
                                                echo "<img src='$dtout' width='300px'>";
                                            }
                                        }else{
                                            echo "Foto Absen Tidak Ditemukan";
                                        }
                                        ?></h5>
                                    <?php } ?>
                                    <small class="text-muted p-t-10 db">Catatan</small>
                                    <h5><?php echo ($absence['notes'])?$absence['notes']:"-"; ?></h5>
                                </div>
                                <?php
                                if (count($event) > 0) {
                                    foreach ($event as $key => $dtev) { 
                                        $pinpoint_event = json_decode($dtev["pinpoint"], true);
                                        ?>
                                        <div class="card p-10">
                                            <h3 class="text-themecolor m-b-0 m-t-0"><?=ucwords($dtev['type'])?></h3>
                                            <small class="text-muted p-t-10 db">Waktu <?=ucwords($dtev['type'])?></small>
                                            <h6><?=$dtev['time']?></h6>
                                            <small class="text-muted p-t-10 db">Lokasi <?=ucwords($dtev['type'])?></small>
                                            <h6><?php 
                                            if($pinpoint_event['lat']==""){
                                                echo "Lokasi Absen Tidak Ditemukan";
                                            }else{
                                                echo $pinpoint_event['lat']." , ".$pinpoint_event['long']."<br><iframe src = 'https://maps.google.com/maps?q=".$pinpoint_event['lat'].",".$pinpoint_event['long']."&hl=es;z=14&amp;output=embed'></iframe>";
                                            }
                                            ?></h6>
                                            <small class="text-muted p-t-10 db">Note</small>
                                            <h5><?php echo ($dtev['notes'])?$dtev['notes']:"-"; ?></h5>
                                            <small class="text-muted p-t-10 db">Bukti Foto</small>
                                            <h6><?php 
                                            if($dtev['evidence']==""){
                                                echo "Bukti Foto Tidak Ditemukan";
                                            }else{
                                                foreach ($dtev['gambar_evidence'] as $key => $dtevev) { ?>
                                                    <div class="card-columns el-element-overlay">
                                                        <div class="card">
                                                            <div class="el-card-item">
                                                                <div class="el-card-avatar el-overlay-1">
                                                                    <?php 
                                                                    if(!empty($dtevev['url'])){
                                                                        echo "<a class='image-popup-vertical-fit' href='".$dtevev['url']."'> <img src='".$dtevev['url']."' alt='user' /> </a>";
                                                                    }else{
                                                                        echo "Bukti Foto Tidak Ditemukan";
                                                                    }?>
                                                                </div>
                                                                <div class="el-card-content">
                                                                    <?php if ($dtevev['time']!==""){
                                                                        echo "<h3 class='box-title'>".$dtevev['time']."</h3>";
                                                                    }?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                <?php } }else{ ?>
                                    <div class="card p-10">
                                        <h4>Checkpoint dan Insiden</h4>
                                        <h6 class="text-danger">Checkpoint dan Insiden Tidak Ditemukan</h6>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Row -->
            </div>
            <footer class="footer"> © <?echo date("Y");?> SGR Patrol Admin </footer>
        </div>
    </div>
	<?php $this->load->view("partial/v_script_bottom"); ?>
</body>
</html>