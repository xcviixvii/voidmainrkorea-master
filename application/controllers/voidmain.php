<?php

class voidmain extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->ci_minifier->init(2);

	}


	function index() {
			renderview('Voidmain/index');
	}
	
	function signout(){
		$this->session->sess_destroy();
		redirect(base_url().'voidmain');
	}
}
