<?php

class PublicacionController extends Zend_Controller_Action
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
        $id = $this->getRequest()->getParam('id');
        $aut = Zend_Auth::getInstance();
        
        $objPublicacionDao = new Application_Model_PublicacionDao();
        $objUsuarioDao = new Application_Model_UsuarioDao();
        $objComentarioDao = new Application_Model_ComentarioDao();
        
        $objPublicacion = $objPublicacionDao->obtenerPorId($id);
        $objUsuario = $objUsuarioDao->obtenerPorId($objPublicacion->getUsuarioId());
        $listaComentarios = $objComentarioDao->obtenerTodosPorPublicacionId($id);
        
        //SEGURIDAD
        $flagPermiso = false;
        $objPerfilRedSocialDao = new Application_Model_PerfilRedSocialDao();
        $perfilRedSocial = $objPerfilRedSocialDao->obtenerPorUsuarioId($aut->getIdentity()->usu_id)->getNombre();
        $objPerfilRedSocialDao = null;
        
        $objAmigoDao = new Application_Model_AmigoDao();
        if($this->getRequest()->getParam('id') != null)
        {
            $flag = false;
        	if($aut->getIdentity()->usu_id == $objPublicacion->getUsuarioPara())
        	{
        	    $flag = true;
        	}
        	
        	if($aut->getIdentity()->usu_id == $objPublicacion->getUsuarioId())
        	{
        	    $flag = true;
        	}
        	
        	if($flag == false){
        	
        		if($objAmigoDao->sonAmigos($aut->getIdentity()->usu_id, $objPublicacion->getUsuarioPara()) == false)
        		{
        		    echo "<img src='/imagenes/proyecto/denegado.png'>&nbsp;&nbsp;&nbsp;TÚ NO TIENES ACCESO A ESTA INFORMACIÓN.";
        		    exit();
        		}else{
        		    if($objPublicacion->getPrivacidadId() == 1 && $perfilRedSocial == "Alumno")
        		    {
        		        $flagPermiso = true;
        		    }
        		    
        		    if($objPublicacion->getPrivacidadId() == 2 && $perfilRedSocial == "Profesor")
        		    {
        		        $flagPermiso = true;
        		    }
        		    
        		    if($objPublicacion->getPrivacidadId() == 3 && $perfilRedSocial == "Funcionario")
        		    {
        		        $flagPermiso = true;
        		    }
        		    
        		    if($objPublicacion->getPrivacidadId() == 4 && ($perfilRedSocial == "Alumno" || $perfilRedSocial == "Profesor"))
        		    {
        		        $flagPermiso = true;
        		    }
        		    
        		    if($objPublicacion->getPrivacidadId() == 5 && ($perfilRedSocial == "Alumno" || $perfilRedSocial == "Funcionario"))
        		    {
        		        $flagPermiso = true;
        		    }
        		    
        		    if($objPublicacion->getPrivacidadId() == 6 && ($perfilRedSocial == "Profesor" || $perfilRedSocial == "Funcionario"))
        		    {
        		        $flagPermiso = true;
        		    }
        		    
        		    if($flagPermiso == false)
        		    {
        		    	echo "<img src='/imagenes/proyecto/denegado.png'>&nbsp;&nbsp;&nbsp;TÚ NO TIENES ACCESO A ESTA INFORMACIÓN.";
        		    	exit();
        		    }
        		}
        	}
        }
        //SEGURIDAD
        
        $this->view->publicacion = $objPublicacion;
        $this->view->usuario = $objUsuario;
        $this->view->comentarios = $listaComentarios;
        
    }

}

