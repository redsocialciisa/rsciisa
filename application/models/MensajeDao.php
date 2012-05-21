<?php

class Application_Model_MensajeDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_MensajeMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objMensaje = null;
    
    	if(count($resultado) > 0){
    
    		$objMensaje = new Application_Model_Mensaje();
    		 
    		$objMensaje->setId($resultado->current()->men_id);
    		$objMensaje->setDe($resultado->current()->usu_id_de);
    		$objMensaje->setPara($resultado->current()->usu_id_para);
    		$objMensaje->setAsunto($resultado->current()->men_asunto);
    		$objMensaje->setFecha($resultado->current()->men_fecha);
    
    	}
    	return $objMensaje;
    }
    
    public function eliminar($men_id)
    {
    	$where = 'men_id = ' . $men_id;
    
    	return $this->_table->delete($where);
    }    

}

