<?php

class MuroController extends Zend_Controller_Action
{

    public function init()
    {
        //validación, si no está logeado vuelve al login
		$aut = Zend_Auth::getInstance();
		if($aut->hasIdentity() == false){
			$this->_redirect('/auth');
		}
		
		//$aut = Zend_Auth::getInstance();
		//Zend_Debug::dump($aut->getIdentity());exit;
    }

    public function indexAction()
    {
        $aut = Zend_Auth::getInstance();
        $perfil = $aut->getIdentity()->perfil_ciisa;
        $objPublicacionDao = new  Application_Model_PublicacionDao();
        
        //se obtiene el listado de publicaciones según el perfil entrante
        $listaPublicaciones = $objPublicacionDao->obtenerMuroPrincipal($perfil);
        
        //plantilla de paginator
        Zend_View_Helper_PaginationControl::setDefaultViewPartial ( 'paginator/items.phtml' );
        
        $paginator = Zend_Paginator::factory( $listaPublicaciones );
        
        $paginator->setDefaultItemCountPerPage( 5 );
        
        if ($this->_hasParam ( 'page' )) {
        	$paginator->setCurrentPageNumber( $this->_getParam ( 'page' ) );
        }
        
        $this->view->listaPublicaciones = $paginator;
        
    }
    
    public function comentarPublicacionAction()
    {
        $this->_helper->layout()->disableLayout();
        $aut = Zend_Auth::getInstance();
        
        $texto = $this->getRequest()->getParam('texto');
        $idPublicacion = $this->getRequest()->getParam('idPublicacion');

        $objComentario = new Application_Model_Comentario();
        $objComentarioDao = new Application_Model_ComentarioDao();
        $objUsuarioDao = new Application_Model_UsuarioDao();
        
        //se registra el omentario
        $objComentario->setPublicacionId($idPublicacion);
        $objComentario->setTexto($texto);
        $objComentario->setUsuarioId($aut->getIdentity()->usu_id);
        
        $now = new DateTime();
        $objComentario->setFecha($now->format('Y-m-d H:i:s'));     
        
        $idCom = $objComentarioDao->guardar($objComentario);
        
        $listaComentarios = $objComentarioDao->obtenerPorPublicacionId($idPublicacion);
        
        $htmlComentarios = "<div id='divComentariosPublicacion$idPublicacion'>";
        if(count($listaComentarios) > 0){
            foreach ($listaComentarios as $comentario){
                $objUsuario = $objUsuarioDao->obtenerPorId($comentario->getUsuarioId());
                
                $htmlComentarios .= "<table><tr><td valign='top'>";
                
                $htmlComentarios .= "<div style='width: 50px;'><a class='thumbnail'>";  
                $htmlComentarios .= "<img src='/imagenes/usuarios/".$objUsuario->getFoto()."'>";
                $htmlComentarios .= "</div>";
                
                $htmlComentarios .= "</td><td valign='top'>";
                
                if($idCom == $comentario->getId())
                {                
                	$htmlComentarios .= "<p class='label label-info span4'>".$objUsuario->getNombre()."</p>";
                }else{
                    $htmlComentarios .= "<p class='label span4'>".$objUsuario->getNombre()."</p>";
                }
                    
				$htmlComentarios .= "<div class='cont'><p>".$comentario->getTexto()."</p></div>";
                $htmlComentarios .= "<p>".$comentario->getFecha()."</p>";
                $htmlComentarios .= "</td></tr></table>";
                
            }
        }
        
        $htmlComentarios .= "comentarios: <span class='badge badge-info'>".$objComentarioDao->obtenerCantidadComentariosPorPublicacionId($idPublicacion)."</span>";
        
        $htmlComentarios .= "</div>";

        $objComentario = null;
        $objComentarioDao = null;
        $objUsuarioDao = null;
        
        $this->view->ok = $htmlComentarios;
    }
    
    public function miMuroAction()
    {
        $aut = Zend_Auth::getInstance();
        //$perfil = $aut->getIdentity()->perfil_ciisa;
        $objPublicacionDao = new  Application_Model_PublicacionDao();
        
        //se obtiene el listado de publicaciones según el perfil entrante
        $listaPublicaciones = $objPublicacionDao->obtenerMisPublicaciones();
        
        //plantilla de paginator
        Zend_View_Helper_PaginationControl::setDefaultViewPartial ( 'paginator/items.phtml' );
        
        $paginator = Zend_Paginator::factory( $listaPublicaciones );
        
        $paginator->setDefaultItemCountPerPage( 5 );
        
        if ($this->_hasParam ( 'page' )) {
        	$paginator->setCurrentPageNumber( $this->_getParam ( 'page' ) );
        }
        
        $formPublicacion = new Application_Form_FormPublicacion();
        if($this->getRequest()->isPost())
        {
        	$formData = $this->_request->getPost();
        	if($formPublicacion->isValid($this->_getAllParams()))
        	{
        	    $objPublicacion = new Application_Model_Publicacion();
        	    $objPublicacionDao = new Application_Model_PublicacionDao();
        	    
        		$aut = Zend_Auth::getInstance();
        		
        		$tipoPrivacidad = 0;
        		if($this->getRequest()->getParam('cbxAlumno'))
        		{
        			$tipoPrivacidad = 1;
        		}
        		
        		if($this->getRequest()->getParam('cbxProfesor'))
        		{
        			$tipoPrivacidad = 2;
        		}
        		
        		if($this->getRequest()->getParam('cbxAcademico'))
        		{
        			$tipoPrivacidad = 3;
        		}
        		
        		if($this->getRequest()->getParam('cbxAlumno') && $this->getRequest()->getParam('cbxProfesor'))
        		{
        			$tipoPrivacidad = 4;
        		}
        		
        		if($this->getRequest()->getParam('cbxAlumno') && $this->getRequest()->getParam('cbxAcademico'))
        		{
        			$tipoPrivacidad = 5;
        		}
        		
        		if($this->getRequest()->getParam('cbxProfesor') && $this->getRequest()->getParam('cbxAcademico'))
        		{
        			$tipoPrivacidad = 6;
        		}
        		
        		if($this->getRequest()->getParam('cbxAlumno') && $this->getRequest()->getParam('cbxProfesor') && $this->getRequest()->getParam('cbxAcademico'))
        		{
        			$tipoPrivacidad = 7;
        		}
        		
        		$objPublicacion->setUsuarioId($aut->getIdentity()->usu_id);
        		$objPublicacion->setUsuarioPara($aut->getIdentity()->usu_id);
        		$objPublicacion->setTexto($this->getRequest()->getParam('txtTextoPublicacion'));
        		$objPublicacion->setTipoId($this->getRequest()->getParam('grpTipo'));
        		$objPublicacion->setPrivacidadId($tipoPrivacidad);
        		$objPublicacion->setVideo($this->getRequest()->getParam('txtVideo'));
        		
        		$fecha = new DateTime();
        		$objPublicacion->setFecha($fecha->format('Y-m-d H:i:s'));
        		
        		$fecha = new DateTime();
        		$fechahora = str_replace(" ","",str_replace("-","",str_replace(":","",$fecha->format('Y-m-d H:i:s'))));
        		
        		if(isset($_FILES['fileFoto']['name']) && $_FILES['fileFoto']['name'] != "")
        		{
        		    $fecha = new DateTime();
        		    $fechahora = str_replace(" ","",str_replace("-","",str_replace(":","",$fecha->format('Y-m-d H:i:s'))));
        		    
	        		$foto_name = $_FILES['fileFoto']['name'];
	        		$objPublicacion->setFoto($fechahora."_".$foto_name);
	        		$foto_tmp 	= $_FILES['fileFoto']['tmp_name'];
	        		copy($foto_tmp, "/var/www/rsciisa/public/imagenes/fotos/".$fechahora."_".$foto_name);
        		}
        		
        		$objPublicacionDao->guardar($objPublicacion);
        		
        		$this->view->success = "success";
        		$this->_redirect('/muro/mi-muro');
        		
        	}else{
        	    //$formPublicacion->optFoto->setAttrib('checked',true);
        	    $formPublicacion->populate($formData);
        	    $this->view->error = "error";        	    
        	}
        }
        
        $this->view->listaPublicaciones = $paginator;
        $this->view->formPublicacion = $formPublicacion;
        
    }
    
    public function muroContactoAction()
    {
        $idUsuario = $this->getRequest()->getParam('id');
        
    	$aut = Zend_Auth::getInstance();
    	if($aut->getIdentity()->usu_id == $idUsuario)
    	{
    	    $this->_redirect('/muro/mi-muro');
    	}
    	
    	$objPublicacionDao = new  Application_Model_PublicacionDao();
    	$objUsuarioDao = new Application_Model_UsuarioDao();
    
    	//se obtiene el listado de publicaciones según el perfil entrante
    	$listaPublicaciones = $objPublicacionDao->obtenerPublicacionesContacto($idUsuario);
    
    	//plantilla de paginator
    	Zend_View_Helper_PaginationControl::setDefaultViewPartial ( 'paginator/items.phtml' );
    
    	$paginator = Zend_Paginator::factory( $listaPublicaciones );
    	$paginator->setDefaultItemCountPerPage( 5 );
    
    	if ($this->_hasParam ( 'page' )) {
    		$paginator->setCurrentPageNumber( $this->_getParam ( 'page' ) );
    	}
    
    	$this->view->paginaActual =  $this->getRequest()->getParam('page');
    	$this->view->listaPublicaciones = $paginator;
    	$this->view->Contacto = $objUsuarioDao->obtenerPorId($idUsuario);
    	
    	$objUsuarioDao = null;
    	$objPublicacionDao = null;
    
    }
    
    public function noticiasAction()
    {
    	$aut = Zend_Auth::getInstance();
    	//$perfil = $aut->getIdentity()->perfil_ciisa;
    	$objPublicacionDao = new  Application_Model_PublicacionDao();
    
    	//se obtiene el listado de publicaciones según el perfil entrante
    	$listaPublicacionesGEO = $objPublicacionDao->obtenerPublicacionesGrupoEventoOferta();
    
    	//plantilla de paginator
    	Zend_View_Helper_PaginationControl::setDefaultViewPartial ( 'paginator/items.phtml' );
    
    	$paginator = Zend_Paginator::factory( $listaPublicacionesGEO );
    	$paginator->setDefaultItemCountPerPage( 5 );
    
    	if ($this->_hasParam ( 'page' )) {
    		$paginator->setCurrentPageNumber( $this->_getParam ( 'page' ) );
    	}
    
    	$this->view->paginaActual =  $this->getRequest()->getParam('page');
    	$this->view->listaPublicaciones = $paginator;
    
    }
    
    public function validaYoutubeAction()
    {
        $this->_helper->layout()->disableLayout();
        $urlYotube = $this->getRequest()->getParam('youtube');
        
        $headers = get_headers('http://gdata.youtube.com/feeds/api/videos/' . $urlYotube);
        if (!strpos($headers[0], '200')) {
            $this->view->flag = "error";
        }else{
            $this->view->flag = "success";
        }
    }
    
    public function logoutAction()
    {
    	Zend_Auth::getInstance()->clearIdentity();
    	$this->_redirect('/auth');
    }


}




