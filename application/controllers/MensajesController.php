<?php

class MensajesController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
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
        	    $usuPara = $usuarioDao->obtenerPorNombreExacto($this->getRequest()->getParam('txtPara'));
        	    
        	    $fechahora = str_replace(" ","",str_replace("-","",str_replace(":","",$fecha->format('Y-m-d H:i:s'))));
        	    $objMensaje->setDe($aut->getIdentity()->usu_id);
        	    $objMensaje->setPara($usuPara->getId());
        	    $objMensaje->setFecha($fechahora);
        	    $objMensaje->setTexto($this->getRequest()->getParam('txtTextoMensaje'));
        	    $objMensajeDao->guardar($objMensaje);
        	    
        	    $this->view->mensaje_ok = "ok";
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
        	$formData = $this->_request->getPost();
        	if($form->isValid($this->_getAllParams()))
        	{
        		$fechahora = str_replace(" ","",str_replace("-","",str_replace(":","",$fecha->format('Y-m-d H:i:s'))));
        		$objMensaje->setDe($aut->getIdentity()->usu_id);
        		$objMensaje->setPara($usu_id_menu);
        		$objMensaje->setFecha($fechahora);
        		$objMensaje->setTexto($this->getRequest()->getParam('txtTextoResponder'));
        		$objMensajeDao->guardar($objMensaje);
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


}

