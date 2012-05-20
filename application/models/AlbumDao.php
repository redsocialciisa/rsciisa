<?php

class Application_Model_AlbumDao
{

    private $_table;
    
    public function __construct()
    {
        $this->_table = new Application_Model_DbTable_AlbumMap();
    }
    
    public function obtenerPorId($id)
    {
        $id = (int)$id;
        
        $resultado = $this->_table->find($id);
        
        $album = null;
        
        if(count($resultado) > 0){
        
        	$album = new Application_Model_Album();
        	
        	$album->setId($resultado->current()->alb_id);
        	$album->setNombre($resultado->current()->alb_nombre);      	
        
        }        
    	   return $album;
    }
}

?>