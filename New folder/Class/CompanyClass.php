<?php
 date_default_timezone_set('Asia/Tehran');
 class CompanyClass
{
	
    private $response = array();
    function __construct(){}
	
	public function VersionInfo(){

		echo "{\"Text\":\"2.0\"}";

    }
	
			
	public function Banner(){
		$directoryToScan = "\xampp\htdocs\login\SlideImage";

		define('WEBSITE', "/login");
	
		$array = array();
		$num_files = count(glob("/xampp/htdocs/login/SlideImage/*.jpg"));
		$totalFiles = (string)$num_files;
		foreach (glob("/xampp/htdocs/login/SlideImage/*.jpg") as $filename) {
			$object_url = str_replace("/xampp/htdocs/login", '', $filename) ;
			$object_name = str_replace("/xampp/htdocs/login/SlideImage/", '', $filename) ;
			$object_name = str_replace(".jpg", '', $object_name) ;			
			$turl = WEBSITE.$object_url;
			$url = str_replace("\/", "\\", $turl);
			if(substr($object_name,0,5)!="image"){
					array_push($array, array('GoodName'=>$object_name,'GoodImageUrl'=>$url));
			}
		}
		$Last =  json_encode($array, JSON_UNESCAPED_UNICODE);
		echo "{\"Goods\":".$Last."}";
	}			
	
	public function BasketGet(){
		
		if (isset($_REQUEST['Mobile']))	{ $Mobile = $_REQUEST['Mobile'];}else {$Mobile = "";};
		
		$sq ="Exec [dbo].[spApp_BasketGet] '".$Mobile."'";
		MainClass::LogFile("BasketGet",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Goods\":".$Last."}";
		
	}
	
	public function BasketHistory(){
	
		
		if (isset($_REQUEST['Mobile']))	{ $Mobile = $_REQUEST['Mobile'];}else {$Mobile = "";};
		if (isset($_REQUEST['Code']))	{ $Code = $_REQUEST['Code'];}else {$Code = "0";};
		if (isset($_REQUEST['ReservedRows']))	{ $ReservedRows = $_REQUEST['ReservedRows'];}else {$ReservedRows = "null";};
		$sq ="Exec [dbo].[spApp_BasketPreFactors] '".$Mobile."',$Code,$ReservedRows";

		MainClass::LogFile("BasketHistory",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"PreFactors\":".$Last."}";
		
	}
	
	public function BasketToPreFactor(){
		$UserId = -2000;
		
		if (isset($_REQUEST['Mobile']))	{ $Mobile = $_REQUEST['Mobile'];}else {$Mobile = "";};
		if (isset($_REQUEST['Explain'])) { $Explain = $_REQUEST['Explain'];}else {$Explain = "NoExplain";};
		
		$sq ="Exec [dbo].[spApp_BasketToPreFactor] '".$Mobile."',$UserId , '".$Explain."'";
		

		MainClass::LogFile("BasketToPreFactor",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Goods\":".$Last."}";
		
	}
	
	public function BasketSum(){
		if (isset($_REQUEST['Mobile']))	{ $Mobile = $_REQUEST['Mobile'];}else {$Mobile = "";};
		
		$sq ="Exec dbo.spApp_BasketSummary '".$Mobile."'";
		MainClass::LogFile("BasketSum",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Goods\":".$Last."}";
		
	}
				
	public function Basketdeleteall(){
		if (isset($_REQUEST['Mobile']))	{ $Mobile = $_REQUEST['Mobile'];}else {$Mobile = "";};
		$sq ="  set nocount on Update AppBasket set ProcessStatus = 10 where MobileNo = '".$Mobile."' and ProcessStatus = 0 select 1 ";
		MainClass::LogFile("Basketdeleteall",$sq);
		$result = database::custom_sqlSRV($sq, false);
		if ($result) {
			echo "{\"Text\":\"done\"}";
		} else {
			echo "{\"Text\":\"false\"}";
		}
		
	}		


	public function check_server(){
		echo "{\"Text\":\"false\"}";
    }
		

	public function deletebasket(){
		
		$DeviceCode="";
		$GoodRef=0;
		$UserId=-2000;
		$Mobile="";
	
		if (isset($_REQUEST['DeviceCode']))	{ $DeviceCode = $_REQUEST['DeviceCode'];}	 else {$DeviceCode = " ";};
		if (isset($_REQUEST['GoodRef']))	{ $GoodRef = $_REQUEST['GoodRef'];}			 else {$GoodRef = "";};
		if (isset($_REQUEST['UserId']))		{ $UserId = $_REQUEST['UserId'];} 			else {$UserId = -2000;};
		if (isset($_REQUEST['Mobile']))		{ $Mobile = $_REQUEST['Mobile'];}			 else {$Mobile = "";};
		$sq ="Exec [dbo].[spApp_BasketDelete] '".$DeviceCode."', $GoodRef, $UserId , '".$Mobile."'";

		MainClass::LogFile("deletebasket",$sq);
		$result = database::custom_sqlSRV($sq, false);
	
		if ($result) {
			echo "{\"Text\":\"done\"}";
		} else {
			echo "{\"Text\":\"false\"}";
	
		}
	}


	public function Favorite_action(){
		$XUser="";
		if (isset($_REQUEST['Mobile']))	{ $Mobile = $_REQUEST['Mobile'];}else {$Mobile = "";};
		if (isset($_REQUEST['GoodRef']))	{ $GoodRef = $_REQUEST['GoodRef'];}else {$GoodRef = "";};
		if (isset($_REQUEST['DeleteFlag']))	{ $DeleteFlag = $_REQUEST['DeleteFlag'];}else {$DeleteFlag = "0";};
		
		$sq ="Exec [dbo].[spApp_FavoriteInsert] '".$Mobile."',$GoodRef,$DeleteFlag";
		MainClass::LogFile("Favorite_action",$sq);

		$res = database::custom_sqlSRV($sq, false);
		if($res) {					
			$XUser = $res["Result"];
		}	
		echo "{\"Text\":\"".$XUser."\"}";		
	}


	public function Insertbasket_old_without_ratio(){
		$XUser=0;
		$DeviceCode="";
		$GoodRef=0;
		$FacAmount=0;
		$Price=0;
		$Explain="";
		$Source="";
		$UserId=-2000;
		$Mobile="";
		if (isset($_REQUEST['DeviceCode']))	{ $DeviceCode = $_REQUEST['DeviceCode'];}	 else {$DeviceCode = " ";};
		if (isset($_REQUEST['GoodRef']))	{ $GoodRef = $_REQUEST['GoodRef'];}			 else {$GoodRef = "";};
		if (isset($_REQUEST['FacAmount']))	{ $FacAmount = $_REQUEST['FacAmount'];} 		else {$FacAmount = "";};
		if (isset($_REQUEST['Price']))		{ $Price = $_REQUEST['Price'];}				 else {$Price = "";};
		if (isset($_REQUEST['Explain']))	{ $Explain = $_REQUEST['Explain'];}			 else {$Explain = "";};
		if (isset($_REQUEST['Source']))		{ $Source = $_REQUEST['Source'];} 			else {$Source = "";};
		if (isset($_REQUEST['UserId']))		{ $UserId = $_REQUEST['UserId'];} 			else {$UserId = -2000;};
		if (isset($_REQUEST['Mobile']))		{ $Mobile = $_REQUEST['Mobile'];}			 else {$Mobile = "";};
		$sq ="Exec [dbo].[spApp_BasketInsert] '".$DeviceCode."', $GoodRef, $FacAmount, $Price, '".$Explain."', '".$Source."' , $UserId , '".$Mobile."'";

		MainClass::LogFile("Insertbasket_old_without_ratio",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Goodsbuy\":".$Last."}";
	
	}
	
	public function Insertbasket(){
		$XUser=0;
		$DeviceCode="";
		$GoodRef=0;
		$FacAmount=0;
		$Price=0;
		$Explain="";
		$Ratio="1";
		$UnitRef="1";
		$Source="";
		$UserId=-2000;
		$Mobile="";
		if (isset($_REQUEST['DeviceCode']))	{ $DeviceCode = $_REQUEST['DeviceCode'];}	 else {$DeviceCode = " ";};
		if (isset($_REQUEST['GoodRef']))	{ $GoodRef = $_REQUEST['GoodRef'];}			 else {$GoodRef = "";};
		if (isset($_REQUEST['FacAmount']))	{ $FacAmount = $_REQUEST['FacAmount'];} 		else {$FacAmount = "";};
		if (isset($_REQUEST['Price']))		{ $Price = $_REQUEST['Price'];}				 else {$Price = "";};
		if (isset($_REQUEST['UnitRef']))	{ $UnitRef = $_REQUEST['UnitRef'];}			 else {$UnitRef = "";};
		if (isset($_REQUEST['Ratio']))	{ $Ratio = $_REQUEST['Ratio'];}			 else {$Ratio = "";};
		if (isset($_REQUEST['Explain']))	{ $Explain = $_REQUEST['Explain'];}			 else {$Explain = "";};
		if (isset($_REQUEST['Source']))		{ $Source = $_REQUEST['Source'];} 			else {$Source = "";};
		if (isset($_REQUEST['UserId']))		{ $UserId = $_REQUEST['UserId'];} 			else {$UserId = -2000;};
		if (isset($_REQUEST['Mobile']))		{ $Mobile = $_REQUEST['Mobile'];}			 else {$Mobile = "";};
		$sq ="Exec [dbo].[spApp_BasketInsert] '".$DeviceCode."', $GoodRef, $FacAmount, $Price, '".$UnitRef."', '".$Ratio."', '".$Explain."', '".$Source."' , $UserId , '".$Mobile."'";

		MainClass::LogFile("Insertbasket",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Goods\":".$Last."}";
	
	}


	public function PFRCDEWS(){ 		//spApp_InsertReceive
		$UserId = -2000;
		if (isset($_REQUEST['Mobile'])){ $Mobile = $_REQUEST['Mobile'];} else {$Mobile = "";};
		if (isset($_REQUEST['Price'])){ $Price = $_REQUEST['Price'];} else {$Price = "0";};
		if (isset($_REQUEST['Rahgiri'])){ $Rahgiri = $_REQUEST['Rahgiri'];} else {$Rahgiri = "";};
		if (isset($_REQUEST['BankKart'])){ $BankKart = $_REQUEST['BankKart'];} else {$BankKart = "";};
		if (isset($_REQUEST['KartOwner'])){ $KartOwner = $_REQUEST['KartOwner'];} else {$KartOwner = "";};
		if (isset($_REQUEST['PreFactorCode'])){ $PreFactorCode = $_REQUEST['PreFactorCode'];} else {$PreFactorCode = "0";};
		
		$sq = "Exec [dbo].[spApp_InsertReceive] '$Mobile',$Price,'$Rahgiri','$BankKart','$KartOwner',$PreFactorCode,$UserId";		

		MainClass::LogFile("dargah_InsertReceive",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"users\":".$Last."}";
	
	}
	

	public function goodinfo(){
		$Res = array();
	
		$sq = "exec spApp_GetGoods2 ";
		if (isset($_REQUEST['RowCount'])){ $sq = $sq." @RowCount = ".$_REQUEST['RowCount'];}
		else { $sq = $sq." @RowCount = 10";}	
		if (isset($_REQUEST['SearchTarget'])){ $sq = $sq.", @SearchTarget = N'".$_REQUEST['SearchTarget']."'";}
		if (isset($_REQUEST['OrderBy'])){ $sq = $sq.", @OrderBy = N'".$_REQUEST['OrderBy']."'";}
		if (isset($_REQUEST['OnlyActive'])){ $sq = $sq.", @OnlyActive = ".$_REQUEST['OnlyActive'];}
		if (isset($_REQUEST['OnlyAvailable'])){ $sq = $sq.", @OnlyAvailable = ".$_REQUEST['OnlyAvailable'];}
		if (isset($_REQUEST['GroupCode'])){ $sq = $sq.", @GroupCode = ".$_REQUEST['GroupCode'];}
		if (isset($_REQUEST['Where'])){ $sq = $sq.", @Where = N'".$_REQUEST['Where']."'";}
		if (isset($_REQUEST['LikeGood'])){ $sq = $sq.", @LikeGoodRef = ".$_REQUEST['LikeGood'];}
		if (isset($_REQUEST['PageNo'])){ $sq = $sq.", @PageNo = ".$_REQUEST['PageNo'];}
		if (isset($_REQUEST['MobileNo'])){ $sq = $sq.", @MobileNo = '".$_REQUEST['MobileNo']."'";}
		if (isset($_REQUEST['OnlyFavorite'])){ $sq = $sq.", @OnlyFavorite = ".$_REQUEST['OnlyFavorite'];}
		if (isset($_REQUEST['GoodCode'])){ $sq = $sq.", @GoodCode = ".$_REQUEST['GoodCode'];}
	

		MainClass::LogFile("goodinfo",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		
		echo "{\"Goods\":".$Last."}";
		
	}
	
	public function GoodGroupInfo(){
		
		$Res = array();
	
		$sq = "Exec [dbo].[spApp_GetGoodGroups] ";
		if (isset($_REQUEST['GroupName'])){ $sq = $sq."@GroupName = N'".$_REQUEST['GroupName']."'";} else {$sq = $sq."@GroupName = N''";}
		if (isset($_REQUEST['GroupCode'])){ $sq = $sq.", @GroupCode = ".$_REQUEST['GroupCode'];}


		MainClass::LogFile("GoodGroupInfo",$sq);
		$this->response = database::custom_sqlSRV($sq,true);		
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);		
		echo "{\"Groups\":".$Last."}";
	   
	}	
	
	public function GoodGroupInfo_Default(){
		$Res = array();
		$sq = "Exec [dbo].[spApp_GetGoodGroups_Default] ";
		MainClass::LogFile("GoodGroupInfo_Default",$sq);
		$this->response = database::custom_sqlSRV($sq,true);		
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);		
		echo "{\"Groups\":".$Last."}";
	   
	}	
	
	public function GoodGroupInfo_DefaultImage(){
		$Res = array();
		$sq = "Exec [dbo].[spApp_GetGoodGroups_DefaultImage] ";
		MainClass::LogFile("GoodGroupInfo_DefaultImage",$sq);
		$this->response = database::custom_sqlSRV($sq,true);		
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);		
		echo "{\"Groups\":".$Last."}";
	   
	}


	public function XUserCreate(){
		
		$UserId = -2000;
		$XUser = 0;
		if (isset($_REQUEST['FName'])){ $FName = $_REQUEST['FName'];} else {$FName = "";};
		if (isset($_REQUEST['LName'])){ $LName = $_REQUEST['LName'];} else {$LName = "";};
		if (isset($_REQUEST['UName'])){ $UName = $_REQUEST['UName'];} else {$UName = "";};
		if (isset($_REQUEST['UPass'])){ $UPass = $_REQUEST['UPass'];} else {$UPass = "";};
		if (isset($_REQUEST['address'])){ $address = $_REQUEST['address'];} else {$address = "";};
		if (isset($_REQUEST['mobile'])){ $mobile = $_REQUEST['mobile'];} else {$mobile = "";};
		if (isset($_REQUEST['company'])){ $company = $_REQUEST['company'];} else {$company = "";};
		if (isset($_REQUEST['email'])){ $email = $_REQUEST['email'];} else {$email = "";};
		if (isset($_REQUEST['Flag'])){ $Flag = $_REQUEST['Flag'];} else {$Flag = "0";};
		if (isset($_REQUEST['NewPass'])){ $NewPass = $_REQUEST['NewPass'];} else {$NewPass = "";};
		if (isset($_REQUEST['PostalCode'])){ $PostalCode = $_REQUEST['PostalCode'];} else {$PostalCode = "";};
		
		$sq = "Exec [dbo].[spApp_XUserCreate] '$UName','$UPass','$NewPass','$FName','$LName','$mobile','$company','$address','$PostalCode','$email',$UserId,$Flag";		
        MainClass::LogFile("XUserCreate",$sq);

		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"users\":".$Last."}";


	}




}
