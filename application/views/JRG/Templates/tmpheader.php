<!DOCTYPE html>
<html lang="en">
  <head>
    <link rel="shortcut icon" type="image/x-icon" href="<?=base_url()?>favicon.png">
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <!-- <script async src="https://www.googletagmanager.com/gtag/js?id=UA-130582519-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-130582519-1');
    </script> -->

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    
    <link href="<?=base_url()?>Library/lib/fontawesome/css/all.min.css" rel="stylesheet">
    <link href="<?=base_url()?>Library/lib/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="<?=base_url()?>Library/lib/typicons.font/typicons.css" rel="stylesheet">
    <link href="<?=base_url()?>Library/lib/flag-icon-css/css/flag-icon.min.css" rel="stylesheet">
    <link href="<?=base_url()?>Library/lib/line-awesome/css/line-awesome.min.css" rel="stylesheet">
    <link href="<?=base_url()?>Library/lib/quill/quill.snow.css" rel="stylesheet">
    <link href="<?=base_url()?>Library/lib/quill/quill.bubble.css" rel="stylesheet">
    <link href="<?=base_url()?>Library/lib/amazeui-datetimepicker/css/amazeui.datetimepicker.css" rel="stylesheet">
    <link href="<?=base_url()?>Library/lib/jquery-simple-datetimepicker/jquery.simple-dtpicker.css" rel="stylesheet">
    <link href="<?=base_url()?>Library/lib/pickerjs/picker.min.css" rel="stylesheet">
    <link href="<?=base_url()?>Library/lib/select2/css/select2.min.css" rel="stylesheet">
    <!-- azia CSS -->
    <link rel="stylesheet" href="<?=base_url()?>Styles/JRG.css">
    <link rel="stylesheet" href="<?=base_url()?>Library/css/azia.css">



    <script src="<?=base_url()?>Library/lib/jquery/jquery.min.js"></script>
    <script src="<?=base_url()?>Library/lib/jquery-ui/ui/widgets/datepicker.js"></script>
    <script src="<?=base_url()?>Library/lib/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js"></script>
    <script src="<?=base_url()?>Library/lib/jquery-simple-datetimepicker/jquery.simple-dtpicker.js"></script>
    <script src="<?=base_url()?>Library/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?=base_url()?>Library/lib/ionicons/ionicons.js"></script>
    <script src="<?=base_url()?>Library/lib/jquery.flot/jquery.flot.js"></script>
    <script src="<?=base_url()?>Library/lib/jquery.flot/jquery.flot.pie.js"></script>
    <script src="<?=base_url()?>Library/lib/jquery.flot/jquery.flot.resize.js"></script>
    <script src="<?=base_url()?>Library/lib/jquery-sparkline/jquery.sparkline.min.js"></script>
    <script src="<?=base_url()?>Library/js/azia.js"></script>
    <script src="<?=base_url()?>Library/js/Chart.min.js"></script>
    <script src="<?=base_url()?>Library/js/chart.flot.sampledata.js"></script>
    <script src="<?=base_url()?>Library/lib/parsleyjs/parsley.min.js"></script>
    <script src="<?=base_url()?>Library/ckeditor/ckeditor.js"></script>
    <script src="<?=base_url()?>Library/lib/select2/js/select2.min.js"></script>
    <script src="<?=base_url()?>Library/lib/pickerjs/picker.min.js"></script>
    <script src="<?=base_url()?>Library/js/app-calendar.js"></script>
    <script src="<?=base_url()?>Library/js/sweetalert.min.js"></script>
    <style>
      .administrator{
        text-shadow: 0 0 0.2em rgb(75, 0, 130);, 0 0 0.2em rgb(75, 0, 130);,
        0 0 0.2em rgb(75, 0, 130);
        color:rgb(75, 0, 130);
      }
    .maxheight{
      height: 100%;
    }
    </style>
  </head>

  <body class="az-body az-body-sidebar">

    

<?php 
if ($this->session->flashdata('message')){
  notify($this->session->flashdata('message'));  
} 


if($this->session->flashdata('NotifyType') == 'YesNo'){
  $msg  = $this->session->flashdata('msg');
  $url    = $this->session->flashdata('url');
  
  ANotifyYesNo($msg,$url);
}

if($this->session->flashdata('NotifyType') == 'Common'){
  $title  = $this->session->flashdata('title');
  $text   = $this->session->flashdata('text');
  $type   = $this->session->flashdata('type');
  Anotifymain($title,$text,$type); 
}


    $UserTypeID = $this->session->userdata('UserTypeID');
		$ModuleName = $this->uri->segment(2);

		$CheckPermission = $this->Internal_model->CheckPermission($ModuleName,$UserTypeID);
		
		if (!$CheckPermission) {
			$this->session->set_flashdata('message', 'ModuleNotAvailable');
			?>
		 	<script> 
		 		location.href = "<?=base_url()?>adminpanel/Dashboard";
		 	</script>
		 	<?php
		}




?>



