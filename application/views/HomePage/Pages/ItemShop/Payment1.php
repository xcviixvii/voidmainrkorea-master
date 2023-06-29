<?php
if(!$this->session->userdata('UserID')){
	
			?>
		 	<script> 
		 		// alert("please try again after logging in..") 
		 		self.close();
				location.href = '<?=base_url()?>';
		 	</script>
		 	<?php
		}
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
    <form id="form1" name="form1" method="post" action="<?=base_url()?>ItemShop/PaymentStep2">
    <input type="hidden" id="hdnPrice" name="Price">
    <input type="hidden" id="hdnPaymentNum" name="PaymentNum" value="253435"> <!--SET RANDOM VALUE-->
    <section class="recharge">
	    <div class="wrapper">
		    <h1><img src="<?=base_url()?>Images/ItemShopNew/recharge_title.gif" alt="Charging the Cash"></h1>
		    <div class="container">
			    <h2>
				    Current hold E-Points: <strong><?=((!$points) ? "".formatnumber3(0)."":"".formatnumber3($points[0]['EPoint'])."")?></strong> Points
			    </h2>
			    <table class="payment-Info">
			    <caption>Billing information</caption>
			    <tbody>
			    <tr>
				    <th>Charge amount</th>
				    <td>
					    <div class="amount-select">
						    <input id="amount01" type="radio" name="amount" value="50">
						    <label for="amount01">50</label>
						    <input id="amount02" type="radio" name="amount" value="250">
						    <label for="amount02">250</label>
						    <input id="amount03" type="radio" name="amount" value="500">
						    <label for="amount03">500</label>
						    <br>
						    <input id="amount04" type="radio" name="amount" value="1000">
						    <label for="amount04">1,000</label>
						    <input id="amount05" type="radio" name="amount" value="2500">
						    <label for="amount05">2,500</label>
						    <input id="amount06" type="radio" name="amount" value="5000">
						    <label for="amount06">5,000</label>
						    
					    </div>
					    <div class="info">
						    <!-- <p>Monthly charge limit: 500,000 / minus 50,000</p> -->
					    </div>
				    </td>
			    </tr>
			    </tbody>
			    </table>
			    <footer>
				    <img src="<?=base_url()?>Images/ItemShopNew/progress_btn.gif" class="pointer" onclick="fnNext();" alt="Process">
				    <img src="<?=base_url()?>Images/ItemShopNew/cancel_btn.gif" class="pointer" onclick="fnClose();" alt="Cancel">
			    </footer>
		    </div>
	    </div>
    </section>
    </form>
    
    <script type="text/javascript" src="<?=base_url()?>Scripts/jquery-1.5.1.js"></script>
    
    <script type="text/javascript">
        var frm = document.form1;

        function fnNext() {
            var price = 0;
            
            for (var intLoop = 0; intLoop < frm.amount.length; intLoop++) {
                if (frm.amount[intLoop].checked == true) {
                    price = Number(frm.amount[intLoop].value);
                    break;
                }
            }

            if (price == 0) {
                alert("Please select payment amount");
                return false;
            }

            document.getElementById("hdnPrice").value = price;
            frm.submit();
        }

        function fnClose() {
            self.close();
        }
        
        $(document).ready(function() {
            $('input[name="amount"]').click(function() {
                $('input[name="amount"]').next().css('font-weight', 'normal');
                $(this).next().css('font-weight', 'bold');
            });
        });

		
    </script>
</body>
</html>



