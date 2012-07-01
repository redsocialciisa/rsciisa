<?php

class Application_Model_InvitacionDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_InvitacionMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objInvitacion = null;
    
    	if(count($resultado) > 0){
    
    		$objInvitacion = new Application_Model_Invitacion();
    		 
    		$objInvitacion->setId($resultado->current()->inv_id);
    		$objInvitacion->setFecha($resultado->current()->inv_fecha);
    		$objInvitacion->setUsuarioId($resultado->current()->usu_id);
    		$objInvitacion->setTipoInvitacionId($resultado->current()->tip_inv_id);
    		$objInvitacion->setIdActividad($resultado->current()->id_actividad);
    		$objInvitacion->setEstado($resultado->current()->inv_estado);
    
    	}
    	return $objInvitacion;
    }
    
    public function guardar(Application_Model_Invitacion $invitacion)
    {
    	$data = array('inv_id' => $invitacion->getId(),
    			'inv_fecha' => $invitacion->getFecha(),
    			'usu_id' => $invitacion->getUsuarioId(),
    			'tip_inv_id' => $invitacion->getTipoInvitacionId(),
    	        'id_actividad' => $invitacion->getIdActividad(),
    			'inv_estado' => $invitacion->getEstado()
    	);
    
    	if($invitacion->getId() != null){
    		$where = 'inv_id = ' . $invitacion->getId();
    		 
    		return $this->_table->update($data, $where);
    	}
    
    	return $this->_table->insert($data);
    }
        
    public function eliminar($inv_id)
    {
    	$where = 'inv_id = ' . $inv_id;
    
    	return $this->_table->delete($where);
    }
    
    public function eliminarUsuarioPorEvento($eve_id,$usu_id)
    {
        $where = 'id_actividad  =' . $eve_id. ' and tip_inv_id = 2 and usu_id ='. $usu_id;
        
        return $this->_table->delete($where);
    }
    
    public function obtenerPorIdActividadUsuario($usuarioID,$actividadId)
    {
        $where = 'usu_id ='. $usuarioID.' and tip_inv_id = 1 and id_actividad = '.$actividadId;
        $resultado = $this->_table->fetchall($where);
        
        $objInvitacion = null;
        
        if(count($resultado) > 0){
        
        	$objInvitacion = new Application_Model_Invitacion();
        	 
        	$objInvitacion->setId($resultado->current()->inv_id);
        	$objInvitacion->setFecha($resultado->current()->inv_fecha);
        	$objInvitacion->setUsuarioId($resultado->current()->usu_id);
        	$objInvitacion->setTipoInvitacionId($resultado->current()->tip_inv_id);
        	$objInvitacion->setIdActividad($resultado->current()->id_actividad);
        	$objInvitacion->setEstado($resultado->current()->inv_estado);
        
        }
        return $objInvitacion;
    }
    
    public function obtenerPorIdActividadUsuarioEvento($usuarioID,$actividadId)
    {
    	$where = 'usu_id ='. $usuarioID.' and tip_inv_id = 2 and id_actividad = '.$actividadId;
    	$resultado = $this->_table->fetchall($where);
    
    	$objInvitacion = null;
    
    	if(count($resultado) > 0){
    
    		$objInvitacion = new Application_Model_Invitacion();
    
    		$objInvitacion->setId($resultado->current()->inv_id);
    		$objInvitacion->setFecha($resultado->current()->inv_fecha);
    		$objInvitacion->setUsuarioId($resultado->current()->usu_id);
    		$objInvitacion->setTipoInvitacionId($resultado->current()->tip_inv_id);
    		$objInvitacion->setIdActividad($resultado->current()->id_actividad);
    		$objInvitacion->setEstado($resultado->current()->inv_estado);
    
    	}
    	return $objInvitacion;
    }
    
	public function obtenerGrupoPorInvitar($grupoId)
	{
	    $lista = new SplObjectStorage();
	    $objInvitacion = new Application_Model_Invitacion();
	    $where = 'tip_inv_id = 1 and inv_estado = 1  and id_actividad = '.$grupoId;
		$resultado = $this->_table->fetchall($where);

		if(count($resultado) > 0)
		{
			foreach ($resultado as $item)
			{
				$lista->attach($this->obtenerPorId($item->inv_id));
			}
		}
		
		return $lista;
	}
	
	public function obtenerEventoPorInvitar($eventoId)
	{
		$lista = new SplObjectStorage();
		$objInvitacion = new Application_Model_Invitacion();
		$where = 'tip_inv_id = 2 and inv_estado = 1  and id_actividad = '.$eventoId;
		$resultado = $this->_table->fetchall($where);
	
		if(count($resultado) > 0)
		{
			foreach ($resultado as $item)
			{
				$lista->attach($this->obtenerPorId($item->inv_id));
			}
		}
	
		return $lista;
	}
	
	public function obtenerInvitacionGruposPorUsuario($usuId)
	{
	    $lista = new SplObjectStorage();
	    $objInvitacion = new Application_Model_Invitacion();
	    $where = 'tip_inv_id = 1 and inv_estado = 2  and usu_id = '.$usuId;
	    $resultado = $this->_table->fetchall($where);
	    
	    if(count($resultado) > 0)
	    {
	    	foreach ($resultado as $item)
	    	{
	    		$lista->attach($this->obtenerPorId($item->inv_id));
	    	}
	    }
	    
	    return $lista;
	}
	
	public function obtenerInvitacionEventosPorUsuario($usuId)
	{
		$lista = new SplObjectStorage();
		$objInvitacion = new Application_Model_Invitacion();
		$where = 'tip_inv_id = 2 and inv_estado = 2  and usu_id = '.$usuId;
		$resultado = $this->_table->fetchall($where);
		 
		if(count($resultado) > 0)
		{
			foreach ($resultado as $item)
			{
				$lista->attach($this->obtenerPorId($item->inv_id));
			}
		}
		 
		return $lista;
	}
	
	public function obtenerPorEventoYUsuario($eve_id,$usu_id)
	{
		$where = 'tip_inv_id = 2 and id_actividad ='. $eve_id.' and usu_id ='.$usu_id;
	
		$resultado = $this->_table->fetchAll($where);
	
		if(count($resultado) > 0)
		{
			foreach ($resultado as $item)
			{
				return $this->obtenerPorId($item->inv_id)->getEstado();
			}
		}else{
			return null;
		}
	}
	
	public function obtenerCantidadSeleccionados($evento_id)
	{
		$where = 'tip_inv_id = 2 and inv_estado = 1 and id_actividad = '.$evento_id;
		$resultado = $this->_table->fetchall($where);
		 
		return count($resultado);
	}
    
}