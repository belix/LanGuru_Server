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

			sendPush('694ca97a3461e9f5e3f8031cf6c14205711f83307decf16b84e7cf7710c8595d', 'Hallo fordert dich zu einem Match heraus. Schlund?');
			
	}


}
