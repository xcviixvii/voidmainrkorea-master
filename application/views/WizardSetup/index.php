<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<link rel="apple-touch-icon" sizes="76x76" href="assets/img/favicon.ico">

	<title>Voidmain License</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

	<link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png" />
	<link rel="shortcut icon" type="image/x-icon" href="<?=base_url()?>favicon.png">

	<!--     Fonts and icons     -->
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/MaterialFont.css" />
	<link href="<?=base_url()?>Library/lib/fontawesome-free/css/all.min.css" rel="stylesheet">

	<!-- CSS Files -->
	<link href="<?=base_url()?>assets/css/bootstrap.min.css" rel="stylesheet" />
	<link href="<?=base_url()?>assets/css/material-bootstrap-wizard.css" rel="stylesheet" />

	<!-- CSS Just for demo purpose, don't include it in your project -->
	<link href="<?=base_url()?>assets/css/demo.css" rel="stylesheet" />
	<style>
		.wizard-card .favpicture {
		width: 106px;
		height: 106px;
		background-color: #999999;
		border: 4px solid #CCCCCC;
		color: #FFFFFF;
		margin: 5px auto;
		overflow: hidden;
		transition: all 0.2s;
		-webkit-transition: all 0.2s;
		}
	</style>
</head>

<body>
	<div class="image-container set-full-height" style="background-image: url('assets/img/wizard-profile.jpg')">



	    <!--   Big container   -->
	    <div class="container">
	        <div class="row">
		        <div class="col-sm-8 col-sm-offset-2">
		            <!--      Wizard container        -->
		            <div class="wizard-container">
		                <div class="card wizard-card" data-color="blue" id="wizardProfile">
		                    <form action="" method="POST" id="frmSiteLicense" enctype="multipart/form-data">
		                <!--        You can switch " data-color="purple" "  with one of the next bright colors: "green", "orange", "red", "blue"       -->

		                    	<div class="wizard-header">
		                        	<h3 class="wizard-title">
		                        	   Build Your Profile
		                        	</h3>
									<!-- <h5>This information will let us know more about you.</h5> -->
		                    	</div>
								<div class="wizard-navigation">
									<ul>
			                            <li><a href="#about" data-toggle="tab">About</a></li>
			                            <li><a href="#account" data-toggle="tab">Account</a></li>
			                            <li><a href="#license" data-toggle="tab">License</a></li>
			                        </ul>
								</div>

		                        <div class="tab-content">
		                            <div class="tab-pane" id="about">
		                              <div class="row">
		                                	<h4 class="info-text"> Let's start with the basic information</h4>
		                                	<div class="col-sm-4 col-sm-offset-1">
		                                    	<div class="picture-container">
		                                        	<div class="picture">
                                        				<img src="<?=base_url()?>assets/img/default-avatar.png" class="picture-src"
                                        					id="wizardPicturePreview" title="" />
		                                            	<input type="file" name="ImgProfile" id="wizard-picture">
		                                        	</div>
		                                        	<h6>Choose Picture</h6>
		                                    	</div>
		                                	</div>
		                                	<div class="col-sm-6">
												<div class="input-group">
													<span class="input-group-addon">
														<i class="material-icons">face</i>
													</span>
													<div class="form-group label-floating">
			                                          <label class="control-label">First Name <small>(required)</small></label>
			                                          <input name="txtFName" type="text" class="form-control" required>
			                                        </div>
												</div>

												<div class="input-group">
													<span class="input-group-addon">
														<i class="material-icons">record_voice_over</i>
													</span>
													<div class="form-group label-floating">
													  <label class="control-label">Last Name <small>(required)</small></label>
													  <input name="txtLName" type="text" class="form-control" required>
													</div>
												</div>
		                                	</div>
		                                	<div class="col-sm-10 col-sm-offset-1">
												<div class="input-group">
													<span class="input-group-addon">
														<i class="material-icons">email</i>
													</span>
													<div class="form-group label-floating">
			                                            <label class="control-label">Email <small>(required)</small></label>
			                                            <input name="txtEmail" type="email" class="form-control" required>
			                                        </div>
												</div>
		                                	</div>
		                            	</div>
									</div>
									


		                            <div class="tab-pane" id="account">
		                                <h4 class="info-text"> Setup your Account </h4>
		                                <div class="row">
		                                	<div class="col-sm-4 col-sm-offset-1">
												<div class="picture-container">
													<div class="picture">
														<img src="<?=base_url()?>assets/img/default-favicon.ico" class="picture-src" id="wizardPicturePreview2" title="" />
														<input type="file" name="ImgFavicon" id="wizard-picture2">
													</div>
													<h6>Choose Your Favicon</h6>
												</div>
											</div>
											
		                                	<div class="col-sm-6">
		                                		<div class="input-group">
		                                			<span class="input-group-addon">
		                                				<i class="material-icons fas fa-user"></i>
		                                			</span>
		                                			<div class="form-group label-floating">
		                                				<label class="control-label">Username
		                                					<small>(required)</small></label>
		                                				<input name="txtID" type="text" class="form-control" required>
		                                			</div>
		                                		</div>

		                                		<div class="input-group">
		                                			<span class="input-group-addon">
		                                				 <i class="material-icons fas fa-key"></i>
		                                			</span>
		                                			<div class="form-group label-floating">
		                                				<label class="control-label">Password
		                                					<small>(required)</small></label>
		                                				<input name="txtPW" type="password" class="form-control" required>
		                                			</div>
												</div>
												
												<div class="input-group">
													<span class="input-group-addon">
														<i class="material-icons fas fa-dice-d6"></i>
													</span>
													<div class="form-group label-floating">
														<label class="control-label">Game Name
															<small>(required)</small></label>
														<input name="txtGN" type="text" class="form-control" required>
													</div>
												</div>
											</div>

											

											
		                                </div>
									</div>
									


		                            <div class="tab-pane" id="license">
		                                <div class="row">
											<h4 class="info-text"> License Your Site </h4>
											<div class="col-sm-8 col-sm-offset-2">
												<div class="form-group label-floating" >
													<center>
													<h6>License Key</h6>
													<fieldset id="productkey">
														<input name="lic1" id="lic1" type="text" size="5" maxlength="5" style="text-align: center;"> -
														<input name="lic2" id="lic2" type="text" size="5" maxlength="5" style="text-align: center;"> -
														<input name="lic3" id="lic3" type="text" size="5" maxlength="5" style="text-align: center;"> -
														<input name="lic4" id="lic4" type="text" size="5" maxlength="5" style="text-align: center;"> -
														<input name="lic5" id="lic5" type="text" size="5" maxlength="5" style="text-align: center;">
													</fieldset>
													</center>
												</div>
											</div>

											<div class="col-sm-8 col-sm-offset-2">
												<div class="input-group">
													<span class="input-group-addon">
														<i class="material-icons fas fa-dice-d20"></i>
													</span>
													<div class="form-group label-floating">
														<label class="control-label">API Key</label>
														<input name="txtAPI" id="txtAPI" type="text" class="form-control" style="text-align: center;" required>
													</div>
												</div>
											</div>
		                                </div>
		                            </div>
		                        </div>
		                        <div class="wizard-footer">
		                            <div class="pull-right">
		                                <input type='button' class='btn btn-next btn-fill btn-info btn-wd' name='next' value='Next' />
		                                <input type='button' id="SubmitForm" class='btn btn-finish btn-fill btn-info btn-wd' name='finish'
		                                	value='Finish' />
		                            </div>

		                            <div class="pull-left">
		                                <input type='button' class='btn btn-previous btn-fill btn-default btn-wd' name='previous' value='Previous' />
		                            </div>
		                            <div class="clearfix"></div>
		                        </div>
		                    </form>
		                </div>
		            </div> <!-- wizard container -->
		        </div>
	        </div><!-- end row -->
	    </div> <!--  big container -->

	    <div class="footer">
	        <div class="container text-center">
	             <!-- Made with <i class="fa fa-heart heart"></i> by <a href="http://www.creative-tim.com">Creative Tim</a>. Free download <a href="http://www.creative-tim.com/product/bootstrap-wizard">here.</a> -->
	        </div>
	    </div>
	</div>

</body>
	<!--   Core JS Files   -->
    <script src="<?=base_url()?>assets/js/jquery-2.2.4.min.js" type="text/javascript"></script>
	<script src="<?=base_url()?>assets/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="<?=base_url()?>assets/js/jquery.bootstrap.js" type="text/javascript"></script>

	<!--  Plugin for the Wizard -->
	<script src="<?=base_url()?>assets/js/material-bootstrap-wizard.js"></script>

    <!--  More information about jquery.validate here: http://jqueryvalidation.org/	 -->
	<script src="<?=base_url()?>assets/js/jquery.validate.min.js"></script>

</html>


<script>
	$( '#productkey' ).on( 'keyup', 'input', function () {
	if ( this.value.length === 5 ) {
	$( this ).next().focus();
	}
	});

	$('#SubmitForm').click(function () {
		let lic1 = $('#lic1').val();
		let lic2 = $('#lic2').val();
		let lic3 = $('#lic3').val();
		let lic4 = $('#lic4').val();
		let lic5 = $('#lic5').val();

		let license = lic1 +'-'+ lic2 +'-'+ lic3 + '-' + lic4 + '-' + lic5;
		let txtAPI = $('#txtAPI').val();

		$.ajax({
			type: 'POST',
			url: '<?php echo base_url(); ?> jrgapi/MasterPage/GetLicense',
			data: {license:license,txtAPI:txtAPI},
			success: function (data) {
				if(data.success == true){
					$('#frmSiteLicense').attr('action','<?php base_url(); ?>jrgapi/MasterPage/Register/' + 2).submit();
				}
			},
			error: function (data) {
				alert("Invalid WebSite License");
			}
		});

	});
</script>

