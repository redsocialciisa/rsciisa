<?php

class Application_Model_EmocionDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_EmocionMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objEmocion = null;
    
    	if(count($resultado) > 0){
    
    		$objEmocion = new Application_Model_Emocion();
    		 
    		$objEmocion->setId($resultado->current()->emo_id);
    		$objEmocion->setNombre($resultado->current()->emo_nombre);
    		$objEmocion->setFoto($resultado->current()->emo_foto);
    
    	}
    	return $objEmocion;
    }
    
    public function obtenerTodos()
    {
    	$lista = new SplObjectStorage();
    
    	$resultado = $this->_table->fetchAll();
    
    	if(count($resultado) > 0)
    	{
    		foreach ($resultado as $item)
    		{
    			$lista->attach($this->obtenerPorId($item->emo_id));
    		}
    	}
    
    	return $lista;
    }
    
 	public function eliminar($emo_id)
    {
    	$where = 'emo_id = ' . $emo_id;
    
    	return $this->_table->delete($where);
    }

}

