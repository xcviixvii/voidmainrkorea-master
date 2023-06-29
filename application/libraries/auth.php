<?php

class Auth {

	private $CI;
	public function __construct() {
		$this->CI = &get_instance();
	}

	function LicenseChecker() {
        $ini_array = parse_ini_file("license.ini");
        $LicenseKey = $ini_array['LicenseKey'];

		// $License = $this->CI->Internal_model->CheckLicense($LicenseKey); // CHECKING LICENSE
		return $LicenseKey;
	}

	function isLicenseValid() {
		$isValid = $this->LicenseChecker();
		if ($isValid == true) {
            redirect_to('admin/login');
		} else {
			redirect_to('admin/login');
		}
	}
}
