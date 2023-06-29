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
    <section class="recharge">
	    <div class="wrapper">
		    <h1><img src="<?=base_url()?>Images/ItemShopNew/recharge_title.gif" alt="Charging the Cash"></h1>
		    <div class="container">
			<table class="payment-Info">
			    <tbody>
			    <tr>
				    <td>
                        <progress value="0" max="5" id="progressBar"></progress>
				    </td>
			    </tr>
			    </tbody>
			</table>

                <script>
                var timeleft = 5;
                var downloadTimer = setInterval(function(){
                if(timeleft <= 0){
                    clearInterval(downloadTimer);
                }
                document.getElementById("progressBar").value = 5 - timeleft;
                timeleft -= 1;
                }, 1000);
                </script>
          

		    </div>
	    </div>
    </section>
    
</body>
</html>



