<?php

class Application_Form_FormGrupo extends Zend_Form
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
        		->setRequired(true)
		        ->addValidator('IsImage')
		        ->setAttrib('class', 'span4')
		        ->setMaxFileSize(2097152) // 2mb
		        ->addValidator('Extension',false,array('jpg','jpeg','png','gif'));
        
		$descripcion = new Zend_Form_Element_Textarea('txtDescripcion');
		$descripcion->setLabel('Descripcion del grupo: ')
					->setRequired(true)
					->setAttrib('row', 2)
					->setAttrib('onkeypress','ValidarCaracteres(this, 499)')
					->clearErrorMessages()
					->addErrorMessage('Debes ingresar una descripcion');
		
		$tipoPublico = new Zend_Form_Element_Radio('optPublico');
		$tipoPublico->addMultiOptions(array(
				'0' => 'Publico',
		))
		->setAttrib('checked',true)
		->setAttrib('name', 'grpTipo');
		
		$tipoPrivado = new Zend_Form_Element_Radio('optPrivado');
		$tipoPrivado->addMultiOptions(array(
				'1' => 'Privado',
		))
		->setAttrib('name', 'grpTipo');
				
        $buttonEnviar = $this->createElement('submit', 'enviar');
        $buttonEnviar->setLabel('Crear Grupo')
        			 ->setAttrib('class', 'label label-success');
        
        
        $grupoId = new Zend_Form_Element_Hidden('hdnIdGrupo');
        
        $this->addElement($nombre);
        $this->addElement($fileFoto);
        $this->addElement($descripcion);
        $this->addElement($tipoPublico);
        $this->addElement($tipoPrivado);
        $this->addElement($grupoId);
        $this->addElement($buttonEnviar);
        
        
    }

}




