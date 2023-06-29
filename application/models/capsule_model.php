<?php

class capsule_model extends CI_Model{

	function __construct(){
		parent::__construct();
		$CI = &get_instance();
        $this->db2 = $this->load->database('db2', TRUE);
        $this->db3 = $this->load->database('db3', TRUE);
        $this->db4 = $this->load->database('db4', TRUE);
	}

	function capsuleshoplist(){
		$query = $this->db->query("SELECT * FROM CapsuleItem Where IsUnique = 1 ORDER BY section DESC");
		return $query->result_array();
	}

	function capsuleshoplinklist($Num){
		$query = $this->db->query("SELECT * FROM CapsuleItem WHERE (ItemNum = ".$Num." OR ItemNumLink = ".$Num.") AND ItemNum != ItemNumLink");
		return $query->result_array();
	}

	function capsuleunique($Num){
		$query = $this->db->query("SELECT * FROM CapsuleItem WHERE ItemNum = ".$Num."");
		return $query->result_array();
	}

	function updatecapsulepoint($UserNum,$data){
		$this->db->where('UserNum',$UserNum);
			$this->db->update('Points',$data);	
	}

	function insertcapsule($data,$UserNum){
			$this->db->where('UserNum',$UserNum);
			$this->db->update('Points',$data);	
		
	}

	function getrandomitem(){
		$query = $this->db->query("SELECT TOP 1 * FROM ".$this->db->database.".[dbo].[CapsuleItem]  WHERE (IsUnique = 
	CASE 
            WHEN floor(rand() * 100) = 99
               THEN 1
               ELSE 0 
       END 
	   
	  ) ORDER BY NEWID()");
		return $query->result_array();
	}

	function getrandomitembycapsule($ItemNumLink){
		$query = $this->db->query("SELECT TOP 1 * FROM ".$this->db->database.".[dbo].[CapsuleItem] WHERE (ItemNum =  CASE 
            WHEN floor(rand() * 100) >= 1 
               THEN ".$ItemNumLink."
               ELSE 0 
       END  OR ItemNumLink = ".$ItemNumLink." ) ORDER BY NEWID()");
		return $query->result_array();
	}


	function GetCapsuleUniqueItem(){
		$query = $this->db->query("SELECT TOP 1 * FROM CapsuleItem WHERE (IsUnique = 
	CASE 
            WHEN floor(rand() * 100) = 99
               THEN 1
               ELSE 1 
       END 
	   
	  ) ORDER BY NEWID()");
		return $query->result_array();
	}

	function GetCapsuleCommonItem(){
		$query = $this->db->query("SELECT TOP 1 * FROM CapsuleItem WHERE (IsUnique = 
	CASE 
            WHEN floor(rand() * 100) = 99
               THEN 0
               ELSE 0 
       END 
	   
	  ) ORDER BY NEWID()");
		return $query->result_array();
	}


	function GetCapsuleUniqueItemByItem($ItemNumLink){
		$query = $this->db->query("SELECT TOP 1 * FROM CapsuleItem WHERE (IsUnique = 
	CASE 
            WHEN floor(rand() * 100) = 99
               THEN 1
               ELSE 1 
       END 
	   
	  )  AND ItemNum = ".$ItemNumLink." ORDER BY NEWID()");
		return $query->result_array();
	}


	function GetCapsuleCommonItemByItem($ItemNumLink){
		$query = $this->db->query("SELECT TOP 1 * FROM CapsuleItem WHERE (IsUnique = 
	CASE 
            WHEN floor(rand() * 100) = 99
               THEN 0
               ELSE 0 
       END 
	   
	  )  AND ItemNumLink = ".$ItemNumLink." ORDER BY NEWID()");
		return $query->result_array();
	}

	function getiteminfo($itemnum){
		$query = $this->db->query("SELECT * FROM CapsuleItem WHERE ItemNum = ".$itemnum."");
		return $query->result_array();
	}


	function insertwinner($data1){
		$this->db->insert('CapsuleWinner',$data1);	
	}

	function getcapsuleitemname($ProductNum){
		$query = $this->db3->query("SELECT TOP 1 * FROM ShopItemMap WHERE ProductNum = ".$ProductNum."");
		return $query->result_array();
	}

	function getcapswinner(){
		$query = $this->db->query("SELECT TOP 13 * FROM CapsuleWinner order by InsertDate Desc");
		return $query->result_array();
	}

}