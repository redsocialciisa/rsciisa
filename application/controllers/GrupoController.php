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
          
        //Paginador
        //plantilla de paginator
        Zend_View_Helper_PaginationControl::setDefaultViewPartial ( 'paginator/items.phtml' );
        
        $paginator = Zend_Paginator::factory($listaGrupoUsuario);
        
        $paginator->setDefaultItemCountPerPage( 5 );
        
        if ($this->_hasParam ( 'page' )) {
        	$paginator->setCurrentPageNumber( $this->_getParam ( 'page' ) );
        }
        
        $this->view->listaGrupoUsuario = $paginator;
        
       
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
    
}

