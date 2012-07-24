<?php

class PerfilController extends Zend_Controller_Action
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
        $form = new Application_Form_FormPerfil();
         
        if($this->getRequest()->isPost())
        {
            $formData = $this->_request->getPost();
        	if($form->isValid($this->_getAllParams()))
        	{
        	    $aut = Zend_Auth::getInstance();
        	    
        	    $objUsuario = new Application_Model_Usuario();
        	    $objUsuarioDao = new Application_Model_UsuarioDao();
        	    
        	    if(isset($_FILES['fileFoto']['name']) && $_FILES['fileFoto']['name'] != "")
        	    {
        	        $fecha = new DateTime();
        	        $fechahora = str_replace(" ","",str_replace("-","",str_replace(":","",$fecha->format('Y-m-d H:i:s'))));
        	        
        	    	$foto_name  = $_FILES['fileFoto']['name'];
        	    	$foto_tmp 	= $_FILES['fileFoto']['tmp_name'];
        	    	$foto_ext   = str_replace("image/","",$_FILES['fileFoto']['type']);
        	    	
					$location_perfil = "/var/www/rsciisa/public/imagenes/usuarios/".$fechahora."_".$foto_name;
					$location_ico = "/var/www/rsciisa/public/imagenes/usuarios/icono/".$fechahora."_".$foto_name;
					
        	    	copy($foto_tmp, $location_perfil);
        	    	$objUsuario->setFoto($fechahora."_".$foto_name);
        	    	
        	    	$objUtilidad = new Application_Model_Utilidad();
					$tmp_perfil = $objUtilidad->recortarImagen($location_perfil,$foto_ext,300,300);
					$tmp_ico = $objUtilidad->recortarImagen($location_perfil,$foto_ext,80,80);
					
					if($foto_ext == "png")
					{
					    imagepng($tmp_perfil,$location_perfil,0);
					    imagepng($tmp_ico,$location_ico,0);
					}else{
					    //jpeg, jpg
					    imagejpeg($tmp_perfil,$location_perfil,95);
					    imagejpeg($tmp_ico,$location_ico,95);
					}
					
					//se elimina la foto anterior
					$objUsuarioAux = $objUsuarioDao->obtenerPorId($aut->getIdentity()->usu_id);
					$foto = $objUsuarioAux->getFoto();
					if($foto != "user.png")
					{
						unlink("/var/www/rsciisa/public/imagenes/usuarios/icono/".$foto);
						unlink("/var/www/rsciisa/public/imagenes/usuarios/".$foto);
					}
        	    	
        	    }else{
        	        $objUsuario->setFoto($objUsuarioDao->obtenerPorId($aut->getIdentity()->usu_id)->getFoto());
        	    }
        	    
        	    $objUsuario->setId($aut->getIdentity()->usu_id);
        	    $objUsuario->setNombre($this->getRequest()->getParam('txtNombre'));
        	    $objUsuario->setBiografia($this->getRequest()->getParam('txtBiografia'));
        	    
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

