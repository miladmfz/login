<?php

class database{

    private static $db;
    private static $imgdb;
    

    
    private static function connectSQLSRV(){
        try
        {
            self::$db = new PDO ("sqlsrv:server=".sql_servername.";Database=".sql_dbname, sql_user, sql_pass);
            return true;
        }
        catch (PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
            return false;
        }
    }

    private static function connectImgSRV(){
        try
        {
            self::$imgdb = new PDO ("sqlsrv:server=".img_servername.";Database=".img_dbname, img_user, img_pass);
            return true;
        }
        catch (PDOException $e)
        {
            echo "Image Connection failed: " . $e->getMessage();
            return false;
        }
    }
    
    private static function close(){
        self::$db=null;
        self::$imgdb=null;
    }

    public static function excuteQuery($query){
        if(self::connect())
        {
            $sql = $query;

            $stmt = self::$db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();
            if (count($result) !=0) {
                self::close();
                return $result;
            } else {
                self::close();
                return false;
            }
        }
    }

    public static function custom_sql($sql, $fetchAll = true){
        if(self::connect() && $sql!="")
        {


            $stmt = self::$db->prepare($sql);
            $stmt->execute();
            $result = $fetchAll == true?$stmt->fetchAll(PDO::FETCH_BOTH):$stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                self::close();
                return $result;
            } else {
                self::close();
                return false;
            }
        }

    }

    public static function custom_sqlSRV($sql, $fetchAll = true){
        if(self::connectSQLSRV() && $sql!="")
        {
            $stmt = self::$db->prepare($sql);
            $stmt->execute();
            $result = $fetchAll == true?$stmt->fetchAll(PDO::FETCH_ASSOC):$stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                self::close();
                return $result;
            } else {
                self::close();
                return false;
            }
        }

    }

    public static function custom_imgSRV($sql, $fetchAll = true){
        if(self::connectImgSRV() && $sql!="")
        {
            $stmt = self::$imgdb->prepare($sql);
            $stmt->execute();
            $result = $fetchAll == true?$stmt->fetchAll(PDO::FETCH_ASSOC):$stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                self::close();
                return $result;
            } else {
                self::close();
                return false;
            }
        }

    }
		
public static function SaveOCR_sqlSRV($aBarcode, $aFileName)
        {

            if(self::connectSQLSRV())
            { 
		        $stmt = self::$db->prepare("Exec dbo.[spApp_ocrGetFactor] '$aBarcode', 0 ");
                $stmt->execute();

                //$result = $fetchAll == true?$stmt->fetchAll(PDO::FETCH_ASSOC):$stmt->fetch(PDO::FETCH_ASSOC);
                $result =$stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
					$ocrDB = $result['dbname'];
					$FactorRef = $result['FactorRef'];
					$TcPrintRef = $result['TcPrintRef'];
					$cn = file_get_contents($aFileName);
					$stmt = self::$db->prepare("Insert Into $ocrDB.dbo.AttachedFiles(Title, ClassName, ObjectRef, FileName, SourceFile, Type, Owner, CreationDate, Reformer, ReformDate, TcPrintRef)
						Select 'App_ocr', 'Factor', $FactorRef, '$aBarcode.jpg', :FContent, 'zip', -1000, GetDate(), -1000, GetDate(), $TcPrintRef ");
					$stmt->bindParam(':FContent', $cn, PDO::PARAM_LOB, 0, PDO::SQLSRV_ENCODING_BINARY);
					$stmt->execute();
					$stmt = self::$db->prepare("set nocount on Update AppOCRFactor Set HasSignature = 1 Where AppTcPrintRef= $TcPrintRef");
                    $stmt->execute();
					//echo "{\"Text\":\"done\"}";
					echo "\"done\"";
					return true;
				}
            }

        }



    public static function getImage_sqlSRV($sql, $fetchAll = true){
        if(self::connectSQLSRV() && $sql!="")
        {		
            $stmt = self::$db->prepare($sql);
            $stmt->execute();
            $result = $fetchAll == true?$stmt->fetchAll(PDO::FETCH_ASSOC):$stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                self::close();
                return $result;
            } else {
                self::close();
                return false;
            }

        }

    }	
    
    
}


?>