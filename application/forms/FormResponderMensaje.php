<?php

class Application_Form_FormResponderMensaje extends Zend_Form
{

    public function init()
    {
        $textoMensaje = new Zend_Form_Element_Textarea('txtTextoResponder');
        $textoMensaje->setLabel('Responder: ')
        ->setRequired(true)
        ->setValue('')
        ->setAttrib('maxlength', '500')
        ->setAttrib('onkeypress','ValidarCaracteres(this, 499)')
        ->setAttrib('class', 'span6')
        ->setAttrib('rows', 2)
        ->clearErrorMessages()
        ->addErrorMessage('Debes ingresar el texto del mensaje');
        
        $buttonEnviar = $this->createElement('submit', 'enviar');
        $buttonEnviar->setLabel('Responder Mensaje')
        ->setAttrib('class', 'btn btn-success');
        
        
        $this->addElement($textoMensaje);
        $this->addElement($buttonEnviar);
    }


}

