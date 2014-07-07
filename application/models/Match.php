<?php

class Application_Model_Match {
	
	public static function createMatch($opponent1, $opponent2, $gametype) {
		
		$db = new Application_Model_DbTable_Match();
		$dbUser = new Application_Model_DbTable_User();
		
		// get rankings of both users and write back to match table 
		$select = $dbUser->getAdapter()->select()->from(array(
		'user' => 'user', array('ranking')
		))
		->where('username = ?', $opponent1['username'])
		;
		
		$select2 = $dbUser->getAdapter()->select()->from(array(
		'user' => 'user', array('ranking')
		))
		->where('username = ?', $opponent2['username'])
		;
		
		$userRanking1 = $select->query()->fetchAll();
		$userRanking2 = $select2->query()->fetchAll();
		
		
		$row = $db->createRow();
		
		$row->opponent1 = $opponent1['username'];
		$row->nativelang1 = $opponent1['nativelang'];
		$row->opponent2 = $opponent2['username'];
		$row->nativelang2 = $opponent2['nativelang'];
		$row->foreignlang = $opponent1['foreignlang'];
		$row->ranking1 = $userRanking1[0]['ranking'];
		$row->ranking2 = $userRanking2[0]['ranking'];
		$row->aborted = 0;
		$row->active = 1;

		if (!$row->save())
			$error ++;
		
		return $error ? false : true;
	}

	public function createFriendMatch($challengerId, $accepterId) {
		$db = new Application_Model_DbTable_Match();
		$dbUser = new Application_Model_DbTable_User();
		
		// get rankings of both users and write back to match table 
		$select = $dbUser->getAdapter()->select()->from(array(
		'user' => 'user'
		))
		->where('id = ?', $challengerId)
		;
		
		$select2 = $dbUser->getAdapter()->select()->from(array(
		'user' => 'user'
		))
		->where('id = ?', $accepterId)
		;
		
		$userData1 = $select->query()->fetchAll();
		$userData2 = $select2->query()->fetchAll();
		
		
		$row = $db->createRow();
		
		$row->opponent1 = $userData1[0]['username'];
		$row->nativelang1 = $userData1[0]['nativelang'];
		$row->opponent2 = $userData2[0]['username'];
		$row->nativelang2 = $userData2[0]['nativelang'];
		$row->foreignlang = $userData1[0]['foreignlang'];
		$row->ranking1 = $userData1[0]['ranking'];
		$row->ranking2 = $userData2[0]['ranking'];
		$row->aborted = 0;
		$row->active = 1;

		if (!($matchId = $row->save()))
			$error ++;
		
		$listOfWords = Application_Model_Words::retrieveWordsForMultiplyChoiceGame();
		// TO DO: then write back the words to file, so both players get the same words
		Application_Model_Helper::createFileForMatch($matchId, json_encode($listOfWords), 1);
		
		return $error ? false : $matchId;
	}
	
	
	public static function finishMatch($matchdata) {
		// save score to match table
		$db = new Application_Model_DbTable_Match();
		$dbUser = new Application_Model_DbTable_User();
		
		// check if opponent1 or opponent2 is sending the request
		if($matchdata['opponent'] == 'opponent1') {
			$data = array(
		    	'result1'      => $matchdata['result']
			);
			
			$where = $db->getAdapter()->quoteInto('id = ?', $matchdata['id']);
		 
			if(!$db->update($data, $where))
				$error++;
			
			// check if both players finished game already
			$select = $db->getAdapter()->select()->from(array(
			'match' => 'match'
			))
			->where('id = ?', $matchdata['id'])		
			;
			
			$allMatchData = $select->query()->fetchAll();
			
			if($allMatchData[0]['result2'] != NULL) {
				
				// calculate who the winner is and add it to the response
				$result1 = substr_count( $allMatchData[0]['result1'], "1" );
				$result2 = substr_count( $allMatchData[0]['result2'], "1" );
				
				// unentschieden
				if($result1 == $result2) {
					// nothing happens here
					
				}
				
				else if($result1 > $result2) {
					$allMatchData[0]['ranking1'] =  intval($allMatchData[0]['ranking1']) + 10;
					$allMatchData[0]['ranking2'] =  intval($allMatchData[0]['ranking2']) - 10;
				}
				
				else {
					$allMatchData[0]['ranking1'] =  intval($allMatchData[0]['ranking1']) - 10;
					$allMatchData[0]['ranking2'] =  intval($allMatchData[0]['ranking2']) + 10;
				}
				
				
				// update the user table with new ranking
				$userRanking = array(
		    	'ranking'      => $allMatchData[0]['ranking1']
				);
				
				$where = $dbUser->getAdapter()->quoteInto('username = ?', $matchdata['username']);
			 
				if(!$dbUser->update($userRanking, $where))
					$error++;
				
				echo Zend_Json::encode(array('match' => $allMatchData[0]));
			}
			else {
				echo "continue";
			}
		}
		
		else if ($matchdata['opponent'] == 'opponent2') {
			$data = array(
		    	'result2'      => $matchdata['result']
			);
			
			$where = $db->getAdapter()->quoteInto('id = ?', $matchdata['id']);
		 
			if(!$db->update($data, $where))
				$error++;
			
			// check if both players finished game already
			$select = $db->getAdapter()->select()->from(array(
			'match' => 'match'
			))
			->where('id = ?', $matchdata['id'])		
			;
			
			$allMatchData = $select->query()->fetchAll();
		
			if($allMatchData[0]['result1'] != NULL) {
				
				// calculate who the winner is and add it to the response
				$result1 = substr_count( $allMatchData[0]['result1'], "1" );
				$result2 = substr_count( $allMatchData[0]['result2'], "1" );
				
				// unentschieden
				if($result1 == $result2) {
					// nothing happens here
					
				}
				
				else if($result1 > $result2) {
					$allMatchData[0]['ranking1'] =  intval($allMatchData[0]['ranking1']) + 10;
					$allMatchData[0]['ranking2'] =  intval($allMatchData[0]['ranking2']) - 10;
				}
				
				else {
					$allMatchData[0]['ranking1'] =  intval($allMatchData[0]['ranking1']) - 10;
					$allMatchData[0]['ranking2'] =  intval($allMatchData[0]['ranking2']) + 10;
				}
				
				// update the user table with new ranking
				$userRanking = array(
		    	'ranking'      => $allMatchData[0]['ranking2']
				);
				
				$where = $dbUser->getAdapter()->quoteInto('username = ?', $matchdata['username']);
			 
				if(!$dbUser->update($userRanking, $where))
					$error++;
										
				echo Zend_Json::encode(array('match' => $allMatchData[0]));
			}
			else {
				echo "continue";
			}
			// if match should be finished because of user abort
		} else if ($matchdata['status'] == "abort") {
			
			// get current user rankings
			$select = $dbUser->getAdapter()->select()->from(array(
				'user'
			), array('ranking'))
			->where('username = ?', $matchdata['opponent1'])		
			;
			
			$ranking1 = $select->query()->fetchAll();
			
			$select = $dbUser->getAdapter()->select()->from(array(
				'user'
			), array('ranking'))
			->where('username = ?', $matchdata['opponent2'])		
			;
			
			$ranking2 = $select->query()->fetchAll();
						
			// set ranking of both users
			if ($matchdata['winner'] == "opponent1") {
				$allMatchData[0]['ranking1'] =  intval($ranking1[0]['ranking']) + 10;
				$allMatchData[0]['ranking2'] =  intval($ranking2[0]['ranking']) - 10;
				
				// update the user tables with new rankings
				$userRanking2 = array(
			    	'ranking'      => $allMatchData[0]['ranking2']
				);
				
				$userRanking1 = array(
			    	'ranking'      => $allMatchData[0]['ranking1']
				);
					
				$where2 = $dbUser->getAdapter()->quoteInto('username = ?', $matchdata['opponent2']);
				$where1 = $dbUser->getAdapter()->quoteInto('username = ?', $matchdata['opponent1']);
				 
				if(!$dbUser->update($userRanking2, $where2))
					$error++;
				
				if(!$dbUser->update($userRanking1, $where1))
					$error++;
			}
			else {
				$allMatchData[0]['ranking1'] =  intval($ranking1[0]['ranking']) - 10;
				$allMatchData[0]['ranking2'] =  intval($ranking2[0]['ranking']) + 10;
				
				// update the user tables with new rankings
				$userRanking2 = array(
			    	'ranking'      => $allMatchData[0]['ranking2']
				);
				
				$userRanking1 = array(
			    	'ranking'      => $allMatchData[0]['ranking1']
				);
					
				$where2 = $dbUser->getAdapter()->quoteInto('username = ?', $matchdata['opponent2']);
				$where1 = $dbUser->getAdapter()->quoteInto('username = ?', $matchdata['opponent1']);
				 
				if(!$dbUser->update($userRanking2, $where2))
					$error++;
				
				if(!$dbUser->update($userRanking1, $where1))
					$error++;
			}			
			
			// return to iOS
			echo Zend_Json::encode(array('match' => $allMatchData[0]));
		}
		
	}	
	
	
	
	public static function closeMatch($matchid) {
		
		// after setting inactive we need to copy the match to the playedmatch table and delete 
		// from match table
		
		$db = new Application_Model_DbTable_Match();
		
		// check if both players finished game already
		$select = $db->getAdapter()->select()->from(array(
		'match' => 'match'
		))
		->where('id = ?', $matchid['id'])		
		;
		
		$allMatchData = $select->query()->fetchAll();
		
		// close match only once, not twice (not possible)
		if($allMatchData[0] != NULL) {
		
		$data = array(
		    'active'      => 0
		);
		
		$where = $db->getAdapter()->quoteInto('id = ?', $matchid['id']);
		 
		if(!$db->update($data, $where))
			$error++;
		
		// retrieve all information for this match id
		
		// first check if match already exists because someone else already matched you
		$select = $db->getAdapter()->select()->from(array(
		'match' => 'match'
		))
		->where('id = ?', $matchid['id'])
		;
		
		$result = $select->query()->fetchAll();
		
		// copy file to playedmatch table
		Application_Model_Playedmatch::copyMatch($result[0]);
		
		// delete match from match table
		$where = $db->getAdapter()->quoteInto('id=?',$matchid['id']);
      	if (!$db->delete($where))
      		$error ++;
		
		// and delete existing file in tmp folder
		$file = '../tmp/wordsForMatchId' . $matchid['id'] . '.txt';
		unlink($file);
		
		return $error ? false : true;
		}
		
	}


	// transfer data to server every second (match_id, timestamp, score, opponent)
	public static function pingServer($matchdata) {
		$db = new Application_Model_DbTable_Match();
		
		$date = new DateTime();
		$tstamp = $date->getTimestamp();
		$tstampdiff = $tstamp-5;
		
		if ($matchdata['opponent'] == 'opponent1'):
			$data = array(
			   	'opp1tstamp'	=> $matchdata['timestamp'],
			   	'score1' => $matchdata['score'],
			   	'result1' => $matchdata['result']
			);
			
			$where = $db->getAdapter()->quoteInto('id = ?', $matchdata['id']);
					 
			if(!$db->update($data, $where))
				$error++;
			
			$select = $db->getAdapter()->select()->from(array(
				'match' => 'match'
			),array('score2', 'opp2tstamp', 'result1', 'opponent1', 'opponent2'))
			->where('id = ?', $matchdata['id'])		
			;
			
			$scores = $select->query()->fetchAll();
			
			// check if opponent 2 has timed out, if yes then finish Match
			if ($scores[0]['opp2tstamp'] < $tstampdiff) {
				$match['result'] = $scores[0]['result1'];
				$match['winner'] = "opponent1";
				$match['status'] = "abort";
				$match['opponent1'] = $scores[0]['opponent1'];
				$match['opponent2'] = $scores[0]['opponent2'];
				Application_Model_Match::finishMatch($match);
			} else {
				$score = $scores[0]['score2'];
				echo Zend_Json::encode(array('match' => array('score2' => $score)));
			}
		else:
			$data = array(
			   	'opp2tstamp'	=> $matchdata['timestamp'],
			   	'score2' => $matchdata['score'],
			   	'result2' => $matchdata['result']
			);
			
			$where = $db->getAdapter()->quoteInto('id = ?', $matchdata['id']);
			
			if(!$db->update($data, $where))
				$error++;
			
			$select = $db->getAdapter()->select()->from(array(
				'match' => 'match'
			),array('score1', 'opp1tstamp', 'result2', 'opponent1', 'opponent2'))
			->where('id = ?', $matchdata['id'])		
			;
			
			$scores = $select->query()->fetchAll();
			
			// check if opponent 2 has timed out, if yes then finish Match
			if ($scores[0]['opp1tstamp'] < $tstampdiff) {
				$match['result'] = $scores[0]['result2'];
				$match['winner'] = "opponent2";
				$match['status'] = "abort";
				$match['opponent1'] = $scores[0]['opponent1'];
				$match['opponent2'] = $scores[0]['opponent2'];
				Application_Model_Match::finishMatch($match);
			} else {
				$score = $scores[0]['score1'];
				echo Zend_Json::encode(array('match' => array('score1' => $score)));
			}
		endif;
		
	}
	
	
	// method for what should happen if someone leaves the app or the app crashes
	
	public static function abortMatch($matchdata) {
		$db = new Application_Model_DbTable_Match();
		
		// check status of abort variable
		$select = $db->getAdapter()->select()->from(array(
			'match' => 'match'
		),array('aborted'))
		->where('id = ?', $matchdata['id'])		
		;
		
		$abortedValue = $select->query()->fetchAll();
		
		// if nobody has aborted the match yet
		if ($abortedValue[0]['aborted'] == 0) {
			
			$data = array(
		    	'aborted'      => 1
			);
				
			$where = $db->getAdapter()->quoteInto('id = ?', $matchdata['id']);
				 
			if(!$db->update($data, $where))
				$error++;
			
		} else if ($abortedValue[0]['aborted'] == 1) {
			
			$where = $db->getAdapter()->quoteInto('id = ?', $matchdata['id']);
        
			if (!$db->delete($where))
			$error ++;
			
			
		}
		
		return $error ? false : true;

	}
}

?>