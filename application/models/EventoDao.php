<?php

class Application_Model_EventoDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_EventoMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objEvento = null;
    
    	if(count($resultado) > 0){
    
    		$objEvento = new Application_Model_Evento();
    		 
    		$objEvento->setId($resultado->current()->eve_id);
    		$objEvento->setUsuarioId($resultado->current()->usu_id);
    		$objEvento->setNombre($resultado->current()->eve_nombre);
    		$objEvento->setDescripcion($resultado->current()->eve_descripcion);
    		$objEvento->setLugar($resultado->current()->eve_lugar);
    		$objEvento->setCordenadaX($resultado->current()->eve_cordenada_x);
    		$objEvento->setCordenadaY($resultado->current()->eve_cordenada_y);
    		$objEvento->setFechaCreacion($resultado->current()->eve_fecha_creacion);
    		$objEvento->setFechaEvento($resultado->current()->eve_fecha_evento);
    		$objEvento->setTipoEventoId($resultado->current()->tip_eve_id);
    		$objEvento->setHora($resultado->current()->eve_hora);
    		$objEvento->setCancelado($resultado->current()->eve_cancelado);
    	}
    	return $objEvento;
    }
    
    public function guardar(Application_Model_Evento $evento)
    {
    	$data = array('eve_id' => $evento->getId(),
    			'usu_id' => $evento->getUsuarioId(),
    			'eve_nombre' => $evento->getNombre(),
    	        'eve_descripcion' => $evento->getDescripcion(),
    	        'eve_lugar' => $evento->getLugar(),
    	        'eve_cordenada_x' => $evento->getCordenadaX(),
    	        'eve_cordenada_y' => $evento->getCordenadaY(),
    	        'eve_fecha_creacion' => $evento->getFechaCreacion(),
    	        'eve_fecha_evento' => $evento->getFechaEvento(),  	        
    	        'tip_eve_id' => $evento->getTipoEventoId(),
    	        'eve_hora' => $evento->getHora(),
    	        'eve_cancelado' => $evento->getCancelado()
    	);
    
    	if($evento->getId() != null){
    		$where = 'eve_id = ' . $evento->getId();
    		 
    		return $this->_table->update($data, $where);
    	}
    
    	return $this->_table->insert($data);
    }
    
    public function obtenerPorUsuarioId($usu_id)
    {
    	$listaEventos = new SplObjectStorage();
    	$where = 'usu_id = '. $usu_id;
    
    	$resultado = $this->_table->fetchAll($where);
    
    	if(count($resultado) > 0)
    	{
    		foreach ($resultado as $item)
    		{
    			$listaEventos->attach($this->obtenerPorId($item->eve_id));
    		}
    	}
    
    	return $listaEventos;
    }
    
    public function obtenerEntreFechas($fechaInicio, $fechaTermino)
    {
    	$lista = new SplObjectStorage();
    	$where = 'eve_fecha_creacion between'. $fechaInicio .'AND'. $fechaTermino;
    	$where = $where . 'eve_tip_id = 1';
    
    	$resultado = $this->_table->fetchAll($where);
    
    	if(count($resultado) > 0)
    	{
    		foreach ($resultado as $item)
    		{
    			$lista->attach($this->obtenerPorId($item->eve_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function eliminar($eve_id)
    {
    	$where = 'eve_id = ' . $eve_id;
    
    	return $this->_table->delete($where);
    }
    
    public function cancelarEvento(Application_Model_Evento $evento)
    {
    	$data = array('eve_id' => $evento->getId(),
    			  	  'eve_cancelado' => '1'
    	);
    
    	if($evento->getId() != null){
    		$where = 'eve_id = ' . $evento->getId();
    		 
    		return $this->_table->update($data, $where);
    	}
    
    }

    public function obtenerPublicos()
    {
    	$lista = new SplObjectStorage();
    	$where = 'tip_eve_id = 1';
    	$order = 'eve_fecha_creacion desc';
    
    	$resultado = $this->_table->fetchAll($where, $order);
    
    	if(count($resultado) > 0)
    	{
    		foreach ($resultado as $item)
    		{
    			$lista->attach($this->obtenerPorId($item->eve_id));
    		}
    	}
    	return $lista;
    }
    
    public function obtenerPorNombre($nombre)
    {   //NOTE: Solo obtiene los eventos publicos
    $lista = new SplObjectStorage();
    $where = "eve_nombre like '%". $nombre ."%' AND tip_eve_id = 1 AND eve_cancelado = 0";
    $order = 'eve_nombre';
    
    $resultado = $this->_table->fetchAll($where, $order);
    
    if(count($resultado) > 0)
    {
    	foreach ($resultado as $item)
    	{
    		$lista->attach($this->obtenerPorId($item->eve_id));
    	}
    }
    
    return $lista;
    }
    
}


