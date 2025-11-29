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
							foreach ($menu_privillege as $keyMenu=>$menu) {
								if (count($menu['subs']) > 0) {
									echo "<li class='treeview'>";
									echo "<a href='#'><i class='fa ".$menu['icon']."''></i> <span>".$menu['name']."</span>";
									echo "</a>";
									
									echo "<ul class='treeview-menu'>";
									foreach ($menu['subs'] as $keySub=>$sub) {
										echo "<li><a href='".base_url().$sub['module_name']."'><i class='fa ".$sub['icon']."''></i> ".$sub['name']."</a></li>";
									}
									echo "</ul>";
									
									echo "</li>";
								}else{
									echo "<li>";
									echo "<a href='".base_url().$menu['module_name']."'><i class='fa ".$menu['icon']."'></i> <span>".$menu['name']."</span></a>";
									echo "</li>";	
								}
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