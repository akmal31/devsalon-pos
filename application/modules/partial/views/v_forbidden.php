<!DOCTYPE html>
<html lang="en">

<?php $this->load->view("partial/v_html_header"); ?>

<body class="fix-header fix-sidebar card-no-border">
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <div id="main-wrapper">
		<?php // $this->load->view("partial/v_header", $user_profile); ?>
        <?php $this->load->view("partial/v_sidebar"); ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row page-titles">
                </div>
                <!-- Row -->
                <div class="row">
					<div class="card col-md-12">
						<div class="card-body">
							<div class="text-center m-t-30"> 
								<img src="<?php echo base_url()."assets/forbidden.png"; ?>" width=500px>
							</div>
						</div>
					</div>
				</div>
            </div>
            <footer class="footer">Dev salon © <?= date('Y')?> Admin </footer>
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
	function display_c(){
		var refresh=1000; // Refresh rate in milli seconds
		mytime=setTimeout('display_ct()',refresh)
	}
	function display_ct() {
		var x = new Date();
		var mon = x.getMonth();
		var mons = new Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		var d = x.getDate();
        var day = x.getDay();
        var days = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
		var h = x.getHours();
		if(h<10)
        {
            h = "0"+h;
        }
        m = x.getMinutes();
        if(m<10)
        {
            m = "0"+m;
        }
        s = x.getSeconds();
        if(s<10)
        {
            s = "0"+s;
        }
		var x1 = days[day] + ", " + x.getDate() + " " + mons[mon] + " " + x.getFullYear(); 
		x1 = x1 + " - " +  h + ":" +  m + ":" + s + " WIB";
		document.getElementById('ct').innerHTML = x1;
		display_c();
	}
	function confirm_del(id){

		alertify.confirm("Apakah anda ingin menghapus social media ini?", function (e) {
			if (e) {
				var url = '<?php echo SITE_URI. "homepage/del_social/";?>'+id;
				location.href=url;
			}
		});
		return false;
	}
	
	function confirm_del_slider(id){

		alertify.confirm("Apakah anda ingin menghapus Slider ini?", function (e) {
			if (e) {
				var url = '<?php echo SITE_URI. "homepage/del_slider/";?>'+id;
				location.href=url;
			}
		});
		return false;
	}
	
	function confirm_del_banner(id){

		alertify.confirm("Apakah anda ingin menghapus Iklan Banner ini?", function (e) {
			if (e) {
				var url = '<?php echo SITE_URI. "homepage/del_banner/";?>'+id;
				location.href=url;
			}
		});
		return false;
	}
</script>
</html>