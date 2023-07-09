<?php

class database{

		private static $db;
		private static $imgdb;
		
        private static function connect(){
            try
            {
                self::$db = new PDO ("mysql:host=".db_host.";dbname=".db_name, db_user, db_pass);
                self::$db->query("SET NAMES 'utf8mb4'");
                return true;
            }
            catch (PDOException $e)
            {
                echo "Connection failed: " . $e->getMessage();
                return false;
            }
        }
		
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

		public static function select($tblName, $where = false, $fetchAll = true){
			if(self::connect() && $tblName!="")
			{
				$sql = "select * from $tblName where $where";
				if($where == "" || $where==false){
					$sql = "select * from $tblName";
				}
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

		public static function selectCount($tblName, $where, $as = false){
			if(self::connect())
			{
				$sql = $as == false?"select count(*) from $tblName WHERE $where":"select count(*) as $as from $tblName WHERE $where";
				$myfile = fopen("sql_count.txt", "w") or die("Unable to open file!");
				fwrite($myfile, $sql);
				fclose($myfile);
				$stmt = self::$db->prepare($sql);
				$stmt->execute();
				$result = $stmt->fetch();
				if ($result) {
					self::close();
					return $as == false?array("count(*)" => $result['count(*)']):array($as => $result["$as"]);
				} else {
					self::close();
					return false;
				}
			}

		}

		public static function selectCol($tblName, $param, $where, $limit =false , $order = false,  $order_by  = false,  $fetch = false){
			if(self::connect())
			{
				$ordrBy = ($order_by != false && !empty($order_by))?" ORDER BY  ".$order_by:"";
				$ordr   = ($order != false && !empty($order))?$order:"";
				if(!empty($tblName) && !empty($param))
				{
					if($limit != false){
						$sql ="select $param from $tblName ".(!empty($where)?"WHERE $where":"")." $ordrBy $ordr LIMIT $limit";
					}
					else{
						$sql ="select $param from $tblName ".(!empty($where)?"WHERE $where":"")." $ordrBy $ordr";
					}
					//echo json_encode($sql);
					$stmt = self::$db->prepare($sql);
					$stmt->execute();
					$result = $fetch != false? $stmt->fetchAll(): $stmt->fetch();
					if ($result)
					{
						self::close();
						return $result;
					}
					else
					{
						self::close();
						return false;
					}
				}
				else
				{
					return false;
				}
			}

		}


		public static function selectColGenerate($tblName, $param, $where){
			$sql = "select $param from $tblName where $where";
			return $sql;

		}


		public static function insertInto($tblName, $col, $value){
			if(self::connect())
			{
				$sql = "INSERT INTO $tblName ($col) VALUES ($value);";

				$myfile = fopen("insertInto.txt", "w") or die("Unable to open file!");
				fwrite($myfile, $sql);
				fclose($myfile);

				$stmt = self::$db->prepare($sql);
				$stmt->execute();
				$id = self::$db->lastInsertId();
				if ($stmt) {
					self::close();
					return $id;
				} else {
					self::close();
					return false;
				}
			}
		}


		public static function update($tblName, $param, $where){
			if(self::connect())
			{
				if(!empty($tblName) && !empty($param) &&!empty($where))
				{
					$sql  = "UPDATE $tblName SET $param WHERE $where;";


					// $myfile = fopen("sql.txt", "w") or die("Unable to open file!");
					// fwrite($myfile, $sql);
					// fclose($myfile);

					$stmt = self::$db->prepare($sql);
					$stmt->execute();
					if ($stmt) {
						self::close();
						return true;
					} else {
						self::close();
						return false;
					}
				}
				else
				{
					return false;
				}
			}
		}


		public static function delete($tblName, $where){
			if(self::connect())
			{
				if(!empty($tblName) && !empty($where))
				{
					$myfile = fopen("test.txt", "w") or die("Unable to open file!");
					fwrite($myfile,"DELETE FROM $tblName WHERE $where;");
					fclose($myfile);

					$stmt = self::$db->prepare("DELETE FROM $tblName WHERE $where;");

					$stmt->execute();
					if ($stmt) {
						self::close();
						return true;
					}
					else
					{
						self::close();
						return false;
					}

				}
				else
				{
					return false;
				}
			}
		}

		public static function selectJoin($tbl_1, $tbl_2, $cols, $joinType = true, $on, $where1 = false, $orderByField = false, $orderByType = false, $limit = false, $fetchAll = false){
			if(self::connect())
			{
				$sql = "SELECT $cols FROM $tbl_1".($joinType != false?" $joinType JOIN $tbl_2 ":" JOIN $tbl_2 ")."ON $on".($where1!=false?" WHERE $where1 ":"").($orderByField!=false?" ORDER BY $orderByField ":"").($orderByField!=false?($orderByType!=false?"$orderByType":"ASC"):"").($limit!=false?" LIMIT $limit;":";");

				$myfile = fopen("join_sql.txt", "w") or die("Unable to open file!");
				fwrite($myfile, $sql);
				fclose($myfile);

				$stmt = self::$db->prepare($sql);
				$stmt->execute();
				$result = $fetchAll != false? $stmt->fetchAll(PDO::FETCH_ASSOC): $stmt->fetch(PDO::FETCH_ASSOC);
				if (count($result) !=0) {
					self::close();
					return $result;
				} else {
					self::close();
					return false;
				}
			}
		}


		public static function selectJoin3($tbl_1, $tbl_2 , $tbl_3 , $cols, $joinType = true, $on1 , $on2 , $where1 = false, $orderByField = false, $orderByType = false, $limit = false, $fetchAll = false){
			if(self::connect())
			{
				$sql = "SELECT $cols FROM $tbl_1".($joinType != false?" $joinType JOIN $tbl_2 ":" JOIN $tbl_2 ")."ON $on1".($joinType != false?" $joinType JOIN $tbl_3 ":" JOIN $tbl_3 ")."ON $on2".($where1!=false?" WHERE $where1 ":"").($orderByField!=false?" ORDER BY $orderByField ":"").($orderByField!=false?($orderByType!=false?"$orderByType":"ASC"):"").($limit!=false?" LIMIT $limit;":";");

				// $myfile = fopen("3join.txt", "w") or die("Unable to open file!");
				//fwrite($myfile, $sql);
				//fclose($myfile);

				$stmt = self::$db->prepare($sql);
				$stmt->execute();
				$result = $fetchAll != false? $stmt->fetchAll(PDO::FETCH_ASSOC): $stmt->fetch(PDO::FETCH_ASSOC);
				if (count($result) !=0) {
					self::close();
					return $result;
				} else {
					self::close();
					return false;
				}
			}
		}

		public static function selectUnion($cols, $arrayTables,$where , $orderBy, $orderByMode, $limit, $rnd = false){
			if(self::connect())
			{
				$sql = "";
				for ($i=0; $i< count($arrayTables); $i++){
					$sql = $sql."(SELECT $cols FROM ".$arrayTables[$i].($where!=false?" WHERE $where":" ").($orderBy!=false?" ORDER BY $orderBy":" ").($orderBy!=false?$orderByMode!=false?" $orderByMode":"":"").($limit!=false?" limit $limit":"").(count($arrayTables)-1 == $i?") ":") UNION ");
				}
				if($rnd == true){
					$sql = $sql." ORDER BY rand()";
				}
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

		public static function selectUnionIn($cols, $arrayTables, $whereCol,$where , $orderBy, $orderByMode, $limit, $rnd = false){
			if(self::connect())
			{
				$sql = "";
				for ($i=0; $i< count($arrayTables); $i+=2){
					$sql = $sql."(SELECT $cols FROM ".$arrayTables[$i].($whereCol!=false?" WHERE $whereCol in (".self::selectColGenerate($arrayTables[$i+1],"`ad_id`" ,"$where").")":" ").($limit!=false?" limit $limit":"").(count($arrayTables)-2 == $i?") ":") UNION ");
				}
				if($rnd == true){
					$sql = $sql." ORDER BY rand()";
				}
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

		public static function excuteQuery($query){
			if(self::connect())
			{
				$sql = $query;

				// $myfile = fopen("excuteQuery.txt", "w") or die("Unable to open file!");
				//    fwrite($myfile, $sql);
				//   fclose($myfile);

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
            		
				//echo $sql;
                //$stmt = self::$db->prepare($sql);
                //$stmt->execute();
				//$stmt->bindColumn(2, $image, PDO::PARAM_LOB, null, PDO::SQLSRV_ENCODING_BINARY);  
				
				//$stmt->fetch(PDO::FETCH_BOUND);  
				//echo $image;
				//$stmt = null;
            }

        }	
		
		
}


?>