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
                        <h3 class="text-themecolor">User</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>/user">User</a></li>
                            <li class="breadcrumb-item active"><?php if(isset($data['gallery_id'])){ echo "Edit"; }else{ echo "Add"; }?></li>
                        </ol>
                    </div>
                </div>
				<div class="row">
				<div class="col-lg-12">
                        <div class="card card-outline-info">
                            <div class="card-header">
                                <h4 class="m-b-0 text-white"><?php if(isset($data['user_group_id'])){ echo "Update"; }else{ echo "Create"; }?> User Group</h4>
                            </div>
                            <div class="card-body">
                                <form method="POST" enctype="multipart/form-data" action="<?php echo base_url();?>user_group/save" >
								<?php
								$err_group_name = (form_error('group_name') ? 'has-danger' : '');
								$err_description = (form_error('description') ? 'has-danger' : '');
								
								?>
                                    <div name="frmview" class="form-body">
                                        <div class="row p-t-20">
                                            <div class="col-md-6">
                                                <div class="form-group <?php echo $err_group_name;?>">
													<?php $group_name = (set_value('group_name') ? set_value('group_name') : (isset($data['name']) ? $data['name'] : '')); ?>
                                                    <label class="control-label">Group Name</label>
                                                    <input type="text" class="form-control" id="group_name" name="group_name" placeholder="Group Name" value="<?=$group_name?>">
													<?php echo form_error('group_name'); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group <?php echo $err_description;?>">
													<?php $description = (set_value('description') ? set_value('description') : (isset($data['description']) ? $data['description'] : '')); ?>
                                                    <label class="control-label">Description</label>
                                                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Description"><?=$description?></textarea>
													<?php echo form_error('description'); ?>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
										<!--/row-->
										<div class="row">
											<div class="col-md-6">
                                                <div class="form-group">
													<div class="checkbox checkbox-success">
														<?php
														$active = (set_value('active') ? "checked='checked'" : (isset($data['active']) ? "checked='checked'" : ''));														
														?>
														<input id="checkbox33" type="checkbox" name="active" <?php echo $active;?>>
														<label for="checkbox33">Active</label>
													</div>
												</div>
                                            </div>
                                        </div>
                                        <!--/row-->
								</div>
								<div class="form-actions">
									<button type="submit" name="btnSubmit" id="btnSubmit" value="save" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
									<button type="button" class="btn btn-inverse" onClick="location.href='<?php echo base_url(). "user_group"; ?>'">Cancel</button>
								</div>
								<?php if(isset($data['user_group_id'])){ ?>
									<input type="hidden" name="user_group_id" value="<?php echo $data["user_group_id"]; ?>">
								<?php } ?>
                            </form>
						</div>
					</div>
				</div>
            <footer class="footer"> © 2022 Recook Admin </footer>
        </div>
    </div>
	<?php $this->load->view("partial/v_script_bottom"); ?>
</body>

</html>