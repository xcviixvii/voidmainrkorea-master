<?php

class Download extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->ci_minifier->init(2);
	}

	function index() {

			$data['dltype'] = $this->llllllllz_model->getalldownloadtype();
            $data['download'] = $this->llllllllz_model->getalldownloadlink();
			$data['GSet'] = $this->Internal_model->GetPanelSettings();
			$data['Slider'] = $this->Internal_model->GetSliderImage();
            renderhomebodyview('HomePage/Pages/Download/index',$data);
	}
}
