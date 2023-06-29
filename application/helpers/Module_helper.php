<?php

/*****************************************************************************************************************************************************************************************************************************************************************************************************
---------------------------------------------------------------------------------------------------------------------------------------------------
               Editing the source code below may cause major disturbance on your website. Please DO NOT Modify things beyond this line.
---------------------------------------------------------------------------------------------------------------------------------------------------
                   
                                .______    ___________    ____  ______   .__   __.  _______          
                                |   _  \  |   ____\   \  /   / /  __  \  |  \ |  | |       \         
                                |  |_)  | |  |__   \   \/   / |  |  |  | |   \|  | |  .--.  |           
                                |   _  <  |   __|   \_    _/  |  |  |  | |  . `  | |  |  |  |                 
                                |  |_)  | |  |____    |  |    |  `--'  | |  |\   | |  '--'  |              
                                |______/  |_______|   |__|     \______/  |__| \__| |_______/              

                                        .___________. __    __   __       _______.
                                        |           ||  |  |  | |  |     /       | 
                                        `---|  |----`|  |__|  | |  |    |   (----`
                                            |  |     |   __   | |  |     \   \ 
                                            |  |     |  |  |  | |  | .----)   |
                                            |__|     |__|  |__| |__| |_______/ 
                                            .__.     .__. .__. .__. ._______.  
                                            |  |     |  | |  \ |  | |   ____|
                                            |  |     |  | |   \|  | |  |__
                                            |  |     |  | |  . `  | |   __| 
                                            |  `----.|  | |  |\   | |  |____
                                            |_______||__| |__| \__| |_______|

                                ._______   ______      .__   __.   ______   .___________.   
                                |       \ /  __  \     |  \ |  |  /  __  \  |           |   
                                |  .--.  |  |  |  |    |   \|  | |  |  |  | `---|  |----`        
                                |  |  |  |  |  |  |    |  . `  | |  |  |  |     |  |             
                                |  '--'  |  `--'  |    |  |\   | |  `--'  |     |  |              
                                |_______/ \______/     |__| \__|  \______/      |__|                  
                                                                                                                                     
                                .___  ___.   .____.   ._______  .__. ._______. ____    ____
                                |   \/   |  /  __  \  |       \ |  | |   ____| \   \  /   /
                                |  \  /  | |  |  |  | |  .--.  ||  | |  |__     \   \/   /
                                |  |\/|  | |  |  |  | |  |  |  ||  | |   __|     \_    _/
                                |  |  |  | |  `--'  | |  '--'  ||  | |  |          |  |
                                |__|  |__|  \______/  |_______/ |__| |__|          |__|

---------------------------------------------------------------------------------------------------------------------------------------------------
            Editing the source code below may cause major disturbance on your website. Please DO NOT Modify things beyond this line.
---------------------------------------------------------------------------------------------------------------------------------------------------
                                                    YOU HAVE BEEN WARNED.
                                                        BY: JRGDev
***************************************************************************************************************************************************
**************************************************************************************************************************************************/

// RENDER VIEW //
if(!function_exists('renderview')){

  function renderview($view, $vars=array(), $output = false){
    $CI = &get_instance();
    return $CI->load->view($view, $vars, $output);
  }
}

// RENDER HOMEPAGE BODY //
if(!function_exists('renderhomebodyview')){

  function renderhomebodyview($view, $vars=array(), $return = false){
    $CI = &get_instance();
    return $CI->load->renderhomebodyview($view, $vars, $return);
  }
}


// RENDER JRG BODY //
if(!function_exists('renderjrgbodyview')){

  function renderjrgbodyview($view, $vars=array(), $return = false){
    $CI = &get_instance();
    return $CI->load->renderjrgbodyview($view, $vars, $return);
  }
}

// UPLOAD SLIDER
if(!function_exists('FileSlider')){
  function FileSlider($filename){
    $CI = &get_instance();
    return $CI->upload_lib->Slider($filename);
  }
}

// UPLOAD ItemShop
if(!function_exists('FileItemShop')){
  function FileItemShop($filename){
    $CI = &get_instance();
    return $CI->upload_lib->ItemShop($filename);
  }
}


// UPLOAD Thumnail
if(!function_exists('FileThumbnail')){
  function FileThumbnail($filename){
    $CI = &get_instance();
    return $CI->upload_lib->Thumbnail($filename);
  }
}


// GENERATE PAGINATION
if(!function_exists('GenPage')){
  function GenPage($srch,$offset,$path,$tbl,$field,$where,$orderby,$db){
    $CI = &get_instance();
    return $CI->pagination_lib->GenPagination($srch,$offset,$path,$tbl,$field,$where,$orderby,$db);
  }
}

if(!function_exists('DeleteF')){
  function DeleteF($Function){
    if($Function == 'Delete Slider'){
      $data = array(
        'tbl'   => 'pSlider',
        'field' => 'SliderID',
      );
    }
    
    if($Function == 'Delete Code'){
      $data = array(
        'tbl'   => 'pTopUpCode',
        'field' => 'TopUpCID',
      );
    }

    if($Function == 'Delete Download'){
      $data = array(
        'tbl'   => 'download',
        'field' => 'downloadid',
      );
    }

    

    return $data;
  }
}

// ADD EDIT DELETE TEMPLATES //
if(!function_exists('EditDelete')){
  function EditDelete($editlink,$deletelink){

    $NewLink = '
    <a onclick="EditFunc('.$editlink.')" class="pointer" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Edit"><i class="fad fa-edit dodgerblue"></i></a>
    &nbsp;      
    <a href="'.$deletelink.'" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Delete"><i class="fas fa-trash-alt crimson"></i></a>
    ';
    return $NewLink;
  }
}



//Redirect URL
if(!function_exists('RDirect')){
  function RDirect($Path){
    if($Path){
      return redirect(base_url().''.$Path);
    } else {
      return redirect(base_url());
    }

  }
}


if(!function_exists('aside')){

  function aside($module){
    if($module == 'homepage' || $module == ''){
        $output = 'header-left-side';
    } else {
        $output = 'header-left-side2';
    }
    return $output;
  }
}

if(!function_exists('navclass')){

  function navclass($module){
    if($module == 'homepage' || $module == ''){
        $output = '';
    } else{
        $output = 'sub-nav';
    }
    return $output;
  }
}


if(!function_exists('charactersrc')){

  function charactersrc($module){
    if($module == 'homepage' || $module == ''){
        $output = base_url().'Images/Background/(KR)Character_180614.png';
    } elseif($module == 'MyAccount'){
        $output = base_url().'Images/sub_character09.png';
    } elseif($module == 'News'){
        $output = base_url().'Images/sub_character01.png';
    } elseif($module == 'Community'){
        $output = base_url().'Images/sub_character02.png';
    } elseif($module == 'ItemShop' || $module == 'MileageShop'){
        $output = base_url().'Images/sub_character04.png';
    } elseif($module == 'Ranking'){
        $output = base_url().'Images/sub_character06.png';
    } elseif($module == 'Download'){
        $output = base_url().'Images/sub_character07.png';
    } elseif($module == 'Donate'){
        $output = base_url().'Images/sub_character05.png';
    } elseif($module == 'MarketPlace'){
      $output = base_url().'Images/sub_character08.png';
    } elseif($module == 'Support'){
      $output = base_url().'Images/sub_character03.png';
    }
    return $output;
  }
}

if(!function_exists('NewsType')){
  function NewsType($Type){
    if($Type == 3) {
      $output = '<b style="color: rgb(65, 105, 225);">[Event]</b>';
    } elseif($Type == 2) {
      $output = '<b style="color: rgb(30, 144, 255);">[Update]</b>';
    } elseif($Type == 1) {
      $output = '<b style="color: rgb(255, 69, 0);">[Notice]</b>';
    }

    return $output;
  }
}



if(!function_exists('LeftMenu')){
  function LeftMenu($module,$Sub = NULL){
    if($module == 'homepage' || $module == '' || $module == 'Login'){
      $output = '';
    } elseif($module == 'MyAccount'){
      $output = 'LeftNavMyAccount/1'; 
    } elseif($module == 'View') {
      $output = 'LeftNav01/'.$Sub.'';
    } elseif($module == 'Notice') {
      $output = 'LeftNav01/10';
    } elseif($module == 'Update') {
      $output = 'LeftNav01/20';
    } elseif($module == 'Event') {
      $output = 'LeftNav01/30';
    } elseif($module == 'ScreenShot') {
      $output = 'LeftNav02/40';
    } elseif($module == 'ItemShop') {
      $output = 'LeftNav04/1';
    } elseif($module == 'MileageShop') {
      $output = 'LeftNav04/2';
    } elseif($module == 'CartList') {
      $output = 'LeftNav04/3';
    } elseif($module == 'BuyHistory') {
      $output = 'LeftNav04/4';   
    } elseif($module == 'ClaimEvent'){
      $output = 'LeftNav04/5'; 
    } elseif($module == 'Ranking'){
      $output = 'LeftNav06/1'; 
    } elseif($module == 'Download'){
      $output = 'LeftNav07/1'; 
    } elseif($module == 'Gold'){
      $output = 'LeftNav08/1';
    }elseif($module == 'MyPost'){
      $output = 'LeftNav08/5';
    } elseif($module == 'Items'){
      $output = 'LeftNav08/3';
    } elseif($module == 'Items2'){
      $output = 'LeftNav08/4';
    }elseif($module == 'MyPost'){
      $output = 'LeftNav08/5';
    } elseif($module == 'Donate'){
      $output = 'LeftNav09/1';
    } elseif($module == 'Support'){
      $output = 'LeftNav10/1';
    }elseif($module == 'Ticket'){
      $output = 'LeftNav10/2';
    }

    return $output;
  }
}



if(!function_exists('CDay')){
  function CDay($var){
    $output = '';
    if($var == '0'){
        $output = 'Sunday';
    } elseif($var == '1'){
        $output = 'Monday';
    } elseif($var == '2'){
        $output = 'Tuesday';
    } elseif($var == '3'){
        $output = 'Wednesday';
    } elseif($var == '4'){
        $output = 'Thursday';
    } elseif($var == '5'){
        $output = 'Friday';
    } elseif($var == '6'){
        $output = 'Saturday';
    }
    return $output;
  }
}