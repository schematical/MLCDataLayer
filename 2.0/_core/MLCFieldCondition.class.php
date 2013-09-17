<?php
/**
 * Class MLCFieldCondition
 * @property string Field
 * @property mixed Comparison
 * @property string Operator
 */
class MLCFieldCondition{

    protected $strField = null;
    protected $mixComparison = null;
    protected $strOperator = null;
    public function __construct($strField = null, $mixComparison = null, $strOperator = '='){

        $this->strField = $strField;
        $this->mixComparison = $mixComparison;
        $this->strOperator = $strOperator;
    }
    public function RenderSql(){
        $strComparison = $this->mixComparison;
        $strOperator = $this->strOperator;
        if(is_string($this->mixComparison)){
            $strComparison = '"' . $strComparison . '"';
        }
        switch($this->strOperator){
            case("IS NULL"):
                $strComparison = '';
            break;
            case('='):
                if(is_null($strComparison)){
                    $strOperator = 'IS';
                    $strComparison = ' NULL';
                }
            break;
            case('!='):
                if(is_null($strComparison)){
                    $strOperator = 'IS';
                    $strComparison = ' NOT NULL';
                }
           break;
        }
        $strSql = sprintf(
            ' %s %s %s',
            $this->strField,
            $strOperator,
            $strComparison
        );
        return $strSql;
    }
    public function MSerialize(){
        $mixData = array();
        $mixData['field'] = $this->strField;
        $mixData['comparison'] = $this->mixComparison;
        $mixData['operator'] = $this->strOperator;
        return $mixData;
    }
    public function MUnserialize($mixData){
        if(is_string($mixData)){
            $mixData = json_decode($mixData, true);
        }
        $this->strField = $mixData['field'];
        $this->mixComparison = $mixData['comparison'];
        $this->strOperator = $mixData['operator'];

    }
    public function Equals(MLCFieldCondition $objFieldCond){
        if(
            ($this->strField == $objFieldCond->Field) &&
            ($this->strOperator == $objFieldCond->Operator) &&
            ($this->mixComparison == $objFieldCond->Comparison)
        ){
            return true;
        }else{
            return false;
        }
    }
    /////////////////////////
    // Public Properties: GET
    /////////////////////////
    public function __get($strName)
    {
        switch ($strName) {
            case "Field":
                return $this->strField;
            case "Comparison":
                return $this->mixComparison;
            case "Operator":
                return $this->strOperator;
            default:
                //return parent::__get($strName);
                throw new Exception("Not porperty exists with name '" . $strName . "' in class " . __CLASS__);
        }
    }

    /////////////////////////
    // Public Properties: SET
    /////////////////////////
    public function __set($strName, $mixValue)
    {
        switch ($strName) {
            case "Field":
                return $this->strField = $mixValue;
            case "Comparison":
                return $this->mixComparison = $mixValue;
            case "Operator":
                return $this->strOperator = $mixValue;
            default:
                //return parent::__set($strName, $mixValue);
                throw new Exception("Not porperty exists with name '" . $strName . "' in class " . __CLASS__);
        }
    }
}