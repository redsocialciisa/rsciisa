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
        	$album->setFechaCreacion($resultado->current()->alb_fecha_creacion);
        	$album->setUsuario($resultado->current()->usu_id);
        	$album->setDescripcion($resultado->current()->alb_descripcion);
        	$album->setPortada($resultado->current()->alb_portada);
        
        }        
    	   return $album;
    }
    
    
    public function obtenerTodosPorUsuarioId($usu_id)
    {
    	$lista = new SplObjectStorage();
    
    	$where = "usu_id =".$usu_id;
    	
    	$resultado = $this->_table->fetchAll($where);
    
    	if(count($resultado) > 0){
    
    		foreach ($resultado as $item)
    		{
    			$lista->attach($this->obtenerPorId($item->alb_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function guardar(Application_Model_Album $album)
    {
    	$data = array('alb_id' => $album->getId(),
    	        'alb_fecha_creacion' => $album->getFechaCreacion(),
    			'alb_nombre' => $album->getNombre(),    			
    	        'usu_id' => $album->getUsuario(),
    	        'alb_descripcion' => $album->getDescripcion(),
    	        'alb_portada' => $album->getPortada()
    	);
    
    	if($album->getId() != null){
    		$where = 'alb_id = ' . $album->getId();
    			
    		return $this->_table->update($data, $where);
    	}
    
    	return $this->_table->insert($data);    
    }
    
    public function eliminar($id)
    {
    	$id = (int)$id;
    	$where = 'alb_id = ' . $id;
    
    	return $this->_table->delete($where);
    }
    
}

?>