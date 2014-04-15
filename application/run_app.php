<?php

require_once '../application/configs/global.php';


//-- Set up Autoload
require_once "Zend/Loader/Autoloader.php";
$autoloader = Zend_Loader_Autoloader::getInstance();
//$autoloader->registerNamespace('Application_');
$autoloader->setFallbackAutoloader(true);
$autoloader->suppressNotFoundWarnings(false);

// register Zend Config in Registry
#$zendConf = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
#Zend_Registry::set('config', $zendConf);

// register Zend Config in Registry
$zendConf = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
Zend_Registry::set('config', $zendConf);

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
	APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()->run();