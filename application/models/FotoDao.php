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
    		$objFoto->setNombre($resultado->current()->fot_descripcion);
    		$objFoto->setFoto($resultado->current()->fot_foto);
    		$objFoto->setFecha($resultado->current()->fot_fecha_subida);
    		$objFoto->setAlbumId($resultado->current()->alb_id);
    
    	}
    	return $objFoto;
    }
    
    public function guardar(Application_Model_Foto $foto)
    {
    	$data = array('fot_id' => $foto->getId(),
    			'fot_descripcion' => $foto->getNombre(),
    			'fot_foto' => $foto->getFoto(),
    			'fot_fecha_subida' => $foto->getFecha(),
    			'alb_id' => $foto->getAlbumId()
    	);
    
    	if($foto->getId() != null){
    		$where = 'fot_id = ' . $foto->getId();
    		 
    		return $this->_table->update($data, $where);
    	}
    
    	return $this->_table->insert($data);
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
    
    public function eliminarFotosPorAlbumId($alb_id)
    {
    	$where = 'alb_id = ' . $alb_id;
    
    	return $this->_table->delete($where);
    }
    
    public function obtenerPorNombre($nomFoto)
    {
    	$where = 'fot_foto = "'. $nomFoto.'"';
    	$resultado = $this->_table->fetchAll($where);
    	
    	if(count($resultado) > 0)
    	{
    		return $this->obtenerPorId($resultado->current()->fot_id);
    	}
    	
    	return null;
    	
    	
    	
    
    }

}

