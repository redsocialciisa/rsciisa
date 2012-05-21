<?php

class Application_Model_TipoEventoDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_TipoEventoMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objTipoEvento = null;
    
    	if(count($resultado) > 0){
    
    		$objTipoEvento = new Application_Model_TipoEvento();
    		 
    		$objTipoEvento->setId($resultado->current()->tip_eve_id);
    		$objTipoEvento->setNombre($resultado->current()->tip_eve_nombre);
    
    	}
    	return $objTipoEvento;
    }
    
    public function obtenerTodos()
    {
    	$lista = new SplObjectStorage();
    
    	$resultado = $this->_table->fetchAll();
    
    	if(count($resultado) > 0)
    	{
    		foreach ($resultado as $item)
    		{
    			$lista->attach($this->obtenerPorId($item->tip_eve_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function eliminar($tip_eve_id)
    {
    	$where = 'tip_eve_id = ' . $tip_eve_id;
    
    	return $this->_table->delete($where);
    }

}

