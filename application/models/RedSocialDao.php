<?php

class Application_Model_RedSocialDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_RedSocialMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objRedSocial = null;
    
    	if(count($resultado) > 0){
    
    		$objRedSocial = new Application_Model_RedSocial();
    		 
    		$objRedSocial->setId($resultado->current()->red_id);
    		$objRedSocial->setNombre($resultado->current()->red_nombre);
    
    	}
    	return $objRedSocial;
    }
    
    public function guardar(Application_Model_RedSocial $redSocial)
    {
    	$data = array('red_id' => $redSocial->getId(),
    			'red_nombre' => $redSocial->getNombre()
    	);
    
    	if($redSocial->getId() != null){
    		$where = 'red_id = ' . $redSocial->getId();
    		 
    		return $this->_table->update($data, $where);
    	}
    
    	return $this->_table->insert($data);
    }
    
    public function obtenerTodos()
    {
    	$lista = new SplObjectStorage();
    
    	$resultado = $this->_table->fetchAll();
    
    	if(count($resultado) > 0)
    	{
    		foreach ($resultado as $item)
    		{
    			$lista->attach($this->obtenerPorId($item->red_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function eliminar($red_id)
    {
    	$where = 'red_id = ' . $red_id;
    
    	return $this->_table->delete($where);
    }

}

