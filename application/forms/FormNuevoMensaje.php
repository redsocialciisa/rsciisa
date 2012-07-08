<?php

class Application_Form_FormNuevoMensaje extends Zend_Form
{

    public function init()
    {
        $aut = Zend_Auth::getInstance();
        $objAmigoDao = new Application_Model_AmigoDao();
        $listaUsuarios = $objAmigoDao->obtenerTodosPorNombre($aut->getIdentity()->usu_id);
        
        $para = new Zend_Form_Element_Text('txtPara');
        $para->setLabel('Para: ')
        ->setRequired(true)
        ->setValue('')
        ->setAttrib('maxlength', '100')
        ->setAttrib('autocomplete', 'off')  
        ->setAttrib('placeholder','Buscar...')
        ->clearErrorMessages()
        ->addErrorMessage('Debes ingresar a quien enviar el mensaje');
        
        $textoMensaje = new Zend_Form_Element_Textarea('txtTextoMensaje');
        $textoMensaje->setLabel('Texto: ')
        ->setRequired(true)
        ->setValue('')
        ->setAttrib('maxlength', '500')
        ->setAttrib('class', 'span6')
        ->setAttrib('rows', 2)
        ->setAttrib('onkeypress','ValidarCaracteres(this, 499)')
        ->clearErrorMessages()
        ->addErrorMessage('Debes ingresar a quien enviar el mensaje');
        
        $buttonEnviar = $this->createElement('submit', 'enviar');
        $buttonEnviar->setLabel('Enviar Mensaje')
        ->setAttrib('class', 'btn btn-success');
        
        $this->addElement($para);
        $this->addElement($textoMensaje);
        $this->addElement($buttonEnviar);
        
    }


}

