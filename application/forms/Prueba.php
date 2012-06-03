<?php

class Application_Form_Prueba extends Zend_Form
{

    public function init()
    {
        /*
        $textNombre = $this->createElement('text', 'nombre');
        $textNombre->setLabel('Nombre')
        		   ->setAttrib('required', 'required');
        
     	$this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);
     	$fileCv = $this->createElement('file', 'cv');
     	$fileCv->setLabel('Curriculum Vitae')
     			   ->addValidator('IsImage')
     			   ->addValidator('Count',false,1)
     			   ->addValidator('Size',false,5242880)
     			   ->addValidator('Extension',false,array('jpg','jpeg','png','gif'));*/
        
        $nombre = $this->createElement('text', 'Nombre');
        //$fileCv = new Zend_Form_Element_File('id');
        $nombre->setLabel('Clave')        	  
        	   ->addValidator('stringLength',false,array(4,8))
        	   ->setRequired(true)
        	   //->clearErrorMessages()
        	   ->addErrorMessage('Nombre de usuario demasiado corto');
        
        $fileCv = new Zend_Form_Element_File('fileCurriculum');
        $fileCv->setLabel('Curriculum Vitae')
        	   ->addValidator('IsImage')
        	   ->setMaxFileSize(2097152) // 2mb
        	   ->addValidator('Extension',false,array('jpg','jpeg','png','gif'));
     	
     	$buttonEnviar = $this->createElement('submit', 'enviar');
     	$buttonEnviar->setLabel('Enviar Formulario');
     	
     	$this->addElement($nombre);
     	$this->addElement($fileCv);
     	$this->addElement($buttonEnviar);
     	
    }

}




