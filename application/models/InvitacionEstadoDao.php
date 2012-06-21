<?php

class Application_Model_InvitacionEstadoDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_InvitacionEstadoMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objInvitacionEstado = null;
    
    	if(count($resultado) > 0){
    
    		$objTipoEvento = new Application_Model_InvitacionEstado();
    		 
    		$objInvitacionEstado->setId($resultado->current()->inv_estado_id);
    		$objInvitacionEstado->setNombre($resultado->current()->inv_estado_nombre);
    
    	}
    	return $objInvitacionEstado;
    }
    
    public function guardar(Application_Model_InvitacionEstado $InvitacionEstado)
    {
    	$data = array('inv_estado_id' => $InvitacionEstado->getId(),
    			'inv_estado_nombre' => $InvitacionEstado->getNombre()
    	);
    
    	if($InvitacionEstado->getId() != null){
    		$where = 'inv_estado_id = ' . $InvitacionEstado->getId();
    		 
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
    			$lista->attach($this->obtenerPorId($item->inv_estado_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function eliminar($tip_eve_id)
    {
    	$where = 'inv_estado_id = ' . $tip_eve_id;
    
    	return $this->_table->delete($where);
    }

}

