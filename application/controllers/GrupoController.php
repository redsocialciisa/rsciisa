<?php

class GrupoController extends Zend_Controller_Action
{

    public function init()
    {
        
    }

    public function indexAction()
    {

        $successEliminarUsuarios = $this->getRequest()->getParam('se');
        $successInvitarUsuarios = $this->getRequest()->getParam('si');
        
        $form = new Application_Form_FormGrupo();
        $aut = Zend_Auth::getInstance();
        $objGrupoDao = new Application_Model_GrupoDao();
        $objUsuarioGrupo = new Application_Model_UsuarioGrupo();
        $objUsuarioGrupoDao = new Application_Model_UsuarioGrupoDao();
        $objGrupo = new Application_Model_Grupo();
        $objPublicacion = new Application_Model_Publicacion();
        $objPublicacionDao = new Application_Model_PublicacionDao();
        $objInvitacion = new Application_Model_InvitacionDao();
        $objPublicacionGrupo = new Application_Model_PublicacionGrupo();
        $objPublicacionGrupoDao = new Application_Model_PublicacionGrupoDao();
        
        $fecha = new DateTime();
        
        if($this->getRequest()->isPost())
        {       
        	$formData = $this->_request->getPost();
        	if($form->isValid($this->_getAllParams()))
        	{
        	    
        	    $fechahora = str_replace(" ","",str_replace("-","",str_replace(":","",$fecha->format('Y-m-d H:i:s'))));
        		
        		$objGrupo->setNombre($this->getRequest()->getParam('txtNombre'));
        		$objGrupo->setDescripcion($this->getRequest()->getParam('txtDescripcion'));
        		$objGrupo->setFechaCreacion($fechahora);
        		$objGrupo->setTipoGrupoId($this->getRequest()->getParam('grpTipo'));
        		$objGrupo->setUsuId($aut->getIdentity()->usu_id);
        		
        		if(isset($_FILES['fileFoto']['name']) && $_FILES['fileFoto']['name'] != "")
        		{
        			$foto_name = $_FILES['fileFoto']['name'];
        			$foto_tmp 	= $_FILES['fileFoto']['tmp_name'];
        			copy($foto_tmp, "/var/www/rsciisa/public/imagenes/grupos/".$fechahora."_".$foto_name);
        			$objGrupo->setFoto($fechahora."_".$foto_name);
        		}
        		$idGrupo = $objGrupoDao->guardar($objGrupo);
        		
        		$objPublicacion->setFecha($fechahora);
        		$objPublicacion->setFoto($fechahora."_".$foto_name);
        		$objPublicacion->setPrivacidadId(7);
        		$objPublicacion->setTexto($this->getRequest()->getParam('txtNombre'));
        		$objPublicacion->setUsuarioPara($aut->getIdentity()->usu_id);
        		$objPublicacion->setTipoId(5);
        		$objPublicacion->setUsuarioId($aut->getIdentity()->usu_id);
        		$idPublicacion = $objPublicacionDao->guardar($objPublicacion);
        		
        		$objUsuarioGrupo->setGrupoId($idGrupo);
        		$objUsuarioGrupo->setUsuarioId($aut->getIdentity()->usu_id);
        		$objUsuarioGrupoDao->guardar($objUsuarioGrupo);
        		
        		$objPublicacionGrupo->setGrupoId($idGrupo);
        		$objPublicacionGrupo->setPublicacionId($idPublicacion);
        		$objPublicacionGrupoDao->guardar($objPublicacionGrupo);
        		
        		$this->view->mensaje_ok = "ok";
        		
        	}else{
        		$form->populate($formData);
        		$this->view->error = "error";
        	}
        }
        
        $this->view->form = $form;
        
        $listaGrupoUsuario = $objUsuarioGrupoDao->obtenerPorUsuarioId($aut->getIdentity()->usu_id);
        $listaInvitacionesGrupo = $objInvitacion->obtenerInvitacionGruposPorUsuario($aut->getIdentity()->usu_id);
        //Paginador
        //plantilla de paginator
        Zend_View_Helper_PaginationControl::setDefaultViewPartial ( 'paginator/items.phtml' );
        
        $paginatorGrupo = Zend_Paginator::factory($listaGrupoUsuario);
        $paginatorGrupo->setDefaultItemCountPerPage( 5 );
        
        $paginatorInvitacion = Zend_Paginator::factory($listaInvitacionesGrupo);
        $paginatorInvitacion->setDefaultItemCountPerPage( 5 );
        
        
        if ($this->_hasParam ( 'page' )) {
        	$paginatorGrupo->setCurrentPageNumber( $this->_getParam ( 'page' ) );
        }
        
        if ($this->_hasParam ( 'page' )) {
        	$paginatorInvitacion->setCurrentPageNumber( $this->_getParam ( 'page' ) );
        }
        
        $this->view->listaGrupoUsuario = $paginatorGrupo;
        $this->view->listaInvitacionesGrupo = $paginatorInvitacion;
        $this->view->successEliminarUsuarios = $successEliminarUsuarios;
        $this->view->successInvitarUsuarios = $successInvitarUsuarios;
       
        $objPublicacionDao = null;
        $listaPublicaciones = null;
        $paginator = null;
        $form = null;
        $objGrupo = null;
        $objUsuarioGrupo = null;
        $formData = null;
        
    }
    
    public function eliminarAction()
    {
        $this->_helper->layout()->disableLayout();
        $objGrupoDao = new Application_Model_GrupoDao();
        $objUsuarioGrupoDao = new Application_Model_UsuarioGrupoDao();
        $objPublicacionGrupoDao = new Application_Model_PublicacionGrupoDao();
        $objPublicacionDao = new Application_Model_PublicacionDao();
        $objInvitacionDao = new Application_Model_InvitacionDao();
        
        $grupoId = $this->getRequest()->getParam('grupoId');
        $objGrupo = $objGrupoDao->obtenerPorId($grupoId);
        unlink("/var/www/rsciisa/public/imagenes/grupos/".$objGrupo->getFoto());
        $listaPublicacionGrupo = $objPublicacionGrupoDao->obtenerPublicacionesDelGrupo($grupoId);
        $objPublicacionGrupoDao->eliminarPublicaciones($grupoId);
        $objUsuarioGrupoDao->eliminarUsuariosPorGrupoId($grupoId);
        
        foreach ($listaPublicacionGrupo as $item)
        {
            $objPublicacionDao->eliminar($item->getId());
        }    
        
        $objInvitacionDao->eliminarPorGrupo($grupoId);
        $objGrupoDao->eliminar($grupoId);
        
        $objGrupoDao = null;
        $objUsuarioGrupoDao = null;
        $objPublicacionGrupoDao = null;
        $objInvitacionDao = null;
        
        $this->view->ok = "ok";
        
    }
    
    public function editarAction()
    {
        $fecha = new DateTime();
        $objGrupoDao = new Application_Model_GrupoDao();
        $objGrupo = new Application_Model_Grupo();
        $form = new Application_Form_FormEditarGrupo();
        
        if($this->getRequest()->isPost() == false)
        {
            $grupoId = $this->getRequest()->getParam('grupoId');
            $objGrupo = $objGrupoDao->obtenerPorId($grupoId);
	        $array = array(
	                "txtNombre" => $objGrupo->getNombre(),
	        		"txtDescripcion" => $objGrupo->getDescripcion(),
	                "hdnIdGrupo" => $objGrupo->getId(),
	                "MAX_FILE_SIZE" => '2097152',
	                "enviar" => 'Editar Grupo'
	                );
	        $form->populate($array);
        }
        
        if($this->getRequest()->isPost())
        {
        	$formData = $this->_request->getPost();
        	if($form->isValid($this->_getAllParams()))
        	{
        	    $grupo = $objGrupoDao->obtenerPorId($this->getRequest()->getParam('hdnIdGrupo'));
        	    $fechahora = str_replace(" ","",str_replace("-","",str_replace(":","",$fecha->format('Y-m-d H:i:s'))));
        	    $objGrupo->setId($this->getRequest()->getParam('hdnIdGrupo'));
        	    $objGrupo->setNombre($this->getRequest()->getParam('txtNombre'));
        	    $objGrupo->setDescripcion($this->getRequest()->getParam('txtDescripcion'));
        	    $objGrupo->setFechaCreacion($fechahora);
        	    $objGrupo->setTipoGrupoId($grupo->getTipoGrupoId());
        	    $objGrupo->setUsuId($grupo->getUsuId());
        	    
        	    if(isset($_FILES['fileFoto']['name']) && $_FILES['fileFoto']['name'] != "")
        	    {
        	    	$foto_name = $_FILES['fileFoto']['name'];
        	    	$foto_tmp 	= $_FILES['fileFoto']['tmp_name'];
        	    	unlink("/var/www/rsciisa/public/imagenes/grupos/".$grupo->getFoto());
        	    	copy($foto_tmp, "/var/www/rsciisa/public/imagenes/grupos/".$fechahora."_".$foto_name);
        	    	$objGrupo->setFoto($fechahora."_".$foto_name);
        	    } 
        	    else
        	    {
        	        $objGrupo->setFoto($grupo->getFoto());
        	    }    
        	    
        	    $objGrupoDao->guardar($objGrupo);
        	    $this->_redirect('/grupo/');
        	    
        	}else{
        	    //print_r($formData);
        	    //exit();
        		$form->populate($formData);
        	}
        }
        
        //$form->populate($array);
        $this->view->form = $form;
    }
    
    public function contactosAction()
    {
        $aut = Zend_Auth::getInstance();
        $objUsuarioGrupoDao = new Application_Model_UsuarioGrupoDao();
        $objAmigoDao = new Application_Model_AmigoDao();
        
        $grupoId = $this->getRequest()->getParam('grupoId');
        $listaGrupoUsuarios = $objUsuarioGrupoDao->obtenerPorGrupoId($grupoId);
        $listaAmigoNoInvitado = $objAmigoDao->amigoNoInvitadoGrupo($grupoId,$aut->getIdentity()->usu_id);
        
        //Paginador
        Zend_View_Helper_PaginationControl::setDefaultViewPartial ( 'paginator/items.phtml' );
        $paginatorGrupoUsuarios = Zend_Paginator::factory($listaGrupoUsuarios);
        $paginatorGrupoUsuarios->setDefaultItemCountPerPage( 5 );
        
        $paginatorAmigoNoInvitado = Zend_Paginator::factory($listaAmigoNoInvitado);
        $paginatorAmigoNoInvitado->setDefaultItemCountPerPage( 5 );
        
        if ($this->_hasParam ( 'page' )) {
        	$paginatorGrupoUsuarios->setCurrentPageNumber( $this->_getParam ( 'page' ) );
        }
        
        if ($this->_hasParam ( 'page' )) {
        	$paginatorAmigoNoInvitado->setCurrentPageNumber( $this->_getParam ( 'page' ) );
        }
        
        
        $this->view->grupoId = $grupoId;
        $this->view->listaGrupoUsuarios = $paginatorGrupoUsuarios;
        $this->view->listaAmigoNoInvitado = $paginatorAmigoNoInvitado;

    }
    
    public function marcarEliminarAction()
    {
        $this->_helper->layout()->disableLayout();
        $usu_id = $this->getRequest()->getParam('usuarioId');
        $gru_id = $this->getRequest()->getParam('grupoId');
        $cbx_usuario = $this->getRequest()->getParam('cbxUsuario');
        
        $objUsuarioGrupoDao = new Application_Model_UsuarioGrupoDao();
        $objUsuarioGrupoDao->marcarEliminar($gru_id, $usu_id, $cbx_usuario);
        
        $this->view->ok = "ok";
    }
    
    
    public function eliminarUsuarioGrupoAction()
    {
        $grupoId = $this->getRequest()->getParam('grupoId');
        
        $objUsuarioGrupoDao = new Application_Model_UsuarioGrupoDao();
        
        $objUsuarioGrupoDao->eliminarUsuariosPorGrupo($grupoId);
        
        #$this->_redirect('/grupo/contactos/grupoId/' . $grupoId);
        $this->_redirect('/grupo/index/se/ok');
        
    }
    
    public function marcarInvitarAction()
    {
    	$this->_helper->layout()->disableLayout();
    	$usu_id = $this->getRequest()->getParam('usuarioId');
    	$gru_id = $this->getRequest()->getParam('grupoId');
    	$cbx_usuario = $this->getRequest()->getParam('cbxUsuario');
		
    	$fecha = new DateTime();
    	$fechahora = str_replace(" ","",str_replace("-","",str_replace(":","",$fecha->format('Y-m-d H:i:s'))));
    	$objInvitacionDao = new Application_Model_InvitacionDao();
    	
    	$objInvitacion = $objInvitacionDao->obtenerPorIdActividadUsuario($usu_id, $gru_id);
    	
    	if ($objInvitacion)
    	{
    	    $id =$objInvitacion->getId();
    	    $objInvitacion->setId($id);
    	    $objInvitacion->setFecha($fechahora);
    	    $objInvitacion->setTipoInvitacionId(1);
    	    $objInvitacion->setUsuarioId($usu_id);
    	    $objInvitacion->setIdActividad($gru_id);
    	    $objInvitacion->setEstado($cbx_usuario);
    	    $objInvitacionDao->guardar($objInvitacion);
    	    
    	}    
    	else
    	{
    	    $objInvitacion = new Application_Model_Invitacion();
    	    $objInvitacion->setFecha($fechahora);
    	    $objInvitacion->setTipoInvitacionId(1);
    	    $objInvitacion->setUsuarioId($usu_id);
    	    $objInvitacion->setIdActividad($gru_id);
    	    $objInvitacion->setEstado($cbx_usuario);
    	    $objInvitacionDao->guardar($objInvitacion);
    	}

    
    	$this->view->ok = "ok";
    }
    
    
    public function invitarUsuarioGrupoAction()
    {
        
        $aut = Zend_Auth::getInstance();
        $grupoId = $this->getRequest()->getParam('grupoId');
        $fecha = new DateTime();
        $fechahora = str_replace(" ","",str_replace("-","",str_replace(":","",$fecha->format('Y-m-d H:i:s'))));
        $objInvitacionDao = new Application_Model_InvitacionDao();
        $objGrupoDao = new Application_Model_GrupoDao();
        $objNotificacion = new Application_Model_Notificacion();
        $objNotificacionDao = new Application_Model_NotificacionDao();
        $listaInvitacion = $objInvitacionDao->obtenerGrupoPorInvitar($grupoId);
        
        foreach ($listaInvitacion as $item)
        {
            $objGrupo = $objGrupoDao->obtenerPorId($grupoId);
            
            $id = $item->getId();
            $item->setId($id);
            $item->setFecha($fechahora);
            $item->setEstado(2);
            $textoInv = 'Has sido invidado al grupo '.$objGrupo->getNombre().' por '.$aut->getIdentity()->usu_nombre;
            $objNotificacion->setTipoNotificacionId(0);
            $objNotificacion->setVista(0);
            $objNotificacion->setFecha($fechahora);
            $objNotificacion->setUsuarioId($item->getUsuarioId());
            $objNotificacion->setTexto($textoInv);
            $objInvitacionDao->guardar($item);
            $objNotificacionDao->guardar($objNotificacion);
    	}
    	
    	$aut = null;
    	$grupoId = null;
    	$fecha = null;
    	$fechahora = null;
    	$objInvitacionDao = null;
    	$objGrupoDao = null;
    	$objNotificacion = null;
    	$objNotificacionDao = null;
    	$listaInvitacion = null;
    	
    	$this->_redirect('/grupo/index/si/ok');
    	
    }
    
    public function aceptarInvitacionAction()
    {
        $this->_helper->layout()->disableLayout();
        $invitacionId = $this->getRequest()->getParam('invitacionId');
        
        $fecha = new DateTime();
        $fechahora = str_replace(" ","",str_replace("-","",str_replace(":","",$fecha->format('Y-m-d H:i:s'))));
        
        
        $objGrupoDao = new Application_Model_GrupoDao();
        $objUsuarioDao = new Application_Model_UsuarioDao();
        $objInvitacionDao = new Application_Model_InvitacionDao();
        $objInvitacion = $objInvitacionDao->obtenerPorId($invitacionId);
        $objNotificacion = new Application_Model_Notificacion();
        $objNotificacionDao = new Application_Model_NotificacionDao();
        $objUsuarioGrupo = new Application_Model_UsuarioGrupo();
        $objUsuarioGrupoDao = new Application_Model_UsuarioGrupoDao();

        $objUsuarioGrupo->setUsuarioId($objInvitacion->getUsuarioId());
        $objUsuarioGrupo->setGrupoId($objInvitacion->getIdActividad());
        $objUsuarioGrupoDao->guardar($objUsuarioGrupo);
        $objGrupo = $objGrupoDao->obtenerPorId($objInvitacion->getIdActividad());
        $objUsuario =  $objUsuarioDao->obtenerPorId($objInvitacion->getUsuarioId());
        
        $id = $objInvitacion->getId();
        $objInvitacion->setId($id);
        $objInvitacion->setFecha($fechahora);
        $objInvitacion->setEstado(4);
        $textoInv = $objUsuario->getNombre().' ha aceptado tu invitación al grupo '.$objGrupo->getNombre();
        
        $objNotificacion->setTipoNotificacionId(0);
        $objNotificacion->setVista(0);
        $objNotificacion->setFecha($fechahora);
        $objNotificacion->setUsuarioId($objGrupo->getUsuId());
        $objNotificacion->setTexto($textoInv);
        $objNotificacionDao->guardar($objNotificacion);
        $objInvitacionDao->guardar($objInvitacion);
        
        $this->view->ok = "ok"; 
        
    }
    
    public function rechazarInvitacionAction()
    {
        $this->_helper->layout()->disableLayout();
        $invitacionId = $this->getRequest()->getParam('invitacionId');
        
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
    
    public function unirseAction()
    {
    	$aut = Zend_Auth::getInstance();
    	$this->_helper->layout()->disableLayout();
    	$pub_id = $this->getRequest()->getParam('idPublicacion');
    	$objPublicacionGrupoDao = new Application_Model_PublicacionGrupoDao();
    	$objUsuarioGrupoDao = new Application_Model_UsuarioGrupoDao();
    	$objUsuarioGrupo = new Application_Model_UsuarioGrupo();
    
    	$objGrupoId = $objPublicacionGrupoDao->obtenerPorPublicacionId($pub_id)->getGrupoId();
    	$objUsuarioGrupo->setGrupoId($objGrupoId);
    	$objUsuarioGrupo->setUsuarioId($aut->getIdentity()->usu_id);
    	$objUsuarioGrupoDao->guardar($objUsuarioGrupo);
    
    	$this->view->ok = $objGrupoId;
    }

    public function unirse2Action()
    {
        $aut = Zend_Auth::getInstance();
        $this->_helper->layout()->disableLayout();
        $grupoId = $this->getRequest()->getParam('idGrupo');
        $objUsuarioGrupoDao = new Application_Model_UsuarioGrupoDao();
        $objUsuarioGrupo = new Application_Model_UsuarioGrupo();
        
        $objUsuarioGrupo->setGrupoId($grupoId);
        $objUsuarioGrupo->setUsuarioId($aut->getIdentity()->usu_id);
        $objUsuarioGrupoDao->guardar($objUsuarioGrupo);
        
        $this->view->ok = $grupoId;
    }
    
    
    public function dejarParticiparAction()
    {
        $aut = Zend_Auth::getInstance();
    	$this->_helper->layout()->disableLayout();
    	$objGrupoDao = new Application_Model_GrupoDao();
    	$objUsuarioGrupoDao = new Application_Model_UsuarioGrupoDao();
    	$grupoId = $this->getRequest()->getParam('grupoId');
    
    	$objUsuarioGrupoDao->eliminarUsuarioPorGrupoYUsuario2($grupoId,$aut->getIdentity()->usu_id);
    	
    	$this->view->ok = "ok";
    }
    
    public function buscarAction()
    {
        $objGrupoDao = new Application_Model_GrupoDao();
        $formBuscar = new Application_Form_FormBuscarGrupo();
        
        $listaPublicos = $objGrupoDao->obtenerPublicos();
        
        if($this->getRequest()->isPost())
        {
        	$formData = $this->_request->getPost();
        	if($formBuscar->isValid($this->_getAllParams()))
        	{
        	    $listaPublicos = $objGrupoDao->obtenerPorNombre($this->getRequest()->getParam('txtBuscar'));
        	}
        }

        Zend_View_Helper_PaginationControl::setDefaultViewPartial ( 'paginator/itemsv2.phtml' );
        $paginatorBusqueda = Zend_Paginator::factory($listaPublicos);
        $paginatorBusqueda->setDefaultItemCountPerPage(10);
        
        if ($this->_hasParam ( 'page' )) {
        	$paginatorBusqueda->setCurrentPageNumber( $this->_getParam ( 'page' ) );
        }
        
        $this->view->formBuscar = $formBuscar;
        $this->view->listaPublicos =  $paginatorBusqueda;
    }    
    
}