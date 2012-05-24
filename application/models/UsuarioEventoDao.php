<?php

class Application_Model_UsuarioEventoDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_UsuarioEventoMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objUsuarioEvento = null;
    
    	if(count($resultado) > 0){
    
    		$objUsuarioEvento = new Application_Model_UsuarioEvento();
    		 
    		$objUsuarioEvento->setId($resultado->current()->usu_eve_id);
    		$objUsuarioEvento->setUsuarioCiisa($resultado->current()->eve_id);
    		$objUsuarioEvento->setPassword($resultado->current()->usu_id);
    		$objUsuarioEvento->setNombre($resultado->current()->usu_eve_asiste);
    		$objUsuarioEvento->setNombre($resultado->current()->usu_eve_fecha_asiste);
    
    	}
    	return $objUsuarioEvento;
    }
    
    public function guardar(Application_Model_UsuarioEvento $usuarioEvento)
    {
    	$data = array('usu_eve_id' => $usuarioEvento->getId(),
    			'eve_id' => $usuarioEvento->getEventoId(),
    			'usu_id' => $usuarioEvento->getUsuarioId(),
    			'usu_eve_asiste' => $usuarioEvento->getAsiste(),
    			'usu_eve_fecha_asiste' => $usuarioEvento->getFechaAsiste()
    	);
    
    	if($usuarioEvento->getId() != null){
    		$where = 'usu_eve_id = ' . $usuarioEvento->getId();
    		 
    		return $this->_table->update($data, $where);
    	}
    
    	return $this->_table->insert($data);
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
    			$lista->attach($this->obtenerPorId($item->usu_eve_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function obtenerPorEventoId($eve_id)
    {
    	$lista = new SplObjectStorage();
    	$where = 'eve_id ='. $eve_id;
    	 
    	$resultado = $this->_table->fetchAll($where);
    
    	if(count($resultado) > 0)
    	{
    		foreach ($resultado as $item)
    		{
    			$lista->attach($this->obtenerPorId($item->usu_eve_id));
    		}
    	}
    
    	return $lista;
    }
    
    
    public function eliminar($usu_eve_id)
    {
    	$where = 'usu_eve_id = ' . $usu_eve_id;
    
    	return $this->_table->delete($where);
    }

}

