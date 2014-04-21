<?php

class Application_Model_Randommatch {
	
	/**
	 * 
	 * @param user_id, foreign_language, ranking
	 */
	public static function randomMatch($matchdata) {
		
		$db = new Application_Model_DbTable_Randommatch();
		$dbMmatch = new Application_Model_DbTable_Mmatch();
		$dbUser = new Application_Model_DbTable_User();
		$dbActiveMatches = new Application_Model_DbTable_Activemmatches();
		
		$select = $db->getAdapter()->select()->from(array(
			'randommatch' => 'randommatch'
		), array('*'))
		->where('foreign_language=?', $matchdata['foreignlang'])
		->where('level=?', $matchdata['level'])
		->where('ranking > ?', $matchdata['ranking']-100)
		->where('ranking < ?', $matchdata['ranking']+100)
		->where('user_id NOT IN (?)', $matchdata['user_id'])
		;
		
		$result = $select->query()->fetchAll();
		
		$user_info = $db->getAdapter()->select()->from(array(
			'randommatch' => 'randommatch'
		), array('count' => 'count(*)'))
		->where('user_id = ?', $matchdata['user_id'])
		;
		
		$entry_count = $user_info->query()->fetchAll();

		// check if possible opponent exists
		if ($result == NULL && $entry_count[0]['count'] <= 8) {

			// if no opponent in table, write user data to randommatch table
			$row = $db->createRow();
			
			$row->match_id = 0;
			$row->user_id = $matchdata['user_id'];
			$row->foreign_language = $matchdata['foreignlang'];
			$row->level = $matchdata['level'];
			$row->ranking = $matchdata['ranking'];
			
			
			try {
			    $row->save();
			} catch (Exception $e){
			    $error = $e->getMessage();
			}
			
			$randomID = $row->id;
			
			// get Username of requesting User
			$select = $dbUser->getAdapter()->select()->from(array(
				'user' => 'user', array('username')
			))
			->where('id = ?', $matchdata['user_id'])
			;
			
			$user1 = $select->query()->fetchAll();
			
			$username1 = $user1[0]['username'];
			
			// save match details in mmatch table
			$date = date("y-m-d");
			$row = $dbMmatch->createRow();
			
			$row->opponent1 = "zufälliger Gegner";
			$row->nativelang1 = "";
			$row->opponent2 = $username1;
			$row->nativelang2 = $matchdata['nativelang'];
			$row->foreignlang = $matchdata['foreignlang'];
			$row->level = $matchdata['level'];
			$row->aborted = 0;
			$row->active = 1;
			$row->startdate = $date;
				
			try {
			    $row->save();
			} catch (Exception $e){
			    $error = $e->getMessage();
			}
			
			// save match ID
			$id = $row->id;
						
			// save matchID and userID of first player in active matches table
			$rows = $dbActiveMatches->createRow();
			
			$rows->match_id = $id;
			$rows->user_id1 = $matchdata['user_id'];
			$rows->user_id2 = "";
			
			try {
			    $rows->save();
			} catch (Exception $e){
			    $error = $e->getMessage();
			}
			
			// set matchID in randommatch table
			$data = array(
				'match_id' => $id
			);
					
			$where = $db->getAdapter()->quoteInto('id = ?', $randomID);
			
			try {
			    $db->update($data, $where);
			} catch (Exception $e){
			    $error = $e->getMessage();
			}
			
			// retrieve the words for this match
			// check if file already exists (should never be the case because match id is unique at the moment you accept the request)
			if(!file_exists('../tmp/mmatch/wordsForMatchId' . $id . '.txt')) {
				// logic for creating the list of words
					
				// first retrieve list of words
				$listOfWords = Application_Model_Words::retrieveWordsForMultiplyChoiceGame();
				// TO DO: then write back the words to file, so both players get the same words
				Application_Model_Helper::createFileForMatch($id, json_encode($listOfWords), 2);
			}
			
			return $error ? $error : "success";
			
		} else {
			// if possible opponents exist, do THAT
			
			// fetch random match from result array
			$randomOpponent = array_rand($result);
			
			// START: RECEIVE USER DATA
			
			// get Username of User who matched with a random match
			$select2 = $dbUser->getAdapter()->select()->from(array(
				'user' => 'user', array('username', 'nativelang', 'devicetoken')
			))
			->where('id = ?', $matchdata['user_id'])
			;
			
			$user2 = $select2->query()->fetchAll();
			
			$username2 = $user2[0]['username'];
			// END: RECEIVE USER DATA
			
			// update matched match with second username and nativelang
			
			$data = array(
				'opponent1' => $username2,
				'nativelang1' => $matchdata['nativelang']
			);
					
			$where = $dbMmatch->getAdapter()->quoteInto('id = ?', $result[$randomOpponent]['match_id']);
			
			try {
			    $dbMmatch->update($data, $where);
			} catch (Exception $e){
			    $error = $e->getMessage();
			}

			// delete entry in randomMatch		
			$where = $db->getAdapter()->quoteInto('match_id=?', $result[$randomOpponent]['match_id']);
			try {
			    $db->delete($where);
			} catch (Exception $e){
			    $error = $e->getMessage();
			}

			return $error ? $error : "success";
		}
	}
}
		