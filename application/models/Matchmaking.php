<?php

class Application_Model_Matchmaking {
	
	public static function findOpponent($userdata) {
		
		$db = new Application_Model_DbTable_Matchmaking();
		$dbMatch = new Application_Model_DbTable_Match();
		
		
		// check if user crashed game and game is still open
		/*
		if ($userdata['user']['crashedmatches']):
			$select = $dbMatch->getAdapter()->select()->from(array(
				'match'
			),array('id'))
			->where('match.active = ?', 1)
			->where('match.id IN ?', $userdata['user']['crashedmatches'])
			;
			
			$crashed = $select->query()->fetchAll();
		endif;
		 * */
		
		// first check if match already exists because someone else already matched you
		$select2 = $dbMatch->getAdapter()->select()->from(array(
			'match' => 'match'
		))
		->where('match.active = ?', 1)
		->where('opponent1 = ? OR opponent2 = ?', $userdata['user']['username'], $userdata['user']['username'] )
		
		;
		/*
		if ($crashed):
			$select2->where('match.id NOT IN ?', $crashed);
		endif;
		*/
		$matchAlreadyExistsForThisUser = $select2->query()->fetchAll();
					 
		if($matchAlreadyExistsForThisUser) {
			
			
			if(!file_exists('../tmp/wordsForMatchId' . $matchAlreadyExistsForThisUser[0]['id'] . '.txt')) {
			// logic for creating the list of words
			
				// first retrieve list of words depending on the gametype
				
				$listOfWords = Application_Model_Words::retrieveWordsForMultiplyChoiceGame();
					
				// TO DO: then write back the words to file, so both players get the same words
				Application_Model_Helper::createFileForMatch($matchAlreadyExistsForThisUser[0]['id'], json_encode($listOfWords), 1);


			}
			
			$words = Application_Model_Helper::readFromFile($matchAlreadyExistsForThisUser[0]['id'], 1);
			$words = json_decode($words);
			
			// retrieve the match data, so I can remove the language of the opponent user from the wordslist
			$select3 = $dbMatch->getAdapter()->select()->from(array(
			'match' => 'match'
			))
			->where('match.active = ?', 1)
			->where('opponent1 = ? OR opponent2 = ?', $userdata['user']['username'], $userdata['user']['username'] )
			;
			
			$matchdata = $select3->query()->fetchAll();
			
			// START: Retrieve coverpic from opponent
			if($matchdata[0]['opponent1'] != $userdata['user']['username']) {
				// retrieve coverpic for opponent
				$dbUser = new Application_Model_DbTable_User();
				$select5 = $dbUser->getAdapter()->select()->from(array(
				'user' => 'user'
				))
				->where('user.username = ?',$matchdata[0]['opponent1'] )
				;
			}
			
			else {
				// retrieve coverpic for opponent
				$dbUser = new Application_Model_DbTable_User();
				$select5 = $dbUser->getAdapter()->select()->from(array(
				'user' => 'user'
				))
				->where('user.username = ?',$matchdata[0]['opponent2'] )
				;
			}
			
			$coverpic = $select5->query()->fetchAll();
			
			// END: Retrieve Coverpic from opponent
			
			$matchAlreadyExistsForThisUser[0]['category'] = 0;
			$matchAlreadyExistsForThisUser[0]['coverpic'] = $coverpic[0]['coverpic'];
			$matchAlreadyExistsForThisUser[0]['profilepic'] = $coverpic[0]['profilepic'];
			
			$matchAlreadyExistsForThisUser[0]['words'] = $words;
					
			return $matchAlreadyExistsForThisUser ? Zend_Json::encode(array('match' => $matchAlreadyExistsForThisUser[0])) : "dberror1";
				
			
		}
		
		else {
			// check if opponent already exists because someone is searching for opponent
			$select = $db->getAdapter()->select()->from(array(
			'matchmaking' => 'matchmaking'
			))
			->where('foreignlang=?', $userdata['user']['foreignlang'])
			->where('username !=?', $userdata['user']['username'])
			->limit(1,0)
			;
						
			$existingUser = $select->query()->fetchAll();
			
			// opponent exists, delete existing user from matchmaking table and continue with matchmaking
			
			if($existingUser) {
				
				// delete user from matchmaking table 
			    $where = $db->getAdapter()->quoteInto('username=?',$existingUser[0]['username']);
			    if (!$db->delete($where))
		          	$error ++;
				
				// generate the gametype randomly
				$gametype = rand(1, 3);
				
				// write back both users to match table
				$success = Application_Model_Match::createMatch($existingUser[0], $userdata['user'], $gametype);
				
				// send match details back
				$select2 = $dbMatch->getAdapter()->select()->from(array(
				'match' => 'match'
				))
				->where('match.active = ?', 1)
				->where('opponent1 = ? OR opponent2 = ?', $userdata['user']['username'], $userdata['user']['username'] )
				;
				
				$matchAlreadyExistsForThisUser = $select2->query()->fetchAll();
				
			
				if(!file_exists('../tmp/wordsForMatchId' . $matchAlreadyExistsForThisUser[0]['id'] . '.txt')) {
					// logic for creating the list of words
				
					// first retrieve list of words depending on gametype
					
					$listOfWords = Application_Model_Words::retrieveWordsForMultiplyChoiceGame();
							
					
					
					// TO DO: then write back the words to file, so both players get the same words
					Application_Model_Helper::createFileForMatch($matchAlreadyExistsForThisUser[0]['id'], json_encode($listOfWords), 1);

				}
			
				$words = Application_Model_Helper::readFromFile($matchAlreadyExistsForThisUser[0]['id'], 1);
				$words = json_decode($words);
				
				// retrieve the match data, so I can remove the language of the opponent user from the wordslist
				$select3 = $dbMatch->getAdapter()->select()->from(array(
				'match' => 'match'
				))
				->where('match.active = ?', 1)
				->where('opponent1 = ? OR opponent2 = ?', $userdata['user']['username'], $userdata['user']['username'] )
				;
				
				$matchdata = $select3->query()->fetchAll();
				
				// retrieve coverpic for opponent
				$dbUser = new Application_Model_DbTable_User();
				$select5 = $dbUser->getAdapter()->select()->from(array(
				'user' => 'user'
				))
				->where('user.username = ?',$existingUser[0]['username'])
				;
				
				$coverpic = $select5->query()->fetchAll();
				
				$matchAlreadyExistsForThisUser[0]['category'] = 0;
				$matchAlreadyExistsForThisUser[0]['coverpic'] = $coverpic[0]['coverpic'];
				$matchAlreadyExistsForThisUser[0]['profilepic'] = $coverpic[0]['profilepic'];
				
				$matchAlreadyExistsForThisUser[0]['words'] = $words;
				
					
				return $matchAlreadyExistsForThisUser ? Zend_Json::encode(array('match' => $matchAlreadyExistsForThisUser[0])) : "dberror2";
				
			}
			// no opponent existing, please write back to table and then provide timer to request for maximum 30 secs
			
			else {
			
				// check if user is not already in the matchmaking table 
				$select = $db->getAdapter()->select()->from(array(
				'matchmaking' => 'matchmaking'
				))
				->where('username =?', $userdata['user']['username'])
				->limit(1,0)
				;
				
				
				$userIsalreadyInMatchmakingTable = $select->query()->fetchAll();
				
				// only write back to table when user does NOT exist yet
				if(!$userIsalreadyInMatchmakingTable) {
					$row = $db->createRow();
					
					$row->username = $userdata['user']['username'];
					$row->nativelang = $userdata['user']['nativelang'];
					$row->foreignlang = $userdata['user']['foreignlang'];
			
					if (!$row->save())
						$error ++;
					
					return $error ? "dberror3" : "registered"; 
				}
				
				// otherwise send back that it's still searching and repeat requests coming from iOS
				else {
					return "waiting";
				}
				
			}
			
		}
		
		
		
	}

	public static function challengeFriend($data) {
		$db = new Application_Model_DbTable_MatchmakingFriends();
		$dbUser = new Application_Model_DbTable_User();
		
		// check if accepterId has no entry in DB (if there is an entry, you cannot challenge)
		
		$select = $db->getAdapter()->select()->from(array(
			'matchmakingFriends' => 'matchmakingFriends'
		), array('id'))
		->where('accepterId=?', $data['accepterId']);
		
		$idExists = $select->query()->fetchAll();
		
		// not possible to challenge
		if($idExists[0]['id']) 
			return "not possible to challenge";
		
		// check if challenging is allowed (foreignlanguage needs to be the same)
		
		// get accepterData (token and foreignlanguage)
		$select = $dbUser->getAdapter()->select()->from(array(
			'user' => 'user'
		), array('devicetoken', 'foreignlang'))
		->where('id=?', $data['accepterId'])
		;
		
		$accepterData = $select->query()->fetchAll();
		
		// get challengerData (foreignlanguage and username)
		$select = $dbUser->getAdapter()->select()->from(array(
			'user' => 'user'
		), array('foreignlang','username'))
		->where('id=?', $data['challengerId'])
		;
		
		$challengerData = $select->query()->fetchAll();
		
		// same foreign languages (if so, challenging is allowed)
		if($accepterData[0]['foreignlang'] == $challengerData[0]['foreignlang']) {
			// allowed to challenge friend (validation success)
			$row = $db->createRow();
						
			$row->challengerId = $data['challengerId'];
			$row->accepterId = $data['accepterId'];
			$row->status = 0;
	
			if (!($matchId = $row->save()))
				$error ++;
			
			$match = array();
			$match[0]['matchId'] = $matchId;
			
			sendPush($accepterData[0]['devicetoken'], $challengerData[0]['username'] . " hat dich herausgefordert! Bock Schlund?");
			
			return $error ? "could not save challenge request" : Zend_Json::encode(array('friendMatchRequest' => $match[0])); 
		}
		
		else {
			return "different foreign languages";
		}
		
		
	}

	public static function pingFriendChallengeRequest($data) {
		
		// accepter is sending the ping
		if($data['accepterId']) {
			
		}
			
		else {
			
		}
	}
	
	public static function friendMatchMakingExists($data) {
		$db = new Application_Model_DbTable_MatchmakingFriends();
		$dbUser = new Application_Model_DbTable_User();
		
		// check if user exists in matchmaking
		$select = $db->getAdapter()->select()->from(array(
			'matchmakingFriends' => 'matchmakingFriends'
		),array('id', 'challengerId'))
		->where('challengerId=?', $data['accepterId'])
		->orWhere('accepterId=?', $data['accepterId'])
		;
		
		$matchmakingId = $select->query()->fetchAll();
		
		$select = $dbUser->getAdapter()->select()->from(array(
			'user' => 'user'
		), array('username'))
		->where('id=?', $matchmakingId[0]['challengerId'])
		;
		
		$challengerUsername = $select->query()->fetchAll();
		
		$matchmakingData = array();
		$matchmakingData[0]['matchId'] = $matchmakingId[0]['id'];
		$matchmakingData[0]['challengerUsername'] = $challengerUsername[0]['username'];
		
		
		
		return $matchmakingId ? Zend_Json::encode(array('friendMatchRequest' => $matchmakingData[0])) : "match does not exist";
	}
	
	
	public static function removePlayerFromMatchmaking($user) {
		
		$SEMKey = "123456";
	  ## Get Semaphore id
	  $seg = sem_get($SEMKey, 1, 0666, -1);
	  sem_acquire($seg);
		//$user['username']
		$db = new Application_Model_DbTable_Matchmaking();
		
		$where = $db->getAdapter()->quoteInto('username=?',$user['username']);
	      if (!$db->delete($where))
	          $error ++;
		
		return $error ? "dberror4" : "removedSuccessfully";
		
		sem_release($seg);
		
	}	

}

?>