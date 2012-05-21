<?php

class Application_Model_HistorialDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_HistorialMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objHistorial = null;
    
    	if(count($resultado) > 0){
    
    		$objHistorial = new Application_Model_Historial();
    		 
    		$objHistorial->setId($resultado->current()->his_id);
    		$objHistorial->setTexto($resultado->current()->his_texto);
    		$objHistorial->setFecha($resultado->current()->his_fecha);
    		$objHistorial->setMensajeId($resultado->current()->men_id);
    		$objHistorial->setUsuarioId($resultado->current()->usu_id);
    
    	}
    	return $objHistorial;
    }
    
    public function obtenerPorMensajeId($men_id)
    {
    	$lista = new SplObjectStorage();
    	$where = 'men_id ='. $men_id;
    	$order = 'his_fecha asc';
    
    	$resultado = $this->_table->fetchAll($where, $order);
    
    	if(count($resultado) > 0)
    	{
    		foreach ($resultado as $item)
    		{
    			$lista->attach($this->obtenerPorId($item->his_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function eliminar($his_id)
    {
    	$where = 'his_id = ' . $his_id;
    
    	return $this->_table->delete($where);
    }    

}

