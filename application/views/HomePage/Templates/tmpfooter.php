   <?php
	$footer = $this->Internal_model->GetPanelSettings();
   ?>
   <footer id="footer">
    <img src="<?=base_url()?>Images/footer.gif">
	<div style="margin:-60px auto; font-weight: bold;">
    <label><img style="width: 30px;" src="<?=base_url()?>Images/Navigation/logo.png"><br /><?=((count($footer) > 0) ? "".$footer[0]['Copyright']."":"")?></label>
	</div>
	<div style="margin: -170px 740px 0 0;"></div>
    </footer>
    </body>
</html>

<?php
						$points = $this->llllllllz_model->getpoints($this->session->userdata('UserID'));
						if($points){
							if($points[0]['ChaNum'] == NULL){
								
								$ChaInfo2 = $this->llllllllz_model->getChaInfo($this->session->userdata('UserID'));
								var_dump($ChaInfo2);
								if(!$ChaInfo2){
									$ChaNum = 0;
									$GuNum = -1;
								} else {
									$ChaNum = $ChaInfo2[0]['ChaNum'];
									if($ChaInfo2[0]['GuNum'] == 0){
										$GuNum = -1;
									} else {
										$GuNum = $ChaInfo2[0]['GuNum'];
									}
								}
							} else {
                            	$ChaInfo = $this->llllllllz_model->getChaNumInfo($points[0]['ChaNum']);

								if(!$ChaInfo){
									$ChaNum = 0;
									$GuNum = -1;
								} else {
									$ChaNum = $ChaInfo[0]['ChaNum'];
									if($ChaInfo[0]['GuNum'] == 0){
										$GuNum = -1;
									} else {
										$GuNum = $ChaInfo[0]['GuNum'];
									}
								}
							}
                        } else {
                            $ChaInfo = $this->llllllllz_model->getChaInfo($this->session->userdata('UserID'));
                            if(!$ChaInfo){
								$ChaNum = 0;
								$GuNum = -1;
                            } else {
								$ChaNum = $ChaInfo[0]['ChaNum'];
								if($ChaInfo[0]['GuNum'] == 0){
									$GuNum = -1;
								} else {
									$GuNum = $ChaInfo[0]['GuNum'];
								}
								
                            }
						}
?>
<script type="text/javascript">
		
		var IsAuth = '<?=(($this->session->userdata('IsAuth')) ? ''.$this->session->userdata('IsAuth').'':"False")?>';
		var UserID = '<?=(($this->session->userdata('UserID')) ? ''.VoidEncrypter2($this->session->userdata('UserID')).'':"")?>';
		var ChaNum = '<?=VoidEncrypter2($ChaNum)?>';
		var GuNum = '<?=VoidEncrypter2($GuNum)?>';
	    var base_url = '<?=base_url()?>';
	   

	    function fnLoginChech() {
	        if (IsAuth == 'False') {
	        	alert('please try again after logging in..');
	            location.href = '<?=base_url()?>Login';
	            	<?php
					if($this->uri->segment(3) != ""){
						$this->session->set_userdata('path', ''.base_url().''.$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'');
					} elseif($this->uri->segment(2) != ""){
						$this->session->set_userdata('path', ''.base_url().''.$this->uri->segment(1).'/'.$this->uri->segment(2).'');
					} else {
						$this->session->set_userdata('path', ''.base_url().''.$this->uri->segment(1).'');
					}
	            	?>
	            return false;
	        } else {
	            return true;
	            }
			}

		
	</script>
	<?php
		if($this->uri->segment(2) == 'Page') {
			$Mod = $this->uri->segment(1);
			$Sub = NULL;
		} elseif($this->uri->segment(2) == 'ItemFind'){
			$Mod = $this->uri->segment(1);
			$Sub = NULL;
		} elseif($this->uri->segment(2)){
			if($this->uri->segment(2) == 'View'){
				if($data[0]['type'] == 1){
					$Sub = 10;
				} elseif($data[0]['type'] == 2){
					$Sub = 20;
				} elseif($data[0]['type'] == 3){
					$Sub = 30;
				} else {
					$Sub = NULL;
				}
			} else {
				$Sub = NULL;
			}
			$Mod = $this->uri->segment(2);
		} else {
			$Mod = $this->uri->segment(1);
			$Sub = NULL;
		}

		
	?>
  
	
	<script type="text/javascript">
	$(document).ready(function() {
		$('#left-nav').load('<?=base_url()?>LeftMenu/<?=LeftMenu($Mod,$Sub)?>'); //, 'Category=1'
	});
	</script>





<script type="text/javascript" src="https://connect.facebook.net/en_US/all.js"></script>

<script>

//$("#Share1").click(function() {
function ShareMoto(e){
	let Url = $('#Vote' + e).attr('rel');
	<?php
	
	if(!$this->session->userdata('UserID')){
				?>
					alert("please try again after logging in..") 
					self.close();
					location.href = '<?=base_url()?>Login';
				<?php
	} else {
	?>

	FB.ui({
        method: 'feed',
        name: 'Trident Ran Online',
        link: Url,
        picture: '',
        caption: 'Trident Ran Online',
        description: 'Trident Ran Online Ep7 6class.'
    },
     function(response) {
        if (response) {
		alert("Post was published."); 
		// location.href = '<?php echo base_url(); ?>Homepage/InsertSharePoints/' + e;
        } else {
		alert('Post was not published.');
		//alert("Post was published."); 
		//location.href = '<?php echo base_url(); ?>Homepage/InsertSharePoints/' + e;
        }
    });
	<?php
	}
	?>

};




	window.fbAsyncInit = function() {     
		FB.init({
			appId      : '274013880620816',
			status     : true,
			xfbml      : true,
			version: 'v3.1'
		});

		// Code in here will run once FB has been initialised

		function FB_post_feed(method,name,link,picture,caption,description){
			FB.ui({
				method: method,
				name: name,
				link: link,
				picture: picture,
				caption: caption,
				description: description
			});
		}
	};

</script>
<?php
if($this->Internal_model->GetFacebookMessengerPlugins() == 0 ){
	echo $this->Internal_model->GetFacebookMessengerPlugins();
}
?>



<script>
var d = new Date();
var n = d.getFullYear();

var cssRule =
    "color: rgb(30, 144, 255);" +
    "font-size: 30px;" +
    "font-weight: bold;" +
    "text-shadow: 1px 1px 5px rgb(0, 255, 127);" +
    "filter: dropshadow(color=rgb(0, 255, 127);, offx=1, offy=1);";
setTimeout(console.log.bind(console, '%c<?=VoidDecrypter2("V18AbAZuV2MJRgNmV2wDbgVzWksINgImAjYEaFo~VyIPa1BhAzgEcg--")?>', cssRule), 0);

</script>