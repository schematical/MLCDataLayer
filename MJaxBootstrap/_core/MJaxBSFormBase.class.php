<?php
class MJaxBSFormBase extends MJaxExtensionBase{
	protected static $ctlLastAlert = null;
	public function InitControl($objControl){
		$this->objControl = $objControl;
		$this->objControl->AddHeaderAsset(new MJaxJSHeaderAsset(__ASSETS_JS__ . '/_core/MJax.BS.js'));
	}
	public function GetLastAlertedControl(){
		return self::$ctlLastAlert;
	}
	public function Alert($mixObject){
		
		if($mixObject instanceof MJaxControlBase){
			self::$ctlLastAlert = $mixObject;
			$strContent =  $mixObject->Render(false);			
		}elseif(is_string($mixObject)){
			$strContent = $mixObject;
		}else{
			throw new Exception("Alert must have first parameter of type 'MJaxControlBase' or String");
		}
		$strContent = trim(str_replace('"','\\"',str_replace("\r","",str_replace("\n", "", $strContent))));
		$this->objControl->AddJSCall(
			sprintf(
				'MJax.BS.Alert("%s");',
				$strContent
			)
		);
		$this->objControl->ForceRenderFormState = true;
		$this->objControl->SkipMainWindowRender = true;	
			
    }
    public function CtlAlert($mixControl, $mixAlert, $strType = 'error'){
		if($mixControl instanceof MJaxControl){
			$strControlId = $mixControl->ControlId;
		}elseif(is_string($mixControl)){
			$strControlId = $mixControl;
		}else{
			throw new MLCWrongTypeException(__FUNCTION__, 1);
		}
		if($mixAlert instanceof MJaxControlBase){
			$strContent =  $mixAlert->Render(false);			
		}elseif(is_string($mixAlert)){
			$strContent = $mixAlert;
		}else{
			throw new Exception("Alert must have first parameter of type 'MJaxControlBase' or String");
		}
		$strContent = trim(str_replace('"','\\"',str_replace("\r","",str_replace("\n", "", $strContent))));
		$this->objControl->AddJSCall(
			sprintf(
				'MJax.BS.CtlAlert("#%s", "%s", "%s");',
				$strControlId,
				$strContent,
				$strType
			)
		);
		$this->objControl->ForceRenderFormState = true;
		$this->objControl->SkipMainWindowRender = true;		
    }
	public function ClearCtlAlerts(){
		$this->objControl->AddJSCall(			
				'MJax.BS.ClearCtlAlerts();'
		);
	}
    public function HideAlert(){
    	$this->objControl->AddJSCall("MJax.BS.HideAlert();");
		if(!is_null(self::$ctlLastAlert)){
			self::$ctlLastAlert->Remove();
			self::$ctlLastAlert = null;
		}
    	$this->objControl->ForceRenderFormState = true;
		$this->objControl->SkipMainWindowRender = true;
    }
	public function ScrollTo($mixControl){
		if($mixControl instanceof MJaxControl){
			$strControlId = $mixControl->ControlId;
		}elseif(is_string($mixControl)){
			$strControlId = $mixControl;
		}else{
			throw new MLCWrongTypeException(__FUNCTION__, 1);
		}
		$this->objControl->AddJSCall(
			sprintf(
				"MJax.BS.ScrollTo('#%s');",
				$strControlId
			)
		);
		
	}
	public function AnimateOpen($mixControl){
		if($mixControl instanceof MJaxControl){
			$strControlId = $mixControl->ControlId;
		}elseif(is_string($mixControl)){
			$strControlId = $mixControl;
		}else{
			throw new MLCWrongTypeException(__FUNCTION__, 1);
		}
		$this->objControl->AddJSCall(
			sprintf(
				"MJax.BS.AnimateOpen('#%s');",
				$strControlId
			)
		);
		$this->objControl->ForceRenderFormState = true;
		$this->objControl->SkipMainWindowRender = true;		
	}
	public function AnimateClosed($mixControl){
		if($mixControl instanceof MJaxControl){
			$strControlId = $mixControl->ControlId;
		}elseif(is_string($mixControl)){
			$strControlId = $mixControl;
		}else{
			throw new MLCWrongTypeException(__FUNCTION__, 1);
		}
		$this->objControl->AddJSCall(
			sprintf(
				"MJax.BS.AnimateClosed('#%s');",
				$strControlId
			)
		);
		$this->objControl->ForceRenderFormState = true;
		$this->objControl->SkipMainWindowRender = true;		
	}
	
	
}
