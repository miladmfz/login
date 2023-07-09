<?php
 date_default_timezone_set('Asia/Tehran');
 require __DIR__ . '/../print/vendor/autoload.php';
 use Mike42\Escpos\Printer;
 use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
 use Mike42\Escpos\PrintConnectors\FilePrintConnector;
 use Mike42\Escpos\EscposImage;
 class OrderClass
{
	
    private $response = array();
    function __construct(){}
	
	
	public function OrderMizList(){

		if (isset($_REQUEST['InfoState']))	{ $InfoState = $_REQUEST['InfoState'];}else {$InfoState = "0";};
		if (isset($_REQUEST['MizType']))	{ $MizType = $_REQUEST['MizType'];}else {$MizType = "";};

		
		$Res = array();
	
		$sq = "exec spApp_OrderMizList  $InfoState,'$MizType' ";

		MainClass::LogFile("Order_GetRstMiz",$sq);
		$this->response = database::custom_sqlSRV($sq,true);		
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);		
		echo "{\"BasketInfos\":".$Last."}";
	   
	}	
	
	public function OrderReserveList(){

		if (isset($_REQUEST['MizRef']))	{ $MizRef = $_REQUEST['MizRef'];}else {$MizRef = "0";};

		
		$Res = array();
	
		$sq = "exec spApp_OrderReserveList  $MizRef";

		MainClass::LogFile("Order_OrderReserveList",$sq);
		$this->response = database::custom_sqlSRV($sq,true);		
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);		
		echo "{\"BasketInfos\":".$Last."}";
	   
	}	


		
	public function OrderInfoInsert(){

		if (isset($_REQUEST['Broker']))	{ $Broker = $_REQUEST['Broker'];}else {$Broker = "0";};
		if (isset($_REQUEST['Miz']))	{ $Miz = $_REQUEST['Miz'];}else {$Miz = "0";};
		if (isset($_REQUEST['PersonName']))	{ $PersonName = $_REQUEST['PersonName'];}else {$PersonName = "";};
		if (isset($_REQUEST['Mobile']))	{ $Mobile = $_REQUEST['Mobile'];}else {$Mobile = "";};
		if (isset($_REQUEST['InfoExplain']))	{ $InfoExplain = $_REQUEST['InfoExplain'];}else {$InfoExplain = "";};
		if (isset($_REQUEST['Prepayed']))	{ $Prepayed = $_REQUEST['Prepayed'];}else {$Prepayed = "0";};
		if (isset($_REQUEST['ReserveStartTime']))	{ $ReserveStartTime = $_REQUEST['ReserveStartTime'];}else {$ReserveStartTime = "00:00";};
		if (isset($_REQUEST['ReserveEndTime']))	{ $ReserveEndTime = $_REQUEST['ReserveEndTime'];}else {$ReserveEndTime = "00:00";};
		if (isset($_REQUEST['Date']))	{ $Date = $_REQUEST['Date'];}else {$Date = "1401/01/01";};
		if (isset($_REQUEST['State']))	{ $State = $_REQUEST['State'];}else {$State = "0";};
		if (isset($_REQUEST['InfoCode']))	{ $InfoCode = $_REQUEST['InfoCode'];}else {$InfoCode = "0";};
		
		$Res = array();
	
		$sq = "exec spApp_OrderInfoInsert $Broker,$Miz,'$PersonName','$Mobile','$InfoExplain',$Prepayed,'$ReserveStartTime','$ReserveEndTime','$Date',$State,$InfoCode";


		MainClass::LogFile("Order_OrderInfoInsert",$sq);
		
		$this->response = database::custom_sqlSRV($sq,true);		
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);		
		echo "{\"BasketInfos\":".$Last."}";
	   
	}	
	


	
	public function GetGoodFromGroup(){
		
		$Res = array();
		if (isset($_REQUEST['GroupCode']))	{ $GroupCode = $_REQUEST['GroupCode'];}else {$GroupCode = "0";};

		$sq = "select GoodCode,GoodName,MaxSellPrice,'' ImageName from vwGood where  GoodCode in(Select GoodRef From GoodGroup p 
				Join GoodsGrp s on p.GoodGroupRef = s.GroupCode Where s.GroupCode = $GroupCode or s.L1 = $GroupCode or s.L2 = $GroupCode or s.L3 = $GroupCode )";
		MainClass::LogFile("Order_GetGoodFromGroup",$sq);
		$this->response = database::custom_sqlSRV($sq,true);		
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);		
		echo "{\"Goods\":".$Last."}";
	   
	}	
	
	
	public function GetObjectTypeFromDbSetup(){
		
		$Res = array();
		if (isset($_REQUEST['ObjectType']))	{ $ObjectType = $_REQUEST['ObjectType'];}else {$ObjectType = "";};

		$sq = "select * from dbo.fnObjectType('$ObjectType') ";
		MainClass::LogFile("Order_GetObjectTypeFromDbSetup",$sq);
		$this->response = database::custom_sqlSRV($sq,true);		
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);		
		echo "{\"ObjectTypes\":".$Last."}";
	   
	}	
	

	public function GetTodeyFromServer(){
		

		$sq = "select dbo.fnDate_Today() TodeyFromServer";	
		MainClass::LogFile("Order_GetTodeyFromServer",$sq);
		$result = database::custom_sqlSRV($sq, true);
		
		if($result) {					
			$TodeyFromServer = $result[0]["TodeyFromServer"];
		}		
		echo "{\"Text\":\"".$kowsarVerNo."\"}";
	}

	
	public function OrderRowInsert(){
		
		if (isset($_REQUEST['GoodRef']))	{ $GoodRef = $_REQUEST['GoodRef'];}			else {$GoodRef = "";};
		if (isset($_REQUEST['FacAmount']))	{ $FacAmount = $_REQUEST['FacAmount'];} 	else {$FacAmount = "";};
		if (isset($_REQUEST['Price']))		{ $Price = $_REQUEST['Price'];}				else {$Price = "";};
		if (isset($_REQUEST['bUnitRef']))	{ $bUnitRef = $_REQUEST['bUnitRef'];}		else {$bUnitRef = "";};
		if (isset($_REQUEST['bRatio']))		{ $bRatio = $_REQUEST['bRatio'];}			else {$bRatio = "";};
		if (isset($_REQUEST['Explain']))	{ $Explain = $_REQUEST['Explain'];}			else {$Explain = "";};
		if (isset($_REQUEST['UserId']))		{ $UserId = $_REQUEST['UserId'];} 			else {$UserId = -2000;};
		if (isset($_REQUEST['InfoRef']))	{ $InfoRef = $_REQUEST['InfoRef'];}			else {$InfoRef = "";};
		if (isset($_REQUEST['RowCode']))	{ $RowCode = $_REQUEST['RowCode'];}			else {$RowCode = "";};

		$sq ="[dbo].[spApp_OrderRowInsert] $GoodRef , $FacAmount, $Price, $bUnitRef, $bRatio, '$Explain' , $UserId , $InfoRef, $RowCode ";

		MainClass::LogFile("Order_OrderRowInsert",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Goods\":".$Last."}";
	
	}


		
	public function GetOrderGoodList(){
		
		if (isset($_REQUEST['GroupCode']))	{ $GroupCode = $_REQUEST['GroupCode'];}			else {$GroupCode = 0;};
		if (isset($_REQUEST['RowCount']))	{ $RowCount = $_REQUEST['RowCount'];} 			else {$RowCount = 200;};
		if (isset($_REQUEST['Where']))	{ $Where = $_REQUEST['Where'];} 			else {$Where = "";};
		if (isset($_REQUEST['AppBasketInfoRef']))	{ $AppBasketInfoRef = $_REQUEST['AppBasketInfoRef'];} 			else {$AppBasketInfoRef = 0;};

		$sq ="Exec spApp_GetGoods2 @RowCount = $RowCount,@Where = N'$Where',@AppBasketInfoRef=$AppBasketInfoRef, @GroupCode = $GroupCode ,@AppType=3 , @OrderBy = ' order by PrivateCodeForSort '";

		MainClass::LogFile("Order_GetOrderGoodList",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Goods\":".$Last."}";
	
	}

	
		
	public function DeleteGoodFromBasket(){
		
		if (isset($_REQUEST['RowCode']))	{ $RowCode = $_REQUEST['RowCode'];}			else {$RowCode = 0;};
		if (isset($_REQUEST['AppBasketInfoRef']))	{ $AppBasketInfoRef = $_REQUEST['AppBasketInfoRef'];} 			else {$AppBasketInfoRef = 0;};

		$sq ="Delete From AppBasket Where AppBasketInfoRef=$AppBasketInfoRef and AppBasketCode=$RowCode ";

		MainClass::LogFile("Order_DeleteGoodFromBasket",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Text\":\"Done\"}";
	
	}


	
		
	public function GetSellBroker(){
		

		$sq =" select brokerCode,BrokerNameWithoutType from  vwSellBroker ";

		MainClass::LogFile("Order_GetSellBroker",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"SellBrokers\":".$Last."}";

	
	}





		
	public function GetOrderSum(){
		if (isset($_REQUEST['AppBasketInfoRef']))	{ $AppBasketInfoRef = $_REQUEST['AppBasketInfoRef'];}else {$AppBasketInfoRef = "";};
		
		$sq ="Exec spApp_OrderGetSummmary $AppBasketInfoRef";
		MainClass::LogFile("Order_GetOrderSum",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Goods\":".$Last."}";
		
	}

	public function OrderGet(){
		if (isset($_REQUEST['AppBasketInfoRef']))	{ $AppBasketInfoRef = $_REQUEST['AppBasketInfoRef'];}	else {$AppBasketInfoRef = "";};
		if (isset($_REQUEST['AppType']))			{ $AppType = $_REQUEST['AppType'];}						else {$AppType = "";};
		
		$sq ="Exec [dbo].[spApp_OrderGet] $AppBasketInfoRef , $AppType ";
		MainClass::LogFile("Order_OrderGet",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Goods\":".$Last."}";
		
	}

	
	public function OrderToFactor(){
		if (isset($_REQUEST['AppBasketInfoRef']))	{ $AppBasketInfoRef = $_REQUEST['AppBasketInfoRef'];}	else {$AppBasketInfoRef = "";};
		if (isset($_REQUEST['UserId']))			{ $UserId = $_REQUEST['UserId'];}						else {$UserId = "2";};
		
		$sq ="Exec [dbo].[spApp_OrderToFactor] $AppBasketInfoRef , $UserId ";
		MainClass::LogFile("Order_OrderToFactor",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"BasketInfos\":".$Last."}";
		
	}


	public function OrderGetFactor (){
		if (isset($_REQUEST['AppBasketInfoRef']))	{ $AppBasketInfoRef = $_REQUEST['AppBasketInfoRef'];}	else {$AppBasketInfoRef = "";};
		
		
		$sq ="Exec [dbo].[spApp_OrderGetFactor ] $AppBasketInfoRef";
		MainClass::LogFile("Order_OrderGetFactor ",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Factors\":".$Last."}";
		
	}


	public function OrderGetFactorRow(){
		if (isset($_REQUEST['AppBasketInfoRef']))	{ $AppBasketInfoRef = $_REQUEST['AppBasketInfoRef'];}	else {$AppBasketInfoRef = "";};
		if (isset($_REQUEST['GoodGroups']))			{ $GoodGroups = $_REQUEST['GoodGroups'];}				else {$GoodGroups = "0";};
		if (isset($_REQUEST['Where']))				{ $Where = $_REQUEST['Where'];}							else {$Where = "";};
		
		$sq ="Exec [dbo].[spApp_OrderGetFactorRow] $AppBasketInfoRef , $GoodGroups ,'$Where' ";
		MainClass::LogFile("Order_OrderGetFactorRow",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Factors\":".$Last."}";
		
	}


	public function OrderGetAppPrinter(){
	
		
		$sq =" select * from AppPrinter ";
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"AppPrinters\":".$Last."}";
		
	}

	public function Order_CanPrint(){
	
		if (isset($_REQUEST['AppBasketInfoRef']))	{ $AppBasketInfoRef = $_REQUEST['AppBasketInfoRef'];}	else {$AppBasketInfoRef = "";};
		if (isset($_REQUEST['CanPrint']))			{ $CanPrint = $_REQUEST['CanPrint'];}				else {$CanPrint = "0";};
		
		$sq =" spApp_Order_CanPrint  $AppBasketInfoRef ,$CanPrint ";
		MainClass::LogFile("Order_CanPrint",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Text\":\"Done\"}";
		
	}
	
	public function OrderEditInfoExplain(){
	
		if (isset($_REQUEST['AppBasketInfoCode']))	{ $AppBasketInfoCode = $_REQUEST['AppBasketInfoCode'];}	else {$AppBasketInfoCode = "";};
		if (isset($_REQUEST['Explain']))			{ $Explain = $_REQUEST['Explain'];}				else {$Explain = "";};
		
		$sq =" spApp_OrderInfoUpdateExplain  '$Explain', $AppBasketInfoCode  ";

		MainClass::LogFile("OrderEditInfoExplain",$sq);
		
		$this->response = database::custom_sqlSRV($sq,true);		
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);		
		echo "{\"BasketInfos\":".$Last."}";
		
	}
	
	public function OrderDeleteAll(){
	
		if (isset($_REQUEST['AppBasketInfoRef']))	{ $AppBasketInfoRef = $_REQUEST['AppBasketInfoRef'];}	else {$AppBasketInfoRef = "0";};
		
		$sq =" Delete From AppBasket Where  PreFactorCode is null and  AppBasketInfoRef= $AppBasketInfoRef ";
		MainClass::LogFile("OrderDeleteAll",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Text\":\"Done\"}";
		
	}
	
	public function OrderInfoReserveDelete(){
	
		if (isset($_REQUEST['AppBasketInfoRef']))	{ $AppBasketInfoRef = $_REQUEST['AppBasketInfoRef'];}	else {$AppBasketInfoRef = "0";};
		
		$sq =" spApp_OrderInfoReserveDelete  $AppBasketInfoRef ";
		MainClass::LogFile("OrderInfoReserveDelete",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Text\":\"Done\"}";
		
	}

	public function OrderSendImage(){

		$Image= $_REQUEST['Image'];
		$Code= $_REQUEST['Code'];

		$PrinterName= $_REQUEST['PrinterName'];
		$PrintCount= $_REQUEST['PrintCount'];
		
		$decodedImage = base64_decode($Image);
		file_put_contents("FactorImage/$Code.jpg", $decodedImage);


		try {

			$tux = EscposImage::load(__DIR__ . "/../FactorImage/$Code.jpg", false);
			//$connector = new WindowsPrintConnector($PrinterName);
			//$connector = new FilePrintConnector("//192.168.1.33/asd");
			$connector = new FilePrintConnector("$PrinterName");
			$printer = new Printer($connector);
			$printer -> setJustification( Printer::JUSTIFY_CENTER );

			for ($x = 0; $x < $PrintCount; $x++) {
				$printer -> graphics($tux);
				$printer -> cut();
			}
			

			$printer -> close();
			 $filename=__DIR__ . "/../FactorImage/$Code.jpg";
			 unlink($filename);
			 echo "{\"Text\":\"Done\"}";
		} catch (Exception $e) {
			echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
		}




	}
	
	public function testprint1(){
		try {
			$tux = EscposImage::load(__DIR__ . "/../FactorImage/1.jpg", false);
			$connector = new WindowsPrintConnector("casqh");
			$printer = new Printer($connector);
			$printer -> setJustification( Printer::JUSTIFY_CENTER );
			$printer -> graphics($tux);
			$printer -> cut();
			$printer -> close();
			 echo "{\"Text\":\"Done\"}";
		} catch (Exception $e) {
			echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
		}

	}
		public function testprint2(){
		try {
			$tux = EscposImage::load(__DIR__ . "/../FactorImage/1.jpg", false);
			$connector = new WindowsPrintConnector("Kitchen");
			$printer = new Printer($connector);
			$printer -> setJustification( Printer::JUSTIFY_CENTER );
			$printer -> graphics($tux);
			$printer -> cut();
			$printer -> close();
			 echo "{\"Text\":\"Done\"}";
		} catch (Exception $e) {
			echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
		}

	}
		public function testprint3(){
		try {
			$tux = EscposImage::load(__DIR__ . "/../FactorImage/1.jpg", false);
			$connector = new WindowsPrintConnector("Yard");
			$printer = new Printer($connector);
			$printer -> setJustification( Printer::JUSTIFY_CENTER );
			$printer -> graphics($tux);
			$printer -> cut();
			$printer -> close();
			 echo "{\"Text\":\"Done\"}";
		} catch (Exception $e) {
			echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
		}

	}
		public function testprint4(){
		try {
			$tux = EscposImage::load(__DIR__ . "/../FactorImage/1.jpg", false);
			$connector = new WindowsPrintConnector("Roof");
			$printer = new Printer($connector);
			$printer -> setJustification( Printer::JUSTIFY_CENTER );
			$printer -> graphics($tux);
			$printer -> cut();
			$printer -> close();
			 echo "{\"Text\":\"Done\"}";
		} catch (Exception $e) {
			echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
		}

	}

	
}
