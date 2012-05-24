<?php

class Application_Model_IntegracionDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_IntegracionMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objIntegracion = null;
    
    	if(count($resultado) > 0){
    
    		$objIntegracion = new Application_Model_Integracion();
    		 
    		$objIntegracion->setId($resultado->current()->int_id);
    		$objIntegracion->setToken($resultado->current()->int_token);
    		$objIntegracion->setSecret($resultado->current()->int_secret);
    		$objIntegracion->setFechaPermiso($resultado->current()->int_fecha_permiso);
    		$objIntegracion->setUsuarioId($resultado->current()->usu_id);
    		$objIntegracion->setRedId($resultado->current()->red_id);
    		    
    	}
    	return $objIntegracion;
    }
    
    public function guardar(Application_Model_Integracion $integracion)
    {
    	$data = array('int_id' => $integracion->getId(),
    			'int_token' => $integracion->getToken(),
    			'int_secret' => $integracion->getSecret(),
    			'int_fecha_permiso' => $integracion->getFechaPermiso(),
    			'usu_id' => $integracion->getUsuarioId(),
    	        'red_id' => $integracion->getRedId()
    	);
    
    	if($integracion->getId() != null){
    		$where = 'int_id = ' . $integracion->getId();
    		 
    		return $this->_table->update($data, $where);
    	}
    
    	return $this->_table->insert($data);
    }
    
    public function obtenerPorUsuarioAndRedSocial($usu_id, $red_id)
    {
    	$where = 'usu_id ='. $usu_id .'AND red_id ='. $red_id;   
    	$resultado = $this->_table->fetchAll($where);
    
    	$objUsuario = null;
    	
    	if(count($resultado) > 0)
    	{
    		foreach ($resultado as $item)
    		{
    		    $objUsuario = $this->obtenerPorId(($resultado->current()->int_id));
    		}
    	}
    
    	return $objUsuario;
    }
       
    public function obtenerUsuarioId($usu_id)
    {
    	$lista = new SplObjectStorage();
    	$where = 'usu_id ='. $usu_id;
    
    	$resultado = $this->_table->fetchAll($where);
    
    	if(count($resultado) > 0)
    	{
    		foreach ($resultado as $item)
    		{
    			$lista->attach($this->obtenerPorId($item->int_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function eliminar($int_id)
    {
    	$where = 'int_id = ' . $int_id;
    
    	return $this->_table->delete($where);
    }

}

