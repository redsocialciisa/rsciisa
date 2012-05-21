<?php

class Application_Model_TipoNotificacionDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_TipoNotificacionMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objTipoNotificacion = null;
    
    	if(count($resultado) > 0){
    
    		$objTipoNotificacion = new Application_Model_TipoNotificacion();
    		 
    		$objTipoNotificacion->setId($resultado->current()->tip_not_id);
    		$objTipoNotificacion->setNombre($resultado->current()->tip_not_nombre);
    
    	}
    	return $objTipoNotificacion;
    }
    
    public function obtenerTodos()
    {
    	$lista = new SplObjectStorage();
    
    	$resultado = $this->_table->fetchAll();
    
    	if(count($resultado) > 0)
    	{
    		foreach ($resultado as $item)
    		{
    			$lista->attach($this->obtenerPorId($item->tip_not_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function eliminar($tip_not_id)
    {
    	$where = 'tip_not_id = ' . $tip_not_id;
    
    	return $this->_table->delete($where);
    }

}

