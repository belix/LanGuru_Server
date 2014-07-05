<?php

class IndexController extends Zend_Controller_Action
{

	
	public function init() {
		
	}
	
	public function indexAction()
	{

		
	}
	
	public function testAction() {
				
			$this->_helper->layout()->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);


			$listOfWords = Application_Model_Words::retrieveWordsForMultiplyChoiceGame();
			Application_Model_Helper::createFileForMatch(999, json_encode($listOfWords), 1);
			$words = Application_Model_Helper::readFromFile(999, 1);
			$words = json_decode($words);
			
			Zend_Debug::dump($words);
	}


}
