<?php

class Application_Form_FormEditarGrupo extends Zend_Form
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
        ->setAttrib('class', 'span4')
        ->clearErrorMessages()
        ->addErrorMessage('Debes ingresar el nombre del grupo');
        
        $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);
        $fileFoto = new Zend_Form_Element_File('fileFoto');
        $fileFoto->setLabel('Foto Grupo: ')
        		->setRequired(false)
		        ->addValidator('IsImage')
		        ->setAttrib('class', 'span4')
		        ->setMaxFileSize(5199999) // 2mb
		        ->addValidator('Extension',false,array('jpg','jpeg','png'));
        
		$descripcion = new Zend_Form_Element_Textarea('txtDescripcion');
		$descripcion->setLabel('Descripcion del grupo: ')
					->setRequired(true)
					->setAttrib('rows', 2)
					->setAttrib('class', 'span4')
					->clearErrorMessages()
					->addErrorMessage('Debes ingresar una descripcion');
		
        $buttonEnviar = $this->createElement('submit', 'enviar');
        $buttonEnviar->setLabel('Editar Grupo')
        			 ->setAttrib('class', 'btn btn-success');
        
        
        $grupoId= new Zend_Form_Element_Hidden('hdnIdGrupo');
        
        $this->addElement($nombre);
        $this->addElement($fileFoto);
        $this->addElement($descripcion);
        $this->addElement($grupoId);
        $this->addElement($buttonEnviar);
        
        
    }

}




