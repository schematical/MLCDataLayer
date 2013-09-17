<?php
/*
 * Manages arrCollections of data objects
 */
class MLCBaseEntityCollection implements arrayaccess, Iterator{

    protected $arrCollection = array();
    /*---------------------Adv Functionality-------------------------------*/
    protected $arrQueryHistory = array();
    protected $strEntity = null;
    protected $intLimitCount = null;
    protected $intLimitOffset = 0;

    protected $strOrderByField = null;
    protected $strOrderByDriection = 'DESC';

    protected $arrFieldConditions = array();

    protected $arrJoin = array();

    public function MSerialize(){
        $arrReturn = array();
        $arrReturn['conditions'] = array();
        foreach($this->arrFieldConditions as $objFieldCond){
            $arrReturn[] =  $objFieldCond->MSerialize();
        }
        $arrReturn['limit'] = array(
            'ct' => $this->intLimitCount,
            'offset' => $this->intLimitOffset
        );

        $mixData['order_by'] = array(
            'field' => $this->strOrderByField,
            'direction' => $this->strOrderByDriection
        );
        return $arrReturn;
    }
    public function MUnserialize($mixData){
        if(is_string($mixData)){
            $mixData = json_decode($mixData);
        }
        foreach($mixData['conditions'] as $arrCond){
            $objFieldCondition = new MLCFieldCondition();
            $objFieldCondition->MUnserialize($arrCond);
            $this->arrFieldConditions[$objFieldCondition->Field][$objFieldCondition->Comparison][$objFieldCondition->Operator] = $objFieldCondition;
        }
        $this->intLimitCount = $mixData['limit']['ct'];
        $this->intLimitCount = $mixData['limit']['offset'];

        $this->strOrderByField = $mixData['order_by']['field'];
        $this->strOrderByDriection = $mixData['order_by']['direction'];
    }
    public function AddQueryToHistory($strSql){
        $this->arrQueryHistory[] = $strSql;
    }
    public function _dumpQueryHistory(){
        foreach($this->arrQueryHistory as $strQuery){
            echo $strQuery . '<br/>';
        }
    }
    public function SetQueryEntity($strEntity){
        $this->strEntity = $strEntity;
    }
    public function AddFieldCondition($strField, $strComparison, $strOperator = '='){
        if(!array_key_exists($strField, $this->arrFieldConditions)){
            $this->arrFieldConditions[$strField] = array();
        }
        if(!array_key_exists($strOperator, $this->arrFieldConditions[$strField])){
            $this->arrFieldConditions[$strField][$strOperator] = array();
        }
        $this->arrFieldConditions[$strField][$strOperator][$strComparison] = new MLCFieldCondition(
            $strField,
            $strComparison,
            $strOperator
        );
        return $this->arrFieldConditions[$strField][$strOperator][$strComparison];
    }
    public function OrderBy($strField, $strDirection = 'DESC'){
        if(strpos($strField, '.') === false){
            $strField = constant($this->strEntity . '::TABLE_NAME') . '.' . $strField;
        }
        $this->strOrderByField = $strField;
        $this->strOrderByDriection = $strDirection;
    }
    public function RemoveFieldConditions($strField, $strCondition = null, $strComparison = null){
        if(is_null($strCondition)){
            unset($this->arrFieldConditions[$strField]);
        }elseif(is_null($strComparison)){
            unset($this->arrFieldConditions[$strField][$strCondition]);
        }else{
            unset($this->arrFieldConditions[$strField][$strCondition][$strComparison]);
        }
    }
    public function RemoveAllFieldConditions(){
        $this->arrFieldConditions = array();
    }
    public function Limit($intLimitCount, $intLimitOffset = 0){
        $this->intLimitCount = $intLimitCount;
        $this->intLimitOffset = $intLimitOffset;
    }
    public function QueryNext(){
        $this->intLimitOffset += $this->intLimitCount;
        $this->ExecuteQuery();
    }
    public function QueryPrev(){
        $this->intLimitOffset -= $this->intLimitCount;
        $this->ExecuteQuery();
    }

    protected function GenerateSQL($blnSkipLimit = false){
        $strSql = '';


        $arrAndConditions = array();
        foreach($this->arrFieldConditions as $strField => $arrFieldCond){


            foreach($arrFieldCond as $strCondition =>  $arrComparisons){
                $arrOrConditions = array();
                foreach($arrComparisons as $objFieldCondition){

                    $arrOrConditions[] = $objFieldCondition->RenderSql();
                }
                if(count($arrOrConditions) > 0){
                    $arrAndConditions[] = implode(' OR ',$arrOrConditions);
                }
            }

        }
        if(count($arrAndConditions) > 0){
            $strSql .= 'WHERE ';
            $strSql .= implode(' AND ' , $arrAndConditions);
        }
        if(!is_null($this->strOrderByField)){
            $strSql .= ' ORDER BY ' .
                $this->strOrderByField . ' ' .
                $this->strOrderByDriection;
        }
        if(!$blnSkipLimit){
            if(!is_null($this->intLimitCount)){
                $strSql .= ' LIMIT ' .
                    $this->intLimitOffset . ',' .
                    $this->intLimitCount;
            }
        }
        return $strSql;
    }
    public function ExecuteQuery(){
        //Does nothing with out a table set
        if(
            (is_null($this->strEntity)) ||
            (!class_exists($this->strEntity))
        ){
            //_dv($this->strEntity);
            throw new Exception("Cannot Exicute Query on this collection due to invalid Entity Set to query from");
        }
            $strSql = $this->GenerateSQL();
            call_user_func(
                $this->strEntity . '::Query',
                $strSql,
                $this,
                $this->arrJoin
            );


    }
    public function QueryFullCount(){
        //Does nothing with out a table set
        if(
            (is_null($this->strEntity)) ||
            (!class_exists($this->strEntity))
        ){
            //_dv($this->strEntity);
            throw new Exception("Cannot Exicute Query on this collection due to invalid Entity Set to query from");
        }
        $strSql = $this->GenerateSQL(true);
        $intCount = call_user_func(
            $this->strEntity . '::QueryCount',
            $strSql
        );

        return $intCount;
    }
    public function QueryPaginationLength(){
        $intResultCount = $this->QueryFullCount();
        //_dv($intResultCount);
        $intPageCount = ceil($intResultCount/$this->intLimitCount);
        return $intPageCount;
    }


    /*------------Base functionality------------------*/
		public function GetIDsAsArray(){
			$arrReturn = $this->GetFieldAsArray('{id}');
			return $arrReturn;
		}
        public function GetFieldAsArray($strField){
            $arrReturn = array();
            foreach($this->arrCollection as $objEntity){
                if($strField == '{id}'){
                    $arrReturn[$objEntity->getId()] = $objEntity->getId();
                }else{
                    $arrReturn[$objEntity->getId()] = $objEntity->__get($strField);
                }
            }
            return $arrReturn;
        }
		public function GetRandomized(){
			$arrColl = $this->arrCollection;
			$arrReturn = array();
			while(count($arrColl) != 0){
				$i = rand(0, count($arrColl) - 1);
				$arrReturn[] = $arrColl[$i];
				array_splice($arrColl, $i, 1);
			}
			return $arrReturn;		
		}
		public function GetRandom(){
			$arrColl = $this->arrCollection;			
			$i = rand(0, count($arrColl) - 1);
			return $arrColl[$i];
		}
		
		
		public function GetItemByIndex($index){
			return $this->arrCollection[$index];
		}
		public function AddItem($item){
			array_push($this->arrCollection,$item);
		}
		public function GetCollection(){
			return $this->arrCollection;
		}
		public function Length(){
			return count($this->arrCollection);
		}
		public function Combine($nColl){
			$coll = $nColl->getCollection();
			
			foreach($coll as $mObj){
				array_push($this->arrCollection,$mObj);
			}
			
		}
		public function Contains($nObj){
			if(isset($nObj)){
				$coll = $this->getCollection();
				$bool = false;
				foreach($coll as $mObj){
					if($nObj->equals($mObj)){
						$bool =  true;
					}
				}
				return $bool;
			}else{
				return false;
			}
		}
		public function GetIndexOf($nObj){
			if(isset($nObj)){
				$coll = $this->getCollection();
				$bool = false;
				for($i = 0; $i < sizeof($coll); $i++){
					if($nObj->equals($coll[$i])){
						$rIndex =  $i;
					}
				}
				return $rIndex;
			}else{
				return false;
			}
		}
		public function Remove($nObj){
			if((isset($nObj)) and ($this->contains($nObj))){
				$coll = $this->getCollection();
				$bool = false;
				$nColl = array();
				for ($i = 0; $i <= (count($coll) - 1); $i++) {
					$mObj = $coll[$i];
					
					if($nObj->equals($mObj)){
						//do nothing
					}else{
						array_push($nColl, $mObj);
					}
				}
				$this->arrCollection = $nColl;
				return $bool;
			}else{
				return false;
			}
		}
		public function RemoveColl($nColl){
			foreach($nColl as $nObj){
				$this->Remove($nObj);
			}
		}
        public function RemoveAll(){
            $this->arrCollection = array();
        }
		public function __construct($arrData = array()){
			$this->arrCollection = $arrData;
		}
		public function Save(){
			foreach($this->arrCollection as $entity){
				MLCDBDriver::save($entity);
			}
		}

		public function MarkAllDeleted(){
			$coll = $this->arrCollection;
			foreach($coll as $obj){
				try{
					$obj->markDeleted();
				}catch(Exception $e){
					echo "This object type does not have a markDeleted funcion";
				}
			}
		}
    //THESE are required for the arrayaccess implementation
		public function removeByIndex($intIndex){
			unset($this->arrCollection[$intIndex]);
		}
        public function offsetSet($strKey, $mixValue) {
            if (is_null($strKey)) {
                $this->arrCollection[] = $mixValue;
            } else {
                $this->arrCollection[$strKey] = $mixValue;
            }
        }
        public function offsetExists($strKey) {
            return array_key_exists($strKey, $this->arrCollection);
        }
        public function offsetUnset($strOffset) {
            unset($this->arrCollection[$strOffset]);
        }
        public function offsetGet($strOffset) {
            if(array_key_exists($strOffset, $this->arrCollection)){
                 return $this->arrCollection[$strOffset];
            }else{
                return null;
            }
        }
        public function rewind()
        {
            reset($this->arrCollection);
        }

        public function current()
        {
            $var = current($this->arrCollection);
            return $var;
        }

        public function key()
        {
            $var = key($this->arrCollection);

            return $var;
        }

        public function next()
        {
            $var = next($this->arrCollection);
            return $var;
        }

        public function valid()
        {
            $key = key($this->arrCollection);
            $var = ($key !== NULL && $key !== FALSE);
            return $var;
        }

    /////////////////////////
    // Public Properties: GET
    /////////////////////////
    public function __get($strName)
    {
        switch ($strName) {
            case "History":
                return $this->arrQueryHistory;
            case "FieldConditions":
                return $this->arrFieldConditions;
            case "LimitCount":
                return $this->intLimitCount;
            case "LimitOffset":
                return $this->intLimitOffset;
            case "OrderByField":
                return $this->strOrderByField;
            case "OrderByDirection":
                return $this->strOrderByDirection;
            default:
                //return parent::__get($strName);
                throw new Exception("Not porperty exists with name '" . $strName . "' in class " . __CLASS__);
        }
    }

			
	
}
	
?>