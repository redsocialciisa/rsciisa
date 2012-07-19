<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
       
        
    }

    public function indexAction()
    {
        try {
        	if(!@include('/path/to/fichero.php')) {
        		throw new Exception('Error al cargar el fichero');
        	}
        }
        catch(Exception $e) {
        	print "asd";
        }
        
    }
    
}