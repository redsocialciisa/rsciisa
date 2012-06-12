<?php

class PerfilController extends Zend_Controller_Action
{

 public function init()
 {
        
 }

    public function indexAction()
    {
        $form = new Application_Form_FormPerfil();
         
        if($this->getRequest()->isPost())
        {
            $formData = $this->_request->getPost();
        	if($form->isValid($this->_getAllParams()))
        	{
        	    $aut = Zend_Auth::getInstance();
        	    
        	    $objUsuario = new Application_Model_Usuario();
        	    $objUsuarioDao = new Application_Model_UsuarioDao();
        	    
        	    /*
        	    if(isset($_FILES['fileCurriculum']['name']) && $_FILES['fileCurriculum']['name'] != "")
        	    {
        	    	$cv_name = $_FILES['fileCurriculum']['name'];
        	    	$cv_tmp 	= $_FILES['fileCurriculum']['tmp_name'];
        	    	copy($cv_tmp, "/var/www/rsciisa/public/imagenes/cv/".$cv_name);
        	    }else{
        	        $objUsuario->setCv($aut->getIdentity()->usu_cv);
        	    }*/
        	    
        	    if(isset($_FILES['fileFoto']['name']) && $_FILES['fileFoto']['name'] != "")
        	    {
        	        $fecha = new DateTime();
        	        $fechahora = str_replace(" ","",str_replace("-","",str_replace(":","",$fecha->format('Y-m-d H:i:s'))));
        	        
        	    	$foto_name = $_FILES['fileFoto']['name'];
        	    	$foto_tmp 	= $_FILES['fileFoto']['tmp_name'];
        	    	copy($foto_tmp, "/var/www/rsciisa/public/imagenes/usuarios/".$fechahora."_".$foto_name);
        	    	$objUsuario->setFoto($fechahora."_".$foto_name);
        	    }else{
        	        $objUsuario->setFoto($objUsuarioDao->obtenerPorId($aut->getIdentity()->usu_id)->getFoto());
        	    }
        	    
        	    $objUsuario->setId($aut->getIdentity()->usu_id);
        	    $objUsuario->setNombre($this->getRequest()->getParam('txtNombre'));
        	    
        	    $date = strtotime($this->getRequest()->getParam('txtFechaNacimiento'));
        	    $objUsuario->setFechaNacimiento(date("Y", $date)."-".date("m", $date)."-".date("d", $date));
        	    
        	    $objUsuario->setCorreo($this->getRequest()->getParam('txtCorreo'));
        	    $objUsuario->setPrivacidadPublicacionId($this->getRequest()->getParam('sltPrivacidad'));
        	    $objUsuario->setEmocionId($aut->getIdentity()->emo_id);
        	    
        	    $objUsuarioDao->actualizar($objUsuario);
        	    
        	    $this->view->success = "ok";
        	    
        	}else{
        	    $form->populate($formData);
        	}
        }
        
        $this->view->form = $form;
    }

}

