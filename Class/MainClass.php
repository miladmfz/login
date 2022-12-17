<?php
 date_default_timezone_set('Asia/Tehran');
 require __DIR__ . '/../print/vendor/autoload.php';
 use Mike42\Escpos\Printer;
 use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
 use Mike42\Escpos\EscposImage;
 class MainClass
{
	
    private $response = array();
    function __construct(){}



	public function Notification(){
		

	
		if (isset($_REQUEST['Condition'])){ $Condition = $_REQUEST['Condition'];} else {$Condition = "";};

		if ($Condition=="Broker") 
        {
            $Where = "";
        }

		if ($Condition=="Customer") 
        {
            $Where = "";
        }
	
		echo "{\"Text\":\"".$Where."\"}";
	}


	public function TestPrint(){


		
		try {

			$tux = EscposImage::load(__DIR__ . "/../FactorImage/123.jpg", false);
			$connector = new WindowsPrintConnector("printAndroid");
			$printer = new Printer($connector);
			$printer -> setJustification( Printer::JUSTIFY_CENTER );
			$printer -> graphics($tux);

			$printer -> cut();
			$printer -> close();
			
		} catch (Exception $e) {
			echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
		}


	}
	



	public function check(){
		$sq = "select top 10 GoodCode,GoodName,GoodExplain1 from good ";
		MainClass::LogFile("check",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Columns\":".$Last."}";
	}

	public function kowsarVersion(){
		

		$sq = "Exec spversioninfo ";	
		MainClass::LogFile("kowsar_info",$sq);
		$result = database::custom_sqlSRV($sq, true);
		
		if($result) {					
			$kowsarVerNo = $result[0]["VerNo"];
		}		
		echo "{\"Text\":\"".$kowsarVerNo."\"}";
	}

	public function LogFile($FunctonName, $Query){

		$filename= "LogFile/$FunctonName.txt";
		$strTime=jFulltdate();
		$HeaderTag=sql_dbname."#".$strTime." : ";
		$myfile = fopen($filename, "a+") or die("Unable to open file!");


		if(filesize($filename)>100000){
			unlink($filename);
		}


		
		fwrite($myfile, $HeaderTag);
		fwrite($myfile, "\t");
		fwrite($myfile, json_encode($Query, JSON_UNESCAPED_UNICODE));
		fwrite($myfile, "\n");
		fclose($myfile);
	}	

	public function GetColumnList(){

		if (isset($_REQUEST['GoodCode'])) {$GoodCode = $_REQUEST['GoodCode'];} else {$GoodCode = 0;};
		if (isset($_REQUEST['GoodType'])) {$GoodType = $_REQUEST['GoodType'];} else {$GoodType = 0;};
		if (isset($_REQUEST['Type'])) {$Type = $_REQUEST['Type'];} else {$Type = 0;};
		if (isset($_REQUEST['AppType'])) {$AppType = $_REQUEST['AppType'];} else {$AppType = 0;};
		if (isset($_REQUEST['IncludeZero'])) {$IncludeZero = $_REQUEST['IncludeZero'];} else {$IncludeZero = 0;};
		if ($AppType==1) {
			$sq = "Exec [spApp_GetColumn] $GoodCode ,'', $Type,$AppType,$IncludeZero";
        } else {
			$sq = "Exec [spApp_GetColumn] $GoodCode ,'$GoodType', $Type,$AppType,$IncludeZero";
        }

		MainClass::LogFile("GetColumnList",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Columns\":".$Last."}";
	}

	public function GetGoodType(){
		$sq = "Exec [spApp_GetGoodType]";
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Columns\":".$Last."}";
	}

	
	public function GetDistinctValues(){
		if (isset($_REQUEST['TableName'])) {$TableName = $_REQUEST['TableName'];} else {$TableName = '';};
		if (isset($_REQUEST['FieldNames'])) {$FieldNames = $_REQUEST['FieldNames'];} else {$FieldNames = '';};
		if (isset($_REQUEST['WhereClause'])) {$WhereClause = $_REQUEST['WhereClause'];} else {$WhereClause = '';};

		
		$sq = "Exec spAppGetDistinctValues '$TableName','$FieldNames Value','$WhereClause' ";
		MainClass::LogFile("GetDistinctValues",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Values\":".$Last."}";
	}


	public function getImage(){
		$ObjectRef = $_REQUEST['ObjectRef'];
		if (isset($_REQUEST['IX'])) {$IX = $_REQUEST['IX']+1;} else {$IX = 1;}
		if (isset($_REQUEST['Scale'])) {$Scale = $_REQUEST['Scale'];} else {$Scale = 500;}
		if (isset($_REQUEST['ClassName'])) {$ClassName = $_REQUEST['ClassName'];} else {$ClassName = "TGood";}

		$sq = "Exec dbo.spApp_GetImage $ObjectRef, $IX , '$ClassName'";
		$res = database::custom_imgSRV($sq,false);
		MainClass::LogFile("getImage",$sq);
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

			echo "{\"Text\":\"".base64_encode($im->getimageblob())."\"}";

		}
		else{
			echo "{\"Text\":\"no_photo\"}";
		}		
	}
			
	public function GetImageFromKsr(){
		$KsrImageCode = $_REQUEST['KsrImageCode'];
		$Scale = 500;
		$sq = "Exec dbo.spApp_GetKsrImage $KsrImageCode";
		$res = database::custom_imgSRV($sq,false);
		

		MainClass::LogFile("GetImageFromKsr",$sq);
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

			echo "{\"Text\":\"".base64_encode($im->getimageblob())."\"}";

		}
		else{
			echo "{\"Text\":\"no_photo\"}";
		}		
	}
	
			
	public function GetImageCustom(){

		if (isset($_REQUEST['ClassName'])) {$ClassName = $_REQUEST['ClassName'];} else {$ClassName = "TGoodsGrp";}
		if (isset($_REQUEST['ObjectRef'])) {$ObjectRef = $_REQUEST['ObjectRef'];} else {$ObjectRef = "0";}
		if (isset($_REQUEST['Scale'])) {$Scale = $_REQUEST['Scale'];} else {$Scale = 100;}


		$sq = "set nocount on  select IMG from ksrimage where ClassName ='$ClassName' and ObjectRef=$ObjectRef ";
		$res = database::custom_imgSRV($sq,false);
	
		MainClass::LogFile("GetImageCustom",$sq);
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

			echo "{\"Text\":\"".base64_encode($im->getimageblob())."\"}";

		}
		else{
			echo "{\"Text\":\"no_photo\"}";
		}		
	}
	
}
