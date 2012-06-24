<?php

class Application_Form_FormNuevoMensaje extends Zend_Form
{

    public function init()
    {
        $para = new Zend_Form_Element_Text('txtPara');
        $para->setLabel('Para: ')
        ->setRequired(true)
        ->setValue('')
        ->setAttrib('maxlength', '100')
        ->setAttrib('class', 'span4')
        ->clearErrorMessages()
        ->addErrorMessage('Debes ingresar a quien enviar el mensaje');
        
        $textoMensaje = new Zend_Form_Element_Text('txtTextoMensaje');
        $textoMensaje->setLabel('Texto: ')
        ->setRequired(true)
        ->setValue('')
        ->setAttrib('maxlength', '500')
        ->setAttrib('class', 'span6')
        ->clearErrorMessages()
        ->addErrorMessage('Debes ingresar a quien enviar el mensaje');
        
        $buttonEnviar = $this->createElement('submit', 'enviar');
        $buttonEnviar->setLabel('Enviar Mensaje')
        ->setAttrib('class', 'label label-success');
        
        $this->addElement($para);
        $this->addElement($textoMensaje);
        $this->addElement($buttonEnviar);
        
    }


}

