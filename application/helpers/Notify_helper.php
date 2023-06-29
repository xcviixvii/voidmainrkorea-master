<?php
if (! function_exists('notify')){
	function notify($notify){
			if($notify == 'newsadded') { 
				?>
				<script>
				  swal({
					  title: "News",
					  text: "News Added Successfully!",
					  icon: "success",
					  timer: 1000,
					  buttons: false,
					});
				</script>
			<?php
			} elseif($notify == 'newsupdate') { 
				?>
				<script>
				  swal({
					  title: "News",
					  text: "News Update Successfully!",
					  icon: "success",
					  timer: 1000,
					  buttons: false,
					});
				</script>
			<?php
			} elseif($notify == 'SendItemBank') { 
				?>
				<script>
				  swal({
					  title: "Item Bank",
					  text: "Successfully Send To ItemBank!",
					  icon: "success",
					  timer: 1000,
					  buttons: false,
					});
				</script>
			<?php
			} elseif($notify == 'FailedSendItemBank') { 
				?>
				<script>
				  swal({
					  title: "Item Bank",
					  text: "Failed Send To ItemBank!",
					  icon: "error",
					  timer: 1000,
					  buttons: false,
					});
				</script>
			<?php
			} elseif($notify == 'InsertUnique') { 
				?>
				<script>
				  swal({
					  title: "Capsule Shop",
					  text: "Successfully Inserted a Capsule Shop Unique Item",
					  icon: "success",
					  timer: 1000,
					  buttons: false,
					});
				</script>
			<?php
			} elseif($notify == 'FullCapsuleShop') { 
				?>
				<script>
				  swal({
					  title: "Capsule Shop",
					  text: "Capsule Shop Item is Reach the Limit",
					  icon: "error",
					  timer: 2000,
					  buttons: false,
					});
				</script>
			<?php
			} elseif($notify == 'InsertCommon') { 
				?>
				<script>
				  swal({
					  title: "Capsule Shop",
					  text: "Successfully Inserted a Capsule Shop Common Item",
					  icon: "success",
					  timer: 1000,
					  buttons: false,
					});
				</script>
			<?php
			} elseif($notify == 'CapsuleShopDelete') { 
				?>
				<script>
				  swal({
					  title: "Capsule Shop",
					  text: "Item Successfully Deleted",
					  icon: "success",
					  timer: 1000,
					  buttons: false,
					});
				</script>
			<?php
			} elseif($notify == 'ModuleNotAvailable') { 
				?>
				<script>
				  swal({
					  title: "Module Permission",
					  text: "This Module is Disable",
					  icon: "error",
					  timer: 1000,
					  buttons: false,
					});
				</script>
			<?php
			} elseif($notify == 'DeleteSlider') { 
				?>
				<script>
				  swal({
					  title: "Multimedia",
					  text: "Slider Image Successfully Deleted",
					  icon: "success",
					  timer: 1000,
					  buttons: false,
					});
				</script>
			<?php
			} elseif($notify == 'DatabaseBackup') { 
				?>
				<script>
				  swal({
					  title: "Maintenance",
					  text: "Successfully Backup All Database",
					  icon: "success",
					  timer: 1000,
					  buttons: false,
					});
				</script>
			<?php
			}

		
			
		}
	}



if(!function_exists('NotifyAlert')){

  function NotifyAlert($text){
	$CI = &get_instance();

	$notify = array(
		'title'	=> 'Alert',
		'text'	=> $text,
		'type'	=> 'alert',
		'icon'	=> '<i class="fad fa-check" style="font-size:22px;"></i></i>',
		'NotifyType' => 'Common' 
	);
    return $CI->session->set_flashdata($notify);
  }
}


if(!function_exists('NotifyInfo')){

  function NotifyInfo($text){
	$CI = &get_instance();

	$notify = array(
		'title'	=> 'Information',
		'text'	=> $text,
		'type'	=> 'info',
		'icon'	=> '<i class="fad fa-check" style="font-size:22px;"></i></i>',
		'NotifyType' => 'Common' 
	);
    return $CI->session->set_flashdata($notify);
  }
}



	
if(!function_exists('NotifySuccess')){

  function NotifySuccess($text){
	$CI = &get_instance();

	$notify = array(
		'title'	=> 'Success',
		'text'	=> $text,
		'type'	=> 'success',
		'icon'	=> '<i class="fad fa-check" style="font-size:22px;"></i></i>',
		'NotifyType' => 'Common' 
	);
    return $CI->session->set_flashdata($notify);
  }
}


if(!function_exists('NotifyWarning')){

  function NotifyWarning($text){
	$CI = &get_instance();

	$notify = array(
		'title'	=> 'Warning',
		'text'	=> $text,
		'type'	=> 'warning',
		'icon'	=> '<i class="fad fa-exclamation" style="font-size:22px;"></i>',
		'NotifyType' => 'Common' 
	);
    return $CI->session->set_flashdata($notify);
  }
}

if(!function_exists('NotifyError')){

  function NotifyError($text){
	$CI = &get_instance();

	$notify = array(
		'title'	=> 'Error',
		'text'	=> $text,
		'type'	=> 'dangerzone',
		'icon'	=> '<i class="fad fa-times" style="font-size:22px;"></i>',
		'NotifyType' => 'Common' 
	);
    return $CI->session->set_flashdata($notify);
  }
}


if (! function_exists('notifymain')){
	function notifymain($type,$title,$text,$icon){
		?>
			<script>
				notify({
				//alert | success | dangerzone  | warning | info
				type: "<?=$type;?>", 
				title: "<?=$title;?>",
				//custom message
				message: "<?=$text;?>",
				position: {
				//right | left | center
				x: "right", 
				//top | bottom | center
				y: "bottom" 
				},
				icon: '<?=$icon;?>', //<i>
				//normal | full | small
				size: "normal", 
				overlay: false, 
				closeBtn: false, 
				overflowHide: false, 
				spacing: 20, 
				//default | dark-theme
				theme: "default", 
				//auto-hide after a timeout
				autoHide: true, 
				// timeout
				delay: 4000, 
				// callback functions
				onShow: null, 
				onClick: null, 
				onHide: null, 
				//custom template
				template: '<div class="notify"><div class="notify-text"></div></div>'

				});
			</script>
		<?php
	}
}











if(!function_exists('ANotifyError')){

  function ANotifyError($text){
	$CI = &get_instance();

	$notify = array(
		'title'	=> 'Error',
		'text'	=> $text,
		'type'	=> 'error'
	);
	// $this->session->set_flashdata($notify);
    return $CI->session->set_flashdata($notify);
  }
}


if(!function_exists('ANotifyWarning')){

  function ANotifyWarning($text){
	$CI = &get_instance();

	$notify = array(
		'title'	=> 'Warning',
		'text'	=> $text,
		'type'	=> 'warning'
	);
	// $this->session->set_flashdata($notify);
    return $CI->session->set_flashdata($notify);
  }
}


if(!function_exists('ANotifySuccess')){

  function ANotifySuccess($text){
	$CI = &get_instance();

	$notify = array(
		'title'	=> 'Success',
		'text'	=> $text,
		'type'	=> 'success',
		'NotifyType' => 'Common' 
	);
	// $this->session->set_flashdata($notify);
    return $CI->session->set_flashdata($notify);
  }
}


if (! function_exists('Anotifymain')){
	function Anotifymain($title,$text,$type){
		?>
			<script>
			swal({
				title: "<?=$title?>",
				text: "<?=$text?>",
				icon: "<?=$type?>", // success,warning,error
				timer: 2000,
				buttons: false,
			});
			</script>
		<?php
	}
}



if(!function_exists('ANotifyConfirmation')){

  function ANotifyConfirmation($msg,$url){
	$CI = &get_instance();

	$notify = array(
		'msg'	=> $msg,
		'url'	=> $url,
		'NotifyType' => 'YesNo' 

	);
    return $CI->session->set_flashdata($notify);
  }
}


if (! function_exists('ANotifyYesNo')){
	function ANotifyYesNo($msg,$url){
		?>
			<script>
	
        		swal({
                	title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this Data!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                	if (willDelete) {
                    	swal({
                        	title: "Success",
                            text: "<?=$msg?>",
                            icon: "success",
                            type: "success",
                            timer: 1000,
                            buttons: false,
                        }).then(function() {
                        window.location.href = "<?=$url?>";
                        });
                    }
                });
			</script>
		<?php
	}
}





