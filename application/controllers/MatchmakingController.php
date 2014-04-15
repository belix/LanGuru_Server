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
	
	public function testAction() {
		
		$path = "https://www.youtube.com/annotations_invideo?features=1&legacy=1&video_id=k009d7bmkOc";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$path);
		curl_setopt($ch, CURLOPT_FAILONERROR,1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		$retValue = htmlspecialchars(curl_exec($ch));			 
		curl_close($ch);

		$retValue = str_replace("amp;", "", $retValue);
		echo "<pre>";
		echo $retValue;
		echo "</pre>";
		//Zend_Debug::dump($retValue);
		
		/*$array = array('0' => '1405521504', '1' => '1279436137');
		$test = Application_Model_Mmatch::getFriendDetails($array);
		
		Zend_Debug::dump($test);

		$test = Application_Model_Words::retrieveWordsForWordCompletion('EN', 'DE', 'FR');

		Zend_Debug::dump($test);*/
		/*$bla[0] = array_rand(array_fill(1, 10, true), 1);
		Zend_Debug::dump($bla);*/
		
		/*$to_be_translated = "apple";
		
		$request = 'https://www.googleapis.com/language/translate/v2?key=&source=en&target=es&q='.$to_be_translated;
		$response = file_get_contents($request); Zend_Debug::dump($response);
		$data = json_decode($response); Zend_Debug::dump($data);
		$translated = $data->translations->translatedText;*/
		
		//$path = "https://www.youtube.com/my_videos_annotate?v=35XbHN7CNBY";
		
		/*$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$path);
		curl_setopt($ch, CURLOPT_FAILONERROR,1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		$retValue = curl_exec($ch);			 
		curl_close($ch);*/
		//return $retValue;
		//$retValue = str_replace("amp;", "", $retValue);
		//$val = substr($retValue, 65);
		/*
		$ch = curl_init();    
curl_setopt($ch, CURLOPT_URL, $path); // set url
curl_setopt($ch, CURLOPT_FAILONERROR,1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6"); // set browser/user agent    
//curl_setopt($ch, CURLOPT_HEADERFUNCTION, 'read_header'); // get header
$retValue = curl_exec($ch);

		//echo "<pre>";
		Zend_Debug::dump($retValue);*/
		//echo "</pre>";
		
		//Zend_Debug::dump($retValue);
		
		//echo json_encode($matrix);
		
		//Zend_Debug::dump(json_encode($matrix));
		//srand(5);
		// set seed
		//self::seed(42);
		
		// echo 10 numbers between 1 and 100
		//for ($i = 0; $i < 10; $i++) {
			//echo self::num(1, 100) . '<br />';
		//}
		//Zend_Debug::dump($matrix);
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
