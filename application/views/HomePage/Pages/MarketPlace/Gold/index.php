<title><?=((count($GSet) > 0) ? "".$GSet[0]['ServerTitle']."":"")?> | GoldMarket</title>
 <?php
    $stat = $this->session->userdata('stat');
    $amount = $this->session->userdata('amount');

    //echo $stat;
    //echo $amount;
?>


<?php
if(!$this->session->userdata('UserID')){
    NotifyError('please try again after logging in..');
    ?>
	<script> 
	// alert("please try again after logging in..") 
	location.href = '<?=base_url()?>';
	</script>
 	<?php
}
?>



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
		<span class="page-history-select">GOLD MARKET</span>
	</section>
	<h3 class="sub-title">
		<img src="<?=base_url()?>Images/SubTitle/GoldMarket/GoldMarket_sub_title.gif" alt="Gold Market" class="left">
	</h3>
	<div class="clear"></div>
	
	<div class="ranking-search-wrap">
		
		<fieldset class="ranking-search">
			<form method='POST' action="<?=base_url()?>GoldMarket/Filter" onsubmit="return fnSubmit(this)>
            
            <legend>Search Goldmarket</legend>
			<span class="search-title">
            </span>
            <select id="selServer" name="selStat" style="width:100px;">
            <option value="All">All</option>
            <option <?=(($stat == "WHERE Stat = 1 " || $stat == "WHERE Stat = 1 AND") ? 'selected':'')?> value="1">Active</option>
            <option <?=(($stat == "WHERE Stat = 0 " || $stat == "WHERE Stat = 0 AND") ? 'selected':'')?> value="0">Sold</option>
            </select>
            <select id="selSearchFlag" name="selAmount" style="width:200px;">
                <option value="All">All Amount</option>
                <option <?=(($amount == " Gold >= 1000000") ? 'selected':'')?> value="1000000">1,000,000+</option>
                <option <?=(($amount == " Gold >= 10000000") ? 'selected':'')?> value="10000000">10,000,000+</option>
                <option <?=(($amount == " Gold >= 50000000") ? 'selected':'')?> value="50000000">50,000,000+</option>
                <option <?=(($amount == " Gold >= 100000000") ? 'selected':'')?> value="100000000">100,000,000+</option>
                <option <?=(($amount == " Gold >= 500000000") ? 'selected':'')?> value="500000000">500,000,000+</option>
            </select>
				<div class="right">
                    <div class="search-title"></div>
                    <input type="image" src="<?=base_url()?>Images/Button/search_btn.gif" alt="btnSearch"
    						style="vertical-align:middle;" class="pointer" alt="Find">
				
					<a href="<?=base_url()?>GoldMarket"><img src="<?=base_url()?>Images/Button/reset_btn.gif" id="btnReset" class="pointer" alt="Reset"></a>
				</div>
				<div class="clear"></div>
				</form>
		</fieldset>
		
	</div>

	<p class="daily-rank-info">
	</p>
	
	<table class="ranking">
	<caption>Gold Seller List</caption>
	<colgroup>
		<col style="width:170px;">
		<col style="width:170px;">
		<col style="width:170px;">
		<col style="width:100px;">
		
	</colgroup>
	<thead>
	<tr>
		<th scope="col" style="border-left:1px solid black;"><label style="font-size: 12px; color:white; font-weight: normal;">Name</label></th>
		<th scope="col"><label style="font-size: 12px; color:white; font-weight: normal;">Gold</label></th>
		<th scope="col"><label style="font-size: 12px; color:white; font-weight: normal;">Price</label></th>
        <th scope="col"><label style="font-size: 12px; color:white; font-weight: normal;">Status</label></th>
        <th scope="col" class="last" style="border-right:1px solid black;"></th>
	</tr>
	</thead>
	<tbody>
    <?php
    $ctr = 1;
    foreach ($records as $row) {
    $a = $this->llllllllz_model->GetChaNum($row['ChaNum']); 
    // $UserNum = $this->Internal_model->GetUserNum($row['ChaNum']);
    // echo '<pre>';
    // print_r($row);
    // echo '</pre>';
    //echo $a[0]['UserNum'];

    echo '
    <form method="post" action="'.base_url().'GoldMarket/userTransdoConfirm/'.$row['marketid'].'">
    <tr>
    <td class="name" style="padding: 10px 0 8px 0; border-bottom: 1px solid #151515;"><center>'.$a[0]['ChaName'].'</center></td>';
    echo '<td style="padding: 10px 0 8px 0; border-bottom: 1px solid #151515;"><b style="color:#ff8c00; ">'.formatnumber($row['Gold']).'</b></td>';
    echo '<td class="points" style="padding: 10px 0 8px 0; border-bottom: 1px solid #151515;">
    <b>EP : </b>
    <span style="color:#1e90ff;font-weight:bold;">'.$row['EPoints'].'</span>
    </td>';
    echo '<td class="status" style="padding: 10px 0 8px 0; border-bottom: 1px solid #151515;">
    '.(($row['Stat'] == 1) ? "<label style='color:#228b22;font-weight:bold;'>Active</label>":"<label style='color:#ff4500; font-weight:bold;'>Sold</label>").'
    </td>';
    echo '<td class="name" style="padding: 10px 0 8px 0; border-bottom: 1px solid #151515;">
    <center>
    '.(($row['Stat'] == 1) ? "".(($this->session->userdata('UserID') != $a[0]['UserNum']) ? "
        <button type='submit' name='buypost' id='btnfix' class='btnmyaccount pointer' style='width: 40px;'>Buy</button>":"
        <button type='submit' name='cancelpost' id='btnfix' class='btnmyaccount pointer' style='width: 40px;'>Cancel</button>")."":'').'
    </center>
    </td></form>';
    $ctr++;
    }
    ?>
    </tr>

    </tbody>
	</table>
	<div style="align-items: center;display: flex;justify-content: center;">
                   <?=(($pages == 1) ? "" :$pages)?>
           </div>

                    </section>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
   
   