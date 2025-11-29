<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo base_url(); ?>styles/bootstrap/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url(); ?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url(); ?>plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>styles/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url(); ?>styles/js/demo.js"></script>
<!-- page script -->
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