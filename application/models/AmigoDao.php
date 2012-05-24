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
    
    public function obtenerPorUsuarioId($usu_id)
    {
    	$lista = new SplObjectStorage();
    	$where = 'usu_id = '. $usu_id;
    
    	$resultado = $this->_table->fetchAll($where);
    
    	if(count($resultado) > 0){
    
    		foreach ($resultado as $item)
    		{
    			$lista->attach($this->obtenerPorId($item->ami_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function guardarSolicitud(Application_Model_Amigo $amigo)
    {
        $sit_ami_id = "1";
        
    	$data = array('ami_fecha_solicitud' => $amigo->getFechaSolicitud(),
    			'ami_usu_id' => $amigo->getAmigoUsuarioId(),
    			'usu_id' => $amigo->getUsuarioId(),
    			'sit_ami_id' => $sit_ami_id
    	);    
        
    	return $this->_table->insert($data);
    }
    
   public function confirmarAmistad(Application_Model_Amigo $amigo)
   {
	    $sit_ami_id = "2";
	       
	   	$data = array('ami_fecha_amistad' => $amigo->getFechaAmistad(),
	   	        	  'sit_ami_id' => $sit_ami_id
	   	);
	   
	   	$where = 'usu_id = ' . $amigo->getUsuarioId() . 'AND ami_usu_id ='. $amigo->getAmigoUsuarioId();
	   		 
	   	return $this->_table->update($data, $where);   		
   }
    
    public function eliminarAmigo($usu_id, $ami_usu_id)
    {
    	$where = 'usu_id = ' . $usu_id . 'AND ami_usu_id ='. $ami_usu_id;
    
    	return $this->_table->delete($where);
    }  


    
}

?>