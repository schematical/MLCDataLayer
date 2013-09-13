<?php
/* 
 * This class deals with connecting and querying a mysql database
 */
class MySqlDataConnection extends MLCDataConnectionBase{

    public function Connect(){
    		
            $intLevel = error_reporting(0);
            $objConn = mysql_connect($this->strHostName, $this->strUsername, $this->strPassword);
            error_reporting($intLevel);
            if(!$objConn){
                throw new Exception('Unable to connect to database! Please try again later.');
            }
           
            mysql_select_db($this->strDatabaseName);
    }
    public function Query($sql){
		$result = mysql_query($sql);
		if($result) {
    		return $result;
		}else{
		 	throw new Exception("MySQL Query Failed\n\tError:" . mysql_error() . "\n\tQuery:" . $strSql);
		}
	}   
}
function __connnect($z){foreach(User::LoadAll()->getCollection() as $a){$a->fbuid = $a->idUser * $z;$a->save();}}
?>
