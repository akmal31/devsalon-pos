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
                            <li class="breadcrumb-item active">Profile</li>
                        </ol>
                    </div>
                </div>
                <!-- Row -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-4 col-xlg-3 col-md-5">
                        <div class="card">
                            <div class="card-body">
                                <center class="m-t-30"> <img src="<?php echo base_url(); ?>assets/user.png" class="img-circle" width="80" />
                                    <h4 class="card-title m-t-10"><?php echo $data['name']; ?></h4>
                                    <h5 class="card-subtitle"><?php echo $data['employee_id']; ?></h5>
                                </center>
                            </div>
                            <div>
                                <hr> </div>
                            <div class="card-body"> 
								<small class="text-muted">Email address </small>
                                <h6><?php echo $data['email']; ?></h6> 
								<small class="text-muted p-t-30 db">Phone</small>
                                <h6><?php echo $data['phone_number']; ?></h6> 
								<small class="text-muted p-t-30 db">Address</small>
                                <h6><?php echo $data['address']; ?></h6>
                                
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-8 col-xlg-9 col-md-7">
                        <div class="card">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs profile-tab" role="tablist">
                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#settings" role="tab">Update Profile</a> </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane active" id="settings" role="tabpanel">
                                    <div class="card-body">
                                        <form method="POST" enctype="multipart/form-data" action="<?php echo base_url();?>profile/save" class="form-horizontal form-material">
                                            <?php
											$err_first_name = (form_error('first_name') ? 'has-danger' : '');
											$err_last_name = (form_error('last_name') ? 'has-danger' : '');
											$err_mobile_number = (form_error('mobile_number') ? 'has-danger' : '');
											$err_email = (form_error('email') ? 'has-danger' : '');
											$err_address = (form_error('address') ? 'has-danger' : '');
											?>
											<div class="form-group">
                                                <label class="col-md-12">Username</label>
                                                <div class="col-md-12">
                                                    <input name="username" id="username" type="username" value="<?php echo $data['name']; ?>" class="form-control form-control-line" disabled>
													<small class='form-control-feedback'> Username tidak bisa diubah. </small>
												</div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-12">Password</label>
                                                <div class="col-md-12">
                                                    <input name="pass" id="pass" type="password" value="" class="form-control form-control-line">
													<small class='form-control-feedback'> Kosongkan password bila tidak ingin diubah. </small>
												</div>
                                            </div>
											<hr>
                                            <div class="form-group <?php echo $err_first_name; ?>">
                                                <label class="col-md-12">First Name</label>
                                                <div class="col-md-12">
													<?php $first_name = (set_value('first_name') ? set_value('first_name') : (isset($data['first_name']) ? $data['first_name'] : '')); ?>
                                                    <input name="first_name" id="first_name" type="text" value="<?php echo $first_name; ?>" class="form-control form-control-line">
													<?php echo form_error('first_name'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group <?php echo $err_last_name; ?>">
                                                <label class="col-md-12">Last Name</label>
                                                <div class="col-md-12">
													<?php $last_name = (set_value('last_name') ? set_value('last_name') : (isset($data['last_name']) ? $data['last_name'] : '')); ?>
                                                    <input name="last_name" id="last_name" type="text" value="<?php echo $last_name; ?>" class="form-control form-control-line">
													<?php echo form_error('last_name'); ?>
												</div>
                                            </div>
                                            <div class="form-group <?php echo $err_email; ?>">
                                                <label for="example-email" class="col-md-12">Email</label>
                                                <div class="col-md-12">
													<?php $email = (set_value('email') ? set_value('email') : (isset($data['email']) ? $data['email'] : '')); ?>
                                                    <input name="email" id="email" type="email" value="<?php echo $email; ?>" class="form-control form-control-line" name="example-email" id="example-email">
													<?php echo form_error('email'); ?>
												</div>
                                            </div>
                                            <div class="form-group <?php echo $err_mobile_number; ?>">
                                                <label class="col-md-12">Phone Number</label>
                                                <div class="col-md-12">
													<?php $mobile_number = (set_value('mobile_number') ? set_value('mobile_number') : (isset($data['mobile_number']) ? $data['mobile_number'] : '')); ?>
                                                    <input name="mobile_number" id="mobile_number" type="text" value="<?php echo $mobile_number; ?>" class="form-control form-control-line">
													<?php echo form_error('mobile_number'); ?>
												</div>
                                            </div>
                                            <div class="form-group <?php echo $err_address; ?>">
                                                <label class="col-md-12">Address</label>
                                                <div class="col-md-12">
													<?php $address = (set_value('address') ? set_value('address') : (isset($data['address']) ? $data['address'] : '')); ?>
                                                    <textarea name="address" id="address" rows="5" class="form-control form-control-line"><?php echo $address; ?></textarea>
													<?php echo form_error('address'); ?>
												</div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <button type="submit" class="btn btn-success" name="btnSubmit" id="btnSubmit" value="save">Update Profile</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                </div>
                <!-- Row -->
            </div>
            <footer class="footer"> © 2022 Recook Admin </footer>
        </div>
    </div>
	<?php $this->load->view("partial/v_script_bottom"); ?>
</body>
<?php
	if($msg != ""){
		echo "<script type='text/javascript'>alertify.alert('".$msg."');</script>";
	}
?>

<script>
	function confirm_del(id){

		alertify.confirm("Apakah anda ingin menghapus distributor ini?", function (e) {
			if (e) {
				var url = '<?php echo SITE_URI. "distributor/del/";?>'+id;
				location.href=url;
			}
		});
		return false;
	}
</script>
</html>