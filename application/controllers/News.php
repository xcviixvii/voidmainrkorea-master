<?php

class News extends CI_Controller {


	function __construct() {
		parent::__construct();
		$this->ci_minifier->init(2);
	}

	function View($id){
		$data['data'] = $this->llllllllz_model->GetNewsByID($id); 
		$data['GSet'] = $this->Internal_model->GetPanelSettings();
		$data['Slider'] = $this->Internal_model->GetSliderImage();
		renderhomebodyview('HomePage/Pages/News/View',$data);
	}


	function Notice($offset=0) {

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
	    
		
		$config['base_url']=base_url().'News/Notice/';
		$config['use_page_numbers'] = TRUE;
		$config['total_rows']=$this->llllllllz_model->GetallnoticeCounts();
		$config['per_page']=20;
		$config["num_links"] = 3;

		$this->pagination->initialize($config);
		if($offset != 0){
	      $offset = (($offset - 1) * $config["per_page"] );
	    }


		$data['notice'] = $this->llllllllz_model->Getallnotice($config['per_page'],$offset);
		$data['pages']=$this->pagination->create_links();
		$data['GSet'] = $this->Internal_model->GetPanelSettings();
		$data['Slider'] = $this->Internal_model->GetSliderImage();
		$data['highlights'] = $this->llllllllz_model->Getallnoticehighlights();
		renderhomebodyview('HomePage/Pages/News/Notice/index',$data);
	}


	function Update($offset=0) {

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
	    
		
		$config['base_url']=base_url().'News/Update/';
		$config['use_page_numbers'] = TRUE;
		$config['total_rows']=$this->llllllllz_model->GetallupdateCounts();
		$config['per_page']=20;
		$config["num_links"] = 3;

		$this->pagination->initialize($config);
		if($offset != 0){
	      $offset = (($offset - 1) * $config["per_page"] );
	    }


		$data['update'] = $this->llllllllz_model->Getallupdate($config['per_page'],$offset);
		$data['pages']=$this->pagination->create_links();
		$data['GSet'] = $this->Internal_model->GetPanelSettings();
		$data['Slider'] = $this->Internal_model->GetSliderImage();
		$data['highlights'] = $this->llllllllz_model->Getallupdatehighlights();
		renderhomebodyview('HomePage/Pages/News/Updates/index',$data);
	}


	function Event($offset=0) {

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
	    
		
		$config['base_url']=base_url().'News/Event/';
		$config['use_page_numbers'] = TRUE;
		$config['total_rows']=$this->llllllllz_model->GetalleventCounts();
		$config['per_page']=20;
		$config["num_links"] = 3;

		$this->pagination->initialize($config);
		if($offset != 0){
	      $offset = (($offset - 1) * $config["per_page"] );
	    }


		$data['event'] = $this->llllllllz_model->Getallevent($config['per_page'],$offset);
		$data['pages']=$this->pagination->create_links();
		$data['GSet'] = $this->Internal_model->GetPanelSettings();
		$data['Slider'] = $this->Internal_model->GetSliderImage();
		$data['highlights'] = $this->llllllllz_model->Getalleventhighlights();
		renderhomebodyview('HomePage/Pages/News/Events/index',$data);
	}

	

}
