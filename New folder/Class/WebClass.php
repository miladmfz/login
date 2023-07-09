<?php
 date_default_timezone_set('Asia/Tehran');
 class WebClass
{
	
    private $response = array();
    function __construct(){}



	public function WebImageConversation(){
		$ObjectId = "0";
		if (isset($_REQUEST['LetterRef'])){ $LetterRef = $_REQUEST['LetterRef'];} else {$LetterRef = "";};
		if (isset($_REQUEST['CentralRef'])){ $CentralRef = $_REQUEST['CentralRef'];} else {$CentralRef = "";};
		if (isset($_REQUEST['ConversationText'])){ $ConversationText = $_REQUEST['ConversationText'];} else {$ConversationText = "";};

		$sq = "Exec spWeb_AutLetterConversation_Insert @LetterRef=$LetterRef , @CentralRef=$CentralRef,@ConversationText='Image'";	
		MainClass::LogFile("WebImageConversation",$sq);

		$ClassResult = database::custom_sqlSRV($sq,true);

		if($ClassResult) {					
			$ObjectId = $ClassResult[0]["ConversationCode"];
		}


		$Image= $_REQUEST['Image'];
		$decodedImage = base64_decode($Image);
		file_put_contents("LetterImage/$ObjectId.jpg", $decodedImage);

		$sq = "Exec spImageImport  'Aut',$ObjectId,'C:/xampp/htdocs/login/LetterImage/$ObjectId.jpg' ;select @@IDENTITY KsrImageCode";	
		MainClass::LogFile("WebImageConversation",$sq);
		 $this->response = database::custom_imgSRV($sq,true);

		 $Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		 echo $Last;
		 $filename=__DIR__ . "/../LetterImage/$ObjectId.jpg";
		 unlink($filename);
		
	}
	
	public function getWebImage(){
		$ObjectRef = $_REQUEST['ObjectRef'];
		if (isset($_REQUEST['IX'])) {$IX = $_REQUEST['IX'];} else {$IX = 1;}
		if (isset($_REQUEST['Scale'])) {$Scale = $_REQUEST['Scale'];} else {$Scale = 500;}
		if (isset($_REQUEST['ClassName'])) {$ClassName = $_REQUEST['ClassName'];} else {$ClassName = "TGood";}

		$sq = "Exec dbo.spApp_GetImage $ObjectRef, $IX , '$ClassName'";
		$res = database::custom_imgSRV($sq,false);
		MainClass::LogFile("getWebImage",$sq);
		if ($res) {
			$im = new Imagick();
			$im->readimageblob($res["IMG"]);
			$cropWidth = $im->getImageWidth();
			$cropHeight = $im->getImageHeight();
			
			if ($cropWidth>$cropHeight){
				$R=$cropWidth/$cropHeight;
				$cropWidth=$Scale;
				$cropHeight=$cropWidth/$R;
			}
			else{
				$R=$cropHeight/$cropWidth;
				$cropHeight=$Scale;
				$cropWidth=$cropHeight/$R;
			}
			
			$im->adaptiveResizeImage($cropWidth,$cropHeight);

			$Header["ObjectRef"] = $ObjectRef;
			$Header["IMG"] = base64_encode($im->getimageblob());
			array_push($this->response, $Header);

			$JRows   = json_encode($this->response, JSON_UNESCAPED_UNICODE);



			echo $JRows;
			//echo base64_encode($im->getimageblob());

		}	
	}



	public function SetAlarmOff(){
		
		if (isset($_REQUEST['LetterRowCode'])){ $LetterRowCode = htmlspecialchars($_REQUEST['LetterRowCode']);} else {$LetterRowCode = "0";};


		$sq = " Update AutLetterRow Set AlarmActive=0 Where LetterRowCode=$LetterRowCode ";		
		MainClass::LogFile("SetAlarmOff",$sq);

		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo $Last;


	}


	public function AutLetterConversation_Insert(){
		

		if (isset($_REQUEST['LetterRef'])){ $LetterRef = $_REQUEST['LetterRef'];} else {$LetterRef = "";};
		if (isset($_REQUEST['CentralRef'])){ $CentralRef = $_REQUEST['CentralRef'];} else {$CentralRef = "";};
		if (isset($_REQUEST['ConversationText'])){ $ConversationText = $_REQUEST['ConversationText'];} else {$ConversationText = "";};

		$sq = "Exec spWeb_AutLetterConversation_Insert @LetterRef=$LetterRef , @CentralRef=$CentralRef,@ConversationText='$ConversationText'";	
		MainClass::LogFile("AutLetterConversation_Insert",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		
		echo $Last;
	}
	
	public function GetAutConversation(){
		

		if (isset($_REQUEST['LetterRef'])){ $LetterRef = $_REQUEST['LetterRef'];} else {$LetterRef = "";};
		$sq = "Exec spWeb_GetAutConversation  $LetterRef";	
		MainClass::LogFile("GetAutConversation",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		
		echo $Last;
	}
	
	
	public function GetCentralUser(){
		

		if (isset($_REQUEST['Where'])){ $WHERE = $_REQUEST['Where'];} else {$Where = "";};
		$sq = "select CentralCode,CentralName from vwCentralUser ";	
		MainClass::LogFile("GetCentralUser",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		
		echo $Last;
	}
	


	
	
	public function AutLetterRowInsert(){
		

		if (isset($_REQUEST['LetterRef'])){ $LetterRef = $_REQUEST['LetterRef'];} else {$LetterRef = "";};
		if (isset($_REQUEST['LetterDate'])){ $LetterDate = $_REQUEST['LetterDate'];} else {$LetterDate = "";};
		if (isset($_REQUEST['Description'])){ $Description = $_REQUEST['Description'];} else {$Description = "";};
		if (isset($_REQUEST['CreatorCentral'])){ $CreatorCentral = $_REQUEST['CreatorCentral'];} else {$CreatorCentral = "";};
		if (isset($_REQUEST['ExecuterCentral'])){ $ExecuterCentral = $_REQUEST['ExecuterCentral'];} else {$ExecuterCentral = "";};

		$sq = "	spAutLetterRow_Insert  @LetterRef = $LetterRef, @LetterDate ='$LetterDate', @Description ='$Description', @State ='درحال انجام', @Priority ='عادي', @CreatorCentral = $CreatorCentral, @ExecuterCentral = $ExecuterCentral ";	
		MainClass::LogFile("AutLetterRowInsert",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		
		echo $Last;
	}
	



	public function GetDbSetupValue(){
		

		if (isset($_REQUEST['Where'])){ $WHERE = $_REQUEST['Where'];} else {$Where = "";};
		$sq = "select top 1 DataValue from dbsetup where KeyValue = '$WHERE'";	
		MainClass::LogFile("GetDbSetupValue",$sq);
		$result = database::custom_sqlSRV($sq, true);
		
		if($result) {					
			$kowsar_info = $result[0]["DataValue"];
		}		
		echo $kowsar_info;
	}
	

	public function GetAppBrokerCustomer(){
		
		$sq = " select * from AppBrokerCustomer ";

		MainClass::LogFile("GetAppBrokerCustomer",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		//echo "{\"AppBrokerCustomers\":".$Last."}";
		echo $Last;
	}

	public function GetAllBrokers(){
		
		if (isset($_REQUEST['Filter'])){ $Filter = $_REQUEST['Filter'];} else {$Filter = "0";};
	$sq = "Select Server_Name, STRING_AGG([Broker],',') within group (order by case when isnumeric([Broker])=1 then cast([Broker] as decimal) else 0 end, [Broker] ) as BrokerStr From (select Server_Name, Device_Id, [Broker] from app_info where DATEDIFF(m,Updatedate,GETDATE())<$Filter group by Server_Name, Device_Id, [Broker]) ds group by Server_Name";
	
	$this->response = database::custom_sqlSRV($sq,true);
	$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
	//echo "{\"AppBrokerCustomers\":".$Last."}";
	echo $Last;

	}
	
	
	public function GetGoodsSum(){
		

		$sq = "Exec spGoodsSum_Web";
		
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo $Last;
	
	}
	
		
	public function GetCustomerForosh(){
		

		$sq = "Exec spCustomerForosh_web";
		
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo $Last;
	
	
	}
				
	public function GetBrokers(){
		

		$sq = "select BrokerCode , CentralRef,BrokerNameWithoutType from vwSellBroker";
		MainClass::LogFile("GetBrokers",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo $Last;
	
	
	}
		
	
	public function GetBrokerDetail(){
		

		if (isset($_REQUEST['BrokerCode'])){ $BrokerCode = $_REQUEST['BrokerCode'];} else {$BrokerCode = "'0'";};
		$sq = "spWeb_BrokerDetail $BrokerCode ";
		MainClass::LogFile("GetBrokerDetail",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo $Last;
	
	
	}
		
	public function GetBrokerReportDetail(){
		

		if (isset($_REQUEST['BrokerCode'])){ $BrokerCode = $_REQUEST['BrokerCode'];} else {$BrokerCode = "'0'";};
		$sq = "spWeb_BrokerReportDays $BrokerCode ";
		MainClass::LogFile("GetBrokerReportDetail",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo $Last;
	
	
	}
		
				
	public function GetPrefactorBroker(){
		

		if (isset($_REQUEST['BrokerCode'])){ $BrokerCode = $_REQUEST['BrokerCode'];} else {$BrokerCode = "'0'";};
		if (isset($_REQUEST['Days'])){ $Days = $_REQUEST['Days'];} else {$Days = "'1'";};
		$sq = "Declare @D varchar(10)= dbo.fnDate_ConvertToShamsi(dateadd(d, -$Days, getdate())) Select CustName,sum(RowsCount) RowsCount, Sum(SumAmount) SumAmount, Sum(SumPrice) SumPrice From vwPreFactor Where BrokerRef=$BrokerCode And PreFactorDate >= @D Group By CustName";
		MainClass::LogFile("GetPrefactorBroker",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo $Last;
	
	
	}

					
	public function Gettracker(){
		if (isset($_REQUEST['BrokerCode'])){ $BrokerCode = $_REQUEST['BrokerCode'];} else {$BrokerCode = "'0'";};
		if (isset($_REQUEST['StartDate'])){ $StartDate = $_REQUEST['StartDate'];} else {$StartDate = "'0'";};
		if (isset($_REQUEST['EndDate'])){ $EndDate = $_REQUEST['EndDate'];} else {$EndDate = "'0'";};

			
		$sq = "Select * From(select * , rwn=row_Number() over (partition by gpsdate order by gpsdate)From GpsLocation Where Brokerref = $BrokerCode And GpsDate between '$StartDate' And '$EndDate' ) ds where rwn=1 order by GpsDate ";		
		MainClass::LogFile("Gettracker",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo $Last;
	
	
	}

	public function  GetCDCustName(){
		
		if (isset($_REQUEST['BrokerCode'])){ $BrokerCode = $_REQUEST['BrokerCode'];} else {$BrokerCode = "'0'";};
		if (isset($_REQUEST['Days'])){ $Days = $_REQUEST['Days'];} else {$Days = "'1'";};
		
		
		$sq = "spWeb_GetBrokerChartData $BrokerCode ,  $Days , 'CustName ' ,'CustName, '''' PreFactorDate' ";
		MainClass::LogFile("GetCDCustName",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo $Last;
	
	
	}
	
 
	public function GetCDPreFactorDate(){
		
		if (isset($_REQUEST['BrokerCode'])){ $BrokerCode = $_REQUEST['BrokerCode'];} else {$BrokerCode = "'0'";};
		if (isset($_REQUEST['Days']))	   { $Days = $_REQUEST['Days'];} else {$Days = "'1'";};
		
		
		$sq = "spWeb_GetBrokerChartData $BrokerCode ,  $Days , 'PreFactorDate ' ,'PreFactorDate, '''' CustName' ";
		MainClass::LogFile("GetCDPreFactorDate",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo $Last;
	
	
	}


	public function ExistUser(){
		

		if (isset($_REQUEST['UName'])){ $UName = htmlspecialchars($_REQUEST['UName']);} else {$UName = "";};
		if (isset($_REQUEST['UPass'])){ $UPass = htmlspecialchars($_REQUEST['UPass']);} else {$UPass = "";};

		$sq = "Exec spapp_IsXUser  '$UName','$UPass'";		
        MainClass::LogFile("ExistUser",$sq);

		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo $Last;


	}
	


	public function ChangeXUserPassword(){
		

	if (isset($_REQUEST['UName'])){ $UName = htmlspecialchars($_REQUEST['UName']);} else {$UName = "";};
	if (isset($_REQUEST['UPass'])){ $UPass = htmlspecialchars($_REQUEST['UPass']);} else {$UPass = "";};
	if (isset($_REQUEST['NewPass'])){ $NewPass = htmlspecialchars($_REQUEST['NewPass']);} else {$NewPass = "";};


		$sq = "Exec spApp_ChangeXUserPassword  '$UName','$UPass','$NewPass'";		
        MainClass::LogFile("ChangeXUserPassword",$sq);

		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo $Last;


	}
	

	
	public function LetterInsert(){
		if (isset($_REQUEST['LetterDate'])){ $LetterDate = htmlspecialchars($_REQUEST['LetterDate']);} else {$LetterDate = "0";};
		if (isset($_REQUEST['title'])){ $title = htmlspecialchars($_REQUEST['title']);} else {$title = "0";};
		if (isset($_REQUEST['Description'])){ $Description = htmlspecialchars($_REQUEST['Description']);} else {$Description = "0";};
		if (isset($_REQUEST['CentralRef'])){ $CentralRef = htmlspecialchars($_REQUEST['CentralRef']);} else {$CentralRef = "0";};



		$sq = "exec dbo.spAutLetter_Insert @LetterDate='$LetterDate', @InOutFlag=0,@Title ='$title', @Description='$Description',@State ='درحال انجام',@Priority ='عادي', @ReceiveType ='دستي', @CreatorCentral =28, @OwnerCentral =$CentralRef ";		
		MainClass::LogFile("LetterInsert",$sq);

		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo $Last;


	}


	public function GetLetterList(){
		
		if (isset($_REQUEST['SearchTarget'])){ $SearchTarget = htmlspecialchars($_REQUEST['SearchTarget']);} else {$SearchTarget = "";};
		if (isset($_REQUEST['CentralRef'])){ $CentralRef = htmlspecialchars($_REQUEST['CentralRef']);} else {$CentralRef = "";};
		if (isset($_REQUEST['CreationDate'])){ $CreationDate = htmlspecialchars($_REQUEST['CreationDate']);} else {$CreationDate = "";};

		$Where = "";
		
		if($SearchTarget!=""){
			$Where = " (LetterTitle like ''%$SearchTarget%'' or LetterDescription like ''%$SearchTarget%'' or ds.RowExecutorName like ''%$SearchTarget%'') ";
		}		



		if($CentralRef!=""){
			if(strlen($Where) > 1){
				$Where = " $Where And (CreatorCentralRef=$CentralRef or OwnerCentralRef=$CentralRef or RowExecutorCentralRef=$CentralRef) ";
			}else{
				$Where = " (CreatorCentralRef=$CentralRef or OwnerCentralRef=$CentralRef or RowExecutorCentralRef=$CentralRef) ";
			}
		}		

		if($CreationDate!=""){
			if(strlen($Where) > 1){
				$Where = " $Where And LetterDate>''$CreationDate'' ";
			}else{
				$Where = " LetterDate>''$CreationDate'' ";
			}
		}		






			$sq = "Exec spWeb_AutLetterList '$Where' ";		
			MainClass::LogFile("GetLetterList",$sq);
	
			$this->response = database::custom_sqlSRV($sq,true);
			$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
			echo $Last;
	
	
		}
		
		public function GetLetterRowList(){
		
			if (isset($_REQUEST['LetterRef'])){ $LetterRef = htmlspecialchars($_REQUEST['LetterRef']);} else {$LetterRef = "0";};


			$sq = "select  LetterRowCode,Name RowExecutorName,LetterRef ,LetterDate RowLetterDate,LetterDescription LetterRowDescription, LetterState LetterRowState, ExecutorCentralRef RowExecutorCentralRef from vwautletterrow join central on CentralCode=ExecutorCentralRef where LetterRef = $LetterRef order by LetterRowCode desc";		
			MainClass::LogFile("GetLetterRowList",$sq);
	
			$this->response = database::custom_sqlSRV($sq,true);
			$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
			echo $Last;
	
	
		}
		
	
		public function GetLetterFromPersoninfo(){
		
			if (isset($_REQUEST['PersonInfoCode'])){ $PersonInfoCode = htmlspecialchars($_REQUEST['PersonInfoCode']);} else {$PersonInfoCode = "0";};

			$sq = "	spWeb_AutLetterListByPerson $PersonInfoCode ";
			
			MainClass::LogFile("GetLetterRowList",$sq);
	
			$this->response = database::custom_sqlSRV($sq,true);
			$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
			echo $Last;


// 			--letter jobperson
// select * from vwautletter aut
// join (select CentralRef,p.PersonInfoCode from PersonInfo p join JobPerson jp on jp.JobPersonCode=p.JobPersonRef) ds
// on ds.CentralRef=aut.OwnerCentralRef
// where ds.PersonInfoCode=9


// --letter customer
// select * from vwautletter aut
// join (select CentralRef,p.PersonInfoCode from PersonInfo p join customer c on c.CustomerCode=p.CustomerRef) ds
// on ds.CentralRef=aut.OwnerCentralRef
// where ds.PersonInfoCode=10
	
	
		}



			
		public function Web_ActivationCode(){
			if (isset($_REQUEST['ActivationCode'])){ $ActivationCode = $_REQUEST['ActivationCode'];} else {$ActivationCode = "";};
	
			$sq = "select * from AppBrokerCustomer Where ActivationCode = '".$ActivationCode."'";		
	
			MainClass::LogFile("ActivationCode",$sq);
			$this->response = database::custom_sqlSRV($sq,true);
			$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
			echo $Last;
			
		}

		
		public function Web_GetDbsetupObject(){

			if (isset($_REQUEST['Where'])){ $Where = $_REQUEST['Where'];} else {$Where = "";};
			$order='';
	

			if($Where=="AppBroker_ActivationCode"){

				$order = " where KeyValue in ('AppBroker_MenuGroupCode','App_FactorTypeInKowsar','AppBroker_DefaultGroupCode','PreFactor_UsePriceTip','PreFactor_IsReserved') ";	

			}else if ($Where=="AppOcr_ActivationCode"){

				$order = " where KeyValue like '%appocr%' ";	

			}else if ($Where=="AppOrder_ActivationCode"){

				$order = " where KeyValue like '%apporder%' or  KeyValue like '%rstfactor%' ";	

			}

			$sq = "select KeyValue,DataValue,Description from DbSetup $order ";		
	





			MainClass::LogFile("Web_GetDbsetupObject",$sq);
			$this->response = database::custom_sqlSRV($sq,true);
			$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
			echo $Last;
			
		}
		


		
}
