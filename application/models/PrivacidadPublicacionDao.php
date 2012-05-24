<?php

class Application_Model_PrivacidadPublicacionDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_PrivacidadPublicacionMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objPrivacidadPublicacion = null;
    
    	if(count($resultado) > 0){
    
    		$objPrivacidadPublicacion = new Application_Model_PrivacidadPublicacion();
    		 
    		$objPrivacidadPublicacion->setId($resultado->current()->pri_pub_id);
    		$objPrivacidadPublicacion->setNombre($resultado->current()->pri_pub_nombre);
    
    	}
    	return $objPrivacidadPublicacion;
    }
    
    public function guardar(Application_Model_PrivacidadPublicacion $privacidadPublicacion)
    {
    	$data = array('pri_pub_id' => $privacidadPublicacion->getId(),
    			'pri_pub_nombre' => $privacidadPublicacion->getFecha()
    	);
    
    	if($privacidadPublicacion->getId() != null){
    		$where = 'pri_pub_id = ' . $privacidadPublicacion->getId();
    		 
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
    			$lista->attach($this->obtenerPorId($item->pri_pub_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function eliminar($pri_pub_id)
    {
    	$where = 'pri_pub_id = ' . $pri_pub_id;
    
    	return $this->_table->delete($where);
    }

}

