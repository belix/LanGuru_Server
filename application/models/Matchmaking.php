<?php

class Application_Model_Matchmaking {
	
	public static function findOpponent($userdata) {
		
		$db = new Application_Model_DbTable_Matchmaking();
		$dbMatch = new Application_Model_DbTable_Match();
		
		// first check if match already exists because someone else already matched you
		$select2 = $dbMatch->getAdapter()->select()->from(array(
		'match' => 'match'
		))
		->where('match.active = ?', 1)
		->where('opponent1 = ? OR opponent2 = ?', $userdata['user']['username'], $userdata['user']['username'] )
		
		;
		
		$matchAlreadyExistsForThisUser = $select2->query()->fetchAll();
					 
		if($matchAlreadyExistsForThisUser) {
			
			
			if(!file_exists('../tmp/wordsForMatchId' . $matchAlreadyExistsForThisUser[0]['id'] . '.txt')) {
			// logic for creating the list of words
			
				// first retrieve list of words depending on the gametype
				switch ($matchAlreadyExistsForThisUser[0]['gametype']) {
					case 1:
						$listOfWords = Application_Model_Words::retrieveWordsForMultiplyChoiceGame();
						break;
						
					case 2:
						$listOfWords = Application_Model_Words::retrieveWordsForWordCompletion($matchAlreadyExistsForThisUser[0]['foreignlang'], $matchAlreadyExistsForThisUser[0]['nativelang1'], $matchAlreadyExistsForThisUser[0]['nativelang2']);
						break;
						
					case 3:
						$listOfWords = Application_Model_Words::retrieveWordsForMatrixGame($matchAlreadyExistsForThisUser[0]['foreignlang'], $matchAlreadyExistsForThisUser[0]['nativelang1'], $matchAlreadyExistsForThisUser[0]['nativelang2']);
						break;
						
					default:
							
						break;
				}

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
					switch ($matchAlreadyExistsForThisUser[0]['gametype']) {
						case 1:
							$listOfWords = Application_Model_Words::retrieveWordsForMultiplyChoiceGame();
							break;
							
						case 2:
							$listOfWords = Application_Model_Words::retrieveWordsForWordCompletion($matchAlreadyExistsForThisUser[0]['foreignlang'], $matchAlreadyExistsForThisUser[0]['nativelang1'], $matchAlreadyExistsForThisUser[0]['nativelang2']);
							break;
							
						case 3:
							$listOfWords = Application_Model_Words::retrieveWordsForMatrixGame($matchAlreadyExistsForThisUser[0]['foreignlang'], $matchAlreadyExistsForThisUser[0]['nativelang1'], $matchAlreadyExistsForThisUser[0]['nativelang2']);
							break;
						
						default:
							
							break;
					}
					
					
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