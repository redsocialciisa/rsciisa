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
    		$objEvento->setTipoEventoId($resultado->current()->eve_tip_id);    		
    
    	}
    	return $objEvento;
    }
    
    public function obtenerPorUsuarioId($usu_id)
    {
    	$listaEventos = new SplObjectStorage();
    	$where = 'usu_id > '. $usu_id;
    
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

}


