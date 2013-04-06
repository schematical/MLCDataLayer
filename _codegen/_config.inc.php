<?php	/*-----------------------DATALAYER STUFF----------------------------------*/
$arrXTPLData = array();
$arrXTPLData[] = array(
	'from' => __MLC_DATALAYER_CG__ . "/xtpl/data_layer/DataClass.xtpl",
	'to'=>__MODEL_APPS_DIR__ . '/' . MDEApplication::GetActiveApp()->Name . "/data_classes/*.class.php",
	'overwrite' => "false",
	'capitalize' => "true"
);
$arrXTPLData[] = array(
	'from' => __MLC_DATALAYER_CG__ . "/xtpl/data_layer/DataClassBase.xtpl",
	'to' => __MODEL_APPS_DIR__ . '/' . MDEApplication::GetActiveApp()->Name . "/data_classes/base_classes/*Base.class.php",
	'overwrite' => "true",
	'capitalize' => "true"
);
$arrXTPLData[] = array(
	'from' => __MLC_DATALAYER_CG__ ."/xtpl/data_layer/DataConn.xtpl",
	'to' => __MODEL_APPS_DIR__ . '/' . MDEApplication::GetActiveApp()->Name . "/data_classes/base_classes/Conn.inc.php",
	'overwrite' => "true",
	'capitalize' => "true",
	'conn' => 'true',
);

/*-----------------------Regular Control STUFF----------------------------------*/
$arrXTPLData[] = array(
	'from' => __MLC_DATALAYER_CG__ . "/xtpl/ctl/ControlEditPanel.xtpl",
	'to' => __MODEL_APPS_DIR__ . '/' . MDEApplication::GetActiveApp()->Name . "/ctl_classes/*EditPanel.class.php",
	'overwrite' => "false",
	'capitalize' => "true",
);
$arrXTPLData[] = array(
	'from' => __MLC_DATALAYER_CG__ . "/xtpl/ctl/ControlEditPanelBase.xtpl",
	'to' =>  __MODEL_APPS_DIR__ . '/' . MDEApplication::GetActiveApp()->Name . "/ctl_classes/base_classes/*EditPanelBase.class.php",
	'overwrite' => "true",
	'capitalize' => "true",
);
$arrXTPLData[] = array(
	'from' => __MLC_DATALAYER_CG__ . "/xtpl/ctl/ControlConn.xtpl",
	'to' => __MODEL_APPS_DIR__ . '/' . MDEApplication::GetActiveApp()->Name . "/ctl_classes/base_classes/ControlConn.inc.php",
	'overwrite' => "true",
	'capitalize' => "true",
	'conn'=>'true'
);
$arrXTPLData[] = array(
	'from' => __MLC_DATALAYER_CG__ . "/xtpl/ctl/ControlEditPanelTemplate.xtpl",
	'to' =>  __VIEW_APPS_DIR__ . '/' . MDEApplication::GetActiveApp()->Name . "/www/ctl_panels/*EditPanelBase.tpl.php",
	'overwrite' => "true",
	'capitalize' => "true",
);
/*-----------------------List Control STUFF----------------------------------*/
$arrXTPLData[] = array(
	'from' => __MLC_DATALAYER_CG__ . "/xtpl/ctl/ControlListPanel.xtpl",
	'to' => __MODEL_APPS_DIR__ . '/' . MDEApplication::GetActiveApp()->Name . "/ctl_classes/*ListPanel.class.php",
	'overwrite' => "false",
	'capitalize' => "true",
);
$arrXTPLData[] = array(
	'from' => __MLC_DATALAYER_CG__ . "/xtpl/ctl/ControlListPanelBase.xtpl",
	'to' =>  __MODEL_APPS_DIR__ . '/' . MDEApplication::GetActiveApp()->Name . "/ctl_classes/base_classes/*ListPanelBase.class.php",
	'overwrite' => "true",
	'capitalize' => "true",
);

/*-----------------------API STUFF----------------------------------*/
$arrXTPLData[] = array(
	'from' => __MLC_DATALAYER_CG__ . "/xtpl/api/ApiClass.xtpl",
	'to' => __MODEL_APPS_DIR__ . '/' . MDEApplication::GetActiveApp()->Name ."/api_classes/MLCApi*.class.php",
	'overwrite' => "false",
	'capitalize' => "true",
);
$arrXTPLData[] = array(
	'from' => __MLC_DATALAYER_CG__ . "/xtpl/api/ApiClassBase.xtpl",
	'to' => __MODEL_APPS_DIR__ . '/' . MDEApplication::GetActiveApp()->Name ."/api_classes/base_classes/MLCApi*Base.class.php",
	'overwrite' => "true",
	'capitalize' => "true",
);
$arrXTPLData[] = array(
	'from' => __MLC_DATALAYER_CG__ . "/xtpl/api/ApiObject.xtpl",
	'to' => __MODEL_APPS_DIR__ . '/' . MDEApplication::GetActiveApp()->Name ."/api_classes/MLCApi*Object.class.php",
	'overwrite' => "false",
	'capitalize' => "true"
);
$arrXTPLData[] = array(
	'from' => __MLC_DATALAYER_CG__ . "/xtpl/api/ApiObjectBase.xtpl",
	'to' => __MODEL_APPS_DIR__ . '/' . MDEApplication::GetActiveApp()->Name ."/api_classes/base_classes/MLCApi*ObjectBase.class.php",
	'overwrite' => "true",
	'capitalize' => "true"
);
$arrXTPLData[] = array(
	'from' => __MLC_DATALAYER_CG__ . "/xtpl/api/ApiHomeClass.xtpl",
	'to' => __MODEL_APPS_DIR__ . '/' . MDEApplication::GetActiveApp()->Name ."/api_classes/MLCApiHome.class.php",
	'overwrite' => "false",
	'capitalize' => "true",
	'conn' => 'true',
);
$arrXTPLData[] =  array(
	'from' => __MLC_DATALAYER_CG__ . "/xtpl/api/ApiHomeClassBase.xtpl",
	'to' => __MODEL_APPS_DIR__ . '/' . MDEApplication::GetActiveApp()->Name ."/api_classes/base_classes/MLCApiHomeBase.class.php",
	'overwrite' => "true",
	'capitalize' => "true",
	'conn' => 'true',
);
 	/*-----------------------Entity Model STUFF----------------------------------*/
$arrXTPLData[] = array(
	'from' => __MLC_DATALAYER_CG__ . "/xtpl/entity_model/MLCEntityModelClass.xtpl",
	'to' => __MODEL_APPS_DIR__ . '/' . MDEApplication::GetActiveApp()->Name ."/entity_model/MLCEntityMode*.class.php",
	'overwrite' => "false",
	'capitalize' => "true",
);
$arrXTPLData[] = array(
	'from' => __MLC_DATALAYER_CG__ . "/xtpl/entity_model/MLCEntityModelClassBase.xtpl",
	'to' => __MODEL_APPS_DIR__ . '/' . MDEApplication::GetActiveApp()->Name ."/entity_model/base_classes/MLCEntityMode*Base.class.php",
	'overwrite' => "true",
	'capitalize' => "true",
);
$arrXTPLData[] = array(
	'from' => __MLC_DATALAYER_CG__ . "/xtpl/entity_model/MLCEntityModelObject.xtpl",
	'to' => __MODEL_APPS_DIR__ . '/' . MDEApplication::GetActiveApp()->Name ."/entity_model/MLCEntityMode*Object.class.php",
	'overwrite' => "false",
	'capitalize' => "true"
);
$arrXTPLData[] = array(
	'from' => __MLC_DATALAYER_CG__ . "/xtpl/entity_model/MLCEntityModelObjectBase.xtpl",
	'to' => __MODEL_APPS_DIR__ . '/' . MDEApplication::GetActiveApp()->Name ."/entity_model/base_classes/MLCEntityMode*ObjectBase.class.php",
	'overwrite' => "true",
	'capitalize' => "true"
);
$arrXTPLData[] = array(
	'from' => __MLC_DATALAYER_CG__ . "/xtpl/entity_model/MLCEntityModelHomeClass.xtpl",
	'to' => __MODEL_APPS_DIR__ . '/' . MDEApplication::GetActiveApp()->Name ."/entity_model/MLCEntityModeHome.class.php",
	'overwrite' => "false",
	'capitalize' => "true",
	'conn' => 'true',
);
$arrXTPLData[] =  array(
	'from' => __MLC_DATALAYER_CG__ . "/xtpl/entity_model/MLCEntityModelHomeClassBase.xtpl",
	'to' => __MODEL_APPS_DIR__ . '/' . MDEApplication::GetActiveApp()->Name ."/entity_model/base_classes/MLCEntityModeHomeBase.class.php",
	'overwrite' => "true",
	'capitalize' => "true",
	'conn' => 'true',
);