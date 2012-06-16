<?php

class Application_Model_AmigoDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_AmigoMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$amigo = null;
    
    	if(count($resultado) > 0){
    
    		$amigo = new Application_Model_Amigo();
    		 
    		$amigo->setId($resultado->current()->ami_id);
    		$amigo->setFechaSolicitud($resultado->current()->ami_fecha_solicitud);
    		$amigo->setFechaAmistad($resultado->current()->ami_fecha_amistad);
    		$amigo->setAmigoUsuarioId($resultado->current()->ami_usu_id);
    		$amigo->setUsuarioId($resultado->current()->usu_id);
    		$amigo->setEstadoAmistad($resultado->current()->sit_ami_id);
    
    	}
    	return $amigo;
    }    
    
    public function obtenerCantidadContactosPorUsuarioId($usu_id,$limit)
    {
    	$lista = new SplObjectStorage();
    	$where = 'usu_id = '. $usu_id . ' AND sit_ami_id = 2';
    	
    	$resultado = $this->_table->fetchAll($where,null,$limit);
    
    	if(count($resultado) > 0){
    
    		foreach ($resultado as $item)
    		{
    			$lista->attach($this->obtenerPorId($item->ami_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function sonAmigos($usu_id,$ami_usu_id)
    {
    	$lista = new SplObjectStorage();
    	$where = 'usu_id = '. $usu_id . ' AND ami_usu_id = '.$ami_usu_id.' AND sit_ami_id = 2';
    	 
    	$resultado = $this->_table->fetchAll($where);
    
    	if(count($resultado) > 0){
			return true;    
    	}
    
    	return false;
    }
    
    public function solicitudPendiente($usu_id,$ami_usu_id)
    {
    	$lista = new SplObjectStorage();
    	$where = 'usu_id = '. $usu_id . ' AND ami_usu_id = '.$ami_usu_id.' AND sit_ami_id = 1';
    
    	$resultado = $this->_table->fetchAll($where);
    
    	if(count($resultado) > 0){
    		return true;
    	}
    
    	return false;
    }
    
    public function obtenerTodosPorUsuarioId($usu_id)
    {
    	$lista = new SplObjectStorage();
    	
    	$where = 'usu_id = '. $usu_id . ' AND sit_ami_id = 2';
    	 
    	$resultado = $this->_table->fetchAll($where);
    
    	if(count($resultado) > 0){
    
    		foreach ($resultado as $item)
    		{
    			$lista->attach($this->obtenerPorId($item->ami_id));
    		}
    	}
    	
    	$objUsuarioDao = null;
    	
    	return $lista;
    }
    
    public function obtenerTodosUsuariosPorUsuarioId($usu_id)
    {
    	$lista = new SplObjectStorage();
    	$objUsuarioDao = new Application_Model_UsuarioDao();
    	 
    	$where = 'usu_id = '. $usu_id . ' AND sit_ami_id = 2';
    
    	$resultado = $this->_table->fetchAll($where);
    
    	if(count($resultado) > 0){
    
    		foreach ($resultado as $item)
    		{
    			$lista->attach($objUsuarioDao->obtenerPorId($this->obtenerPorId($item->ami_id)->getAmigoUsuarioId()));
    		}
    	}
    	 
    	$objUsuarioDao = null;
    	 
    	return $lista;
    }
    
    public function obtenerSolicitudesPendientesPorUsuarioId($usu_id)
    {
    	$lista = new SplObjectStorage();
    	$objUsuarioDao = new Application_Model_UsuarioDao();
    	 
    	$where = 'ami_usu_id = '. $usu_id . ' AND sit_ami_id = 1';
    
    	$resultado = $this->_table->fetchAll($where);
    
    	if(count($resultado) > 0){
    
    		foreach ($resultado as $item)
    		{
    			$lista->attach($objUsuarioDao->obtenerPorId($this->obtenerPorId($item->ami_id)->getUsuarioId()));
    		}
    	}
    	 
    	$objUsuarioDao = null;
    	 
    	return $lista;
    }
    
    public function guardarSolicitud(Application_Model_Amigo $amigo)
    {
    	$data = array('ami_fecha_solicitud' => $amigo->getFechaSolicitud(),
    			'ami_usu_id' => $amigo->getAmigoUsuarioId(),
    			'usu_id' => $amigo->getUsuarioId(),
    			'sit_ami_id' => $amigo->getEstadoAmistad()
    	);    
        
    	return $this->_table->insert($data);
    }
    
    public function guardarAsociacionAutomatica(Application_Model_Amigo $amigo)
    {
    	$data = array('ami_fecha_solicitud' => $amigo->getFechaSolicitud(),
    	        'ami_fecha_amistad' => $amigo->getFechaAmistad(),
    			'ami_usu_id' => $amigo->getAmigoUsuarioId(),
    			'usu_id' => $amigo->getUsuarioId(),
    			'sit_ami_id' => $amigo->getEstadoAmistad()
    	);
    
    	return $this->_table->insert($data);
    }
    
   public function confirmarAmistad(Application_Model_Amigo $amigo)
   {
	   	$data = array('ami_fecha_amistad' => $amigo->getFechaAmistad(),
	   	        	  'sit_ami_id' => $amigo->getEstadoAmistad()
	   	);
	   
	   	$where = 'usu_id = ' . $amigo->getUsuarioId() . ' AND ami_usu_id = '. $amigo->getAmigoUsuarioId();
	   		 
	   	return $this->_table->update($data, $where);   		
   }
    
    public function eliminarAmigo($usu_id, $ami_usu_id)
    {
    	$where = 'usu_id = ' . $usu_id . ' AND ami_usu_id ='. $ami_usu_id;
    	$this->_table->delete($where);
    	
    	$where = 'usu_id = ' . $ami_usu_id . ' AND ami_usu_id ='. $usu_id;
    	return $this->_table->delete($where);
    }  
<<<<<<< HEAD
=======

	public function amigoNoInvitadoGrupo($grupoId,$usu_id)
	{
	    $aut = Zend_Auth::getInstance();
	    
	    $lista = new SplObjectStorage();
	    $where = 'usu_id not in (SELECT usu_id FROM rsc_usuarios_grupos where gru_id = '.$grupoId.') and ami_usu_id ='.$usu_id;
	    $objUsuarioDao = new Application_Model_UsuarioDao();
	    
	    $resultado = $this->_table->fetchAll($where);
	    
	    if(count($resultado) > 0)
	    {
	    	foreach ($resultado as $item)
	    	{
	    	    #echo $item->usu_id;
	    	    $lista->attach($objUsuarioDao->obtenerPorId($item->usu_id));
	    	}
	    }
	    
	    return $lista;
	    
	}
    
>>>>>>> refs/heads/rfribla
}

?>
