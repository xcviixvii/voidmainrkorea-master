<?php

class Internal_model extends CI_Model {
	function __construct() {
		parent::__construct();
		$CI = &get_instance();
        $this->db2 = $this->load->database('db2', TRUE); // RanGame1
        $this->db3 = $this->load->database('db3', TRUE); // RanShop
        $this->db4 = $this->load->database('db4', TRUE); // RanUser
        $this->db5 = $this->load->database('db5', TRUE); // License

    }

    function voidlogin($username, $password) {
		$query = $this->db->query("SELECT TOP 1 [UserID]
      ,[UserName]
      ,[UserPass]
	  ,[UserTypeDesc]
      ,[UserStatus]
      ,[UserFName]
      ,[UserLName]
      ,[UserEmail]
      ,[GameName]
      ,[UserProfile]
      ,[Favicon]
      ,a.UserTypeID
    FROM pAccount a
    LEFT JOIN pUserType b ON a.UserTypeID = b.UserTypeID
    WHERE UserName='".$username."' AND UserPass='".$password."'");
		if ($query->num_rows() > 0) { return $query->first_row('array'); }
		else { return NULL;	}		
    }
    
    function FindActiveLicense($license,$api){
		$query = $this->db5->query("SELECT TOP 1 * FROM dbo.License WHERE LicenseKey='".$license."' AND API='".$api."' AND Status = 0");
		return $query->result();
    }

    function AdminAccounts($data = array()){
        return $this->db->insert('pAccount',$data);
    } 

    function SetStatus($UserID,$data){
        $this->db->where("UserID", $UserID);
        return $this->db->update("pAccount",$data);
    }

    function ValidateLicense($id,$data2){
      $this->db5->where("id", $id);
      return $this->db5->update("License", $data2);
   
    }

    // MODULE CONFIGURATION //
    function getAllModule($UserType){
        $query = $this->db->query("SELECT ModuleName,ModuleDesc,ParentModule,SeqNo,HasChild,ModuleIcon,UserTypeID,IsAdd,IsEdit,IsDelete,IsApprove,IsShow
  FROM pModule p 
  LEFT JOIN pModulePermission ON ModuleID = p.ID 
  WHERE UserTypeID = ".$UserType." AND IsShow = 1 AND SeqNo != 0  ORDER BY SeqNo ASC");
		return $query->result_array();
    }


    function getSubModule($ParentName,$UserType){
        $query = $this->db->query("SELECT ModuleName,ModuleDesc,ParentModule,SeqNo,HasChild,ModuleIcon,UserTypeID,IsAdd,IsEdit,IsDelete,IsApprove,IsShow
  FROM pModule p 
  LEFT JOIN pModulePermission ON ModuleID = p.ID 
  WHERE UserTypeID = ".$UserType." AND IsShow = 1 AND ParentModule = '".$ParentName."' AND SubSeqNo != 0 ORDER BY SubSeqNo ASC");
		return $query->result_array();
    }


    function getAllModuleByUserType(){
        $query = $this->db->query("SELECT ID,ModuleName,ModuleDesc,ParentModule,SeqNo,SubSeqNo,HasChild,ModuleIcon
  FROM pModule p 
  WHERE SeqNo != 0 
  ORDER BY SeqNo ASC
");
		return $query->result_array();
    }

    function getAllSubModuleByUserType($ParentName){
        $query = $this->db->query("SELECT ID,ModuleName,ModuleDesc,ParentModule,SeqNo,SubSeqNo,HasChild,ModuleIcon
  FROM pModule
  WHERE ParentModule = '".$ParentName."'
  AND SubSeqNo != 0 ORDER BY SubSeqNo ASC
");
		return $query->result_array();
    }


    function GetShowModule($ID,$UserTypeID){
      $query = $this->db->query("SELECT m.ID,ModuleName,ModuleDesc,ParentModule,SeqNo,SubSeqNo,HasChild,ModuleIcon,IsAdd,IsEdit,IsDelete,
IsApprove,IsShow,UserTypeID
    FROM pModule m
    LEFT JOIN pModulePermission mp ON mp.ModuleID = m.ID
    WHERE ModuleID = ".$ID." AND UserTypeID = ".$UserTypeID."");

    if ($query->num_rows() > 0) {  
    $ModInfo = $query->result_array();
    $Mod = $ModInfo[0]['IsShow']; 
    return $Mod;
    }
		else { return;	}	
		
    }


    function ParentModule($ModuleName) {
        $query = $this->db->query("SELECT [ID],[ModuleName],[ModuleDesc],[ParentModule],[SeqNo],[SubSeqNo],[HasChild],[ModuleIcon] FROM pModule WHERE ModuleName = '".$ModuleName."'");
		
		$ModInfo = $query->result_array();
		$Mod = $ModInfo[0]['ParentModule'];
		return $Mod;
    }



    function ModuleDesc($ModuleName){
        $query = $this->db->query("SELECT [ID],[ModuleName],[ModuleDesc],[ParentModule],[SeqNo],[SubSeqNo],[HasChild],[ModuleIcon] FROM pModule WHERE ModuleName = '".$ModuleName."'");
		
		$ModInfo = $query->result_array();
		$Mod = $ModInfo[0]['ModuleDesc'];
		return $Mod;
    }

    function getUserTypeByID($id){
        $query = $this->db->query("SELECT * FROM pUserType WHERE ID = ".$id."");
		return $query->result_array();
    }

    function GetUserTypeDescByID($id){
        $query = $this->db->query("SELECT * FROM pUserType WHERE ID = ".$id."");
        $ModInfo = $query->result_array();
        $Mod = $ModInfo[0]['UserTypeDesc'];
        return $Mod;
    }


    function getAllUserType(){
      $query = $this->db->query("SELECT * FROM pUserType");
		  return $query->result_array();
    }

    

    function ParentModuleByID($ModuleID) {
      $query = $this->db->query("SELECT [ID],[ModuleName],[ModuleDesc],[ParentModule],[SeqNo],[SubSeqNo],[HasChild],[ModuleIcon] FROM pModule WHERE ID = '".$ModuleID."'");
      $data = $query->result_array();

		  $val = $data[0]['ParentModule'];
      return $val;
    }

    function ParentModuleID($ParentName) {
      $query = $this->db->query("SELECT [ID],[ModuleName],[ModuleDesc],[ParentModule],[SeqNo],[SubSeqNo],[HasChild],[ModuleIcon] FROM pModule WHERE ModuleDesc = '".$ParentName."'");
		
      $ModInfo = $query->result_array();
      $Mod = $ModInfo[0]['ID'];
      return $Mod;
    }

    function GetParentStatus($ParentName) {
      $query = $this->db->query("SELECT [ID],[ModuleName],[ModuleDesc],[ParentModule],[SeqNo],[SubSeqNo],[HasChild],[ModuleIcon] FROM pModule WHERE ModuleDesc = '".$ParentName."'");
		
      $ModInfo = $query->result_array();
      $Mod = $ModInfo[0]['HasChild'];
      return $Mod;
    }


    function GetActiveModule($ParentName,$UserType) {
      $query = $this->db->query("SELECT m.[ID],[ModuleName],[ModuleDesc],[ParentModule],[SeqNo],[SubSeqNo],[HasChild],[ModuleIcon],[IsShow],[UserTypeID]
FROM pModule m
LEFT JOIN pModulePermission p ON p.ModuleID = m.ID
WHERE ParentModule = '".$ParentName."' AND UserTypeID = ".$UserType." AND IsShow = 1");
		
		  return $query->num_rows();
    }
    

    function ModuleIsShow($ModuleID,$UserType){
      $query = $this->db->query("SELECT * FROM pModulePermission WHERE ModuleID = '".$ModuleID."' AND UserTypeID = ".$UserType."");

      $ModInfo = $query->result_array();
      $Mod = $ModInfo[0]['IsShow'];
      return $Mod;
    }


    function UpdateModule($data,$ModuleID,$UserType){
      $query = $this->db->query("SELECT * FROM pModulePermission WHERE UserTypeID = ".$UserType." AND ModuleID = ".$ModuleID."");

      if ($query->num_rows() > 0) { 
        $this->db->where(array('ModuleID'=>$ModuleID,'UserTypeID'=>$UserType));
        $this->db->update('pModulePermission',$data);
      } else {   
        $this->db->insert('pModulePermission',$data);	
      }
    }

    function UpdateParentModule($data2,$ModuleID,$UserType){
      $query = $this->db->query("SELECT * FROM pModulePermission WHERE UserTypeID = ".$UserType." AND ModuleID = ".$ModuleID."");

      if ($query->num_rows() > 0) { 
        $this->db->where(array('ModuleID'=>$ModuleID,'UserTypeID'=>$UserType));
        $this->db->update('pModulePermission',$data2);
      } else {   
        $this->db->insert('pModulePermission',$data2);	
      }
    }


    function CheckPermission($ModuleName,$UserTypeID){
      $query = $this->db->query("SELECT m.[ID],[ModuleName],[ModuleDesc],[ParentModule],[SeqNo],[SubSeqNo],[HasChild],
[ModuleIcon],[IsShow],[UserTypeID]
FROM pModule m
LEFT JOIN pModulePermission p ON p.ModuleID = m.ID
WHERE ModuleName = '".$ModuleName."' AND UserTypeID = ".$UserTypeID." AND IsShow = 1");
      return $query->num_rows();
    }
    
    // MODULE CONFIGURATION //

    // ITEM SHOP //

    function AddToCart($data){
      $this->db->insert('pCart',$data);	
    }

    function InsertItemBank($data){
      $this->db3->insert('ShopPurchase',$data);	
    }

    function GetCartItemAlreadyExist($ProductNum){
      $query = $this->db->query("SELECT * FROM pCart WHERE ProductNum = ".$ProductNum."");
      return $query->num_rows();
    }
    
    function GetAllCartItemListByUser($UserID){
      $query = $this->db->query("SELECT CartID,c.ProductNum,s.ItemMain,s.ItemSub,Quantity,UserNum,ItemName,ItemSec,ItemPrice,Itemstock,ItemCtg,ItemSS,ItemDisc,ribbon,discount,u.ItemSub as SubItem,ItemPeriod,sectionname,categoryname
  FROM ".$this->db->database.".[dbo].[pCart] c
  LEFT JOIN [RanShop].[dbo].[ShopItemMap] s ON c.ProductNum = s.ProductNum
  LEFT JOIN ".$this->db->database.".[dbo].[pShopConfig] u ON c.ProductNum = u.ProductNum
  LEFT JOIN ".$this->db->database.".[dbo].[pItemCategory] a ON a.catid = s.ItemCtg
  LEFT JOIN ".$this->db->database.".[dbo].[pItemSection] b ON b.secid = s.ItemSec
  WHERE UserNum = ".$UserID."");
		  return $query->result_array();
    }

    function GetSubItemByItemSubID($ProdNum){
      $query = $this->db->query("SELECT *,c.ItemSub as ProductSubNum FROM ".$this->db->database.".[dbo].[pShopConfig] c
  LEFT JOIN ".$this->db3->database.".[dbo].[ShopItemMap] s ON c.ItemSub = s.ProductNum
  LEFT JOIN ".$this->db->database.".[dbo].[pItemCategory] a ON a.catid = s.ItemCtg
  LEFT JOIN ".$this->db->database.".[dbo].[pItemSection] b ON b.secid = s.ItemSec
  WHERE c.ProductNum = ".$ProdNum."");
      return $query->result_array();

    }

    function DeleteCartList($UserID){
      $this->db->where('UserNum', $UserID);
		  $this->db->delete('pCart');
    }

    function DeleteCartListByID($ProdID,$UserID){
      $this->db->where(array('UserNum'=> $UserID,'ProductNum'=> $ProdID));
		  $this->db->delete('pCart');
    }

    function UpdateShopStocks($data,$ProductNum){
      $this->db3->where('ProductNum', $ProductNum);
      $this->db3->update('ShopItemMap',$data);
    }

    function InsertBuyHistory($data){
      $this->db->insert('pBuyHistory',$data);	
    }

    // ITEM SHOP //


    // UPDATE POINTS //
    function UpdateUserPoints($data,$UserNum){
      $this->db->where('UserNum', $UserNum);
      $this->db->update('Points',$data);
    }

    function InsertPoints($data){
      $this->db->insert('Points',$data);	
    }

    function InsertUpdatePoints($data,$UserNum){
      $query = $this->db->query("SELECT * FROM Points WHERE UserNum = '".$UserNum."' ");

      if ($query->num_rows() > 0) { 
        $this->db->where('UserNum',$UserNum);
        $this->db->update('Points',$data);
      } else {   
        $this->db->insert('Points',$data);	
      }
    }


    function CheckCharOnline($UserNum){
      $query = $this->db2->query("SELECT P.ChaName,P.ChaSchool,P.ChaClass,P.ChaLevel,P.ChaPkWin,P.ChaPkLoss,P.ChaOnline,P.GuNum 
FROM ".$this->db2->database.".[dbo].[ChaInfo] P, ".$this->db4->database.".[dbo].[UserInfo] U 
WHERE P.Usernum = U.usernum and P.chadeleted = 0 AND P.ChaOnline = 1 AND U.UserNum = '".$UserNum."'");
      return $query->num_rows();
    }


    function UpdateUserInfo($UserNum,$data){
      $this->db4->where('UserNum',$UserNum);
      $this->db4->update('UserInfo',$data);
    }
    

    // UPDATE POINTS //




    // GIFT CODE //

    function ClaimCode($Code){
      $query = $this->db->query("SELECT * FROM ".$this->db->database.".[dbo].[pGiftCode] WHERE Code = '".$Code."' COLLATE SQL_Latin1_General_CP1_CS_AS");
      return $query->result_array();
    }

    function DeleteGiftCode($Code){
      $this->db->where('Code', $Code);
		  $this->db->delete('pGiftCode');
    }

    function AllClaimCode(){
      $query = $this->db->query("SELECT * FROM ".$this->db->database.".[dbo].[pGiftCode]");
      return $query->result_array();
    }

    function InsertpGiftCode($data){
      $this->db->insert('pGiftCode',$data);	
    }

    // GIFT CODE //

    // PANEL SETTINGS //

    function GetPanelSettings(){
      $query = $this->db->query("SELECT * FROM ".$this->db->database.".[dbo].[pPanelSettings]");
      return $query->result_array();
    }


    function GetFacebookMessengerPlugins(){
      $query = $this->db->query("SELECT * FROM ".$this->db->database.".[dbo].[pPanelSettings]");
      
      if ($query->num_rows() > 0) { 
        $data = $query->result_array();
        $val = $data[0]['FacebookScript'];
      } else {   
        $val = 0;	
      }
      return $val;
    }

    function GetMediaAds(){
      $query = $this->db->query("SELECT * FROM ".$this->db->database.".[dbo].[pPanelSettings]");
      
      if ($query->num_rows() > 0) { 
        $data = $query->result_array();
        $val = $data[0]['MediaAds'];
      } else {   
        $val = 0;	
      }
      return $val;
    }

    function GetServerStatus(){
      $query = $this->db->query("SELECT * FROM ".$this->db->database.".[dbo].[pPanelSettings]");
      
      if ($query->num_rows() > 0) { 
        $data = $query->result_array();
        $val = $data[0]['ServerStatus'];
      } else {   
        $val = 0;	
      }
      return $val;
    }



    function pPanelSettings($data){
      $query = $this->db->query("SELECT * FROM pPanelSettings");

      if ($query->num_rows() > 0) { 
        $this->db->where('PCID',1);
        $this->db->update('pPanelSettings',$data);
      } else {   
        $this->db->insert('pPanelSettings',$data);	
      }
    }

    // PANEL SETTINGS //

    // SERVER INFORMATION //

    function GetpServerInformation(){
      $query = $this->db->query("SELECT * FROM ".$this->db->database.".[dbo].[pServerInformation]");
      return $query->result_array();
    }

    function GetpServerInformationByID($id){
      $query = $this->db->query("SELECT * FROM ".$this->db->database.".[dbo].[pServerInformation] WHERE ServerInfoID = ".$id."");
      return $query->result_array();
    }


    function pServerInformation($data){
      $this->db->insert('pServerInformation',$data);	
    }

    function GetPlayerOnline(){
		$query = $this->db2->query("SELECT P.ChaName,P.ChaSchool,P.ChaClass,P.ChaLevel,P.ChaPkWin,P.ChaPkLoss,P.ChaOnline,P.GuNum FROM ".$this->db2->database.".[dbo].[ChaInfo] P, ".$this->db4->database.".[dbo].[UserInfo] U WHERE P.Usernum = U.usernum and U.Usertype = 1 and P.chadeleted = 0 AND P.ChaOnline = 1");
		return $query->num_rows();
	}

	function GetGMOnline(){
		$query = $this->db2->query("SELECT P.ChaName,P.ChaSchool,P.ChaClass,P.ChaLevel,P.ChaPkWin,P.ChaPkLoss,P.ChaOnline,P.GuNum FROM ".$this->db2->database.".[dbo].[ChaInfo] P, ".$this->db4->database.".[dbo].[UserInfo] U WHERE P.Usernum = U.usernum and U.Usertype != 1 and P.chadeleted = 0 AND P.ChaOnline = 1");
		return $query->num_rows();
  }
  
  function pDeleteServerInformation($id){
    $this->db->where('ServerInfoID', $id);
		$this->db->delete('pServerInformation');
  }

    // SERVER INFORMATION //

    function GetUser($UserName){
      $query = $this->db4->query("SELECT *  FROM UserInfo WHERE UserName = '".$UserName."'");
      return $query->num_rows();
    }

    function RegisterUser($data){
      $this->db4->insert('UserInfo',$data);	
    }

  function CheckEmail($email){
    $query = $this->db4->query("SELECT * FROM ".$this->db4->database.".[dbo].[UserInfo] WHERE UserEmail = '".$email."'");
    return $query->num_rows();
  }
  



  function getallitemshop(){
		$query = $this->db3->query("SELECT * FROM ShopItemMap");
		return $query->result_array();
  }


  function GetItemShop(){
		$query = $this->db3->query("SELECT * FROM ShopItemMap WHERE ItemCfg IS NULL");
		return $query->result_array();
  }


  function SetLevelReq($data){
    $query = $this->db->query("SELECT * FROM pShopReq");

      if ($query->num_rows() > 0) { 
        $this->db->where('ShopReqID',1);
        $this->db->update('pShopReq',$data);
      } else {   
        $this->db->insert('pShopReq',$data);	
      }
  }


  
  function getalluser(){
		$query = $this->db4->query("SELECT * FROM UserInfo");
		return $query->result_array();
	}

	function getalluseronline($UserNum){
		$query = $this->db2->query("SELECT * FROM ChaInfo WHERE UserNum = ".$UserNum." AND ChaOnline = 1");
		return $query->result_array();
  }
  

  function getalluserbyuserid($UserNum){
		$query = $this->db4->query("SELECT * FROM UserInfo WHERE UserNum = '".$UserNum."'");
		return $query->result_array();
  }
  
  function doinsertitembank($data){
		$this->db3->insert('ShopPurchase',$data);	
	}



  // ====================================== MAINTENANCE =========================================//
  function GetPanelAccounts(){
    $query = $this->db->query("SELECT * FROM pAccount");
		return $query->result_array();
  }

  function BackupDb1(){
    $query = $this->db2->query("BACKUP DATABASE ".$this->db2->database." TO DISK = 'C:\inetpub\wwwroot\Database\RanGame1.bak';
    ");

    return $query->result_array();
  }
  // ====================================== MAINTENANCE =========================================//


  // ====================================== MY ACCOUNT =========================================//
  function GetAllCharacter($UserID){
    $query = $this->db2->query("SELECT * FROM ChaInfo WHERE UserNum = ".$UserID." AND ChaDeleted = 0;");
		return $query->result_array();
  }

  function GetGameTime($UserNum){
		$query = $this->db4->query("SELECT * FROM ".$this->db4->database.".[dbo].[UserInfo] where UserNum = '".$UserNum."'");
		return $query->result_array();
  }	
  
  function GetUserCombatPoint($UserID){
    $query = $this->db4->query("SELECT * FROM ".$this->db4->database.".[dbo].[UserInfo] WHERE UserNum = ".$UserID."");
    $data = $query->result_array();
    $val = $data[0]['UserCombatPoint'];
    return $val;
  }

  function GetUserUserPoint($UserID){
    $query = $this->db4->query("SELECT * FROM ".$this->db4->database.".[dbo].[UserInfo] WHERE UserNum = ".$UserID."");
    $data = $query->result_array();
    $val = $data[0]['UserPoint'];
    return $val;
  }

  function GetUserUserName($UserID){
    $query = $this->db4->query("SELECT * FROM ".$this->db4->database.".[dbo].[UserInfo] WHERE UserNum = ".$UserID."");
    $data = $query->result_array();
    $val = $data[0]['UserName'];
    return $val;
  }

  // ====================================== MY ACCOUNT =========================================//
  function truncateAllTableInRanGame1(){
		$query = $this->db2->query("use RanGame1 EXEC sp_MSForEachTable 'TRUNCATE TABLE ?'");
		return $query->result_array();
  }

  function truncateAllTableInRanShop(){
		$query = $this->db3->query("use RanShop EXEC sp_MSForEachTable 'TRUNCATE TABLE ?'");
		return $query->result_array();
  }

  function truncateAllTableInRanUser(){
		$query = $this->db4->query("use RanUser EXEC sp_MSForEachTable 'TRUNCATE TABLE ?'");
		return $query->result_array();
  }

  function truncateTableInRanGame1($TableName){
		$query = $this->db2->query("TRUNCATE TABLE ".$TableName."");
		//return $query->result_array();
  }

  function truncateTableInRanShop($TableName){
		$query = $this->db3->query("TRUNCATE TABLE ".$TableName."");
		//return $query->result_array();
  }

  function truncateTableInRanUser($TableName){
		$query = $this->db4->query("TRUNCATE TABLE ".$TableName."");
		//return $query->result_array();
  }

    //=========================================================================================================================







    // ======================================== TOP UP ================================================//

    function InsertpTopUp($data){
      $this->db->insert('pTopUp',$data);
    }

    function GetAllTopUpCode(){
      $query = $this->db->query("SELECT * FROM pTopUpCode WHERE TopUpStatus = 0");
		  return $query->result_array();
    }

    function InsertpTopUpC($data){
      $this->db->insert('pTopUpCode',$data);
    }

    function deletetopupcode($id){
      $this->db->where('TopUpCID', $id);
		  $this->db->delete('pTopUpCode');
    }

    function DeleteCardDetails($Code,$Pin){
      $this->db->where(array('TopUpCode'=>$Code,'TopUpPin'=>$Pin));
		  $this->db->delete('pTopUpCode');
    }

    function GetCardDetails($Code,$Pin){
      $query = $this->db->query("SELECT * FROM pTopUpCode WHERE TopUpCode = '".$Code."' AND TopUpPin = '".$Pin."'");
		  
      if ($query->num_rows() > 0) { 
        $data = $query->result_array();
        $val = $data[0]['EPoints'];
      } else {   
        $val = 0;	
      }
      return $val;
    }

    // ======================================== TOP UP ================================================//


    // SEND ITEM BANK //

    function GetUserUserNum($ChaName){
      $query = $this->db2->query("SELECT * FROM ".$this->db2->database.".[dbo].[ChaInfo] WHERE ChaName = '".$ChaName."'");

      if ($query->num_rows() > 0) { 
        $data = $query->result_array();
        $val = $data[0]['UserNum'];
      } else {   
        $val = 0;	
      }
      return $val;
    }

    function GetUserName($UserName){
      $query = $this->db4->query("SELECT * FROM ".$this->db4->database.".[dbo].[UserInfo] WHERE UserName = '".$UserName."'");

      if ($query->num_rows() > 0) { 
        $data = $query->result_array();
        $val = $data[0]['UserNum'];
      } else {   
        $val = 0;	
      }
      return $val;
    }



    ///////// CAPSULE SHOP //////////////////////
    function getcapsuleimage($ItemNum){
      $query = $this->db3->query("SELECT * FROM ShopItemMap WHERE ProductNum = '".$ItemNum."'");
      return $query->result_array();
    }


    function GetCapsuleUniqueItem(){
      $query = $this->db->query("SELECT * FROM CapsuleItem WHERE IsUnique = 1");
      return $query->result_array();
    }

    function GetCapsuleSubItem($ItemNum){
      $query = $this->db->query("SELECT * FROM CapsuleItem WHERE ItemNumLink = ".$ItemNum." AND ItemNum != ".$ItemNum."");
      return $query->result_array();
    }


    function GetShopItemName($ItemNum){
      $query = $this->db3->query("SELECT * FROM ShopItemMap WHERE ProductNum = ".$ItemNum."");
      $data = $query->result_array();
      $val = $data[0]['ItemName'];
      return $val;
    }

    function GetShopItemSS($ItemNum){
      $query = $this->db3->query("SELECT * FROM ShopItemMap WHERE ProductNum = ".$ItemNum."");
      $data = $query->result_array();
      $val = $data[0]['ItemSS'];
      return $val;
    }


    function InsertCapsuleShopItem($data){
      $this->db->insert('CapsuleItem',$data);
    }

    function GetCapsuleItemUniqueCount(){
      $query = $this->db->query("SELECT * FROM CapsuleItem WHERE IsUnique = 1");
		  return $query->num_rows();
    }


    function DeleteCapsuleShopItem($ItemNum){
      $this->db->where('ItemNum', $ItemNum);
		  $this->db->delete('CapsuleItem');
    }

    function DeleteCapsuleLinkedItem($ItemNum){
      $this->db->where('ItemNumLink', $ItemNum);
		  $this->db->delete('CapsuleItem');
    }


    function GetCapsuleConfig(){
      $query = $this->db->query("SELECT * FROM ".$this->db->database.".[dbo].[CapsuleConfig]");
      return $query->result_array();
    }


    function CapsuleConfig($data){
      $query = $this->db->query("SELECT * FROM CapsuleConfig");

      if ($query->num_rows() > 0) { 
        $this->db->where('CCID',1);
        $this->db->update('CapsuleConfig',$data);
      } else {   
        $this->db->insert('CapsuleConfig',$data);	
      }
    }


    function GetCapsuleStatus(){
      $query = $this->db->query("SELECT * FROM CapsuleConfig");

      if ($query->num_rows() > 0) { 
        $data = $query->result_array();
        $val = $data[0]['CapsuleStatus'];
      } else {   
        $val = 0;	
      }
      return $val;
    }

    function GetCapsuleReq(){
      $query = $this->db->query("SELECT * FROM CapsuleConfig");

      if ($query->num_rows() > 0) { 
        $data = $query->result_array();
        $val = $data[0]['CapsuleReq'];
      } else {   
        $val = 0;	
      }
      return $val;
    }

    function GetPlayerChaLevel($UserID,$ChaLevel){
      $query = $this->db2->query("SELECT TOP 1 *
FROM ".$this->db2->database.".[dbo].[ChaInfo] WHERE UserNum = '".$UserID."' AND ChaLevel >= '".$ChaLevel."' AND ChaDeleted = 0");
      return $query->num_rows();
    }


    ///////// CAPSULE SHOP //////////////////////

    //////// VOTEING SYSTEM ///////////////////

    function GetVoteList(){
      $query = $this->db->query("SELECT * FROM pVoteSystem");
      return $query->result_array();
    }


    function GetVoteLink($VoteID){
      $query = $this->db->query("SELECT * FROM pVoteSystem WHERE VoteID = ".$VoteID."");
      $data = $query->result_array();
      $val = $data[0]['VoteUrl'];
      return $val;
    }

    function GetVotePoints($VoteID){
      $query = $this->db->query("SELECT * FROM pVoteSystem WHERE VoteID = ".$VoteID."");
      $data = $query->result_array();
      $val = $data[0]['VotePoints'];
      return $val;
    }

    function GetVoteTime($VoteID){
      $query = $this->db->query("SELECT * FROM pVoteSystem WHERE VoteID = ".$VoteID."");
      $data = $query->result_array();
      $val = $data[0]['VoteTime'];
      return $val;
    }

    function GetVoteLogs($UserName,$VoteID){
      $query = $this->db->query("SELECT TOP 1 * FROM pVoteLogs WHERE VoteID = ".$VoteID." AND UserName = '".$UserName."' ORDER BY LastVoteDatetime Desc");
      return $query->result_array();
    }


    function InsertVoteLogs($data){
      $this->db->insert('pVoteLogs',$data);
    }
    /////////// VOTING SYSTEM /////////////////////
    

    /////////// GOLD MARKET ///////////////////////

    function GetPostMarket($num,$offset){
      return $this->db
        ->select('*')
		    ->from("GoldMarket")
        ->order_by("marketid","DESC")
        ->limit($num, $offset)
        ->get()
		    ->result_array();


      // $query = $this->db->query("SELECT * FROM GoldMarket ORDER BY marketid Desc OFFSET ".$offset." ROWS FETCH NEXT ".$num." ROWS ONLY");
      return $query->result_array();
    }

    function GetChaNum($ChaNum){
      $query = $this->db2->query("SELECT * FROM ChaInfo WHERE ChaNum = '".$ChaNum."'");
      $data = $query->result_array();
      $val = $data[0]['ChaName'];
      return $val;
    }

    function GetUserNum($ChaNum){
      $query = $this->db2->query("SELECT * FROM ".$this->db2->database.".[dbo].[ChaInfo] WHERE ChaNum = '".$ChaNum."'");
      $data = $query->result_array();
      $val = $data[0]['UserNum'];
      return $val;
    }

    function GetPostMarketNumRows(){
      $query = $this->db->query("SELECT * FROM GoldMarket");
      return $query->num_rows();
    }

    function GetAllChaNum($UserID){
      $query = $this->db2->query("SELECT * FROM ChaInfo WHERE UserNum = ".$UserID." AND ChaDeleted != 1");
      return $query->result_array();
    }

    function GetGoldMarketPost($ChaNum){
      $query = $this->db->query("SELECT * FROM GoldMarket WHERE  ChaNum IN (".$ChaNum.")");
      return $query->result_array();
    }

    function GetMyPostMarketNumRows($ChaNum){
      $query = $this->db->query("SELECT * FROM GoldMarket WHERE ChaNum IN (".$ChaNum.")");
      return $query->num_rows();
    }

    function GetMyPostGoldMarket($ChaNum,$num,$offset){
      return $this->db
        ->select('*')
		    ->from("GoldMarket")
		    ->where("ChaNum IN (".$ChaNum.")")
        ->order_by("marketid","desc")
        ->limit($num, $offset)
        ->get()
        ->result_array();
    
      // $query = $this->db->query("SELECT * FROM GoldMarket WHERE ChaNum IN (".$ChaNum.")ORDER BY marketid Desc OFFSET ".$offset." ROWS FETCH NEXT ".$num." ROWS ONLY");
      // return $query->result_array();
    }


    




    /////////// GOLD MARKET ///////////////////////
    
    ////////// DONATE
    function GetDonate(){
      $query = $this->db->query("SELECT * FROM ".$this->db->database.".[dbo].[donate]");
      return $query->result_array();
    }


    ////////// DONATE




    /////////// CONQUEROR PATH ////////////////////////

    function GetConquerorData(){
        $query = $this->db->query("SELECT * FROM pConquerorPath");
      return $query->result_array();
    }

    function InsertConquerorPath($data){
      $this->db->insert('pConquerorPath',$data);	
    }

    function InsertConquerorPathSchedule($data){
      $this->db->insert('pConquerorPathConfig',$data);	
    }

    function GetClubDetails(){
      $query = $this->db->query("SELECT * FROM pConquerorPath");
      return $query->result_array();
    }


    function GetConquerorScore(){
      $query = $this->db->query("SELECT TOP 5 B.GuNum
	,B.GuName
	,sum(CPPoints) as Score
  FROM  [RanPanel2].[dbo].[pConquerorPath] G
  LEFT JOIN [RanGame1].[dbo].[GuildInfo] B ON G.GuNum = B.GuNum WHERE B.GuNum != 0
  GROUP BY B.GuNum,B.GuName
  ORDER BY sum(CPPoints) DESC");

      return $query->result_array();
    }


    function GetConquerorPathDate($id){
      $query = $this->db2->query("SELECT * FROM ".$this->db->database.".[dbo].[pConquerorPath] WHERE CPID = '".$id."'");
      $data = $query->result_array();
      $val = $data[0]['CPDate'];
      return $val;
    }

    function GetConquerorPathPoints($id){
      $query = $this->db2->query("SELECT * FROM ".$this->db->database.".[dbo].[pConquerorPath] WHERE CPID = '".$id."'");
      $data = $query->result_array();
      $val = $data[0]['CPPoints'];
      return $val;
    }

    function UpdateCPPoints($id,$data){
      $this->db->where('CPID', $id);
			$this->db->update('pConquerorPath',$data);	
    }


    function GetFirstDayOfConquerorPath(){
      $query = $this->db->query("SELECT TOP 1 * FROM pConquerorPath");
      $data = $query->result_array();
      $val = $data[0]['CPDate'];
      return $val;
    }

    function GetConquerorPathDayCount(){
      $query = $this->db->query("SELECT TOP 1 * FROM pConquerorPath");
      $data = $query->result_array();
      if($query->num_rows() == 0){
        return 0;
      } else {
        return $query->num_rows();
      }
    }

    function GetCPMonthNumberDays($month,$year){
      $query = $this->db->query("SELECT * FROM pConquerorPath WHERE MONTH([CPDate]) = ".$month." AND YEAR([CPDate]) = ".$year."");
      return $query->num_rows();
    }

    function GetCPWinner($date){
      $query = $this->db->query("SELECT * FROM pConquerorPath WHERE CPDate = '".$date."'");
      $data = $query->result_array();
      $val = $data[0]['GuNum'];
      return $val;
    }

    function GenerateGuildBadge($GuNum){
      $query = $this->db2->query("SELECT TOP 1 * FROM GuildInfo Where GuNum = '".$GuNum."'");
      $data = $query->result_array();
      if($query->num_rows() == 0){
        return 0;
      } else {
        $val = $data[0]['GuMarkImage'];
        return $val;
      }
    }

    function GenerateGuildName($GuNum){
      $query = $this->db2->query("SELECT TOP 1 * FROM GuildInfo Where GuNum = '".$GuNum."'");
      $data = $query->result_array();
      if($query->num_rows() == 0){
        return "";
      } else {
        $val = $data[0]['GuName'];
        return $val;
      }
    }


    function GetConquerorSchedule(){
      $query = $this->db->query("SELECT TOP 1 * FROM pConquerorPathConfig Where GuNum = 0 AND Status = 0");
      $data = $query->result_array();
      if($query->num_rows() == 0){
        return "";
      } else {
        $val = $data[0]['CPSchedule'];
        return $val;
      }
    }



    function UpdateConquerorsPathScore($date,$GuNum){
      $data = array(
        'GuNum' => $GuNum
      );

      $this->db->where('CPDate', $date);
			$this->db->update('pConquerorPath',$data);	
    }


    function GetCWWinner(){
      $query = $this->db2->query("SELECT TOP 1 * FROM GuildRegion WHERE RegionID = 4");
      $data = $query->result_array();
      if($query->num_rows() == 0){
        return 0;
      } else {
        $val = $data[0]['GuNum'];
        return $val;
      }
    }
    /////////// CONQUEROR PATH ////////////////////////
    
    /////////// MULTIMEDIA ////////////////////////

    function InsertSlider($data){
      $this->db->insert('pSlider',$data);	
    }

    function GetSliderImage(){
      $query = $this->db->query("SELECT * FROM pSlider");
      return $query->result_array();
    }

    function DeleteFunc($tbl,$Field,$id){
      $this->db->where($Field, $id);
		  $this->db->delete($tbl);
    }

    function GetEditData($tbl,$field,$id){
      $query = $this->db->query("SELECT * FROM ".$tbl." WHERE ".$field." = ".$id."");
      return $query->result_array();
    }

    function UpdateData($tbl,$field,$id,$data){
      $this->db->where($field,$id);
			$this->db->update($tbl,$data);	
    }

    /////////// MULTIMEDIA ////////////////////////



    /////////// TICKET ////////////////////////

    function InsertTicket($data){
      $this->db->insert('pTicket',$data);	
    }

    function InsertReplyTicket($data){
      $this->db->insert('pTicket_Reply',$data);	
    }

    function GetAllMyTicket($UserNum){
      $query = $this->db->query("SELECT * FROM pTicket WHERE UserNum = ".$UserNum."");
      return $query->result_array();
    }

    function GetTicket($TicketNum,$UserNum){
      $query = $this->db->query("SELECT * FROM pTicket WHERE TicketNumber = '".$TicketNum."' AND UserNum = ".$UserNum."");
      return $query->result_array();
    }


    function GetTicketReply($TicketNum){
      $query = $this->db->query("SELECT * FROM pTicket_Reply WHERE TicketNumber = '".$TicketNum."'");
      return $query->result_array();
    }


    function DeleteTicket($id){
      $this->db->where('TicketNumber', $id);
      $this->db->delete('pTicket');
      
      $this->db->where('TicketNumber', $id);
		  $this->db->delete('pTicket_Reply');
    }
    /////////// TICKET ////////////////////////
    
    function InsertUpdate($data,$fieldID,$id,$database,$db){
		$this->db->select('*')->from($database);
        $query = $this->db->get();
        
		if ($query->num_rows() <= 0) {
			$this->db->insert($database,$data);	
		} elseif($id != "") {
			$this->db->where($fieldID, $id);
			$this->db->update($database,$data);	
		} else {
			$this->db->where($fieldID, 1);
			$this->db->update($database,$data);	
		}
    }
    
    function deletefunction($database,$fieldID,$id,$db){
        if($db == 1){
		    $this->db->where($fieldID, $id);
		    $this->db->delete($database);
        } elseif($db == 2){
		    $this->db2->where($fieldID, $id);
		    $this->db2->delete($database);
        } elseif($db == 3){
		    $this->db3->where($fieldID, $id);
		    $this->db3->delete($database);
        } elseif($db == 4){
		    $this->db4->where($fieldID, $id);
		    $this->db4->delete($database);
        }
    }
    
    function databasequery($count = false,$db,$query){
        if($db == 1){
           $query = $this->db->query($query);
        } elseif($db == 2){
            $query = $this->db2->query($query);
        } elseif($db == 3){
            $query = $this->db3->query($query);
        } elseif($db == 4){
            $query = $this->db4->query($query);
        }

        if($count == true){
            return $query->num_rows();
        } else {
            return $query->result_array();
        }
    }





}    