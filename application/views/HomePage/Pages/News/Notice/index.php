<title><?=((count($GSet) > 0) ? "".$GSet[0]['ServerTitle']."":"")?> | Notice</title>
<body  class="sub-body">
    <div class="in-body1">
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
		NEWS
		<img src="<?=base_url()?>Images/ico_navi.gif" alt="">
		<span class="page-history-select">NOTICE</span>
	</section>
	<h3 class="sub-title">
		<img src="<?=base_url()?>Images/SubTitle/News/notice_sub_title.gif" alt="Notice">
	</h3>
	

    <table class="board-list">
	<caption>Board List</caption>
	<colgroup>
		<col style="width:50px;">
		<col style="width:500px;">
		<col style="width:110px;">
		<col style="width:40px;">
	</colgroup>
	<thead>
	<tr>
		<th scope="col"><img src="<?=base_url()?>Images/Board/no_th.gif" alt="number"></th>
		<th scope="col"><img src="<?=base_url()?>Images/Board/subject_th.gif" alt="title"></th>
		<th scope="col"><img src="<?=base_url()?>Images/Board/date_th.gif" alt="Date Created"></th>
		<th scope="col" class="last"><img src="<?=base_url()?>Images/Board/view_th.gif" alt="Views"></th>
	</tr>
	</thead>
	<tbody>
        
        <?php
            foreach ($highlights as $rows) {
                ?>
                <tr class="notice">
                    <td class="no"><img src="<?=base_url()?>Images/Icon/notice_board_icon.gif" alt="Notice"></td>
                    <td class="subject"><strong><a href="<?=base_url()?>News/View/<?=$rows['newsid']?>"><?=$rows['newstitle'];?></a></strong></td>   
                    <td class="date"><?=formatdate51($rows['datestamp']);?></td>
                    <td class="view"><?=$rows['views']?></td>
                </tr>
                <?php
            }
        ?>
        <?php
            foreach ($notice as $row) {
                ?>
                <tr>
                    <td class="no"><?=$row['newsid'];?></td>
                    <td class="subject"><a href="<?=base_url()?>News/View/<?=$row['newsid']?>"><?=$row['newstitle'];?></a></td>
                    <td class="date"><?=formatdate51($row['datestamp']);?></td>
                    <td class="view"><?=$row['views']?></td>
                </tr>

                <?php
            }
        ?>
    

	



	</tbody>
	</table>
    <div style="align-items: center;display: flex;justify-content: center;">
        <center>
        <?=$pages?>
        </center>
    </div>
    <br />
	
	<fieldset class="board-search">
	    <legend>Bulletin board search</legend>
	    <section>
       
            <div class="clear"></div>
            
        </section>
    </fieldset>

                    </section>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
   
