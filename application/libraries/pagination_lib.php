<?php

class pagination_lib {

	private $CI;
	public function __construct() {
		$this->CI = &get_instance();
	}

	function GenPagination($srch,$offset,$path,$tbl,$field,$where,$order,$db){
			$this->CI->load->library('pagination');
			$config['num_links'] = 4;
			$config['full_tag_open'] = "<ul class='pagination'>";
			$config['full_tag_close'] ="</ul>";
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
			$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
			$config['next_tag_open'] = "<li>";
			$config['next_tagl_close'] = "</li>";
			$config['prev_tag_open'] = "<li>";
			$config['prev_tagl_close'] = "</li>";
			$config['first_tag_open'] = "<li>";
			$config['first_tagl_close'] = "</li>";
			$config['last_tag_open'] = "<li>";
			$config['last_tagl_close'] = "</li>";
			$config['first_link'] = false;
			$config['last_link'] = false;
			$config['base_url']=base_url().''.$path;
			$config['total_rows']=$this->CI->llllllllz_model->mPaginationCount($srch,$tbl,$field,$where,$db);
			$config['use_page_numbers'] = TRUE;
			$config['per_page']=25;
			$this->CI->pagination->initialize($config);

			if($offset != 0){
			    $offset = (($offset - 1) * $config["per_page"] );
			}
			$data['records'] = $this->CI->llllllllz_model->mPagination($config['per_page'],$offset,$srch,$tbl,$field,$where,$order,$db);
            $data['pages']= $this->CI->pagination->create_links();
            

            return $data;
    }
}
