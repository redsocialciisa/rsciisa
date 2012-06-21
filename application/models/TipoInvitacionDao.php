<?php

class Application_Model_TipoInvitacionDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_TipoInvitacionMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objTipoInvitacion = null;
    
    	if(count($resultado) > 0){
    
    		$objTipoInvitacion = new Application_Model_TipoInvitacion();
    		 
    		$objTipoInvitacion->setId($resultado->current()->tip_inv_id);
    		$objTipoInvitacion->setNombre($resultado->current()->tip_inv_nombre);
    
    	}
    	return $objTipoInvitacion;
    }
    
    public function guardar(Application_Model_TipoInvitacion $tipoInvitacion)
    {
    	$data = array('tip_inv_id' => $tipoInvitacion->getId(),
    			'tip_inv_nombre' => $tipoInvitacion->getNombre()
    	);
    
    	if($tipoInvitacion->getId() != null){
    		$where = 'tip_not_id = ' . $tipoInvitacion->getId();
    		 
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
    			$lista->attach($this->obtenerPorId($item->tip_inv_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function eliminar($tip_inv_id)
    {
    	$where = 'tip_inv_id = ' . $tip_inv_id;
    
    	return $this->_table->delete($where);
    }

}

