<?php

class Application_Form_FormPerfil extends Zend_Form
{

    public function init()
    {
        $aut = Zend_Auth::getInstance();
        $objUsuarioDao = new Application_Model_UsuarioDao();
        $objCiisa = new Application_Model_Ciisa();
        $objUsuario = $objUsuarioDao->obtenerPorId($aut->getIdentity()->usu_id);
        $objPrivacidadPublicacion = new Application_Model_PrivacidadPublicacionDao();
        
        $listaPrivacidad = $objPrivacidadPublicacion->obtenerTodos();
        
        $fileFoto = new Zend_Form_Element_File('fileFoto');
        $fileFoto->setLabel('Foto Perfil: (jpg, jpeg, png)')
		        ->addValidator('IsImage')
		        ->setAttrib('class', 'span4')
		        ->setMaxFileSize(5199999) // 2mb
		        ->addValidator('Extension',false,array('jpg','jpeg','png'));
        
        $nombre = new Zend_Form_Element_Text('txtNombre');
        $nombre->setLabel('Nombre: ')
        	   ->setValue($objUsuario->getNombre())
        	   ->setAttrib('class', 'span4')
        	   ->setAttrib('onkeypress', 'return soloLetras(event)')
        	   ->setAttrib('readonly', true)
        	   ->clearErrorMessages()
               ->addErrorMessage('Debes ingresar el nombre de usuario');
        
        $biografia = new Zend_Form_Element_Textarea('txtBiografia');
        $biografia->setLabel('Biografía breve: ')
	        ->setValue($objUsuario->getBiografia())
	        ->setAttrib('onkeypress','ValidarCaracteres(this, 119)')
	        ->setAttrib('class', 'span4')
	        ->setAttrib('rows', '3');
        
        $carrera = new Zend_Form_Element_Text('txtCarrera');
        $carrera->setLabel('Carrera: ')
                ->setValue($aut->getIdentity()->carrera)
                ->setAttrib('class', 'span4')
      		    ->setAttrib('readonly', true);        
        
        $perfilciisa = new Zend_Form_Element_Text('txtPerfil');
        $perfilciisa->setLabel('Perfil CIISA: ')
        			->setValue($objCiisa->obtenerDescripcionPerfil($aut->getIdentity()->perfil_ciisa))
        			->setAttrib('readonly', true);
        
        $fecha = new Zend_Form_Element_Text('txtFechaNacimiento');
        $fecha->setLabel('Fecha Nacimiento: ')
        	    ->setValue(substr($objUsuario->getFechaNacimiento(),8,2).substr($objUsuario->getFechaNacimiento(),4,3)."-".substr($objUsuario->getFechaNacimiento(),0,4))
        	    ->setAttrib('id', 'txtFechaNacimiento')
        	    ->setAttrib('name', 'txtFechaNacimiento')
		        ->clearErrorMessages()
		        ->addErrorMessage('Debes ingresar tu fecha de nacimiento');
        
        $correo = new Zend_Form_Element_Text('txtCorreo');
        $correo->setLabel('Correo: ')
        		->setValue($objUsuario->getCorreo())
        		->setAttrib('class', 'span4')
		        ->addFilters(array('StringTrim', 'StripTags'))
		        ->addValidator('EmailAddress')
		        ->clearErrorMessages()
		        ->addErrorMessage('Correo electrónico inválido');
        
        $comboPrivacidad = new Zend_Form_Element_Select('sltPrivacidad');
        $comboPrivacidad->setLabel('Privacidad de Publicación: ');
         
        foreach ($listaPrivacidad as $item){
        	$comboPrivacidad->addMultiOption($item->getId(), $item->getNombre());
        }
        $comboPrivacidad->setValue($objUsuario->getPrivacidadPublicacionId());
        
        $buttonEnviar = $this->createElement('submit', 'enviar');
        $buttonEnviar->setLabel('Actualizar mis datos')
        			 ->setAttrib('class', 'btn btn-success');
        
        $this->addElement($fileFoto);
        $this->addElement($nombre);
        $this->addElement($biografia);
        if($aut->getIdentity()->perfil_ciisa == "ALUMNO" || $aut->getIdentity()->perfil_ciisa == "EX-ALUMNO")
        {
        	$this->addElement($carrera);
        }
		$this->addElement($perfilciisa);
        $this->addElement($fecha);
        $this->addElement($correo);
        $this->addElement($comboPrivacidad);
        $this->addElement($buttonEnviar);
    }

}




