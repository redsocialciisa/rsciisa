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
    
    public function obtenerTodos()
    {
    	$lista = new SplObjectStorage();
    
    	$resultado = $this->_table->fetchAll();
    
    	if(count($resultado) > 0){
    
    		foreach ($resultado as $item)
    		{
    			$lista->attach($this->obtenerPorId($item->are_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function eliminar($are_id)
    {
    	$where = 'are_id = ' . $are_id;
    
    	return $this->_table->delete($where);
    }

}
