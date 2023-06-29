<?php

class llllllllz_model extends CI_Model {
	function __construct() {
		parent::__construct();
		$CI = &get_instance();
        $this->db2 = $this->load->database('db2', TRUE); // RanGame1
        $this->db3 = $this->load->database('db3', TRUE); // RanShop
        $this->db4 = $this->load->database('db4', TRUE); // RanUser
	}

	function userlogin($username, $password) {
		$query = $this->db4->query("SELECT TOP 1 * FROM UserInfo WHERE UserName='".$username."' AND UserPass='".$password."'");
		if ($query->num_rows() > 0) { return $query->first_row('array'); }
		else { return NULL;	}		
	}


	function getChaInfo($userid){
		$query = $this->db2->query("SELECT TOP 1 * FROM ChaInfo WHERE UserNum = '".$userid."' AND ChaDeleted = 0");
		return $query->result_array();
	}

	function GetChaNum($ChaNum){
		$query = $this->db2->query("SELECT * FROM ChaInfo WHERE ChaNum = '".$ChaNum."' AND ChaDeleted != 1");
		return $query->result_array();
	}

	function getChaNumInfo($ChaNum){
		$query = $this->db2->query("SELECT TOP 1 * FROM ChaInfo WHERE ChaNum = '".$ChaNum."'");
		return $query->result_array();
	}

	function getpoints($userid){
		$query = $this->db->query("SELECT * FROM ".$this->db->database.".[dbo].[Points] WHERE UserNum = '".$userid."'");
		return $query->result_array();
	}

	function GetUserEpoints($UserNum){
		$query = $this->db->query("SELECT * FROM Points WHERE UserNum = ".$UserNum."");
		if ($query->num_rows() > 0) {
			$UserInfo = $query->result_array();
			$NewUserEPoints = $UserInfo[0]['EPoint'];
			return $NewUserEPoints;
		} else { 
			return 0;	
		}	

	}


	function findaccountfix($username, $password) {
		$query = $this->db4->query("SELECT TOP 1 * FROM UserInfo WHERE UserName='".$username."' AND UserPass='".$password."'");
		if ($query->num_rows() > 0) { return $query->first_row('array'); }
		else { return NULL;	}		
	}

	function fixaccount($UserNum){
		$query = $this->db2->query("SELECT TOP 10 P.UserNum,P.GuNum,P.ChaName,P.ChaSchool,P.ChaClass,P.ChaLevel,P.ChaExp,P.ChaOnline,
P.ChaPower,P.ChaDex,P.ChaSpirit,P.ChaStrength,P.ChaStrong,P.ChaStRemain FROM ".$this->db2->database.".[dbo].[ChaInfo] P, ".$this->db4->database.".[dbo].[UserInfo] U where P.Usernum = U.usernum and P.chadeleted = 0 AND P.UserNum = '".$UserNum."'");
		return $query->result_array();
	}	

	function fixingaccount($UserNum,$data){
		$this->db4->where('UserNum', $UserNum);
		$this->db4->update('UserInfo',$data);
	}

	
	/* RAN NEWS */
	function updatenews($id,$data){
		$this->db->where('newsid', $id);
		$this->db->update('news',$data);	
	}

	function getAllNews(){
		$this->db->select('*')->from('news')
							  ->where(array("newstatus"=>'Posted'))
							  ->order_by('newsid','desc')
							  ->limit(6);
		$query = $this->db->get();
		return $query->result_array();
	}

	function getNewsList(){
		$this->db->select('*')->from('news')
							->where(array("newstatus"=>'Posted'))
							->order_by('newsid','desc');
		$query = $this->db->get();
		return $query->result_array();
	}

	function getCountNewsList(){
		$this->db->select('*')->from('news')
							  ->where(array("newstatus"=>'Posted'))
							  ->order_by('newsid','desc');
		$query = $this->db->get();
		return $query->num_rows();
	}

	function getNewsListDraft(){
		$this->db->select('*')->from('news')
							->where(array("newstatus"=>'Draft'))
							->order_by('newsid','desc');
		$query = $this->db->get();
		return $query->result_array();
	}

	function getCountNewsListDraft(){
		$this->db->select('*')->from('news')
							  ->where(array("newstatus"=>'Draft'))
							  ->order_by('newsid','desc');
		$query = $this->db->get();
		return $query->num_rows();
	}

	function addnews($data){
		$this->db->insert('news',$data);	
	}

	function getnewsbyid($id){
		$this->db->select('*')->from('news')
							->where('newsid = '.$id.'');
		$query = $this->db->get();
		return $query->result_array();
	}

	function getnewsid($data){
		$this->db->insert('news',$data);
		$last_id = $this->db->insert_id();
		return $last_id;

	}

	function DeleteNews($id){
		$this->db->where('newsid', $id);
		$this->db->delete('news');
	}
	/* END RAN NEWS*/



	/* CHARACTER CLASS*/
	function getSchoolinfo0(){
		$query = $this->db2->query("SELECT sum(case when ChaClass IN(1,64) then 1 end) as brawler, 
		sum(case when ChaClass IN(2,128) then 1 end) as swordsman, 
		sum(case when ChaClass IN(4,256) then 1 end) as archer, 
		sum(case when ChaClass IN(8,512) then 1 end) as shaman, 
		sum(case when ChaClass IN(16,32) then 1 end) as extreme, 
		sum(case when ChaClass IN(1024,2048) then 1 end) as gunner, 
		sum(case when ChaClass IN(4096,8192) then 1 end) as assassin, 
		sum(case when ChaClass IN(16384,32768) then 1 end) as magician,
		sum(case when ChaClass IN(65536,131072) then 1 end) as shaper,
		sum(case when ChaDeleted=1 then 1 end) as deleted,
		sum(case when ChaOnline=1 then 1 end) as online,
		sum(1) as overall from ChaInfo Where ChaSchool = 0");
		return $query->result_array();
		
	}
	function getSchoolinfo1(){

		$query = $this->db2->query("SELECT sum(case when ChaClass IN(1,64) then 1 end) as brawler, 
		sum(case when ChaClass IN(2,128) then 1 end) as swordsman, 
		sum(case when ChaClass IN(4,256) then 1 end) as archer, 
		sum(case when ChaClass IN(8,512) then 1 end) as shaman, 
		sum(case when ChaClass IN(16,32) then 1 end) as extreme, 
		sum(case when ChaClass IN(1024,2048) then 1 end) as gunner, 
		sum(case when ChaClass IN(4096,8192) then 1 end) as assassin, 
		sum(case when ChaClass IN(16384,32768) then 1 end) as magician,
		sum(case when ChaClass IN(65536,131072) then 1 end) as shaper,
		sum(case when ChaDeleted=1 then 1 end) as deleted,
		sum(case when ChaOnline=1 then 1 end) as online,
		sum(1) as overall from ChaInfo Where ChaSchool = 1");

		return $query->result_array();
		
	}
	function getSchoolinfo2(){

	$query = $this->db2->query("SELECT sum(case when ChaClass IN(1,64) then 1 end) as brawler, 
		sum(case when ChaClass IN(2,128) then 1 end) as swordsman, 
		sum(case when ChaClass IN(4,256) then 1 end) as archer, 
		sum(case when ChaClass IN(8,512) then 1 end) as shaman, 
		sum(case when ChaClass IN(16,32) then 1 end) as extreme, 
		sum(case when ChaClass IN(1024,2048) then 1 end) as gunner, 
		sum(case when ChaClass IN(4096,8192) then 1 end) as assassin, 
		sum(case when ChaClass IN(16384,32768) then 1 end) as magician,
		sum(case when ChaClass IN(65536,131072) then 1 end) as shaper,
		sum(case when ChaDeleted=1 then 1 end) as deleted,
		sum(case when ChaOnline=1 then 1 end) as online,
		sum(1) as overall from ChaInfo Where ChaSchool = 2");

		return $query->result_array();
		
	}

	function gettop10ranking(){
		$query = $this->db2->query("SELECT TOP 10 P.ChaNum, P.ChaName,P.ChaSchool,P.ChaClass,P.ChaLevel,P.ChaExp,P.ChaOnline FROM ".$this->db2->database.".[dbo].[ChaInfo] P, ".$this->db4->database.".[dbo].[UserInfo] U 
where P.Usernum = U.usernum and U.Usertype = 1 and P.chadeleted = 0 ORDER BY P.Chalevel DESC, P.ChaPkWin DESC, P.ChaName ASC ");
		return $query->result_array();
	}

	function getnotice(){
		$query = $this->db->query("SELECT TOP 5 * FROM ".$this->db->database.".[dbo].[news] WHERE newstatus = 'Posted' ORDER BY newsid DESC");
		return $query->result_array();
	}

	function getClubLeader(){
		$query = $this->db2->query("SELECT * FROM GuildRegion");
		return $query->result_array();
	}

	function getClubName($GuNum){
		$query = $this->db2->query("SELECT * FROM GuildInfo WHERE GuNum=".$GuNum."");
		return $query->result_array();
	}


	/* Item Shop*/

	function getshopcfg($productnum){
		$query = $this->db->query("SELECT * FROM pShopConfig WHERE ProductNum = ".$productnum."");
		return $query->result_array();
	}
	function getallproduct(){
		$query = $this->db3->query("SELECT * FROM ShopItemMap WHERE ItemSec != 3");
		return $query->result_array();
	}

	function get_productbyimg($productnum) {
		$query = $this->db3->query("SELECT * FROM [".$this->db3->database."].[dbo].[ShopItemMap] WHERE ProductNum = ".$productnum."");

		$prodinfo = $query->result_array();
		$prodname = $prodinfo[0]['ItemSS'];
		return $prodname;
	}

	function get_productbyname($productnum) {
		$query = $this->db3->query("SELECT * FROM [".$this->db3->database."].[dbo].[ShopItemMap] WHERE ProductNum = ".$productnum."");

		$prodinfo = $query->result_array();
		$prodname = $prodinfo[0]['ItemName'];
		return $prodname;
	}

	function get_productprice($productnum) {
		$query = $this->db3->query("SELECT * FROM [".$this->db3->database."].[dbo].[ShopItemMap] WHERE ProductNum = ".$productnum."");

		$prodinfo = $query->result_array();
		$prodprice = $prodinfo[0]['ItemPrice'];
		return $prodprice;
	}

	function get_productStock($productnum) {
		$query = $this->db3->query("SELECT * FROM [".$this->db3->database."].[dbo].[ShopItemMap] WHERE ProductNum = ".$productnum."");

		$prodinfo = $query->result_array();
		$prodstock = $prodinfo[0]['Itemstock'];
		return $prodstock;
	}


	function getitemeshop(){
		$query = $this->db3->query("SELECT * FROM ".$this->db3->database.".[dbo].[ShopItemMap] P WHERE hidden != 1 AND P.ItemSec = 1 AND ItemCfg IS NULL order by ProductNum desc");
		return $query->result_array();
	}

	function getitemshopcount(){
		$query = $this->db3->query("SELECT * FROM ".$this->db3->database.".[dbo].[ShopItemMap] WHERE ItemCfg IS NULL");
		return $query->num_rows();
	}

	function GetMileageShopCount(){
		$query = $this->db3->query("SELECT * FROM ".$this->db3->database.".[dbo].[ShopItemMap] WHERE ItemCfg = 1");
		return $query->num_rows();
	}


	function getitemshopcountbyName($ProdName){
		$query = $this->db3->query("SELECT * FROM ".$this->db3->database.".[dbo].[ShopItemMap] WHERE ItemName = '".$ProdName."'");
		return $query->num_rows();
	}

	function getitemeshoppagination($num,$offset){
		return $this->db3
        ->select('*')
		->from("ShopItemMap")
		->where("ItemCfg IS NULL AND hidden != 1")
        ->order_by("ProductNum","desc")
        ->limit($num, $offset)
        ->get()
		->result_array();
		

		// $query = $this->db3->query("SELECT * FROM ".$this->db3->database.".[dbo].[ShopItemMap] WHERE ItemCfg IS NULL AND hidden != 1 ORDER BY ProductNum desc OFFSET ".$offset." ROWS FETCH NEXT ".$num." ROWS ONLY ");
		// return $query->result_array();
	}

	function GetMileageShopPagination($num,$offset){
		return $this->db3
        ->select('*')
		->from("ShopItemMap")
		->where("ItemCfg = 1 AND hidden != 1")
        ->order_by("ProductNum","desc")
        ->limit($num, $offset)
        ->get()
		->result_array();

		// $query = $this->db3->query("SELECT * FROM ".$this->db3->database.".[dbo].[ShopItemMap] WHERE ItemCfg = 1 ORDER BY ProductNum desc OFFSET ".$offset." ROWS FETCH NEXT ".$num." ROWS ONLY ");
		// return $query->result_array();
	}

	function getitemeshoppaginationbyname($ProdName){
		$query = $this->db->query("SELECT * FROM ".$this->db3->database.".[dbo].[ShopItemMap] WHERE ItemName = '".$ProdName."' AND hidden != 1  ORDER BY ProductNum desc");
		return $query->result_array();
	}

	function getitemebyitem($id){
		$query = $this->db3->query("SELECT * FROM ".$this->db3->database.".[dbo].[ShopItemMap] s 
LEFT JOIN ".$this->db->database.".[dbo].[pItemCategory] c ON c.catid = s.ItemCtg
LEFT JOIN ".$this->db->database.".[dbo].[pItemSection] b ON b.secid = s.ItemSec
WHERE ProductNum = ".$id."");
		return $query->result_array();
	}


	function GetBuyHistory($UserID){
		$query = $this->db->query("SELECT * FROM ".$this->db->database.".[dbo].[pBuyHistory] WHERE UserNum = '".$UserID."'");
		return $query->result_array();
	}

	function GetBuyHistoryCount($UserID){
		$query = $this->db->query("SELECT * FROM ".$this->db->database.".[dbo].[pBuyHistory] WHERE UserNum = '".$UserID."'");
		return $query->num_rows();
	}

	
	function GetBuyHistoryPagination($num,$offset,$UserID){
		// $query = $this->db->query("SELECT * FROM ".$this->db->database.".[dbo].[pBuyHistory] WHERE UserNum = '".$UserID."' ORDER BY HistoryID desc OFFSET ".$offset." ROWS FETCH NEXT ".$num." ROWS ONLY ");
		// return $query->result_array();

		return $this->db
        ->select('*')
		->from("pBuyHistory")
		->where("UserNum = ".$UserID."")
        ->order_by("HistoryID","desc")
        ->limit($num, $offset)
        ->get()
		->result_array();
	}

	/* SHOPMANAGER */
	function get_allproduct(){
		$query = $this->db3->query("SELECT * FROM ".$this->db3->database.".[dbo].[ShopItemMap] ORDER BY ProductNum DESC");
		return $query->result_array();
	}

	function get_newallproduct(){
		$query = $this->db3->query("SELECT * FROM ".$this->db3->database.".[dbo].[ShopItemMap] A,".$this->db->database.".[dbo].[pItemCategory] B,".$this->db->database.".[dbo].[pItemSection] C WHERE A.ItemSec = C.secid AND A.ItemCtg = B.catid ORDER BY ProductNum Desc");
		return $query->result_array();
	}

	function getitembyid($id){
		$query = $this->db3->query("SELECT * FROM ".$this->db3->database.".[dbo].[ShopItemMap] A,".$this->db->database.".[dbo].[pItemCategory] B,".$this->db->database.".[dbo].[pItemSection] C WHERE A.ItemSec = C.secid AND A.ItemCtg = B.catid and ProductNum = ".$id."");
		return $query->result_array();
	}

	function updateitem($id,$data){
		$this->db3->where(array('ProductNum'=>$id));
		$this->db3->update('ShopItemMap',$data);
	}

	function get_itemcategorybyid($id){
		$query = $this->db->query('SELECT * FROM pItemCategory WHERE secid = '.$id.' ORDER BY categoryorder ASC');
		return $query->result_array();
	}

	function getallsection(){
		$query = $this->db->query("SELECT * FROM pItemSection");
		return $query->result_array();
	}

	function getallsectionbysorting(){
		$query = $this->db->query("SELECT * FROM pItemSection ORDER BY sectionorder ASC");
		return $query->result_array();
	}

	function getconfigbyid($id){
		$query = $this->db->query("SELECT * FROM pShopConfig WHERE ProductNum = ".$id."");
		return $query->result_array();
	}

	function addsection($data){
		$this->db->insert('pItemSection',$data);
	}

	function insertitemconfig($data){
		$this->db->insert('pShopConfig',$data);
	}

	function addcategory($data){
		$this->db->insert('pItemCategory',$data);
	}

	function updateitemconfig($id,$data) {
		$this->db->where(array('ProductNum'=>$id));
		$this->db->update('pShopConfig',$data);
	}

	function updatesorting($id,$data) {
		$this->db->where(array('secid'=>$id));
		$this->db->update('pItemSection',$data);
	}

	function updatesorting1($id,$data) {
		$this->db->where(array('catid'=>$id));
		$this->db->update('pItemCategory',$data);
	}	

	function doupdateshoplevelreq($data){
		$this->db->select('*')->from('pShopReq');
		$query = $this->db->get();

		if ($query->num_rows() <= 0) {
			$this->db->insert('pShopReq',$data);	
		} else {
			$this->db->where('ShopReqID', 1);
			$this->db->update('pShopReq',$data);	
		}
	}	

	function getlevelreq(){
		$query = $this->db->query("SELECT * FROM pShopReq");
		return $query->result_array();
	}

	function additemshop($data){
		$this->db3->insert('ShopItemMap',$data);	
	}

	/* RANKING */
	function getranking(){
		$query = $this->db2->query("SELECT TOP 50 P.ChaNum,P.ChaName,P.ChaSchool,P.ChaClass,P.ChaLevel,P.ChaPkWin,P.ChaPkLoss,P.ChaOnline,P.GuNum FROM ".$this->db2->database.".[dbo].[ChaInfo] P, ".$this->db4->database.".[dbo].[UserInfo] U WHERE
			P.Usernum = U.usernum and U.Usertype = 1 and P.chadeleted = 0 
			ORDER BY P.Chalevel DESC,P.ChaPkWin DESC, P.ChaName ASC");
		return $query->result_array();
	}

	function getGuild($GuNum){

		$query = $this->db2->query("SELECT TOP 1 * FROM GuildInfo Where GuNum = '".$GuNum."'");
		return $query->result_array();
	}
	/* END RANKING */

	/* START CALENDAR */
	function getevents($date){
		$query = $this->db->query("SELECT *,CONVERT(VARCHAR(10), eventfrom, 23) as evefrom,CONVERT(VARCHAR(10), eventto, 23) as eveto FROM news WHERE '".$date."' BETWEEN CONVERT(VARCHAR(10), eventfrom, 23) AND CONVERT(VARCHAR(10), eventto, 23) AND newstatus = 'Posted' AND type = 3");
		return $query->result_array();

	}
	/* END CALENDAR */

	/* MANAGE ALL ACCOUNTS*/
	function getAllAccountCounts(){
		$query = $this->db4->query("SELECT * FROM UserInfo");
		return $query->num_rows();
	}

	function getAllAcc($perpage,$offset){
		$query = $this->db4->query("SELECT * FROM UserInfo ORDER BY UserNum asc OFFSET ".$offset." ROWS FETCH NEXT ".$perpage." ROWS ONLY");
		return $query->result_array();
	}

	function getLastLogIP($userID){
	
		$query = $this->db4->query("SELECT TOP 1 * FROM LogLogin Where UserID = '".$userID."' AND LogInOut = 1 ORDER BY LogDate Desc");
		return $query->result_array();
	}

	function getaccountpoints($UserNum){
		$query = $this->db->query("SELECT * FROM Points WHERE UserNum = ".$UserNum."");
		return $query->result_array();
	}

	function getAccountInformation($UserNum){
		$query = $this->db4->query("SELECT * FROM UserInfo Where UserNum = '".$UserNum."'");
		return $query->result_array();
	}

	function doupdateaccount($UserNum,$data){
		$db4 = $this->load->database('db4', TRUE);
		$db4->where('UserNum',$UserNum);
		$db4->update('UserInfo',$data);
	}

	function getAccountCharacter($UserNum){
		$query = $this->db2->query("SELECT * FROM ChaInfo Where UserNum = '".$UserNum."' AND ChaDeleted = 0;");
		return $query->result_array();
	}


	function GetUserInfo($UserNum){
		$query = $this->db4->query("SELECT * FROM UserInfo Where UserNum = '".$UserNum."'");
		return $query->result_array();
	}


	function getipaddress($userID){
		$query = $this->db4->query("SELECT IsNull(LogIpAddress,'') as puta FROM LogLogin Where UserID = '".$userID."' GROUP BY IsNull(LogIpAddress,'')");
		return $query->result_array();
	}

	/* DYNAMIC PAGINATION */
	function mPagination($num,$offset,$srch,$tbl,$field,$where,$order,$db){
		if($db == 2){
			return $this->db2
			->select('*')
			->from($tbl)
			->where($where)
			->like($field,$srch)
			->order_by($order)
			->limit($num, $offset)
			->get()
			->result_array();
		}elseif($db == 4){
			return $this->db4
			->select('*')
			->from($tbl)
			->where($where)
			->like($field,$srch)
			->order_by($order)
			->limit($num, $offset)
			->get()
			->result_array();
		}
			
	}


	function mPaginationCount($srch,$tbl,$field,$where,$db){
		if($db == 2){
			return $this->db2
			->select('*')
			->from($tbl)
			->where($where)
			->like($field,$srch)
			->get()
			->num_rows();
		}elseif($db == 4){
			return $this->db4
			->select('*')
			->from($tbl)
			->where($where)
			->like($field,$srch)
			->get()
			->num_rows();
		}
			
	}

	/* DYNAMIC PAGINATION */





	/* FILTERING */
	function srchallacc($num,$offset,$username){
		return $this->db4
        ->select('*')
		->from('UserInfo')
		->like('UserName',$username)
        ->order_by("UserNum")
        ->limit($num, $offset)
        ->get()
		->result_array();
		// $query = $this->db4->query("SELECT * FROM UserInfo WHERE UserName like '%".$username."%' ORDER BY UserNum Desc OFFSET ".$offset." ROWS FETCH NEXT ".$num." ROWS ONLY");
		// return $query->result_array();
	}

	function srchaccnum($username){
		$query = $this->db4->query("SELECT * FROM UserInfo WHERE UserName like '%".$username."%'");
		return $query->num_rows();
	}
	/* END ALL ACCOUNTS*/


	/* ALL CHARACTER */
	function getallcharCounts(){

		$query = $this->db2->query("SELECT * FROM ChaInfo Where ChaDeleted = 0");
		return $query->num_rows();
	}

	function getAllChar($perpage,$offset){
		return $this->db2
        ->select('*')
		->from('ChaInfo')
		->where('ChaDeleted = 0')
        ->order_by("ChaNum")
        ->limit($perpage, $offset)
        ->get()
		->result();
		
		// $this->db2->limit($offset, $perpage);
		// $query = $this->db2->get("ChaInfo"); 
        // if ($query->num_rows() > 0) {
        //     foreach ($query->result() as $row) {
        //         $data[] = $row;
        //     }
        //     return $data;
        // }
		// return false;
		
		// $query = $this->db2->query("SELECT * FROM ChaInfo WHERE ChaDeleted = 0 ORDER BY ChaNum asc OFFSET ".$offset." ROWS FETCH NEXT ".$perpage." ROWS ONLY");
		// return $query->result_array();
	}

	function srchcharnum($chaname){
		$query = $this->db2->query("SELECT * FROM ChaInfo WHERE ChaName like '%".$chaname."%'");
		return $query->num_rows();
	}

	function srchallchar($num,$offset,$chaname){
		return $this->db2
        ->select('*')
		->from('ChaInfo')
		->like('ChaName',$chaname)
        ->order_by("ChaNum")
        ->limit($num, $offset)
        ->get()
		->result();

		// $query = $this->db2->query("SELECT * FROM ChaInfo WHERE ChaName like '%".$chaname."%' and ChaDeleted = 0 ORDER BY UserNum Desc OFFSET ".$offset." ROWS FETCH NEXT ".$num." ROWS ONLY");
		// return $query->result_array();
	}

	function GetCharacterInfo($ChaNum){
		$query = $this->db2->query("SELECT * FROM ChaInfo Where ChaNum = '".$ChaNum."'");
		return $query->result_array();
	}

	function getusername($UserNum){
		$query = $this->db4->query("SELECT *  FROM UserInfo WHERE UserNum = ".$UserNum."");
		return $query->result_array();
	}

	function GetUName($UserNum){
		$query = $this->db4->query("SELECT *  FROM UserInfo WHERE UserNum = ".$UserNum."");

		$UserInfo = $query->result_array();
		$UName = $UserInfo[0]['UserName'];
		return $UName;

	}


	function UpdateCharacterInfo($ChaNum,$data){
		$this->db2->where('ChaNum', $ChaNum);
        $this->db2->update('ChaInfo',$data);
	}
	/* END OF CHARACTER */

	function getlaunchernotice(){
		$query = $this->db->query("SELECT TOP 7 * FROM ".$this->db->database.".[dbo].[news] ORDER BY newsid DESC");
		return $query->result_array();
	}

	function getsliderimg(){
		$query = $this->db->query("SELECT * FROM plugins");
		return $query->result_array();
	}

	function uploadplugins($data){
		$this->db->insert('plugins',$data);	
	}

	function adddownloadlink($data){
		$this->db->insert('download',$data);	
	}
	
	function deletedownloadlink($id){
    	$this->db->where('downloadid', $id);
		$this->db->delete('download');
  	}

	function getalldownloadlink(){
		$query = $this->db->query("SELECT * FROM download");
		return $query->result_array();
	}

	function getalldownloadtype(){
		$query = $this->db->query("SELECT DownloadType FROM download GROUP BY DownloadType");
		return $query->result_array();	
	}

	function GetDownloadLink(){
		$query = $this->db->query("SELECT TOP 1 * FROM download WHERE DownloadType = 'Game Client'");

		$dllink = $query->result_array();
		$dl = $dllink[0]['DownloadLink'];
		return $dl;
	}

	function getfriendlist($UserID,$offset,$pagelimit){
		return $this->db2
        ->select('ChaFriendNum,ChaP,ChaS,ChaFlag,ChaName,ChaOnline')
		->from("ChaFriend A,ChaInfo B")
		->where("ChaP = '".$UserID."' AND B.ChaNum = A.ChaS")
        ->order_by("ChaFriendNum","ASC")
        ->limit($pagelimit, $offset)
        ->get()
		->result_array();

		// $query = $this->db2->query("SELECT [ChaFriendNum],[ChaP],[ChaS],[ChaFlag],[ChaName],[ChaOnline] FROM ".$this->db2->database.".[dbo].[ChaFriend] A,".$this->db2->database.".[dbo].[ChaInfo] B WHERE ChaP = '".$UserID."' AND B.ChaNum = A.ChaS ORDER BY ChaFriendNum ASC OFFSET ". $offset." ROWS FETCH NEXT ".$pagelimit." ROWS ONLY");
		// return $query->result_array();	
	}

	function getfriendlistcount($UserID){
		$query = $this->db2->query("SELECT * FROM ".$this->db2->database.".[dbo].[ChaFriend] WHERE ChaP = '".$UserID."'");
		return $query->num_rows();	
	}

	function GetGuildMemberList($GuNum,$UserID,$offset,$pagelimit){
		return $this->db2
        ->select('UserNum,ChaName,ChaOnline')
		->from("ChaInfo")
		->where("GuNum = ".$GuNum." AND UserNum != '".$UserID."'")
        ->order_by("UserNum","ASC")
        ->limit($pagelimit, $offset)
        ->get()
		->result_array();

		// $query = $this->db2->query("SELECT [UserNum],[ChaName],[ChaOnline] FROM ".$this->db2->database.".[dbo].[ChaInfo] WHERE GuNum = ".$GuNum." AND UserNum != '".$UserID."' ORDER BY UserNum ASC OFFSET ". $offset." ROWS FETCH NEXT ".$pagelimit." ROWS ONLY");
		// return $query->result_array();	
	}

	function GetGuildMemberListCount($GuNum,$UserID){
		$query = $this->db2->query("SELECT * FROM ".$this->db2->database.".[dbo].[ChaInfo] WHERE GuNum = ".$GuNum." AND UserNum != '".$UserID."'");
		return $query->num_rows();	
	}

	

	/* GET ALL NOTICE */
	function Getallnoticehighlights(){
		$this->db->select('*')->from('news')
							  ->where(array("newstatus"=>'Posted','type'=>1))
							  ->where('highlights IS NOT NULL')
							  ->order_by('newsid','desc')
							  ->limit(5);
		$query = $this->db->get();
		return $query->result_array();
	}

	function Getallnotice($perpage,$offset){
		return $this->db
        ->select('*')
		->from("news")
		->where("newstatus = 'Posted' AND type = 1 AND highlights IS NULL")
        ->order_by("newsid","desc")
        ->limit($perpage, $offset)
        ->get()
		->result_array();
		

		// $query = $this->db->query("SELECT * FROM news WHERE newstatus = 'Posted' AND type = 1 AND highlights IS NULL ORDER BY newsid DESC OFFSET ".$offset." ROWS FETCH NEXT ".$perpage." ROWS ONLY");
		// return $query->result_array();
	}

	function GetallnoticeCounts(){
		$query = $this->db->query("SELECT * FROM news WHERE newstatus = 'Posted' AND type = 1 AND highlights IS NULL");
		return $query->num_rows();
	}

	/* GET ALL UPDATES */

	function Getallupdatehighlights(){
		$this->db->select('*')->from('news')
							  ->where(array("newstatus"=>'Posted','type'=>2))
							  ->where('highlights IS NOT NULL')
							  ->order_by('newsid','desc')
							  ->limit(5);
		$query = $this->db->get();
		return $query->result_array();
	}

	function Getallupdate($perpage,$offset){
		return $this->db
        ->select('*')
		->from("news")
		->where("newstatus = 'Posted' AND type = 2 AND highlights IS NULL")
        ->order_by("newsid","desc")
        ->limit($perpage, $offset)
        ->get()
		->result_array();


		// $query = $this->db->query("SELECT * FROM news WHERE newstatus = 'Posted' AND type = 2 AND highlights IS NULL ORDER BY newsid DESC OFFSET ".$offset." ROWS FETCH NEXT ".$perpage." ROWS ONLY");
		// return $query->result_array();
	}

	function GetallupdateCounts(){
		$query = $this->db->query("SELECT * FROM news WHERE newstatus = 'Posted' AND type = 2 AND highlights IS NULL");
		return $query->num_rows();
	}

	/* GET ALL EVENTS */

	function Getalleventhighlights(){
		$this->db->select('*')->from('news')
							  ->where(array("newstatus"=>'Posted','type'=>3))
							  ->where('highlights IS NOT NULL')
							  ->order_by('newsid','desc')
							  ->limit(5);
		$query = $this->db->get();
		return $query->result_array();
	}

	function Getallevent($perpage,$offset){
		return $this->db
        ->select('*')
		->from("news")
		->where("newstatus = 'Posted' AND type = 3 AND highlights IS NULL")
        ->order_by("newsid","desc")
        ->limit($perpage, $offset)
        ->get()
		->result_array();

		// $query = $this->db->query("SELECT * FROM news WHERE newstatus = 'Posted' AND type = 3 AND highlights IS NULL ORDER BY newsid DESC OFFSET ".$offset." ROWS FETCH NEXT ".$perpage." ROWS ONLY");
		// return $query->result_array();
	}

	function GetalleventCounts(){
		$query = $this->db->query("SELECT * FROM news WHERE newstatus = 'Posted' AND type = 3 AND highlights IS NULL");
		return $query->num_rows();
	}




	// GOLD MARKET
	function characterperuser($UserNum){
		$query = $this->db2->query("SELECT * FROM ChaInfo WHERE UserNum = '".$UserNum."' AND ChaDeleted != 1");
		return $query->result_array();
	}

	function doupdatepoint($UserNum,$data1){
		$this->db->where('UserNum', $UserNum);
		$this->db->update('Points',$data1);
	}

	function doinsertvp($data1){
		$this->db->insert('Points',$data1);
	}

	function dopostgold($data){
		$this->db->insert('GoldMarket',$data);
	}

	function doupdatemarket($marketid,$data){
		$this->db->where('marketid', $marketid);
		$this->db->update('GoldMarket',$data);
	}

	function doinsertlocker($data){
		$this->db2->insert('UserInven',$data);	
	}

	function doupdatelocker($UserNum,$data1){
		$this->db2->where('UserNum', $UserNum);
		$this->db2->update('UserInven',$data1);
	}

	function doupdategold($ChaNum,$data){
		$this->db2->where('ChaNum', $ChaNum);
		$this->db2->update('ChaInfo',$data);
	}

	function searchpostmarket($num,$offset,$stat,$amount){
		$query = $this->db->query("SELECT * FROM GoldMarket ".$stat." ".$amount." ORDER BY marketid Desc OFFSET ".$offset." ROWS FETCH NEXT ".$num." ROWS ONLY");
		return $query->result_array();
	}

	function searchpostmarketnumrows($stat,$amount){
		$query = $this->db->query("SELECT * FROM GoldMarket ".$stat." ".$amount."");
		return $query->num_rows();
	}

	function getpostmarketbyid($marketid){
		$query = $this->db->query("SELECT * FROM GoldMarket WHERE marketid = ".$marketid."");
		return $query->result_array();
	}

	
	//GET LOCKER MONEY
	function getlockermoney($UserNum){
		$query = $this->db2->query("SELECT * FROM UserInven WHERE UserNum = ".$UserNum."");
		return $query->result_array();
	}

	//CheckUser

	function CheckUser($UserNum){
		$query = $this->db4->query("SELECT * FROM UserInfo WHERE UserNum = ".$UserNum."");
		return $query->result_array();
	}

	// ALL SOLD IN GOLD MARKET AFTER 1DAY
	function deletesoldmarket(){
		$this->db->where("DATEDIFF(DAY, dtetmesold, CURRENT_TIMESTAMP) >= 1");
		$this->db->delete('GoldMarket');
	}


	function cancelmarketpost($marketid){
		$this->db->where("marketid = ".$marketid."");
		$this->db->delete('GoldMarket');
	}

	function searchmarketing($stat,$amount){
		$query = $this->db->query("SELECT * FROM GoldMarket WHERE ".$stat." ".$amount."");
		return $query->result_array();
	}

	// GOLD MARKET


}