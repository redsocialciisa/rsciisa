<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $aut = Zend_Auth::getInstance();
        if($aut->hasIdentity()){
            $this->_redirect('/muro');
        }else{
            $this->_redirect('/auth');
        }
    }
    
    public function indexAction()
    {
        
    }
}
