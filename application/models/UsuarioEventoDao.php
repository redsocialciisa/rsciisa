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
    		$objUsuarioEvento->setEventoId($resultado->current()->eve_id);
    		$objUsuarioEvento->setUsuarioId($resultado->current()->usu_id);
    		$objUsuarioEvento->setAsiste($resultado->current()->usu_eve_asiste);
    		$objUsuarioEvento->setFechaAsiste($resultado->current()->usu_eve_fecha_asiste);
    		$objUsuarioEvento->setEliminar($resultado->current()->usu_eve_eliminar);
    	}
    	return $objUsuarioEvento;
    }
    
    public function guardar(Application_Model_UsuarioEvento $usuarioEvento)
    {
    	$data = array('usu_eve_id' => $usuarioEvento->getId(),
    			'eve_id' => $usuarioEvento->getEventoId(),
    			'usu_id' => $usuarioEvento->getUsuarioId(),
    			'usu_eve_asiste' => $usuarioEvento->getAsiste(),
    			'usu_eve_fecha_asiste' => $usuarioEvento->getFechaAsiste(),
    	        'usu_eve_eliminar' => $usuarioEvento->getEliminar()
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
    	$order = 'eve_id desc';
    	$objEventoDao = new Application_Model_EventoDao();
    	
    	$resultado = $this->_table->fetchAll($where,$order);
    	
    	if(count($resultado) > 0)
    	{
    		foreach ($resultado as $item)
    		{
    		    $objEvento = $objEventoDao->obtenerPorId($this->obtenerPorId($item->usu_eve_id)->getEventoId());

    		    if($objEvento->getCancelado() == 0)
    		    {
    		        $lista->attach($objEvento);
    		    }
    		}
    	}
    
    	$objEventoDao = null;
    	return $lista;
    }
    
    public function obtenerPorGrupoYUsuario($eve_id,$usu_id)
    {
    	$where = 'eve_id ='. $eve_id.' and usu_id ='.$usu_id;
    
    	$resultado = $this->_table->fetchAll($where);
    
    	if(count($resultado) > 0)
    	{
    		foreach ($resultado as $item)
    		{
    			return $this->obtenerPorId($item->usu_eve_id)->getEliminar();
    		}
    	}else{
    		return null;
    	}
    }
    
    public function obtenerMarcadosEliminar($eve_id)
    {
    	$where = 'eve_id ='. $eve_id.' and usu_eve_eliminar = 1';
    
    	$resultado = $this->_table->fetchAll($where);
    
    	return count($resultado);
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
    
    public function obtenerUsuariosPorEventoId($eve_id)
    {
    	$lista = new SplObjectStorage();
    	$where = 'eve_id ='. $eve_id;
    	$objUsuarioDao = new Application_Model_UsuarioDao();
    	
    	$resultado = $this->_table->fetchAll($where);
    
    	if(count($resultado) > 0)
    	{
    		foreach ($resultado as $item)
    		{
    			$lista->attach($objUsuarioDao->obtenerPorId($this->obtenerPorId($item->usu_eve_id)->getUsuarioId()));
    		}
    	}
    
    	return $lista;
    }
    
    public function obtenerUsuariosAEliminar($eve_id)
    {
    	$lista = new SplObjectStorage();
    	$where = 'eve_id ='. $eve_id. ' and usu_eve_eliminar = 1';
    	 
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
    
    public function obtenerUsuariosAEliminarTwo($eve_id)
    {
    	$lista = new SplObjectStorage();
    	$where = 'eve_id ='. $eve_id. ' and usu_eve_eliminar = 1';
    
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
    
    public function marcarEliminar($eve_id,$usu_id,$cbx_usuario)
    {
    	$data = array('usu_eve_eliminar' => $cbx_usuario);
    
    	$where = 'eve_id = '.$eve_id.' and usu_id = '. $usu_id;
    	return $this->_table->update($data, $where);
    }
    
    public function eliminar($usu_eve_id)
    {
    	$where = 'usu_eve_id = ' . $usu_eve_id;
    
    	return $this->_table->delete($where);
    }
    
    public function eliminarUsuariosPorEvento($eve_id)
    {
    	$where = 'eve_id = ' . $eve_id.' and usu_eve_eliminar = 1';
    
    	return $this->_table->delete($where);
    }
    
    public function eliminarUsuarioDelEvento($eve_id,$usu_id)
    {
    	$where = 'eve_id = '.$eve_id.' and usu_id ='.$usu_id;
    
    	return $this->_table->delete($where);
    }

}

