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
                            <li class="breadcrumb-item active"><?=$data['name']?></li>
                        </ol>
                    </div>
                </div>
                <!-- Row -->
                <?php $message = $this->session->flashdata('success');
                if (isset($message)) { ?>
                    <div class="alert alert-success"> <i class="mdi mdi-comment-check"></i> <?=$message?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                    </div>
                <?php $this->session->unset_userdata('success'); } ?>
                <?php $message = $this->session->flashdata('failed');
                if (isset($message)) { ?>
                    <div class="alert alert-danger"> <i class="mdi mdi-comment-remove-outline"></i> <?=$message?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                    </div>
                <?php $this->session->unset_userdata('failed'); } ?>

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-sm-12 p-20">
                                <center class="m-t-30"> <img src="<?php echo base_url(); ?>assets/user.png" class="img-circle" width="80" />
                                    <h4 class="card-title m-t-10"><?php echo $data['name']; ?></h4>
                                    <h5 class="card-title"><?php echo $data['email']; ?></h5>
                                    <h5 class="card-subtitle"><?php echo $data['employee_id']; ?></h5>
                                    <?php if(!empty($data['resign_date'])){ ?><span class="label label-danger">Resign</span><?php } ?>
                                </center>
                            </div>
                            <div class="col-md-8 p-20">
                                <div class="card p-10">
                                    <small class="text-muted p-t-10 db">Phone</small>
                                    <h6><?php if(!empty($data['phone_number']['no_hp'])){echo $data['phone_number']['no_hp'];}else{echo "-";} ?></h6> 
                                    <small class="text-muted p-t-10 db">Address</small>
                                    <h6><?php if(!empty($data['address']['alamat_ktp'])){echo $data['address']['alamat_ktp'];}else{echo "-";}?></h6>
                                    <small class="text-muted p-t-10 db">Join Date</small>
                                    <h6><?php if(!empty($data['join_date'])){echo date_format(date_create($data['join_date']),"d F Y");}else{echo "-";} ?></h6> 
                                    <small class="text-muted p-t-10 db">Device</small>
                                    <h6><?php if(!empty($data['device_id'])){echo $data['device_id'];}else{echo "-";} ?></h6> 
                                    <?php if(!empty($data['resign_date'])){ ?>
                                        <small class="text-muted p-t-10 db">Resign Date</small>
                                        <h6><?=date_format(date_create($data['resign_date']),"d F Y")?></h6> 
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="button-group">
                    <!-- <button type="button" class="btn waves-effect waves-light btn-outline-primary">Update Password</button> -->
                    <a type="button" href="<?=base_url(); ?>user/reset/<?php echo $data['user_id'].'/'.$data['id']; ?>" class="btn waves-effect waves-light btn-outline-warning" onclick="return confirm('Anda yakin akan reset device user ini?')">Reset Device</a>
                    <?php if(empty($data['resign_date'])){ ?>
                        <a type="button" href="" data-toggle="modal" data-target="#setResign" class="btn waves-effect waves-light btn-outline-danger">Set Resign</a>
                    <?php }else{ ?>
                        <a type="button" href="<?=base_url(); ?>user/reactivate/<?php echo $data['employee_id']."/".$data['id']; ?>" onclick="return confirm('Anda yakin pegawai ini akan di set aktifkan kembali?');" class="btn waves-effect waves-light btn-outline-primary">Set Active</a>
                    <?php } ?>
                    <a type="button" href="" data-toggle="modal" data-target="#downloadReport" class="btn waves-effect waves-light btn-outline-primary">Download Laporan</a>
                </div>
                <div class="modal fade" id="downloadReport" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form method="POST" enctype="multipart/form-data" action="<?php echo base_url(); ?>user/export">                                    
                                <div class="modal-header">
                                    <h4 class="modal-title" id="exampleModalLabel3">Download Reporting Shift</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <div id="form-group" class="form-group">
                                        <label for="recipient-name" class="control-label">Date Range</label>
                                        <input type="hidden" class="form-control" id="user_id" name="user_id" value="<?php echo $data['id']; ?>">
                                        <input class="form-control input-daterange-datepicker" type="text" name="daterange" value="<?php echo date("m/d/Y").' - '.date("m/d/Y");?>" />
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="btnSubmit" id="btnSubmit" value="save" class="btn btn-success"> <i class="fa fa-check"></i> Download</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="setResign" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form method="POST" enctype="multipart/form-data" action="<?php echo base_url(); ?>user/resign">                                    
                                <div class="modal-header">
                                    <h4 class="modal-title" id="exampleModalLabel3">Set Resign</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label class="col-md-12">Tanggal Resign</label>
                                        <div class="col-md-12">
                                            <input name="resign_date" id="example-date-input" type="date" value="<?=date("Y-m-d");?>" class="form-control form-control-line" >
                                            <input type="hidden" class="form-control" id="employee_id" name="employee_id" value="<?php echo $data['id']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="btnSubmit" id="btnSubmit" value="save" class="btn btn-success"> <i class="fa fa-check"></i> Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-12">
                        <div class="card">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs profile-tab" role="tablist">
                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#profile" role="tab">Info Personal</a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#pekerjaan" role="tab">Info Pekerjaan</a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#kontak" role="tab">Info Kontak Keluarga</a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#payroll" role="tab">Info Payroll</a> </li>
                                <!-- <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#document" role="tab">File Document</a> </li> -->
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#absences" role="tab">Absensi</a> </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane active" id="profile" role="tabpanel">
                                    <div class="card-body">
                                        <form method="POST" enctype="multipart/form-data" action="<?php echo base_url();?>user/save" class="form-horizontal form-material">
                                            <?php
											$err_first_name = (form_error('first_name') ? 'has-danger' : '');
											$err_last_name = (form_error('last_name') ? 'has-danger' : '');
											$err_mobile_number = (form_error('mobile_number') ? 'has-danger' : '');
											$err_email = (form_error('email') ? 'has-danger' : '');
											$err_address = (form_error('address') ? 'has-danger' : '');
											?>
											<div class="form-group">
                                                <label class="col-md-12">Nama</label>
                                                <div class="col-md-12">
                                                    <input type="hidden" class="form-control" id="user_id" name="user_id" value="<?php echo $data['user_id']; ?>">
                                                    <input type="hidden" class="form-control" id="emp_id" name="emp_id" value="<?php echo $data['id']; ?>">
                                                    <input name="nama" id="nama" type="text" value="<?php if(!empty($data['name'])){echo $data['name'];}else{echo "-";}; ?>" class="form-control form-control-line" >
												</div>
                                            </div>
											<div class="form-group">
                                                <label class="col-md-12">Email</label>
                                                <div class="col-md-12">
                                                    <input name="email" id="email" type="text" value="<?php if(!empty($data['email'])){echo $data['email'];}else{echo "-";}; ?>" class="form-control form-control-line" >
												</div>
                                            </div>
											<div class="form-group">
                                                <label class="col-md-12">Nomor handphone</label>
                                                <div class="col-md-12">
                                                    <input name="no_hp" id="no_hp" type="text" value="<?php if(!empty($data['phone_number']['no_hp'])){echo $data['phone_number']['no_hp'];}else{echo "-";}; ?>" class="form-control form-control-line" >
												</div>
                                            </div>
											<div class="form-group">
                                                <label class="col-md-12">Telpon Rumah</label>
                                                <div class="col-md-12">
                                                    <input name="home_phone" id="home_phone" type="text" value="<?php if(!empty($data['phone_number']['telp_rumah'])){echo $data['phone_number']['telp_rumah'];}else{echo "-";}; ?>" class="form-control form-control-line" >
												</div>
                                            </div>
											<!-- <div class="form-group">
                                                <label class="col-md-12">Nama Ibu Kandung</label>
                                                <div class="col-md-12">
                                                    <input name="ibu_kandung" id="ibu_kandung" type="text" value="<?php if(!empty($data['email'])){echo $data['email'];}else{echo "-";}; ?>" class="form-control form-control-line" >
												</div>
                                            </div> -->
											<div class="form-group">
                                                <label class="col-md-12">Nomor Identitas (KTP)</label>
                                                <div class="col-md-12">
                                                    <input name="ktp" id="ktp" type="text" value="<?php if(!empty($data['document']['ktp'])){echo $data['document']['ktp'];}else{echo "-";}; ?>" class="form-control form-control-line" >
												</div>
                                            </div>
											<div class="form-group">
                                                <label class="col-md-12">Nomor Kartu Keluarga</label>
                                                <div class="col-md-12">
                                                    <input name="kk" id="kk" type="text" value="<?php if(!empty($data['document']['kk'])){echo $data['document']['kk'];}else{echo "-";}; ?>" class="form-control form-control-line">
												</div>
                                            </div>
											<div class="form-group">
                                                <label class="col-md-12">NPWP</label>
                                                <div class="col-md-12">
                                                    <input name="npwp" id="npwp" type="text" value="<?php if(!empty($data['document']['npwp'])){echo $data['document']['npwp'];}else{echo "-";}; ?>" class="form-control form-control-line" >
												</div>
                                            </div>
											<div class="form-group">
                                                <label class="col-md-12">Tempat Lahir</label>
                                                <div class="col-md-12">
                                                    <input name="tempat_lahir" id="tempat_lahir" type="text" value="<?php if(!empty($data['birthplace'])){echo $data['birthplace'];}else{echo "-";}; ?>" class="form-control form-control-line" >
												</div>
                                            </div>
											<div class="form-group">
                                                <label class="col-md-12">Tanggal Lahir</label>
                                                <div class="col-md-12">
                                                    <input name="tanggal_lahir" id="example-date-input" type="date" value="<?php if(!empty($data['birthdate'])){echo date_format(date_create($data['birthdate']),"Y-m-d");}else{echo "-";}; ?>" class="form-control form-control-line" >
												</div>
                                            </div>
											<div class="form-group">
                                                <label class="col-md-12">Alamat Sesuai KTP</label>
                                                <div class="col-md-12">
                                                    <input name="alamat_ktp" id="alamat_ktp" type="text" value="<?php if(!empty($data['address']['alamat_ktp'])){echo $data['address']['alamat_ktp'];}else{echo "-";}; ?>" class="form-control form-control-line" >
												</div>
                                            </div>
											<div class="form-group">
                                                <label class="col-md-12">Alamat Domisili</label>
                                                <div class="col-md-12">
                                                    <input name="alamat_dom" id="alamat_dom" type="text" value="<?php if(!empty($data['address']['alamat_domisili'])){echo $data['address']['alamat_domisili'];}else{echo "-";}; ?>" class="form-control form-control-line" >
												</div>
                                            </div>
											<div class="form-group">
                                                <label class="col-md-12">Jenis Kelamin</label>
                                                <div class="col-md-12">
                                                    <select name="gender" id="gender" class="custom-select col-12">
                                                        <option <?php if($data['gender']=="laki-laki"){echo "selected";} ?> value="laki-laki">Laki-laki</option>
                                                        <option <?php if($data['gender']=="perempuan"){echo "selected";} ?> value="perempuan">Perempuan</option>
                                                    </select>
												</div>
                                            </div>
											<div class="form-group">
                                                <label class="col-md-12">Status Pernikahan</label>
                                                <div class="col-md-12">
                                                    <select name="marital_status" id="marital_status" class="custom-select col-12">
                                                        <option <?php if($data['marital_status']=="single"){echo "selected";} ?> value="single">Single</option>
                                                        <option <?php if($data['marital_status']=="menikah"){echo "selected";} ?> value="menikah">Menikah</option>
                                                        <option <?php if($data['marital_status']=="janda/duda"){echo "selected";} ?> value="janda/duda">Janda/Duda</option>
                                                    </select>
												</div>
                                            </div>
											<div class="form-group">
                                                <label class="col-md-12">Golongan Darah</label>
                                                <div class="col-md-12">
                                                    <select name="blood_type" id="blood_type" class="custom-select col-12">
                                                        <option <?php if($data['blood_type']=="A"){echo "selected";} ?> value="A">A</option>
                                                        <option <?php if($data['blood_type']=="B"){echo "selected";} ?> value="B">B</option>
                                                        <option <?php if($data['blood_type']=="O"){echo "selected";} ?> value="O">O</option>
                                                        <option <?php if($data['blood_type']=="AB"){echo "selected";} ?> value="AB">AB</option>
                                                    </select>
												</div>
                                            </div>
											<div class="form-group">
                                                <label class="col-md-12">Agama</label>
                                                <div class="col-md-12">
                                                    <select name="religion" id="religion" class="custom-select col-12">
                                                        <option <?php if($data['religion']=="Islam"){echo "selected";} ?> value="Islam">Islam</option>
                                                        <option <?php if($data['religion']=="Katolik"){echo "selected";} ?> value="Katolik">Katolik</option>
                                                        <option <?php if($data['religion']=="Protestan"){echo "selected";} ?> value="Protestan">Protestan</option>
                                                        <option <?php if($data['religion']=="Hindu"){echo "selected";} ?> value="Hindu">Hindu</option>
                                                        <option <?php if($data['religion']=="Budha"){echo "selected";} ?> value="Budha">Budha</option>
                                                    </select>
												</div>
                                            </div>
                                            <button type="submit" onclick="return confirm('Anda yakin akan mengupdate data pegawai ini?');" name="btnSubmit" id="btnSubmit" value="save" class="btn btn-success"> <i class="fa fa-check"></i> Update Info Personal</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-pane" id="pekerjaan" role="tabpanel">
                                    <div class="card-body">
                                        <form method="POST" enctype="multipart/form-data" action="<?php echo base_url();?>user/save_work" class="form-horizontal form-material">
                                            <div class="form-group">
                                                <label class="col-md-12">ID Karyawan</label>
                                                <div class="col-md-12">
                                                    <input name="employee_id" id="employee_id" type="hidden" value="<?php echo $data['id']; ?>">
                                                    <input name="emp_id" id="emp_id" type="text" value="<?php echo $data['employee_id']; ?>" class="form-control form-control-line" >
												</div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-12">Posisi Pekerjaan</label>
                                                <div class="col-md-12">
                                                    <input name="position" id="position" type="text" value="<?php if(!empty($data['position'])){echo $data['position'];}else{echo "-";}; ?>" class="form-control form-control-line" disabled>
												</div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-12">Level Pekerjaan</label>
                                                <div class="col-md-12">
                                                    <input name="level" id="level" type="text" value="<?php if(!empty($data['level'])){echo $data['level'];}else{echo "-";}; ?>" class="form-control form-control-line" disabled>
												</div>
                                            </div>
											<div class="form-group">
                                                <label class="col-md-12">Status Karyawan</label>
                                                <div class="col-md-12">
                                                    <select name="status" id="status" class="custom-select col-12">
                                                        <option <?php if($data['status']=="probation"){echo "selected";} ?> value="probation">Probation</option>
                                                        <option <?php if($data['status']=="contract"){echo "selected";} ?> value="contract">Contract</option>
                                                        <option <?php if($data['status']=="permanent"){echo "selected";} ?> value="permanent">Permanent</option>
                                                    </select>
												</div>
                                            </div>
											<div class="form-group">
                                                <label class="col-md-12">Tanggal Bergabung</label>
                                                <div class="col-md-12">
                                                    <input name="join" id="example-date-input" type="date" value="<?php if(!empty($data['join_date'])){echo date_format(date_create($data['join_date']),"Y-m-d");}else{echo "-";}; ?>" class="form-control form-control-line" >
												</div>
                                            </div>
											<div class="form-group">
                                                <label class="col-md-12">Tanggal Selesai Kontrak</label>
                                                <div class="col-md-12">
                                                    <input name="end_date" id="example-date-input" type="date" value="<?php if(!empty($data['end_date'])){echo date_format(date_create($data['end_date']),"Y-m-d");}else{echo "-";}; ?>" class="form-control form-control-line" >
												</div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-12">Manager ID</label>
                                                <div class="col-md-12">
                                                    <input name="manager_id" id="manager_id" type="text" value="<?php if(!empty($data['manager_id'])){echo $data['manager_id'];}else{echo "-";}; ?>" class="form-control form-control-line">
												</div>
                                            </div>
                                            <button type="submit" onclick="return confirm('Anda yakin akan mengupdate data pegawai ini?');" name="btnSubmit" id="btnSubmit" value="save" class="btn btn-success"> <i class="fa fa-check"></i> Update Info Pekerjaan</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-pane" id="kontak" role="tabpanel">
                                    <div class="card-body">
                                        <?php if (!empty($data['family_data'])){ ?>
                                        <form method="POST" enctype="multipart/form-data" action="<?php echo base_url();?>profile/save" class="form-horizontal form-material">
											<div class="form-group">
                                                <label class="col-md-12">Nama Keluarga</label>
                                                <div class="col-md-12">
                                                    <input name="name" id="name" type="text" value="<?php if(!empty($data['name'])){echo $data['name'];}else{echo "-";}; ?>" class="form-control form-control-line" >
												</div>
                                            </div>
											<div class="form-group">
                                                <label class="col-md-12">Status Keluarga</label>
                                                <div class="col-md-12">
                                                    <input name="emp_id" id="emp_id" type="text" value="<?php echo $data['employee_id']; ?>" class="form-control form-control-line" >
												</div>
                                            </div>
											<div class="form-group">
                                                <label class="col-md-12">Nomor Handphone</label>
                                                <div class="col-md-12">
                                                    <input name="email" id="email" type="text" value="<?php if(!empty($data['email'])){echo $data['email'];}else{echo "-";}; ?>" class="form-control form-control-line" >
												</div>
                                            </div>
                                            <button type="submit" onclick="return confirm('Anda yakin akan mengupdate data pegawai ini?');" name="btnSubmit" id="btnSubmit" value="save" class="btn btn-success"> <i class="fa fa-check"></i> Update Info Keluarga</button>
                                        </form>
                                        <?php }else{ ?>
                                            <div class="form-group">
                                                <label class="col-md-12">Data Keluarga Kosong</label>
                                            </div>                                            
                                            <?php } ?>
                                    </div>
                                </div>
                                <div class="tab-pane" id="payroll" role="tabpanel">
                                    <div class="card-body">
                                        <form method="POST" enctype="multipart/form-data" action="<?php echo base_url();?>profile/save" class="form-horizontal form-material">
											<div class="form-group">
                                                <label class="col-md-12">BJPS Ketenagakerjaan</label>
                                                <div class="col-md-12">
                                                    <input name="name" id="name" type="text" value="<?php if(!empty($data['bpjs']['ketenagakerjaan'])){echo $data['bpjs']['ketenagakerjaan'];}else{echo "-";}; ?>" class="form-control form-control-line" >
												</div>
                                            </div>
											<div class="form-group">
                                                <label class="col-md-12">BPJS Kesehatan</label>
                                                <div class="col-md-12">
                                                    <input name="emp_id" id="emp_id" type="text" value="<?php if(!empty($data['bpjs']['kesehatan'])){echo $data['bpjs']['kesehatan'];}else{echo "-";}; ?>" class="form-control form-control-line" >
												</div>
                                            </div>
											<div class="form-group">
                                                <label class="col-md-12">NPWP</label>
                                                <div class="col-md-12">
                                                    <input name="npwp" id="npwp" type="text" value="<?php if(!empty($data['document']['npwp'])){echo $data['document']['npwp'];}else{echo "-";}; ?>" class="form-control form-control-line" >
												</div>
                                            </div>
											<div class="form-group">
                                                <label class="col-md-12">Nama Bank</label>
                                                <div class="col-md-12">
                                                    <input name="bank" id="bank" type="text" value="<?php if(!empty($data['bank']['name'])){echo $data['bank']['name'];}else{echo "-";}; ?>" class="form-control form-control-line" >
												</div>
                                            </div>
											<div class="form-group">
                                                <label class="col-md-12">Nomor Rekening</label>
                                                <div class="col-md-12">
                                                    <input name="email" id="email" type="text" value="<?php if(!empty($data['bank']['account'])){echo $data['bank']['account'];}else{echo "-";}; ?>" class="form-control form-control-line" >
												</div>
                                            </div>
											<div class="form-group">
                                                <label class="col-md-12">Nama Pemilik Rekening</label>
                                                <div class="col-md-12">
                                                    <input name="email" id="email" type="text" value="<?php if(!empty($data['bank']['account_holder'])){echo $data['bank']['account_holder'];}else{echo "-";}; ?>" class="form-control form-control-line" >
												</div>
                                            </div>
											<div class="form-group">
                                                <label class="col-md-12">Status PTKP</label>
                                                <div class="col-md-12">
                                                    <input name="email" id="email" type="text" value="<?php if(!empty($data['tax']['ptkp_status'])){echo $data['tax']['ptkp_status'];}else{echo "-";}; ?>" class="form-control form-control-line" >
												</div>
                                            </div>
                                            <button type="submit" onclick="return confirm('Anda yakin akan mengupdate data pegawai ini?');" name="btnSubmit" id="btnSubmit" value="save" class="btn btn-success"> <i class="fa fa-check"></i> Update Info Payroll</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-pane" id="absences" role="tabpanel">
                                    <div class="card-body">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                <div class="form-group row">
                                                    <label for="example-search-input" class="col-1 col-form-label">Filter : </label>
                                                    <div class="col-3">
                                                        <input class="form-control" type="month" name="filter_bulan" value="<?=date('Y-m')?>" id="filter_bulan">
                                                    </div><div class="col-3">
                                                        <button type="button" class="btn waves-effect waves-light btn-outline-primary" id="submit_filter" name="submit_filter" onclick="filterData()">Refresh Data</button>
                                                    </div>
                                                </div>
                                                    <table id="tableData" class="table color-bordered-table">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 30px;">No</th>    
                                                                <th style="width: 30px;">Tanggal</th>
                                                                <th style="width: 100px;">Location</th>
                                                                <th style="width: 30px;">Shift</th>
                                                                <th style="width: 70px;">Clock In</th>
                                                                <th style="width: 100px;">Clock Out</th>
                                                                <th style="width: 80px;">Notes</th>
                                                                <th style="width: 50px;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                            // if (count($absences) > 0) {
                                                            //     foreach ($absences as $key => $dt) {
                                                            //         $location = json_decode((string)$dt["pinpoint"], true);
                                                            //         echo "<tr>";
                                                            //         echo "<td>" . $dt['absence_date'] . "</td>";
                                                            //         echo "<td>" . $dt['location_name'] . "</td>";
                                                            //         echo "<td>" . $dt['shift_name'] . "</td>";
                                                            //         echo "<td>" . $dt['time_in'] . "<br>";
                                                            //             if(!empty($location['in'])){
                                                            //                 echo "<iframe src = 'https://maps.google.com/maps?q=".$location['in']['lat'].",".$location['in']['long']."&hl=es;z=14&amp;output=embed'></iframe>";
                                                            //             };
                                                            //         echo "</td>";
                                                            //         echo "<td>" . $dt['time_out'] . "<br>";
                                                            //             if(!empty($location['in'])){
                                                            //                 echo "<iframe src = 'https://maps.google.com/maps?q=".$location['in']['lat'].",".$location['in']['long']."&hl=es;z=14&amp;output=embed'></iframe>";
                                                            //             };
                                                            //         echo "</td>";
                                                            //         echo "<td>" . $dt['notes'] . "</td>";
                                                            //         echo "<td>";

                                                            //         echo "<a href='" . base_url() . "user/edit/" . $dt['user_id'] . "'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
                                                            //         echo "</td>";
                                                            //         echo "</tr>";
                                                            //     }
                                                            // } else {
                                                            //     echo "<tr><td colspan='8' align='center'>Tidak ada data</td></tr>";
                                                            // }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                    </div>
                    <!-- Column -->
                </div>
                <!-- Row -->
            </div>
            <footer class="footer"> © <?echo date("Y");?> SGR Patrol Admin </footer>
        </div>
    </div>
	<?php $this->load->view("partial/v_script_bottom"); ?>
</body>
<?php
	if($this->session->flashdata('success')){
		echo "<script type='text/javascript'>alertify.alert('".$this->session->flashdata('success')."');</script>";
        $this->session->unset_userdata('success');
	}
?>
<script src="<?php echo base_url(); ?>plugins/moment/moment.js"></script>
<script src="<?php echo base_url(); ?>plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
<!-- Clock Plugin JavaScript -->
<script src="<?php echo base_url(); ?>plugins/clockpicker/dist/jquery-clockpicker.min.js"></script>
<!-- Color Picker Plugin JavaScript -->
<script src="<?php echo base_url(); ?>plugins/jquery-asColorPicker-master/libs/jquery-asColor.js"></script>
<script src="<?php echo base_url(); ?>plugins/jquery-asColorPicker-master/libs/jquery-asGradient.js"></script>
<script src="<?php echo base_url(); ?>plugins/jquery-asColorPicker-master/dist/jquery-asColorPicker.min.js"></script>
<!-- Date Picker Plugin JavaScript -->
<script src="<?php echo base_url(); ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<!-- Date range Plugin JavaScript -->
<script src="<?php echo base_url(); ?>plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script src="<?php echo base_url(); ?>plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<script>
    // MAterial Date picker    
    $('#mdate').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
         $('#timepicker').bootstrapMaterialDatePicker({ format : 'HH:mm', time: true, date: false });
    $('#date-format').bootstrapMaterialDatePicker({ format : 'dddd DD MMMM YYYY - HH:mm' });
   
        $('#min-date').bootstrapMaterialDatePicker({ format : 'DD/MM/YYYY HH:mm', minDate : new Date() });
    // Clock pickers
    $('#single-input').clockpicker({
        placement: 'bottom',
        align: 'left',
        autoclose: true,
        'default': 'now'
    });
    $('.clockpicker').clockpicker({
        donetext: 'Done',
    }).find('input').change(function() {
        console.log(this.value);
    });
    $('#check-minutes').click(function(e) {
        // Have to stop propagation here
        e.stopPropagation();
        input.clockpicker('show').clockpicker('toggleView', 'minutes');
    });
    if (/mobile/i.test(navigator.userAgent)) {
        $('input').prop('readOnly', true);
    }
    // Colorpicker
    $(".colorpicker").asColorPicker();
    $(".complex-colorpicker").asColorPicker({
        mode: 'complex'
    });
    $(".gradient-colorpicker").asColorPicker({
        mode: 'gradient'
    });
    // Date Picker
    jQuery('.mydatepicker, #datepicker').datepicker();
    jQuery('#datepicker-autoclose').datepicker({
        autoclose: true,
        todayHighlight: true
    });
    jQuery('#date-range').datepicker({
        toggleActive: true
    });
    jQuery('#datepicker-inline').datepicker({
        todayHighlight: true
    });
    // Daterange picker
    $('.input-daterange-datepicker').daterangepicker({
        buttonClasses: ['btn', 'btn-sm'],
        applyClass: 'btn-danger',
        cancelClass: 'btn-inverse'
    });
    $('.input-daterange-timepicker').daterangepicker({
        timePicker: true,
        format: 'MM/DD/YYYY h:mm A',
        timePickerIncrement: 30,
        timePicker12Hour: true,
        timePickerSeconds: false,
        buttonClasses: ['btn', 'btn-sm'],
        applyClass: 'btn-danger',
        cancelClass: 'btn-inverse'
    });
    $('.input-limit-datepicker').daterangepicker({
        format: 'MM/DD/YYYY',
        minDate: '06/01/2015',
        maxDate: '06/30/2015',
        buttonClasses: ['btn', 'btn-sm'],
        applyClass: 'btn-danger',
        cancelClass: 'btn-inverse',
        dateLimit: {
            days: 6
        }
    });
    </script>

<script type="text/javascript">
    function filterData() {
        let filter = $("#filter_bulan").val();
        const myArray = filter.split("-");
        let bulan = myArray[1];
        let tahun = myArray[0];

        $('#tableData').DataTable({
            "processing": true,
            "responsive":true,
            "serverSide": true,
            "ordering": true, // Set true agar bisa di sorting
            "order": [[ 1, 'desc' ]], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
            "ajax": {
                data: {
                            employee_id: <?=$data['id']?>,
							month: bulan,
                            year: tahun
						},
                    "url": "<?= base_url('user/view_data_query_absence'); ?>", // URL file untuk proses select datanya
                    "type": "POST"
                },
            "deferRender": true,
            "aLengthMenu": [[25, 50, 100],[ 25, 50, 100]], // Combobox Limit
            "columns": [
                {"data": 'id',"sortable": false, 
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }  
                },
                { "data": "absence_date" },
                { "data": "location_name" },
                { "data": "shift_name" },
                { "data": "time_in" },
				{ "data": "time_out" },
				{ "data": "notes" },
                { "data": "id",
                    "render": 
                    function( data, type, row, meta ) {
                        return '<a  class="btn waves-effect waves-light btn-outline-info" target="blank" href="<?=BASE_URL?>/user/absence_detail/'+row.absence_id+'">Show Detail</a>';
                    }
                },
            ],
			"bDestroy": true,
        });
    }

</script>

</html>