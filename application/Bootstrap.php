<?php

include 'simplepush.php';

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
	
	// load db-config files
	protected function _initDefaultDb()
	{
		// Hosteurope Chaoshennen
		$config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/db-hosteurope.ini', 'development');
		$db = Zend_Db::factory($config->db->adapter, $config->db->params);
		Zend_Db_Table::setDefaultAdapter($db);
		Zend_Registry::set('db_default', $db);
		
	}

	protected function _initViewHelpers()
	{
		defined('APPLICATION_PATH')
			or define('APPLICATION_PATH' , dirname(__FILE__));
			
		defined('APPLICATION_ENV')
			or define('APPLICATION_ENV' , 'development');
			
		$frontController = Zend_Controller_Front::getInstance();
		
		$frontController->setControllerDirectory(APPLICATION_PATH . '/controllers');
		
		$frontController->setParam('env', APPLICATION_ENV);
		
		Zend_Layout::startMvc(APPLICATION_PATH . '/layouts/scripts');
		
		$view = Zend_Layout::getMvcInstance()->getView();
		$view->doctype('HTML5');
		
		/*  ==========================
		 *  Styles im <head>
		 *  ==========================
		 */
		 
		$view->headLink()
			/* CSS für Drag & Drop + Resize */
			->appendStylesheet('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/base/jquery-ui.css')
			/* Webfont */
			#->appendStylesheet('http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,400')
			->appendStylesheet('/css/main.css')
			->appendStylesheet('http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,400')
			
		;
		
		/*  ==========================
		 *  Scripte im <head>
		 *  ==========================
		 */
		
		$view->headScript()->setAllowArbitraryAttributes(true)
        #->appendFile('http://js.photodil.de/lmcbutton.js','text/javascript')
		;
		
		/*  ==========================
		 *  Scripte vor dem </body> tag 
		 *  ==========================
		 */
		$view -> inlineScript() -> setAllowArbitraryAttributes(true);
		
		$view->inlineScript()
			->appendFile('//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js','text/javascript')
			->appendFile('https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js', 'text/javascript')
			->appendFile('/js/scripts.js', 'text/javascript')
			
			
		;
		        
        #$view->addHelperPath('Noumenal/View/Helper', 'Noumenal_View_Helper');
		
		unset($frontController);
	}
	
	public function _initPlugins()
 	{		
		$this->bootstrap('frontController');
		#$acl = new Application_Plugin_Auth_Acl();
		#$fronController = $this->getResource('frontController');
		#$fronController->registerPlugin(new Application_Plugin_Auth_CheckAcl($acl));
		#$fronController->registerPlugin(new Application_Plugin_LoginBox());
	}

	
	protected function _initActionHelpers()
	{
		#Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH .'/controllers/helpers/');
	}
	
	public function _initSessions()
	{
    	$this->bootstrap('session');
	}
	
}