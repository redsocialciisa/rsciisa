<?php

class Application_Model_UsuarioMensajeDao
{
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_UsuarioMensajeMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objUsuarioMensaje = null;
    
    	if(count($resultado) > 0){
    
    		$objUsuarioMensaje = new Application_Model_UsuarioMensaje();
    		 
    		$objUsuarioMensaje->setId($resultado->current()->usu_men_id);
    		$objUsuarioMensaje->setLeido($resultado->current()->usu_men_leido);
    		$objUsuarioMensaje->setUsuarioId($resultado->current()->usu_id);
    		$objUsuarioMensaje->setMensajeId($resultado->current()->men_id);
    
    	}
    	return $objUsuarioMensaje;
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
    			$lista->attach($this->obtenerPorId($item->usu_gru_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function eliminar($usu_men_id)
    {
    	$where = 'usu_men_id = ' . $usu_men_id;
    
    	return $this->_table->delete($where);
    }

}

