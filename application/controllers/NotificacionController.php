<?php

class NotificacionController extends Zend_Controller_Action
{

    public function init()
    {
        $aut = Zend_Auth::getInstance();
        if($aut->hasIdentity() == false){
        	$this->_redirect('/auth');
        }
    }

    public function indexAction()
    {
        $aut = Zend_Auth::getInstance();
        $objNotificacionDao = new Application_Model_NotificacionDao();
        $listaNotificaciones = $objNotificacionDao->obtenerPorUsuarioId($aut->getIdentity()->usu_id);
		        
        Zend_View_Helper_PaginationControl::setDefaultViewPartial ( 'paginator/itemsv2.phtml' );
        
        $paginatorNotificaciones = Zend_Paginator::factory($listaNotificaciones);
        $paginatorNotificaciones->setDefaultItemCountPerPage(20);
        
        if ($this->_hasParam ( 'page' )) {
        	$paginatorNotificaciones->setCurrentPageNumber( $this->_getParam ( 'page' ) );
        }
        
        $this->view->listaNotificaciones = $paginatorNotificaciones;

    }
}

