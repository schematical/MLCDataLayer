<?php
abstract class MLCDateTime{

	public static function Now($strOffset = null){
		
		$strDate = date("Y-m-d H:i:s");
		if(!is_null($strOffset)){
			$intTime = strtotime($strOffset . ' ' . $strOffset);
			$strDate = date("Y-m-d H:i:s", $intTime);
		}
		return $strDate;
	}
	
	public static function Q(QDateTime $objQDateTime){	
		if ($objQDateTime->IsTimeNull()){
			//error_log("NotNUll: " . sprintf("'%s'", $objQDateTime->__toString('YYYY-MM-DD')));
			return $objQDateTime->__toString('YYYY-MM-DD');
		}else{
			//error_log("IS NULL: " . sprintf("'%s'", $objQDateTime->__toString(QDateTime::FormatIso)));
			return  $objQDateTime->__toString(QDateTime::FormatIso);
		}
	
	}
	public static function IsGreaterThan($mixDate1, $mixDate2 = null){
		if(is_string($mixDate1)){
			$intDate1 = strtotime($mixDate1);
		}else{
			$intDate1 = $mixDate1;
		}
		if(is_string($mixDate2)){
			$intDate2 = strtotime($mixDate2);
		}elseif(is_null($mixDate2)){
			$intDate2 = time();//NOW
		}else{
			$intDate2 = $mixDate2;
		}
		
		return ($intDate1 > $intDate2);
		
	}
	public static function IsLessThan($mixDate1, $mixDate2 = null){
		return !self::IsGreaterThan($mixDate1, $mixDate2);
	}
	public static function Parse($mixDate){
		$intTime = null;
		if(is_string($mixDate)){
			$intTime = strtotime($mixDate);
		}elseif(is_int($mixDate)){
			$intTime = $mixDate;
		}else{
			throw new Exception(__FUNCTION__ . ':: must be either an int or a date as a string');
		}
		return date("Y-m-d H:i:s", $intTime);
	}
}