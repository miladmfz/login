<?php
 date_default_timezone_set('Asia/Tehran');
 class BrokerClass
{
	
    private $response = array();
    function __construct(){}




	public function testdate(){

        if (isset($_REQUEST['GpsDate'])){ $GpsDate = $_REQUEST['GpsDate'];} else {$GpsDate = "";};
 
        $GpsDate = str_replace('\\', '', $GpsDate);
        $dateObj = new DateTime($GpsDate);
        $formattedDate = $dateObj->format('Y/m/d H:i:s');
        echo $formattedDate ;


    }





	public function UpdateLocation(){

        $ExistFlag = 0;
                
        IF ($ExistFlag == 0){
            $DJson = $_REQUEST['GpsLocations'];
            $dobj = json_decode($DJson, true);		
            if ($dobj) {
                foreach ($dobj as $key => $value) {
 
                    $Longitude = $value["Longitude"];
                    $Latitude = $value["Latitude"];
                    $BrokerRef = $value["BrokerRef"];
                    $GpsDate = $value["GpsDate"];


                    $GpsDate = str_replace('\\', '', $GpsDate);
                    $dateObj = new DateTime($GpsDate);
                    $formattedDate = $dateObj->format('Y/m/d H:i:s');





                    $sq = " Insert Into GpsLocation (Longitude,Latitude,BrokerRef,GpsDate ) values  ('$Longitude','$Latitude',$BrokerRef,'$formattedDate') select @@IDENTITY";		
                    
                    MainClass::LogFile("UpdateLocation",$sq);
                    
                    $res = database::custom_sqlSRV($sq,true);

                }
            }
            

        }
        

        $sq = "select top 1 * from GpsLocation order by 1 desc   ";
        $this->response = database::custom_sqlSRV($sq,true);
        $Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
    
        echo "{\"Locations\":".$Last."}";		
    }



    public function BrokerStack(){
        
        if (isset($_REQUEST['BrokerRef'])){
            $Code = $_REQUEST['BrokerRef'];
        }else {
            $Code = "0" ;
        }
    
        
        $sq = "exec spApp_GetBrokerStack ".$Code;
        MainClass::LogFile("BrokerStack",$sq);
        $result = database::custom_sqlSRV($sq, true);
        if($result) {					
            $BrokerStack = $result[0]["BrokerStack"];
        }		
        echo "{\"Text\":\"".$BrokerStack."\"}";	
    
    
        
    }
    
    public function CustomerInsert(){
        $sq = "exec spApp_CustomerIns ";
        if (isset($_REQUEST['BrokerRef'])){ $sq = $sq." @BrokerRef = ".$_REQUEST['BrokerRef'];}
        if (isset($_REQUEST['CityCode'])){ $sq = $sq.", @CityCode = ".$_REQUEST['CityCode'];}
        if (isset($_REQUEST['KodeMelli'])){ $sq = $sq.", @KodeMelli = '".$_REQUEST['KodeMelli']."'";}
        if (isset($_REQUEST['FName'])){ $sq = $sq.", @FName = '".$_REQUEST['FName']."'";}
        if (isset($_REQUEST['LName'])){ $sq = $sq.", @LName = '".$_REQUEST['LName']."'";}
        if (isset($_REQUEST['Address'])){ $sq = $sq.", @Address = '".$_REQUEST['Address']."'";}
        if (isset($_REQUEST['Phone'])){ $sq = $sq.", @Phone = '".$_REQUEST['Phone']."'";}
        if (isset($_REQUEST['Mobile'])){ $sq = $sq.", @Mobile = '".$_REQUEST['Mobile']."'";}
        if (isset($_REQUEST['Fax'])){ $sq = $sq.", @Fax = '".$_REQUEST['Fax']."'";}
        if (isset($_REQUEST['EMail'])){ $sq = $sq.", @EMail = '".$_REQUEST['EMail']."'";}
        if (isset($_REQUEST['PostCode'])){ $sq = $sq.", @PostCode = '".$_REQUEST['PostCode']."'";}
        if (isset($_REQUEST['ZipCode'])){ $sq = $sq.", @ZipCode = '".$_REQUEST['ZipCode']."'";}
        if (isset($_REQUEST['UserId'])){ $sq = $sq.", @UserId = '".$_REQUEST['UserId']."'";}
        
        $this->response = database::custom_sqlSRV($sq,true);

        MainClass::LogFile("CustomerInsert",$sq);
        $Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
    
        echo "{\"Customers\":".$Last."}";		
    }

    public function getImageInfo(){
        if (isset($_REQUEST['code'])){ $Code = $_REQUEST['code'];} else {$Code = 0;};
        $sq = "Exec spApp_GetInfo 1, 'KsrImage', ".$Code." , @RowCount=200, @CountFlag=1 ";
        MainClass::LogFile("getImageInfo",$sq);
        $res = database::custom_imgSRV($sq,true);
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    
    }

	public function GetMenuBroker(){
	
		$sq = "select DataValue from DbSetup where KeyValue='AppBroker_MenuGroupCode'";		
        MainClass::LogFile("GetMenuBroker",$sq);
		$result = database::custom_sqlSRV($sq, true);
		if($result) {					
			$kowsar_info = $result[0]["DataValue"];
		}		
		echo "{\"Text\":\"".$kowsar_info."\"}";
    }

    public function MaxRepLogCode(){
    
            $sq = "Select top 1 * from RepLogData order by 1 desc";	
            $result = database::custom_sqlSRV($sq, true);
            MainClass::LogFile("MaxRepLogCode",$sq);
            if($result) {					
                $kowsar_info = $result[0]["RepLogDataCode"];
            }		
            echo "{\"Text\":\"".$kowsar_info."\"}";
    
    }
    
    public function PFQASWED(){             //spPreFactor_Insert
        
        $UserId = -1000;
        $Stk = 1;
        $HJson = $_REQUEST['PFHDQASW'];
        $hobj = json_decode($HJson, true);
        $factorcode = 0;
        $factordate = "";
        $Explain = "BazaryabApp";
        $Customer = 0;
        $Broker = 0;
        $MobFCode = 0;
        $MobFDate = "";
        $ExistFlag = 0;
        $ClassName = "PreFactor";
        $CountRows = 0;

        $NotAmount = array();
        $z=0;


        if ($hobj){
            if (isset($hobj[0]["PreFactorCode"])){$MobFCode = $hobj[0]["PreFactorCode"];}
            if (isset($hobj[0]["PreFactorDate"])){$MobFDate = $hobj[0]["PreFactorDate"];}
            if (isset($hobj[0]["CustomerRef"])){$Customer = $hobj[0]["CustomerRef"];}
            if (isset($hobj[0]["BrokerRef"])){$Broker = $hobj[0]["BrokerRef"];}
            if (isset($hobj[0]["PreFactorExplain"])){$Explain = $hobj[0]["PreFactorExplain"];}
            if (isset($hobj[0]["rwCount"])){$CountRows = $hobj[0]["rwCount"];}
            
            $sq = "IF Exists(Select 1 From DbSetup Where KeyValue = 'App_FactorTypeInKowsar' And DataValue='1') Select ClassName = 'Factor' Else Select ClassName = 'PreFactor' ";
            $ClassResult = database::custom_sqlSRV($sq,true);
            if($ClassResult) {					
                $ClassName = $ClassResult[0]["ClassName"];
            }
            
            $sq = "Exec [dbo].[spPreFactor_Insert] '".$ClassName."', $Stk, $UserId, 0, '', $Customer, '".$Explain."', $Broker, $MobFCode, '".$MobFDate."'";

            MainClass::LogFile("spPreFactor_Insert",$sq);
            
            $result = database::custom_sqlSRV($sq,true);
            if($result) {					
                $factorcode = $result[0]["PreFactorCode"];
                $factordate = $result[0]["PreFactorDate"];
                $ExistFlag = $result[0]["ExistFlag"];
            }
        }
                
        IF ($ExistFlag == 0){
            $DJson = $_REQUEST['PFDTQASW'];
            $dobj = json_decode($DJson, true);		
            if ($dobj) {
                foreach ($dobj as $key => $value) {
                    $z++;
                    $Code = $value["GoodRef"];
                    $Amount = $value["FactorAmount"];
                    $Price = $value["Price"];
                    
                    
                    $sq = "Exec [dbo].[spPreFactor_InsertRow] '".$ClassName."', $factorcode , $Code, $Amount, 0, $UserId, '', 1, 0, $Price";
                    MainClass::LogFile("spPreFactor_InsertRow",$sq);
                    
                    $res = database::custom_sqlSRV($sq,true);
                    if($res) { 
                        if ($res[0]["RowCode"]==-1){
                            $NotAmount["GoodCode"] = $Code;
                            $NotAmount["Flag"] = 1;
                            array_push($this->response, $NotAmount);
                        }
                        else if ($res[0]["RowCode"]==-2){
                            $NotAmount["GoodCode"] = $Code;
                            $NotAmount["Flag"] = 2;
                            array_push($this->response, $NotAmount);
                        }
                    }
                }
            }
            
            if(count($this->response)<1 ){
                $sq1="Select Sum(FacAmount) rcount From ".$ClassName."Rows Where ".$ClassName."Ref= ".$factorcode;	
                MainClass::LogFile("spPreFactor_InsertRow",$sq1);
                $res1 = database::custom_sqlSRV($sq1,true);
                $rcount= $res1[0]["rcount"];
                
                if($CountRows ==$rcount){
                    $NotAmount["GoodCode"] = "0";
                    $NotAmount["PreFactorCode"] = $factorcode;
                    $NotAmount["PreFactorDate"] = $factordate;
                    $NotAmount["ExistFlag"] = $ExistFlag;
                    array_push($this->response, $NotAmount);
                }else{
                    $sq = "Delete ".$ClassName."Rows Where ".$ClassName."Ref = ".$factorcode;
                    $sq1 = "Delete ".$ClassName." Where ".$ClassName."Code = ".$factorcode;
                    MainClass::LogFile("spPreFactor_InsertRow",$sq);
                    MainClass::LogFile("spPreFactor_Insert",$sq1);
                    database::custom_sqlSRV($sq,true);
                    database::custom_sqlSRV($sq1,true);
                }
            }else{
                $sq = "Delete ".$ClassName."Rows Where ".$ClassName."Ref = ".$factorcode;
                $sq1 = "Delete ".$ClassName." Where ".$ClassName."Code = ".$factorcode;
                MainClass::LogFile("spPreFactor_InsertRow",$sq);
                MainClass::LogFile("spPreFactor_Insert",$sq1);
                database::custom_sqlSRV($sq,true);
                database::custom_sqlSRV($sq1,true);	
            }
        }else{
            $NotAmount["GoodCode"] = "0";
            $NotAmount["PreFactorCode"] = $factorcode;
            $NotAmount["PreFactorDate"] = $factordate;
            $NotAmount["ExistFlag"] = $ExistFlag;
            array_push($this->response, $NotAmount);			
        }
        
        return json_encode($this->response, JSON_UNESCAPED_UNICODE);		
    }
    
    public function repinfo(){
        if (isset($_REQUEST['table'])){ $Table = $_REQUEST['table'];} else {$Table = "";};
        if (isset($_REQUEST['code'])){ $Code = $_REQUEST['code'];} else {$Code = 0;};
        if (isset($_REQUEST['reptype'])){ $RepType = $_REQUEST['reptype'];} else {$RepType = 0;};
        if (isset($_REQUEST['Reprow'])){ $Reprow = $_REQUEST['Reprow'];} else {$Reprow = 100;};
        if (isset($_REQUEST['Where'])){ $Where = $_REQUEST['Where'];} else {$Where = "''";};
    
        
        if ($Table=="PropertyValue") 
        {
            $Where = "  Where ClassName = ''TGood''";
        }
        
        $sq = "Exec spApp_GetInfo ".$RepType.", ".$Table.", ".$Code.", @RowCount=".$Reprow.", @CountFlag=1 , @WhereCluase= ' $Where '";

        MainClass::LogFile("repinfo",$sq);
        
        if($Table =="KsrImage"){
            $this->response = database::custom_imgSRV($sq,true);
        }else{
            $this->response = database::custom_sqlSRV($sq,true);
        }
        
        
        
        $Last =  json_encode($this->response, JSON_UNESCAPED_UNICODE);
        
        $Replication_array = array("Text" =>$Last );
        
        echo json_encode($Replication_array, JSON_UNESCAPED_UNICODE);
    }


}
