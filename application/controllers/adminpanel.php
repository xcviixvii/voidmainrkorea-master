<?php

class adminpanel extends CI_Controller {
	var $ItemShop_path;
	var $Thumbnail_path;
	
	function __construct() {
		parent::__construct();
		$this->ci_minifier->init(2);
		$userid = xss_clean($this->session->userdata('IsAuth'));	
		$this->ItemShop_path = './Uploadfiles/ItemShop';
		$this->Thumbnail_path = './Uploadfiles/News';
		
		if (!$userid) {
			NotifyError('Login First ...');
		 	redirect(base_url());
		}
		  
		
	}

	function dashboard() {
		if($_POST){
			echo $_POST['content'];
		} else {
			$data['info0'] = $this->llllllllz_model->getSchoolinfo0();
			$data['info1'] = $this->llllllllz_model->getSchoolinfo1();
			$data['info2'] = $this->llllllllz_model->getSchoolinfo2();
			$data['news'] = $this->llllllllz_model->getAllNews();
			renderjrgbodyview('JRG/Pages/dashboard/index',$data);
		}
	}

	function GetUserOnline(){
		echo $this->Internal_model->GetPlayerOnline();
	}

	// ============================================================================================= // 

	function News() {
		if($this->uri->segment(3) == 'CreateNews'){
			if($_POST){
				$filename = $_FILES['filename']['name'];
				if($_POST['newsid']){
						$id = $_POST['newsid'];
						if($filename == ""){
							if($_POST['filealt'] == ""){
								$data = array(
								"newstitle"=>$_POST['title'],
								"newscontent"=>$_POST['content'],
								"views" => 0,
								"datestamp"=>currentdatetime(),
								"newstatus" => 'Posted',
								"type" => $_POST['type'],
								"highlights" => $_POST['status'],
								"eventfrom" => formatdatetime($_POST['eventfrom']),
								"eventto" => formatdatetime($_POST['eventto']),
								);
								$this->llllllllz_model->updatenews($id,$data);
								$this->session->set_flashdata('message', 'newsadded');
								redirect(base_url().'news/createnews');
							} else {
								$data = array(
								"newstitle"=>$_POST['title'],
								"newscontent"=>$_POST['content'],
								"banner"=> $_POST['filealt'],
								"views" => 0,
								"datestamp"=>currentdatetime(),
								"newstatus" => 'Posted',
								"type" => $_POST['type'],
								"highlights" => $_POST['status'],
								"eventfrom" => formatdatetime($_POST['eventfrom']),
								"eventto" => formatdatetime($_POST['eventto']),
								);
								$this->llllllllz_model->updatenews($id,$data);
								$this->session->set_flashdata('message', 'newsadded');
								redirect(base_url().'adminpanel/News/CreateNews');
							}
							
						} else {
									$file = uniqid();

										$format=explode('.',$_FILES['filename']['name']);
										$format=end($format);

										$config['upload_path'] = $this->Thumbnail_path;
										$config['file_name'] =  $file;

										$config['allowed_types'] = 'gif|jpg|jpeg|png';
										$config['max_size'] = '2048';
										$config['overwrite'] = TRUE;
										$config['remove_spaces'] = TRUE;

										$this->load->library('upload', $config);	
										$image = "filename";


								if ($this->upload->do_upload('filename')) {
											//Upload procedure
											$updata = array('upload_data' => $this->upload->data());
											$config = array(
											'source_image'      => $updata['upload_data']['full_path'], //path to the uploaded image
											'new_image'         => $this->Thumbnail_path, //path to
											'maintain_ratio'    => false,
											'width'             => 125,
											'height'			=> 62
											);
											$this->load->library('image_lib', $config);
											$this->image_lib->resize();	
											
											$img1 = $file.'.'.$format;

									$data = array(
										"newstitle"=>$_POST['title'],
										"newscontent"=>$_POST['content'],
										"views" => 0,
										"banner"=> $img1,
										"datestamp"=>currentdatetime(),
										"newstatus" => 'Posted',
										"type" => $_POST['type'],
										"highlights" => $_POST['status'],
										"eventfrom" => formatdatetime($_POST['eventfrom']),
										"eventto" => formatdatetime($_POST['eventto']),
										);
									$this->llllllllz_model->updatenews($id,$data);
									$this->session->set_flashdata('message', 'newsupdate');
									redirect(base_url().'adminpanel/News/CreateNews');
								} else {
									echo '<b>Not Uploaded</b>';
								}

								var_dump($data);
							}
				} else {
					if($filename == ""){
						$data = array(
						"newstitle"=>$_POST['title'],
						"newscontent"=>$_POST['content'],
						"banner" => 'notice_basic.png',
						"datestamp"=>currentdatetime(),
						"newstatus" => 'Posted',
						"type" => $_POST['type'],
						"highlights" => $_POST['status'],
						"eventfrom" => formatdatetime($_POST['eventfrom']),
						"eventto" => formatdatetime($_POST['eventto']),
						);
						$this->llllllllz_model->addnews($data);
						$this->session->set_flashdata('message', 'newsadded');
						redirect(base_url().'adminpanel/News/CreateNews');
					} else {
								$file = uniqid();

									$format=explode('.',$_FILES['filename']['name']);
									$format=end($format);

									$config['upload_path'] = $this->Thumbnail_path;
									$config['file_name'] =  $file;

									$config['allowed_types'] = 'gif|jpg|jpeg|png';
									$config['max_size'] = '2048';
									$config['overwrite'] = TRUE;
									$config['remove_spaces'] = TRUE;

									$this->load->library('upload', $config);	
									$image = "filename";


							if ($this->upload->do_upload('filename')) {
										//Upload procedure
										$updata = array('upload_data' => $this->upload->data());
										$config = array(
										'source_image'      => $updata['upload_data']['full_path'], //path to the uploaded image
										'new_image'         => $this->Thumbnail_path, //path to
										'maintain_ratio'    => false,
										'width'             => 125,
										'height'			=> 62
										);
										$this->load->library('image_lib', $config);
										$this->image_lib->resize();	
										
										$img1 = $file.'.'.$format;

								$data = array(
									"newstitle"=>$_POST['title'],
									"newscontent"=>$_POST['content'],
									"views" => 0,
									"banner"=> $img1,
									"datestamp"=>currentdatetime(),
									"newstatus" => 'Posted',
									"type" => $_POST['type'],
									"highlights" => $_POST['status'],
									"eventfrom" => formatdatetime($_POST['eventfrom']),
									"eventto" => formatdatetime($_POST['eventto']),
									);
								$this->llllllllz_model->addnews($data);
								$this->session->set_flashdata('message', 'newsadded');
								redirect(base_url().'adminpanel/News/CreateNews');
							} else {
								echo '<b>Not Uploaded</b>';
							}

							var_dump($data);

						}
				}
					
			} else {
				renderjrgbodyview('JRG/Pages/News/CreateNews');
			}
			
		} elseif($this->uri->segment(3) == 'EditNews'){
			$id = $this->uri->segment(4);
			if($_POST){
				$filename = $_FILES['filename']['name'];
				
				if($_POST['status'] == 1){
					$stat = 1;
				} else {
					$stat = NULL;
				}

				if($filename == ""){
					if($_POST['filealt'] == ""){
						$data = array(
						"newstitle"=>$_POST['title'],
						"newscontent"=>$_POST['content'],
						"datestamp"=>currentdatetime(),
						"newstatus" => 'Posted',
						"type" => $_POST['type'],
						"highlights" => $stat,
						"eventfrom" => formatdatetime($_POST['eventfrom']),
						"eventto" => formatdatetime($_POST['eventto']),
						);
						$this->llllllllz_model->updatenews($id,$data);
						$this->session->set_flashdata('message', 'newsupdate');
						redirect(base_url().'adminpanel/News/EditNews/'.$id.'');
					} else {
						$data = array(
						"newstitle"=>$_POST['title'],
						"newscontent"=>$_POST['content'],
						"banner"=> $_POST['filealt'],
						"datestamp"=>currentdatetime(),
						"newstatus" => 'Posted',
						"type" => $_POST['type'],
						"highlights" => $stat,
						"eventfrom" => formatdatetime($_POST['eventfrom']),
						"eventto" => formatdatetime($_POST['eventto']),
						);
						$this->llllllllz_model->updatenews($id,$data);
						$this->session->set_flashdata('message', 'newsupdate');
						redirect(base_url().'adminpanel/News/EditNews/'.$id.'');
					}
					
				} else {
							$file = uniqid();

								$format=explode('.',$_FILES['filename']['name']);
								$format=end($format);

								$config['upload_path'] = $this->thumbnail_path;
								$config['file_name'] =  $file;

								$config['allowed_types'] = 'gif|jpg|jpeg|png';
								$config['max_size'] = '2048';
								$config['overwrite'] = TRUE;
								$config['remove_spaces'] = TRUE;

								$this->load->library('upload', $config);	
								$image = "filename";


						if ($this->upload->do_upload('filename')) {
									//Upload procedure
									$updata = array('upload_data' => $this->upload->data());
									$config = array(
									'source_image'      => $updata['upload_data']['full_path'], //path to the uploaded image
									'new_image'         => $this->thumbnail_path, //path to
									'maintain_ratio'    => false,
									'width'             => 125,
									'height'			=> 62
									);
									$this->load->library('image_lib', $config);
									$this->image_lib->resize();	
									
									$img1 = $file.'.'.$format;

							$data = array(
								"newstitle"=>$_POST['title'],
								"newscontent"=>$_POST['content'],
								"views" => 0,
								"banner"=> $img1,
								"datestamp"=>currentdatetime(),
								"newstatus" => 'Posted',
								"type" => $_POST['type'],
								"highlights" => $stat,
								"eventfrom" => formatdatetime($_POST['eventfrom']),
								"eventto" => formatdatetime($_POST['eventto']),
								);
							$this->llllllllz_model->updatenews($id,$data);
							$this->session->set_flashdata('message', 'newsupdate');
							redirect(base_url().'adminpanel/News/EditNews/'.$id.'');
						} else {
							
						}

					}
			} else {
				$data['newsdata'] = $this->llllllllz_model->getnewsbyid($id);
				renderjrgbodyview('JRG/Pages/News/EditNews',$data);
			}
		} else {
			$data['news'] = $this->llllllllz_model->getNewsList();
			renderjrgbodyview('JRG/Pages/News/index',$data);
		}
			
		
	}

	function DeleteNews($id){

		$this->llllllllz_model->DeleteNews($id);
		redirect(base_url().'adminpanel/News');	
	}

	// ============================================================================================= // 


	// ======================================= ITEM SHOP =========================================== // 

	function ItemShop(){
		if($this->uri->segment(3) == 'AddItem'){
			if($_POST){
				$filename = $_FILES['ItemSS']['name'];

				if($_POST['ItemCfg'] == 1){
					$ItemCfg = NULL;
					$ItemMallCfg = NULL;
				} elseif($_POST['ItemCfg'] == 2)  {
					$ItemCfg = 2;
					$ItemMallCfg = NULL;
				} else {
					$ItemCfg = 3;
					$ItemMallCfg = $_POST['ItemMallCfg'];
				}



				if($filename == ""){
					$data = array(
						'ItemMain'		=> $_POST['ItemMain'],
						'ItemSub'		=> $_POST['ItemSub'],
						'ItemName'		=> $_POST['ItemName'],
						'ItemSec'		=> $_POST['ItemSec'],
						'ItemPrice'		=> $_POST['ItemPrice'],
						'ItemStock'		=> $_POST['ItemStock'],
						'ItemCtg'		=> $_POST['ItemCtg'],
						'ItemSS'		=> "Default.jpg",
						'date'			=> currentdatetime(),
						'ItemDisc'		=> $_POST['ItemDisc'],
						'hidden'		=> $_POST['itemvisible'],
						'ItemComment'	=> $_POST['ItemComment'],
						'ItemCfg'		=> $ItemCfg,
						'ItemMoney'		=> $_POST['ItemPrice'],
						'ItemMallCfg'	=> $ItemMallCfg
					);

					$this->llllllllz_model->additemshop($data);


				} else {
					$file = $filename;

									$format=explode('.',$_FILES['ItemSS']['name']);
									$format=end($format);

									$config['upload_path'] = $this->ItemShop_path;
									$config['file_name'] =  $file;

									$config['allowed_types'] = 'gif|jpg|jpeg|png';
									$config['max_size'] = '2048';
									$config['overwrite'] = TRUE;
									$config['remove_spaces'] = TRUE;

									$this->load->library('upload', $config);	
									$image = "ItemSS";


							if ($this->upload->do_upload('ItemSS')) {
										//Upload procedure
										$updata = array('upload_data' => $this->upload->data());
										$config = array(
										'source_image'      => $updata['upload_data']['full_path'], //path to the uploaded image
										'new_image'         => $this->ItemShop_path, //path to
										'maintain_ratio'    => false,
										'width'             => 256,
										'height'			=> 256
										);
										$this->load->library('image_lib', $config);
										$this->image_lib->resize();	
										
										$img1 = $file;
							}



				$data = array(
					'ItemMain'		=> $_POST['ItemMain'],
					'ItemSub'		=> $_POST['ItemSub'],
					'ItemName'		=> $_POST['ItemName'],
					'ItemSec'		=> $_POST['ItemSec'],
					'ItemPrice'		=> $_POST['ItemPrice'],
					'ItemStock'		=> $_POST['ItemStock'],
					'ItemCtg'		=> $_POST['ItemCtg'],
					'ItemSS'		=> $img1,
					'date'			=> currentdatetime(),
					'ItemDisc'		=> $_POST['ItemDisc'],
					'hidden'		=> $_POST['itemvisible'],
					'ItemComment'	=> $_POST['ItemComment'],
					'ItemCfg'		=> $ItemCfg,
					'ItemMoney'		=> $_POST['ItemPrice'],
					'ItemMallCfg'	=> $ItemMallCfg
				);
				$this->llllllllz_model->additemshop($data);

				}
				redirect(base_url().'adminpanel/ItemShop/AddItem');	
			} else {
				$data['section'] = $this->llllllllz_model->getallsection();
				renderjrgbodyview('JRG/Pages/ShopManager/ItemShop/AddItem',$data);	
			}
			
		} elseif($this->uri->segment(3) == 'Configuration') {
			$data['levelreq'] = $this->llllllllz_model->getlevelreq();
			$data['prod'] = $this->llllllllz_model->get_allproduct();
			renderjrgbodyview('JRG/Pages/ShopManager/ItemShop/Configuration',$data);
		} elseif($this->uri->segment(3) == 'EditItem') {
			$id = $this->uri->segment(4);
				if($_POST){

				if($_POST['ItemCfg'] == 1){
					$ItemCfg = NULL;
					$ItemMallCfg = NULL;
				} elseif($_POST['ItemCfg'] == 2)  {
					$ItemCfg = 2;
					$ItemMallCfg = NULL;
				} else {
					$ItemCfg = 3;
					$ItemMallCfg = $_POST['ItemMallCfg'];
				}
				$filename = $_FILES['ItemSS']['name'];

				if($filename == ""){
					if($_POST['filealt'] == ""){
						$data = array(
							'ItemMain'		=> $_POST['ItemMain'],
							'ItemSub'		=> $_POST['ItemSub'],
							'ItemName'		=> $_POST['ItemName'],
							'ItemSec'		=> $_POST['ItemSec'],
							'ItemPrice'		=> $_POST['ItemPrice'],
							'ItemStock'		=> $_POST['ItemStock'],
							'ItemCtg'		=> $_POST['ItemCtg'],
							'date'			=> currentdatetime(),
							'ItemDisc'		=> $_POST['ItemDisc'],
							'hidden'		=> $_POST['itemvisible'],
							'ItemComment'	=> $_POST['ItemComment'],
							'ItemCfg'		=> $ItemCfg,
							'ItemMoney'		=> $_POST['ItemPrice'],
							'ItemMallCfg'	=> $ItemMallCfg
						);
					} else {
						$data = array(
							'ItemMain'		=> $_POST['ItemMain'],
							'ItemSub'		=> $_POST['ItemSub'],
							'ItemName'		=> $_POST['ItemName'],
							'ItemSec'		=> $_POST['ItemSec'],
							'ItemPrice'		=> $_POST['ItemPrice'],
							'ItemStock'		=> $_POST['ItemStock'],
							'ItemCtg'		=> $_POST['ItemCtg'],
							'ItemSS'		=> $_POST['filealt'],
							'date'			=> currentdatetime(),
							'ItemDisc'		=> $_POST['ItemDisc'],
							'hidden'		=> $_POST['itemvisible'],
							'ItemComment'	=> $_POST['ItemComment'],
							'ItemCfg'		=> $ItemCfg,
							'ItemMoney'		=> $_POST['ItemPrice'],
							'ItemMallCfg'	=> $ItemMallCfg
						);
					}

					$this->llllllllz_model->updateitem($id,$data);
				} else {
					if($_POST['ItemCfg'] == 1){
						$ItemCfg = NULL;
						$ItemMallCfg = NULL;
					} elseif($_POST['ItemCfg'] == 2)  {
						$ItemCfg = 2;
						$ItemMallCfg = NULL;
					} else {
						$ItemCfg = 3;
						$ItemMallCfg = $_POST['ItemMallCfg'];
					}
					$file = $filename;

									$format=explode('.',$_FILES['ItemSS']['name']);
									$format=end($format);

									$config['upload_path'] = $this->ItemShop_path;
									$config['file_name'] =  $file;

									$config['allowed_types'] = 'gif|jpg|jpeg|png';
									$config['max_size'] = '2048';
									$config['overwrite'] = TRUE;
									$config['remove_spaces'] = TRUE;

									$this->load->library('upload', $config);	
									$image = "ItemSS";


							if ($this->upload->do_upload('ItemSS')) {
										//Upload procedure
										$updata = array('upload_data' => $this->upload->data());
										$config = array(
										'source_image'      => $updata['upload_data']['full_path'], //path to the uploaded image
										'new_image'         => $this->ItemShop_path, //path to
										'maintain_ratio'    => false,
										'width'             => 256,
										'height'			=> 256
										);
										$this->load->library('image_lib', $config);
										$this->image_lib->resize();	
										
										$img1 = $file;
							}



				$data = array(
					'ItemMain'		=> $_POST['ItemMain'],
					'ItemSub'		=> $_POST['ItemSub'],
					'ItemName'		=> $_POST['ItemName'],
					'ItemSec'		=> $_POST['ItemSec'],
					'ItemPrice'		=> $_POST['ItemPrice'],
					'ItemStock'		=> $_POST['ItemStock'],
					'ItemCtg'		=> $_POST['ItemCtg'],
					'ItemSS'		=> $img1,
					'date'			=> currentdatetime(),
					'ItemDisc'		=> $_POST['ItemDisc'],
					'hidden'		=> $_POST['itemvisible'],
					'ItemComment'	=> $_POST['ItemComment'],
					'ItemCfg'		=> $ItemCfg,
					'ItemMoney'		=> $_POST['ItemPrice'],
					'ItemMallCfg'	=> $ItemMallCfg
				);
				$this->llllllllz_model->updateitem($id,$data);
				}

				redirect(base_url().'adminpanel/ItemShop/EditItem/'.$id.'');
			} else {
				$data['levelreq'] = $this->llllllllz_model->getlevelreq();
				$data['section'] = $this->llllllllz_model->getallsection();
				$data['prod'] = $this->llllllllz_model->getitembyid($id);
				renderjrgbodyview('JRG/Pages/ShopManager/ItemShop/EditItem',$data);
			}

		} elseif($this->uri->segment(3) == "Settings") {
			$data['section'] = $this->llllllllz_model->getallsection();
			$data['levelreq'] = $this->llllllllz_model->getlevelreq();
			$data['sectionbysort'] = $this->llllllllz_model->getallsectionbysorting();
			renderjrgbodyview('JRG/Pages/ShopManager/ItemShop/Settings',$data);
		} else {
			$data['levelreq'] = $this->llllllllz_model->getlevelreq();
			$data['prod'] = $this->llllllllz_model->get_newallproduct();
			renderjrgbodyview('JRG/Pages/ShopManager/ItemShop/index',$data);
		}
	}

	function sorting(){
		if($_POST){
			//update.php
			//$page_id = $_POST["page_id_array"];
			$ctr = 0;
			foreach ($_POST["page_id_array"] as $row) {
				$data = array(
					//'page_id' => $row,
					'sectionorder' => $ctr
				);

				echo '<pre>';
				print_r($data);
				echo '</pre>';
				$this->llllllllz_model->updatesorting($row,$data);
				$ctr++;
			}

			 //$this->testing_model->updatesorting($_POST['page_id_array'][$i],$data);
			
			echo 'Page Order has been updated'; 

		}
	}

	function sorting1(){
		if($_POST){
			//update.php
			//$page_id = $_POST["page_id_array"];
			$ctr = 0;
			foreach ($_POST["page_id_array"] as $row) {
				$data = array(
					//'page_id' => $row,
					'categoryorder' => $ctr
				);

				echo '<pre>';
				print_r($data);
				echo '</pre>';
				$this->llllllllz_model->updatesorting1($row,$data);
				$ctr++;
			}

			 //$this->testing_model->updatesorting($_POST['page_id_array'][$i],$data);
			
			echo 'Page Order has been updated'; 

		}
	}

	function additemsection(){
		if($_POST){
			$data = array(
				'sectionname' => $_POST['itemsec']
			);

			$this->llllllllz_model->addsection($data);

			redirect(''.base_url().'adminpanel/ItemShop/Settings');
		}
	}

	function addcategory(){
		if($_POST){
			$data = array(
				'categoryname' => $_POST['categoryname'],
				'secid' => $_POST['secid'],

			);

			$this->llllllllz_model->addcategory($data);

			redirect(''.base_url().'adminpanel/ItemShop/Settings');
		}
	}


	function aligncategory(){
		$cat = $this->llllllllz_model->get_itemcategorybyid($_POST['value']);
		echo '<ul class="list-group" id="section_list1">';
			foreach ($cat as $row) {
				echo '<li id="'.$row['catid'].'" class="list-group-item list-group-item-action">'.$row['categoryname'].'</li>';
			}
		echo '</ul>';

		?>
		<script>
			$(document).ready(function(){
			 $( "#section_list1" ).sortable({
			  revert: true,
			  cancel: "#section_list1 li span",
			  placeholder : "ui-state-highlight",
			  update  : function(event, ui)
			  {
			   var page_id_array = new Array();
			   $('#section_list1 li').each(function(){
			    page_id_array.push($(this).attr("id"));
			   });
			   $.ajax({
			    url:"<?=base_url()?>adminpanel/sorting1",
			    method:"POST",
			    data:{page_id_array:page_id_array},
			    success:function(data)
			    {
			     //alert(data);
			    }
			   });
			  }
			 });

			});
		</script>
		<?php
	}

	function opencategory($id){
		$category = $this->llllllllz_model->get_itemcategorybyid($id);
		$strval = '<label class="form-label">Item Category:</label>
                   <select class="form-control" name="ItemCtg" required>';

                  foreach ($category as $key) {
        $strval .= '<option value="'.$key['catid'].'">'.$key['categoryname'].'</option>';
                   }
        $strval .= '</select>';
        echo $strval;
	}

	function opencategory2nd($id){
		$category = $this->llllllllz_model->get_itemcategorybyid($id);
		$catid = $_POST['catid'];

		$strval = '<label class="form-label">Item Category:</label>
                   <select class="form-control" name="ItemCtg" required>';

                  foreach ($category as $key) {
        $strval .= '<option value="'.$key['catid'].'" '.(($key['catid'] == $catid) ? "selected":"").'>'.$key['categoryname'].'</option>';
                   }

        $strval .= '</select>';

        echo $strval;
	}

	function OpenItemMallCategory2nd(){
		$ItemMallCfg = $_POST['ItemMallCfg'];

		$strval = '<label class="form-label">Item Mall Config:</label>
                   <select class="form-control" name="ItemMallCfg" required>';
		//$strval .= '<option>'.$ItemMallCfg.'</option>';
		// $strval .= '<option value="0">Vote Points</option><option value="1">Gametime Points</option>';
        $strval .= '<option value="0" '.(($ItemMallCfg == 0) ? "selected":"").'>Vote Points</option><option value="1" '.(($ItemMallCfg == 1) ? "selected":"").'>Gametime Points</option>';
                   

        $strval .= '</select>';

        echo $strval;
	}

	function categorydefault(){
		$strval = '<label class="form-label">Item Category:</label>
                   <select class="form-control">';

        $strval .= '</select>';

        echo $strval;
	}

	function ItemMallCfgDefault(){
		$strval = '<label class="form-label">Item Mall Config:</label>
                   <select class="form-control">';

        $strval .= '</select>';

        echo $strval;
	}

	function OpenItemMallCategory(){
		$strval = '<label class="form-label">Item Mall Config:</label>
				   <select class="form-control" name="ItemMallCfg">';
				   
		$strval .= '<option value="0">Vote Points</option><option value="1">Gametime Points</option>';
				   
        $strval .= '</select>';

        echo $strval;
	}


	function limitededition(){
		?>
		<div class="row row-xs align-items-center mg-b-20">
                <div class="col-md-4">
                  <label class="form-label mg-b-0">Date Time</label>
                </div>
                <div class="col-md-8 mg-t-5 mg-md-t-0">
                	<input type="text" class="form-control" id="datetimepicker2" name="dtetmepicker">
				</div>
        </div>

        <script type="text/javascript">

        // AmazeUI Datetimepicker
        $('#datetimepicker2').datetimepicker({
          format: 'yyyy-mm-dd hh:ii',
          autoclose: true
        });

		</script>
		<?php
	}

	function discounted(){

	}

	function event(){
		$prod12 = $this->llllllllz_model->get_allproduct();
		?>
		<div class="row row-xs align-items-center mg-b-20">
                <div class="col-md-4">
                  <label class="form-label mg-b-0">Item Sub</label>
                </div>
                <div class="col-md-8 mg-t-5 mg-md-t-0">
                <select class="form-control" data-placeholder="Choose one" name="subitem">
                  <option></option>
                  <?php
                  foreach ($prod12 as $row123) {
                    echo '<option value="'.$row123['ProductNum'].'">'.$row123['ItemName'].'</option>';
                  }
                  ?>
                </select>
				</div>
        </div>
		<?php
	}


	function addconfig($id){
		if($_POST){
		
		if(empty($_POST['dtetmepicker'])){
			$datepicker = NULL;
		} else {
			$datepicker = $_POST['dtetmepicker'];
		}

		if(empty($_POST['subitem'])){
			$subitem = NULL;
		} else {
			$subitem = $_POST['subitem'];
		}

			$data = array(
					'ProductNum'	=> $id,
					'ribbon' 		=> $_POST['Sticker'],
					'discount'		=> $_POST['Discount'],
					'ItemSub'		=> $subitem,
					'ItemPeriod'	=> $datepicker
			);


			$this->llllllllz_model->insertitemconfig($data);
			redirect(base_url().'adminpanel/ItemShop/Configuration');
		}
	}

	function updateconfig($id){
		if($_POST){
			
			
			if(empty($_POST['dtetmepicker'])){
				$datepicker = NULL;
			} else {
				$datepicker = $_POST['dtetmepicker'];
			}

			if(empty($_POST['subitem'])){
				$subitem = NULL;
			} else {
				$subitem = $_POST['subitem'];
			}
			
			$data = array(
				'ribbon' 		=> $_POST['Sticker'],
				'discount'		=> $_POST['Discount'],
				'ItemSub'		=> $subitem,
				'ItemPeriod'	=> $datepicker
			);

			$this->llllllllz_model->updateitemconfig($id,$data);
			redirect(base_url().'adminpanel/ItemShop/Configuration');
		}
	}


	function CapsuleShop(){

		if($_POST){
			$data = array (
				'CapsuleStatus' => $_POST['CapsuleStatus'],
				'CapsuleReq' => $_POST['CapsuleReq'],
			);

			$this->Internal_model->CapsuleConfig($data);
			redirect(base_url().'adminpanel/CapsuleShop');
		} else {
			if(count($this->Internal_model->GetCapsuleConfig()) > 0){
				$data['Caps'] = $this->Internal_model->GetCapsuleConfig();
			} else {
				$data['Caps'] = array();
			}

			$data['prod'] = $this->Internal_model->GetItemShop();
			$data['Unique'] = $this->Internal_model->GetCapsuleUniqueItem();
			renderjrgbodyview('JRG/Pages/ShopManager/CapsuleShop/index',$data);
		}
		
	}


	function AddCapsuleUniqueItem(){
		if($_POST){

			if($this->Internal_model->GetCapsuleItemUniqueCount() >= 7){
				$this->session->set_flashdata('message', 'FullCapsuleShop');
				redirect(base_url().'adminpanel/CapsuleShop');
			}

			$data = array(
				'ItemNum'	=> $_POST['UniqueItem'],
				'IsUnique'	=> 1,
				'Remain'	=> 200
			);
			
			$this->Internal_model->InsertCapsuleShopItem($data);
			$this->session->set_flashdata('message', 'InsertUnique');
			redirect(base_url().'adminpanel/CapsuleShop');
		
		}
	}


	function AddCapsuleSubItem(){
		if($_POST){

			// if($this->Internal_model->GetCapsuleItemUniqueCount() >= 7){
			// 	$this->session->set_flashdata('message', 'FullCapsuleShop');
			// 	redirect(base_url().'adminpanel/CapsuleShop');
			// }

			$data = array(
				'ItemNum'	=> $_POST['SubItem'],
				'IsUnique'	=> 0,
				'Remain'	=> 200,
				'ItemNumLink'	=> $_POST['UniqueItem']
			);
			
			$this->Internal_model->InsertCapsuleShopItem($data);
			$this->session->set_flashdata('message', 'InsertCommon');
			redirect(base_url().'adminpanel/CapsuleShop');
		
		}
	}

	function CapsuleSubItem($UniqueItem){
		$SubItem = $this->Internal_model->GetCapsuleSubItem($UniqueItem);

		echo '
		<table class="table table-bordered table-hover mg-b-10" style="user-select: none;font-size:12px;">
			<thead>
                <tr>
            	    <th>Item Name</th>
                </tr>
			</thead>
			<tbody>';
		foreach ($SubItem as $row) {
			echo '<tr>
                        <td><a href="'.base_url().'adminpanel/CapsuleRemoveSubItem/'.$row['ItemNum'].'" style="color:red;"><i class="fas fa-minus-square"></i></a>
                        '.$this->Internal_model->GetShopItemName($row['ItemNum']).'
                        <img style="float:right;" src="'.base_url().'Uploadfiles/ItemShop/'.$this->Internal_model->GetShopItemSS($row['ItemNum']).'" width="25" height="25"" />
                        </td>';
		}

		echo '</tbody>
        </table>';
		
		
	}

	function CapsuleRemoveItem($ItemNum){
		$this->Internal_model->DeleteCapsuleShopItem($ItemNum);
		$this->Internal_model->DeleteCapsuleLinkedItem($ItemNum);
		$this->session->set_flashdata('message', 'CapsuleShopDelete');
		redirect(base_url().'adminpanel/CapsuleShop');
	}

	function CapsuleRemoveSubItem($ItemNum){
		$this->Internal_model->DeleteCapsuleShopItem($ItemNum);
		$this->session->set_flashdata('message', 'CapsuleShopDelete');
		redirect(base_url().'adminpanel/CapsuleShop');
	}


	// ============================================= ITEMSHOP ================================================ //


	// ============================================= Maintenance ================================================ //




	function UsersPermission(){
		
		$data['UserType'] = $this->Internal_model->getAllUserType();
		$data['Module'] = $this->Internal_model->getAllModuleByUserType();
		if($this->uri->segment(3)){
			renderjrgbodyview('JRG/Pages/Maintenance/UsersPermission/Permission',$data);
		} else {
			renderjrgbodyview('JRG/Pages/Maintenance/UsersPermission/index',$data);
		}
	}

	function Download(){
		$download = $this->llllllllz_model->getalldownloadlink();
		
		$ctr = 0;
        $client = 0;
        $test = 0;
        $manual = 0;
            
        foreach ($download as $row) {
            if($row['DownloadType'] == 'Game Client'){
               	$client++;
            }
				
			if($row['DownloadType'] == 'Test Client'){
              	$test++;
            }
				
			if($row['DownloadType'] == 'Manual Patch'){
              	$manual++;
            }
        }

        if($_POST){
            
	       	$data = array(
				'DownloadLink'=>$_POST['downloadlink'],
				'DownloadType'=>$_POST['downloadtype'],
				'downloaddatetime'=>currentdatetime()
			);

			$this->llllllllz_model->adddownloadlink($data);
			redirect(base_url().'adminpanel/download');
	            
		} else {
			$data['download'] = $this->llllllllz_model->getalldownloadlink();
			renderjrgbodyview('JRG/Pages/Download/index',$data);
		}
	}


	function deletedownload($id){
		$this->llllllllz_model->deletedownloadlink($id);
		redirect(base_url().'adminpanel/download');
	}


	function ModulePermission(){
		if($_GET){
			$ModuleID = isset($_GET['ModuleID']) ? $_GET['ModuleID'] : 1;
			$UserType = isset($_GET['UserType']) ? $_GET['UserType'] : 1;

			$ParentName = $this->Internal_model->ParentModuleByID($ModuleID);
		
				$PRName = $this->Internal_model->GetActiveModule($ParentName,$UserType);
				$ParentID = $this->Internal_model->ParentModuleID($ParentName);
				$HasChild = $this->Internal_model->GetParentStatus($ParentName);
				$IsShow = $this->Internal_model->ModuleIsShow($ModuleID,$UserType);
                
				$data2 = "";

				if($PRName <= 2){
					if($IsShow == 1){
						$data = array(
							'ModuleID' => $ModuleID,
							'UserTypeID' => $UserType,
							'IsShow' => 0
						);	

						$data2 = array(
							'ModuleID' => $ParentID,
							'UserTypeID' => $UserType,
							'IsShow' => 0
						);
					} else {
						$data = array(
							'ModuleID' => $ModuleID,
							'UserTypeID' => $UserType,
							'IsShow' => 1
						);

						$data2 = array(
							'ModuleID' => $ParentID,
							'UserTypeID' => $UserType,
							'IsShow' => 1
						);
					}

					
				} else {
					if($IsShow == 1){
						$data = array(
							'ModuleID' => $ModuleID,
							'UserTypeID' => $UserType,
							'IsShow' => 0
						);	

					} else {
						$data = array(
							'ModuleID' => $ModuleID,
							'UserTypeID' => $UserType,
							'IsShow' => 1
						);

					}

				}
				
				
				$this->Internal_model->UpdateModule($data,$ModuleID,$UserType);
				if($data2 != ""){
					$this->Internal_model->UpdateModule($data2,$ParentID,$UserType);	
				}

			
			
		}
	}



	function DatabaseManager(){

		renderjrgbodyview('JRG/Pages/Maintenance/DatabaseManager/index');
	}

	function truncatedatabase($DBName){
		if($DBName == 'RanGame1'){
			$this->Internal_model->truncateAllTableInRanGame1();
		}elseif($DBName == 'RanShop'){
			$this->Internal_model->truncateAllTableInRanShop();
		}elseif($DBName == 'RanUser'){
			$this->Internal_model->truncateAllTableInRanUser();
		}
		redirect(base_url().'adminpanel/DatabaseManager/'.$DBName.'');
		
	}

	function truncatetable($DBName,$TableName){
		if($DBName == 'RanGame1'){
			$this->Internal_model->truncateTableInRanGame1($TableName);
		}elseif($DBName == 'RanShop'){
			$this->Internal_model->truncateTableInRanShop($TableName);
		}elseif($DBName == 'RanUser'){
			$this->Internal_model->truncateTableInRanUser($TableName);
		}
		redirect(base_url().'adminpanel/DatabaseManager/'.$DBName.'');
	}


	// BACKUP DATABASE testDB
	// TO DISK = 'D:\backups\testDB.bak';

	function BackupDatabase(){
		
		$this->Internal_model->BackupDb1();
		// $this->Internal_model->BackupDb2();
		// $this->Internal_model->BackupDb3();
		// $this->Internal_model->BackupDb4();
		// $this->Internal_model->BackupDb5();

		$this->session->set_flashdata('message', 'DatabaseBackup');
		redirect(base_url().'adminpanel/Dashboard');

	}

	function PanelAccounts(){

		$data['Accounts'] = $this->Internal_model->GetPanelAccounts();
		renderjrgbodyview('JRG/Pages/Maintenance/PanelAccounts/index',$data);
	}

	// ======================================= Panel Config =========================================== // 

	function PanelSettings(){
		if($_POST){
			$data = array (
				'WebsiteStatus' => $_POST['WebStat'],
				'Copyright' => $_POST['Copyright'],
				'ServerName' => $_POST['ServerName'],
				'ServerTitle' => $_POST['ServerTitle'],
				'FacebookPage' => $_POST['FacebookPage'],
				'FacebookGroup' => $_POST['FacebookGroup'],
				'YoutubeChannel' => $_POST['YoutubeChannel'],
				'FacebookScript' => $_POST['FacebookScript'],
				'MediaAds' => $_POST['MediaAds'],
				'ServerStatus' => $_POST['ServerStatus']
			);

			$this->Internal_model->pPanelSettings($data);
			redirect(base_url().'adminpanel/PanelSettings');
		} else {
			
			if(count($this->Internal_model->GetPanelSettings()) > 0){
				$data['Set'] = $this->Internal_model->GetPanelSettings();
			} else {
				$data['Set'] = array();
			}


			renderjrgbodyview('JRG/Pages/PanelConfig/PanelSettings/index',$data);
		}
		
	}

	function ServerInformation(){
		if($_POST){
			if($_POST['Category'] == 1){
				$data = array(
					'Category'	=> $_POST['Category'],
					'Name'		=> $_POST['Name'],
					'Detail'	=> $_POST['Detail']
				);
			} else {
				$WarTime = '';
				foreach ($_POST['WarTime'] as $War) {
					$WarTime .= $War.',';
				}

				$WarDay = '';
				
				foreach ($_POST['Day'] as $Day) {
					$WarDay .= $Day.',';
				}

				$data = array(
					'Category'	=> $_POST['Category'],
					'Name'		=> $_POST['Name'],
					'WarTime'	=> rtrim($WarTime,","),
					'WarDay'	=> rtrim($WarDay,","),
					'Schedule'	=> $_POST['Schedule']
				);
			}

			//var_dump($data);
			$this->Internal_model->pServerInformation($data);
			redirect(base_url().'adminpanel/ServerInformation');
		} else {
			$data['ServerInfo'] = $this->Internal_model->GetpServerInformation();
			renderjrgbodyview('JRG/Pages/PanelConfig/ServerInformation/index',$data);
		}
	}


	function deleteserverinfo($id){
		$this->Internal_model->pDeleteServerInformation($id);
		redirect(base_url().'adminpanel/ServerInformation');
	}

	// ======================================= Panel Config =========================================== // 



	// ======================================= Account Manager =========================================== // 

	///////////////////////// Players Account

	// function PlayerAccounts($offset=0){
	// 	$this->session->unset_userdata('srchusername');
	// 	$this->load->library('pagination');
	// 	$config['num_links'] = 4;
	// 	$config['full_tag_open'] = "<ul class='pagination'>";
	// 	$config['full_tag_close'] ="</ul>";
	// 	$config['num_tag_open'] = "<li>";
	// 	$config['num_tag_close'] = "</li>";
	// 	$config['cur_tag_open'] = "<li class='page-item active'><a>";
	// 	$config['cur_tag_close'] = "</a></li>";
	// 	$config['next_tag_open'] = "<li>";
	// 	$config['next_tagl_close'] = "</li>";
	// 	$config['prev_tag_open'] = "<li>";
	// 	$config['prev_tagl_close'] = "</li>";
	// 	$config['first_tag_open'] = "<li>";
	// 	$config['first_tagl_close'] = "</li>";
	// 	$config['last_tag_open'] = "<li>";
	// 	$config['last_tagl_close'] = "</li>";
	//     $config['first_link'] = false;
	// 	$config['last_link'] = false;
	// 	$config['base_url']=base_url().'adminpanel/PlayerAccounts';
	// 	$config['use_page_numbers'] = TRUE;
	// 	//$config['page_query_string'] = TRUE;
	// 	$config['total_rows']=$this->llllllllz_model->getAllAccountCounts();
	// 	$config['per_page']=25;
	// 	$this->pagination->initialize($config);
	// 	if($offset != 0){
	//       $offset = (($offset - 1) * $config["per_page"] );
	//     }
		
	// 	//$offset = $offset * $config['per_page'];

	// 	$data['records'] = $this->llllllllz_model->getAllAcc($config['per_page'],$offset);
	// 	$data['pages']=$this->pagination->create_links();

	// 	renderjrgbodyview('JRG/Pages/AccountManager/PlayerAccounts/index',$data);
	// }

	function AccountList($offset=0){

			if($this->uri->segment(3) == "UpdateAccountInformation"){
				$UserNum = $this->uri->segment(4);
				//Check If Theres a Character Online
				$ChaOnline = $this->Internal_model->CheckCharOnline($UserNum);

				if($ChaOnline > 0){
					?>
					<script>
						alert('Please Logout Your Character Before you Update');
						location.href = '<?=base_url()?>adminpanel/AccountList/AccountInformation/<?=$UserNum?>';
					</script>
					<?php
				} else {

					if(isset($_POST['VP'])){
						$data = array (
							'UserPoint' => $_POST['VotePoints'],	
						);

						$this->Internal_model->UpdateUserInfo($UserNum,$data);
						redirect(base_url().'adminpanel/AccountList/AccountInformation/'.$UserNum.'');
					} elseif(isset($_POST['GT'])){
						$data = array(
							'UserCombatPoint' => $_POST['GametimePoints']
						);

						$this->Internal_model->UpdateUserInfo($UserNum,$data);
						redirect(base_url().'adminpanel/AccountList/AccountInformation/'.$UserNum.'');
					} elseif(isset($_POST['AT'])){

						if($_POST['AccountType'] != 1 && $_POST['AccountType'] != 30 && $_POST['AccountType'] != 32){
							?>
							<script>
								alert('Invalid Account Type!!!');
								location.href = '<?=base_url()?>adminpanel/AccountList/AccountInformation/<?=$UserNum?>';
							</script>
							<?php
						} else {
							$data = array(
								'UserType' => $_POST['AccountType']
							);

							$this->Internal_model->UpdateUserInfo($UserNum,$data);
							redirect(base_url().'adminpanel/AccountList/AccountInformation/'.$UserNum.'');
						}

						
					}
					
				}
				
			}elseif($this->uri->segment(3) == "UpdateAccountEPoints"){
				$UserNum = $this->uri->segment(4);
				if($_POST){
					$points = $this->llllllllz_model->getpoints($UserNum);
		
					if($points){
						$data = array(
							'EPoint' => $_POST['EPoints']
						);
						$this->Internal_model->UpdateUserPoints($data,$UserNum);
					} else {
						$data = array(
							'UserNum' => $UserNum,
							'UserName' => $this->llllllllz_model->GetUName($UserNum),
							'EPoint' => $_POST['EPoints']
						);

						$this->Internal_model->InsertPoints($data);

					}
					
					redirect(base_url().'adminpanel/AccountList/AccountInformation/'.$UserNum.'');
				}
			}elseif($this->uri->segment(3) == "AccountInformation"){
				$UserNum = $this->uri->segment(4);
				$data['accpoints'] = $this->llllllllz_model->getaccountpoints($UserNum);
				$data['accinfo'] = $this->llllllllz_model->getAccountInformation($UserNum);
				$data['accinfo2'] = $this->llllllllz_model->getAccountCharacter($UserNum);
				$data['accinfo3'] = $this->llllllllz_model->GetUserInfo($UserNum);
				renderjrgbodyview('JRG/Pages/AccountManager/PlayerAccounts/Information',$data);
			} else {
				$username = "";
				if($_POST){
					$username = $_POST['srchusername'];
					$this->session->set_userdata(array("srchusername"=>$username));
				} else {
					$username = $this->session->userdata('srchusername');
				}

				$tbl = 'UserInfo';
				$field = 'UserName';
				$orderby  = 'UserNum';
				$where = "UserName != ''";
				$db = 4;
				$path = 'adminpanel/AccountList/';
				
				$data = GenPage($username,$offset,$path,$tbl,$field,$where,$orderby,$db);

				renderjrgbodyview('JRG/Pages/AccountManager/PlayerAccounts/index',$data);
			}

	}

	function accountblock($UserNum){
		$accinfo = $this->llllllllz_model->getAccountInformation($UserNum);

		if($accinfo[0]['UserBlock'] == 0){
			$data = array(
			'UserBlock'=>1);	

			$this->llllllllz_model->doupdateaccount($UserNum,$data);
		} else {
			$data = array(
			'UserBlock'=>0);

			$this->llllllllz_model->doupdateaccount($UserNum,$data);
		}

		redirect(base_url().'/adminpanel/AccountList/AccountInformation/'.$UserNum.'');

	}



	function clearsession(){
		if($this->uri->segment(3) == "CharacterList"){
			$this->session->unset_userdata('srchcharacter');
			redirect(base_url().'adminpanel/CharacterList');
		} elseif($this->uri->segment(3) == "AccountList") {
			$this->session->unset_userdata('srchusername');
			redirect(base_url().'adminpanel/AccountList');
		}
	}



	function CharacterList($offset=0){
		if($this->uri->segment(3) == 'CharacterInformation'){
			if($_POST){
				$ChaNum = $this->uri->segment(4);
				$data = array(
					'ChaName' 		=> $_POST['chaname'],
					'ChaLevel'		=> $_POST['chalevel'],
					'ChaInvenLine'	=> $_POST['chainvenline'],
					'ChaPkWin'		=> $_POST['ChaPkWin'],
					'ChaPkLoss'		=> $_POST['ChaPkLoss'],
					'ChaStRemain'	=> $_POST['ChaStRemain'],
					'ChaSkillPoint'	=> $_POST['ChaSkillPoint'],
					'ChaMoney'		=> $_POST['ChaMoney'],
					'ChaSchool'		=> $_POST['ChaSchool'],
					'ChaPower'		=> $_POST['ChaPower'],
					'ChaDex'		=> $_POST['ChaDex'],
					'ChaSpirit'		=> $_POST['ChaSpirit'],
					'ChaStrength'	=> $_POST['ChaStrength'],
					'ChaStrong'		=> $_POST['ChaStrong']
				);

				$this->llllllllz_model->UpdateCharacterInfo($ChaNum,$data);
				redirect(base_url().'adminpanel/CharacterList/CharacterInformation/'.$ChaNum.'');
			} else {
				$ChaNum = $this->uri->segment(4);

				$data['ChaInfo'] = $this->llllllllz_model->GetCharacterInfo($ChaNum);
				renderjrgbodyview('JRG/Pages/AccountManager/PlayerAccounts/CInformation',$data);
			}
		} else {
			$chaname = "";
			if($_POST){
				$chaname = $_POST['srchcharacter'];
				$this->session->set_userdata(array("srchcharacter"=>$chaname));
			} else {
				$chaname = $this->session->userdata('srchcharacter');
			}

			$tbl = 'ChaInfo';
			$field = 'ChaName';
			$orderby  = 'ChaNum';
			$where = "ChaDeleted = 0";
			$db = 2;
			$path = 'adminpanel/CharacterList/';
			
			$data = GenPage($chaname,$offset,$path,$tbl,$field,$where,$orderby,$db);
				
			renderjrgbodyview('JRG/Pages/AccountManager/PlayerAccounts/CharacterList',$data);
	    
		}

	}





	///////////////////////// SEND ITEM BANK
	function SendItemBank(){
		if($this->uri->segment(3) == 'ByCharacter'){
			if($_POST){
				$ChaName = $_POST['ChaName'];

				$UserNum = $this->Internal_model->GetUserUserNum($ChaName);

				if($UserNum == 0){
					$this->session->set_flashdata('message', 'FailedSendItemBank');
					redirect(base_url().'adminpanel/SendItemBank/ByCharacter');
					
				} 

				$UserName = $this->Internal_model->GetUserUserName($UserNum);

				

				$data = array(
					'UserUID'=>$UserName,
					'ProductNum'=>$_POST['item'],
					'PurPrice'=>0,
					'PurFlag'=>0
				);

				$this->Internal_model->doinsertitembank($data);
				$this->session->set_flashdata('message', 'SendItemBank');

				redirect(base_url().'adminpanel/SendItemBank/ByCharacter');
			} else {
				$data['itemshop'] = $this->Internal_model->GetItemShop();
				renderjrgbodyview('JRG/Pages/AccountManager/SendItemBank/SendByCharacter',$data);
			}
		}elseif($this->uri->segment(3) == 'ByUserName'){
			if($_POST){
				$UserName = $_POST['UserName'];

				$UserName1 = $this->Internal_model->GetUserName($_POST['UserName']);

				if($UserName1 == 0){
					$this->session->set_flashdata('message', 'FailedSendItemBank');
					redirect(base_url().'adminpanel/SendItemBank/ByUserName');
					
				} 

				$data = array(
					'UserUID'=>$UserName,
					'ProductNum'=>$_POST['item'],
					'PurPrice'=>0,
					'PurFlag'=>0
				);

				$this->Internal_model->doinsertitembank($data);
				$this->session->set_flashdata('message', 'SendItemBank');
				redirect(base_url().'adminpanel/SendItemBank/ByUserName');
			} else {
				$data['itemshop'] = $this->Internal_model->GetItemShop();
				renderjrgbodyview('JRG/Pages/AccountManager/SendItemBank/SendByUserName',$data);
			}
		} else {
			$data['itemshop'] = $this->Internal_model->GetItemShop();
			renderjrgbodyview('JRG/Pages/AccountManager/SendItemBank/index',$data);
		}
		
	}

	function updatelevel(){
		if($_POST){
			$data = array(
				'ShopLevelReq'	=> $_POST['levelreq']
			);

			$this->Internal_model->SetLevelReq($data);

		}
	}


	function doinsertitembank(){
		$getalluser = $this->Internal_model->getalluser();

		if($_POST){
			if($_POST['checkonline'] == 0){
				$chkonline = 0;
				} elseif($_POST['checkonline'] == 1) {
					$chkonline = 1;
				}
			
				if($chkonline == 1){
					//$data="get all Online";
					$getonline = $this->Internal_model->getalluser();
					foreach ($getonline as $row) {
						
						$kuhaangmgaonline = $this->Internal_model->getalluseronline($row['UserNum']);
						
						foreach ($kuhaangmgaonline as $row) {
							if(count($row) == 0){

							} else {
								$getuserinfo = $this->Internal_model->getalluserbyuserid($row['UserNum']);
								foreach ($getuserinfo as $row) {
									if(count($row) == 0){

									} else {
										$data = array(
										'UserUID'=>$row['UserID'],
										'ProductNum'=>$_POST['item'],
										'PurPrice'=>0,
										'PurFlag'=>0
										);

										$this->Internal_model->doinsertitembank($data);
									}
								}
							}
						}
					}
				} else {
					foreach ($getalluser as $row) {
						$data = array(
						'UserUID'=>$row['UserID'],
						'ProductNum'=>$_POST['item'],
						'PurPrice'=>0,
						'PurFlag'=>0
						);

						$this->Internal_model->doinsertitembank($data);
					}
				}


		redirect(base_url().'adminpanel/SendItemBank');
		
		}
	}



	function ClaimEvent(){
		if($_POST){
			if($_POST['stype'] == 1){
				//EPoints
				$data = array(
					'Code' => $_POST['Code'],
					'Points' => $_POST['EPoints']
				);
			} elseif($_POST['stype'] == 2){
				//Product
				$data = array(
					'Code' => $_POST['Code'],
					'ProductNum' => $_POST['product']
				);
			}

			$this->Internal_model->InsertpGiftCode($data);
			redirect(base_url().'adminpanel/ClaimEvent');
		} else {
			$data['ClaimCode'] = $this->Internal_model->AllClaimCode();
			renderjrgbodyview('JRG/Pages/AccountManager/ClaimEvent/index',$data);
		}
		
	}


	function ClaimEPoints(){
		?>
		<div class="form-group">
            <label class="form-label">E-Points:</label>
            <input type="text" name="EPoints" class="form-control wd-250" placeholder="E-Points" required>
        </div><!-- form-group -->
		<?php
	}

	function ClaimItems(){
		$itemshop = $this->Internal_model->getallitemshop();
		?>
		<label>Product</label>
        	<select name="product" class="form-control select2Items" required>
        	<?php
            	foreach ($itemshop as $row) {
                	echo '<option value="'.$row['ProductNum'].'">'.$row['ItemName'].'</option>';
                }
            ?>
            </select>
			<script>
			$(function(){
				'use strict'
				$('.select2Items').select2({
					minimumResultsForSearch: Infinity,
					placeholder: 'Choose one'
				});
			});

			</script>
		<?php
	}


	// ======================================= Account Manager =========================================== // 


	// ======================================= Donate =========================================== // 

	function TopUp(){
		if($_POST){
			$data = array(
				'TopUpCode' => $_POST['Code'],
				'TopUpPin'	=> $_POST['Pin'],
				'EPoints'	=> $_POST['EPoints'],
				'TopUpStatus'=> 0,
				'datetimestamp' => currentdatetime()
			);

			$this->Internal_model->InsertpTopUpC($data);
			redirect(base_url().'adminpanel/TopUp');
		} else {
			$data['TopUp'] = $this->Internal_model->GetAllTopUpCode();
			renderjrgbodyview('JRG/Pages/Donate/TopUp/index',$data);
		}
		
	}

	function deletetopupcode($id){
		$this->Internal_model->deletetopupcode($id);
		redirect(base_url().'adminpanel/TopUp');	
	}

	// ======================================= Donate =========================================== //
	
	
	// ======================================= Event =========================================== //
	function ConquerorsPath(){
		if($_POST){
			$Date = datebetween($_POST['DateFrom'],$_POST['DateTo']);

			foreach ($Date as $row) {
				$data = array(
					'CPDate'		=> $row,
					'GuNum'			=> 0,
					'CPPoints'		=> $_POST['DPoints'],
					'Status'		=> 0
				);

				$this->Internal_model->InsertConquerorPath($data);
			}

			$Schedule = $_POST['Schedule'];

			$data2 = array(
				'CPSchedule'		=> $Schedule,
				'GuNum'				=> 0,
				'Status'			=> 0
			);
			
			$this->Internal_model->InsertConquerorPathSchedule($data2);


			redirect(base_url().'adminpanel/ConquerorsPath');
		} else {
			$data['Score'] = $this->Internal_model->GetConquerorScore();
			$data['CPData'] = $this->Internal_model->GetConquerorData();
			renderjrgbodyview('JRG/Pages/Event/ConquerorsPath/index',$data);
		}
	}

	function GetConquerorPathDate(){
		if($_POST){
			$id = $_POST['e'];

			echo '
			
						
			<div class="form-group">
			<label class="form-label">Date:</label>
			<input type="text" name="CPDate" class="form-control wd-250" value="'.$this->Internal_model->GetConquerorPathDate($id).'" readonly>
			</div>
			'; 
			echo '<input type="hidden" name="CPID" value="'.$id.'">';
			echo '
				<div class="form-group">
                    <label class="form-label">Points:</label>
                    <input type="text" name="Points" class="form-control wd-250" placeholder="Points" value="'.$this->Internal_model->GetConquerorPathPoints($id).'" required>
                </div>';
		}
	}


	function UpdateConquerorPoints(){
		if($_POST){

			$data = array(
				'CPPoints'		=> $_POST['Points']
			);

			$this->Internal_model->UpdateCPPoints($_POST['CPID'],$data);

			redirect(base_url().'adminpanel/ConquerorsPath');
		}
	}

	// ======================================= Event =========================================== //


	// ======================================= Multimedia =========================================== //

	function Slider(){
		if($_POST){

				$filename = $_FILES['filename']['name'];
				FileSlider($filename);
				$data = array(
					'Image'		=> $filename,
					'Url'		=> $_POST['Url']
				);

				$this->Internal_model->InsertSlider($data);
				NotifySuccess('New Slider Image Successfully Added');
				RDirect('adminpanel/Slider');

		} else {
			$data['Slider'] = $this->Internal_model->GetSliderImage();
			renderjrgbodyview('JRG/Pages/Multimedia/Slider/index',$data);
		}
	}

	// ======================================= Multimedia =========================================== //

	// ==================================== DELETE CONFIRMATION ===================================== //
	function DeleteFunction($val,$id,$msg,$rd){
		$url = base_url().'AdminPanel/Deleting/'.VoidDecrypter2($val).'/'.VoidDecrypter2($id).'/'.VoidDecrypter2($rd).'';
		ANotifyConfirmation(VoidDecrypter2($msg),$url);
		RDirect('adminpanel/'.VoidDecrypter2($rd).'');
	}

	function Deleting($val,$id,$rd){
		$tbl = DeleteF($val)['tbl'];
		$Field = DeleteF($val)['field'];
		$this->Internal_model->DeleteFunc($tbl,$Field,$id);
		RDirect('adminpanel/'.$rd.'');
	}

	// ==================================== DELETE CONFIRMATION ===================================== //

	// ==================================== EDIT FUNCTION ===================================== //
	function Edit_Func($id){
		$tbl = VoidDecrypter2($_POST['Table']);
		$field = VoidDecrypter2($_POST['Flds']);
		$data = $this->Internal_model->GetEditData($tbl,$field,$id);
		$Msg = VoidDecrypter2($_POST['Msg']);
		$Rd = VoidDecrypter2($_POST['Rd']);
		$Opt = VoidDecrypter2($_POST['opt']);

		echo '<form method="POST" action="'.base_url().'adminpanel/Post_Edit/'.VoidEncrypter2($id).'/'.VoidEncrypter2($tbl).'/'.VoidEncrypter2($field).'/'.VoidEncrypter2($Msg).'/'.VoidEncrypter2($Rd).'" data-parsley-validate>
				<div class="pd-30 pd-sm-40 bg-gray-200 bd bd-2">';
		$NFields = "";
		foreach ($_POST['Fields'] as $Fields) {
			
			$opt = explode(",",$Opt);
			$FieldD = (explode("-",VoidDecrypter2($Fields)));
			$String = $FieldD[0];
			$Label = preg_replace('/(?<!\ )[A-Z]/', ' $0', $String);
			$NFields .= $FieldD[0].',';
			if($FieldD[1] == '2'){
				?>
				<div class="form-group">
                	<label class="form-label"><?=$Label?>:</label>
                        <div id="slWrapper" class="parsley-select wd-sm-250">
                        <select class="form-control select2" data-placeholder="Choose one" data-parsley-class-handler="#slWrapper" data-parsley-errors-container="#slErrorContainer" name="<?=$FieldD[0]?>" required>
							<option></option>
							<?php
							foreach ($opt as $val) {
								echo '<option value="'.$val.'" '.(($data[0][''.$FieldD[0].''] == $val) ? "SELECTED":"").'>'.$val.'</option>';
							}
							?>
							
                        </select>
                    	<div id="slErrorContainer"></div>
                    	</div>
                </div><!-- form-group -->
				<?php
			} else {
				?>
				<div class="form-group">
					<label class="form-label"><?=$Label?>:</label>
						<input type="text" name="<?=$FieldD[0]?>" class="form-control wd-250" value="<?=$data[0][''.$FieldD[0].'']?>" placeholder="<?=$FieldD[0]?>" maxlength="11" required>
				</div>
				<?php
			}
			
		}
		echo '<input type="hidden" name="NFields" value="'.$NFields.'">
			<br />
            	<button type="submit" class="btn btn-az-primary pd-x-20">Save</button>
            	</div>
			</form>';
			
			?>
			<script>
			$(function(){
				'use strict'
				$(document).ready(function(){
				$('.select2').select2({
					placeholder: 'Download Type'
				});

				$('.select2-no-search').select2({
					minimumResultsForSearch: Infinity,
					placeholder: 'Choose one'
				});
				});

				$('#selectForm').parsley();
				$('#selectForm2').parsley();

			});
			</script>
			<?php
	}

	function Post_Edit($id,$tbl,$field,$Msg,$Rd){
		$enumerate = explode(",",rtrim($_POST['NFields'],","));



		// var_dump($enumerate);
	
		$data = array();
		foreach ($enumerate as $Val) {
			$data[$Val] = $_POST[''.$Val.''];
		}


		// var_dump($data);
		$this->Internal_model->UpdateData(VoidDecrypter2($tbl),VoidDecrypter2($field),VoidDecrypter2($id),$data);
		ANotifySuccess(''.VoidDecrypter2($Msg).'');
		RDirect(VoidDecrypter2($Rd));
	}

	// ==================================== EDIT FUNCTION ===================================== //


	function ticket(){
		renderjrgbodyview('JRG/Pages/Ticket/index');
	}

}