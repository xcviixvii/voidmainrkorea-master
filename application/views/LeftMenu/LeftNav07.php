<h2><img src="<?=base_url()?>Images/Leftnav/data_left_title.gif" alt="Reference Room"></h2>
<ul>
<li><a href="<?=base_url()?>Download"><img id="sub-nav1" src="<?=base_url()?>Images/LeftNav/Library/download_nav_off.gif" alt="
Download"></a></li>
<li><a href="#"><img id="sub-nav2" src="<?=base_url()?>Images/LeftNav/Library/multimedia_nav_off.gif" alt="Multimedia"></a></li>
<li><a href="#"><img id="sub-nav3" src="<?=base_url()?>Images/LeftNav/Library/gallery_nav_off.gif" alt="Gallery"></a></li>
</ul>


<script type="text/javascript">
    var Category = <?=$this->uri->segment(3)?>;
    $(document).ready(function() {
        if (Category != "0") {
            $('#sub-nav'+Category+'').attr('src', $('#sub-nav'+Category+'').attr('src').replace('_off', '_on'));
        }
    });
</script>
