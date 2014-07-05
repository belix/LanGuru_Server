<?php

class Application_Model_User {
	
	public static function registerUser($userinfo) {
	
		$db = new Application_Model_DbTable_User();
		
		$row = $db->createRow();
		
		$row->fbid = $userinfo['user']['fbid'];
		$row->devicetoken = $userinfo['user']['devicetoken'];
		$row->username = $userinfo['user']['username'];
		$row->password = $userinfo['user']['password'];
		$row->email = $userinfo['user']['email'];
		$row->nativelang = $userinfo['user']['nativelang'];
		$row->foreignlang = $userinfo['user']['foreignlang'];

		if (!$row->save())
			$error ++;
		
		$user_id = $row->id;
		
		$userdetails['user'] = array('id' => $user_id, 'fbid' => $userinfo['user']['fbid'], 'username' => $userinfo['user']['username'], 'nativelang' => $userinfo['user']['nativelang'], 'foreignlang' => $userinfo['user']['foreignlang'], 'ranking' => 1000);
		
		return $error ? false : $userdetails; 
		
	}
	
	public static function savePictures($userData) {
		$db = new Application_Model_DbTable_User();
		
		$data = array(
			'coverpic' => $userData['coverpic'],
			'profilepic' => $userData['profilepic']
		);
				
		$where = $db->getAdapter()->quoteInto('id = ?', $userData['userid']);
		 
		if(!$db->update($data, $where))
			$error++;					
		
		return $error ? "dberror-savepictures-error" : "success-savepictures";
	}
	
	public static function getPictures($userData) {
		$db = new Application_Model_DbTable_User();
		
		$select = $db->getAdapter()->select()->from(array(
			'user' => 'user'
		), array('coverpic'))
		->where('id=?', $userData['userid'])
		;
		
		$result = $select->query()->fetchAll();
		
		return $result ? Zend_Json::encode($result[0]) : "dberror-getPictures";
		
		
	}

		
	public static function loginUser($logindata) {
				
		$db = new Application_Model_DbTable_User();
		
		$select = $db->getAdapter()->select()->from(array(
			'user' => 'user'
		), array('count' => 'count(*)'))
		->where('username=?', $logindata['username'])
		->where('password=?', $logindata['password'])
		;
		
		$result = $select->query()->fetchAll();
		
		if ($result[0]['count']) {
			$select = $db->getAdapter()->select()->from(array(
				'user' => 'user'
			), array('*'))
			->where('username=?', $logindata['username'])
			->where('password=?', $logindata['password'])
			;
			
			$info = $select->query()->fetchAll();
			
			foreach ($info as $key => $value) {
				$userData["user"] = $value;
			}
			
			return $userData;
		} else {
			$userData = 'fail';
			
			return $userData;
		}
		
	}

	public static function loginFBUser($fbID) {
				
		$db = new Application_Model_DbTable_User();
		
		$select = $db->getAdapter()->select()->from(array(
			'user' => 'user'
		), array('count' => 'count(*)'))
		->where('fbid=?', $fbID)
		;
		
		$result = $select->query()->fetchAll();
		
		if ($result[0]['count']) {
			$select = $db->getAdapter()->select()->from(array(
				'user' => 'user'
			), array('*'))
			->where('fbid=?', $fbID)
			;
			
			$info = $select->query()->fetchAll();
			
			foreach ($info as $key => $value) {
				$userData["user"] = $value;
			}
			
			return $userData;
		} else {
			$userData = 'fail';
			
			return $userData;
		}
		
	}
	
	public static function checkUsername($username) {
		$db = new Application_Model_DbTable_User();
		
		$select = $db->getAdapter()->select()->from(array(
		'user' => 'user'
		))
		->where('username=?',$username['user']['username'])
		;
									
		$error = $select->query()->fetchAll();
		
		
		return $error ? false : true;
	}
	
	public static function checkEmail($email) {
		$db = new Application_Model_DbTable_User();
		
		$select = $db->getAdapter()->select()->from(array(
		'user' => 'user'
		))
		->where('email=?',$email['user']['email'])
		;
									
		$error = $select->query()->fetchAll();
		
		
		return $error ? false : true;
	}
	
	public static function getRankingForUsers($username) {
		$db = new Application_Model_DbTable_User();
		$select = $db->getAdapter()->select()->from(array(
		'user' => 'user'), array('fbid', 'ranking'))
		->where('fbid IN(?)', $username['fbids'])
		;
									
		$result = $select->query()->fetchAll();
		
		return $result ? Zend_Json::encode(array('users' => $result)) : "dberror-getRankingForUsers";
	}
	
	// function to switch the native and foreign lang in the app
	
	public static function changeLanguage($userdata) {
		$db = new Application_Model_DbTable_User();
		
		$data = array(
			'nativelang' => $userdata['nativelang'],
		    'foreignlang' => $userdata['foreignlang']
		);
				
		$where = $db->getAdapter()->quoteInto('username = ?', $userdata['username']);
		 
		if(!$db->update($data, $where))
			$error++;					
		
		return $error ? "dberror-changelanguage-error" : "success-changelanguage";
		
		
	}
	
	public static function getFriendInfos($array) {
		$db = new Application_Model_DbTable_User();
		
		$select = $db->getAdapter()->select()->from(array(
			'user' => 'user',
		), array('userid' => 'id', 'username', 'fbid', 'ranking', 'nativelang', 'foreignlang'))
		->where('username = ?', $array['username'])
		;
									
		$result = $select->query()->fetchAll();
		
		return $result ? Zend_Json::encode(array('userdetails' => $result)) : "dberror-could-not-retrieve-userdetails";
				
	}
	
	public static function addUser() {
		$db = new Application_Model_DbTable_User();
		
		$row = $db->createRow();
		
		$row->username = 'harikiri';
		
		if (!$row->save())
			$error ++;
	}

	public static function getOverallRanking($data) {
		$db = new Application_Model_DbTable_User();
		
			// get top N users
			$select = $db->getAdapter()->select()->from(array(
				'user' => 'user',
			), array('id', 'username', 'ranking', 'profilepic'))
			->order('ranking DESC')
			->limit(3,0)
			;
									
			$result = $select->query()->fetchAll();
			
			// get "urself"
			$select2 = $db->getAdapter()->select()->from(array(
				'user' => 'user',
			), array('id', 'username', 'ranking', 'profilepic'))
			->where('id=?', $data['id'])
			;
									
			$result2 = $select2->query()->fetchAll();
			$userRanking = $result2[0]['ranking'];
			
			// get the next one below "urself"
			$select3 = $db->getAdapter()->select()->from(array(
				'user' => 'user',
			), array('id', 'username', 'ranking', 'profilepic'))
			->where('ranking=(SELECT MAX(ranking) FROM user WHERE ranking < ?)', $userRanking)
			->limit(1,0)
			;
			
			$result3 = $select3->query()->fetchAll();
			
			// get the next one above "urself"
			$select4 = $db->getAdapter()->select()->from(array(
				'user' => 'user',
			), array('id', 'username', 'ranking', 'profilepic'))
			->where('ranking=(SELECT MAX(ranking) FROM user WHERE ranking > ?)', $userRanking)
			->limit(1,0)
			;
			
			$result4 = $select4->query()->fetchAll();
			$combinedResult = array();
			$combinedResult = array_merge($result, $result4, $result2, $result3 );
		
		return $combinedResult ? Zend_Json::encode(array('ranking' => $combinedResult)) : "dberror-could-not-retrieve-overall-ranking";
	}
}

?>