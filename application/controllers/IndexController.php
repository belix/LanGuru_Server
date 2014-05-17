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

			$db = new Application_Model_DbTable_User();
		
		$select = $db->getAdapter()->select()->from(array(
			'user' => 'user'
		))
		->where('id=?', '13')
		;
		
		$result = $select->query()->fetchAll();
		
		Zend_Debug::dump($result[0]);
	}


}

