<!doctype html>
<html lang="en">
    <?php $this->load->view("partial/v_header_html"); ?>
<body>
    <?php $this->load->view("partial/v_loader"); ?>
    <!-- App Header -->
    <div class="appHeader no-border transparent position-absolute">
        <div class="pageTitle"></div>
        <div class="right">
        </div>
    </div>
    <!-- * App Header -->

    <!-- App Capsule -->
    <div id="appCapsule">
        <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <?= $this->session->flashdata('error'); ?>
        </div>
        <?php endif; ?>

        <div class="section mt-2 text-center">
            <img src="<?php echo base_url(); ?>assets/img/logo.png" alt="icon" style="width:250px;">
            <h2 style="color: #E06EA3;">Login Page</h2>
        </div>
        <div class="section mb-5 p-2">

            <form class="form-horizontal form-material" id="loginform" action="<?=base_url()?>auth" method="post">
                <div class="card">
                    <div class="card-body pb-1">
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="email1">E-mail / Nama / Phone</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Your ID" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">

                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="password1">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password" id="password" autocomplete="off" placeholder="Your password">
                                    <span class="input-group-text toggle-password" onclick="togglePassword()" style="cursor:pointer;">
                                        <ion-icon id="eyeIcon" name="eye-outline"></ion-icon>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group basic">
                            <p style="color:red"><?php echo (isset($msg) ? $msg : '');?></p>
                        </div>
                    </div>
                </div>
                <div class="form-button-group  transparent">
                    <button type="submit" class="btn btn-primary btn-block btn-lg">Log in</button>
                </div>

            </form>
        </div>

    </div>
    <!-- * App Capsule -->

    <?php $this->load->view("partial/v_script_bottom"); ?>

</body>

</html>