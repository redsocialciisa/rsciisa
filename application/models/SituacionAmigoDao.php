<?php

class Application_Model_SituacionAmigoDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_SituacionAmigoMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objSituacionAmigo = null;
    
    	if(count($resultado) > 0){
    
    		$objRedSocial = new Application_Model_SituacionAmigo();
    		 
    		$objSituacionAmigo->setId($resultado->current()->sit_ami_id);
    		$objSituacionAmigo->setNombre($resultado->current()->sit_ami_nombre);
    
    	}
    	return $objSituacionAmigo;
    }
    
    public function guardar(Application_Model_SituacionAmigo $situacionAmigo)
    {
    	$data = array('sit_ami_id' => $situacionAmigo->getId(),
    			'sit_ami_nombre' => $situacionAmigo->getNombre()
    	);
    
    	if($situacionAmigo->getId() != null){
    		$where = 'sit_ami_id = ' . $situacionAmigo->getId();
    		 
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
    			$lista->attach($this->obtenerPorId($item->sit_ami_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function eliminar($sit_ami_id)
    {
    	$where = 'sit_ami_id = ' . $sit_ami_id;
    
    	return $this->_table->delete($where);
    }

}

