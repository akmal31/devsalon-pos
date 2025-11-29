<!doctype html>
<html lang="en">
    <?php $this->load->view("partial/v_html_header"); ?>
<body>
    <?php $this->load->view("partial/v_loader"); ?>
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="pageTitle">
            <img src="<?php echo base_url(); ?>assets/img/loading-icon.png" alt="logo" class="logo">
        </div>
    </div>
    <!-- * App Header -->

    <!-- App Capsule -->
    <div id="appCapsule">

        <div class="section mt-3 text-center">
            <div class="avatar-section">
                <a href="#">
                    <ion-icon name="logo-octocat" style="font-size: 100px;"></ion-icon>
                </a>
            </div>
            <br>
                <h4><?=$user_profile['USERNAME'];?></h4>
                <span><?=$user_group['name'];?></span>
        </div>

        <div class="listview-title mt-1">Theme</div>
        <ul class="listview image-listview text inset no-line">
            <li>
                <div class="item">
                    <div class="in">
                        <div>
                            Dark Mode
                        </div>
                        <div class="form-check form-switch  ms-2">
                            <input class="form-check-input dark-mode-switch" type="checkbox" id="darkmodeSwitch">
                            <label class="form-check-label" for="darkmodeSwitch"></label>
                        </div>
                    </div>
                </div>
            </li>
        </ul>

        <div class="listview-title mt-1">Services</div>
        <ul class="listview image-listview text inset">
            <li>
                <a href="<?php echo base_url(); ?>services" class="item">
                    <div class="in">
                        <div>List Perawatan</div>
                    </div>
                    <!-- <div class="form-check form-switch  ms-2">
                        <span class="badge badge-warning">Under Maintenance</span>
                    </div> -->
                </a>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>services/package" class="item">
                    <div class="in">
                        <div>List Paket</div>
                    </div>
                </a>
            </li>
        </ul>

        <div class="listview-title mt-1">Account</div>
        <ul class="listview image-listview text mb-2 inset">
            <!-- <li>
                <a href="#" class="item">
                    <div class="in">
                        <div>Update Password</div>
                    </div>
                </a>
            </li> -->
            <li>
                <a href="" class="item" data-bs-toggle="modal" data-bs-target="#DialogBasic">
                    <div class="in">
                        <div>Log out</div>
                    </div>
                </a>
            </li>
        </ul>
        <!-- Dialog Basic -->
        <div class="modal fade dialogbox" id="DialogBasic" data-bs-backdrop="static" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Logout</h5>
                    </div>
                    <div class="modal-body">
                        Kamu yakin mau logout?
                    </div>
                    <div class="modal-footer">
                        <div class="btn-inline">
                            <a href="#" class="btn btn-text-secondary" data-bs-dismiss="modal">Ga jadi</a>
                            <a href="<?=base_url().'auth/logout';?>" class="btn btn-text-primary">Yakin</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- * Dialog Basic -->


    </div>
    <!-- * App Capsule -->

    <!-- App Bottom Menu -->
    <?php $this->load->view("partial/v_menu_bottom"); ?>
    <!-- * App Bottom Menu -->

    <?php $this->load->view("partial/v_script_bottom"); ?>

</body>

</html>