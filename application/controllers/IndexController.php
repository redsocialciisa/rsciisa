<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /*$aut = Zend_Auth::getInstance();
        if($aut->hasIdentity()){
            $this->_redirect('/muro');
        }else{
            $this->_redirect('/auth');
        }*/
    }

    public function indexAction()
    {
        $objUsuarioEventoDao = new Application_Model_UsuarioEventoDao();
        $listaEliminar = $objUsuarioEventoDao->obtenerUsuariosAEliminar(8);

        foreach ($listaEliminar as $item)
        {
            echo $item->getUsuarioId();
        }
        
    }
    
    
}