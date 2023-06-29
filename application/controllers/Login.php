<?php

class Login extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->ci_minifier->init(2);

		if ($this->session->userdata('IsAuth') == True) {
			redirect(base_url());
	  	}	
	}
	
	function index() {
			renderview('Login/index');
	}
	
	function signout(){
		$this->session->sess_destroy();
		redirect(base_url());
	}
}
