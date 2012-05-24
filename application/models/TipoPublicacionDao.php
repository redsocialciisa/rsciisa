<?php

class Application_Model_TipoPublicacionDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_TipoPublicacionesMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objTipoPublicacion = null;
    
    	if(count($resultado) > 0){
    
    		$objTipoPublicacion = new Application_Model_TipoPublicacion();
    		 
    		$objTipoPublicacion->setId($resultado->current()->tip_pub_id);
    		$objTipoPublicacion->setNombre($resultado->current()->tip_pub_nombre);
    
    	}
    	return $objTipoPublicacion;
    }
    
    public function guardar(Application_Model_TipoPublicacion $tipoPublicacion)
    {
    	$data = array('tip_pub_id' => $tipoPublicacion->getId(),
    			'tip_pub_nombre' => $tipoPublicacion->getNombre()
    	);
    
    	if($tipoPublicacion->getId() != null){
    		$where = 'tip_pub_id = ' . $tipoPublicacion->getId();
    		 
    		return $this->_table->update($data, $where);
    	}
    
    	return $this->_table->insert($data);
    }
    
    public function obtenerTodos()
    {
    	$lista = new SplObjectStorage();
    
    	$resultado = $this->_table->fetchAll();
    
    	if(count($resultado) > 0)
    	{
    		foreach ($resultado as $item)
    		{
    			$lista->attach($this->obtenerPorId($item->tip_pub_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function eliminar($tip_pub_id)
    {
    	$where = 'tip_pub_id = ' . $tip_pub_id;
    
    	return $this->_table->delete($where);
    }

}

