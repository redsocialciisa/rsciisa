<?php

class Application_Form_FormEditarAlbum extends Zend_Form
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
	        ->setAttrib('maxlength', '24')
	        ->setAttrib('class', 'span3')
	        ->clearErrorMessages()
	        ->addErrorMessage('Debes ingresar el nombre del álbum');
        
        $fotoDesc1 = new Zend_Form_Element_Textarea('fotoDesc1');
        $fotoDesc1->setLabel('Descripción foto portada: ')
	        ->setAttrib('rows','3')
	        ->setAttrib('onkeypress','ValidarCaracteres(this, 249)')
	        ->setAttrib('class','span6');
                
		$descripcion = new Zend_Form_Element_Textarea('txtDescripcion');
		$descripcion->setLabel('Descripcion del álbum: ')
					->setRequired(false)
					->setAttrib('class', 'span6')
					->setAttrib('rows', 2)
					->setAttrib('onkeypress','ValidarCaracteres(this, 499)')
					->clearErrorMessages()
					->addErrorMessage('Debes ingresar una descripcion');
		
        $buttonEnviar = $this->createElement('submit', 'enviar');
        $buttonEnviar->setLabel('Editar album')
        			 ->setAttrib('class', 'btn btn-success');
        
        
        $albumId= new Zend_Form_Element_Hidden('hdnIdAlbum');
        
        $this->addElement($nombre);
        $this->addElement($fotoDesc1);
        $this->addElement($descripcion);
        $this->addElement($albumId);
        $this->addElement($buttonEnviar);
        
        
    }

}




