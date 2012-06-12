<?php

class ContactoController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
 
    }

    public function indexAction()
    {
        $objUsuarioDao = new Application_Model_UsuarioDao();
        $nombreUsuario = $this->getRequest()->getParam('txtBuscador');
        
        $listaUsuarios = $objUsuarioDao->obtenerTodosPorString($nombreUsuario);
        
		$this->view->listaUsuarios = $listaUsuarios;        

    }


}

