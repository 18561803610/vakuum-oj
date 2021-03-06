<?php
error_reporting(E_ALL|E_STRICT);
require_once('library/global.php');
require_once('library/BFL/BFL_Loader.php');

//初始化自動加載器
BFL_Loader::setBFLPath('./library/BFL/');
BFL_Loader::setControllerPath('./library/application/controller/');
BFL_Loader::setModelPath('./library/application/model/');

//初始化計時器
BFL_Timer::initialize();

//設置運行時全局變量
BFL_Register::setVar('password_encode_word',PWD_ENCWORD);
BFL_Register::setVar('db_info',getDBInfo());

//初始化參數表
$config = MDL_Config::getInstance();

//檢查地址綁定
$bind_address = $config->getVar('site_address');
if ($bind_address != '' && $bind_address != BFL_General::getServerAddress())
{
	BFL_Controller::redirect($bind_address);
}

//初始化用戶會話
BFL_ACL::getInstance()->initialize(SESSION_PREFIX,'guest');
MDL_User_Auth::getLoginedUserInformation();

//加載插件
MDL_Plugin::load_plugins(MDL_Locator::getInstance()->getFilePath('plugins'));

//初始化前端控制器
$controller = BFL_Controller::getInstance();
$controller->setCustomControllerRouter('/admin','_admin');
$controller->setNotFound('CTL_error','notFound');
$controller->dispatch();
