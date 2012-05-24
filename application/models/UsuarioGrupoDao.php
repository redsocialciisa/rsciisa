<?php

class Application_Model_UsuarioGrupoDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_UsuarioGrupoMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objUsuarioGrupo = null;
    
    	if(count($resultado) > 0){
    
    		$objUsuarioGrupo = new Application_Model_UsuarioGrupo();
    		 
    		$objUsuarioGrupo->setId($resultado->current()->usu_gru_id);
    		$objUsuarioGrupo->setParticipa($resultado->current()->usu_gru_unido);
    		$objUsuarioGrupo->setGrupoId($resultado->current()->gru_id);
    		$objUsuarioGrupo->setUsuarioId($resultado->current()->usu_id);
    		$objUsuarioGrupo->setFechaParticipa($resultado->current()->usu_gru_fecha_une);
    
    	}
    	return $objUsuarioGrupo;
    }
    
    public function guardar(Application_Model_UsuarioGrupo $usuarioGrupo)
    {
    	$data = array('usu_gru_id' => $usuarioGrupo->getId(),
    			'usu_gru_unido' => $usuarioGrupo->getEventoId(),
    			'gru_id' => $usuarioGrupo->getUsuarioId(),
    			'usu_id' => $usuarioGrupo->getAsiste(),
    			'usu_gru_fecha_une' => $usuarioGrupo->getFechaAsiste()
    	);
    
    	if($usuarioGrupo->getId() != null){
    		$where = 'usu_gru_id = ' . $usuarioGrupo->getId();
    		 
    		return $this->_table->update($data, $where);
    	}
    
    	return $this->_table->insert($data);
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
    			$lista->attach($this->obtenerPorId($item->usu_gru_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function obtenerPorGrupoId($gru_id)
    {
    	$lista = new SplObjectStorage();
    	$where = 'gru_id ='. $gru_id;
    
    	$resultado = $this->_table->fetchAll($where);
    
    	if(count($resultado) > 0)
    	{
    		foreach ($resultado as $item)
    		{
    			$lista->attach($this->obtenerPorId($item->usu_gru_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function eliminar($usu_gru_id)
    {
    	$where = 'usu_gru_id = ' . $usu_gru_id;
    
    	return $this->_table->delete($where);
    }

}

