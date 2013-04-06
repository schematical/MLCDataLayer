<?php
/*
 * Works as the base class for our data_layer
 */
abstract class BaseEntity {
	protected static $blnUseCache = false;
	protected static $arrCachedData = array();
	
	protected $id = null;
	protected $table = null;//str
	protected $pKey = null;//str
	protected $arrDBFields = array();//array
	protected $modFields = null;//array
	protected $loaded = null;//bool
	protected $modified = null;//bool
	protected $strDBConn = null;
	
	public static function UseCache(){
		self::$blnUseCache = true;
	}
	protected static function LoadFromCache($strClassName, $intId){
		if(!self::$blnUseCache){
			return null;
		}
		if(!array_key_exists($strClassName, self::$arrCachedData)){
			return null;
		}else{
			if(array_key_exists($intId, self::$arrCachedData)){
				return self::$arrCachedData[$strClassName][$intId];
			}else{
				return null;
			}
		}
	}
	protected static function LoadAllFromCache(){
		if(!self::$blnUseCache){
			return null;
		}
		return self::$arrCachedData;
	}
	
	public function __construct(){		
		$this->loaded = false;
	}
	public function getId(){
		return $this->id;
	}
	public function setId($tId){
		$this->id = $tId;
	}
	
	public function setTable($nTable){
		self::$table = $nTable;
	}
	public function getTable(){
		//eval("\$table = " . get_class($this) . "::\$table;");
		return $this->table;
	}
	public function setPKey($nPKey){
		self::$pKey = $nPKey;
	}
	public function getPKey(){
		//eval("\$pKey = " . get_class($this) . "::\$pKey;");
		return $this->pKey;
	}
	public function isLoaded(){
		return $this->loaded;
	}
	public function isModified(){
		return $this->modified;
	}
	public function reload(){
		$id  = $this->id;
		$pKey = $this->getPKey();
		$table = $this->getTable();
		$sql = "SELECT * FROM " . $table . " WHERE " . $pKey . " = '" . $id . "';";
		$result = MLCDBDriver::Query($sql,$this->strDBConn);
		$arrFields = mysql_fetch_assoc($result);
		if($arrFields){
			
			
			$this->arrDBFields = $arrFields;
			$this->loaded = 1;
			
			if(sizeof($this->modFields) > 0){
				foreach($this->modFields as $key => $value){
					$this->modFields[$key] = false;
				}
			}
		}	
	}
	public function materilize($data){
		if(isset($data) && (sizeof($data) > 1)){
			$this->arrDBFields = $data;
			$this->loaded = true;
			
			$this->setId($this->getField($this->getPKey()));
		}
		if(self::$blnUseCache){
			if(!array_key_exists(get_class($this), self::$arrCachedData)){
				self::$arrCachedData[get_class($this)] = array();
			}
			self::$arrCachedData[get_class($this)][$this->getId()] = $this;
		}
	}
	public function load(){
		$this->reload();
		$this->loaded = 1;
	}
	public function forceLoaded(){
	 	$this->loaded = 1;
	}
	public function getField($field){
		if($this->loaded == 0){
			$this->load();
		}
		if(array_key_exists($field, $this->arrDBFields)){
			return $this->arrDBFields[$field];
		}else{
			throw new Exception("Field $field does not exist");
		}
	}
	public function getAllFields(){
		if($this->loaded == 0){
			$this->load();
		}
		return $this->arrDBFields;
	}
	public function setField($field, $value){
		if($this->loaded == 0){
			if(isset($this->id)){
				$this->load();
			}
		}
		$this->arrDBFields[$field] = $value;
		$this->modified = 1;
		$this->modFields[$field] = true;
		
	}
	public function addNewField($field, $value){
		if(!isset($this->arrDBFields)){
			$this->arrDBFields = array();
		}
		if(!array_key_exists($field, $this->arrDBFields)){
			$tArray = array($field => $value);
			$this->arrDBFields = array_merge($this->arrDBFields, $tArray);
			
		}else{
			throw new Exception("Field $field Already Exists");
		}
	
	}
	public function markDeleted(){
		$id = $this->id;
		//eval("\$table = " . get_class($this) . "::\$table;");
		//eval("\$pKey = " . get_class($this) . "::\$pKey;");
        $pKey = $this->getPKey();
		$table = $this->getTable();
		if($id){
			MLCDBDriver::delete($table, array($pKey => $id), $this->strDBConn);
		}
	}
	public function save(){
		
		$id = $this->id;
		//eval("\$table = " . get_class($this) . "::\$table;");
		//eval("\$pKey = " . get_class($this) . "::\$pKey;");
        $pKey = $this->getPKey();
		$table = $this->getTable();
		if(!$id){
			$this->loaded = 0;
		}
		if($this->loaded == 0){
			$nextKey = $this->getNextId($table, $pKey, $this->strDBConn);
			$this->setField($pKey, $nextKey);
			$this->id = $nextKey;
		 	MLCDBDriver::Insert($table, $this->arrDBFields, $this->strDBConn);
			$this->loaded = 1;
		}else{
			$updateFields = array();
			MLCDBDriver::Update($table, $this->arrDBFields, array($pKey => $id), $this->strDBConn);
		}
        
	}
	public function equals($nObj){
		$bol1 = $nObj->getId() == $this->getId();
		$bol2 = $nObj->getTable() == $this->getTable();
		$bol3 = ($this->isModified() == false);
		$bol4 = ($nObj->isModified() == false);
		if($bol1 && $bol2 && $bol3 && $bol4){
		 	return true;
		}else{
			return false;
		}
	}
	public static function getNextId($table, $pKey, $strDBConn = null){
		$sql = "SELECT MAX(" . $pKey . ") + 1 as 'id' FROM " . $table . " LIMIT 1;";
        
		$result = MLCDBDriver::Query($sql, $strDBConn);
		if($result) {
			while($row = mysql_fetch_assoc($result)){
				$id = $row['id'];
                if(is_null($id)){
                    return 1;
                }else{
                    return $id;
                }
			}
		}
		
	}
	protected static function _LoadByField($strTable, $strField, $mixValue, $strCompairison = '='){
		if(is_numeric($mixValue)){
			$strValue = $mixValue;
		}else{
			$strValue = sprintf('"%s"', $mixValue);
		} 
		$strExtra = sprintf(' WHERE %s %s %s', $strField, $strCompairison, $strValue);
		
		$sql = sprintf("SELECT * FROM %s %s;", $strTable, $strExtra);
		//die($sql);
		$result = MLCDBDriver::query($sql);
		$coll = new BaseEntityCollection();
		while($data = mysql_fetch_assoc($result)){
			$strClassName = $strTable;
			$tObj = new $strClassName();
			$tObj->materilize($data);
			$coll->addItem($tObj);
		}
		$arrResults = $coll->getCollection();
		if(count($arrResults) == 0){
			return null;
		}elseif(count($arrResults) == 1){
			return $arrResults[0];
		}else{
			return $arrResults;
		}
		return ;
	}
	
	
		
	///new functions created on 7/19/08
	
    public function __get($strName){
        return $this->getField($strName);
    }
    public function __set($strName, $value){
        $this->setField($strName, $value);
    }
	public function __GetDBFields(){
		return $this->arrDBFields;
	}
}
?>