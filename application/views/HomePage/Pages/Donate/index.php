<title><?=((count($GSet) > 0) ? "".$GSet[0]['ServerTitle']."":"")?> | Donate</title>
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
		<span class="page-history-select">DONATE</span>
	</section>
	<h3 class="sub-title">
		<img src="<?=base_url()?>Images/SubTitle/Donate/donate_sub_title.gif" alt="Donate" class="left">
	</h3>
	<div class="clear"></div>
	
    <?php

    foreach ($dnt as $row) {
        $string = str_replace("&nbsp;", " ", $row['donatecontent']);
        echo '
        <h1>'.$row['donatetitle'].'</h1>
        <br />
        <fieldset class="donatecontent">'.$string.'</fieldset>
        <br />
        ';
    }

    ?>
	<section class="paging">
	</section>

                    </section>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
   
   