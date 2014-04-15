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
		;
		
		$result = $select->query()->fetchAll();
		
		// check if possible opponent exists
		if ($result == NULL) {
			// if no opponent in table, write user data to table
			$row = $db->createRow();
			
			$row->user_id = $matchdata['user_id'];
			$row->foreign_language = $matchdata['foreignlang'];
			$row->level = $matchdata['level'];
			$row->ranking = $matchdata['ranking'];
			
			if (!($row->save()))
				$error ++;
			
			return $error ? "dberror-randomMatch" : "success";
			
		} else {
			// if opponent exists, do THAT
			
			$randomOpponent = array_rand($result);
			
			// get user Details of opponent and user
			// START: RECEIVE USER DATA
			// get Username of requesting User
			$select = $dbUser->getAdapter()->select()->from(array(
				'user' => 'user', array('username')
			))
			->where('id = ?', $matchdata['user_id'])
			;
			
			// get Username of User who is currently in database
			$select2 = $dbUser->getAdapter()->select()->from(array(
				'user' => 'user', array('username', 'nativelang', 'devicetoken')
			))
			->where('id = ?', $result[$randomOpponent]['user_id'])
			;
			
			$user1 = $select->query()->fetchAll();
			$user2 = $select2->query()->fetchAll();
			
			$username1 = $user1[0]['username'];
			$username2 = $user2[0]['username'];
			// END: RECEIVE USER DATA
			
			// START: SAVE MATCH
			$date = date("y-m-d");
			$row = $dbMmatch->createRow();
			
			$row->opponent1 = $username2;
			$row->nativelang1 = $user2[0]['nativelang'];
			$row->opponent2 = $username1;
			$row->nativelang2 = $matchdata['nativelang'];
			$row->foreignlang = $matchdata['foreignlang'];
			$row->level = $matchdata['level'];
			$row->aborted = 0;
			$row->active = 1;
			$row->startdate = $date;
	
			if (!($id = $row->save()))
				$error ++;
			
			// END: SAVE MATCH
			
			// START: SAVE FB IDs TO ACTIVEMMATCHES TABLE
			$rows = $dbActiveMatches->createRow();
			
			$rows->match_id = $id;
			$rows->user_id1 = $matchdata['user_id'];
			$rows->user_id2 = $result[$randomOpponent]['user_id'];
			
			if (!$rows->save())
				$error ++;
			// END: SAVE User IDs TO ACTIVEMMATCHES TABLE
			
			// delete entry in randomMatch		
			$where = $db->getAdapter()->quoteInto('user_id=?', $result[$randomOpponent]['user_id']);
    	  	if (!$db->delete($where))
      			$error ++;
			
			// retrieve the words for this match
			// check if file already exists (should never be the case because match id is unique at the moment you accept the request)
			if(!file_exists('../tmp/mmatch/wordsForMatchId' . $id . '.txt')) {
				// logic for creating the list of words
					
				// first retrieve list of words
				$listOfWords = Application_Model_Words::retrieveWordsForMultiplyChoiceGame($matchdata['foreignlang'], $user2[0]['nativelang'], $matchdata['nativelang']);
				// TO DO: then write back the words to file, so both players get the same words
				Application_Model_Helper::createFileForMatch($id, json_encode($listOfWords), 2);
			}
			
			sendPush($user2[0]['devicetoken'], $username1 . ' fordert dich zu einem Match heraus. Schlund?');
			return $error ? "dberror-randpmMatch" : "success";
		}
	}
}
		