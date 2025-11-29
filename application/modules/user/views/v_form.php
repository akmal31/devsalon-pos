<!DOCTYPE html>
<html lang="en">

<?php $this->load->view("partial/v_html_header"); ?>

<body class="fix-header fix-sidebar card-no-border">
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
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
                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>user">User</a></li>
                            <li class="breadcrumb-item active"><?php if (isset($data['user_id'])) {
                                                                    echo "Edit";
                                                                } else {
                                                                    echo "Add";
                                                                } ?></li>
                        </ol>
                    </div>
                </div>
                <!-- Row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-outline-info">
                            <div class="card-header">
                                <h4 class="m-b-0 text-white"><?php if (isset($data['user_id'])) {
                                                                    echo "Update";
                                                                } else {
                                                                    echo "Create";
                                                                } ?> User</h4>
                            </div>
                            <div class="card-body">
                                <form method="POST" enctype="multipart/form-data" action="<?php echo base_url(); ?>user/save">
                                    <?php
                                    $err_username = (form_error('username') ? 'has-danger' : '');
                                    $err_password = (form_error('password') ? 'has-danger' : '');
                                    $err_description = (form_error('description') ? 'has-danger' : '');
                                    $err_email = (form_error('email') ? 'has-danger' : '');
                                    $err_first_name = (form_error('first_name') ? 'has-danger' : '');
                                    $err_last_name = (form_error('last_name') ? 'has-danger' : '');
                                    $err_address = (form_error('address') ? 'has-danger' : '');
                                    $err_user_group_id = (form_error('user_group_id') ? 'has-danger' : '');
                                    $err_mobile_number = (form_error('mobile_number') ? 'has-danger' : '');
                                    $err_active = (form_error('active') ? 'has-danger' : '');

                                    ?>
                                    <h3 class="box-title m-t-40">User</h3>
                                    <hr>
                                    <div name="frmview" class="form-body">
                                        <div class="row p-t-20">
                                            <div class="col-md-6">
                                                <div class="form-group <?php echo $err_username; ?>">
                                                    <?php $username = (set_value('username') ? set_value('username') : (isset($data['username']) ? $data['username'] : '')); ?>
                                                    <label class="control-label">Username</label>
                                                    <input type="text" id="username" name="username" class="form-control" placeholder="Isi dengan Username" value="<?php echo $username; ?>" <?php if (isset($data['user_id'])) {
                                                                                                                                                                                                    echo "readonly";
                                                                                                                                                                                                } ?>>
                                                    <?php echo form_error('username'); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group <?php echo $err_password; ?>">
                                                    <?php $password = (set_value('password') ? set_value('password') : (isset($data['password']) ? $data['password'] : '')); ?>
                                                    <label class="control-label">Password</label>
                                                    <input type="password" id="password" name="password" class="form-control" placeholder="Isi dengan password" value="<?php echo $password; ?>" <?php if (isset($data['user_id'])) {
                                                                                                                                                                                                        echo "readonly";
                                                                                                                                                                                                    } ?>>
                                                    <?php echo form_error('password'); ?>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group <?php echo $err_description; ?>">
                                                    <?php $description = (set_value('description') ? set_value('description') : (isset($data['description']) ? $data['description'] : '')); ?>
                                                    <label class="control-label">Description User</label>
                                                    <textarea class="form-control" id="description" name="description" rows="5" placeholder="Enter text ..." <?php if (isset($data['user_id'])) {
                                                                                                                                                                    echo "readonly";
                                                                                                                                                                } ?>><?php echo $description; ?></textarea>
                                                    <?php echo form_error('description'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/row-->
                                        <?php if (!isset($data['user_id'])) { ?>
                                            <div class="row">
                                                <!--/span-->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="checkbox checkbox-success">
                                                            <?php
                                                            $active = (set_value('active') ? "checked='checked'" : (isset($data['active']) ? "checked='checked'" : ''));
                                                            ?>
                                                            <input id="checkbox33" type="checkbox" id="active" name="active" <?php echo $active; ?>>
                                                            <label for="checkbox33">Active</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/row-->
                                        <?php } ?>
                                        <h3 class="box-title m-t-40">User Profile</h3>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group <?php echo $err_first_name; ?>">
                                                    <?php $first_name = (set_value('first_name') ? set_value('first_name') : (isset($data['first_name']) ? $data['first_name'] : '')); ?>
                                                    <label class="control-label">First Name</label>
                                                    <input type="first_name" id="first_name" name="first_name" class="form-control" placeholder="Isi dengan nama awal" value="<?php echo $first_name; ?>">
                                                    <?php echo form_error('first_name'); ?>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group <?php echo $err_last_name; ?>">
                                                    <?php $last_name = (set_value('last_name') ? set_value('last_name') : (isset($data['last_name']) ? $data['last_name'] : '')); ?>
                                                    <label class="control-label">Last Name</label>
                                                    <input type="last_name" id="last_name" name="last_name" class="form-control" placeholder="Isi dengan nama akhir" value="<?php echo $last_name; ?>">
                                                    <?php echo form_error('last_name'); ?>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group <?php echo $err_email; ?>">
                                                    <?php $email = (set_value('email') ? set_value('email') : (isset($data['email']) ? $data['email'] : '')); ?>
                                                    <label class="control-label">Email</label>
                                                    <input type="email" id="email" name="email" class="form-control" placeholder="Isi dengan email" value="<?php echo $email; ?>">
                                                    <?php echo form_error('email'); ?>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group <?php echo $err_mobile_number; ?>">
                                                    <?php $mobile_number = (set_value('mobile_number') ? set_value('mobile_number') : (isset($data['mobile_number']) ? $data['mobile_number'] : '')); ?>
                                                    <label class="control-label">Mobile Number</label>
                                                    <input type="mobile_number" id="mobile_number" name="mobile_number" class="form-control" placeholder="Isi dengan mobile number" value="<?php echo $mobile_number; ?>">
                                                    <?php echo form_error('mobile_number'); ?>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group <?php echo $err_address; ?>">
                                                    <?php $address = (set_value('address') ? set_value('address') : (isset($data['address']) ? $data['address'] : '')); ?>
                                                    <label class="control-label">Address</label>
                                                    <textarea class="form-control" id="address" name="address" rows="5" placeholder="Enter text ..."><?php echo $address; ?></textarea>
                                                    <?php echo form_error('address'); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group <?php echo $err_user_group_id; ?>">
                                                    <?php $user_group_id = (set_value('user_group_id') ? set_value('user_group_id') : (isset($data['user_group_id']) ? $data['user_group_id'] : '')); ?>
                                                    <label class="control-label">User Group</label>
                                                    <select name="user_group_id" class="form-control">
                                                        <!-- <option disabled="disabled" value="" selected>Pilih User Group</option> -->
                                                        <?php foreach ($user_group_list as $key => $dt) { ?>
                                                            <option <?php echo ($user_group_id == $dt["user_group_id"]) ? 'selected' : ''; ?> value="<?php echo $dt["user_group_id"]; ?>"><?php echo $dt["name"]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <?php echo form_error('user_group_id'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/row-->
                                    </div>
                                    <div class="form-actions">
                                        <button type="submit" name="btnSubmit" id="btnSubmit" value="save" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
                                        <button type="button" class="btn btn-inverse" onClick="location.href='<?php echo base_url() . "user"; ?>'">Cancel</button>
                                    </div>
                                    <?php
                                    if (isset($data['user_id'])) {
                                    ?>
                                        <input type="hidden" name="user_id" value="<?php echo $data["user_id"]; ?>">
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
            <footer class="footer"> © <?= date('Y') ?> Recook Admin </footer>
        </div>
    </div>
    <?php $this->load->view("partial/v_script_bottom"); ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/html5-editor/bootstrap-wysihtml5.css" />
    <!-- wysuhtml5 Plugin JavaScript -->
    <script src="<?php echo base_url(); ?>plugins/html5-editor/wysihtml5-0.3.0.js"></script>
    <script src="<?php echo base_url(); ?>plugins/html5-editor/bootstrap-wysihtml5.js"></script>
    <script>
        $(document).ready(function() {

            $('.textarea_editor').wysihtml5();


        });
    </script>
</body>

</html>