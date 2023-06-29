
<style> 
ul li a:hover{
    color:#cc0000;
}
</style>

<h2><img src="<?=base_url()?>Images/Leftnav/itemshop_left_title.gif" alt=""></h2>
<ul>
<li><a href="<?=base_url()?>ItemShop"><img id="sub-nav1" src="<?=base_url()?>Images/Leftnav/Itemshop/itemshop_nav_off.gif" alt="Item Shop"></a></li>
<li><a href="<?=base_url()?>MileageShop"><img id="sub-nav2" src="<?=base_url()?>Images/Leftnav/Itemshop/mileageshop_nav_off.gif" alt="Mileage Shop"></a></li>
<!-- <li><a href=""><img id="sub-nav6" src="<?=base_url()?>Images/Leftnav/Itemshop/capsuleshop_nav_off.gif" alt="Capsule event"></a></li> -->
<li class="left-nav">
    <a stype="cursor:pointer;" id="btnCartList"><img id="sub-nav3" src="<?=base_url()?>Images/Leftnav/Itemshop/account_nav_off.gif" alt="My shopping information"></a>
	<ul>
	<li id="depth1"><a style="cursor:pointer;" id="btnCartList2">Shopping Basket</a></li>
	<!-- <li id="depth2"><a href="/ItemShop/WishList.aspx">- 찜목록</a></li> -->
	<li id="depth3"><a style="cursor:pointer;" id="btnBuyHistory">Buy List</a></li>
	</ul>
</li>
<li><a href="<?=base_url()?>ItemShop/ClaimEvent"><img id="sub-nav5" src="<?=base_url()?>Images/Leftnav/Itemshop/claimevent_nav_off.gif" alt="Claim Event"></a></li>

</ul>

<div class="mycash">
	<h2>
		<span>My Cash</span>
	</h2>
	<dl>
	<dt class="cash"></dt>
	<dd style="color: rgb(30, 144, 255);">
		<strong><?=((!$points) ? "0":"".formatnumber3($points[0]['EPoint'])."")?></strong>
	</dd>
	<dt class="mileage"></dt>
	<dd style="color:yellow;">
		<strong>0</strong>
	</dd>
	</dl>
	<img src="<?=base_url()?>Images/ItemShopNew/recharge_btn3.gif" id="btnLeftCash" class="pointer" alt="Recharge">
</div>
<?php
    if($this->uri->segment(3) == 4){
        $Ctg = 3;
    } else {
        $Ctg = $this->uri->segment(3);
    }
?>
<script type="text/javascript">
    $(document).ready(function() {
        let Category = <?=$Ctg?>;
        if (Category != '0') {
            $('#sub-nav' + Category).attr('src', $('#sub-nav'  + Category).attr('src').replace('_off', '_on'));
            
            if(<?=$this->uri->segment(3);?> == 3){
                $('#depth1').addClass('select-menu');
            } else if(<?=$this->uri->segment(3);?> == 4){
                $('#depth3').addClass('select-menu');
            } 
        }

        $('#btnLeftCash').click(function() {
            if (IsAuth == 'False') {
                alert('Please try again after logging in..');
                location.href = '<?=base_url()?>Login';
                return false;
            }
            Common.OpenCenterWindow(500, 412, '<?=base_url()?>ItemShop/TopUpStart', 'Payment', false);
        });


        $('#btnCartList').click(function() {
            if (IsAuth == 'False') {
                alert('Please try again after logging in..');
                location.href = '<?=base_url()?>Login';
                return false;
            } else {
                location.href = '<?=base_url()?>ItemShop/CartList';
                return true;
            }
        });

        $('#btnCartList2').click(function() {
            if (IsAuth == 'False') {
                alert('Please try again after logging in..');
                location.href = '<?=base_url()?>Login';
                return false;
            } else {
                location.href = '<?=base_url()?>ItemShop/CartList';
                return true;
            }
        });

        $('#btnBuyHistory').click(function() {
            if (IsAuth == 'False') {
                alert('Please try again after logging in..');
                location.href = '<?=base_url()?>Login';
                return false;
            } else {
                location.href = '<?=base_url()?>ItemShop/BuyHistory';
                return true;
            }
        });

        
    });
</script>
