<?php
class MJaxBSTextBox extends MJaxExtensionBase{
	
	public function Render($blnPrint = true){
		
		return MJaxControlBase::$arrExtensions['MJaxBSControlBase']->Render($blnPrint);
	}
	/////////////////////////
    // Public Properties: GET
    /////////////////////////
    public function __get($strName) {
    
        switch ($strName) {
            //case "InputPrepend": return $this->strInputPrepend;
			//case "InputAppend": return $this->strInputAppend;
            default:
               throw new MJaxExtensionMissingPropertyException("No property with name '" . $strName . "'");
        }
    }

    /////////////////////////
    // Public Properties: SET
    /////////////////////////
    public function __set($strName, $mixValue) {
    	
        switch ($strName) {
        	
            //case "InputPrepend":
                //return ($this->strInputPrepend = $mixValue);
            default:
               throw new MJaxExtensionMissingPropertyException("No property '" . $strName . "'");
        }
    }
	
}
