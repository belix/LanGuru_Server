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
		$row->gametype = $gametype;

		if (!$row->save())
			$error ++;
		
		return $error ? false : true;
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
		
		else {
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
	
	
	// method for what should happen if someone leaves the app or the app crashes
	
	public static function abortMatch($matchdata) {
		$db = new Application_Model_DbTable_Match();
		
		// check if both players finished game already
		$select = $db->getAdapter()->select()->from(array(
		'match' => 'match'
		),array('aborted'))
		->where('id = ?', $matchdata['id'])		
		;
		
		$abortedValue = $select->query()->fetchAll();
		
		
		if($matchdata['opponent'] == 'opponent1') {
			// if noone aborted yet
			if($abortedValue[0]['aborted'] == 0) {
				// write back a 1 to the dataset
				$data = array(
		    'aborted'      => 1
				);
				
				$where = $db->getAdapter()->quoteInto('id = ?', $matchdata['id']);
				 
				if(!$db->update($data, $where))
					$error++;
			}
			
			// player 2 already aborted (so value is 2)
			else {
				// set the value to 3
				$data = array(
		    'aborted'      => 3
				);
				
				$where = $db->getAdapter()->quoteInto('id = ?', $matchdata['id']);
				 
				if(!$db->update($data, $where))
					$error++;
				
			}
			
		
		}
		
		else {
			// if noone aborted yet
			if($abortedValue[0]['aborted']  == 0) {
				// write back a 1 to the dataset
				$data = array(
		    'aborted'      => 2
				);
				
				$where = $db->getAdapter()->quoteInto('id = ?', $matchdata['id']);
				 
				if(!$db->update($data, $where))
					$error++;
			}
			
			// player 2 already aborted (so value is 2)
			else {
				// set the value to 3
				$data = array(
		    'aborted'      => 3
				);
				
				$where = $db->getAdapter()->quoteInto('id = ?', $matchdata['id']);
				 
				if(!$db->update($data, $where))
					$error++;
				
			}
		}
		
		return $error ? false : true;

	}
}

?>