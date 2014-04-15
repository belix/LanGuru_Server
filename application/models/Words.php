<?php

class Application_Model_Words {
	
	
	// 1st Minigame - MultiplyChoice
	
	public static function retrieveWordsForMultiplyChoiceGame() {
		
		
		// create 5 random ids for words
		//$randomIds = self::UniqueRandomNumbersWithinRange(0, 19, 20);
		
		$db = new Application_Model_DbTable_Words();
		$category = 1;
		
		if($nativelang != $nativelang2) {
				$select = $db->getAdapter()->select()->from(array(
			'words' => 'words'
			),array('id','type'))
			->where('category = ?', $category)
			//->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang .')<=10'))
			//->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang2 .')<=10'))
			->order(new Zend_Db_Expr('RAND()'))
			->limit(30,0);
			
		$correctWords = $select->query()->fetchAll();
		
		$countType1 = 0;
		$countType2 = 0;
		$countType3 = 0;
		$countType4 = 0;
		
		$ids = array();
			foreach($correctWords as $key => $value) {
				foreach($value as $key2 => $value2) {
					if($key2 == 'id')
						$ids[0][] = $value2;
					if($key2 == 'type' && $value2 == '1')
						$countType1++;
					else if($key2 == 'type' && $value2 == '2')
						$countType2++;
					else if($key2 == 'type' && $value2 == '3')
						$countType3++;
					else if($key2 == 'type' && $value2 == '4')
						$countType4++;
				}
			}	
			
			// sql for the wrong words for type 1
		if($countType1 > 0) {
			$select2 = $db->getAdapter()->select()->from(array(
				'words' => 'words'), array('id'))
				->where('id NOT IN(?)',$ids)
				->where('category = ?', $category)
				->where('type = 1')
				//->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang .')<=10'))
				//->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang2 .')<=10'))
				->order(new Zend_Db_Expr('RAND()'))
				->limit(($countType1*3),0);	
				
				$wrongWordsType1 = $select2->query()->fetchAll();
		}
		
		if($countType2 > 0) {
			$select3 = $db->getAdapter()->select()->from(array(
				'words' => 'words'), array('id'))
				->where('id NOT IN(?)',$ids)
				->where('category = ?', $category)
				->where('type = 2')
				//->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang .')<=10'))
				//->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang2 .')<=10'))
				->order(new Zend_Db_Expr('RAND()'))
				->limit(($countType2*3),0);	
				
				$wrongWordsType2 = $select3->query()->fetchAll();
		}
		
		if($countType3 > 0) {
			$select4 = $db->getAdapter()->select()->from(array(
				'words' => 'words'), array('id'))
				->where('id NOT IN(?)',$ids)
				->where('category = ?', $category)
				->where('type = 3')
				//->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang .')<=10'))
				//->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang2 .')<=10'))
				->order(new Zend_Db_Expr('RAND()'))
				->limit(($countType3*3),0);	
				
				$wrongWordsType3 = $select4->query()->fetchAll();
		}
		
		if($countType4 > 0) {
			$select5 = $db->getAdapter()->select()->from(array(
				'words' => 'words'), array('id'))
				->where('id NOT IN(?)',$ids)
				->where('category = ?', $category)
				->where('type = 4')
				//->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang .')<=10'))
				//->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang2 .')<=10'))
				->order(new Zend_Db_Expr('RAND()'))
				->limit(($countType4*3),0);	
				
				$wrongWordsType4 = $select5->query()->fetchAll();
		}
		
		// now merge all arrays 
		$counterType1 = 0;
		$counterType2 = 0;
		$counterType3 = 0;
		$counterType4 = 0;
		
		$allWords = array();
		
		for($i = 0; $i < 30; $i++) {
			$currentType = $correctWords[$i]['type'];
			
			$correctWord = array($correctWords[$i]);
			
			
			switch ($currentType) {
					case '1':
						$wrongWords = array($wrongWordsType1[$counterType1],$wrongWordsType1[$counterType1+1], $wrongWordsType1[$counterType1+2]);
						$counterType1 += 3; 
						break;
					case '2':
						$wrongWords = array($wrongWordsType2[$counterType2],$wrongWordsType2[$counterType2+1], $wrongWordsType2[$counterType2+2]);
						$counterType2 += 3; 
						break;
					case '3':
						$wrongWords = array($wrongWordsType3[$counterType3],$wrongWordsType3[$counterType3+1], $wrongWordsType3[$counterType3+2]);
						$counterType3 += 3; 
						break;
					default:
						$wrongWords = array($wrongWordsType4[$counterType4],$wrongWordsType4[$counterType4+1], $wrongWordsType4[$counterType4+2]);
						$counterType4 += 3; 
						break;
			}

			$allWords[$i] = array_merge($correctWord,$wrongWords);
			 
			
		}
		
		}

		else {
			$select = $db->getAdapter()->select()->from(array(
			'words' => 'words'
			),array('id','type'))
			->where('category = ?', $category)
			//->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang .')<=10'))
			->order(new Zend_Db_Expr('RAND()'))
			->limit(30,0);
			
		$correctWords = $select->query()->fetchAll();
		
		$countType1 = 0;
		$countType2 = 0;
		$countType3 = 0;
		$countType4 = 0;
		
		$ids = array();
			foreach($correctWords as $key => $value) {
				foreach($value as $key2 => $value2) {
					if($key2 == 'id')
						$ids[0][] = $value2;
					if($key2 == 'type' && $value2 == '1')
						$countType1++;
					else if($key2 == 'type' && $value2 == '2')
						$countType2++;
					else if($key2 == 'type' && $value2 == '3')
						$countType3++;
					else if($key2 == 'type' && $value2 == '4')
						$countType4++;
				}
			}	
			
			// sql for the wrong words for type 1
		if($countType1 > 0) {
			$select2 = $db->getAdapter()->select()->from(array(
				'words' => 'words'), array('id'))
				->where('id NOT IN(?)',$ids)
				->where('category = ?', $category)
				->where('type = 1')
				//->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang .')<=10'))
				->order(new Zend_Db_Expr('RAND()'))
				->limit(($countType1*3),0);	
				
				$wrongWordsType1 = $select2->query()->fetchAll();
		}
		
		if($countType2 > 0) {
			$select3 = $db->getAdapter()->select()->from(array(
				'words' => 'words'), array('id'))
				->where('id NOT IN(?)',$ids)
				->where('category = ?', $category)
				->where('type = 2')
				//->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang .')<=10'))
				->order(new Zend_Db_Expr('RAND()'))
				->limit(($countType2*3),0);	
				
				$wrongWordsType2 = $select3->query()->fetchAll();
		}
		
		if($countType3 > 0) {
			$select4 = $db->getAdapter()->select()->from(array(
				'words' => 'words'), array('id'))
				->where('id NOT IN(?)',$ids)
				->where('category = ?', $category)
				->where('type = 3')
				//->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang .')<=10'))
				->order(new Zend_Db_Expr('RAND()'))
				->limit(($countType3*3),0);	
				
				$wrongWordsType3 = $select4->query()->fetchAll();
		}
		
		if($countType4 > 0) {
			$select5 = $db->getAdapter()->select()->from(array(
				'words' => 'words'), array('id'))
				->where('id NOT IN(?)',$ids)
				->where('category = ?', $category)
				->where('type = 4')
				//->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang .')<=10'))
				->order(new Zend_Db_Expr('RAND()'))
				->limit(($countType4*3),0);	
				
				$wrongWordsType4 = $select5->query()->fetchAll();
		}
		
		// now merge all arrays 
		$counterType1 = 0;
		$counterType2 = 0;
		$counterType3 = 0;
		$counterType4 = 0;
		
		$allWords = array();
		
		for($i = 0; $i < 30; $i++) {
			$currentType = $correctWords[$i]['type'];
			
			$correctWord = array($correctWords[$i]);
			
			
			switch ($currentType) {
					case '1':
						$wrongWords = array($wrongWordsType1[$counterType1],$wrongWordsType1[$counterType1+1], $wrongWordsType1[$counterType1+2]);
						$counterType1 += 3; 
						break;
					case '2':
						$wrongWords = array($wrongWordsType2[$counterType2],$wrongWordsType2[$counterType2+1], $wrongWordsType2[$counterType2+2]);
						$counterType2 += 3; 
						break;
					case '3':
						$wrongWords = array($wrongWordsType3[$counterType3],$wrongWordsType3[$counterType3+1], $wrongWordsType3[$counterType3+2]);
						$counterType3 += 3; 
						break;
					default:
						$wrongWords = array($wrongWordsType4[$counterType4],$wrongWordsType4[$counterType4+1], $wrongWordsType4[$counterType4+2]);
						$counterType4 += 3; 
						break;
			}

			$allWords[$i] = array_merge($correctWord,$wrongWords);
			 
			
		}
		}

		foreach ($allWords as $key => $value) {
			//unset($allWords[$key][0]['id']);
			unset($allWords[$key][0]['type']);
		}
		
		foreach ($allWords as $key => $value) {
			foreach ($value as $keys => $values) {
				$allWords[$key][$keys] = $values['id'];
			}
		}
		
		return $allWords;
			
	}
	
	// 2nd Minigame - Word Completion 
	
	public static function retrieveWordsForWordCompletion($foreignlang, $nativelang, $nativelang2) {
			
		$db = new Application_Model_DbTable_Words();
		$category = 1;
		
		if($nativelang != $nativelang2) {
				$select = $db->getAdapter()->select()->from(array(
			'words' => 'words'
			),array('id'))
			->where('category = ?', $category)
			->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang .')<=10'))
			->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang2 .')<=10'))			
			->order(new Zend_Db_Expr('RAND()'))
			->limit(30,0);
			
			$correctWords = $select->query()->fetchAll();
			
			// generate random strings and put it into the array depending on the nativelanguage
			// TODO: Länge des Strings bis dato auf UTF-8 optimiert, da sonst die Umlaute falsch berechnet werden, muss man evtl. bei anderen Sprachen anpassen.
			// TODO: Generelles Sprachenproblem, die random Characters müssen abhängig von der Sprache generiert werden, da es in jeder Sprache andere Buchstaben gibt!
			/*
			foreach ($correctWords as $key => $value) {
				
				if ($value[$nativelang]) {
					if (mb_strlen($value[$nativelang], 'UTF-8') == 15)
						$correctWords[$key]['wrongchars' . $nativelang] = "BOBSCHLUNDSTUDIOS";
					else {
							$charCount = mb_strlen($value[$nativelang], 'UTF-8');
							$calc = floor(0.4 * $charCount);
							
							$getDifference = 15 - $charCount;
							$seed = str_split('abcdefghijklmnopqrstuvwxyz'); // and any other characters
						    shuffle($seed); // probably optional since array_is randomized; this may be redundant
						    $rand = '';
						    foreach (array_rand($seed, $getDifference) as $k) $rand .= $seed[$k];
						 	
							if ($calc > 1)
								$charPositions = array_rand(array_fill(1, $charCount, true), $calc);
							else
								$charPositions[0] = array_rand(array_fill(1, $charCount, true), $calc);
						
					 		$correctWords[$key]['charpositions' . $nativelang] = $charPositions;
							$correctWords[$key]['wrongchars' . $nativelang] = $rand;
						}
				} 
				if ($value[$nativelang2]) {
				
					if (mb_strlen($value[$nativelang2], 'UTF-8') == 15)
						$correctWords[$key]['wrongchars2' . $nativelang2] = "BOBSCHLUNDSTUDIOS";
					else {
							$charCount = mb_strlen($value[$nativelang], 'UTF-8');
							$calc = floor(0.4 * $charCount);
							
							$getDifference = 15 - $charCount;
							$seed = str_split('abcdefghijklmnopqrstuvwxyz'); // and any other characters
						    shuffle($seed); // probably optional since array_is randomized; this may be redundant
						    $rand = '';
						    foreach (array_rand($seed, $getDifference) as $k) $rand .= $seed[$k];
							
							if ($calc > 1)
								$charPositions = array_rand(array_fill(1, $charCount, true), $calc);
							else
								$charPositions[0] = array_rand(array_fill(1, $charCount, true), $calc);
						
					 		$correctWords[$key]['charpositions' . $nativelang2] = $charPositions;
							$correctWords[$key]['wrongchars' . $nativelang2] = $rand;
						}
				}
			}*/
			
			foreach ($correctWords as $key => $value) {
				$correctWords[$key] = $value['id'];
			}
			
			return $correctWords;
			
		}
		
		else {
			$select = $db->getAdapter()->select()->from(array(
			'words' => 'words'
			),array('id'))
			->where('category = ?', $category)
			->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang .')<=10'))
			->order(new Zend_Db_Expr('RAND()'))
			->limit(30,0);
			
			$correctWords = $select->query()->fetchAll();
			
			// generate random strings and put it into the array depending on the nativelanguage
			// TODO: Länge des Strings bis dato auf UTF-8 optimiert, da sonst die Umlaute falsch berechnet werden, muss man evtl. bei anderen Sprachen anpassen.
			// TODO: Generelles Sprachenproblem, die random Characters müssen abhängig von der Sprache generiert werden, da es in jeder Sprache andere Buchstaben gibt!
			/*
			foreach ($correctWords as $key => $value) {
				if (mb_strlen($value[$nativelang], 'UTF-8') == 15)
					$correctWords[$key]['wrongchars' . $nativelang] = "BOBSCHLUNDSTUDIOS";
				else {
						$charCount = mb_strlen($value[$nativelang], 'UTF-8');
						$calc = floor(0.4 * $charCount);

						$getDifference = 15 - $charCount;
						$seed = str_split('abcdefghijklmnopqrstuvwxyz'); // and any other characters
					    shuffle($seed); // probably optional since array_is randomized; this may be redundant
					    $rand = '';
					    foreach (array_rand($seed, $getDifference) as $k) $rand .= $seed[$k];
						
						if ($calc > 1)
							$charPositions = array_rand(array_fill(1, $charCount, true), $calc);
						else
							$charPositions[0] = array_rand(array_fill(1, $charCount, true), $calc);
						
					 	$correctWords[$key]['charpositions' . $nativelang] = $charPositions;
						$correctWords[$key]['wrongchars' . $nativelang] = $rand;
					}
			}*/
			
			foreach ($correctWords as $key => $value) {
				$correctWords[$key] = $value['id'];
			}
			
			return $correctWords;
		}
	}
	
	// 3rd Game - Matrix Game
	public static function retrieveWordsForMatrixGame($foreignlang, $nativelang, $nativelang2) {
		
		// create 5 random ids for words
		//$randomIds = self::UniqueRandomNumbersWithinRange(0, 19, 20);
		
		$db = new Application_Model_DbTable_Words();
		$category = 1;
		
		if($nativelang != $nativelang2) {
				$select = $db->getAdapter()->select()->from(array(
			'words' => 'words'
			),array('id','type'))
			->where('category = ?', $category)
			->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang .')<=10'))
			->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang2 .')<=10'))
			->order(new Zend_Db_Expr('RAND()'))
			->limit(30,0);
			
			
		$correctWords = $select->query()->fetchAll();
		
		$countType1 = 0;
		$countType2 = 0;
		$countType3 = 0;
		$countType4 = 0;
		
		$ids = array();
			foreach($correctWords as $key => $value) {
				foreach($value as $key2 => $value2) {
					if($key2 == 'id')
						$ids[0][] = $value2;
					if($key2 == 'type' && $value2 == '1')
						$countType1++;
					else if($key2 == 'type' && $value2 == '2')
						$countType2++;
					else if($key2 == 'type' && $value2 == '3')
						$countType3++;
					else if($key2 == 'type' && $value2 == '4')
						$countType4++;
				}
			}	
			Zend_Debug::dump($ids);
			// sql for the wrong words for type 1
		if($countType1 > 0) {
			$select2 = $db->getAdapter()->select()->from(array(
				'words' => 'words'), array('id'))
				->where('id NOT IN(?)',$ids)
				->where('category = ?', $category)
				->where('type = 1')
				->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang .')<=10'))
				->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang2 .')<=10'))
				->order(new Zend_Db_Expr('RAND()'))
				->limit(($countType1*3),0);	
				
				$wrongWordsType1 = $select2->query()->fetchAll();
		}
		
		if($countType2 > 0) {
			$select3 = $db->getAdapter()->select()->from(array(
				'words' => 'words'), array('id'))
				->where('id NOT IN(?)',$ids)
				->where('category = ?', $category)
				->where('type = 2')
				->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang .')<=10'))
				->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang2 .')<=10'))
				->order(new Zend_Db_Expr('RAND()'))
				->limit(($countType2*3),0);	
				
				$wrongWordsType2 = $select3->query()->fetchAll();
		}
		
		if($countType3 > 0) {
			$select4 = $db->getAdapter()->select()->from(array(
				'words' => 'words'), array('id'))
				->where('id NOT IN(?)',$ids)
				->where('category = ?', $category)
				->where('type = 3')
				->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang .')<=10'))
				->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang2 .')<=10'))
				->order(new Zend_Db_Expr('RAND()'))
				->limit(($countType3*3),0);	
				
				$wrongWordsType3 = $select4->query()->fetchAll();
		}
		
		if($countType4 > 0) {
			$select5 = $db->getAdapter()->select()->from(array(
				'words' => 'words'), array('id'))
				->where('id NOT IN(?)',$ids)
				->where('category = ?', $category)
				->where('type = 4')
				->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang .')<=10'))
				->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang2 .')<=10'))
				->order(new Zend_Db_Expr('RAND()'))
				->limit(($countType4*3),0);	
				
				$wrongWordsType4 = $select5->query()->fetchAll();
		}
		
		// now merge all arrays 
		$counterType1 = 0;
		$counterType2 = 0;
		$counterType3 = 0;
		$counterType4 = 0;
		
		$allWords = array();
		
		for($i = 0; $i < 30; $i++) {
			$currentType = $correctWords[$i]['type'];
			
			$correctWord = array(array('id' => $correctWords[$i]['id']));
			
			switch ($currentType) {
					case '1':
						$wrongWords = array($wrongWordsType1[$counterType1],$wrongWordsType1[$counterType1+1], $wrongWordsType1[$counterType1+2]);
						$counterType1 += 3; 
						break;
					case '2':
						$wrongWords = array($wrongWordsType2[$counterType2],$wrongWordsType2[$counterType2+1], $wrongWordsType2[$counterType2+2]);
						$counterType2 += 3; 
						break;
					case '3':
						$wrongWords = array($wrongWordsType3[$counterType3],$wrongWordsType3[$counterType3+1], $wrongWordsType3[$counterType3+2]);
						$counterType3 += 3; 
						break;
					default:
						$wrongWords = array($wrongWordsType4[$counterType4],$wrongWordsType4[$counterType4+1], $wrongWordsType4[$counterType4+2]);
						$counterType4 += 3; 
						break;
			}

			$allWords[$i] = array_merge($correctWord,$wrongWords);
			 
			
		}
		
		}

		else {
			$select = $db->getAdapter()->select()->from(array(
			'words' => 'words'
			),array('id','type'))
			->where('category = ?', $category)
			->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang .')<=10'))
			->order(new Zend_Db_Expr('RAND()'))
			->limit(30,0);
			
			
			
		$correctWords = $select->query()->fetchAll();
		
		$countType1 = 0;
		$countType2 = 0;
		$countType3 = 0;
		$countType4 = 0;
		
		$ids = array();
			foreach($correctWords as $key => $value) {
				foreach($value as $key2 => $value2) {
					if($key2 == 'id')
						$ids[0][] = $value2;
					if($key2 == 'type' && $value2 == '1')
						$countType1++;
					else if($key2 == 'type' && $value2 == '2')
						$countType2++;
					else if($key2 == 'type' && $value2 == '3')
						$countType3++;
					else if($key2 == 'type' && $value2 == '4')
						$countType4++;
				}
			}	
			
			// sql for the wrong words for type 1
		if($countType1 > 0) {
			$select2 = $db->getAdapter()->select()->from(array(
				'words' => 'words'), array('id'))
				->where('id NOT IN(?)',$ids)
				->where('category = ?', $category)
				->where('type = 1')
				->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang .')<=10'))
				->order(new Zend_Db_Expr('RAND()'))
				->limit(($countType1*3),0);	
				
				$wrongWordsType1 = $select2->query()->fetchAll();
		}
		
		if($countType2 > 0) {
			$select3 = $db->getAdapter()->select()->from(array(
				'words' => 'words'), array('id'))
				->where('id NOT IN(?)',$ids)
				->where('category = ?', $category)
				->where('type = 2')
				->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang .')<=10'))
				->order(new Zend_Db_Expr('RAND()'))
				->limit(($countType2*3),0);	
				
				$wrongWordsType2 = $select3->query()->fetchAll();
		}
		
		if($countType3 > 0) {
			$select4 = $db->getAdapter()->select()->from(array(
				'words' => 'words'), array('id'))
				->where('id NOT IN(?)',$ids)
				->where('category = ?', $category)
				->where('type = 3')			
				->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang .')<=10'))
				->order(new Zend_Db_Expr('RAND()'))
				->limit(($countType3*3),0);	
				
				$wrongWordsType3 = $select4->query()->fetchAll();
		}
		
		if($countType4 > 0) {
			$select5 = $db->getAdapter()->select()->from(array(
				'words' => 'words'), array('id'))
				->where('id NOT IN(?)',$ids)
				->where('category = ?', $category)
				->where('type = 4')
				->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang .')<=10'))
				->order(new Zend_Db_Expr('RAND()'))
				->limit(($countType4*3),0);	
				
				$wrongWordsType4 = $select5->query()->fetchAll();
		}
		
		// now merge all arrays 
		$counterType1 = 0;
		$counterType2 = 0;
		$counterType3 = 0;
		$counterType4 = 0;
		
		$allWords = array();
		
		for($i = 0; $i < 30; $i++) {
			$currentType = $correctWords[$i]['type'];
			
			$correctWord = array(array('id' => $correctWords[$i]['id']));
			
			
			switch ($currentType) {
					case '1':
						$wrongWords = array($wrongWordsType1[$counterType1],$wrongWordsType1[$counterType1+1], $wrongWordsType1[$counterType1+2]);
						$counterType1 += 3; 
						break;
					case '2':
						$wrongWords = array($wrongWordsType2[$counterType2],$wrongWordsType2[$counterType2+1], $wrongWordsType2[$counterType2+2]);
						$counterType2 += 3; 
						break;
					case '3':
						$wrongWords = array($wrongWordsType3[$counterType3],$wrongWordsType3[$counterType3+1], $wrongWordsType3[$counterType3+2]);
						$counterType3 += 3; 
						break;
					default:
						$wrongWords = array($wrongWordsType4[$counterType4],$wrongWordsType4[$counterType4+1], $wrongWordsType4[$counterType4+2]);
						$counterType4 += 3; 
						break;
			}

			$allWords[$i] = array_merge($correctWord,$wrongWords);
			 
			
		}
		}
		
		// iterate through array and add a random number to each wordpair
		foreach ($allWords as $key => $value) {
			$value[] = rand(1, 10);
			$matrix[] = $value;
		}

		return $matrix;
			
	}

	public static function UniqueRandomNumbersWithinRange($min, $max, $quantity) {
	    $numbers = range($min, $max);
	    shuffle($numbers);
    	return array_slice($numbers, 0, $quantity);
	}
	
	
	/**
	 * Core Data Test Method
	 * 
	 */
	 public static function getAllWords()
	 {
	 	$db = new Application_Model_DbTable_Words();
		
		$select = $db->getAdapter()->select()->from(array(
			'words' => 'words'
		),array('*'))
		;
			
		$words = $select->query()->fetchAll();
		
		return $words;
	 }
	
	


}

?>