<?php

class Application_Form_FormAlbum extends Zend_Form
{

    public function init()
    {
        $aut = Zend_Auth::getInstance();
        $objUsuarioDao = new Application_Model_UsuarioDao();
        $objUsuario = $objUsuarioDao->obtenerPorId($aut->getIdentity()->usu_id);
        
        $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);
        
        $nombre = new Zend_Form_Element_Text('txtNombre');
        $nombre->setLabel('Nombre: ')
	        ->setRequired(true)
	        ->setValue('')
	        ->setAttrib('maxlength', '49')
	        ->setAttrib('class', 'span6')
	        ->clearErrorMessages()
	        ->addErrorMessage('Debes ingresar el nombre del álbum');
        
        $fotoDesc1 = new Zend_Form_Element_Textarea('fotoDesc1');
        $fotoDesc1->setLabel('Descripción: ')
	        ->setAttrib('rows','2')
	        ->setAttrib('onkeypress','ValidarCaracteres(this, 44)')
	        ->setAttrib('class','span3');
        
        $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);
        $fileFoto1 = new Zend_Form_Element_File('fileFoto1');
        $fileFoto1->setLabel(' ')
        		->setRequired(false)
		        ->addValidator('IsImage')
		        ->setAttrib('class', 'span4')
		        ->setMaxFileSize(5097152)
		        ->addValidator('Extension',false,array('jpg','jpeg','png'));
        
		$descripcion = new Zend_Form_Element_Textarea('txtDescripcion');
		$descripcion->setLabel('Descripcion del grupo: ')
					->setRequired(false)
					->setAttrib('class', 'span6')
					->setAttrib('rows', 2)
					->setAttrib('onkeypress','ValidarCaracteres(this, 499)')
					->clearErrorMessages()
					->addErrorMessage('Debes ingresar una descripcion');
		
        $buttonEnviar = $this->createElement('submit', 'enviar');
        $buttonEnviar->setLabel('Crear Grupo')
        			 ->setAttrib('class', 'btn btn-success');
        
        
        $this->addElement($nombre);
        $this->addElement($fotoDesc1);
        $this->addElement($fileFoto1);
        $this->addElement($descripcion);
        $this->addElement($buttonEnviar);
        
        
    }

}




