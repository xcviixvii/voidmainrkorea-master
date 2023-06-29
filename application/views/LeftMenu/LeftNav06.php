

<h2><img src="<?=base_url()?>Images/Leftnav/ranking_left_title.gif" alt="ranking"></h2>
<ul>
    <li><a href="<?=base_url()?>Ranking"><img id="sub-nav1" src="<?=base_url()?>Images/Leftnav/Ranking/totalranking_nav_off.gif" alt="Server Ranking"></a></li>
    <li><a href="#"><img id="sub-nav2" src="<?=base_url()?>Images/Leftnav/Ranking/schoolranking_nav_off.gif" alt="Academy Ranking"></a></li>
    <li><a href="#"><img id="sub-nav3" src="<?=base_url()?>Images/Leftnav/Ranking/classranking_nav_off.gif" alt="Department ranking"></a></li>    
  
</ul>



<script type="text/javascript">
    var Category = <?=$this->uri->segment(3)?>;
    $(document).ready(function() {
        if (Category != "0") {
            $('#sub-nav'+Category+'').attr('src', $('#sub-nav'+Category+'').attr('src').replace('_off', '_on'));
        }
    });
</script>
