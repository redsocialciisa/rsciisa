<?php

class Application_Model_GrupoDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_GrupoMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objGrupo = null;
    
    	if(count($resultado) > 0){
    
    		$objGrupo = new Application_Model_Grupo();
    		 
    		$objGrupo->setId($resultado->current()->gru_id);
    		$objGrupo->setNombre($resultado->current()->gru_nombre);
    		$objGrupo->setDescripcion($resultado->current()->gru_descripcion);
    		$objGrupo->setFoto($resultado->current()->gru_foto);
    		$objGrupo->setFechaCreacion($resultado->current()->gru_fecha_creacion);
    		$objGrupo->setTipoGrupoId($resultado->current()->tip_gru_id);
    
    	}
    	return $objGrupo;
    }
    
    public function obtenerPorUsuarioId($usu_id)
    {
    	$lista = new SplObjectStorage();
    	$where = 'usu_id ='. $usu_id;
    
    	$resultado = $this->_table->fetchAll($where);
    
    	if(count($resultado) > 0)
    	{
    		foreach ($resultado as $item)
    		{
    			$lista->attach($this->obtenerPorId($item->gru_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function eliminar($gru_id)
    {
    	$where = 'gru_id = ' . $gru_id;
    
    	return $this->_table->delete($where);
    }

}

