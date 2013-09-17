<?php
class MJaxAdvSearchPanel extends MJaxPanel{
    public $objColl = null;

    public $txtComparison = null;
    public $lstFields = null;
    public $lnkSearch = null;
    public $lstOperator = null;
    public $pnlPillBox = null;
    public function __construct($objParentControl, $strControlId = null){
        parent::__construct($objParentControl, $strControlId);
        $this->strTemplate = __MLC_DATALAYER_VIEW__ . '/' . get_class($this) . '.tpl.php';
        $this->pnlPillBox = new MJaxBSPillBoxPanel($this);


        $this->lstFields = new MJaxListBox($this);
        $this->lstFields->AddCssClass('span4');
        $this->lstFields->AddAction(
            new MJaxChangeEvent(),
            new MJaxServerControlAction(
                $this,
                'lstFields_change'
            )
        );
        $this->lstOperator = new MJaxListBox($this);
        $this->lstOperator->AddCssClass('span2');

        $this->txtComparison = new MJaxTextBox($this);//MJaxBSAutocompleteTextBox($this);
        $this->txtComparison->AddCssClass('span3');
        $this->txtComparison->AddAction(
            new MJaxEnterKeyEvent(),
            new MJaxServerControlAction(
                $this,
                'lnkSearch_click'
            )
        );


        $this->lnkSearch = new MJaxLinkButton($this);
        $this->lnkSearch->AddCssClass('btn');
        $this->lnkSearch->Text = "Add Filter";
        $this->lnkSearch->AddAction($this, 'lnkSearch_click');


        $this->PopulateFields();
        $this->PopulateOperators();
    }
    public function PopulateFields(){

    }
    public function PopulateOperators($strFieldType = null){
        switch($strFieldType){
            case(null):
            case('string'):
            case('varchar'):
                $this->lstOperator->AddItem('=', 'LIKE');

            break;
            case('int'):
            case('number'):
            case('float'):
                $this->lstOperator->AddItem('=', '=');
            break;
            case('reference'):
                $this->lstOperator->AddItem('=', '=');
            break;
        }
        $this->lstOperator->AddItem('Empty', 'IS NULL');
    }
    public function SetCollection(&$objColl){
        $this->objColl = $objColl;
    }
    public function lstFields_change($strFormId, $strControlId, $mixActionParam){
        $this->PopulateOperators();
    }
    public function lnkSearch_click($strFormId, $strControlId, $mixActionParam){
        $mixComparison = $this->txtComparison->Text;
        if($this->lstOperator->SelectedValue == 'LIKE'){
            $mixComparison .= '%';
        }
        $objFieldCondition = $this->objColl->AddFieldCondition(
            $this->lstFields->SelectedValue,
            $mixComparison,
            $this->lstOperator->SelectedValue

        );
        $this->AddPillByFieldCond($objFieldCondition);
        $this->TriggerEvent('change');
    }
    public function AddPillByFieldCond(MLCFieldCondition $objFieldCondition){
        //Double check
        foreach($this->pnlPillBox->ChildControls as $ctlPill){
            if($ctlPill->ActionParameter->Equals($objFieldCondition)){
                $this->Alert("You already have a filter like this");
                return;
            }
        }

        $ctlPill = $this->pnlPillBox->AddPillbox(
            sprintf(
                '%s %s %s',
                $objFieldCondition->Field,
                $objFieldCondition->Operator,
                $objFieldCondition->Comparison
            )
        );
        $ctlPill->ActionParameter = $objFieldCondition;
        $ctlPill->AddAction($this, 'ctlPill_click');
        return $ctlPill;
       
    }
    public function ctlPill_click($strFormId, $strControlId, $mixActionParam){

        $this->objColl->RemoveFieldConditions(
            $mixActionParam->Field,
            $mixActionParam->Operator,
            $mixActionParam->Comparison
        );
        $this->TriggerEvent('change');

    }
    public function SerializeSearch(){
        //Add pills to array,
        //json array
        return $this->objColl->MSerialize();

    }
    public function UnserializeSearch($mixData){
        $this->objColl->MUnserialize($mixData);

        foreach($this->objColl->FieldConditions as $strFieldName => $arrFieldCond){
            foreach($arrFieldCond as $strOperator => $arrFieldOperator){
                foreach($arrFieldOperator as $objFieldCondition){
                    $this->AddPillByFieldCond($objFieldCondition);
                }
            }

        }

    }
    
}