
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
$isunique = $this->Internal_model->getcapsuleimage($unique[0]['ItemNum']);
//var_dump($points);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width" />
<title>Capsule Shop</title>
<link rel="shortcut icon" type="image/x-icon" href="<?=base_url()?>favicon.ico">
<link href="<?=base_url()?>capsule/Base.css" rel="stylesheet" type="text/css" media="all" />

<header id="header">
<h1><a href="/CapsuleShop/"><img src="<?=base_url()?>image/capsuleshop/title_shop.png" alt="Capsule Shop"></a></h1>
<aside class="header_btn">
<a id="btnWinner" class="btn_winners" title="Winners">Winners</a>
<a onClick="document.getElementById('wrap_guide').style.display='';" title="Guide" class="btn_guide">Guide</a>
</aside>
</header>


<section id="container">

<h1>BUY CAPSULE</h1>

<div class="contents">

<div class="purchase">
<p class="capsule_name"><?=$isunique[0]['ItemName']?></p>
<p class="capsule_num"><span class="hidden"></span><span id="remain-capsule" class="num_level1">
    <?=$unique[0]['Remain']?></span>/200</p>

<div class="item_thumb">
<dl>
<dt>Capsule Set contains below Items</dt>
<dd>

<img id="capsule-item<?=$unique[0]['ItemNum']?>" class="tooltip" src="<?=base_url()?>Uploadfiles/ItemShop/<?=$isunique[0]['ItemSS']?>" width="40" height="40" alt="<?=$isunique[0]['ItemName']?>" style="border:1px solid #ffd000;" />

<?php 
    foreach ($capsuleshoplinklist as $row) {
        $rows = $this->Internal_model->getcapsuleimage($row['ItemNum']);
        echo '<img id="group-item'.$row['ItemNum'].'" class="tooltip" src="'.base_url().'Uploadfiles/ItemShop/'.$rows[0]['ItemSS'].'" width="40" height="40" alt="'.$isunique[0]['ItemName'].'" />';
    }
?>
</dd>
</dl>
</div>


<form id="paymentForm" method="post" action="<?=base_url()?>capsuleshop/CompleteProcess">
<input type="hidden" id="hdnItemNum" name="ItemNum" value="<?=$this->uri->segment(3)?>" />
<div class="payment">

<div class="number">
<dl>
<dt>No. of Capsule(s)</dt>
<dd>
<fieldset>
<legend>No. of Capsule(s)</legend>
<ul>
<li><input type="radio" id="rdoCapsuleCount1" name="CapsuleCount" value="1" checked="checked" /><label for="rdoCapsuleCount1">&nbsp;<em>1</em></label></li>
<li><input type="radio" id="rdoCapsuleCount2" name="CapsuleCount" value="5" /><label for="rdoCapsuleCount2">&nbsp;<em>5</em></label></li>
<li><input type="radio" id="rdoCapsuleCount3" name="CapsuleCount" value="10" /><label for="rdoCapsuleCount3">&nbsp;<em>10</em></label></li>
</ul>
</fieldset>
</dd>
</dl>
</div>


<div class="price">
<dl>
<dt>Payment Method</dt>
<dd>
<fieldset>
<legend>Payment Method</legend>
<ul>
<li><input type="radio" id="rdoBuyType1" name="BuyType" value="RCoin" /><label for="rdoBuyType1">&nbsp;<span class="rcoin"><em id="rcoin">20</em> Vote-Coin</span></label></li>
</ul>
</fieldset>
</dd>
</dl>
</div>

</div>
</form>

</div>
<p id="alert" class="alert">
</p>
<div class="btn_group">
<p id="btnOn"><a id="btnBuy" title="Buy">Buy</a></p>
<p id="btnOff" class="btn_off" style="display:none;">Buy</p>
<p class="btn_right"><a href="<?=base_url()?>CapsuleShop/" title="Done">Done</a></p>
</div>

</div>


</section>


<footer id="footer">

<div class="my_capsule">
<h2>My Capsule</h2>
<div class="open">
<p id="my-capsule" class="num"><?=((!$points) ? 0:"".(($points[0]['Capsule'] < 1) ? 0 : "".$points[0]['Capsule']."")."")?></p>
<!--<p id="my-capsule" class="num"><?=((!$points) ? 0:"".(($points[0]['Capsule'] < 1) ? 0 : "".$points[0]['Capsule']."")."")?></p>-->
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
<p>There is one Lucky Item among 200 capsules, and the chance to get it is completely random. But don’t be disappointed for not getting the Lucky Item, because you will definitely get an item!<br /><br />
<em>1. Choose the capsule that contains Lucky Item you want to get.<br />
2. Select the number of capsules to buy and the payment method.<br />
3. Press the 'Buy' button to complete your purchase.<br />
4. Press the 'Open Capsule' button to open your capsule(s).</em><br /><br />
※ All items obtained from Capsule Shop will be automatically provide to in-game Item Bank.<br />
※ Cash used to buy items in Capsule Shop cannot be refunded. <br /><br />
<strong>Who is lucky? Don’t wait no more and try it now!</strong></p>
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
        $(document).keydown(function (event) {
            if (event.keyCode == 123) { // Prevent F12
                return false;
            } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) { // Prevent Ctrl+Shift+I        
                return false;
            }
        });      

        $(document).on("contextmenu", function (e) {        
            e.preventDefault();
        });
        
        document.onkeydown = function(e) {
            key = (e) ? e.keyCode : event.keyCode;

            if (key == 8 || key == 116) {
                if (e) {   //표준         
                    e.preventDefault();
                }
                else { //익스용
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
                            //$('#winner-list').append('<li><span class="data">[' + result[i].InsertDate + ']</span> <span class="id">' + result[i].InsertUser + '</span>님이 <span class="item">' + result[i].ItemName + '</span>에 당첨되었습니다.</li>');
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
        var userRCoin = '<?=((!$points) ? 0 : $points[0]['VP'])?>';
        var userRPoint = '0';
        var userCoupon = '0';
        
        var itemRCoin = '20';
        var itemRPoint = '0';
        var itemCoupon = '0';
        
        var buyCapsuleCount, buyType;
        
        var IsBuy = 0;

        $(document).ready(function() {
            // 첫번째 BuyType 체크
            $('input[name="BuyType"]:first').attr('checked', 'checked');

            // 첫번째 캡슐 수 체크
            buyCapsuleCount = $('input[name="CapsuleCount"]:first').val();

            // 첫번째 BuyType
            buyType = $('input[name="BuyType"]:first').val();

            fnBuyCheck();

            // 구매할 캡슐 수 체크
            $('input[name="CapsuleCount"]').click(function() {
                buyCapsuleCount = $(this).val();
                buyType = $('input[name="BuyType"]:checked').val();

                if (buyType == 'RCoin') {
                    $('#rcoin').text(buyCapsuleCount * itemRCoin);
                    $('#rpoint').text(itemRPoint);
                    $('#coupon').text(itemCoupon);
                } else if (buyType == 'RPoint') {
                    $('#rpoint').text(buyCapsuleCount * itemRPoint);
                    $('#rcoin').text(itemRCoin);
                    $('#coupon').text(itemCoupon);
                } else if (buyType == 'Coupon') {
                    $('#coupon').text(buyCapsuleCount * itemCoupon);
                    $('#rcoin').text(itemRCoin);
                    $('#rpoint').text(itemRPoint);
                }

                fnBuyCheck();
            });

            // 지불 수단 체크
            $('input[name="BuyType"]').click(function() {
                buyCapsuleCount = $('input[name="CapsuleCount"]:checked').val();
                buyType = $(this).val();

                if (buyType == 'RCoin') {
                    $('#rcoin').text(buyCapsuleCount * itemRCoin);
                    $('#rpoint').text(itemRPoint);
                    $('#coupon').text(itemCoupon);
                } else if (buyType == 'RPoint') {
                    $('#rpoint').text(buyCapsuleCount * itemRPoint);
                    $('#rcoin').text(itemRCoin);
                    $('#coupon').text(itemCoupon);
                } else if (buyType == 'Coupon') {
                    $('#coupon').text(buyCapsuleCount * itemCoupon);
                    $('#rcoin').text(itemRCoin);
                    $('#rpoint').text(itemRPoint);
                }

                fnBuyCheck();
            });

            $('#btnBuy').click(function(event) {
                $('.btn_group').hide();
                var isValid = fnBuyCheck();

                if (isValid) {
                    IsBuy = 1;
                    if (IsBuy == 1) {
                        $('#paymentForm').submit();
                    }
                } else {
                    IsBuy = 0;
                }

                event.stopPropagation();
            });

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
                // 이벤트 좌표
                var $e_x = e.pageX;
                var $e_y = e.pageY;

                // 툴팁 좌표
                var $tt_x = $('#item-info').outerWidth();
                var $tt_y = $('#item-info').outerHeight();

                // 본문 좌표
                var $bd_x = $("body").outerWidth();
                var $bd_y = $("body").outerHeight();


                // 툴팁을 이동시킨다.
                $('#item-info').css({
                    "top": $e_y + $tt_y > $bd_y ? $e_y - $tt_y : $e_y,
                    "left": $e_x + $tt_x + 20 > $bd_x ? $e_x - $tt_x - 10 : $e_x + 15
                });
            });

            setInterval('fnGetRemainCapsuleCount()', 1000);
        });
        
        // 구매 체크
        var fnBuyCheck = function() {
            var isValid = true;
            if (buyType === 'RCoin') {
                if ((buyCapsuleCount * itemRCoin) > userRCoin) {
                    $('#alert').text('Not enough R-Coin!');
                    $('#btnOn').hide();
                    $('#btnOff').show();
                    isValid = false;
                } else {
                    $('#alert').text('');
                    $('#btnOn').show();
                    $('#btnOff').hide();
                    isValid = true;
                }
            } else if (buyType === 'RPoint') {
                if ((buyCapsuleCount * itemRPoint) > userRPoint) {
                    $('#alert').text('Not enough R-Point!');
                    $('#btnOn').hide();
                    $('#btnOff').show();
                    isValid = false;
                } else {
                    $('#alert').text('');
                    $('#btnOn').show();
                    $('#btnOff').hide();
                    isValid = true;
                }
            } else if (buyType === 'Coupon') {
                if ((buyCapsuleCount * itemCoupon) > userCoupon) {
                    $('#alert').text('No Coupon!');
                    $('#btnOn').hide();
                    $('#btnOff').show();
                    isValid = false;
                } else {
                    $('#alert').text('');
                    $('#btnOn').show();
                    $('#btnOff').hide();
                    isValid = true;
                }
            }

            return isValid;
        }
        
        // 현재 캡슐이 남은 수 1초마다 갱신
        var fnGetRemainCapsuleCount = function() {
            var itemNum = <?=$this->uri->segment(3)?>;
            $.post('<?=base_url()?>CapsuleShop/GetRemainCapsuleCount/', + ItemNum, function(data) {
                $('#remain-capsule').text(data);
            });
            //}).error(function (data) { alert(data.responseText); });
        }
    </script>
</body>
</html>