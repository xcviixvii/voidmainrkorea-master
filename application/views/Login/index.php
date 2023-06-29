<?php
$gencode = generatecaptcha();
?>
<!doctype html>

<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Ran Online | Login</title>
    <link rel="shortcut icon" type="image/x-icon" href="<?=base_url()?>favicon.ico">
    <link rel="Stylesheet" type="text/css" href="<?=base_url()?>Styles/Basic.css" media="all">
    <link rel="Stylesheet" type="text/css" href="<?=base_url()?>Styles/Plugin/jquery.autocomplete.css" media="all">
	<script type="text/javascript" src="<?=base_url()?>Library/notify/jquery-1.8.2.min.js"></script>
    <link href="<?=base_url()?>Library/Notify/jquery.notify.css" type="text/css" rel="stylesheet" />
    <link href="<?=base_url()?>Library/lib/fontawesome/css/all.min.css" rel="stylesheet">
    <script type="text/javascript" src="<?=base_url()?>Library/notify/jquery.notify.min.js"></script>
</head>
<body>
<?php
if($this->session->flashdata('NotifyType') == 'Common'){
  $title  = $this->session->flashdata('title');
  $text   = $this->session->flashdata('text');
  $type   = $this->session->flashdata('type');
  $icon   = $this->session->flashdata('icon');
  notifymain($type,$title,$text,$icon); 
}
?>
    <div class="member">
		<div class="bottom-body">
			<div id="member-wrapper">
				<header id="member-header">
					<nav>
						<span><a href="<?=base_url()?>">HOME</a></span>
						<span class="selector">ㅣ</span>
						<span><a href="#"><b>LOGIN</b></a></span>
					</nav>
					<h1><img src="<?=base_url()?>Images/Login/login_logo_title.gif" alt="란 온라인 로그인"></h1>
					<section class="tell">
						<!--<img src="<?=base_url()?>Images/Login/loginneed_title.png" alt="로그인이 필요한 서비스입니다">-->
					</section>
				</header>
				<div id="member-content">
					<div class="content-top"></div>
					<div class="content-middle">
						<h2><img src="<?=base_url()?>Images/Login/login_title.gif" alt="로그인"></h2>
						<section class="login">
							<section class="loginbox-left">
								<img src="<?=base_url()?>Images/Login/lock.gif" alt="">
							</section>
							<section class="loginbox-right">
							    <form id="frmLogin" method="post" action="">
							    <input type="hidden" id="hdnPath" name="path" value="/">
							    <fieldset>
							        <legend>LOGIN</legend>
								    <section class="login-info">
									    <dl>
										    <dt><label for="txtID">ID</label></dt>
										    <dd><input type="text" id="txtID" name="txtID" class="txt-middle"></dd>
										    <dt class="pwd"><label for="txtPW">PASSWORD</label></dt>
										    <dd class="pwd"><input type="password" id="txtPW" name="txtPW" class="txt-middle"></dd>
										    <dt class="validate"><label for="txtPW">CODE</label></dt>
										    <dd class="validate"><input type="text" id="txtValidateString" name="txtValidateString" class="txt-middle" maxlength="5" autocomplete="off">
									    </dl>
								    </section>
								    <section class="login-button">
									    <img src="<?=base_url()?>Images/Button/login2_btn.gif" id="btnLogin" class="pointer" alt="LOGIN">
								    </section>
								</fieldset>
								</form>								
								<div class="clear"></div>
								<section class="vali-wrapper">
								    <div>
								        <p>Enter the following characters in the security code..</p>
								        <div class="imageconfig"><span class="sss"><?=$gencode?></span></div>
								    </div>
								</section>
								<div class="login-management">
									<div class="login-management-left">
										<span class="tip">Forgot your username and password?</span>
										<span class="tip">Don't Have an Account?</span>
									</div>
									<div class="login-management-right">
										<a href="/Member/FindIDConfirm.aspx"><img src="<?=base_url()?>Images/Button/findinfo_btn.gif" alt="ID AND PASSWORD"></a>
										<a href="<?=base_url()?>Registration"><img src="<?=base_url()?>Images/Button/signup2_btn.gif" alt="Registration"></a>
									</div>
									<div class="clear"></div>
							    </div>
							</section>
							<div class="clear"></div>
						</section>
					</div>
				</div>
			</div>
		</div>
	</div>
    <script type="text/javascript" src="<?=base_url()?>Scripts/jquery-1.5.1.min.js"></script>
    <script type="text/javascript" src="<?=base_url()?>Scripts/Common.js"></script>

    <footer id="footer">
	<?php
	renderview('HomePage/Templates/tmpfooter');
	?>
	</footer>


    
    
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

            $('#txtID').keyup(function(e) {
                if (e.keyCode == "13") {
                    $("#txtPW").focus();
                }
            });

            $('#txtPW').keyup(function(e) {
                if (e.keyCode == "13") {
                    $('#txtValidateString').focus();
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
                    // alert('Please enter your ID.');
                    <?php
                    NotifyWarning("Please enter your ID.");
                    ?>
                    $('#txtID').focus();
                    return false;
                }

                if (!Common.FormValidate($('#txtPW'))) {
                    // alert('Please enter a password.');
                    <?php
                    NotifyWarning("Please enter a password.");
                    ?>
                    $('#txtPW').focus();
                    return false;
                }

                if (!Common.FormValidate($('#txtValidateString'))) {
                    // alert('Please enter a security code.');
                    <?php
                    NotifyWarning("Please enter a security code.");
                    ?>
                    $('#txtValidateString').focus();
                    return false;
                }

                if ("<?=$gencode?>" != $("#txtValidateString").val()) {
                    // alert("Anti-subscription code does not match.");
                    <?php
                    NotifyError("Anti-subscription code does not match.");
                    ?>
					location.reload();
                    return false;
                }

                $('#frmLogin').attr('action', '<?=base_url()?>jrgapi/MasterPage/player').submit();
                //$('#frmLogin').attr('action', '/MasterPage/LoginProcess.aspx').submit();
            });
        });
    </script>
</body>
</html>
