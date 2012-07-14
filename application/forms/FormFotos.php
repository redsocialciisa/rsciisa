<?php

class Application_Form_FormFotos extends Zend_Form
{

    public function init()
    {
        $aut = Zend_Auth::getInstance();
        $objUsuarioDao = new Application_Model_UsuarioDao();
        $objUsuario = $objUsuarioDao->obtenerPorId($aut->getIdentity()->usu_id);
        
        $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);
        $this->setAttrib('id', 'formFotos');
        $this->setAttrib('name', 'formFotos');
        
        $fotoDesc1 = new Zend_Form_Element_Textarea('fotoDesc1');
        $fotoDesc1->setLabel('Descripción: ')
		        ->setAttrib('rows','1')
		        ->setAttrib('onkeypress','ValidarCaracteres(this, 44)')
		        ->setAttrib('class','span6');
        
        $fileFoto1 = new Zend_Form_Element_File('fileFoto1');
        $fileFoto1->setLabel('Foto: ')
        		->setRequired(false)
		        ->addValidator('IsImage')
		        ->setAttrib('class', 'span4')
		        ->setMaxFileSize(5097152)
		        ->addValidator('Extension',false,array('jpg','jpeg','png'));
        
        $fotoDesc2 = new Zend_Form_Element_Textarea('fotoDesc2');
        $fotoDesc2->setLabel('Descripción: ')
		        ->setAttrib('rows','1')
		        ->setAttrib('onkeypress','ValidarCaracteres(this, 44)')
		        ->setAttrib('class','span6');
        
        $fileFoto2 = new Zend_Form_Element_File('fileFoto2');
        $fileFoto2->setLabel('Foto: ')
		        ->setRequired(false)
		        ->addValidator('IsImage')
		        ->setAttrib('class', 'span4')
		        ->setMaxFileSize(5097152)
		        ->addValidator('Extension',false,array('jpg','jpeg','png'));
        
        $fotoDesc3 = new Zend_Form_Element_Textarea('fotoDesc3');
        $fotoDesc3->setLabel('Descripción: ')
		        ->setAttrib('rows','1')
		        ->setAttrib('onkeypress','ValidarCaracteres(this, 44)')
		        ->setAttrib('class','span6');
        
        $fileFoto3 = new Zend_Form_Element_File('fileFoto3');
        $fileFoto3->setLabel('Foto: ')
		        ->setRequired(false)
		        ->addValidator('IsImage')
		        ->setAttrib('class', 'span4')
		        ->setMaxFileSize(5097152)
		        ->addValidator('Extension',false,array('jpg','jpeg','png'));
        
        $fotoDesc4 = new Zend_Form_Element_Textarea('fotoDesc4');
        $fotoDesc4->setLabel(' ')
		        ->setAttrib('rows','1')
		        ->setAttrib('onkeypress','ValidarCaracteres(this, 44)')
		        ->setAttrib('class','span6');
        
        $fileFoto4 = new Zend_Form_Element_File('fileFoto4');
        $fileFoto4->setLabel(' ')
		        ->setRequired(false)
		        ->addValidator('IsImage')
		        ->setAttrib('class', 'span4')
		        ->setMaxFileSize(5097152)
		        ->addValidator('Extension',false,array('jpg','jpeg','png'));
        
        $fotoDesc5 = new Zend_Form_Element_Textarea('fotoDesc5');
        $fotoDesc5->setLabel(' ')
		        ->setAttrib('rows','1')
		        ->setAttrib('onkeypress','ValidarCaracteres(this, 44)')
		        ->setAttrib('class','span6');
        
        $fileFoto5 = new Zend_Form_Element_File('fileFoto5');
        $fileFoto5->setLabel(' ')
		        ->setRequired(false)
		        ->addValidator('IsImage')
		        ->setAttrib('class', 'span4')
		        ->setMaxFileSize(5097152)
		        ->addValidator('Extension',false,array('jpg','jpeg','png'));
        
        $this->addElement($fileFoto1);
        $this->addElement($fotoDesc1);
        
        $this->addElement($fileFoto2);
        $this->addElement($fotoDesc2);
        
        $this->addElement($fileFoto3);
        $this->addElement($fotoDesc3);
        
      /*  $this->addElement($fileFoto4);
        $this->addElement($fotoDesc4);
        
        $this->addElement($fileFoto5);
        $this->addElement($fotoDesc5);*/
        
    }

}




