<?php
class MLCFieldCondition{
    protected $strField = null;
    protected $mixComparison = null;
    protected $strOperator = null;
    public function __construct($strField, $mixComparison, $strOperator = '='){
        $this->strField = $strField;
        $this->mixComparison = $mixComparison;
        $this->strOperator = $strOperator;
    }
    public function RenderSql(){
        $strComparison = $this->mixComparison;
        if(is_string($this->mixComparison)){
            $strComparison = '"' . $strComparison . '"';
        }
        $strSql = sprintf(
            ' %s %s %s',
            $this->strField,
            $this->strOperator,
            $strComparison
        );
        return $strSql;
    }
}