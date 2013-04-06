<?php
class MLCDBDriver{
    public static $intActiveConnection = null;
    protected static $arrInitilizedDatabases = array();
    
	public static function Init(){
		$intDBIndex = 0;
		$strDBConnName = 'DB_' . $intDBIndex;
		$blnContinue = (defined($strDBConnName));
		
		while($blnContinue){
			
			
			
			self::ParseDBConnection($strDBConnName);
			$intDBIndex += 1;
			$strDBConnName = 'DB_' . $intDBIndex;
			$blnContinue = (defined($strDBConnName));
		}
	}
	public static function ParseDBConnection($strDBConnName){
		$strDBData = constant($strDBConnName);
			
		$arrDBData = unserialize($strDBData);
		self::$arrInitilizedDatabases[$strDBConnName] = new MySqlDataConnection(
			$arrDBData['host'], 
			$arrDBData['db_name'],
			$arrDBData['user'],
			$arrDBData['pass']
		);
		
	}
    public static function AddDataConnection($objDataConnection){
    	throw new Exception();
        $intConnectionNumber = count(MLCDBDriver::$arrInitilizedDatabases);
        $objDataConnection->SetConnectionNumber($intConnectionNumber);
        MLCDBDriver::$arrInitilizedDatabases[$objDataConnection->ConnectionNumber] = $objDataConnection;
    	
    }
    
	public static function Query($strSql, $mixDBConnection = null){
		//die($strSql);
		if(!is_null($mixDBConnection)){
			if(is_string($mixDBConnection)){
				//die(print_r(self::$arrInitilizedDatabases));
				$objDBConnection = self::$arrInitilizedDatabases[$mixDBConnection];
			}else{
				$objDBConnection = $mixDBConnection;
			}
			if(
				(is_object($objDBConnection)) &&
				($objDBConnection instanceof DataConnectionBase)
			){
				$objDBConnection->Connect();
			}else{
				throw new Exception("Invalid DB Connection passed in");
			}
			
		}		
		
		//error_log($strSql);
        $result = mysql_query($strSql);

		if($result) {
    		return $result;
		}else{
		 	throw new Exception("MySQL Query Failed\n\tError:" . mysql_error() . "\n\tQuery:" . $strSql);
		}
		
	}
	
	
	public static function Insert($table, $fieldValues, $strDBConnName = null){
		$fields = array_keys($fieldValues);
		$values = array_values($fieldValues);
		
		$escVals = array();
		foreach($values as $val){
			if(is_null($val)){
				$val = 'NULL';
			}elseif(!is_numeric($val)){
				$val = "'" . mysql_real_escape_string($val) .  "'";
			}
			$escVals[] = $val;
		}
		
		$sql = " INSERT INTO " . $table . " (`";
		$sql .= join('`, `', $fields);
		$sql .= "`) VALUES (";
		$sql .= join(', ', $escVals);
		$sql .= ");";
		
		$result = MLCDBDriver::Query($sql, $strDBConnName = null);
		return $result;
	}
	
	public static function Update($table, $fieldValues, $conditions, $strDBConnName = null){
		$updates = array();
		foreach($fieldValues as $field => $val){
			if(is_null($val)){
				$val = 'NULL';
			}elseif(!is_numeric($val)){			
				$val = "'" . mysql_real_escape_string($val) . "'";
			}
			$updates[] = "`" . $field . "` = " . $val;
		}
		$where = array();
		foreach($conditions as $field => $val){
			if(!is_numeric($val)){
				$val = "'" . mysql_real_escape_string($val) . "'";
			}
			$where[] = "`". $field . "` = " . $val;
		}
		
		$sql = " UPDATE " . $table . " SET ";
		$sql .= join(', ', $updates);
		$sql .= " WHERE ";
		$sql .= join('AND ', $where);
		$sql .= ";";
       
		$result = MLCDBDriver::Query($sql, $strDBConnName);
		return $result;
	}
	public static function Delete($table, $conditions, $strDBConnName = null){
		$where = array();
		foreach($conditions as $field => $val){
			if(!is_numeric($val)){
				$val = "'" . mysql_real_escape_string($val) . "'";
			}
			$where[] = $field . " = " . $val;
		}
		
		$sql = " DELETE FROM " . $table;
		$sql .= " WHERE ";
		$sql .= join('AND ', $where);
		$sql .= ";";
		$result = MLCDBDriver::Query($sql, $strDBConnName);
		
		return $result;
	}
	public static function GetDB($strDBName){
		if(array_key_exists($strDBName, self::$arrInitilizedDatabases)){
			return self::$arrInitilizedDatabases[$strDBName];
		}
		return null;
	}

}

?>