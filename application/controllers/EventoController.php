<?php

class EventoController extends Zend_Controller_Action
{

    public function init()
    {
        
    }

    public function indexAction()
    {
        $aut = Zend_Auth::getInstance();
        
        if ($this->getRequest()->isPost()) {
	    	$objEvento = new Application_Model_Evento();
	    	$objEventoDao = new Application_Model_EventoDao();
	    	$objUtilidad = new Application_Model_Utilidad();
		    	
	    	$objEvento->setNombre($this->getRequest()->getParam('txtNombreEvento'));
	    	$objEvento->setDescripcion($this->getRequest()->getParam('txtDescripcion'));
	    	$objEvento->setLugar($this->getRequest()->getParam('address'));
	    	$objEvento->setFechaCreacion($objUtilidad->devolverFechaParaBD($this->getRequest()->getParam('txtFechaEvento')));
	    	$objEvento->setHora($this->getRequest()->getParam('sltHora'));
	    	$objEvento->setTipoEventoId($this->getRequest()->getParam('cbxTipo'));
	    	
	    	$cordenadas = explode(",", $this->getRequest()->getParam('hdnCordenadas'));
	    	$objEvento->setCordenadaX(str_replace("(","",trim($cordenadas[0])));
	    	$objEvento->setCordenadaY(str_replace(")","",trim($cordenadas[1])));
	    	
	    	$objEvento->setUsuarioId($aut->getIdentity()->usu_id);
	    	
	    	$objEventoDao->guardar($objEvento);
        }
    }
    

}

