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
                        <h3 class="text-themecolor">Distributor</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>/distributor">Distributor</a></li>
                            <li class="breadcrumb-item active"><?php if(isset($data['distributor_id'])){ echo "Edit"; }else{ echo "Add"; }?></li>
                        </ol>
                    </div>
                </div>
                <!-- Row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-outline-info">
                            <div class="card-header">
                                <h4 class="m-b-0 text-white"><?php if(isset($data['distributor_id'])){ echo "Update"; }else{ echo "Create"; }?> distributor</h4>
                            </div>
                            <div class="card-body">
                                <form method="POST" enctype="multipart/form-data" action="<?php echo base_url();?>distributor/save" >
								<?php
								$err_latitude = (form_error('latitude') ? 'has-danger' : '');
								$err_longitude = (form_error('longitude') ? 'has-danger' : '');
								$err_name = (form_error('name') ? 'has-danger' : '');
								$err_phone = (form_error('phone') ? 'has-danger' : '');
								$err_address = (form_error('address') ? 'has-danger' : '');
								$err_active = (form_error('active') ? 'has-danger' : '');
								
								?>
                                    <div name="frmview" class="form-body">
                                        <div class="row p-t-20">
                                            <div class="col-md-6">
                                                <div class="form-group <?php echo $err_latitude;?>">
													<?php $latitude = (set_value('latitude') ? set_value('latitude') : (isset($data['latitude']) ? $data['latitude'] : '')); ?>
                                                    <label class="control-label">Location (Latitude)</label>
                                                    <input type="text" id="latitude" name="latitude" class="form-control" placeholder="Isi dengan Latitude (contoh:-6.3045502)" value="<?php echo $latitude;?>">
													<?php echo form_error('latitude'); ?>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group <?php echo $err_longitude;?>">
													<?php $longitude = (set_value('longitude') ? set_value('longitude') : (isset($data['longitude']) ? $data['longitude'] : '')); ?>
                                                    <label class="control-label">Location (Longitude)</label>
                                                    <input type="text" id="longitude" name="longitude" class="form-control form-control-danger" placeholder="Isi dengan Longitude (contoh:107.0097057,15)" value="<?php echo $longitude;?>">
													<?php echo form_error('longitude'); ?>
												</div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                        <div class="row p-t-20">
                                            <div class="col-md-6">
                                                <div class="form-group <?php echo $err_name;?>">
													<?php $name = (set_value('name') ? set_value('name') : (isset($data['name']) ? $data['name'] : '')); ?>
                                                    <label class="control-label">Nama Distributor</label>
                                                    <input type="text" id="name" name="name" class="form-control" placeholder="Isi dengan Nama Distributor" value="<?php echo $name;?>">
													<?php echo form_error('name'); ?>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group <?php echo $err_phone;?>">
													<?php $phone = (set_value('phone') ? set_value('phone') : (isset($data['phone']) ? $data['phone'] : '')); ?>
                                                    <label class="control-label">Nomor Telpon Distributor</label>
                                                    <input type="text" id="phone" name="phone" class="form-control form-control-danger" placeholder="Isi dengan nomor telpon distributor" value="<?php echo $phone;?>">
													<?php echo form_error('phone'); ?>
												</div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
										<div class="row">
											<div class="col-6">
												<div class="form-group <?php echo $err_address;?>">
												<?php $address = (set_value('address') ? set_value('address') : (isset($data['address']) ? $data['address'] : '')); ?>
												<label class="control-label">Alamat Distributor</label>
												<textarea class="textarea_editor form-control" id="address" name="address" rows="5" placeholder="Isi dengan alamat distributor"><?php echo $address;?></textarea>
												<?php echo form_error('address'); ?>
												</div>
											</div>
										</div>
										<div class="row">
                                            <!--/span-->
											<div class="col-md-6">
                                                <div class="form-group">
													<div class="checkbox checkbox-success">
														<?php
														$active = (set_value('active') ? "checked='checked'" : (isset($data['active']) ? "checked='checked'" : ''));														
														?>
														<input id="checkbox33" type="checkbox" id="active" name="active" <?php echo $active;?>>
														<label for="checkbox33">Active</label>
													</div>
												</div>
                                            </div>
                                        </div>
                                        <!--/row-->
                                    </div>
                                    <div class="form-actions">
                                        <button type="submit" name="btnSubmit" id="btnSubmit" value="save" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
                                        <button type="submit" class="btn btn-inverse" value="">Cancel</button>
                                    </div>
									<?php 
										if(isset($data['distributor_id'])){
									?>
										<input type="hidden" name="distributor_id" value="<?php echo $data["distributor_id"]; ?>">
									<?php	
										}
									?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Row -->
            </div>
            <footer class="footer"> © 2022 Recook Admin </footer>
        </div>
    </div>
	<?php $this->load->view("partial/v_script_bottom"); ?>
</body>

</html>