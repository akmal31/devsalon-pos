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
                        <h3 class="text-themecolor">Import Employee</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo base_url() ?>homepage">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo base_url() ?>user">Employee</a></li>
                            <li class="breadcrumb-item active">Import Employee</li>
                        </ol>
                    </div>
                </div>
                <div class="alert alert-success" id="alert_success" name="alert_success"> <i class="mdi mdi-comment-check"></i> Import employee sedang dalam proses, tunggu beberapa saat lalu cek kembali di list employee untuk melihat hasilnya. Terima kasih.<br> <a href="<?php echo base_url() ?>user">Klik disini untuk kembali ke list employee</a>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 p-10">
                                <a href="<?php echo base_url(); ?>user/download_template" type="button" _target="blank" class="btn btn-info">Download Sample File</a>
                                <hr>
                                <div class="card p-10">
                                    <form method="POST" enctype="multipart/form-data" action="<?php echo base_url(); ?>user/export_employee">
                                        <div id="form-group" class="form-group">
                                            <label for="recipient-name" class="control-label">Import Employee</label>
                                            <input type="file" class="form-control" id="employee" name="employee" required>
                                            <span id="warningempty" class="text-danger" style="display:none;">File tidak boleh kosong</span>
                                        </div>
                                        <div class="form-group" id="loadingbar" name="loadingbar" style="text-align:center;display:none;">
                                            <img src="<?php echo base_url(); ?>assets/loading3.gif" width="50%"/>
                                        </div>
                                        <div id="form-group" class="form-group">
                                            <a type="button" onclick="valImportEmployee()" name="btnSubmitCheck" id="btnSubmitCheck" class="btn btn-success text-white">Check File</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-12 p-20" id="failed_output_area" name="failed_output_area">
                                <hr>
                                <h4>Output</h4> 
                                <div class="card p-10" id="output">
                                    <p class="text-danger">Terdapat kesalahan dalam file yang anda inputkan, silahkan cek kembali file tersebut</p>
                                    <h4>Detail</h4> 
                                    <div id="form-group" class="form-group">
                                        <textarea rows="10" class="form-control" id="error_output" name="error_output" readonly></textarea>
                                    </div>
                                </div>
                                <hr>
                            </div>
                            <div class="col-md-12 p-20" id="success_output_area" name="success_output_area">
                                <hr>
                                <h4>Output</h4> 
                                <div class="card p-10" id="output">
                                    <p class="text-info">Tidak ada kesalahan dalam file yang anda inputkan, silahkan <b>klik tombol process</b> dibawah ini untuk melanjutkan proses import employee</p>
                                    <div class="form-group" id="loadingbar_import" name="loadingbar_import" style="text-align:center;display:none;">
                                        <img src="<?php echo base_url(); ?>assets/loading3.gif" width="25%"/>
                                    </div>
                                    <div id="form-group" class="form-group">
                                        <a onclick="ImportEmployee()" type="button" class="btn btn-success text-white"> <i class="fa fa-check"></i> Process</a>
                                    </div>
                                </div>
                                <hr>
                            </div>
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
	if($this->session->flashdata('success')){
		echo "<script type='text/javascript'>alertify.alert('".$this->session->flashdata('success')."');</script>";
        $this->session->unset_userdata('success');
	}
?>
<script src="<?php echo base_url(); ?>plugins/moment/moment.js"></script>
<script src="<?php echo base_url(); ?>plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
<script>
    if (/mobile/i.test(navigator.userAgent)) {
        $('input').prop('readOnly', true);
    }
    $("#success_output_area").hide();
    $("#failed_output_area").hide();
    $("#alert_success").hide();
    $("#loadingbar_import").hide();
    </script>

<script type="text/javascript">
     function valImportEmployee() {
            $("#loadingbar").show();
            $("#btnSubmitCheck").hide();
            var file_data = $('#employee').prop('files')[0];   
            
            if(file_data==undefined){
                $("#warningempty").show();
                $("#loadingbar").hide();
                $("#btnSubmitCheck").show();
            }
            const fileupload = $('#employee').prop('files')[0];
  
            let formData = new FormData();
            formData.append('employee', fileupload);
            $("#error_output").text("");
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() . 'user/import_employee_val' ?>",
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                success: function (data) {
                    const feedback = JSON.parse(data);
                    if(feedback.total_data == 0){
                        $("#btnSubmitCheck").show();
                        $("#error_output").text("No data input, please check the file");
                        $("#loadingbar").hide();
                        $("#failed_output_area").show();
                        $("#success_output_area").hide();
                    } else if($.isEmptyObject(feedback.failed_list)){
                        $("#btnSubmitCheck").show();
                        $("#loadingbar").hide();
                        $("#failed_output_area").hide();
                        $("#success_output_area").show();
                    }else{
                        var blkstr = [];
                        $.each(feedback.failed_list, function(idx,val) {
                            $.each(val, function(idx2,val2) {
                                if (val2?.validation_error) {
                                    const error_message = (val2?.validation_error ?? []).map((e) => `${e.field.replace("ImportField.", "")} [Expected: ${e.value}]`).join(", ");
                                    blkstr.push(`Employee Name: ${val2?.data?.fullname ?? "~"} with ID: ${val2?.data?.employee_id ?? "~"} - ${idx2} - ${error_message}`);
                                } else if (val2?.manager_name) {
                                    blkstr.push(`${idx2} - Manager Name: ${val2?.manager_name} - Manager NIK: ${val2?.manager_nik}`);
                                } else {
                                    blkstr.push(`Unknown Error`);
                                }
                            })
                        });

                        $("#btnSubmitCheck").show();
                        $("#error_output").text(blkstr.join("\r\n"));
                        $("#loadingbar").hide();
                        $("#failed_output_area").show();
                        $("#success_output_area").hide();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $("#btnSubmitCheck").show();
                    $("#error_output").text("Something when wrong, please try again");
                    $("#loadingbar").hide();
                    $("#failed_output_area").show();
                    $("#success_output_area").hide();
                }
            });
     }
     function ImportEmployee() {
            $("#loadingbar_import").show();
            $("#btnSubmitCheck").hide();
            var file_data = $('#employee').prop('files')[0];   
            
            if(file_data==undefined){
                $("#warningempty").show();
                $("#loadingbar_import").hide();
                $("#btnSubmitCheck").show();
            }
            const fileupload = $('#employee').prop('files')[0];
  
            let formData = new FormData();
            formData.append('employee', fileupload);

            $("#error_output").text("");
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() . 'user/import_employee_confirm' ?>",
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                success: function (data) {
                    const feedback = JSON.parse(data);
                    $("#loadingbar_import").hide();
                    $("#alert_success").show();
                    $("#success_output_area").hide();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $("#btnSubmitCheck").show();
                    $("#error_output").text("Something when wrong, please try again");
                    $("#loadingbar_import").hide();
                    $("#failed_output_area").show();
                    $("#success_output_area").hide();
                }
            });
     }
</script>

</html>