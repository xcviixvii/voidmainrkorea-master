<?php

class testing_model extends CI_Model {
	
	function getdata(){
		$this->db->select('*')->from('page')
							  ->order_by('page_order','asc');
		$query = $this->db->get();
		return $query->result_array();
	}

	function updatesorting($id,$data) {
		$this->db->where(array('page_id'=>$id));
		$this->db->update('page',$data);
	}	

}