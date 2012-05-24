<?php

class Application_Model_HabilidadDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_HabilidadMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objHabilidad = null;
    
    	if(count($resultado) > 0){
    
    		$objHabilidad = new Application_Model_Habilidad();
    		 
    		$objHabilidad->setId($resultado->current()->hab_id);
    		$objHabilidad->setNombre($resultado->current()->hab_nombre);
    
    	}
    	return $objHabilidad;
    }
    
    public function guardar(Application_Model_Habilidad $habilidad)
    {
    	$data = array('hab_id' => $habilidad->getId(),
    			'hab_nombre' => $habilidad->getNombre()
    	);
    
    	if($habilidad->getId() != null){
    		$where = 'hab_id = ' . $habilidad->getId();
    		 
    		return $this->_table->update($data, $where);
    	}
    
    	return $this->_table->insert($data);
    }
    
    public function eliminar($hab_id)
    {
    	$where = 'hab_id = ' . $hab_id;
    
    	return $this->_table->delete($where);
    }

}

