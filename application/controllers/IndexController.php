<?php

class IndexController extends Zend_Controller_Action
{

	
	public function init() {
		
	}
	
	public function indexAction()
	{

		
	}
	
	public function testAction() {
				
			$this->_helper->layout()->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);

			if ($this->_request->isGet()) {
				$userModel = new Application_Model_User();
				$return = $userModel::getAllUsers();
				echo Zend_Json::encode(array('allUsers' => $return));
			}
	}


}

