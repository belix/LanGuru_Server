<?php

class Application_Model_Mmatch {
	
	// request a match for a specific user
	public static function requestMatch($matchdata) {
		$db = new Application_Model_DbTable_Mmatch();
		$dbUser = new Application_Model_DbTable_User();
		$dbActiveMatches = new Application_Model_DbTable_Activemmatches();
				
		// START: CHECK IF MATCH ALREADY EXISTS
		// TO-DO: DOES NOT WORK MAYBE
		$array = array($matchdata['user_id1'], $matchdata['user_id2']);
		$select = $dbActiveMatches->getAdapter()->select()->from(array(
			'activemmatches' => 'activemmatches'
		))
		->where('user_id1 IN (?)', $array)
		->where('user_id2 IN (?)', $array)
		;
		
		$result = $select->query()->fetchAll();
		
		
		if($result == NULL) {
		// END: CHECK IF MATCH ALREADY EXISTS
		
		// START: RECEIVE USER DATA
		$select = $dbUser->getAdapter()->select()->from(array(
			'user' => 'user', array('username')
		))
		->where('id = ?', $matchdata['user_id1'])
		;
		
		$select2 = $dbUser->getAdapter()->select()->from(array(
			'user' => 'user', array('username')
		))
		->where('id = ?', $matchdata['user_id2'])
		;
		
		$user1 = $select->query()->fetchAll();
		$user2 = $select2->query()->fetchAll();
		
		$username1 = $user1[0]['username'];
		$username2 = $user2[0]['username'];
		// END: RECEIVE USER DATA
		
		// START: SAVE MATCH
		$date = date("y-m-d");
		$row = $db->createRow();
		
		$row->opponent1 = $username1;
		$row->nativelang1 = $matchdata['nativelang1'];
		$row->opponent2 = $username2;
		$row->foreignlang = $matchdata['foreignlang'];
		$row->level = $matchdata['level'];
		$row->aborted = 0;
		$row->active = 0;
		$row->startdate = $date;

		if (!($id = $row->save()))
			$error ++;
		
		// END: SAVE MATCH
		
		// START: SAVE FB IDs TO ACTIVEMMATCHES TABLE
		$row = $dbActiveMatches->createRow();
		
		$row->match_id = $id;
		$row->user_id1 = $matchdata['user_id1'];
		$row->user_id2 = $matchdata['user_id2'];
		
		if (!$row->save())
			$error ++;
		// END: SAVE FB IDs TO ACTIVEMMATCHES TABLE
		
		sendPush($user2[0]['devicetoken'], $username1 . ' fordert dich zu einem Match heraus. Schlund?');
		return $error ? "dberror-requestMatch" : "success";
	}

	else {
		return "match-exists";
	}
	}


	public static function getFriendDetails($array) {
		$db = new Application_Model_DbTable_User();
		
		$select = $db->getAdapter()->select()->from(array(
			'user' => 'user',
		), array('id', 'username', 'fbid', 'ranking', 'nativelang', 'foreignlang'))
		->where('fbid IN(?)', $array['fbids'])
		;
									
		$result = $select->query()->fetchAll();
		
		return $result ? Zend_Json::encode(array('userdetails' => $result)) : "dberror-could-not-retrieve-userdetails";
				
	}



	// function to check if there are existing matches for a specific user with the specific status
	// TO DO: returns only one match
	public static function matchExists($userdata) {
		$db = new Application_Model_DbTable_Mmatch();
		
		// get rankings of both users and write back to match table 
		$select = $db->getAdapter()->select()->from(array(
		'mmatch' => 'mmatch'
		))
		->where('opponent1 = ? OR opponent2 = ?', $userdata['user'], $userdata['user'])
		;
		
		$result = $select->query()->fetchAll();
		
		
		if($result == NULL) {
			return "no-match";
		}
		
		else {
			return $result ? Zend_Json::encode(array('matches' => $result)) : "dberror-could-not-retrieve-matches";
		}

	}
	
	public static function getWordsForMatch($matchdata) {
		$db = new Application_Model_DbTable_Mmatch();
		
		// get rankings of both users and write back to match table 
		$select = $db->getAdapter()->select()->from(array(
			'mmatch' => 'mmatch'
		))
		->where('id = ?', $matchdata['id'])
		;
		
		$result = $select->query()->fetchAll();		
		
		$words = Application_Model_Helper::readFromFile($matchdata['id'], 2);
		$words = json_decode($words);

		$result[0]['words'] = $words;
		
		return $result ? Zend_Json::encode(array('match' => $result[0])) : "dberror-could-not-retrieve-words";
	
	}
	// function to delete the match in the mmatch table if user declines request by other user
	public static function deleteRequest($matchdata) {
		$db = new Application_Model_DbTable_Mmatch();
		
		$where = $db->getAdapter()->quoteInto('id=?',$matchdata['id']);
      	if (!$db->delete($where))
      		$error ++;

		return $error ? "dberror-delete-request" : "success";
		
	}
	
	// function to accept the match by the second user, so the match table gets updated with the needed data
	public static function acceptRequest($matchdata) {
		$db = new Application_Model_DbTable_Mmatch();
		
		$data = array(
			'nativelang2' => $matchdata['nativelang2'],
		    'active'      => 1
		);
				
		$where = $db->getAdapter()->quoteInto('id = ?', $matchdata['id']);
		 
		if(!$db->update($data, $where))
			$error++;
		
		// request the match and return it back to the device
		$select = $db->getAdapter()->select()->from(array(
		'mmatch' => 'mmatch'))
		->where('id = ?', $matchdata['id'])
		;
		
		$result = $select->query()->fetchAll();
		
		// retrieve the words for this match
		// check if file already exists (should never be the case because match id is unique at the moment you accept the request)
		if(!file_exists('../tmp/mmatch/wordsForMatchId' . $matchdata['id'] . '.txt')) {
		// logic for creating the list of words
			
		// first retrieve list of words
		$listOfWords = Application_Model_Words::retrieveWordsForMultiplyChoiceGame();
		// TO DO: then write back the words to file, so both players get the same words
		Application_Model_Helper::createFileForMatch($matchdata['id'], json_encode($listOfWords), 2);


		}
		
			
		return $result ? Zend_Json::encode(array('match' => $result[0])) : "dberror-could-not-accept-match";

	}

	// function to finish a specific round
	public static function finishRound($matchdata) {
		$db = new Application_Model_DbTable_Mmatch();
		$dbUser = new Application_Model_DbTable_User();

		// get match data first
		$select = $db->getAdapter()->select()->from(array(
		'mmatch' => 'mmatch'
		))
		->where('id = ?', $matchdata['id'])
		;
		
		$result10 = $select->query()->fetchAll();
		
		if($matchdata['opponent'] == "opponent1") {
			$data = array(
				'score1' => $matchdata['score'],
		   	 	'active' => $matchdata['active']
			);
			
			// send push to opponent-2 because opponent 1 finished, get user data first
			$select = $dbUser->getAdapter()->select()->from(array(
			'user' => 'user', array('devicetoken')
			))
			->where('username = ?', $result10[0]['opponent2'])
			;
			
			$result11 = $select->query()->fetchAll();
			
			sendPush($result11[0]['devicetoken'], $result10[0]['opponent1'] . ' hat die Runde beendet. Du bist wieder dran. Schlund?');
			
		}
		
		else {
			$data = array(
				'score2' => $matchdata['score'],
 				'active' => $matchdata['active']
			);
			
			// send push to opponent-1 because opponent 2 finished, get user data first
			$select = $dbUser->getAdapter()->select()->from(array(
			'user' => 'user', array('devicetoken')
			))
			->where('username = ?', $result10[0]['opponent1'])
			;
			
			$result11 = $select->query()->fetchAll();
			
			sendPush($result11[0]['devicetoken'], $result10[0]['opponent2'] . ' hat die Runde beendet. Du bist wieder dran. Schlund?');
			
		}
		
		
				
		$where = $db->getAdapter()->quoteInto('id = ?', $matchdata['id']);
		 
		if(!$db->update($data, $where))
			$error++;
		
		// delete file for round 1 and create a new one for round 2
		if($matchdata['active'] == 3) {
			// and delete existing file in tmp folder
			$file = '../tmp/mmatch/wordsForMatchId' . $matchdata['id'] . '.txt';
			unlink($file);
			
			// create new file
			// first retrieve list of words
			$listOfWords = Application_Model_Words::retrieveWordsForWordCompletion($matchdata['foreignlang'], $matchdata['nativelang1'], $matchdata['nativelang2']);
			// TO DO: then write back the words to file, so both players get the same words
			Application_Model_Helper::createFileForMatch($matchdata['id'], json_encode($listOfWords), 2);
		}
		
		// delete file for round 2 and create a new one for round 3
		if($matchdata['active'] == 5) {
			$file = '../tmp/mmatch/wordsForMatchId' . $matchdata['id'] . '.txt';
			unlink($file);
			
			// create new file
			// first retrieve list of words
			$listOfWords = Application_Model_Words::retrieveWordsForMatrixGame($matchdata['foreignlang'], $matchdata['nativelang1'], $matchdata['nativelang2']);
			// TO DO: then write back the words to file, so both players get the same words
			Application_Model_Helper::createFileForMatch($matchdata['id'], json_encode($listOfWords), 2);
		}
		
		// ÄNDERUNG - WICHTIG BELE FRAGEN (Änderung von 5 auf 7 beim Aktivstatus)
		// check if this was the last round
		if($matchdata['active'] == 7) {
			// get the current match from the match table
			$select = $db->getAdapter()->select()->from(array(
			'mmatch' => 'mmatch'
			))
			->where('id = ?', $matchdata['id'])
			;
			
			$result = $select->query()->fetchAll();
			
			// Beispiel 1
			$score1 = explode("#", $result[0]['score1']);
			$score2 = explode("#", $result[0]['score2']);
			$finalScore1 = 0;
			$finalScore2 = 0;
			
			foreach($score1 as $key => $value){
				$finalScore1 += intval($value);
			}
			
			foreach($score2 as $key => $value) {
				$finalScore2 += intval($value);
			}
			
			// get the current ranking for both users
			$select2 = $dbUser->getAdapter()->select()->from(array(
			'user' => 'user', array('ranking')
			))
			->where('username = ?', $result[0]['opponent1'])
			;
			
			// get the current ranking for both users
			$select3 = $dbUser->getAdapter()->select()->from(array(
			'user' => 'user', array('ranking')
			))
			->where('username = ?', $result[0]['opponent2'])
			;
			
			$rankingResult1 = $select2->query()->fetchAll();
			$rankingResult2 = $select3->query()->fetchAll();
			$currentRanking1 = $rankingResult1[0]['ranking'];
			$currentRanking2 = $rankingResult2[0]['ranking'];
			
			// TO DO: built in the formula for calculating the ranking
			
			// calculate which user has won the match
			if($finalScore1 > $finalScore2) {
				// player1 won, player 2 lost (fixed here to 10 ppts, need to be adjusted by logic)
				$currentRanking1 += 10;
				$currentRanking2 -= 10;
				
				$data = array(
					'rankdiff1' => 10,
					'rankdiff2' => -10
				);
	
				$where = $db->getAdapter()->quoteInto('id = ?', $matchdata['id']);
				 
				if(!$db->update($data, $where))
					$error++;
			}
			
			else if($finalScore1 < $finalScore2) {
				// player1 won, player 2 lost (fixed here to 10 ppts, need to be adjusted by logic)
				$currentRanking1 -= 10;
				$currentRanking2 += 10;
				
				$data = array(
					'rankdiff1' => -10,
					'rankdiff2' => 10
				);
	
				$where = $db->getAdapter()->quoteInto('id = ?', $matchdata['id']);
				 
				if(!$db->update($data, $where))
					$error++;
			}
			
			else {
				// tied match
				
				
			}
			
			// save the new ranking to the user table (user1)
			$data = array(
				'ranking' => $currentRanking1
			);
	
			$where = $db->getAdapter()->quoteInto('username = ?', $result[0]['opponent1']);
			 
			if(!$dbUser->update($data, $where))
				$error++;
			
			// save the new ranking to the user table (user2)
			$data = array(
				'ranking' => $currentRanking2
			);
	
			$where = $db->getAdapter()->quoteInto('username = ?', $result[0]['opponent2']);
			 
			if(!$dbUser->update($data, $where))
				$error++;
			
			
			// request the match and return it back to the device
			$select = $db->getAdapter()->select()->from(array(
			'mmatch' => 'mmatch'))
			->where('id = ?', $matchdata['id'])
			;
			
			$result = $select->query()->fetchAll();
			
			// START: DELETE MATCH FROM ACTIVEMATCH TABLE
			$dbActiveMatches = new Application_Model_DbTable_Activemmatches();
		
			$where = $dbActiveMatches->getAdapter()->quoteInto('match_id=?',$matchdata['id']);
	      	if (!$dbActiveMatches->delete($where))
	      		$error ++;
			// END: DELETE MATCH FROM ACTIVEMATCH TABLE
			
			return $result ? Zend_Json::encode(array('match' => array('rankdiff1' => $result[0]['rankdiff1'],'rankdiff2' => $result[0]['rankdiff2'], 'ranking1' => $currentRanking1, 'ranking2' => $currentRanking2))) : "dberror-could-not-finish-match-active-5";
			
		}

		return $error ? "dberror-finishRound" : Zend_Json::encode(array('match' => array()));
	}

}

?>