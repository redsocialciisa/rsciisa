<?php

class Application_Model_PublicacionEventoDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_PublicacionEventoMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$publicacionEvento = null;
    
    	if(count($resultado) > 0){
    
    		$publicacionEvento = new Application_Model_PublicacionEvento();
    		 
    		$publicacionEvento->setId($resultado->current()->pub_eve_id);
    		$publicacionEvento->setPublicacionId($resultado->current()->pub_id);
    		$publicacionEvento->setEventoId($resultado->current()->eve_id);
    
    	}
    	return $publicacionEvento;
    }
    
    public function obtenerPorPublicacionId($pub_id)
    {
    	$where = 'pub_id ='. $pub_id;
    	 
    	$resultado = $this->_table->fetchAll($where);
    
    	$publicacionEvento = null;
    
    	if(count($resultado) > 0){
    
    		$publicacionEvento = new Application_Model_PublicacionEvento();
    		 
    		$publicacionEvento->setId($resultado->current()->pub_eve_id);
    		$publicacionEvento->setPublicacionId($resultado->current()->pub_id);
    		$publicacionEvento->setEventoId($resultado->current()->eve_id);
    
    	}
    	return $publicacionEvento;
    }
    
    public function obtenerPublicacionesDelEvento($eve_id)
    {
    	$aut = Zend_Auth::getInstance();
    	$lista = new SplObjectStorage();
    
    	$objPublicacionDao = new Application_Model_PublicacionDao();
    	
    	$where = 'eve_id ='. $eve_id;
    	 
    	$resultado = $this->_table->fetchAll($where);
    
    	if(count($resultado) > 0){
    
    		foreach ($resultado as $item)
    		{
    			$lista->attach($objPublicacionDao->obtenerPorId($this->obtenerPorId($item->pub_eve_id)->getPublicacionId()));
    		}
    	}
    
    	return $lista;
    
    }
    
    public function guardar(Application_Model_PublicacionEvento $publicacionEvento)
    {
    	$data = array('pub_id' => $publicacionEvento->getPublicacionId(),
    			'eve_id' => $publicacionEvento->getEventoId()
    	);
    
    	return $this->_table->insert($data);
    }

}

