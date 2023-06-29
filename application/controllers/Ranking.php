<?php

class Ranking extends CI_Controller {

    function __construct() {
    parent::__construct();
    $this->ci_minifier->init(2);
    }
    
	function index() {
        $data['ranking'] = $this->llllllllz_model->getranking();
        $data['GSet'] = $this->Internal_model->GetPanelSettings();
        $data['Slider'] = $this->Internal_model->GetSliderImage();
        renderhomebodyview('HomePage/Pages/Ranking/index',$data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */