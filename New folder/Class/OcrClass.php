<?php
 date_default_timezone_set('Asia/Tehran');
 class OcrClass
{
	
    private $response = array();
    function __construct(){}
	
	

	public function ExitDelivery(){
		

		if (isset($_REQUEST['Where'])){ $Where = $_REQUEST['Where'];} else {$Where = "0";};

		$sq = "  update AppOCRFactor set HasSignature=0,AppIsDelivered=0 where AppOCRFactorCode= $Where";

		MainClass::LogFile("ExecQuery",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Goods\":".$Last."}";

	}

	
	public function TestJob()
    {

		//$sq = "select JobCode,Title,Explain from job";		
		if (isset($_REQUEST['Where'])){ $Where = $_REQUEST['Where'];} else {$Where = "";};
		
		$sq = "select JobCode,Title,Explain from job where Explain='$Where'";		

		
		MainClass::LogFile("TestJob",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Jobs\":".$Last."}";
		
    }
	


	
	public function TestJobPerson()
    {
		if (isset($_REQUEST['Where'])){ $Where = $_REQUEST['Where'];} else {$Where = "";};
		
		$sq = "select j.JobCode,jp.JobPersonCode,j.Title,c.Name,c.FName from  JobPerson jp  join job j on j.JobCode=jp.JobRef  join Central c on c.CentralCode=jp.CentralRef  where j.Title='$Where'";		

		MainClass::LogFile("TestJobPerson",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"JobPersons\":".$Last."}";
		
    }
	
	



	
	public function GetOcrFactorDetail(){
		if (isset($_REQUEST['OCRFactorCode'])){ $OCRFactorCode = $_REQUEST['OCRFactorCode'];} else {$OCRFactorCode = "0";};
		
		$sq = "[dbo].[spApp_ocrGetFactorDetail] ".$OCRFactorCode;
		MainClass::LogFile("GetOcrFactorDetail",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"AppOcrFactors\":".$Last."}";
	}
	

	public function OcrControlled(){
		

		if (isset($_REQUEST['AppOCRCode'])){ $AppOCRCode = $_REQUEST['AppOCRCode'];} else {$AppOCRCode = "";};
		if (isset($_REQUEST['State'])){ $State = $_REQUEST['State'];} else {$State = "0";};
		if (isset($_REQUEST['JobPersonRef'])){ $JobPersonRef = $_REQUEST['JobPersonRef'];} else {$JobPersonRef = "0";};
		
		$sq = "Exec dbo.spApp_ocrSetControlled $AppOCRCode ,$State ,$JobPersonRef";
		//$sq = "Exec dbo.spApp_ocrSetControlled $AppOCRCode ,$State ";

		MainClass::LogFile("OcrControlled",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Goods\":".$Last."}";


	}

	public function ocrShortage(){
		if (isset($_REQUEST['OCRFactorRowCode'])){ $OCRFactorRowCode = $_REQUEST['OCRFactorRowCode'];} else {$OCRFactorRowCode = "0";};
		if (isset($_REQUEST['Shortage'])){ $Shortage = $_REQUEST['Shortage'];} else {$Shortage = "0";};
		
		$sq = "Exec dbo.spApp_ocrSetShortage ".$OCRFactorRowCode.", ".$Shortage;
		MainClass::LogFile("ocrShortage",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Factors\":".$Last."}";
	}
	
	public function OcrDeliverd(){
		

		if (isset($_REQUEST['AppOCRCode'])){ $AppOCRCode = $_REQUEST['AppOCRCode'];} else {$AppOCRCode = "0";};
		if (isset($_REQUEST['State'])){ $State = $_REQUEST['State'];} else {$State = "0";};
		if (isset($_REQUEST['Deliverer'])){ $Deliverer = $_REQUEST['Deliverer'];} else {$Deliverer = "";};
		
		$sq = "Exec dbo.spApp_ocrSetDelivery ".$AppOCRCode.", ".$State.",'".$Deliverer."'";

		MainClass::LogFile("OcrDeliverd",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Factors\":".$Last."}";

	}


	public function GetOcrFactor(){
		$barcode= $_REQUEST['barcode'];
		if (isset($_REQUEST['Step'])){ $Step = $_REQUEST['Step'];} else {$Step = "0";};
		if (isset($_REQUEST['orderby'])){ $orderby = $_REQUEST['orderby'];} else {$orderby = "Goodname";};
		
		$sq = "Exec dbo.spApp_ocrGetFactor '$barcode',1,$Step,'$orderby'";	

		MainClass::LogFile("GetOcrFactor",$sq);

		$result = database::custom_sqlSRV($sq, true);
		$I=0;
		if ($result) {
			foreach ($result as $key => $value) {
				if ($I==0)
				{
					$I = 1;
					$Header["FactorCode"] = $value["FactorCode"];
					$Header["FactorPrivateCode"] = $value["FactorPrivateCode"];
					$Header["FactorDate"] = $value["FactorDate"];
					$Header["SumAmount"] = $value["SumAmount"];
					$Header["SumPrice"] = $value["SumPrice"];
					$Header["NewSumPrice"] = $value["NewSumPrice"];
					$Header["CustName"] = $value["CustName"];
					$Header["CustomerRef"] = $value["CustomerRef"];
					$Header["Address"] = $value["Address"];
					$Header["Phone"] = $value["Phone"];
					$Header["ErrCode"] = $value["ErrCode"];
					$Header["ErrMessage"] = $value["ErrMessage"];
					$Header["AppIsControled"] = $value["AppIsControled"];
					$Header["AppIsPacked"] = $value["AppIsPacked"];
					$Header["AppOCRFactorCode"] = $value["AppOCRFactorCode"];
				}
				$Row["GoodCode"] = $value["GoodRef"];
				$Row["GoodMaxSellPrice"] = $value["GoodMaxSellPrice"];
				$Row["FactorRowCode"] = $value["FactorRowCode"];
				$Row["GoodName"] = $value["GoodName"];
				$Row["Price"] = $value["Price"];
				$Row["FacAmount"] = $value["FacAmount"];
				$Row["GoodExplain4"] = $value["GoodExplain4"];
				$Row["AppRowIsControled"] = $value["AppRowIsControled"];
				$Row["AppRowIsPacked"] = $value["AppRowIsPacked"];
				$Row["AppOCRFactorRowCode"] = $value["AppOCRFactorRowCode"];
				$Row["ShortageAmount"] = $value["ShortageAmount"];
				$Row["CachedBarCode"] = $value["CachedBarCode"];

				array_push($this->response, $Row);
			}
		} 
		else {
			$Header["FactorCode"] = 0;
			$Header["FactorPrivateCode"] = 0;
			$Header["FactorDate"] = "";
			$Header["SumAmount"] = 0;
			$Header["SumPrice"] = 0;
			$Header["NewSumPrice"] = 0;
			$Header["CustomerRef"] = "";
			$Header["CustName"] = "";
			$Header["Address"] = "";
			$Header["Phone"] = "";
			$Header["ErrCode"] = "1";
			$Header["ErrMessage"] = "";
			$Header["IsControled"] = "";
			$Header["IsPacked"] = "";
			$Header["AppOCRFactorCode"] = "";
			$Row["IsControled"] =0;
			$Row["IsPacked"] = 0;
			$Row["GoodCode"] = 0;
			$Row["GoodMaxSellPrice"] = 0;
			$Row["FactorRowCode"] = 0;
			$Row["GoodName"] = "";
			$Row["Price"] = 0;
			$Row["FacAmount"] = 0;
			$Row["AppRowIsControled"] =0;
			$Row["AppRowIsPacked"] = 0;
			$Row["AppOCRFactorRowCode"] = 0;
			array_push($this->response, $Row);
		}
		$JHeader = json_encode($Header, JSON_UNESCAPED_UNICODE);
		$JRows   = json_encode($this->response, JSON_UNESCAPED_UNICODE);

		echo "{\"Factor\":".$JHeader.", \"Goods\":".$JRows."}";
	}

	public function GetCustomerPath(){
		$sq = "Select Distinct IsNull(NVarchar5 , '') CustomerPath From PropertyValue Where ClassName= 'TCustomer'";
		MainClass::LogFile("GetCustomerPath",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Factors\":".$Last."}";
	}

	public function GetStackCategory(){
		
		
		$sq = "Select Distinct IsNull(GoodExplain4 , '') GoodExplain4 From good ";

		MainClass::LogFile("GetStackCategory",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Goods\":".$Last."}";
		
	}

	public function GetOcrGoodDetail(){
		

		if (isset($_REQUEST['GoodCode'])){ $GoodCode = $_REQUEST['GoodCode'];} else {$State = "0";};
		$sq = "select cast(s.Amount as Int) TotalAvailable ,size,CoverType,cast(PageNo as Int) PageNo 
				from vwGood with(nolock) Join GoodStack s with(nolock) on GoodCode = GoodRef 
				where StackRef = 10110 And Goodcode=".$GoodCode;

		MainClass::LogFile("GetOcrGoodDetail",$sq);		
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Goods\":".$Last."}";

	}

	public function GetFactorList(){
		

		if (isset($_REQUEST['State'])){ $State = $_REQUEST['State'];} else {$State = "0";};
		if (isset($_REQUEST['SearchTarget'])){ $SearchTarget =$_REQUEST['SearchTarget'];} else {$SearchTarget = "";};
		if (isset($_REQUEST['Stack'])){ $Stack =$_REQUEST['Stack'];} else {$Stack = "";};
		if (isset($_REQUEST['path'])){ $path =$_REQUEST['path'];} else {$path = "";};
		if (isset($_REQUEST['Row'])){ $Row =$_REQUEST['Row'];} else {$Row = "10";};
		if (isset($_REQUEST['PageNo'])){ $PageNo =$_REQUEST['PageNo'];} else {$PageNo = 0;};

		if (isset($_REQUEST['HasShortage'])){ $HasShortage =$_REQUEST['HasShortage'];} else {$HasShortage = "0";};
		if (isset($_REQUEST['IsEdited'])){ $IsEdited =$_REQUEST['IsEdited'];} else {$IsEdited = "0";};

		$Where = "";
		
		if($State=="0"){
			if($Stack=="همه"){		
				$Where = "";
					
			}else{
				$Where = " And Exists(Select 1 From FactorRows r Join Good g on GoodRef=GoodCode Join AppOCRFactorRow cr on cr.AppFactorRowRef=r.FactorRowCode And cr.AppOCRFactorRef=o.AppOCRFactorCode Where r.FactorRef=FactorCode And IsNull(GoodExplain4, '''')=''$Stack'' And IsNull(cr.AppRowIsControled,0)=0) ";	

			}
		}		
		if($State=="4"){

			$order = ", ' order by o.AppTcPrintRef desc' ";	
		}else{
			$order = ", ' order by o.AppTcPrintRef ' ";	

		}
		
		if($path=="همه"){			
			$Where = $Where." ";	
		}else {
			$Where = $Where." And IsNull(f.Ersall, '''') = N''$path'' ";	

		}		
		
		if ($HasShortage=="1"){
			$Where = $Where." And o.HasShortage=1 ";	

		}		
		if ($IsEdited=="1"){
			$Where = $Where." And o.IsEdited=1 ";	

		}
		
		$sq = "Exec dbo.spApp_ocrFactorList $State , '$SearchTarget' ,'$Where',$Row ,$PageNo $order ";
		MainClass::LogFile("GetFactorList",$sq);	
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Factors\":".$Last."}";

	}
				
	public function GetFactorListCount(){
		

		if (isset($_REQUEST['State'])){ $State = $_REQUEST['State'];} else {$State = "0";};
		if (isset($_REQUEST['SearchTarget'])){ $SearchTarget =$_REQUEST['SearchTarget'];} else {$SearchTarget = "";};
		if (isset($_REQUEST['Stack'])){ $Stack =$_REQUEST['Stack'];} else {$Stack = "";};
		if (isset($_REQUEST['path'])){ $path =$_REQUEST['path'];} else {$path = "";};
		if (isset($_REQUEST['Row'])){ $Row =$_REQUEST['Row'];} else {$Row = "10";};
		if (isset($_REQUEST['PageNo'])){ $PageNo =$_REQUEST['PageNo'];} else {$PageNo = 0;};

		if (isset($_REQUEST['HasShortage'])){ $HasShortage =$_REQUEST['HasShortage'];} else {$HasShortage = "0";};
		if (isset($_REQUEST['IsEdited'])){ $IsEdited =$_REQUEST['IsEdited'];} else {$IsEdited = "0";};

		$Where = "";
		
		if($State=="0"){
			if($Stack=="همه"){		
				$Where = "";
					
			}else{
				$Where = " And Exists(Select 1 From FactorRows r Join Good g on GoodRef=GoodCode Join AppOCRFactorRow cr on cr.AppFactorRowRef=r.FactorRowCode And cr.AppOCRFactorRef=o.AppOCRFactorCode Where r.FactorRef=FactorCode And IsNull(GoodExplain4, '''')=''$Stack'' And IsNull(cr.AppRowIsControled,0)=0) ";	

			}
		}
		
		if($path=="همه"){			
			$Where = $Where." ";	
		}else {
			$Where = $Where." And IsNull(f.Ersall, '''') = N''$path'' ";	

		}
		
		
		if ($HasShortage=="1"){
			$Where = $Where." And o.HasShortage=1 ";	

		}		
		if ($IsEdited=="1"){
			$Where = $Where." And o.IsEdited=1 ";	

		}
		$sq = "Exec dbo.spApp_ocrFactorListTotal $State , '$SearchTarget' ,'$Where',$Row ,$PageNo ";

		MainClass::LogFile("GetFactorListCount",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Factors\":".$Last."}";

	}
	

	public function SetPackDetail(){
		

		if (isset($_REQUEST['OcrFactorCode'])){ $OcrFactorCode = $_REQUEST['OcrFactorCode'];} 
		if (isset($_REQUEST['Reader'])){ $Reader = $_REQUEST['Reader'];} else {$Reader = " ";};
		if (isset($_REQUEST['Controler'])){ $Controler = $_REQUEST['Controler'];} else {$Controler = " ";};
		if (isset($_REQUEST['Packer'])){ $Packer = $_REQUEST['Packer'];} else {$Packer = " ";};
		if (isset($_REQUEST['PackDeliverDate'])){ $PackDeliverDate = $_REQUEST['PackDeliverDate'];} else {$PackDeliverDate = " ";};
		if (isset($_REQUEST['PackCount'])){ $PackCount = $_REQUEST['PackCount'];} else {$PackCount = "1";};
		if (isset($_REQUEST['AppDeliverDate'])){ $AppDeliverDate = $_REQUEST['AppDeliverDate'];} else {$AppDeliverDate = "";};

		$sq = "Exec dbo.spApp_ocrSetPackDetail $OcrFactorCode,'".$Reader."','".$Controler."','$Packer - $AppDeliverDate','".$PackDeliverDate."',".$PackCount;

		MainClass::LogFile("SetPackDetail",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Goods\":".$Last."}";

	}


	public function SaveOcrImage(){
		$barcode= $_REQUEST['barcode'];
		$image= $_REQUEST['image'];
		
		//MainClass::LogFile("SaveOcrImage",$_REQUEST);
		//OcrImageOld();
		
		$decodedImage = base64_decode($image);
		file_put_contents("images/$barcode.jpg", $decodedImage);
		$nameZip = "images/$barcode.zip";
		$zip = new ZipArchive();
		$zip->open($nameZip, ZipArchive::CREATE);
		$zip->addFromString("$barcode.jpg", $decodedImage);
		$zip->close();
		database::SaveOCR_sqlSRV($barcode, $nameZip);


	}

	public function OcrImageOld(){
		$lasttime=date("Ymd");
		foreach (glob("/xampp/htdocs/login/images/*.*") as $filename) {
			$filedate = date("Ymd", filemtime($filename));
			 if($filedate<$lasttime){
			 	unlink($filename);
			}
		}
	}


}
