<?php


class MatchmakingController extends Zend_Controller_Action
{
	private static $RSeed = 0;
	
	public function init() {
			$this->_helper->layout()->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);
			
	}
	
	
	
	public function indexAction()
	{
		
		
		
	}
	
	/* 
	 * check if username already exists
	 * */
	 
	public function findOpponentAction() {
		$SEMKey = "123456";
	  ## Get Semaphore id
	  $seg = sem_get($SEMKey, 1, 0666, -1);
	  sem_acquire($seg);
	  
		$request = $this->getRequest();
			
			
		if ($request->isPost()) {
			// get the json raw data
			$handle = fopen("php://input", "rb");
			$http_raw_post_data = '';
			
			while (!feof($handle)) {
			    $http_raw_post_data .= fread($handle, 8192);
			}
			fclose($handle); 
			
			// convert it to a php array
			$json_data = json_decode($http_raw_post_data, true);
					
			$enemyIsAvailable = Application_Model_Matchmaking::findOpponent($json_data);
			
			if($enemyIsAvailable) {
				// continue
				echo $enemyIsAvailable;
			}
			
			else {
				// username exists already
				//echo "kein gegner vorhanden";
			}
		}
		
		sem_release($seg);
	}
	
	public function challengeFriendAction() {
		$request = $this->getRequest();
			
			
		if ($request->isPost()) {
			// get the json raw data
			$handle = fopen("php://input", "rb");
			$http_raw_post_data = '';
			
			while (!feof($handle)) {
			    $http_raw_post_data .= fread($handle, 8192);
			}
			fclose($handle); 
			
			// convert it to a php array
			$json_data = json_decode($http_raw_post_data, true);
					
			$challenge = Application_Model_Matchmaking::challengeFriend($json_data);
			
			echo $challenge;
		}
	}
	
	public function friendMatchmakingExistsAction() {
		$request = $this->getRequest();
			
			
		if ($request->isPost()) {
			// get the json raw data
			$handle = fopen("php://input", "rb");
			$http_raw_post_data = '';
			
			while (!feof($handle)) {
			    $http_raw_post_data .= fread($handle, 8192);
			}
			fclose($handle); 
			
			// convert it to a php array
			$json_data = json_decode($http_raw_post_data, true);
					
			$challenge = Application_Model_Matchmaking::friendMatchmakingExists($json_data);
			
			echo $challenge;
		}
	}
	
	public function pingFriendChallengeRequestAction() {
		$request = $this->getRequest();
			
			
		if ($request->isPost()) {
			// get the json raw data
			$handle = fopen("php://input", "rb");
			$http_raw_post_data = '';
			
			while (!feof($handle)) {
			    $http_raw_post_data .= fread($handle, 8192);
			}
			fclose($handle); 
			
			// convert it to a php array
			$json_data = json_decode($http_raw_post_data, true);
					
			$challenge = Application_Model_Matchmaking::pingFriendChallengeRequest($json_data);
			
			echo $challenge;
		}
	}
	// function to send all the data to finish a match like score, winner etc.
	public function finishMatchAction() {
		$request = $this->getRequest();
			
			
		if ($request->isPost()) {
			// get the json raw data
			$handle = fopen("php://input", "rb");
			$http_raw_post_data = '';
			
			while (!feof($handle)) {
			    $http_raw_post_data .= fread($handle, 8192);
			}
			fclose($handle); 
			
			// convert it to a php array
			$json_data = json_decode($http_raw_post_data, true);
			
			$error = Application_Model_Match::finishMatch($json_data);
			
			
		}
	
	}
	
	
	// this method should be called when the game is over 
	public function closeMatchAction(){
		
		$request = $this->getRequest();
			
			
		if ($request->isPost()) {
			// get the json raw data
			$handle = fopen("php://input", "rb");
			$http_raw_post_data = '';
			
			while (!feof($handle)) {
			    $http_raw_post_data .= fread($handle, 8192);
			}
			fclose($handle); 
			
			// convert it to a php array
			$json_data = json_decode($http_raw_post_data, true);
			
			$error = Application_Model_Match::closeMatch($json_data);
		}
	}
	
	
	// happens if you dont find an enemy
	public function removePlayerFromMatchmakingAction() {
		$request = $this->getRequest();
			
			
		if ($request->isPost()) {
			// get the json raw data
			$handle = fopen("php://input", "rb");
			$http_raw_post_data = '';
			
			while (!feof($handle)) {
			    $http_raw_post_data .= fread($handle, 8192);
			}
			fclose($handle); 
			
			// convert it to a php array
			$json_data = json_decode($http_raw_post_data, true);
			
			$error = Application_Model_Matchmaking::removePlayerFromMatchmaking($json_data);
			
			echo $error;
		}
		
	}
	
	// function called when you closes the app and is ingame => abort the match
	public function abortMatchAction() {
		$request = $this->getRequest();
			
			
		if ($request->isPost()) {
			// get the json raw data
			$handle = fopen("php://input", "rb");
			$http_raw_post_data = '';
			
			while (!feof($handle)) {
			    $http_raw_post_data .= fread($handle, 8192);
			}
			fclose($handle); 
			
			// convert it to a php array
			$json_data = json_decode($http_raw_post_data, true);
			
			$error = Application_Model_Match::abortMatch($json_data);
			
			echo $error;
		}
	}
	
	// function called every second to update and get user scores
	public function pingServerAction() {
		$request = $this->getRequest();
			
			
		if ($request->isPost()) {
			// get the json raw data
			$handle = fopen("php://input", "rb");
			$http_raw_post_data = '';
			
			while (!feof($handle)) {
			    $http_raw_post_data .= fread($handle, 8192);
			}
			fclose($handle); 
			
			// convert it to a php array
			$json_data = json_decode($http_raw_post_data, true);
			
			$result = Application_Model_Match::pingServer($json_data);
		}
	}
	
	public function rankingAction() {
		$request = $this->getRequest();
			
			
		if ($request->isPost()) {
			// get the json raw data
			$handle = fopen("php://input", "rb");
			$http_raw_post_data = '';
			
			while (!feof($handle)) {
			    $http_raw_post_data .= fread($handle, 8192);
			}
			fclose($handle); 
			
			// convert it to a php array
			$json_data = json_decode($http_raw_post_data, true);
			
			$result = Application_Model_User::getOverallRanking($json_data);
			
			echo $result;
		}
	}
	
	public function testAction() {

		//$test = Application_Model_Words::retrieveWordsForMatrixGame('EN', 'DE', 'DE');
		//$test['id'] = 138;
		//Zend_Debug::dump(Application_Model_Match::abortMatch($test));
	} 
	
	
	public function getwordsAction() {
		$words = Application_Model_Words::getAllWords();
		echo Zend_Json::encode(array('response' => $words));
	} 
	
	
	public function wordsAction() {
		$my_file = '../tmp/datei3.txt';
		//$data = fread($handle,filesize($my_file));
		$handle = fopen('../tmp/datei3.txt', "r") or die("Couldn't get handle");

		if ($handle) {
			$counterLong = 0;
			$counter = 0;
			
		    while (!feof($handle)) {
		    	$counterLong++;
		    	
		        $buffer = fgets($handle, 4096);
		        // Process buffer here.. initialize
		        $string = trim((string)$buffer);
		        $array = explode("\n", $string);
		        $newArray = array();
				foreach($array as $key => $value) {
					$newArray[] = explode("\t", $value);
				}
				
				foreach($newArray as $key => $value) {
					foreach($value as $key2 => $value2) {
						if ($key2 == 0 && strpos($value2, '{')) {
							$substring = trim(substr($value2, 0, strpos($value2, '{')));
							$newArray[$key][0] = $substring;
						}
						elseif ($key2 == 1 && strpos($value2, '[')) {
							$substring = trim(substr($value2, 0, strpos($value2, '[')));
							$newArray[$key][1] = $substring;
						}
						
						elseif ($key2 == 2) {
							switch (trim($value2)) {
								case 'noun':
									$newArray[$key][2] = 1;
									break;
								case 'verb':
									$newArray[$key][2] = 2;
									break;
								case 'adj':
									$newArray[$key][2] = 3;
									break;
								default:
									$newArray[$key][2] = 4;
									break;
							}
						}
					}
				}
				
				// part to get only the top 3 for the same value
				foreach ($newArray as $key => $value) {
		
					foreach($value as $key2 => $value2) {
						
						if($key2 == 0) {
							//echo $value2;
							// if the value is the same as the last increase the counter and continue
							if($tempWord == $value2 && $counter != 0) {
								$counter += 1;
								
							}
							
							// if the value is still the same as last and counter is greater then 3 then start and continue to unset the values until new word
							else if($tempWord == $value2 && $counter >= 0) {
								//echo 1;
								unset($newArray);
							}
							
							// new word, reset the counter
							else if($tempWord != $value2) {
								$counter = 0;
								$tempWord = NULL;
								
							}
							
							// set the new value into tempWord
							$tempWord = $value2;
						}
						
					}
				}
				
				
				// now write back to database
				
				$db = new Application_Model_DbTable_Words();
				if(is_array($newArray))	{	
					foreach ($newArray as $key => $value) {
						$row = $db->createRow();
						if($value[2] != NULL)
							$row->type = $value[2];
						if($value[0] != NULL)
							$row->DE = $value[0];
						if($value[1] != NULL)
							$row->EN = $value[1];
			
						if (!$row->save())
							$error ++;
					}
				}
				
				//Zend_Debug::dump($newArray);
				
				//if($counterLong == 30)
					//break;
		    }
		    fclose($handle);
		}
		/*

		
		
		
		
		// part to get only the top 3 for the same value
		$counter = 0;
		$tempWord = NULL;
		foreach ($newArray as $key => $value) {

			foreach($value as $key2 => $value2) {
				
				if($key2 == 0) {
					//echo $value2;
					// if the value is the same as the last increase the counter and continue
					if($tempWord == $value2 && $counter != 2) {
						$counter += 1;
						
					}
					
					// if the value is still the same as last and counter is greater then 3 then start and continue to unset the values until new word
					else if($tempWord == $value2 && $counter >= 2) {
						//echo 1;
						unset($newArray[$key]);
					}
					
					// new word, reset the counter
					else if($tempWord != $value2) {
						$counter = 0;
						
					}
					
					// set the new value into tempWord
					$tempWord = $value2;
				}
				
			}
		}
		//Zend_Debug::dump($newArray);
		
		
		$db = new Application_Model_DbTable_Words();
		/*
					
		foreach ($newArray as $key => $value) {
			$row = $db->createRow();
			$row->type = $value[2];
			$row->DE = $value[0];
			$row->EN = $value[1];

			if (!$row->save())
				$error ++;
		}
		*/
	}

// set seed
	public static function seed($s = 0) {
		self::$RSeed = abs(intval($s)) % 9999999 + 1;
		self::num();
		
	}
	
	
	// generate random number
	public static function num($min = 0, $max = 9999999) {
		if (self::$RSeed == 0) self::seed(mt_rand());
		self::$RSeed = (self::$RSeed * 125) % 2796203;
		return self::$RSeed % ($max - $min + 1) + $min;
	}
	
	
	
}


/*
		$data = file_get_contents($my_file, true);
		$string = (string)$data;
		$array = explode("\n", $string);
		$newArray = array();
		foreach($array as $key => $value) {
			$newArray[] = explode("\t", $value);
		}
		
		foreach($newArray as $key => $value) {
			foreach($value as $key2 => $value2) {
				if ($key2 == 0 && strpos($value2, '{')) {
					$substring = trim(substr($value2, 0, strpos($value2, '{')));
					$newArray[$key][0] = $substring;
				}
				elseif ($key2 == 1 && strpos($value2, '[')) {
					$substring = trim(substr($value2, 0, strpos($value2, '[')));
					$newArray[$key][1] = $substring;
				}
				elseif ($key2 == 2) {
					switch (trim($value2)) {
						case 'noun':
							$newArray[$key][2] = 0;
							break;
						case 'verb':
							$newArray[$key][2] = 1;
							break;
						case 'adj':
							$newArray[$key][2] = 2;
							break;
						default:
							$newArray[$key][2] = "unknown";
							break;
					}
				}
			}
		}
		//Zend_Debug::dump($newArray);
		
		
		
		// part to get only the top 3 for the same value
		$counter = 0;
		$tempWord = NULL;
		foreach ($newArray as $key => $value) {

			foreach($value as $key2 => $value2) {
				
				if($key2 == 0) {
					//echo $value2;
					// if the value is the same as the last increase the counter and continue
					if($tempWord == $value2 && $counter != 2) {
						$counter += 1;
						
					}
					
					// if the value is still the same as last and counter is greater then 3 then start and continue to unset the values until new word
					else if($tempWord == $value2 && $counter >= 2) {
						//echo 1;
						unset($newArray[$key]);
					}
					
					// new word, reset the counter
					else if($tempWord != $value2) {
						$counter = 0;
						
					}
					
					// set the new value into tempWord
					$tempWord = $value2;
				}
				
			}
		}
		//Zend_Debug::dump($newArray);
		
		
		$db = new Application_Model_DbTable_Words();
		/*
					
		foreach ($newArray as $key => $value) {
			$row = $db->createRow();
			$row->type = $value[2];
			$row->DE = $value[0];
			$row->EN = $value[1];

			if (!$row->save())
				$error ++;
		}
		*/
