<?php

class PublicacionController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $id = $this->getRequest()->getParam('id');
        
        $objPublicacionDao = new Application_Model_PublicacionDao();
        $objUsuarioDao = new Application_Model_UsuarioDao();
        $objComentarioDao = new Application_Model_ComentarioDao();
        
        $objPublicacion = $objPublicacionDao->obtenerPorId($id);
        $objUsuario = $objUsuarioDao->obtenerPorId($objPublicacion->getUsuarioId());
        $listaComentarios = $objComentarioDao->obtenerTodosPorPublicacionId($id);
        
        $this->view->publicacion = $objPublicacion;
        $this->view->usuario = $objUsuario;
        $this->view->comentarios = $listaComentarios;
        
    }

}

