<?php
//TZ Related info
//http://www.thisprogrammingthing.com/2013/things-i-learned-while-writing-a-timezone-aware-website/
class MLCDateTime{
    const MYSQL_FORMAT = "Y-m-d H:i:s";
    protected static $strTimeZone = "America/Los_Angeles";
    protected static $arrDateStr = array(
            'H'=>'hour',
            'h'=>'hour',
            'i'=>'minute',
            's'=>'second',
            'y'=>'year',
            'Y'=>'year',
            'm'=>'month',
            'd'=>'day'
    );
    public static function Offset($strDate, $strOffset = null){
        if(!is_null($strOffset)){
            $intTime = strtotime($strOffset . ' ' . $strDate);
            $strDate = date(self::MYSQL_FORMAT, $intTime);
        }
        return $strDate;
    }
	public static function Now($strOffset = null){
		
		$strDate = date(self::MYSQL_FORMAT);
		if(!is_null($strOffset)){
			$intTime = strtotime($strOffset . ' ' . $strDate);
			$strDate = date(self::MYSQL_FORMAT, $intTime);
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
    public static function ParseFromFormat($strFormat, $strDate, $arrDateStr = null) {
        if(is_null($arrDateStr)){
            $arrDateStr = self::$arrDateStr;
        }
        //_dv($strFormat . ' - ' . $strDate);
        $arrFormat = preg_split('//', $strFormat, -1, PREG_SPLIT_NO_EMPTY);
        $arrDate = preg_split('//', $strDate, -1, PREG_SPLIT_NO_EMPTY);

        $dt = array();
        foreach ($arrDate as $k => $v) {
            if (
                (array_key_exists($k, $arrFormat)) &&
                (array_key_exists($arrFormat[$k], $arrDateStr))
            ){
                if(!array_key_exists(self::$arrDateStr[$arrFormat[$k]], $dt)){
                    $dt[self::$arrDateStr[$arrFormat[$k]]] = '';
                }
                $dt[self::$arrDateStr[$arrFormat[$k]]] .= $v;
            }
        }
        return $dt;
    }
    public static function ConvertFromFormatToFormat($strFromFormat, $strToFormat, $strDate, $arrDateStr = null){
        if(is_null($arrDateStr)){
            $arrDateStr = self::$arrDateStr;
        }
        $arrDateData = self::ParseFromFormat($strFromFormat, $strDate, $arrDateStr);

        $arrToFormat = preg_split('//', $strToFormat, -1, PREG_SPLIT_NO_EMPTY);
        $strDateReturn = '';
        foreach ($arrToFormat as $k => $v) {
            if(array_key_exists($v, $arrDateStr)){
                if (array_key_exists($arrDateStr[$v], $arrDateData)){
                    $strDateReturn .= $arrDateData[$arrDateStr[$v]];
                }
            }else{
                $strDateReturn .= $v;
            }
        }
        return $strDateReturn;
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
    public static function GetDST($tzId, $time = null) {
        if($time == null){
            $time = gmdate('U');
        }

        $tz = new DateTimeZone($tzId);

        $transition = $tz->getTransitions($time);

        return $transition[0]['isdst'];
    }

    public static function GetOffset($tzId, $time = null) {
        if($time == null){
            $time = gmdate('U');
        }

        $tz = new DateTimeZone($tzId);

        $transition = $tz->getTransitions($time);

        return $transition[0]['offset'];
    }
    protected $objDateTime = null;
    public function __construct($mixDate){
        if(
            is_object($mixDate) &&
            $mixDate instanceof DateTime
        ){
            $intTime = $mixDate;
        }elseif(is_string($mixDate)){
            $intTime = strtotime($mixDate);
        }elseif(is_int($mixDate)){
            $intTime = $mixDate;
        }
        $this->objDateTime = new DateTime($intTime, new DateTimeZone("UTC"));

        $this->objDateTime->setTimeZone(self::$strTimeZone);
    }
    public function __toString(){//$strFormat = null){
        $strFormat = null;
        if(is_null($strFormat)){
            $strFormat = self::MYSQL_FORMAT;
        }
        return $this->objDateTime->format($strFormat);
    }
}