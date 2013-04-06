<?php
define('__MJAX_BS_MANAGER__', dirname(__FILE__));
define('__MJAX_BS_MANAGER_CORE__', __MJAX_BS_MANAGER__ . '/_core');
define('__MJAX_BS_MANAGER_CORE_CTL__', __MJAX_BS_MANAGER_CORE__ . '/ctl');
define('__MJAX_BS_MANAGER_CORE_MODEL__', __MJAX_BS_MANAGER_CORE__ . '/model');
define('__MJAX_BS_MANAGER_CORE_VIEW__', __MJAX_BS_MANAGER_CORE__ . '/view');
MLCApplicationBase::$arrClassFiles['MJaxBSControlBase'] = __MJAX_BS_MANAGER_CORE__ . '/MJaxBSControlBase.class.php';
MLCApplicationBase::$arrClassFiles['MJaxBSFormBase'] = __MJAX_BS_MANAGER_CORE__ . '/MJaxBSFormBase.class.php';
MLCApplicationBase::$arrClassFiles['MJaxBSTextBox'] = __MJAX_BS_MANAGER_CORE__ . '/MJaxBSTextBox.class.php';

//require_once(__MJAX_BS_MANAGER_CORE__ . '/_enum.inc.php');

MJaxControlBase::AddExtension(new MJaxBSControlBase());
MJaxFormBase::AddExtension(new MJaxBSFormBase());
//MJaxTextBox::AddExtension(new MJaxBSTextBox());
