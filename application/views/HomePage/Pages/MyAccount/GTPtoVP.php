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
$gtime = $this->Internal_model->GetGameTime($this->session->userdata('UserID'));
?>
<!doctype html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Ran Online</title>
	<link rel="Stylesheet" type="text/css" href="<?=base_url()?>Styles/Popup.css" media="all">
	<link rel="Stylesheet" type="text/css" href="<?=base_url()?>Styles/ItemShop.css" media="all">
	<script type="text/javascript" src="<?=base_url()?>Scripts/Common.js"></script>

    <style>

    
    td .num-button .num-up, td .num-button .num-down {
        font-size: 0;
        text-indent: -1000px;
        line-height: 0;
        overflow: hidden;
        background: url(../Images/ItemshopNew/num_btn.gif) no-repeat;
    }


    td .num-button {
        width: 11px;
        height: 20px;
        margin-right: 5px;
        font-size: 0;
        line-height: 0;
        vertical-align: middle;
        display: inline-block;
    }

    td .num-button .num-up {
        width: 11px;
        height: 9px;
        margin-bottom: 2px;
        display: block;
    }

    td .num-button .num-down {
        width: 11px;
        height: 9px;
        background-position: 0 -11px;
        display: block;
    }



    .txt-num {
        width: 40px;
        padding: 0px 5px 0px 5px;
        font-family: "Verdana", "굴림";
        font-size: 12px;
        height: 20px;
    }

    .txt-num2 {
        width: 90px;
        padding: 0px 5px 0px 5px;
        font-family: "Verdana", "굴림";
        font-size: 12px;
        height: 20px;
    }

    a {
        color: #444;
        text-decoration: none;
        cursor: pointer;
    }

    </style>
</head>
<body>
    <form method="post" action="<?=base_url()?>MyAccount/GTPtoVP" onsubmit="return fnSubmit(this)">
    <input type="hidden" name="status" value="<?=$this->Internal_model->CheckCharOnline($this->session->userdata('UserID'))?>">
    <section class="recharge">
	    <div class="wrapper">
		    <h1><div style="color:white; margin-top:5px;">Gametime Points to Vote Points</div></h1>
		    <div class="container">
            <h2>
    			Account Status : <?=(($this->Internal_model->CheckCharOnline($this->session->userdata('UserID')) == 0) ? "<b style='color:red'>Offline</b>":"<b style='color:green'>Online</b>")?>
                <br />Current Vote Points : <?=(($this->Internal_model->GetUserUserPoint($this->session->userdata('UserID')) == NULL) ? "0":"".$this->Internal_model->GetUserUserPoint($this->session->userdata('UserID'))."")?>
                <br /><small style="font-size:11px;">Note: <small style="color:orange">Please Log-off your Character before Using this Function</small></small>
                <!-- <br /><small style="font-size:11px;"><b> Gametime Points - Vote Points</b></small> -->
                <br /><small style="font-size:11px;"><b>2 Gametime Points - 1 Vote Points</b></small>
            </h2>
            <table class="payment-Info">
    			<caption>Account information</caption>
    		<tbody>
                <tr>
    			    <th style="font-size:10px;">GAMETIME POINTS</th>
    				<td>
    				<input type="text" id="UserPoint" value="<?=$gtime[0]['UserCombatPoint']?> Points">
    				</td>
    			</tr> 

    			<tr>
    			    <th style="font-size:10px;">CONVERT</th>
    				<td>
                        <input type="text" name="GameTimePoints" id="txt-num" class="txt-num" value="1" readonly="readonly">
                        <span class="num-button">
                        <a id="btnUP" class="num-up"></a>
                        <a id="btnDown" class="num-down"></a>
                        </span>

                        <input type="text" id="convertable" class="txt-num2" value="1 Points" readonly="readonly">
                        <input type="hidden" name="UserPoints" id="UserPoints" value="1">
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
    
</body>
</html>


	<script type="text/javascript">
		$(document).ready(function() {
            $('#btnUP').click(function() {


            	var chenes = <?=$gtime[0]['UserCombatPoint']?>;

            	var ewan = parseInt(chenes / 2);
            	var valtotal = ewan + 1;
            	var etoval = ewan;
                var val = $('#txt-num').val();
                val++;
                if(ewan == 0){
                	val = 1;
                	exit;
                }
                if (val == valtotal) {
                    val = etoval;
                }

                $('#txt-num').val(val);
                $('#convertable').val(val * 1 + ' Points');
                $('#UserPoints').val(val * 1);
            });
            $('#btnDown').click(function() {
                var val = $('#txt-num').val();
                val--;
                if (val < 0 || val == 0) {
                    val = 1;
                }
                $('#txt-num').val(val);
                $('#convertable').val(val * 1 + ' Points');
                $('#UserPoints').val(val * 1);
            });

        });
	</script>