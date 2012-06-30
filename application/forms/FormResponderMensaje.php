<?php

class Application_Form_FormResponderMensaje extends Zend_Form
{

    public function init()
    {
        $textoMensaje = new Zend_Form_Element_Text('txtTextoResponder');
        $textoMensaje->setLabel('Responder: ')
        ->setRequired(true)
        ->setValue('')
        ->setAttrib('maxlength', '500')
        ->setAttrib('class', 'span3')
        ->clearErrorMessages()
        ->addErrorMessage('Debes ingresar el texto del mensaje');
        
        $buttonEnviar = $this->createElement('submit', 'enviar');
        $buttonEnviar->setLabel('Responder Mensaje')
        ->setAttrib('class', 'label label-success');
        
        
        $this->addElement($textoMensaje);
        $this->addElement($buttonEnviar);
    }


}

