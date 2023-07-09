<?php

	if(isset($_REQUEST['tag'])) {
        $method = $_REQUEST['tag'];
        $arg = isset($_REQUEST['arg']) && !empty($_REQUEST['arg']) ? $_REQUEST['arg'] : null;
        
        require_once "Config/database.php";
        require_once "Config/jdf.php";

        require_once "Class/DatabaseClass.php";
		require_once "Class/MainClass.php";
        require_once "Class/OcrClass.php";
        require_once "Class/BrokerClass.php";
        require_once "Class/CompanyClass.php";
        require_once "Class/KowsarClass.php";
        require_once "Class/OrderClass.php";
		require_once "Class/WebClass.php";
        
        $MainClass = new MainClass;
        $OcrClass = new OcrClass;
        $BrokerClass = new BrokerClass;
        $CompanyClass = new CompanyClass;
        $KowsarClass = new KowsarClass;
        $OrderClass = new OrderClass;
		$WebClass = new WebClass;

		if (method_exists($MainClass, $method)){
			$res = $MainClass->$method();
			echo $res;
        }
		
        if (method_exists($OcrClass, $method)){
			$res = $OcrClass->$method();
			echo $res;
        }
		
        if (method_exists($BrokerClass, $method)){
			$res = $BrokerClass->$method();
			echo $res;
        }	
		
        if (method_exists($CompanyClass, $method)){
			$res = $CompanyClass->$method();
			echo $res;
        }
				
		
        if (method_exists($KowsarClass, $method)){
			$res = $KowsarClass->$method();
			echo $res;
        }
				

		
        if (method_exists($OrderClass, $method)){
			$res = $OrderClass->$method();
			echo $res;
        }
		
		
        if (method_exists($WebClass, $method)){
			$res = $WebClass->$method();
			echo $res;
        }
					
        


        
    }
?>
