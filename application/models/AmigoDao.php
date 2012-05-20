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
    
    public function obtenerTodos($id){
        
    }
    
}

?>