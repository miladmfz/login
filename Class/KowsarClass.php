<?php
 date_default_timezone_set('Asia/Tehran');

 class KowsarClass
{
	
    private $response = array();
    function __construct(){}
	
	public function TestFun(){
		//MainClass::LogFile("TestFun","TestFunText");


		// $directoryToScan = "\xampp\htdocs\login\images";
		// $array = array();
		// $num_files = count(glob("/xampp/htdocs/login/images/*.*"));
		// $totalFiles = (string)$num_files;
		// $lasttime=date("Ymd");
		// foreach (glob("/xampp/htdocs/login/SlideImage/*.*") as $filename) {
		// 	$filedate = date("Ymd", filemtime($filename));

		// 	 if($filedate<$lasttime){
		// 	 	unlink($filename);
		// 	 }

		// }
		
	
	}




			
	public function check1()
    {
        $Res = array();

        $sq = "Select Top 10 * from good order by 1 desc";
		$result = database::custom_sqlSRV($sq,true);
        if ($result) {
            foreach ($result as $key => $value) {
                $Res["state"] = "1";
                $Res["GoodCode"] = $value["GoodCode"];
                $Res["GoodName"] = $value["GoodName"];
                $Res["MaxSellPrice"] = $value["MaxSellPrice"];
                array_push($this->response, $Res);
            }
        } else {
            $this->response["state"] = "0";
        }
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Goods\":".$Last."}";
    }
	public function check2()
    {
		$Res = array();

        $sq = "Select Top 10 * from good order by 1 ";
		$result = database::custom_sqlSRV($sq,true);
        if ($result) {
            foreach ($result as $key => $value) {
                $Res["state"] = "1";
                $Res["GoodCode"] = $value["GoodCode"];
                $Res["GoodName"] = $value["GoodName"];
                $Res["MaxSellPrice"] = $value["MaxSellPrice"];
                array_push($this->response, $Res);
            }
        } else {
            $this->response["state"] = "0";
        }
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Goods\":".$Last."}";
    }



	public function kowsar_info(){
		
		$kowsar_info="";
		$WHERE="";
		if (isset($_REQUEST['Where'])){ $WHERE = $_REQUEST['Where'];} else {$Where = "";};
		$sq = "select top 1 DataValue from dbsetup where KeyValue = '$WHERE'";	
		MainClass::LogFile("kowsar_info",$sq);
		$result = database::custom_sqlSRV($sq, true);
		
		if($result) {					
			$kowsar_info = $result[0]["DataValue"];
		}		
		echo "{\"Text\":\"".$kowsar_info."\"}";
	}
	

	public function Notification_kowsar(){
		

	
		if (isset($_REQUEST['Condition'])){ $Condition = $_REQUEST['Condition'];} else {$Condition = "";};

		if ($Condition=="asli") 
        {
            $Where = "";
        }

	
		echo "{\"Text\":\"".$Where."\"}";
	}
	




	
    public function ActivationCode(){
		if (isset($_REQUEST['ActivationCode'])){ $ActivationCode = $_REQUEST['ActivationCode'];} else {$ActivationCode = "";};

		$sq = "select * from AppBrokerCustomer Where ActivationCode = '".$ActivationCode."'";		

		MainClass::LogFile("ActivationCode",$sq);
		$this->response = database::custom_sqlSRV($sq,true);
		$Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
		echo "{\"Activations\":".$Last."}";
		
    }

	public function ErrorLog(){

    	if (isset($_REQUEST['ErrorLog'])){ $ErrorLog = $_REQUEST['ErrorLog'];} else {$ErrorLog = "";};
    	if (isset($_REQUEST['Broker'])){ $Broker = $_REQUEST['Broker'];} else {$Broker = "";};
    	if (isset($_REQUEST['DeviceId'])){ $DeviceId = $_REQUEST['DeviceId'];} else {$DeviceId = "";};
    	if (isset($_REQUEST['ServerName'])){ $ServerName = $_REQUEST['ServerName'];} else {$ServerName = "";};
    	if (isset($_REQUEST['VersionName'])){ $VersionName = $_REQUEST['VersionName'];} else {$VersionName = "";};
    	if (isset($_REQUEST['StrDate'])){ $StrDate = $_REQUEST['StrDate'];} else {$StrDate = "";};
    


		$sq = " Insert into ErrorLogReport([ErrorLogText], [Broker], [DeviceId], [ServerName], [VersionName], [StrDate])values ('$ErrorLog','$Broker','$DeviceId','$ServerName','$VersionName','$StrDate')";

		MainClass::LogFile("ErrorLog",$sq);
		$result = database::custom_sqlSRV($sq, true);
		
		return "\"done\"";
	}		

	public function Log_report(){
		if (isset($_REQUEST['Device_Id'])){ $device_Id = $_REQUEST['Device_Id'];} else {$device_Id = "";};
		if (isset($_REQUEST['Address_Ip'])){ $address_ip = $_REQUEST['Address_Ip'];} else {$address_ip = "";};
		if (isset($_REQUEST['Server_Name'])){ $server_name = $_REQUEST['Server_Name'];} else {$server_name = "";};
		if (isset($_REQUEST['Factor_Code'])){ $factor_code = $_REQUEST['Factor_Code'];} else {$factor_code = "";};
		if (isset($_REQUEST['StrDate'])){ $strDate = $_REQUEST['StrDate'];} else {$strDate = "";};
		if (isset($_REQUEST['Broker'])){ $Broker = $_REQUEST['Broker'];} else {$Broker = "";};
		if (isset($_REQUEST['Explain'])){ $Explain = $_REQUEST['Explain'];} else {$Explain = "";};

		$sq = "exec spApp_appinfo '$device_Id','$address_ip','$server_name','$factor_code','$strDate','$Broker','$Explain'    ";

		MainClass::LogFile("Log_report",$sq);
		$result = database::custom_sqlSRV($sq, true);
		return "\"done\"";
	}		

	public function Verification() {
		
		if (isset($_REQUEST['Code'])){ $Code = $_REQUEST['Code'];} else {$Code = "";};
		if (isset($_REQUEST['MobileNumber'])){ $MobileNumber = $_REQUEST['MobileNumber'];} else {$MobileNumber = "";};
		$sq="$Code_$MobileNumber";
		MainClass::LogFile("Verification",$sq);
		$this->VerificationCode1("$Code", "$MobileNumber");
		echo "{\"Text\":\"done\"}";

	}



	function VerificationCode1($Code, $MobileNumber) {
		

		$token = $this->GetToken();
		if($token != false){
			$postData = array(
				'Code' => $Code,
				'MobileNumber' => $MobileNumber,
			);
			
			$url = $this->getAPIVerificationCodeUrl();
			$VerificationCode = $this->execute($postData, $url, $token);
			$object = json_decode($VerificationCode);

			if(is_object($object)){
				$array = get_object_vars($object);
				if(is_array($array)){
					$result = $array['Message'];
				} else {
					$result = false;
				}
			} else {
				$result = false;
			}
			
		} else {
			$result = false;
		}
		return $result;
	}
	private function GetToken(){
		$postData = array(
			'UserApiKey' => '1e605332b72c1590d5c57b3',
			'SecretKey' => 'Kowsar321@!',
		);
		$postString = json_encode($postData);

		$ch = curl_init($this->getApiTokenUrl());

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                            'Content-Type: application/json'
                                            ));		
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);

		$result = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($result);
		
		if(is_object($response)){
			$resultVars = get_object_vars($response);
			if(is_array($resultVars)){
				@$IsSuccessful = $resultVars['IsSuccessful'];
				if($IsSuccessful == true){
					@$TokenKey = $resultVars['TokenKey'];
					$resp = $TokenKey;
					
				} else {
					$resp = false;

				}
			}
		}
		return $resp;
	}
	protected function getAPIVerificationCodeUrl() {
		return "http://RestfulSms.com/api/VerificationCode";
	}
	protected function getApiTokenUrl(){
		
		return "http://RestfulSms.com/api/Token";
	}
	private function execute($postData, $url, $token){
		
		$postString = json_encode($postData);

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
											'Content-Type: application/json',
											'x-sms-ir-secure-token: '.$token
											));		
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
		
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

}



