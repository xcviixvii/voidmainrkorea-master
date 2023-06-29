
<?php
class WebService_model extends CI_Model {
	function __construct() {
		parent::__construct();
		$CI = &get_instance();
        $this->db2 = $this->load->database('db2', TRUE); // RanGame1
        $this->db3 = $this->load->database('db3', TRUE); // RanShop
        $this->db4 = $this->load->database('db4', TRUE); // RanUser
    }

    function GetNewItem(){
		$query = $this->db3->query("SELECT TOP 20 [ProductNum]
      ,[ItemMain]
      ,[ItemSub]
      ,[ItemName]
      ,[ItemSec]
      ,[ItemPrice]
      ,[Itemstock]
      ,[ItemCtg]
      ,[Itemexp]
      ,[ItemIco]
      ,[ItemSS]
      ,[date]
      ,[ItemComment]
      ,[ItemDisc]
      ,[hidden]
      ,[ItemCfg]
  FROM ".$this->db3->database.".[dbo].[ShopItemMap] WHERE ItemCfg IS NULL AND hidden != 1 ORDER BY ProductNum DESC");
		return $query->result_array();
    }
    
    function GetGMPick(){
		$query = $this->db3->query("SELECT TOP 20 [ProductNum]
      ,[ItemMain]
      ,[ItemSub]
      ,[ItemName]
      ,[ItemSec]
      ,[ItemPrice]
      ,[Itemstock]
      ,[ItemCtg]
      ,[Itemexp]
      ,[ItemIco]
      ,[ItemSS]
      ,[date]
      ,[ItemComment]
      ,[ItemDisc]
      ,[hidden]
      ,[ItemCfg]
  FROM ".$this->db3->database.".[dbo].[ShopItemMap] WHERE ItemCfg = 2 AND hidden != 1 ORDER BY ProductNum DESC");
		return $query->result_array();
	}
}

