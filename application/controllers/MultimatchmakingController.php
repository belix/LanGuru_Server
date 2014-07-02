<?php

class MultimatchmakingController extends Zend_Controller_Action
{
	
	
	public function init() {
			$this->_helper->layout()->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);
			
	}
	
	public function indexAction()
	{
		
		
		
	}
	
	
	public function requestMatchAction() {
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
			$error = Application_Model_Mmatch::requestMatch($json_data);
			
			echo $error;
		}
	}
	
	public function randomMatchAction() {
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
			$error = Application_Model_Randommatch::randomMatch($json_data);
			
			echo $error;
		}
	} 
	
	public function matchExistsAction() {
		
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
			$error = Application_Model_Mmatch::matchExists($json_data);
			
			echo $error;
		}
		 
	}
	
	public function deleteRequestAction() {
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
			$error = Application_Model_Mmatch::deleteRequest($json_data);
			
			echo $error;
		}
	}
	
	// function to request the match for a specific id
	public function acceptRequestAction() {
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
			$error = Application_Model_Mmatch::acceptRequest($json_data);
			
			echo $error;
		}
	}
	
	public function friendDetailsAction() {
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
			$error = Application_Model_Mmatch::getFriendDetails($json_data);
			
			echo $error;
		}

	}
	
	public function searchFriendsAction() {
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
			$error = Application_Model_User::getFriendInfos($json_data);
			
			echo $error;
		}

	}
	
	public function finishRoundAction() {
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
			$error = Application_Model_Mmatch::finishRound($json_data);
			
			echo $error;
		}
	}
	
	// function is called when you click on a specific match to start it in the table view
	public function getMatchAction() {
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
			$error = Application_Model_Mmatch::getWordsForMatch($json_data);
			
			echo $error;
		}
	}
	
	public function getRankingForUsersAction() {
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
			$error = Application_Model_User::getRankingForUsers($json_data);
			
			echo $error;
		}
	}
	
	public function testAction(){
			//$array['username'] = "Bob Schlund";
			//$test = Application_Model_User::getFriendInfos($array);
			
			$matchdata['score'] = 6;
			$matchdata['active'] = 2;
			$matchdata['opponent'] = "opponent2";
			$matchdata['foreignlang'] = "EN";
			$matchdata['id'] = 99;
			$matchdata['nativelang1'] = "DE";
			$matchdata['nativelang2'] = "DE";
			
			
			
			//Zend_Debug::dump(Application_Model_Mmatch::finishRound($matchdata));
			
		}
		
		
	
}


