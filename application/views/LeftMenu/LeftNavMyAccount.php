

<h2><img src="<?=base_url()?>Images/Leftnav/myaccount_left_title.gif" alt="My Account"></h2>
<ul>
<li><a href="#"><img id="sub-nav1" src="<?=base_url()?>Images/Leftnav/MyAccount/account_nav_off.gif" alt="Account"></a></li>
<li><a href="#"><img id="sub-nav2" src="<?=base_url()?>Images/Leftnav/MyAccount/post_nav_off.gif" alt="mypost"></a></li>
<li id="btnLeftCash"><img id="sub-nav3" src="<?=base_url()?>Images/Leftnav/MyAccount/recharge_nav_off.gif" alt="recharge" class="pointer"></li>
</ul>

<script type="text/javascript">
    var Category = <?=$this->uri->segment(3)?>;
    $(document).ready(function() {
        if (Category != "0") {
            $('#sub-nav<?=$this->uri->segment(3)?>').attr('src', $('#sub-nav<?=$this->uri->segment(3)?>').attr('src').replace('_off', '_on'));
        }
    });

    $('#btnLeftCash').click(function () {
        if (IsAuth == 'False') {
            alert('Please try again after logging in..');
            location.href = '<?=base_url()?>Login';
            return false;
        }
        Common.OpenCenterWindow(500, 412, '<?=base_url()?>ItemShop/TopUpStart', 'Payment', false);
    });
</script>


