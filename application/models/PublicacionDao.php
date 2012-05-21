<?php

class Application_Model_PublicacionDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_PublicacionMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objPublicacion = null;
    
    	if(count($resultado) > 0){
    
    		$objPublicacion = new Application_Model_Publicacion();
    		 
    		$objPublicacion->setId($resultado->current()->pub_id);
    		$objPublicacion->setTexto($resultado->current()->pub_texto);
    		$objPublicacion->setFoto($resultado->current()->pub_foto);
    		$objPublicacion->setVideo($resultado->current()->pub_video);
    		$objPublicacion->setFecha($resultado->current()->pub_fecha);
    		$objPublicacion->setPrivacidadId($resultado->current()->pri_pub_id);
    		$objPublicacion->setTipoId($resultado->current()->tip_pub_id);
    		$objPublicacion->setUsuarioId($resultado->current()->usu_id);
    
    	}
    	return $objPublicacion;
    }   
    
    public function eliminar($pub_id)
    {
    	$where = 'pub_id = ' . $pub_id;
    
    	return $this->_table->delete($where);
    }

}

