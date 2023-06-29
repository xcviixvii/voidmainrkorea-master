<?php
$gencode = generatecaptcha();
?>
<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Ran Online | Registration</title>
    <link rel="shortcut icon" type="image/x-icon" href="<?=base_url()?>favicon.ico">
    <link rel="Stylesheet" type="text/css" href="<?=base_url()?>Styles/Basic.css" media="all">
    <link rel="Stylesheet" type="text/css" href="<?=base_url()?>Styles/Plugin/jquery.autocomplete.css" media="all">
</head>
<body>
    <div class="member">
		<div class="bottom-body">
			<div id="member-wrapper">
				<header id="member-header">
					<nav>
						<span><a href="<?=base_url()?>">HOME</a></span>
						<span class="selector">ㅣ</span>
						<span><a href="<?=base_url()?>login"><b>LOGIN</b></a></span>
					</nav>
					<h1><img src="<?=base_url()?>Images/Login/login_logo_title.gif" alt="란 온라인 로그인"></h1>
					<section class="tell">
						<!--<img src="<?=base_url()?>Images/Login/loginneed_title.png" alt="로그인이 필요한 서비스입니다">-->
					</section>
				</header>
				<div id="member-content">
					<div class="content-top"></div>
					<div class="content-middle">
						<h2><img src="<?=base_url()?>Images/Login/registration_title.gif" alt="로그인"></h2>
						<section class="login">
							<section class="loginbox-left" style="line-height: 220px;">
								<img src="<?=base_url()?>Images/Login/lock.gif" alt="">
							</section>
							<section class="loginbox-right">
							    <form id="frmregistration" method="post">
							    <input type="hidden" id="hdnPath" name="path" value="/">
							    <fieldset>
							        <legend>LOGIN</legend>
								    <section class="registration-info">
									    <dl>
										    <dt><label for="txtID">ID</label></dt>
										    <dd><input type="text" id="txtID" name="txtID" class="txt-middleR"></dd>
										    
										    <dt class="pwd"><label for="txtPW">PW</label></dt>
										    <dd class="pwd"><input type="password" id="txtPW" name="txtPW" class="txt-middleR"></dd>

										    <dt class="pwd"><label for="txtPIN">PIN</label></dt>
										    <dd class="pwd"><input type="text" id="txtPIN" name="txtPIN" class="txt-middleR"></dd>

										    <dt class="pwd"><label for="txtEMAIL">EMAIL</label></dt>
										    <dd class="pwd"><input type="email" id="txtEMAIL" name="txtEMAIL" class="txt-middleR"></dd>

										    <dt class="validate"><label for="txtPW">CODE</label></dt>
										    <dd class="validate"><input type="text" id="txtValidateString" name="txtValidateString" class="txt-middleR" maxlength="5">
									    </dl>
								    </section>
								</fieldset>
								<section class="vali-wrapper2">
								    <div>
								        <p>Enter the following characters in the security code..</p>
								        <div class="imageconfig2"><span class="sss"><?=$gencode?></span></div>
								    </div>
								</section>
										<!--
										<dl>
										    <dt><label for="txtID">REFFERRAL</label></dt>
										    <dd><input type="text" id="txtID" name="txtID" class="txt-middle"></dd>
									 	</dl>
									 	-->
									 	<br />
										<div style="text-align: center;">
										<img src="<?=base_url()?>Images/Button/registration_btn.gif" id="btnLogin" class="pointer" alt="">
										<img src="<?=base_url()?>Images/Button/cancel_btn.gif" id="btncancel" class="pointer" alt="">
										</div>
									    <br />
									    <section style="border-bottom:1px solid #CCCCCC;"></section>

								</form>								
								<div class="clear"></div>
								
							
							</section>
							<div class="clear"></div>
						</section>
					</div>
				</div>
			</div>
		</div>
	</div>
    
    <footer id="footer">
	<?php
    renderview('HomePage/Templates/tmpfooter')?>
	</footer>

    <script type="text/javascript" src="<?=base_url()?>Scripts/jquery-1.5.1.min.js"></script>
    <script type="text/javascript" src="<?=base_url()?>Scripts/AjaxSetup.js"></script>
    <script type="text/javascript" src="<?=base_url()?>Scripts/Common.js"></script>
      
    
    <script type="text/javascript">
        $(document).ready(function() {
            $('#txtID').keypress(function(e) {
                if (e.keyCode == '13') {
                    return false;
                }
            });

            $('#txtPW').keypress(function(e) {
                if (e.keyCode == '13') {
                    return false;
                }
            });

            $('#txtPIN').keypress(function(e) {
                if (e.keyCode == '13') {
                    return false;
                }
            });

            $('#txtEMAIL').keypress(function(e) {
                if (e.keyCode == '13') {
                    return false;
                }
            });

            $('#txtID').keyup(function(e) {
                if (e.keyCode == "13") {
                    $("#txtPW").focus();
                }
            });

            $('#txtPW').keyup(function(e) {
                if (e.keyCode == "13") {
                    $('#txtPIN').focus();
                }
            });

            $('#txtPIN').keyup(function(e) {
                if (e.keyCode == "13") {
                    $("#txtEMAIL").focus();
                }
            });

            $('#txtEMAIL').keyup(function(e) {
                if (e.keyCode == "13") {
                    $("#txtValidateString").focus();
                }
            });

            $('#txtValidateString').keyup(function(e) {                
                if (e.keyCode == "13") {
                    $("#btnLogin").click();
                } else {
                    var val = $('#txtValidateString').val();
                    val = val.toUpperCase()
					val = val.replace(/(\s*)/g, "");
                    $('#txtValidateString').val(val);
                    
                }
            });

            $('#btnLogin').click(function() {
                if (!Common.FormValidate($('#txtID'))) {
                    alert('Please enter your ID.');
                    $('#txtID').focus();
                    return false;
                }

                if (!Common.FormValidate($('#txtPW'))) {
                    alert('Please enter a password.');
                    $('#txtPW').focus();
                    return false;
                }

                if (!Common.FormValidate($('#txtPIN'))) {
                    alert('Please enter a PIN.');
                    $('#txtPIN').focus();
                    return false;
                }
                var email = $('#txtEMAIL').val();
      			if (email.match(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/)){

      			} else {
      				alert('Please Enter a Valid Email Address');
      				$('#txtEMAIL').focus();
      				return false;
      			}

                if (!Common.FormValidate($('#txtEMAIL'))) {
                    alert('Please enter a EMAIL.');
                    $('#txtEMAIL').focus();
                    return false;
                }

                if (!Common.FormValidate($('#txtValidateString'))) {
                    alert('Please enter a security code.');
                    $('#txtValidateString').focus();
                    return false;
                }

                if ("<?=$gencode?>" != $("#txtValidateString").val()) {
                    alert("Anti-subscription code does not match.");
					location.reload();
                    return false;
                }

                $('#frmregistration').attr('action', '<?=base_url()?>jrgapi/MasterPage/Registration').submit();

                //$('#frmLogin').attr('action', '/MasterPage/LoginProcess.aspx').submit();
            });
        });

         $(document).ready(function() {
	        $('#btncancel').click(function() {
				location.href = "<?=base_url()?>login"
			});
		});
    </script>
</body>
</html>
