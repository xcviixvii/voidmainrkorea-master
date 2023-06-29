

<h2><img src="<?=base_url()?>Images/Leftnav/community_left_title.gif" alt="community"></h2>
<ul>
    <li><a href="#"><img id="sub-nav10" src="<?=base_url()?>Images/Leftnav/Community/freeboard_nav_off.gif" alt="Free Board"></a></li>
    <li><a href="#"><img id="sub-nav40" src="<?=base_url()?>Images/Leftnav/Community/screenshot_nav_on.gif" alt="ScreenShot"></a></li>
    <li><a href="#"><img id="sub-nav50" src="<?=base_url()?>Images/Leftnav/Community/devnote_nav_off.gif" alt="Developer Note"></a></li>
    <li><a href="#"><img id="sub-nav70" src="<?=base_url()?>Images/Leftnav/Community/club_nav_off.gif" alt="Club"></a></li>
    
</ul>


<script type="text/javascript">
    var Category = <?=$this->uri->segment(3)?>;
    $(document).ready(function() {
        if (Category != "0") {
            $('#sub-nav<?=$this->uri->segment(3)?>').attr('src', $('#sub-nav<?=$this->uri->segment(3)?>').attr('src').replace('_off', '_on'));
        }

    });
</script>