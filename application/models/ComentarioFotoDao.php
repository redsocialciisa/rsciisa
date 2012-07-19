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
    
    		$objComentarioFoto = new Application_Model_ComentarioFoto();
    		 
    		$objComentarioFoto->setId($resultado->current()->com_fot_id);
    		$objComentarioFoto->setTexto($resultado->current()->com_fot_texto);
    		$objComentarioFoto->setFecha($resultado->current()->com_fecha);
    		$objComentarioFoto->setFotoId($resultado->current()->fot_id);
    		$objComentarioFoto->setUsuId($resultado->current()->usu_id);
    		    
    	}
    	return $objComentarioFoto;
    }
    
    public function obtenerPorFotoId($fot_id)
    {
    	$lista = new SplObjectStorage();
    	$where = 'fot_id = '. $fot_id;
    
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
    
    public function obtenerPorFotoIdDesc($fot_id)
    {
    	$lista = new SplObjectStorage();
    	$where = 'fot_id = '. $fot_id;
    	$order = 'com_fecha desc';
    
    	$resultado = $this->_table->fetchAll($where,$order);
    
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
    			'fot_id' => $comentarioFoto->getFotoId(),
    	        'usu_id' => $comentarioFoto->getUsuId()
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
    
    public function eliminarComentariosPorFotoId($fot_id)
    {
    	$where = 'fot_id = ' . $fot_id;
    
    	return $this->_table->delete($where);
    }

}

