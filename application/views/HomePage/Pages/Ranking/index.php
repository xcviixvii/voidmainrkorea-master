<title><?=((count($GSet) > 0) ? "".$GSet[0]['ServerTitle']."":"")?> | Ranking</title>
<body  class="sub-body">
<div id="dialog" style='display:none; text-align:center; overflow:hidden;' >
    <iframe id="myIframe" src="/Popup/popup_pw_change.html" style="width:390px; height:270px; border:0"></iframe>
</div>
    <div class="in-body6">
        <div class="bottom-body">
            <div id="wrapper">
                <?php
                renderview('HomePage/Templates/tmpheader');
                ?>
                <div id="container">
                    <nav id="nav">                
                        <section id="left-nav">&nbsp;</section>
                    
                    </nav>
                    <section id="content">
                        
    <section class="page-history">
		HOME
		<img src="<?=base_url()?>Images/ico_navi.gif" alt="">
		RANKING
		<img src="<?=base_url()?>Images/ico_navi.gif" alt="">
		<span class="page-history-select">SERVER RANKING</span>
	</section>
	<h3 class="sub-title">
		<img src="<?=base_url()?>Images/SubTitle/Ranking/totalranking_sub_title.gif" alt="Comprehensive ranking" class="left">
	</h3>
	<div class="clear"></div>
	<!--
	<div class="ranking-search-wrap">
		
		<fieldset class="ranking-search">
			<legend>Search Ranking</legend>
			<div class="search-title">
				<img src="<?=base_url()?>Images/Board/search_title.gif" alt="Ranking Search">
			</div>
		
				
				<div class="select-search-wrapper left">
					<div id="select-school" class="select">
						<span>School</span>
						<input type="hidden" id="selSchool" value="100">
					</div>
					<ul id="select-school-list" class="select-list display-none">
					<li><a class="selected" title="100">&nbsp;School</a></li>
					<li><a class="selected" title="0">&nbsp;<img src="<?=base_url()?>Images/Icon/00.gif" alt="SG"> Sacred Gate</a></li>
					<li><a class="selected" title="1">&nbsp;<img src="<?=base_url()?>Images/Icon/01.gif" alt="MP"> Mystic Peak</a></li>
					<li><a class="selected" title="2">&nbsp;<img src="<?=base_url()?>Images/Icon/02.gif" alt="PH"> Pheonix</a></li>
					</ul>
				</div>
				
				<div class="select-search-wrapper left">
					<div id="select-class" class="select">
						<span>Class</span>
						<input type="hidden" id="selClass" value="0">
					</div>
					<ul id="select-class-list" class="select-list display-none">
					<li><a class="selected" title="0">&nbsp;All</a></li>
					<li><a class="selected" title="1">&nbsp;<img src="<?=base_url()?>Images/icon/brawler_emblem_icon.gif"> Brawler</a></li>
					<li><a class="selected" title="2">&nbsp;<img src="<?=base_url()?>Images/icon/sword_emblem_icon.gif"> Swordsman</a></li>
					<li><a class="selected" title="3">&nbsp;<img src="<?=base_url()?>Images/icon/archer_emblem_icon.gif"> Archer</a></li>
					<li><a class="selected" title="4">&nbsp;<img src="<?=base_url()?>Images/icon/shamen_emblem_icon.gif"> Shaman</a></li>
					<li><a class="selected" title="5">&nbsp;<img src="<?=base_url()?>Images/icon/extreme_emblem_icon.gif"> Extreme</a></li>
					<li><a class="selected" title="6">&nbsp;<img src="<?=base_url()?>Images/icon/scientist_emblem_icon.gif"> Science</a></li>
					<li><a class="selected" title="7">&nbsp;<img src="<?=base_url()?>Images/icon/assassin_emblem_icon.gif"> Assasin</a></li>
					<li><a class="selected" title="8">&nbsp;<img src="<?=base_url()?>Images/icon/magician_emblem_icon.gif"> Magician</a></li>
					<li><a class="selected" title="9">&nbsp;<img src="<?=base_url()?>Images/icon/shaper_emblem_icon.gif"> Shaper</a></li>
					</ul>
				</div>

				<div class="right">
					<div class="search-title"></div>
					<img src="<?=base_url()?>Images/Button/search_btn.gif" id="btnSearch" class="pointer" alt="검색">
					<img src="<?=base_url()?>Images/Button/reset_btn.gif" id="btnReset" class="pointer" alt="초기화">
				</div>
				<div class="clear"></div>
				
		</fieldset>
		
	</div>

	<p class="daily-rank-info">
	</p>
	-->
	<table class="ranking">
	<caption>Ranking List</caption>
	<colgroup>
		<col style="width:40px;">
		<col style="width:150px;">
		<col style="width:120px;">
		<col style="width:100px;">
		<col style="width:50px;">
		<col style="width:50px;">
		<col style="width:90px;">
		
	</colgroup>
	<thead>
	<tr>
		<th scope="col" style="border-left:1px solid black;"><label style="font-size: 12px; color:white; font-weight: normal;">Rank</label></th>
		<th scope="col"><label style="font-size: 12px; color:white; font-weight: normal;">Name</label></th>
		<th scope="col"><label style="font-size: 12px; color:white; font-weight: normal;">Club</label></th>
		<th scope="col"><label style="font-size: 12px; color:white; font-weight: normal;">School</label></th>
		<th scope="col"><label style="font-size: 12px; color:white; font-weight: normal;">Class</label></th>
		<th scope="col"><label style="font-size: 12px; color:white; font-weight: normal;">Level</label></th>
		<th scope="col" class="last" style="border-right:1px solid black;"><label style="font-size: 12px; color:white; font-weight: normal;">K / D</label></th>
	</tr>
	</thead>
	<tbody>
		<?php
		$ctr = 1;
		foreach ($ranking as $row) {
			echo '<tr>
					<td style="padding: 10px 0 8px 0;border-bottom:1px solid #ccc;">'.$ctr.'</td>
					<td style="padding: 10px 0 8px 0;border-bottom:1px solid #ccc;" class="name">'.$row['ChaName'].'</td>
					<td style="padding: 10px 0 8px 0;border-bottom:1px solid #ccc;">';
					$getBadge = $this->llllllllz_model->getGuild($row['GuNum']); 
				      $GuNum = $this->encrypt->encode($row['GuNum']);
				      ?>
				                       <table>
				                        <tr>
				                        <td class="badge"><?=((!$getBadge) ? '':''.GenerateBadge(bin2hex($getBadge[0]['GuMarkImage'])).'')?></td>
				                        <td class="badge">&nbsp; <?=((!$getBadge) ? '<a style="cursor:default;color:#808080;text-decoration:none;">No Guild</a>':'<a href="#" style="text-decoration:none;">'.$getBadge[0]['GuName'].'</a>')?></td>
				                        </tr>
				                        </table>
				                        
				    <?php 
					echo '</td>
					<td style="padding: 10px 0 8px 0;border-bottom:1px solid #ccc;" class="school">'.Fullschool($row['ChaSchool']).'</td>
					<td style="padding: 10px 0 8px 0;border-bottom:1px solid #ccc;" class="class">'.ClassIMG($row['ChaClass']).'</td>
					<td style="padding: 10px 0 8px 0;border-bottom:1px solid #ccc;" class="level">'.$row['ChaLevel'].'</td>
					<td style="padding: 10px 0 8px 0;border-bottom:1px solid #ccc;" class="exp">'.$row['ChaPkWin'] .'/'. $row['ChaPkLoss'].'</td>
					
				</tr>';
		$ctr++;
		}
		?>
	</tbody>
	</table>
	<section class="paging">
	</section>

                    </section>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
   
   