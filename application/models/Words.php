<?php

class Application_Model_Words {
	
	
	// 1st Minigame - MultiplyChoice
	
	public static function retrieveWordsForMultiplyChoiceGame() {
		
		
		// create 5 random ids for words
		//$randomIds = self::UniqueRandomNumbersWithinRange(0, 19, 20);
		
		$db = new Application_Model_DbTable_Words();
		$category = 1;
		
		
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
		$countType5 = 0;
		
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
					else if($key2 == 'type' && $value2 == '5')
						$countType5++;
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
		
		if($countType5 > 0) {
			$select6 = $db->getAdapter()->select()->from(array(
				'words' => 'words'), array('id'))
				->where('id NOT IN(?)',$ids)
				->where('category = ?', $category)
				->where('type = 5')
				//->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang .')<=10'))
				//->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang2 .')<=10'))
				->order(new Zend_Db_Expr('RAND()'))
				->limit(($countType5*3),0);	
				
				$wrongWordsType5 = $select6->query()->fetchAll();
		}
		
		// now merge all arrays 
		$counterType1 = 0;
		$counterType2 = 0;
		$counterType3 = 0;
		$counterType4 = 0;
		$counterType5 = 0;
		
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
					case '4':
						$wrongWords = array($wrongWordsType4[$counterType4],$wrongWordsType4[$counterType4+1], $wrongWordsType4[$counterType4+2]);
						$counterType4 += 3; 
						break;
					default:
						$wrongWords = array($wrongWordsType5[$counterType5],$wrongWordsType5[$counterType5+1], $wrongWordsType5[$counterType5+2]);
						$counterType5 += 3; 
						break;
			}

			$allWords[$i] = array_merge($correctWord,$wrongWords);
			 
			
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
	
	public static function retrieveWordsForWordCompletion() {
			
		$db = new Application_Model_DbTable_Words();
		$category = 1;
		
				$select = $db->getAdapter()->select()->from(array(
			'words' => 'words'
			),array('id'))
			->where('category = ?', $category)
			//->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang .')<=10'))
			//->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang2 .')<=10'))			
			->order(new Zend_Db_Expr('RAND()'))
			->limit(30,0);
			
			$correctWords = $select->query()->fetchAll();
			
			foreach ($correctWords as $key => $value) {
				$correctWords[$key][0] = $value['id'];
				unset($correctWords[$key]['id']);
			}
			
			return $correctWords;
			
		
	}
	
	// 3rd Game - Matrix Game
	public static function retrieveWordsForMatrixGame($foreignlang, $nativelang, $nativelang2) {
		
		// create 5 random ids for words
		//$randomIds = self::UniqueRandomNumbersWithinRange(0, 19, 20);
		
		$db = new Application_Model_DbTable_Words();
		$category = 1;
		
			$select = $db->getAdapter()->select()->from(array(
			'words' => 'words'
			),array('id','type'))
			->where('category = ?', $category)
			->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang .')<=13'))
			->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang2 .')<=13'))
			->order(new Zend_Db_Expr('RAND()'))
			->limit(30,0);
			
			
		$correctWords = $select->query()->fetchAll();
		
		$countType1 = 0;
		$countType2 = 0;
		$countType3 = 0;
		$countType4 = 0;
		$countType5 = 0;
		
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
					else if($key2 == 'type' && $value2 == '5')
						$countType5++;
				}
			}	
			// sql for the wrong words for type 1
		if($countType1 > 0) {
			$select2 = $db->getAdapter()->select()->from(array(
				'words' => 'words'), array('id'))
				->where('id NOT IN(?)',$ids)
				->where('category = ?', $category)
				->where('type = 1')
				->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang .')<=13'))
				->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang2 .')<=13'))
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
				->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang .')<=13'))
				->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang2 .')<=13'))
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
				->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang .')<=13'))
				->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang2 .')<=13'))
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
				->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang .')<=13'))
				->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang2 .')<=13'))
				->order(new Zend_Db_Expr('RAND()'))
				->limit(($countType4*3),0);	
				
				$wrongWordsType4 = $select5->query()->fetchAll();
		}
		
		if($countType5 > 0) {
			$select6 = $db->getAdapter()->select()->from(array(
				'words' => 'words'), array('id'))
				->where('id NOT IN(?)',$ids)
				->where('category = ?', $category)
				->where('type = 5')
				->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang .')<=13'))
				->where(new Zend_Db_Expr('CHAR_LENGTH(' . $nativelang2 .')<=13'))
				->order(new Zend_Db_Expr('RAND()'))
				->limit(($countType5*3),0);	
				
				$wrongWordsType5 = $select6->query()->fetchAll();
		}
		
		// now merge all arrays 
		$counterType1 = 0;
		$counterType2 = 0;
		$counterType3 = 0;
		$counterType4 = 0;
		$counterType5 = 0;
		
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
					case '4':
						$wrongWords = array($wrongWordsType4[$counterType4],$wrongWordsType4[$counterType4+1], $wrongWordsType4[$counterType4+2]);
						$counterType4 += 3; 
						break;
					default:
						$wrongWords = array($wrongWordsType5[$counterType5],$wrongWordsType5[$counterType5+1], $wrongWordsType5[$counterType5+2]);
						$counterType5 += 3; 
						break;
			}

			$allWords[$i] = array_merge($correctWord,$wrongWords);
			 
			
		}
		

		foreach ($allWords as $key => $value) {
			foreach ($value as $keys => $values) {
				$allWords[$key][$keys] = $values['id'];
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