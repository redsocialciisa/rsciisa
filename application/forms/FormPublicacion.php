<?php

class Application_Form_FormPublicacion extends Zend_Form
{

    public function init()
    {
        $aut = Zend_Auth::getInstance();
        $objUsuarioDao = new Application_Model_UsuarioDao();
        $objUsuario = $objUsuarioDao->obtenerPorId($aut->getIdentity()->usu_id);
        
        $alu = false;
        $pro = false;
        $aca = false;
        
        switch ($objUsuario->getPrivacidadPublicacionId()) {
        	case 1:
        		$alu = true;
        	break;
        	case 2:
        		$pro = true;
        	break;
        	case 3:
        		$aca = true;
        	break;
        	case 4:
        	    $alu = true;
        	    $pro = true;
        	break;
        	case 5:
        	    $alu = true;
        	    $aca = true;
        	break;
        	case 6:
        	    $pro = true;
        	    $aca = true;
        	break;
        	case 7:
        	    $alu = true;
        	    $pro = true;
        	    $aca = true;
        	break;
        }
        
        $this->setAction('/muro/mi-muro/');
        $this->setMethod('post');
        $this->setName('formPublicacion');
        $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);
        
        $tipoTexto = new Zend_Form_Element_Radio('optTexto');
        $tipoTexto->addMultiOptions(array(
        		'1' => 'Texto',
        ))
        ->setAttrib('onclick', 'desabilitarFotoVideo()')
        ->setAttrib('name', 'grpTipo');
        
        $tipoFoto = new Zend_Form_Element_Radio('optFoto');
        $tipoFoto->addMultiOptions(array(
        		'2' => 'Foto',
        ))
        ->setAttrib('checked',true)
        ->setAttrib('onclick', 'desabilitarVideo()')
        ->setAttrib('name', 'grpTipo');
        
        $tipoVideo = new Zend_Form_Element_Radio('optVideo');
        $tipoVideo->addMultiOptions(array(
        		'3' => 'Video',
        ))
        ->setAttrib('onclick', 'desabilitarFoto()')
        ->setAttrib('name', 'grpTipo');
        
        /*
        $nombre = new Zend_Form_Element_Textarea('txtTextoPublicacion');
        $nombre->setRequired(true)
        	   ->setValue('')
        	   ->setAttrib('rows', 4)
        	   ->setAttrib('class', 'span4')
        	   ->clearErrorMessages()
        	   ->setName('txtTextoPublicacion')
               ->addErrorMessage('Escribe algo...'); */
        
        $fileFoto = new Zend_Form_Element_File('fileFoto');
        $fileFoto->addValidator('IsImage')
		        ->setAttrib('class', 'span2')
		        ->setMaxFileSize(2097152) // 2mb
		        ->addValidator('Extension',false,array('jpg','jpeg','png','gif'));
       
        $txtVideo = new Zend_Form_Element_Text('txtVideo');
        $txtVideo->setValue('')
        		 //->setAttrib('onblur','ValidaUrlYouTube(this.value)')
		         ->setAttrib('class', 'span4');
        
        $cbxAlumno = new Zend_Form_Element_Checkbox('cbxAlumno');
        $cbxAlumno->setChecked(true)
        		  ->setChecked($alu);
        
        $cbxProfesor = new Zend_Form_Element_Checkbox('cbxProfesor');
        $cbxProfesor->setChecked(true)
        			->setChecked($pro);
        
        $cbxAcademico = new Zend_Form_Element_Checkbox('cbxAcademico');
        $cbxAcademico->setChecked(true)
        			 ->setChecked($aca);
        
        //$hdnTipoPublicacion = new Zend_Form_Element_Hidden('')
        
       // $buttonEnviar = $this->createElement('submit', 'btnCrearPublicacion');
       // $buttonEnviar->setLabel('Crear Publicacion')
       	//			 ->setAttrib('class', 'label label-success');
        
        $this->addElement($tipoTexto);
        $this->addElement($tipoFoto);
        $this->addElement($tipoVideo);
        //$this->addElement($nombre);
        $this->addElement($fileFoto);
        $this->addElement($txtVideo);
        $this->addElement($cbxAlumno);
        $this->addElement($cbxProfesor);
        $this->addElement($cbxAcademico);
        //$this->addElement($buttonEnviar);
        
        /*
        $this->clearDecorators();
        $this->setElementDecorators(array(
        		array('ViewHelper'),
        		array('Errors'),
        		array('Description'),
        ));
        
        $fileFoto->setDecorators(array(
                       array('File'),
                       array('Errors'),
        ));*/
        
    }

}




