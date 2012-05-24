<?php

class Application_Model_TipoGrupoDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_TipoGrupoMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objTipoGrupo = null;
    
    	if(count($resultado) > 0){
    
    		$objTipoGrupo = new Application_Model_TipoGrupo();
    		 
    		$objTipoGrupo->setId($resultado->current()->tip_gru_id);
    		$objTipoGrupo->setNombre($resultado->current()->tip_gru_nombre);
    
    	}
    	return $objTipoGrupo;
    }
    
    public function guardar(Application_Model_TipoGrupo $tipoGrupo)
    {
    	$data = array('tip_gru_id' => $tipoGrupo->getId(),
    			'tip_gru_nombre' => $tipoGrupo->getNombre()
    	);
    
    	if($tipoGrupo->getId() != null){
    		$where = 'tip_gru_id = ' . $tipoGrupo->getId();
    		 
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
    			$lista->attach($this->obtenerPorId($item->tip_gru_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function eliminar($tip_gru_id)
    {
    	$where = 'tip_gru_id = ' . $tip_gru_id;
    
    	return $this->_table->delete($where);
    }

}

