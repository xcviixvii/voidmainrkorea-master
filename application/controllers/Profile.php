<?php

class profile extends CI_Controller {
	

	function index() {
		$this->ci_minifier->init(2);
		renderview('HomePage/Pages/Profile/index');
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */