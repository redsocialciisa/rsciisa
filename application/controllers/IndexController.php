<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
    }

    public function indexAction()
    {
        $aut = Zend_Auth::getInstance();
        if($aut->hasIdentity()){
        	$this->_redirect('/muro');
        }else{
            $this->_redirect('/auth');
        }
    }
    
}