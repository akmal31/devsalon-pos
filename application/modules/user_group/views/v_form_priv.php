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
                            <li class="breadcrumb-item active">Setting Privillege</li>
                        </ol>
                    </div>
                </div>
				<div class="row">
				<div class="col-lg-12">
					<div class="box">
						<?php 
						$attributes = array('class' => 'form-horizontal', 'id' => 'frmUser', 'name' => 'frmUserGroup');
						echo form_open(base_url().'user_group/save_priv', $attributes); 
						?>
							<input type="hidden" name="user_group_id" id="user_group_id" value="<?php echo $user_group_id;?>" />
							<div class="box-header with-border">
								<h3 class="box-title">Setting Privillege</h3>
							</div>
							<div class="box-body">
								<table class="table table-bordered">
									<tr >
										<th style="width: 20px">No.</th>
										<th>Menu</th>
										<th style="width: 15px;text-align:center;">#</th>
										<th style="width: 30px;text-align:center;">View</th>
										<th style="width: 30px;text-align:center;">Insert</th>
										<th style="width: 30px;text-align:center;">Update</th>
										<th style="width: 30px;text-align:center;">Delete</th>
										<th style="width: 30px;text-align:center;">Rate/Coverage</th>
										<th style="width: 30px;text-align:center;">Active</th>
										<th style="width: 30px;text-align:center;">Detail</th>
									</tr>
									<?php
									if (count($list) > 0) {
										$i = 1;
										foreach ($list as $key=>$dt) {
											if ($dt['is_view']==1 && $dt['is_insert']==1 && $dt['is_update']==1 && $dt['is_delete']==1 && $dt['is_rate_coverage']==1 && $dt['is_active']==1 && $dt['is_detail']==1) {
												$checkAll = "checked='checked'";
											}
											else {
												$checkAll = "";
											}
											
											if ($dt['is_view'] == 1) {
												$checkView = "checked='checked'";
											}
											else {
												$checkView = "";
											}
											
											if ($dt['is_insert'] == 1) {
												$checkInsert = "checked='checked'";
											}
											else {
												$checkInsert = "";
											}
											
											if ($dt['is_update'] == 1) {
												$checkUpdate = "checked='checked'";
											}
											else {
												$checkUpdate = "";
											}
											
											if ($dt['is_delete'] == 1) {
												$checkDelete = "checked='checked'";
											}
											else {
												$checkDelete = "";
											}
											
											if ($dt['is_rate_coverage'] == 1) {
												$checkRateCoverage = "checked='checked'";
											}
											else {
												$checkRateCoverage = "";
											}
											
											if ($dt['is_active'] == 1) {
												$checkActive = "checked='checked'";
											}
											else {
												$checkActive = "";
											}
											
											if ($dt['is_detail'] == 1) {
												$checkDetail = "checked='checked'";
											} 
											else {
												$checkDetail = "";
											}
											//Not Reporting
											if($dt['parent_id'] != 58){
												echo "<tr>";
													echo "<td>".$i."</td>";
													echo "<td>".$dt['parent_name']. " > " .$dt['menu_name']."</td>";
													echo "<td style='text-align:center;'><input type='checkbox' style='left:635px;opacity:100' name='all_".$user_group_id."_".$key."' id='all_".$user_group_id."_".$key."' $checkAll /></td>";
													echo "<td style='text-align:center;'><input type='checkbox' style='left:680px;opacity:100' name='is_view_".$user_group_id."_".$key."' id='is_view_".$user_group_id."_".$key."' $checkView /></td>";
													echo "<td style='text-align:center;'><input type='checkbox' style='left:740px;opacity:100' name='is_insert_".$user_group_id."_".$key."' id='is_insert_".$user_group_id."_".$key."' $checkInsert /></td>";
													echo "<td style='text-align:center;'><input type='checkbox' style='left:810px;opacity:100' name='is_update_".$user_group_id."_".$key."' id='is_update_".$user_group_id."_".$key."' $checkUpdate /></td>";
													echo "<td style='text-align:center;'><input type='checkbox' style='left:885px;opacity:100' name='is_delete_".$user_group_id."_".$key."' id='is_delete_".$user_group_id."_".$key."' $checkDelete /></td>";
													echo "<td style='text-align:center;'><input type='checkbox' style='left:980px;opacity:100' name='is_rate_coverage_".$user_group_id."_".$key."' id='is_rate_coverage_".$user_group_id."_".$key."' $checkRateCoverage /></td>";
													echo "<td style='text-align:center;'><input type='checkbox' style='left:1100px;opacity:100' name='is_active_".$user_group_id."_".$key."' id='is_active_".$user_group_id."_".$key."' $checkActive /></td>";
													echo "<td style='text-align:center;'><input type='checkbox' style='left:1165px;opacity:100' name='is_detail_".$user_group_id."_".$key."' id='is_detail_".$user_group_id."_".$key."' $checkDetail /></td>";
												echo "</tr>";											
											}//Reporting
											else{
												echo "<tr><td colspan='10' style='padding:1px;'>&nbsp;</td></tr>";
												echo '<tr>
														<th style="width: 20px">&nbsp;</th>
														<th>&nbsp;</th>
														<th style="width: 15px">#</th>
														<th style="width: 30px;text-align:center;">View</th>
														<th style="width: 30px;text-align:center;">Survey</th>
														<th style="width: 30px;text-align:center;">Transfer</th>
														<th style="width: 30px;text-align:center;">Document</th>
														<th style="width: 30px;text-align:center;">Active System</th>
														<th style="width: 30px;text-align:center;">Detail</th>		
														<th style="width: 30px">&nbsp;</th>
													 </tr>';
												echo '<tr>';
												echo "<td style='text-align:center;'>".$i."</td>";
												echo "<td>".$dt['parent_name']. " > " .$dt['menu_name']."</td>";
												echo "<td style='text-align:center;'><input type='checkbox' name='all_".$user_group_id."_".$key."' id='all_".$user_group_id."_".$key."' $checkAll /></td>";
												echo "<td style='text-align:center;'><input type='checkbox' name='is_view_".$user_group_id."_".$key."' id='is_view_".$user_group_id."_".$key."' $checkView /></td>";
												echo "<td style='text-align:center;'><input type='checkbox' name='is_insert_".$user_group_id."_".$key."' id='is_insert_".$user_group_id."_".$key."' $checkInsert /></td>";
												echo "<td style='text-align:center;'><input type='checkbox' name='is_update_".$user_group_id."_".$key."' id='is_update_".$user_group_id."_".$key."' $checkUpdate /></td>";
												echo "<td style='text-align:center;'><input type='checkbox' name='is_rate_coverage_".$user_group_id."_".$key."' id='is_rate_coverage_".$user_group_id."_".$key."' $checkRateCoverage /></td>";
												echo "<td style='text-align:center;'><input type='checkbox' name='is_active_".$user_group_id."_".$key."' id='is_active_".$user_group_id."_".$key."' $checkActive /></td>";
												echo "<td style='text-align:center;'><input type='checkbox' name='is_detail_".$user_group_id."_".$key."' id='is_detail_".$user_group_id."_".$key."' $checkDetail /></td>";
												echo "<td style='text-align:center;'><input type='hidden' name='is_delete_".$user_group_id."_".$key."' id='is_delete_".$user_group_id."_".$key."' value='' /></td>"; 
											}
											
											$i++;
										}
									}
									else {
										echo "<tr><td colspan='10'>Tidak ada data</td></tr>";
									}
									?>
								</table>
							</div>
							<div class="box-footer">
								<button name="btnSubmit" id="btnSubmit" value="cancel" type="submit" class="btn btn-default">Cancel</button>
								<button name="btnSubmit" id="btnSubmit" value="save" type="submit" class="btn btn-info pull-right">Save</button>
							</div>
						<?php form_close(); ?>
					</div>
				</div>
			</div>	
            <footer class="footer"> © 2022 Recook Admin </footer>
        </div>
    </div>
	<?php $this->load->view("partial/v_script_bottom"); ?>
</body>
<script>
	$(document).ready(function() {
	<?php
	if (count($list) > 0) {
		foreach ($list as $keyMn=>$mn) {
		?>
		$('#all_<?php echo $user_group_id;?>_<?php echo $keyMn;?>').click(function(event) {  
			if(this.checked) { 
				document.getElementById('is_view_<?php echo $user_group_id;?>_<?php echo $keyMn;?>').checked=true;
				document.getElementById('is_insert_<?php echo $user_group_id;?>_<?php echo $keyMn;?>').checked=true;
				document.getElementById('is_update_<?php echo $user_group_id;?>_<?php echo $keyMn;?>').checked=true;
				<?php //Not Reporting
				if($keyMn != 51){ ?>
					document.getElementById('is_delete_<?php echo $user_group_id;?>_<?php echo $keyMn;?>').checked=true;					
				<?php } ?>
				document.getElementById('is_rate_coverage_<?php echo $user_group_id;?>_<?php echo $keyMn;?>').checked=true;
				document.getElementById('is_active_<?php echo $user_group_id;?>_<?php echo $keyMn;?>').checked=true;
				document.getElementById('is_detail_<?php echo $user_group_id;?>_<?php echo $keyMn;?>').checked=true;
			}else{	
				document.getElementById('is_view_<?php echo $user_group_id;?>_<?php echo $keyMn;?>').checked=false;
				document.getElementById('is_insert_<?php echo $user_group_id;?>_<?php echo $keyMn;?>').checked=false;
				document.getElementById('is_update_<?php echo $user_group_id;?>_<?php echo $keyMn;?>').checked=false;
				<?php //Not Reporting
				if($keyMn != 51){ ?>
					document.getElementById('is_delete_<?php echo $user_group_id;?>_<?php echo $keyMn;?>').checked=false;					
				<?php } ?>
				document.getElementById('is_rate_coverage_<?php echo $user_group_id;?>_<?php echo $keyMn;?>').checked=false;	
				document.getElementById('is_active_<?php echo $user_group_id;?>_<?php echo $keyMn;?>').checked=false;
				document.getElementById('is_detail_<?php echo $user_group_id;?>_<?php echo $keyMn;?>').checked=false;
			}
		});
	<?php
		}
	}
	?>
	});
</script>
</html>