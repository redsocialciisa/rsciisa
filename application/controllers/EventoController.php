<?php

class EventoController extends Zend_Controller_Action
{

    public function init()
    {
    }

    public function indexAction()
    {
        $aut = Zend_Auth::getInstance();
        
        $succesEliminarUsuarios = $this->getRequest()->getParam('se');
        $succesInvitarUsuarios = $this->getRequest()->getParam('si');
        
        if ($this->getRequest()->isPost()) 
        {
	    	$objEvento = new Application_Model_Evento();
	    	$objEventoDao = new Application_Model_EventoDao();
	    	$objUtilidad = new Application_Model_Utilidad();
	    	
	    	$objPublicacionEventoDao = new Application_Model_PublicacionEventoDao();
	    	$objPublicacionEvento = new Application_Model_PublicacionEvento();
	    	
	    	$objUsuarioEventoDao = new Application_Model_UsuarioEventoDao();
	    	$objUsuarioEvento = new Application_Model_UsuarioEvento();
	    	
	    	$objPublicacionDao = new Application_Model_PublicacionDao();
	    	$objPublicacion = new Application_Model_Publicacion();
		    	
	    	$objEvento->setNombre($this->getRequest()->getParam('txtNombreEvento'));
	    	$objEvento->setDescripcion($this->getRequest()->getParam('txtDescripcion'));
	    	$objEvento->setLugar($this->getRequest()->getParam('address'));
	    	$objEvento->setFechaEvento($objUtilidad->devolverFechaParaBD($this->getRequest()->getParam('txtFechaEvento')));
	    	$objEvento->setHora($this->getRequest()->getParam('sltHora'));
	    	$objEvento->setCancelado(0);
	    	$objEvento->setTipoEventoId($this->getRequest()->getParam('cbxTipo'));
	    	
	    	$fecha = new DateTime();
	    	$fechahora = str_replace(" ","",str_replace("-","",str_replace(":","",$fecha->format('Y-m-d H:i:s'))));
	    	$objEvento->setFechaCreacion($fechahora);
	    	
	    	$cordenadas = explode(",", $this->getRequest()->getParam('hdnCordenadas'));
	    	$objEvento->setCordenadaX(str_replace("(","",trim($cordenadas[0])));
	    	$objEvento->setCordenadaY(str_replace(")","",trim($cordenadas[1])));
	    	$objEvento->setUsuarioId($aut->getIdentity()->usu_id);
	    	$idEvento = $objEventoDao->guardar($objEvento);#GRABA
	    	
	    	$objPublicacion->setTexto($this->getRequest()->getParam('txtNombreEvento'));
	    	$objPublicacion->setFecha($fechahora);
	    	$objPublicacion->setPrivacidadId(7);
	    	$objPublicacion->setTipoId(4);
	    	$objPublicacion->setUsuarioId($aut->getIdentity()->usu_id);
	    	$objPublicacion->setUsuarioPara($aut->getIdentity()->usu_id);
	    	$idPublicacion = $objPublicacionDao->guardar($objPublicacion);#GRABA
	    	
	    	$objUsuarioEvento->setUsuarioId($aut->getIdentity()->usu_id);
	    	$objUsuarioEvento->setEventoId($idEvento);
	    	$objUsuarioEvento->setEliminar(0);
	    	$objUsuarioEventoDao->guardar($objUsuarioEvento);#GRABA
	    	
	    	$objPublicacionEvento->setPublicacionId($idPublicacion);
	    	$objPublicacionEvento->setEventoId($idEvento);
	    	$objPublicacionEventoDao->guardar($objPublicacionEvento);#GRABA
        }
        
        $objUsuarioEventoDao = new Application_Model_UsuarioEventoDao();
        $listaEventos = $objUsuarioEventoDao->obtenerPorUsuarioId($aut->getIdentity()->usu_id);
        
        Zend_View_Helper_PaginationControl::setDefaultViewPartial ( 'paginator/items.phtml' );
        $paginator = Zend_Paginator::factory($listaEventos);
        $paginator->setDefaultItemCountPerPage( 5 );
        
        if ($this->_hasParam ( 'page' )) {
        	$paginator->setCurrentPageNumber( $this->_getParam ( 'page' ) );
        }
        
        $objInvitacionDao = new Application_Model_InvitacionDao();
        $listaInvitacionesEvento = $objInvitacionDao->obtenerInvitacionEventosPorUsuario($aut->getIdentity()->usu_id);
        
        Zend_View_Helper_PaginationControl::setDefaultViewPartial ( 'paginator/items.phtml' );
        
        $paginatorInvitacion = Zend_Paginator::factory($listaInvitacionesEvento);
        $paginatorInvitacion->setDefaultItemCountPerPage( 5 );
        
        if ($this->_hasParam ( 'page' )) {
        	$paginatorInvitacion->setCurrentPageNumber( $this->_getParam ( 'page' ) );
        }
        
        $this->view->succesEliminarUsuarios = $succesEliminarUsuarios;
        $this->view->succesInvitarUsuarios = $succesInvitarUsuarios;
        
        $this->view->listaInvitacionesEvento = $paginatorInvitacion;
        $this->view->listaEventos = $paginator;
    }
    
    public function contactosAction()
    {
        $aut = Zend_Auth::getInstance();
        $eventoId = $this->getRequest()->getParam('id');
        
        $objAmigoDao = new Application_Model_AmigoDao();
        $listaAmigos = $objAmigoDao->obtenerTodosUsuariosPorUsuarioId($aut->getIdentity()->usu_id);
        
        $objUsuarioEventoDao = new Application_Model_UsuarioEventoDao();
        
        $listaUsuariosEvento = $objUsuarioEventoDao->obtenerUsuariosPorEventoId($eventoId);
        
        $this->view->id = $eventoId; 
        $this->view->listaUsuarios = $listaAmigos;
        $this->view->listaUsuariosEvento = $listaUsuariosEvento; 
    }
    
    public function marcarInvitarAction()
    {
    	$this->_helper->layout()->disableLayout();
    	$usu_id = $this->getRequest()->getParam('usuarioId');
    	$eve_id = $this->getRequest()->getParam('eventoId');
    	$cbx_usuario = $this->getRequest()->getParam('cbxUsuario');
    
    	$fecha = new DateTime();
    	$fechahora = str_replace(" ","",str_replace("-","",str_replace(":","",$fecha->format('Y-m-d H:i:s'))));
    	$objInvitacionDao = new Application_Model_InvitacionDao();
    	 
    	$objInvitacion = $objInvitacionDao->obtenerPorIdActividadUsuarioEvento($usu_id, $eve_id);
    	 
    	if ($objInvitacion)
    	{
    		$id =$objInvitacion->getId();
    		$objInvitacion->setId($id);
    		$objInvitacion->setFecha($fechahora);
    		$objInvitacion->setTipoInvitacionId(2);
    		$objInvitacion->setUsuarioId($usu_id);
    		$objInvitacion->setIdActividad($eve_id);
    		$objInvitacion->setEstado($cbx_usuario);
    		$objInvitacionDao->guardar($objInvitacion);
    	}
    	else
    	{
    		$objInvitacion = new Application_Model_Invitacion();
    		$objInvitacion->setFecha($fechahora);
    		$objInvitacion->setTipoInvitacionId(2);
    		$objInvitacion->setUsuarioId($usu_id);
    		$objInvitacion->setIdActividad($eve_id);
    		$objInvitacion->setEstado($cbx_usuario);
    		$objInvitacionDao->guardar($objInvitacion);
    	}
    
    	$this->view->ok = "ok";
    }
    
    public function marcarEliminarAction()
    {
    	$this->_helper->layout()->disableLayout();
    	$usu_id = $this->getRequest()->getParam('usuarioId');
    	$eve_id = $this->getRequest()->getParam('eventoId');
    	$cbx_usuario = $this->getRequest()->getParam('cbxUsuario');
    
    	$objUsuarioEventoDao = new Application_Model_UsuarioEventoDao();
    	$objUsuarioEventoDao->marcarEliminar($eve_id, $usu_id, $cbx_usuario);
    
    	$this->view->ok = "ok";
    }
    
    public function invitarUsuarioEventoAction()
    {
    
    	$eventoId = $this->getRequest()->getParam('id');
    	$fecha = new DateTime();
    	$fechahora = str_replace(" ","",str_replace("-","",str_replace(":","",$fecha->format('Y-m-d H:i:s'))));
    	$objInvitacionDao = new Application_Model_InvitacionDao();
    	$listaInvitacion = $objInvitacionDao->obtenerEventoPorInvitar($eventoId);
    
    	foreach ($listaInvitacion as $item)
    	{
    		$id = $item->getId();
    		$item->setId($id);
    		$item->setFecha($fechahora);
    		$item->setEstado(2);
    		$objInvitacionDao->guardar($item);
    	}
    	 
    	$this->_redirect('/evento/index/si/ok');
    	 
    }
    
    public function aceptarInvitacionAction()
    {
    	$this->_helper->layout()->disableLayout();
    	$invitacionId = $this->getRequest()->getParam('invitacionId');
    
    	$fecha = new DateTime();
    	$fechahora = str_replace(" ","",str_replace("-","",str_replace(":","",$fecha->format('Y-m-d H:i:s'))));
    
    	$objInvitacionDao = new Application_Model_InvitacionDao();
    	$objInvitacion = $objInvitacionDao->obtenerPorId($invitacionId);
    	
    	$objUsuarioEvento = new Application_Model_UsuarioEvento();
    	$objUsuarioEventoDao = new Application_Model_UsuarioEventoDao();
    
    	$objUsuarioEvento->setUsuarioId($objInvitacion->getUsuarioId());
    	$objUsuarioEvento->setEventoId($objInvitacion->getIdActividad());
    	$objUsuarioEventoDao->guardar($objUsuarioEvento); 
    
    	$id = $objInvitacion->getId();
    	$objInvitacion->setId($id);
    	$objInvitacion->setIdActividad($objInvitacion->getIdActividad());
    	$objInvitacion->setFecha($fechahora);
    	$objInvitacion->setEstado(4);
    	$objInvitacionDao->guardar($objInvitacion);
    
    	$this->view->ok = "ok";
    
    }
    
    public function cancelarEventoAction()
    {
        $this->_helper->layout()->disableLayout();
        $id = $this->getRequest()->getParam('id');
        
        $objEventoDao = new Application_Model_EventoDao();
        $objEvento = new Application_Model_Evento();
        
        $objEvento->setId($id);
        $objEvento->setCancelado(1);
        
        $objEventoDao->cancelarEvento($objEvento);
        
        $this->view->ok = "ok";
    }
    
    public function noAsistirAction()
    {
    	$this->_helper->layout()->disableLayout();
    	$aut = Zend_Auth::getInstance();
    	$id = $this->getRequest()->getParam('id');
    
    	$objUsuarioEventoDao = new Application_Model_UsuarioEventoDao();
    	$objInvitacionDao = new Application_Model_InvitacionDao();

    	$objUsuarioEventoDao->eliminarUsuarioDelEvento($id,$aut->getIdentity()->usu_id);
    	$objInvitacionDao->eliminarUsuarioPorEvento($id, $aut->getIdentity()->usu_id);
    
    	$this->view->ok = "ok";
    }
    
    public function rechazarInvitacionAction()
    {
    	$this->_helper->layout()->disableLayout();
    	$invitacionId = $this->getRequest()->getParam('id');
    
    	$fecha = new DateTime();
    	$fechahora = str_replace(" ","",str_replace("-","",str_replace(":","",$fecha->format('Y-m-d H:i:s'))));
    
    	$objInvitacionDao = new Application_Model_InvitacionDao();
    	$objInvitacion = $objInvitacionDao->obtenerPorId($invitacionId);
    
    	$id = $objInvitacion->getId();
    	$objInvitacion->setId($id);
    	$objInvitacion->setFecha($fechahora);
    	$objInvitacion->setEstado(3);
    	$objInvitacionDao->guardar($objInvitacion);
    
    	$this->view->ok = "ok";
    }
    
    public function eliminarUsuariosEventoAction()
    {
        $this->_helper->layout()->disableLayout();
    	$eventoId = $this->getRequest()->getParam('eventoId');
    	$usuarioId = $this->getRequest()->getParam('usuarioId');
    	
    	$objUsuarioEventoDao = new Application_Model_UsuarioEventoDao();
    	$objInvitacionDao = new Application_Model_InvitacionDao();
    	
    	$listaEliminar = $objUsuarioEventoDao->obtenerUsuariosAEliminar(8);
    	
    	foreach ($listaEliminar as $item)
    	{
    		$objInvitacionDao->eliminarUsuarioPorEvento($eventoId, $item->getUsuarioId());
    	}
    	
    	$objUsuarioEventoDao->eliminarUsuariosPorEvento($eventoId);
    
    	$listaEliminar = null;
    	$objUsuarioEventoDao = null;
    	$objInvitacionDao = null;
    	
    }

}



