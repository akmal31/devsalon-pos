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
                        <h3 class="text-themecolor">User Group</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                            <li class="breadcrumb-item active">User Group</li>
                        </ol>
                    </div>
                </div>
				<div class="card">
					<div class="card-body">
						<a href='<?php echo base_url();?>user_group/add'><i class="fa fa-plus"></i></a>&nbsp;&nbsp;<a href='<?php echo base_url();?>user_group/add'>Add</a>
							<div class="table-responsive m-t-40">
								<table id="listUserGroup" class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>Group Name</th>
											<th>Description</th>
											<th>Active</th>
											<th>User Inserted</th>
											<th>Date Inserted</th>
											<th>User Updated</th>
											<th>Date Updated</th>
											<th>#</th>
										</tr>
									</thead>
									<tbody>
									<?php
									if (count($list) > 0) {
										foreach ($list as $key=>$dt) {
											echo "<tr>";
												echo "<td>".$dt['name']."</td>";
												echo "<td>".$dt['description']."</td>";
												echo "<td>".($dt['active']==1 ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">Not Active</span>')."</td>";
												echo "<td>".$dt['username_inserted']."</td>";
												echo "<td>".$dt['date_inserted']."</td>";
												echo "<td>".($dt['username_updated']!='' ? $dt['username_updated'] : '-')."</td>";
												echo "<td>".($dt['date_updated']!='0000-00-00 00:00:00' && $dt['date_updated']!=null ? $dt['date_updated'] : '-')."</td>";
												echo "<td>";
												echo "<a href='".base_url()."user_group/edit/".$dt['user_group_id']."'><i class='fa fa-edit'></i></a>&nbsp;&nbsp;";
												echo "<a href='".base_url()."user_group/priv/".$dt['user_group_id']."'><i class='fa fa-gears'></i></a>&nbsp;&nbsp;";
												echo "<a href='".base_url()."user_group/delete/".$dt['user_group_id']."'><i class='fa fa-close'></i></a>";
												echo "</td>";
											echo "</tr>";
										}
									}
									else {
										echo "<tr><td colspan='8'>Tidak ada data</td></tr>";
									}
									?>
									</tbody>
								</table>
							</div>
					</div>
				</div>	
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

		alertify.confirm("Apakah anda ingin menghapus Pengajar ini?", function (e) {
			if (e) {
				var url = '<?php echo SITE_URI. "pengajar/del/";?>'+id;
				location.href=url;
			}
		});
		return false;
	}
</script>
</html>