<?php

class MileageShop extends CI_Controller {
	
	function __construct() {
        parent::__construct();
        $this->ci_minifier->init(2);
			$this->session->unset_userdata('path');
	}

	function index($offset = 0) {
			$this->load->library('pagination');
			$config['full_tag_open'] = "<section class='paging'><ul>";
            $config['full_tag_close'] ="</ul></section>";
            $config['cur_tag_open'] = "<li class='on try' style='float:left;'>";
            $config['cur_tag_close'] = "</li>";
            $config['num_tag_open'] = "<li class='try' style='float:left;'>";
            $config['num_tag_close'] = "</li>";

            $config['prev_tag_open'] = "<li class='image' style='float:left;'>";
            $config['prev_tag_close'] = "</li>";
            $config['prev_link'] = '<img src="'.base_url().'Images/Board/prev_page_btn.gif" alt="">';

            $config['next_tag_open'] = "<li class='image' style='float:left;'>";
            $config['next_tag_close'] = "</li>";
            $config['next_link'] = '<img src="'.base_url().'Images/Board/next_page_btn.gif" alt="">';

            $config['first_tag_open'] = "<li class='image' style='float:left;'>";
            $config['first_tag_close'] = "</li>";
            $config['first_link'] = '<img src="'.base_url().'Images/Board/first_page_btn.gif" alt="">';
            
            $config['last_tag_open'] = "<li class='image' style='float:left;'>";
            $config['last_tag_close'] = "</li>";
            $config['last_link'] = '<img src="'.base_url().'Images/Board/last_page_btn.gif" alt="">';
			$config['base_url']=base_url().'MileageShop/Page';
			$config['total_rows']=$this->llllllllz_model->GetMileageShopCount();
			$config['use_page_numbers'] = TRUE;
			$config['per_page']=12;
			$this->pagination->initialize($config);
			if($offset != 0){
			    $offset = (($offset - 1) * $config["per_page"] );
			}
			$data['count'] = $this->llllllllz_model->GetMileageShopCount();
			$data['MileageShop'] = $this->llllllllz_model->GetMileageShopPagination($config['per_page'],$offset);
			$data['pages']=$this->pagination->create_links();
            $data['GSet'] = $this->Internal_model->GetPanelSettings();
            $data['Slider'] = $this->Internal_model->GetSliderImage();
            renderhomebodyview('HomePage/Pages/MileageShop/index',$data);
    }
}