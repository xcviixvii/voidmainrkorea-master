<title><?=((count($GSet) > 0) ? "".$GSet[0]['ServerTitle']."":"")?> | <?=ucfirst($this->uri->segment(1))?></title>
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
		<span class="page-history-select">SUPPORT</span>
	</section>
	<h3 class="sub-title">
		<img src="<?=base_url()?>Images/SubTitle/Support/support_sub_title.gif" alt="Support" class="left">
	</h3>
	<div class="clear"></div>
    <form method="POST">
     <input type="text" name="TicketTitle" placeholder="Title" value="" style="width: 40%; margin: 5px auto;font-size:16px;height:25px;">
	<textarea id="editor1" class="content" name="content"></textarea>
    <br />
    <input type="image" src="<?=base_url()?>Images/Button/post_btn.gif" alt="btnPost" style="vertical-align:middle;" class="pointer" alt="Post">
    </form>

                    </section>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript">
   
     $(function () {

    CKEDITOR.replace('editor1')
    
    //$('.textarea').wysihtml5()
  })
</script>