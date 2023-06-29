<?php

class Community extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->ci_minifier->init(2);
	
	}

	function ScreenShot() {
		$data['GSet'] = $this->Internal_model->GetPanelSettings();
		$data['Slider'] = $this->Internal_model->GetSliderImage();
		renderhomebodyview('HomePage/Pages/Community/ScreenShot/index',$data);
	}
}
