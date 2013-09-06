<?php
define('__MLC_DATALAYER__', dirname(__FILE__));
define('__MLC_DATALAYER_CORE__', __MLC_DATALAYER__ . '/_core');
define('__MLC_DATALAYER_CG__', __MLC_DATALAYER__ . '/_codegen');
define('DB_PREFIX','');
//MLCApplicationBase::$arrClassFiles['MLCDBDriver'] = __MLC_DATALAYER__ . '/MLCDBDriver.class.php';
MLCApplicationBase::$arrClassFiles['BaseEntity'] = __MLC_DATALAYER_CORE__ . '/BaseEntity.class.php';
MLCApplicationBase::$arrClassFiles['BaseEntityCollection'] = __MLC_DATALAYER_CORE__ . '/BaseEntityCollection.class.php';
MLCApplicationBase::$arrClassFiles['DataConnectionBase'] = __MLC_DATALAYER_CORE__ . '/DataConnectionBase.class.php';
MLCApplicationBase::$arrClassFiles['MLCDBDriver'] = __MLC_DATALAYER_CORE__ . '/MLCDBDriver.class.php';
MLCApplicationBase::$arrClassFiles['MLCDateTime'] = __MLC_DATALAYER_CORE__ . '/MLCDateTime.class.php';      
MLCApplicationBase::$arrClassFiles['MySqlDataConnection'] = __MLC_DATALAYER_CORE__ . '/MySqlDataConnection.class.php';
MLCDBDriver::Init();
require_once(__MLC_DATALAYER_CORE__ . '/ctl/_events.inc.php');
