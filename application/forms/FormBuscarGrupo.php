<?php

class Application_Form_FormBuscarGrupo extends Zend_Form
{

    public function init()
    {
        $para = new Zend_Form_Element_Text('txtBuscar');
        $para->setValue('');
        
        $buttonBuscar = $this->createElement('submit', 'enviar');
        $buttonBuscar->setLabel('Buscar grupo')
        ->setAttrib('class', 'btn btn-success');
        
        $this->addElement($para);
        $this->addElement($buttonBuscar);
    }
}

