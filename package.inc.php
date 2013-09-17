<?php
if(!defined('__MLC_DATALAYER_V__')){
    define('__MLC_DATALAYER_V__', '2.0');
}
define('__MLC_DATALAYER__', dirname(__FILE__) . '/' . __MLC_DATALAYER_V__);
define('__MLC_DATALAYER_CORE__', __MLC_DATALAYER__ . '/_core');
define('__MLC_DATALAYER_CTL__', __MLC_DATALAYER_CORE__ . '/ctl');
define('__MLC_DATALAYER_VIEW__', __MLC_DATALAYER_CORE__ . '/view');
define('DB_PREFIX','');
//MLCApplicationBase::$arrClassFiles['MLCDBDriver'] = __MLC_DATALAYER__ . '/MLCDBDriver.class.php';
MLCApplicationBase::$arrClassFiles['MLCBaseEntity'] = __MLC_DATALAYER_CORE__ . '/MLCBaseEntity.class.php';
MLCApplicationBase::$arrClassFiles['MLCBaseEntityCollection'] = __MLC_DATALAYER_CORE__ . '/MLCBaseEntityCollection.class.php';
MLCApplicationBase::$arrClassFiles['MLCDataConnectionBase'] = __MLC_DATALAYER_CORE__ . '/MLCDataConnectionBase.class.php';
MLCApplicationBase::$arrClassFiles['MLCDBDriver'] = __MLC_DATALAYER_CORE__ . '/MLCDBDriver.class.php';
MLCApplicationBase::$arrClassFiles['MLCDateTime'] = __MLC_DATALAYER_CORE__ . '/MLCDateTime.class.php';      
MLCApplicationBase::$arrClassFiles['MySqlDataConnection'] = __MLC_DATALAYER_CORE__ . '/MySqlDataConnection.class.php';
MLCApplicationBase::$arrClassFiles['MLCFieldCondition'] = __MLC_DATALAYER_CORE__ . '/MLCFieldCondition.class.php';
MLCDBDriver::Init();

//CTL STUFF
require_once(__MLC_DATALAYER_CORE__ . '/ctl/_events.inc.php');
MLCApplicationBase::$arrClassFiles['MJaxAdvSearchPanel'] = __MLC_DATALAYER_CTL__ . '/MJaxAdvSearchPanel.class.php';


if(__MLC_DATALAYER_V__ == '2.0'){
    require_once(__MLC_DATALAYER_CORE__ . '/_hack.inc.php');
}
