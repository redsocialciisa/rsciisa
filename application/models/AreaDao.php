<?php

class Application_Model_AreaDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_AreaMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$area = null;
    
    	if(count($resultado) > 0){
    
    		$area = new Application_Model_Area();
    		 
    		$area->setId($resultado->current()->are_id);
    		$area->setNombre($resultado->current()->are_nombre);
    
    	}
    	return $area;
    }

}

