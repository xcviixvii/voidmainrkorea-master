<?php
class upload_lib {
	var $Slider_path; 
	var $ItemShop_path;
	var $Thumbnail_path;

	private $CI;

    function __construct() {
		$this->CI = &get_instance();
		$this->Slider_path = './Uploadfiles/Slider';
		$this->ItemShop_path = './Uploadfiles/ItemShop';
		$this->Thumbnail_path = './Uploadfiles/News';
    }
	 
	
	function Slider($filename){
        $format=explode('.',$filename);
        $format=end($format);
                
		$config['upload_path'] = $this->Slider_path;
		$config['file_name'] =  $filename;
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['max_size'] = '2048';
		$config['overwrite'] = TRUE;
		$config['remove_spaces'] = TRUE;

		$this->CI->load->library('upload', $config);	
		$image = "filename";

		if ($this->CI->upload->do_upload('filename')) {
		//Upload procedure
			$updata = array('upload_data' => $this->CI->upload->data());
			$config = array(
				'source_image'      => $updata['upload_data']['full_path'], //path to the uploaded image
				'new_image'         => $this->Slider_path, //path to
				'maintain_ratio'    => false,
				'width'             => 460,
				'height'			=> 210
			);
			$this->CI->load->library('image_lib', $config);
            $this->CI->image_lib->resize();	
        }
	}



	function ItemShop($filename){
        $format=explode('.',$filename);
        $format=end($format);
                
		$config['upload_path'] = $this->ItemShop_path;
		$config['file_name'] =  $filename;
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['max_size'] = '2048';
		$config['overwrite'] = TRUE;
		$config['remove_spaces'] = TRUE;

		$this->CI->load->library('upload', $config);	
		$image = "filename";

		if ($this->CI->upload->do_upload('filename')) {
		//Upload procedure
			$updata = array('upload_data' => $this->CI->upload->data());
			$config = array(
				'source_image'      => $updata['upload_data']['full_path'], //path to the uploaded image
				'new_image'         => $this->ItemShop_path, //path to
				'maintain_ratio'    => false,
				'width'             => 256,
				'height'			=> 256
			);
			$this->CI->load->library('image_lib', $config);
            $this->CI->image_lib->resize();	
        }
	}





	function Thumbnail($filename){
        $format=explode('.',$filename);
        $format=end($format);
                
		$config['upload_path'] = $this->Thumbnail_path;
		$config['file_name'] =  $filename;
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['max_size'] = '2048';
		$config['overwrite'] = TRUE;
		$config['remove_spaces'] = TRUE;

		$this->CI->load->library('upload', $config);	
		$image = "filename";

		if ($this->CI->upload->do_upload('filename')) {
		//Upload procedure
			$updata = array('upload_data' => $this->CI->upload->data());
			$config = array(
				'source_image'      => $updata['upload_data']['full_path'], //path to the uploaded image
				'new_image'         => $this->Thumbnail_path, //path to
				'maintain_ratio'    => false,
				'width'             => 125,
				'height'			=> 62
			);
			$this->CI->load->library('image_lib', $config);
            $this->CI->image_lib->resize();	
        }
	}
	


}