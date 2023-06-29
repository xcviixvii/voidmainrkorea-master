<?php

class Donate extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->ci_minifier->init(2);
	}

	function index() {
            $data['dnt'] = $this->Internal_model->GetDonate();
			$data['GSet'] = $this->Internal_model->GetPanelSettings();
			$data['Slider'] = $this->Internal_model->GetSliderImage();
            renderhomebodyview('HomePage/Pages/Donate/index',$data);
	}
}
