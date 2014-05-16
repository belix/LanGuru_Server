<?php

class RegistrationController extends Zend_Controller_Action
{
	
	
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
	 
	public function checkUsernameAction() {
		
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
					
			$isFreeUsername = Application_Model_User::checkUsername($json_data);
			
			if($isFreeUsername) {
				// continue
				echo 1;
			}
			
			else {
				// username exists already
				echo 0;
			}
		}
	}
	
	/* 
	 * check if email already exists
	 * */
	 
	public function checkEmailAction() {
		
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
					
			$isFreeEmail = Application_Model_User::checkEmail($json_data);
			
			if($isFreeEmail) {
				// continue
				echo 1;
			}
			
			else {
				// email exists already
				echo 0;
			}
		}
	}
	
	
	/* 
	 * register the user
	 * */
	 
	 
	public function registerUserAction() {
	
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
				
				//echo json_encode($json_data);
				
				if (is_array($json_data)) {
					// convert it back to json
					
					// write the user back to database
					$registered = Application_Model_User::registerUser($json_data);
					
					if($registered) {
						echo json_encode($registered);
					}
					else {
						echo 0;
					}
					
				
				}
				 
				 
			}
			
	}
	
	/* 
	 * log the user in
	 * */
	 
	 
	public function loginUserAction() {
	
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
				
				//echo json_encode($json_data);
				
				if (is_array($json_data)) {
					// convert it back to json
					
					// write the user back to database
					$login = Application_Model_User::loginUser($json_data);
					
					if($login) {
						echo json_encode($login);
					}
					else {
						echo 0;
					}
					
				
				}
				 
				 
			}
			
	}
	
	/* 
	 * log the user in with facebook
	 * */
	 
	 
	public function loginFbuserAction() {
	
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
				
				//echo json_encode($json_data);
				
				if (is_array($json_data)) {
					// convert it back to json
					
					// write the user back to database
					$login = Application_Model_User::loginFBUser($json_data);
					
					if($login) {
						echo json_encode($login);
					}
					else {
						echo 0;
					}
					
				
				}
				 
				 
			}
			
	}

	public function changeLanguageAction() {
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
				
				//echo json_encode($json_data);
				
				if (is_array($json_data)) {
					// convert it back to json
					
					// write the user back to database
					$changed = Application_Model_User::changeLanguage($json_data);
					
					echo $changed;
					
					
				
				}
				 
				 
			}
	}
	
	public function savePicturesAction() {
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
				
				//echo json_encode($json_data);
				
				if (is_array($json_data)) {
					// convert it back to json
					
					// write the user back to database
					$changed = Application_Model_User::savePictures($json_data);
					
					echo $changed;
					
				}
				 
				 
			}
	}

	
}
