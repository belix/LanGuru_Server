<?php

class Application_Model_Helper {
	
	// Helper to eliminate specific keys from array
	public static function eliminateKeyFromArray($specificKey, $array) {
		$neuesArray = array();
		
		$dimCount = self::countdim($array);
		
		switch($dimCount){
			case 2:
			foreach($array as $key => $value) {
			   
			 if (!isset($neuesArray[$key])) {
			  	$neuesArray[$key] = array();
			 }
			   
			 foreach ($value as $key2 => $value2) {
			    
			  if ($key2 != $specificKey) {
			   	$neuesArray[$key][$key2]= $value2;
			  }
			  
			 }
			}
			
			return $neuesArray;
			break;
			
			
			case 3:
			foreach($array as $key => $value) {
			   
			 if (!isset($neuesArray[$key])) {
			  	$neuesArray[$key] = array();
			 }
			   
			 foreach ($value as $key2 => $value2) {
			    
			  if (!isset($neuesArray[$key][$key2])) {
			   	$neuesArray[$key][$key2] = array();
			  }
			  foreach($value2 as $key3 => $value3) {
			  
			   if($key3 != $specificKey) {
			    	$neuesArray[$key][$key2][$key3] = $value3;
			   }
			  }
			 }
			}
		
			return $neuesArray;
			break;
			
			default: break;
		}
	}
	
	// Helper function to count the dimensions of an array
	public static function countdim($array) {
	    if (is_array(reset($array)))   
	      $return = self::countdim(reset($array)) + 1;
	    else
	      $return = 1;
	  
	    return $return;
    }
	
	
	// create files for mmatch and match depending on the source. 1 = match, 2 = mmatch
	public static function createFileForMatch($matchid, $content, $source) {
		
		switch ($source) {
			case 1:
				$my_file = '../tmp/wordsForMatchId' . $matchid . '.txt';
				break;
				
			case 2:
				$my_file = '../tmp/mmatch/wordsForMatchId' . $matchid . '.txt';
				break;
				
			default:
				
				break;
		}
		
		$handle = fopen($my_file, 'a') or die('Cannot open file:  '.$my_file);
		$data = $content;
		fwrite($handle, $data);
		fclose($handle);
		
	}
	
	// read files for mmatch and match depending on the soruce. 1 = match, 2 = mmatch
	public static function readFromFile($matchid, $soruce) {
		
		switch ($soruce) {
			case 1:
				$my_file = '../tmp/wordsForMatchId' . $matchid . '.txt';
				break;
				
			case 2:
				$my_file = '../tmp/mmatch/wordsForMatchId' . $matchid . '.txt';
				break;
			
			default:
				
				break;
		}
		
		$handle = fopen($my_file, 'r');
		//$data = fread($handle,filesize($my_file));
		$data = file_get_contents($my_file);
		return $data;
	}
	
	
}



?>
	