<?php

class Application_Form_FormBuscarEvento extends Zend_Form
{

    public function init()
    {
        $para = new Zend_Form_Element_Text('txtBuscar');
        $para->setValue('');
        
        $buttonBuscar = $this->createElement('submit', 'enviar');
        $buttonBuscar->setLabel('Buscar evento')
        ->setAttrib('class', 'btn btn-success');
        
        $this->addElement($para);
        $this->addElement($buttonBuscar);
    }
}

