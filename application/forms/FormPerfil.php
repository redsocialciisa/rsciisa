<?php

class Application_Form_FormPerfil extends Zend_Form
{

    public function init()
    {
        $aut = Zend_Auth::getInstance();
        $objUsuarioDao = new Application_Model_UsuarioDao();
        $objUsuario = $objUsuarioDao->obtenerPorId($aut->getIdentity()->usu_id);
        
        $fileFoto = new Zend_Form_Element_File('fileFoto');
        $fileFoto->setLabel('Foto Perfil: ')
		        ->addValidator('IsImage')
		        ->setAttrib('class', 'span4')
		        ->setMaxFileSize(2097152) // 2mb
		        ->addValidator('Extension',false,array('jpg','jpeg','png','gif'));
        
        $nombre = new Zend_Form_Element_Text('txtNombre');
        $nombre->setLabel('Nombre: ')
        	   ->setRequired(true)
        	   ->setValue($objUsuario->getNombre())
        	   ->setAttrib('class', 'span4')
        	   ->setAttrib('onkeypress', 'return soloLetras(event)')
        	   ->clearErrorMessages()
               ->addErrorMessage('Debes ingresar el nombre de usuario');
        
        $carrera = new Zend_Form_Element_Text('txtCarrera');
        $carrera->setLabel('Carrera: ')
                ->setValue($aut->getIdentity()->carrera)
                ->setAttrib('class', 'span4')
      		    ->setAttrib('readonly', true);        
        
        $jornada = new Zend_Form_Element_Text('txtJornada');
        $jornada->setLabel('Jornada: ')
        		->setAttrib('readonly', true);
        
        $perfilciisa = new Zend_Form_Element_Text('txtPerfil');
        $perfilciisa->setLabel('Perfil CIISA: ')
        			->setValue($aut->getIdentity()->perfil_ciisa)
        			->setAttrib('readonly', true);
        
        $fecha = new Zend_Form_Element_Text('txtFechaNacimiento');
        $fecha->setLabel('Fecha Nacimiento: ')
        	    ->setValue($objUsuario->getFechaNacimiento())
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
        
        $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);
        $fileCv = new Zend_Form_Element_File('fileCurriculum');
        $fileCv->setLabel('Curriculum Vitae: ')
        		->setAttrib('class', 'span4')
        		->setMaxFileSize(2097152) // 2mb
        		->addValidator('Extension',false,array('doc','pdf'));
        
        $buttonEnviar = $this->createElement('submit', 'enviar');
        $buttonEnviar->setLabel('Actualizar mis datos')
        			 ->setAttrib('class', 'label label-success');
        
        $this->addElement($fileFoto);
        $this->addElement($nombre);
        $this->addElement($carrera);
        $this->addElement($jornada);
        $this->addElement($perfilciisa);
        $this->addElement($fecha);
        $this->addElement($correo);
        $this->addElement($fileCv);
        $this->addElement($buttonEnviar);
    }

}




