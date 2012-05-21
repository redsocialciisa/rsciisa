<?php

class Application_Model_OfertaDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_OfertaMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objOferta = null;
    
    	if(count($resultado) > 0){
    
    		$objOferta = new Application_Model_Oferta();
    		 
    		$objOferta->setId($resultado->current()->ofe_id);
    		$objOferta->setNombre($resultado->current()->ofe_nombre);
    		$objOferta->setDescripcion($resultado->current()->ofe_descripcion);
    		$objOferta->setFechaCreacion($resultado->current()->ofe_fecha_creacion);
    		$objOferta->setUsuarioId($resultado->current()->usu_id);
    		$objOferta->setAreaId($resultado->current()->are_id);
    
    	}
    	return $objOferta;
    }
    
    public function obtenerPorUsuarioId($usu_id)
    {
    	$lista = new SplObjectStorage();
    	$where = 'usu_id ='. $usu_id;
    
    	$resultado = $this->_table->fetchAll($where);
    
    	if(count($resultado) > 0)
    	{
    		foreach ($resultado as $item)
    		{
    			$lista->attach($this->obtenerPorId($item->ofe_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function eliminar($ofe_id)
    {
    	$where = 'ofe_id = ' . $ofe_id;
    
    	return $this->_table->delete($where);
    }

}


