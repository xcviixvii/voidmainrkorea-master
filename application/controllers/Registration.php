<?php

class Registration extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->ci_minifier->init(2);
		if ($this->session->userdata('IsAuth') == True) {
			?>
		 	<script> 
		 		location.href = "<?=base_url()?>"
		 	</script>
		 	<?php
	  	}	
	}

	function index() {
		$data['GSet'] = $this->Internal_model->GetPanelSettings();
			renderview('Registration/index',$data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */