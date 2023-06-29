<?php
class MasterPage extends CI_Controller {

	function index(){
		renderview('WizardSetup/index');
		$this->ci_minifier->init(2);
	}
	function logout() {
		$this->session->sess_destroy();
		redirect(base_url());
	}

}
