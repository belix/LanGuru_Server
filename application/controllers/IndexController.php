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
				'user' => 'user',
			), array('id', 'username', 'ranking'))
			->order('ranking DESC')
			->limit(3,0)
			;
									
			$result = $select->query()->fetchAll();
			
			$select2 = $db->getAdapter()->select()->from(array(
				'user' => 'user',
			), array('id', 'username', 'ranking'))
			->where('username=?', 'Fabian Eppinger')
			;
									
			$result2 = $select2->query()->fetchAll();
			$userRanking = $result2[0]['ranking'];
			
			$select3 = $db->getAdapter()->select()->from(array(
				'user' => 'user',
			), array('id', 'username', 'ranking'))
			->where('ranking=(SELECT MAX(ranking) FROM user WHERE ranking < ?)', $userRanking)
			->limit(1,0)
			;
			
			$result3 = $select3->query()->fetchAll();
			
			
			$select4 = $db->getAdapter()->select()->from(array(
				'user' => 'user',
			), array('id', 'username', 'ranking'))
			->where('ranking=(SELECT MAX(ranking) FROM user WHERE ranking > ?)', $userRanking)
			->limit(1,0)
			;
			
			$result4 = $select4->query()->fetchAll();
			$combinedResult = array();
			$combinedResult = array_merge($result, $result4, $result2, $result3 );
			
			Zend_Debug::dump($combinedResult);
	}


}
