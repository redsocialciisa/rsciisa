<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        //validación, si no está logeado vuelve al login
        $aut = Zend_Auth::getInstance();
        if($aut->hasIdentity() == false){ 
        	$this->_redirect('/auth/index');
        }
	}

	public function logoutAction()
	{
		Zend_Auth::getInstance()->clearIdentity();
		$this->_redirect('/auth/index');
	}
	
}