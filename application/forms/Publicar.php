<?php

class Application_Form_Publicar extends Zend_Form
{

    public function init()
    {
        $this->setName('publicar');
        $id = new Zend_Form_Element_Hidden('id');
        $texto = new Zend_Form_Element_Text('texto');
        $texto->setLabel('Texto')
        ->setRequired(true)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty');
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        $this->addElements(array($id, $texto, $submit));
    }


}

