<?php

class MensajesController extends Zend_Controller_Action
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
        $objMensaje = new Application_Model_Mensaje();
        $objMensajeDao = new Application_Model_MensajeDao();
        $form = new Application_Form_FormNuevoMensaje();
        $usuarioDao = new Application_Model_UsuarioDao();
        $fecha = new DateTime();
        
        if($this->getRequest()->isPost())
        {
        	$formData = $this->_request->getPost();
        	if($form->isValid($this->_getAllParams()))
        	{
        	    $objNotificacion = new Application_Model_Notificacion();
        	    $objNotificacionDao = new Application_Model_NotificacionDao();
        	    
        	    	$idUsuarioPara = $this->getRequest()->getParam('hdnDestinarioSeleccionado');
        	    
	        	    $fechahora = str_replace(" ","",str_replace("-","",str_replace(":","",$fecha->format('Y-m-d H:i:s'))));
	        	    $objMensaje->setDe($aut->getIdentity()->usu_id);
	        	    $objMensaje->setPara($idUsuarioPara);
	        	    $objMensaje->setFecha($fechahora);
	        	    $objMensaje->setTexto($this->getRequest()->getParam('txtTextoMensaje'));
	        	    $objMensajeDao->guardar($objMensaje);
	        	    $textoInv = $aut->getIdentity()->usu_nombre.' te ha enviado un mensaje';
	        	    $objNotificacion->setTipoNotificacionId(5);
	        	    $objNotificacion->setVista(0);
	        	    $objNotificacion->setFecha($fechahora);
	        	    $objNotificacion->setUsuarioId($idUsuarioPara);
	        	    $objNotificacion->setTexto($textoInv);
	        	    $objNotificacion->setActividad($aut->getIdentity()->usu_id);
	        	    $objNotificacionDao->guardar($objNotificacion);
	        	    
	        	    $this->view->mensaje_ok = "ok";
	        	    
        	}else{
        	    $this->view->error = "error";
        	}
        }
        
        $listaUsuarios = $objMensajeDao->UsuariosEnviadosRecibidos($aut->getIdentity()->usu_id);

        //plantilla de paginator
        Zend_View_Helper_PaginationControl::setDefaultViewPartial ( 'paginator/items.phtml' );
        $paginatorMensajes = Zend_Paginator::factory($listaUsuarios);
        $paginatorMensajes->setDefaultItemCountPerPage( 5 );
        
        if ($this->_hasParam ( 'page' )) {
        	$paginatorMensajes->setCurrentPageNumber( $this->_getParam ( 'page' ) );
        }
        
		$this->view->listaUsuarios = $paginatorMensajes;
        $this->view->form = $form;
        
    }
    
    public function verAction()
    {
        $aut = Zend_Auth::getInstance();
        $objMensaje = new Application_Model_Mensaje();
        $objMensajeDao = new Application_Model_MensajeDao();
		$form = new Application_Form_FormResponderMensaje();
        $usu_id_menu = $this->getRequest()->getParam('id');
        $fecha = new DateTime();
        
        if($this->getRequest()->isPost())
        {
            $objNotificacion = new Application_Model_Notificacion();
            $objNotificacionDao = new Application_Model_NotificacionDao();
            
        	$formData = $this->_request->getPost();
        	if($form->isValid($this->_getAllParams()))
        	{
        		$fechahora = str_replace(" ","",str_replace("-","",str_replace(":","",$fecha->format('Y-m-d H:i:s'))));
        		$objMensaje->setDe($aut->getIdentity()->usu_id);
        		$objMensaje->setPara($usu_id_menu);
        		$objMensaje->setFecha($fechahora);
        		$objMensaje->setTexto($this->getRequest()->getParam('txtTextoResponder'));
        		$objMensajeDao->guardar($objMensaje);
        		$textoInv = $aut->getIdentity()->usu_nombre.' te ha enviado un mensaje';
        		$objNotificacion->setTipoNotificacionId(5);
        		$objNotificacion->setVista(0);
        		$objNotificacion->setFecha($fechahora);
        		$objNotificacion->setUsuarioId($usu_id_menu);
        		$objNotificacion->setTexto($textoInv);
        		$objNotificacion->setActividad($aut->getIdentity()->usu_id);
        		$objNotificacionDao->guardar($objNotificacion);
        	}
        }
        
        $listamensajes = $objMensajeDao->obtenerMensajes($aut->getIdentity()->usu_id, $usu_id_menu);
        
        //Paginador
        //plantilla de paginator
        Zend_View_Helper_PaginationControl::setDefaultViewPartial ( 'paginator/itemsv2.phtml' );
        
        $paginatorMensajes = Zend_Paginator::factory($listamensajes);
        $paginatorMensajes->setDefaultItemCountPerPage(10);
        
        if ($this->_hasParam ( 'page' )) {
        	$paginatorMensajes->setCurrentPageNumber( $this->_getParam ( 'page' ) );
        }	
        
        $this->view->id = $usu_id_menu;
        $this->view->listamensajes = $paginatorMensajes;
        $this->view->form = $form;
    }
    
    function obtenerContactosAction()
    {
        $this->_helper->layout()->disableLayout();
        $aut = Zend_Auth::getInstance();
        
        $objAmigoDao = new Application_Model_AmigoDao();
        $objUsuarioDao = new Application_Model_UsuarioDao();
        $objMensajeDao = new Application_Model_MensajeDao();
        
        $listaUsuarios = $objAmigoDao->obtenerTodosUsuariosPorUsuarioId($aut->getIdentity()->usu_id);
        
        $columna = 0;
        $cantColumna = 8;
        
       $html =  "<table width='100%' align='center'>";
         foreach ($listaUsuarios as $usuario){
        	if($columna == 0){
        		$html .= "<tr>";
        	}
        							$html .= "<td>";
        								$html .= "<table align='center'>";
        									$html .= "<tr>";
        										$html .= "<td align='center'>";
        												$html .= $usuario->getNombre();
        											$html .= "<input type='radio' name='optUsuario' value='".$usuario->getId()."' onclick='elDestinatario(".$usuario->getId().")'>";
        											$html .= "<input type='hidden' id='hdnNomUsr".$usuario->getId()."' name='hdnNomUsr".$usuario->getId()."' value='".$usuario->getNombre()."'>";
        										$html .= "</td>";
        									$html .= "</tr>";
        									$html .= "<tr>";
        										$html .= "<td align='center'>";
        											$html .= "<img width='70px' src='/imagenes/usuarios/icono/".$usuario->getFoto()."'></img>";
        										$html .= "</td>";
        									$html .= "</tr>";
        								$html .= "</table>";						
        							$html .= "</td>"; 
        							$columna++;
        							 if($columna == $cantColumna){
        								 $html .= "</tr>";				
        								 $columna = 0;
        							 }
        						 }
        						 $html .= "</tr>";
        				$html .= "</table>";
        					
			$this->view->ok = $html;
        
    }


}

