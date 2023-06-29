<html lang="en">
<head>
    <meta charset="utf-8">
    <!-- CSS HERE -->
    <link rel="shortcut icon" type="image/x-icon" href="<?=base_url()?>favicon.ico">

    <link href="<?=base_url()?>Library/lib/fontawesome/css/all.min.css" rel="stylesheet">
    <link rel="Stylesheet" type="text/css" href="<?=base_url()?>Styles/Basic.css" media="all">
    <link rel="Stylesheet" type="text/css" href="<?=base_url()?>Styles/Plugin/jquery.autocomplete.css" media="all">    
    <link rel="Stylesheet" type="text/css" href="<?=base_url()?>Styles/Plugin/wt-rotator.css" media="all">
    <link rel="stylesheet" href="<?=base_url()?>Styles/JRG.css">
    <script type="text/javascript" src="<?=base_url()?>Scripts/Common.js"></script>
	  <!-- SCRIPT HERE -->
	  <script type="text/javascript" src="<?=base_url()?>Js/Common.js"></script>
    <script type="text/javascript" src="<?=base_url()?>Js/prototype.js"></script>
    <script type="text/javascript" src="<?=base_url()?>html/nowcom/bg_control.js"></script>
    <script type="text/javascript" src="<?=base_url()?>Library/notify/jquery-1.8.2.min.js"></script>
    <!-- <script type="text/javascript" src="<?=base_url()?>Scripts/AjaxSetup.js"></script> -->
    <script type="text/javascript" src="<?=base_url()?>Scripts/Plugin/jquery.slides.min.js"></script>    
    <script type="text/javascript" src="<?=base_url()?>Scripts/Plugin/jquery.autocomplete.min.js"></script>
    <script type="text/javascript" src="<?=base_url()?>Scripts/Plugin/jquery.cookie.js"></script>
    <script type="text/javascript" src="<?=base_url()?>Scripts/Plugin/jquery.simplemodal.js"></script>
    <script type="text/javascript" src="<?=base_url()?>Scripts/Plugin/jquery.wt-rotator.min.js"></script>
    <script type="text/javascript" src="<?=base_url()?>Scripts/Plugin/jquery.easing.1.3.js"></script>    
    <script type="text/javascript" src="<?=base_url()?>Scripts/JRGMain.js"></script>
    <script type="text/javascript" src="<?=base_url()?>Scripts/RanLogin.js"></script>
    <script src="<?=base_url()?>Library/ckeditor/ckeditor.js"></script>
    <!--
    <script type="text/javascript" src="<?=base_url()?>Scripts/RanLogin.js"></script>
    -->
    <!-- <script data-ad-client="ca-pub-2624054502426779" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> -->

    <link href="<?=base_url()?>Library/Notify/jquery.notify.css" type="text/css" rel="stylesheet" />

    <script type="text/javascript" src="<?=base_url()?>Library/notify/jquery.notify.min.js"></script>
    
</head>
<body>

<?php
if($this->session->flashdata('NotifyType') == 'Common'){
  $title  = $this->session->flashdata('title');
  $text   = $this->session->flashdata('text');
  $type   = $this->session->flashdata('type');
  $icon   = $this->session->flashdata('icon');
  notifymain($type,$title,$text,$icon); 
}
?>



