
<?php
if(!$this->session->userdata('UserID')){
	?>
	<script> 
		alert("please try again after logging in..") 
		self.close();
		location.href = '<?=base_url()?>';
	</script>
	<?php
}
$gencode = generatecaptcha();
?>

<!doctype html>

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Ran Online</title>
	<link rel="Stylesheet" type="text/css" href="<?=base_url()?>Styles/Popup.css" media="all">
	<link rel="Stylesheet" type="text/css" href="<?=base_url()?>Styles/ItemShop.css" media="all">
	<script type="text/javascript" src="<?=base_url()?>Scripts/Common.js"></script>
</head>
<body>
    <form method="post" action="<?=base_url()?>jrgapi/MasterPage/pTopUp" onsubmit="return pay(this)">
    	<section class="recharge">
    		<div class="wrapper">
    			<h1><img src="<?=base_url()?>Images/ItemShopNew/recharge_title.gif" alt="Charging the Cash"></h1>
    			<div class="container">
    				<h2>
    					Current hold E-Points:
    					<strong><?=((!$points) ? "".formatnumber3(0)."":"".formatnumber3($points[0]['EPoint'])."")?></strong> Points
    				</h2>
    				<table class="payment-Info">
    					<caption>Billing information</caption>
    					<tbody>
    						<tr>
    							<th>Method of Payment</th>
    							<td>
    								<div class="payment-select">
    									<fieldset>
    										<legend>Method of Payment</legend>
    										<input id="payment01" type="radio" name="payment" value="Gcash">
    										<label for="payment01">GCash</label>
    										<input id="payment02" type="radio" name="payment" value="Paymaya">
    										<label for="payment02">Paymaya</label>
    										<input id="payment03" type="radio" name="payment" value="Paypal">
    										<label for="payment03">Paypal</label>
    										<br>


    									</fieldset>
    								</div>
    							</td>
    						</tr>
    						<tr>
    							<th>Contact</th>
    							<td class="mobile">
    								<fieldset>
    									<input type="text" name="selUserPhone1" id="selUserPhone1" value="" maxlength="3">
    									-
    									<input type="text" name="txtUserPhone2" id="txtUserPhone2" value="" maxlength="3">
    									-
    									<input type="text" name="txtUserPhone3" id="txtUserPhone3" value="" maxlength="4">
    								</fieldset>
    								<div class="info">
    									<!-- <p>Payment Processing Notification Only for sending SMS.</p> -->
    								</div>
    							</td>
    						</tr>

							<tr>
								<th>Code</th>
								<td><input type="text" id="txtValidateString" name="txtValidateString" class="txt-middleR" maxlength="5" autocomplete="off"></td>
							</tr>
							<tr>
								<th>Security Code</th>
								<td>
									<section class="vali-wrapper2">
										<div>
											<div class="imageconfig2"><span class="sss"><?=$gencode?></span></div>
										</div>
									</section>
								</td>
							</tr>
    						<!-- <tr>
    							<th>Terms of service </th>
    							<td>
    								<fieldset>
    									<legend>Terms and conditions</legend>
    									<input type="checkbox" id="chkAgree" name="Agree">
    									<label for="agree">
    										I agree to the cache terms and conditions. <em><a onclick="fnAgree();"
    												class="pointer">[View terms]</a></em>
    									</label>
    								</fieldset>
    							</td>
    						</tr> -->
    					</tbody>
    				</table>
    				<section class="payment-result">
    					<dl>
    						<dt>
    							<img src="<?=base_url()?>Images/ItemShopNew/amount.gif" alt="Charge amount">
    						</dt>
    						<dd class="amount">
    							<strong id="amount">
									<?php
										if(isset($_POST['amount'])){
											$amount = $_POST['amount'];
											echo formatnumber3($_POST['amount']);
										} else {
											$amount = 0;
											echo 0;
										}
									?>
								</strong> Points
    						</dd>
    						<dt><img src="<?=base_url()?>Images/ItemShopNew/payment.gif" alt="Method of Payment"></dt>
    						<dd class="payment-way">
    							<strong id="paymentkind"></strong>
    						</dd>
    					</dl>
    				</section>
    				<footer>
    					<img src="<?=base_url()?>Images/ItemShopNew/prev_btn.gif" class="pointer" onclick="fnPrev();"
    						alt="Prev">
    					<input type="image" src="<?=base_url()?>Images/ItemShopNew/progress_btn.gif" alt="Progress"
    						style="vertical-align:middle;">
    					<img src="<?=base_url()?>Images/ItemShopNew/cancel_btn.gif" class="pointer"
    						onclick="self.close();" alt="Cancel">
    				</footer>
    			</div>
    		</div>
    	</section>
	<input type="hidden" name="amount" value="<?=$amount?>">	

	<input type="hidden" name="goodname" value="RanOnlineCash">	
	<!-- 결제방법 -->
	<input type="hidden" name="gopaymethod" value="">
	<!-- 결제자 -->
    <input type="hidden" name="buyername" value="">
    <!-- 결제자 email -->
    <input type="hidden" name="buyeremail" value="">
    <!-- 결제자 phone -->
    <input type="hidden" name="buyertel" value="">
    <!-- 결제번호 -->
    <input type="hidden" name="PaymentNum" value="253435">
    <!-- hwkim 추가 -->
	</form>
	
	<script type="text/javascript" src="<?=base_url()?>Scripts/jquery-1.5.1.js"></script>
    
    
    <script type="text/javascript">
        var userPhone = '';
        var Price = Number('<?=$amount?>'); //Number('1000');

        var userPhone1 = '';
        var userPhone2 = '';
        var userPhone3 = '';

        var arrPhone = userPhone.split('-');
        var len = arrPhone.length;
        
        if (arrPhone.length == 3) {
            userPhone1 = arrPhone[0];
            userPhone2 = arrPhone[1];
            userPhone3 = arrPhone[2];
        }
        
        document.getElementById('selUserPhone1').value = userPhone1;
        document.getElementById('txtUserPhone2').value = userPhone2;
        document.getElementById('txtUserPhone3').value = userPhone3;
    
        var openwin;

		if (Price <= 0){ 
			alert('Please Select Amount !!!');
			location.href='<?=base_url()?>ItemShop/PaymentStep1' ; 
		}


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
		
        function pay(frm) {
            var paymentType = '';
            
			

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

            for (var intLoop = 0; intLoop < frm.payment.length; intLoop++) {
                if (frm.payment[intLoop].checked == true) {
                    paymentType = frm.payment[intLoop].value;
                    break;
                }
            }

            if (paymentType == '') {
                alert("Please Select Payment Method");
                return false;
            }

            if (paymentType == 'onlyvbank') {
                if (Price < 10000) {
                    alert('You can not make a deposit by less than 10,000 Points.');
                    return false;
                }
			}
			
			if (Price <= 0){
				alert("Please select Payment Amount !!!");
				location.href='<?=base_url()?>ItemShop/PaymentStep1' ; 
			}

            frm.gopaymethod.value = paymentType;

            if (document.getElementById("txtUserPhone2").value == '' || document.getElementById("txtUserPhone3").value == '') {
                alert('Please enter your contact information.');
                return false;
            }

            frm.buyertel.value = document.getElementById('selUserPhone1').value + document.getElementById('txtUserPhone2').value + document.getElementById('txtUserPhone2').value;            

            if (document.getElementById('chkAgree').checked == false) {
                alert('Please accept the terms.');
                return false;
            }   
        }

        function fnPrev() {
            location.href = '<?=base_url()?>ItemShop/PaymentStep1';
        }

        function fnAgree() {
            Common.OpenCenterWindow(500, 680, '/ItemShop/Payment/Agreement.html', 'agree', false);
        }

        $(document).ready(function() {
            $('input[name="payment"]').click(function() {
                $('input[name="payment"]').next().css('font-weight', 'normal');
                $(this).next().css('font-weight', 'bold');
                $('#paymentkind').text($(this).next().text());
            });
        });
    </script>
</body>
</html>