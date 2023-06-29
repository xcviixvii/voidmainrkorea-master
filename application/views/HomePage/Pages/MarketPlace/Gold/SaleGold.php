<?php
if(!$this->session->userdata('UserID')){
            NotifyError('please try again after logging in..');
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
    <form method="post" onsubmit="return fnSubmit(this)">
    <input type="hidden" name="status" value="<?=$this->Internal_model->CheckCharOnline($this->session->userdata('UserID'))?>">
    <section class="recharge">
	    <div class="wrapper">
		    <h1><img src="<?=base_url()?>Images/ItemShopNew/AccountFix.gif" alt="Account Fix"></h1>
		    <div class="container">
            <h2>
    			Account Status : <?=(($this->Internal_model->CheckCharOnline($this->session->userdata('UserID')) == 0) ? "<b style='color:red'>Offline</b>":"<b style='color:green'>Online</b>")?>
    		</h2>
            <table class="payment-Info">
    			<caption>Account information</caption>
    		<tbody>
    			<tr>
    			    <th style="font-size:10px;">CHARACTER</th>
    				<td>
    				<select id="selres" class="selres_box" name="character" onchange="ShowChar(this.value)">
                    <?php 
                    $char = $this->llllllllz_model->characterperuser($this->session->userdata('UserID'));
                    ?>
                        <option value="0">Select Character</option>
                        <?php foreach ($char as $row): ?>
                            <option <?=(($this->session->userdata('ChaNum') == $row['ChaNum']) ? "selected":"")?> value="<?=$row['ChaNum']?>"><?=$row['ChaName']?></option>
                        <?php endforeach ?>

                    </select>
    				</td>
    			</tr>    

                <tr>
    			    <th style="font-size:10px;"></th>
    				<td>
    				<table style="width:100%;">
                    <colgroup>
                    <col width="30">
                    <col width="300">
                    <col width="20">
                    </colgroup>
                    <thead>
                    <tr>
                    <th scope="col" class="line">CLASS</th>
                    <th scope="col" class="line">CHARACTER NAME</th>
                    <th scope="col">GOLD</th>
                    </tr>
                    </thead>
                    <tbody id="tableSaleGold">
                    </tbody>
                    </table>
    				</td>
                </tr>   
                
                <tr>
                    <th>GOLD AMOUNT</th>
                    <td>
                        <input type="text" name="goldsale" placeholder="Gold Amount" value="<?=$this->session->userdata('Gold')?>" style="width: 50%; margin: 0px auto;">
                    </td>
                </tr>

                <tr>
                    <th>E-POINTS</th>
                    <td>
                        <input type="text" name="epprice" value="<?=$this->session->userdata('EP')?>" placeholder="E-Points Price" style="width: 50%; margin: 0px auto;"><br /><br />
                    </td>
                </tr>


            </tbody>
            <table/>
             <footer>
				<input type="image" src="<?=base_url()?>Images/ItemShopNew/progress_btn.gif" alt="Progress"
    						style="vertical-align:middle;">
    					<img src="<?=base_url()?>Images/ItemShopNew/cancel_btn.gif" class="pointer"
    						onclick="self.close();" alt="Cancel">
			</footer>
			</div>
	    </div>
    </section>
    </form>
    
    <script type="text/javascript" src="<?=base_url()?>Scripts/jquery-1.5.1.js"></script>
    
    <script type="text/javascript">
     function ShowChar(ChaNum) {
            $.post(
                    "<?=base_url()?>GoldMarket/ChaInfo/" + ChaNum,
                    { "ChaNum": ChaNum },
                    function(data) {
                        $("#tableSaleGold").html(data).show();
                    }
                );
        }
    </script>
</body>
</html>



