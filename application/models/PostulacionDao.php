<?php

class Application_Model_PostulacionDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_PostulacionMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objPostulacion = null;
    
    	if(count($resultado) > 0){
    
    		$objPostulacion = new Application_Model_Postulacion();
    		 
    		$objPostulacion->setId($resultado->current()->pos_id);
    		$objPostulacion->setFecha($resultado->current()->pos_fecha);
    		$objPostulacion->setUsuarioId($resultado->current()->usu_id);
    		$objPostulacion->setOfertaId($resultado->current()->ofe_id);
    
    	}
    	return $objPostulacion;
    }
    
    public function guardar(Application_Model_Postulacion $postulacion)
    {
    	$data = array('pos_id' => $postulacion->getId(),
    			'pos_fecha' => $postulacion->getFecha(),
    			'usu_id' => $postulacion->getUsuarioId(),
    			'ofe_id' => $postulacion->getOfertaId()
    	);
    
    	if($postulacion->getId() != null){
    		$where = 'pos_id = ' . $postulacion->getId();
    		 
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
    			$lista->attach($this->obtenerPorId($item->pos_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function obtenerPorOfertaId($ofe_id)
    {
    	$lista = new SplObjectStorage();
    	$where = 'ofe_id ='. $ofe_id;
    
    	$resultado = $this->_table->fetchAll($where);
    
    	if(count($resultado) > 0)
    	{
    		foreach ($resultado as $item)
    		{
    			$lista->attach($this->obtenerPorId($item->pos_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function eliminar($pos_id)
    {
    	$where = 'pos_id = ' . $pos_id;
    
    	return $this->_table->delete($where);
    }
    

}

