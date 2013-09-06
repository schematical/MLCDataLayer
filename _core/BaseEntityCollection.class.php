<?php
/*
 * Manages collections of data objects
 */
class BaseEntityCollection implements arrayaccess
	{
		
		protected $collection = array();
		public function getIDsAsArray(){
			$arrReturn = array();
			foreach($this->collection as $objEntity){
				$arrReturn[$objEntity->getId()] = $objEntity->getId();
			}
			return $arrReturn;
		}
		public function getRandomized(){
			$arrColl = $this->collection;
			$arrReturn = array();
			while(count($arrColl) != 0){
				$i = rand(0, count($arrColl) - 1);
				$arrReturn[] = $arrColl[$i];
				array_splice($arrColl, $i, 1);
			}
			return $arrReturn;		
		}
		public function getRandom(){
			$arrColl = $this->collection;			
			$i = rand(0, count($arrColl) - 1);
			return $arrColl[$i];
		}
		
		
		public function getItemByIndex($index){
			return $this->collection[$index];
		}
		public function addItem($item){
			array_push($this->collection,$item);
		}
		public function getCollection(){
			return $this->collection;
		}
		public function length(){
			return count($this->collection);
		}
		public function combine($nColl){
			$coll = $nColl->getCollection();
			
			foreach($coll as $mObj){
				array_push($this->collection,$mObj);
			}
			
		}
		public function contains($nObj){
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
		public function getIndexOf($nObj){
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
		public function remove($nObj){
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
				$this->collection = $nColl;
				return $bool;
			}else{
				return false;
			}
		}
		public function removeColl($nColl){
			foreach($nColl as $nObj){
				$this->remove($nObj);
			}
		}
		public function __construct($arrData = array()){
			$this->collection = $arrData;
		}
		public function save(){
			foreach($this->collection as $entity){
				MLCDBDriver::save($entity);
			}
		}
		public function markAllDeleted(){
			$coll = $this->collection;
			foreach($coll as $obj){
				try{
					$obj->markDeleted();
				}catch(Exception $e){
					echo "This object type does not have a markDeleted funcion";
				}
			}
		}
		public function removeByIndex($intIndex){
			unset($this->collection[$intIndex]);
		}
        public function offsetSet($strKey, $mixValue) {
            if (is_null($strKey)) {
                $this->collection[] = $mixValue;
            } else {
                $this->collection[$strKey] = $mixValue;
            }
        }
        public function offsetExists($strKey) {
            return array_key_exists($strKey, $this->collection);
        }
        public function offsetUnset($strOffset) {
            unset($this->collection[$strOffset]);
        }
        public function offsetGet($strOffset) {
            if(array_key_exists($strOffset, $this->collection)){
                 return $this->collection[$strOffset];
            }else{
                return null;
            }
        }
			
	
	}
	
?>