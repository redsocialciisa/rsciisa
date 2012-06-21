<?php

class Application_Model_PublicacionGrupoDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_PublicacionGrupoMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$publicacionGrupo = null;
    
    	if(count($resultado) > 0){
    
    		$publicacionGrupo = new Application_Model_PublicacionGrupo();
    		 
    		$publicacionGrupo->setId($resultado->current()->pub_gru_id);
    		$publicacionGrupo->setPublicacionId($resultado->current()->pub_id);
    		$publicacionGrupo->setGrupoId($resultado->current()->gru_id);
    
    	}
    	return $publicacionGrupo;
    }
    
    public function obtenerPorPublicacionId($pub_id)
    {
    	$where = 'pub_id ='. $pub_id;
    	 
    	$resultado = $this->_table->fetchAll($where);
    
    	$publicacionGrupo = null;
    
    	if(count($resultado) > 0){
    
    		$publicacionGrupo = new Application_Model_PublicacionGrupo();
    		 
    		$publicacionGrupo->setId($resultado->current()->pub_gru_id);
    		$publicacionGrupo->setPublicacionId($resultado->current()->pub_id);
    		$publicacionGrupo->setGrupoId($resultado->current()->gru_id);
    
    	}
    	return $publicacionGrupo;
    }
    
    public function obtenerPublicacionesDelGrupo($gru_id)
    {
    	$aut = Zend_Auth::getInstance();
    	$lista = new SplObjectStorage();
    
    	$objPublicacionDao = new Application_Model_PublicacionDao();
    	
    	$where = 'gru_id ='. $gru_id;
    	 
    	$resultado = $this->_table->fetchAll($where);
    
    	if(count($resultado) > 0){
    
    		foreach ($resultado as $item)
    		{
    			$lista->attach($objPublicacionDao->obtenerPorId($this->obtenerPorId($item->pub_gru_id)->getPublicacionId()));
    		}
    	}
    
    	return $lista;
    
    }
    
    public function guardar(Application_Model_PublicacionGrupo $publicacionGrupo)
    {
    	$data = array('pub_id' => $publicacionGrupo->getPublicacionId(),
    			'gru_id' => $publicacionGrupo->getGrupoId()
    	);
    
    	return $this->_table->insert($data);
    }

}

