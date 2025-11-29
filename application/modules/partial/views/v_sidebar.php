<!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">                
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                    <?php
                    if (count($menu_privillege) > 0) {
                        foreach ($menu_privillege as $keyMenu=>$menu) { ?>
                            <li> 
                                <a <?php if(!empty($menu['subs'])) {?> class="has-arrow" <?php } ?>  href="<?php echo base_url().$menu['module_name']; ?>" aria-expanded="false">
                                    <i class="<?php echo $menu['icon']; ?>"></i><span class="hide-menu"><?php echo $menu['name']; ?> </span>
                                </a>
                                <?php if (!empty($menu['subs'])) {
                                    foreach ($menu['subs'] as $submenu) {
                                ?>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="<?php echo base_url().$submenu['module_name']; ?>"><?php echo $submenu['name']; ?></a></li>
                                </ul>
                                <?php } ?>
                            </li>
                        <?php }
                        }
                    }
                    ?>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->