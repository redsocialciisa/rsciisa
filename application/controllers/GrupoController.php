<?php

class GrupoController extends Zend_Controller_Action
{

    public function init()
    {
        
    }

    public function indexAction()
    {	
        $form = new Application_Form_FormGrupo();
        $aut = Zend_Auth::getInstance();
        $objGrupoDao = new Application_Model_GrupoDao();
        $objUsuarioGrupo = new Application_Model_UsuarioGrupo();
        $objUsuarioGrupoDao = new Application_Model_UsuarioGrupoDao();
        $objGrupo = new Application_Model_Grupo();
        $objPublicacion = new Application_Model_Publicacion();
        $objPublicacionDao = new Application_Model_PublicacionDao();
        $objInvitacion = new Application_Model_InvitacionDao();
        
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
        		
        		$objPublicacion->setFecha($fechahora);
        		$objPublicacion->setFoto($fechahora."_".$foto_name);
        		$objPublicacion->setPrivacidadId(7);
        		$objPublicacion->setTexto($this->getRequest()->getParam('txtDescripcion'));
        		$objPublicacion->setUsuarioPara($aut->getIdentity()->usu_id);
        		$objPublicacion->setTipoId(5);
        		$objPublicacion->setUsuarioId($aut->getIdentity()->usu_id);
        		$objPublicacionDao->guardar($objPublicacion);

        		$idGrupo = $objGrupoDao->guardar($objGrupo);
        		$objUsuarioGrupo->setParticipa(0);
        		$objUsuarioGrupo->setGrupoId($idGrupo);
        		$objUsuarioGrupo->setUsuarioId($aut->getIdentity()->usu_id);
        		$objUsuarioGrupo->setFechaParticipa($fechahora);
        		$objUsuarioGrupoDao->guardar($objUsuarioGrupo);
        		

        		
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
        $grupoId = $this->getRequest()->getParam('grupoId');
        
        $objUsuarioGrupoDao->eliminarUsuariosPorGrupoId($grupoId);
        $objGrupoDao->eliminar($grupoId);
        
        $this->view->ok = "ok";
    }
    
    public function editarAction()
    {
        
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
        
        $this->_redirect('/grupo/contactos/grupoId/' . $grupoId);
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
        
        $grupoId = $this->getRequest()->getParam('grupoId');
        $fecha = new DateTime();
        $fechahora = str_replace(" ","",str_replace("-","",str_replace(":","",$fecha->format('Y-m-d H:i:s'))));
        $objInvitacionDao = new Application_Model_InvitacionDao();
        $listaInvitacion = $objInvitacionDao->obtenerGrupoPorInvitar($grupoId);
        
        foreach ($listaInvitacion as $item)
        {
            $id = $item->getId();
            $item->setId($id);
            $item->setFecha($fechahora);
            $item->setEstado(2);
            $objInvitacionDao->guardar($item);
    	}
    	
    	$this->_redirect('/grupo/contactos/grupoId/' . $grupoId);
    	
    }

    public function aceptarInvitacionAction()
    {
        $this->_helper->layout()->disableLayout();
        $invitacionId = $this->getRequest()->getParam('invitacionId');
        
        $fecha = new DateTime();
        $fechahora = str_replace(" ","",str_replace("-","",str_replace(":","",$fecha->format('Y-m-d H:i:s'))));
        
        $objInvitacionDao = new Application_Model_InvitacionDao();
        $objInvitacion = $objInvitacionDao->obtenerPorId($invitacionId);
        
        $objUsuarioGrupo = new Application_Model_UsuarioGrupo();
        $objUsuarioGrupoDao = new Application_Model_UsuarioGrupoDao();
        
        $objUsuarioGrupo->setFechaParticipa($fechahora);
        $objUsuarioGrupo->setUsuarioId($objInvitacion->getUsuarioId());
        $objUsuarioGrupo->setGrupoId($objInvitacion->getIdActividad());
        $objUsuarioGrupo->setParticipa(1);
        $objUsuarioGrupoDao->guardar($objUsuarioGrupo);
        
        $id = $objInvitacion->getId();
        $objInvitacion->setId($id);
        $objInvitacion->setFecha($fechahora);
        $objInvitacion->setEstado(4);
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
    
}

