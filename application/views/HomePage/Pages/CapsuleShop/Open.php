
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
if($this->Internal_model->GetCapsuleStatus() != 1){
?>
<script> 
	alert("This Module Is Not Available Please Try Again Later...") 
	self.close();
	location.href = '<?=base_url()?>';
</script>
<?php
}

$points = $this->llllllllz_model->getpoints($this->session->userdata('UserID'));
$caps = $this->Internal_model->getcapsuleimage($item[0]['ItemNum']);
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width" />
<title>Capsule Shop</title>
<link rel="shortcut icon" type="image/x-icon" href="<?=base_url()?>favicon.ico">
<link href="<?=base_url()?>capsule/Base.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body oncontextmenu="return false" onselectstart="return false" onselect="return false" ondragstart="return false">
<div id="wrap">

<header id="header">
<h1><a href="<?=base_url()?>CapsuleShop/"><img src="<?=base_url()?>image/capsuleshop/title_shop.png" alt="Capsule Shop"></a></h1>
<aside class="header_btn">
<a id="btnWinner" class="btn_winners" title="Winners">Winners</a>
<a onClick="document.getElementById('wrap_guide').style.display='';" title="Guide" class="btn_guide">Guide</a>
</aside>
</header>


<section id="container">

<h1>OPEN CAPSULE</h1>

<div class="contents">

<embed src="/CapsuleShop/Sound/Open.mp3" autostart="true" hidden="true" loop="false" />
<div class="result">
<div class="message">
<p class="lose">Almost, better luck next time!</p>
<p class="item_name"><?=$caps[0]['ItemName']?></p>
</div>
<div class="lose_bg">
<img id="group-item<?=$item[0]['ItemNum']?>" class="tooltip" src="<?=base_url()?>Uploadfiles/ItemShop/<?=$caps[0]['ItemSS']?>" alt="<?=$caps[0]['ItemName']?>" width="80" height="80" />
</div>
</div>


<div class="btn_group">
<p><a href="<?=base_url()?>CapsuleShop/OpenProcess" title="Open Capsule">Open Capsule</a></p>
<p class="btn_right"><a href="<?=base_url()?>CapsuleShop/" title="Done">Done</a></p>
</div>

</div>


</section>


<footer id="footer">

<div class="my_capsule">
<h2>My Capsule</h2>
<div class="open">
<p id="my-capsule" class="num"><?=((!$points) ? 0:"".(($points[0]['Capsule'] < 1) ? 0 : "".$points[0]['Capsule']."")."")?></p>
<?=((!$points) ? '<p class="btn_empty">Open Capsule</p>':''.(($points[0]['Capsule'] < 1) ? '<p class="btn_empty">Open Capsule</p>':'<p><a href="'.base_url().'CapsuleShop/OpenProcess" title="Open Capsule">Open Capsule</a></p>').'')?>
<!--<p><a href="/CapsuleShop/OpenProcess.aspx" title="Open Capsule">Open Capsule</a></p>-->

</div>
</div>


<div class="my_cash">
<h2>My Cash</h2>
<ul>
<li class="rcoin"><span class="hidden">R-Coin : </span></span><?=((!$points) ? "0":"".$points[0]['VP']."")?></li>
</ul>
</div>

</footer>

</div>

<div id="wrap_winners" style="display:none;">
<div class="blackout"></div>
<div class="lightbox">
<iframe src='about:blank' mce_src='about:blank' scrolling='no' frameborder='0'></iframe>
<div class="container">
<h1>WINNERS</h1>
<div class="contents">
<ul id="winner-list"></ul>
</div>
<div class="btn">
<a onClick="document.getElementById('wrap_winners').style.display='none';" title="Open more" class="btn_open">Close</a>
</div>
</div>
</div>
</div>


<div id="wrap_guide" style="display:none;">
<div class="blackout"></div>
<div class="lightbox">
<iframe src="about:blank" mce_src="about:blank" scrolling="no" frameborder="0"></iframe>
<div class="container">
<h1>GUIDE</h1>
<div class="contents">
<p>There is one Lucky Item among 200 capsules, and the chance to get it is completely random. But don�t be disappointed for not getting the Lucky Item, because you will definitely get an item!<br /><br />
<em>1. Choose the capsule that contains Lucky Item you want to get.<br />
2. Select the number of capsules to buy and the payment method.<br />
3. Press the 'Buy' button to complete your purchase.<br />
4. Press the 'Open Capsule' button to open your capsule(s).</em><br /><br />
? All items obtained from Capsule Shop will be automatically provide to in-game Item Bank.<br />
? Cash used to buy items in Capsule Shop cannot be refunded. <br /><br />
<strong>Who is lucky? Don�t wait no more and try it now!</strong></p>
</div>
<div class="btn">
<a onClick="document.getElementById('wrap_guide').style.display='none';" title="Open more" class="btn_open">Close</a>
</div>
</div>
</div>
</div>


<div id="item-info" style="display:none; position:absolute;">

</div>

<script type="text/javascript" src="<?=base_url()?>Scripts/jQuery/jquery-1.7.1.js"></script>
<script type="text/javascript" src="<?=base_url()?>Common/JS/jQuery/jquery.cookie.js"></script>
<script type="text/javascript"> 
/*       
        document.onkeydown = function(e) {
            key = (e) ? e.keyCode : event.keyCode;

            if (key == 8 || key == 116) {
                if (e) {   //??         
                    e.preventDefault();
                }
                else { //???
                    event.keyCode = 0;
                    event.returnValue = false;
                }
            }
        }
  */      
        $(document).ready(function() {
            $('#btnWinner').click(function() {
                $('#winner-list').empty();
                $.post('<?=base_url()?>capsuleshop/getcapsulewinnerlist', {}, function(data) {
                    if (data != '') {
                        var result = eval('(' + data + ')');

                        for (var i = 0; i < result.length; i++) {
                            //$('#winner-list').append('<li><span class="data">[' + result[i].InsertDate + ']</span> <span class="id">' + result[i].InsertUser + '</span>?? <span class="item">' + result[i].ItemName + '</span>? ???????.</li>');
                            $('#winner-list').append('<li><span class="data">[' + result[i].InsertDate + ']</span> <span class="id">' + result[i].InsertUser + '</span> has won <span class="item">' + result[i].ItemName + '.</span></li>');
                        }
                    }

                    $('#wrap_winners').show();
                });
                //}).error(function(data) { alert(data.responseText); });
            });
        });
    </script>
<script type="text/javascript">
        $(document).ready(function() {
            $(".tooltip").hover(
                function(e) {
                    var id = $(this).attr('id');

                    if (id.split('-')[0] == 'capsule') {
                        var itemNum = id.replace('capsule-item', '');
                        $('#item-info').load('<?=base_url()?>CapsuleShop/GetCapsuleItemInfo/' + itemNum).show();
                    } else {
                        var groupItemNum = id.replace('group-item', '');
                        $('#item-info').load('<?=base_url()?>CapsuleShop/GetCapsuleItemInfo/' + groupItemNum).show();
                    }

                    //$('#list').load('/CapsuleShop/Ajax/GetCapsuleShopList.aspx?ItemNum=' + itemNum);
                    //infoBox.html(html);
                    //infoBox.fadeIn();
                },
                function(e) {
                    $('#item-info').empty();
                }
            ).mousemove(function(e) {
                // ??? ??
                var $e_x = e.pageX;
                var $e_y = e.pageY;

                // ?? ??
                var $tt_x = $('#item-info').outerWidth();
                var $tt_y = $('#item-info').outerHeight();

                // ?? ??
                var $bd_x = $("body").outerWidth();
                var $bd_y = $("body").outerHeight();


                // ??? ?????.
                $('#item-info').css({
                    "top": $e_y + $tt_y > $bd_y ? $e_y - $tt_y : $e_y,
                    "left": $e_x + $tt_x + 20 > $bd_x ? $e_x - $tt_x - 10 : $e_x + 15
                });
            });
        });
    </script>
</body>
</html>