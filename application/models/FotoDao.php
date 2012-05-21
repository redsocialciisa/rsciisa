<?php

class Application_Model_FotoDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_FotoMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objFoto = null;
    
    	if(count($resultado) > 0){
    
    		$objFoto = new Application_Model_Foto();
    		 
    		$objFoto->setId($resultado->current()->fot_id);
    		$objFoto->setNombre($resultado->current()->fot_nombre);
    		$objFoto->setFoto($resultado->current()->fot_foto);
    		$objFoto->setFecha($resultado->current()->fot_fecha_subida);
    		$objFoto->setAlbumId($resultado->current()->alb_id);
    
    	}
    	return $objFoto;
    }
    
    public function obtenerPorAlbumId($alb_id)
    {
    	$lista = new SplObjectStorage();
    	$where = 'alb_id ='. $alb_id;
    
    	$resultado = $this->_table->fetchAll($where);
    
    	if(count($resultado) > 0)
    	{
    		foreach ($resultado as $item)
    		{
    			$lista->attach($this->obtenerPorId($item->fot_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function eliminar($fot_id)
    {
    	$where = 'fot_id = ' . $fot_id;
    
    	return $this->_table->delete($where);
    }

}

