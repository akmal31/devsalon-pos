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
                        <h3 class="text-themecolor">Employee</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>homepage">Home</a></li>
                            <li class="breadcrumb-item active">Employee</li>
                        </ol>
                    </div>
					<div class="col-md-7 col-4 align-self-center">
                        <div class="d-flex m-t-10 justify-content-end">
						<button type="button" class="btn btn-outline-success" data-container="body" title="" data-toggle="popover" data-placement="bottom" 
							data-content="Di Menu ini terdapat list karyawan dengan jabatan dan penempatannya." data-original-title="Data Karyawan">
							Menu Information
						</button>
                        </div>
                    </div>
                </div>
                <!-- Row -->
				<!-- Error or Success Message -->
				<?php $message = $this->session->flashdata('success');
                if (isset($message)) { ?>
                    <div class="alert alert-success"> <i class="mdi mdi-comment-check"></i> <?=$message?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                    </div>
                <?php $this->session->unset_userdata('success'); } ?>
				<?php $message = $this->session->flashdata('warning');
                if (isset($message)) { ?>
                    <div class="alert alert-warning"> <i class="fa fa-exclamation-triangle"></i> <?=$message?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                    </div>
                <?php $this->session->unset_userdata('warning'); } ?>
                <?php $message = $this->session->flashdata('failed');
                if (isset($message)) { ?>
                    <div class="alert alert-danger"> <i class="mdi mdi-comment-remove-outline"></i> <?=$message?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                    </div>
                <?php $this->session->unset_userdata('failed'); } ?>
				<!-- End of Error or Success Message -->
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-md-3 col-xs-6 b-r"> 
								<a href='<?php echo base_url(); ?>user/import_employee' type="button" class="btn btn-success"><i class="fa fa-plus"></i> Import Employee</a>
								<a href='' data-toggle="modal" data-target="#ImportApproval" type="button" class="btn btn-info"><i class="fa fa-check"></i> Import Approval Line</a>
							</div>
							<div class="col-md-3 col-xs-6 b-r"></div>
							<div class="col-md-3 col-xs-6 b-r"></div>
							<div class="col-md-3 col-xs-6 b-r">
							<p><strong>Total User : </strong><span class="label label-info"><?=$alldata?></span></p>
							<p><strong>Total User Active : </strong><span class="label label-primary"><?=$activedata?></span></p>
							</div>
						</div>
					</div>
				</div>
				<div class="modal fade" id="ImportEmployee" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form method="POST" enctype="multipart/form-data" action="<?php echo base_url(); ?>user/import_user">                                    
								<div class="modal-header">
									<h4 class="modal-title" id="exampleModalLabel3">Input Employee</h4>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								</div>
								<div class="modal-body">
									<div id="form-group" class="form-group">
										<label for="recipient-name" class="control-label">Input File</label>
										<input type="file" class="form-control" id="userfile" name="userfile">
 									</div>
									<div id="form-group" class="form-group">
										<label for="recipient-name" class="control-label"><a target="blank" href="https://docs.google.com/spreadsheets/d/1QPD_GEt_25O074S4Po6WV5GFonLkwiCE/edit#gid=1424042531">Download Sample File</a></label>
 									</div>
								</div>
								<div class="modal-footer">
									<button type="submit" name="btnSubmit" id="btnSubmit" value="save" class="btn btn-success"> <i class="fa fa-check"></i> Process</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="modal fade" id="ImportApproval" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form method="POST" enctype="multipart/form-data" action="<?php echo base_url(); ?>user/import_approval">                                    
								<div class="modal-header">
									<h4 class="modal-title" id="exampleModalLabel3">Input Approval Line</h4>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								</div>
								<div class="modal-body">
									<div id="form-group" class="form-group">
										<label for="recipient-name" class="control-label">Input File</label>
										<input type="file" class="form-control" id="approval" name="approval">
									</div>
									<div id="form-group" class="form-group">
										<label for="recipient-name" class="control-label"><a target="blank" href="https://docs.google.com/spreadsheets/d/1ley6cS4clHGT3KOZEsN6Et0n6a8eJt_f">Download Sample File</a></label>
 									</div>
								</div>
								<div class="modal-footer">
									<button type="submit" name="btnSubmit" id="btnSubmit" value="save" class="btn btn-success"> <i class="fa fa-check"></i> Process</button>
								</div>
							</form>
						</div>
					</div>
				</div>
                <div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="table-query" class="table table-bordered table-striped" style="width: 100%;">
								<thead>
									<tr>
										<th style="width: 30px;">No.</th>
										<th style="width: 150px;">Nama</th>
										<th style="width: 70px;">Email</th>
										<th style="width: 70px;">Jabatan</th>
										<th style="width: 70px;">Level</th>
										<th style="width: 100px;">Penempatan</th>
										<th style="width: 100px;">Action</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
            </div>
            <footer class="footer"> © <?echo date("Y");?> SGR Patrol Admin </footer>
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
	
	function make_pass(length,id) {
		var result = ''
		var characters       = 'abcdefghijklmnopqrstuvwxyz0123456789';
		var charactersLength = characters.length;
		for ( var i = 0; i < length; i++ ) {
		  result += characters.charAt(Math.floor(Math.random() * charactersLength));
	   }
	   element = 'new_password'+id
	   document.getElementById(element).value= result;
	}
	
	function make_code(length, id, nama) {
		var nama_text = nama.toUpperCase();
		var result = nama_text;
		var characters = '0123456789';
		var charactersLength = characters.length;
		for (var i = 0; i < length; i++) {
			result += characters.charAt(Math.floor(Math.random() * charactersLength));
		}
		element = 'referal_code' + id;
		document.getElementById(element).value = result;
	}
	
	function showDiv(element,id){
		element_code = 'code'+id;
		element_value = 'value'+id;
		element_komisi = 'komisi'+id;
		document.getElementById(element_code).style.display = element.value == 1 ? 'block' : 'none';
		document.getElementById(element_value).style.display = element.value == 1 ? 'block' : 'none';
		document.getElementById(element_komisi).style.display = element.value == 1 ? 'block' : 'none';
	}
	function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
	}
</script>

<script>
    var tabel = null;
    $(document).ready(function() {
        tabel = $('#table-query').DataTable({
            "processing": true,
            "responsive":true,
            "serverSide": true,
            "ordering": true, // Set true agar bisa di sorting
            "order": [[ 0, 'asc' ]], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
            "ajax":
            {
                "url": "<?= base_url('user/view_data_query');?>", // URL file untuk proses select datanya
                "type": "POST"
            },
            "deferRender": true,
            "aLengthMenu": [[25, 50, 100],[ 25, 50, 100]], // Combobox Limit
            "columns": [
                {"data": 'id',"sortable": false, 
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }  
                },
                { "data": "name" },
                { "data": "email" },
				{ "data": "position_name" },
				{ "data": "position_level_name" },
				{ "data": "location_name" },
				{ "data": "id",
				"render":
				function(data, type, row, meta) {
					var fname = row.name;
					var fname = fname.split(" ");
					var fname = fname[0];
					var 
					action  = "<a title='Detail User' href='<?=base_url();?>user/detail/"+row.emp_id+"'><i class='mdi mdi-account-card-details'></i> Detail</a>&nbsp;&nbsp;";
					return action;
                }
				},
            ],
			
        });
    });
</script>
</html>