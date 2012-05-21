<?php

class Application_Model_TipoGrupoDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_TipoGrupoMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objTipoGrupo = null;
    
    	if(count($resultado) > 0){
    
    		$objTipoGrupo = new Application_Model_TipoGrupo();
    		 
    		$objTipoGrupo->setId($resultado->current()->tip_gru_id);
    		$objTipoGrupo->setNombre($resultado->current()->tip_gru_nombre);
    
    	}
    	return $objTipoGrupo;
    }
    
    public function obtenerTodos()
    {
    	$lista = new SplObjectStorage();
    
    	$resultado = $this->_table->fetchAll();
    
    	if(count($resultado) > 0)
    	{
    		foreach ($resultado as $item)
    		{
    			$lista->attach($this->obtenerPorId($item->tip_gru_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function eliminar($tip_gru_id)
    {
    	$where = 'tip_gru_id = ' . $tip_gru_id;
    
    	return $this->_table->delete($where);
    }

}

