

<h2><img src="<?=base_url()?>Images/Leftnav/news_left_title.gif" alt="News"></h2>
<ul>
<li><a href="<?=base_url()?>News/Notice"><img id="sub-nav10" src="<?=base_url()?>Images/Leftnav/Notice/notice_nav_off.gif" alt="notice"></a></li>
<li><a href="<?=base_url()?>News/Update"><img id="sub-nav20" src="<?=base_url()?>Images/Leftnav/Notice/update_nav_off.gif" alt="update"></a></li>
<li><a href="<?=base_url()?>News/Event"><img id="sub-nav30" src="<?=base_url()?>Images/Leftnav/Notice/event_nav_off.gif" alt="event"></a></li>

</ul>


<script type="text/javascript">
    var Category = <?=$this->uri->segment(3)?>;
    $(document).ready(function() {
        if (Category != "0") {
            $('#sub-nav'+Category+'').attr('src', $('#sub-nav'+Category+'').attr('src').replace('_off', '_on'));
        }
    });
</script>