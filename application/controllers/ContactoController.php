<?php

class ContactoController extends Zend_Controller_Action
{

    public function init()
    {
    	$aut = Zend_Auth::getInstance();
		if($aut->hasIdentity() == false){
			$this->_redirect('/auth');
		}
		//
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
        $objNotificacion = new Application_Model_Notificacion();
        $objNotificacionDao = new Application_Model_NotificacionDao();
        
        
        $aut = Zend_Auth::getInstance();
        $idContacto = $this->getRequest()->getParam('usuario'); //id del contacto
        $now = new DateTime();
        
        //guardar para el usuario contacto
        $objAmigo->setAmigoUsuarioId($idContacto);
        $objAmigo->setFechaSolicitud($now->format('Y-m-d H:i:s'));
        $objAmigo->setEstadoAmistad(1);//pendiente
        $objAmigo->setUsuarioId($aut->getIdentity()->usu_id);
        $textoInv = $aut->getIdentity()->usu_nombre. ' te ha enviado una solicitud de amistad';
        $objNotificacion->setTipoNotificacionId(3);
        $objNotificacion->setVista(0);
        $objNotificacion->setFecha($now->format('Y-m-d H:i:s'));
        $objNotificacion->setUsuarioId($idContacto);
        $objNotificacion->setTexto($textoInv);
        $objNotificacionDao->guardar($objNotificacion);
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
    	$now = new DateTime();
    	$objAmigoDao = new Application_Model_AmigoDao();
    	$objAmigo = new Application_Model_Amigo();
    	$objNotificacion = new Application_Model_Notificacion();
    	$objNotificacionDao = new Application_Model_NotificacionDao();
    	$objUsuarioDao = new Application_Model_UsuarioDao();
    	
    	
    	$aut = Zend_Auth::getInstance();
    	$idContacto = $this->getRequest()->getParam('usuario'); //id del contacto
    	#$objUsuario = $objUsuarioDao->obtenerPorId($idContacto);
    	
    	//1
    	$objAmigo->setFechaAmistad($now->format('Y-m-d H:i:s'));
    	$objAmigo->setEstadoAmistad(2);
    	$objAmigo->setAmigoUsuarioId($aut->getIdentity()->usu_id);
    	$objAmigo->setUsuarioId($idContacto);
    	$textoInv = $aut->getIdentity()->usu_nombre. ' ha aceptado tu solicitud de amistad';
    	$objNotificacion->setTipoNotificacionId(4);
    	$objNotificacion->setVista(0);
    	$objNotificacion->setFecha($now->format('Y-m-d H:i:s'));
    	$objNotificacion->setUsuarioId($idContacto);
    	$objNotificacion->setTexto($textoInv);
    	$objNotificacionDao->guardar($objNotificacion);
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

