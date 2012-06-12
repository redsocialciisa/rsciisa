<?php

class Application_Model_ComentarioDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_ComentarioMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objComentario = null;
    
    	if(count($resultado) > 0){
    
    		$objComentario = new Application_Model_Comentario();
    		 
    		$objComentario->setId($resultado->current()->com_id);
    		$objComentario->setTexto($resultado->current()->com_texto);
    		$objComentario->setFecha($resultado->current()->com_fecha);
    		$objComentario->setPublicacionId($resultado->current()->pub_id);
    		$objComentario->setUsuarioId($resultado->current()->usu_id);
    
    	}
    	return $objComentario;
    }
    
    public function obtenerPorPublicacionId($pub_id)
    {
    	$lista = new SplObjectStorage();
    	
    	$where = 'pub_id ='. $pub_id;
    	$order = 'com_fecha desc';
    	$count = 5;
    
    	$resultado = $this->_table->fetchAll($where,$order,$count);
    
    	if(count($resultado) > 0)
    	{    
    		foreach ($resultado as $item)
    		{
    			$lista->attach($this->obtenerPorId($item->com_id));
    		}
    		
    	}
    
    	return $lista;
    }
    
    public function obtenerTodosPorPublicacionId($pub_id)
    {
    	$lista = new SplObjectStorage();
    	 
    	$where = 'pub_id ='. $pub_id;
    	$order = 'com_fecha desc';
    
    	$resultado = $this->_table->fetchAll($where,$order);
    
    	if(count($resultado) > 0)
    	{
    		foreach ($resultado as $item)
    		{
    			$lista->attach($this->obtenerPorId($item->com_id));
    		}
    
    	}
    
    	return $lista;
    }
    
    public function obtenerCantidadComentariosPorPublicacionId($pub_id)
    {
    	$where = 'pub_id ='. $pub_id;
    
    	$resultado = $this->_table->fetchAll($where);
        
    	return count($resultado);
    }
    
    public function guardar(Application_Model_Comentario $comentario)
    {
    	$data = array('com_id' => $comentario->getId(),
    			'com_texto' => $comentario->getTexto(),
    			'com_fecha' => $comentario->getFecha(),
    			'pub_id' => $comentario->getPublicacionId(),
    	        'usu_id' => $comentario->getUsuarioId()
    	);
    
    	if($comentario->getId() != null){
    		$where = 'com_id = ' . $comentario->getId();
    		 
    		return $this->_table->update($data, $where);
    	}
    
    	return $this->_table->insert($data);
    }
    
    public function eliminar($com_id)
    {
    	$where = 'com_id = ' . $com_id;
    
    	return $this->_table->delete($where);
    }

}

