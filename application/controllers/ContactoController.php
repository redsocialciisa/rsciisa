<?php

class ContactoController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
 
    }

    public function busquedaAction()
    {
        $objUsuarioDao = new Application_Model_UsuarioDao();
        $nombreUsuario = $this->getRequest()->getParam('txtBuscador');
        
        $listaUsuarios = $objUsuarioDao->obtenerTodosPorString($nombreUsuario);
        
        //plantilla de paginator
        Zend_View_Helper_PaginationControl::setDefaultViewPartial ( 'paginator/items.phtml' );
        
        $paginator = Zend_Paginator::factory( $listaUsuarios );
        
        $paginator->setDefaultItemCountPerPage( 40 );
        
        if ($this->_hasParam ( 'page' )) {
        	$paginator->setCurrentPageNumber( $this->_getParam ( 'page' ) );
        }
        
        $this->view->listaUsuarios = $paginator;
        $this->view->largoBusqueda = strlen(trim($nombreUsuario));

    }
    
    public function agregarAction()
    {
        $this->_helper->layout()->disableLayout();
        $objAmigoDao = new Application_Model_AmigoDao();
        $objAmigo = new Application_Model_Amigo();
        
        $aut = Zend_Auth::getInstance();
        $idContacto = $this->getRequest()->getParam('usuario'); //id del contacto
        $now = new DateTime();
        
        //guardar para el usuario contacto
        $objAmigo->setAmigoUsuarioId($idContacto);
        $objAmigo->setFechaSolicitud($now->format('Y-m-d H:i:s'));
        $objAmigo->setEstadoAmistad(1);//pendiente
        $objAmigo->setUsuarioId($aut->getIdentity()->usu_id);
        
        $objAmigoDao->guardarSolicitud($objAmigo);
        
        $this->view->ok = "ok";
        
    }
    
    public function indexAction()
    {
        $aut = Zend_Auth::getInstance();
        $objAmigoDao = new Application_Model_AmigoDao();
        $listaContactos = $objAmigoDao->obtenerTodosUsuariosPorUsuarioId($aut->getIdentity()->usu_id);
        
        $listaPendientes = $objAmigoDao->obtenerSolicitudesPendientesPorUsuarioId($aut->getIdentity()->usu_id);
        
        $this->view->listaContactos = $listaContactos;
        $this->view->listaPendientes = $listaPendientes;
    }
    
    public function aceptarAction()
    {
    	$this->_helper->layout()->disableLayout();
    	$objAmigoDao = new Application_Model_AmigoDao();
    	$objAmigo = new Application_Model_Amigo();
    
    	$aut = Zend_Auth::getInstance();
    	$idContacto = $this->getRequest()->getParam('usuario'); //id del contacto
    	$now = new DateTime();
    	
    	//1
    	$objAmigo->setFechaAmistad($now->format('Y-m-d H:i:s'));
    	$objAmigo->setEstadoAmistad(2);
    	$objAmigo->setAmigoUsuarioId($aut->getIdentity()->usu_id);
    	$objAmigo->setUsuarioId($idContacto);
    	
    	$objAmigoDao->confirmarAmistad($objAmigo);
    	
    	//2: automatica
    	$objAmigo->setFechaSolicitud($now->format('Y-m-d H:i:s'));
    	$objAmigo->setAmigoUsuarioId($idContacto);
    	$objAmigo->setUsuarioId($aut->getIdentity()->usu_id);

    	$objAmigoDao->guardarAsociacionAutomatica($objAmigo);
    
    	$this->view->ok = "ok";
    
    }
    
    public function rechazarAction()
    {
    	$this->_helper->layout()->disableLayout();
    	$objAmigoDao = new Application_Model_AmigoDao();
    
    	$aut = Zend_Auth::getInstance();
    	$idContacto = $this->getRequest()->getParam('usuario'); //id del contacto

    	$objAmigoDao->eliminarAmigo($idContacto, $aut->getIdentity()->usu_id);
    	
    	$this->view->ok = "ok";
    
    }


}

