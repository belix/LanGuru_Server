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
			
			$db = new Application_Model_DbTable_MatchmakingFriends();
		$dbUser = new Application_Model_DbTable_User();
		
		
		// allowed to challenge friend (validation success)
		$row = $db->createRow();
					
		$row->challengerId = 37;
		$row->accepterId = 40;
		$row->status = 0;

		if (!$row->save())
			$error ++;
		
		// challenge initiated, send push to accepter
		
		// get accepterDeviceID
		$select = $dbUser->getAdapter()->select()->from(array(
			'user' => 'user'
		), array('devicetoken'))
		->where('id=?', 40)
		
		;
		
		$accepterDeviceToken = $select->query()->fetchAll();
		//sendPush($accepterDeviceToken[0], "blablabla hat dich herausgefordert! Bock?");
		
			Zend_Debug::dump($accepterDeviceToken);
			//sendPush('4277a42fbe0852cb521631a7da1220758bbccbbb0e0bd10a6f391d13a1267c74', 'FRISS POWPEL JUNGE');
			//$data['id'] = 37; 
			//Application_Model_User::getOverallRanking($data);
			
			/*$db = new Application_Model_DbTable_User();
		
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
			
			Zend_Debug::dump($combinedResult);*/
	}


}
