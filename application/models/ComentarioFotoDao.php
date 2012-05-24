<?php

class Application_Model_ComentarioFotoDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_ComentarioFotoMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objComentarioFoto = null;
    
    	if(count($resultado) > 0){
    
    		$objComentarioFoto = new Application_Model_Comentario();
    		 
    		$objComentarioFoto->setId($resultado->current()->com_fot_id);
    		$objComentarioFoto->setTexto($resultado->current()->com_fot_texto);
    		$objComentarioFoto->setFecha($resultado->current()->com_fecha);
    		$objComentarioFoto->setPublicacionId($resultado->current()->fot_id);
    		    
    	}
    	return $objComentarioFoto;
    }
    
    public function obtenerPorFotoId($fot_id)
    {
    	$lista = new SplObjectStorage();
    	$where = 'fot_id > '. $fot_id;
    
    	$resultado = $this->_table->fetchAll($where);
    
    	if(count($resultado) > 0)
    	{
    		foreach ($resultado as $item)
    		{
    			$lista->attach($this->obtenerPorId($item->com_fot_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function guardar(Application_Model_ComentarioFoto $comentarioFoto)
    {
    	$data = array('com_fot_id' => $comentarioFoto->getId(),
    			'com_fot_texto' => $comentarioFoto->getTexto(),
    			'com_fecha' => $comentarioFoto->getFecha(),
    			'fot_id' => $comentarioFoto->getFotoId()
    	);
    
    	if($comentarioFoto->getId() != null){
    		$where = 'com_fot_id = ' . $comentarioFoto->getId();
    		 
    		return $this->_table->update($data, $where);
    	}
    
    	return $this->_table->insert($data);
    }
    
    public function eliminar($com_fot_id)
    {
    	$where = 'com_fot_id = ' . $com_fot_id;
    
    	return $this->_table->delete($where);
    }

}

