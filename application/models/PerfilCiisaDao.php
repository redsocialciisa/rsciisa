<?php

class Application_Model_PerfilCiisaDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_PerfilCiisaMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objPerfilCiisa = null;
    
    	if(count($resultado) > 0){
    
    		$objPerfilCiisa = new Application_Model_PerfilCiisa();
    		 
    		$objPerfilCiisa->setId($resultado->current()->per_cii_id);
    		$objPerfilCiisa->setPerfil($resultado->current()->per_id);
    		$objPerfilCiisa->setPerfilCiisa($resultado->current()->per_ciisa_id);
    		 	 	
    	}
    	return $objPerfilCiisa;
    }
    
    
    public function obtenerPorPerfilCiisa($perfilCiisa)
    {
    	$where = "per_ciisa_id = '" .trim($perfilCiisa) . "'";
    
    	$resultado = $this->_table->fetchAll($where);
    
    	if(count($resultado) > 0)
    	{
    		return $this->obtenerPorId($resultado->current()->per_cii_id);
    	}
    
    	return null;
    }

}

