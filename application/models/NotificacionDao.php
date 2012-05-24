<?php

class Application_Model_NotificacionDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_NotificacionMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objNotificacion = null;
    
    	if(count($resultado) > 0){
    
    		$objNotificacion = new Application_Model_Notificacion();
    		 
    		$objNotificacion->setId($resultado->current()->not_id);
    		$objNotificacion->setTexto($resultado->current()->not_texto);
    		$objNotificacion->setFecha($resultado->current()->not_fecha);
    		$objNotificacion->setUsuarioId($resultado->current()->usu_id);
    		$objNotificacion->setTipoNotificacionId($resultado->current()->tip_not_id);
    
    	}
    	return $objNotificacion;
    }
    
    public function guardar(Application_Model_Notificacion $notificacion)
    {
    	$data = array('not_id' => $notificacion->getId(),
    			'not_texto' => $notificacion->getTexto(),
    			'not_fecha' => $notificacion->getFecha(),
    			'usu_id' => $notificacion->getUsuarioId(),
    			'tip_not_id' => $notificacion->getTipoNotificacionId()
    	);
    
    	if($notificacion->getId() != null){
    		$where = 'not_id = ' . $notificacion->getId();
    		 
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
    			$lista->attach($this->obtenerPorId($item->not_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function eliminar($not_id)
    {
    	$where = 'not_id = ' . $not_id;
    
    	return $this->_table->delete($where);
    }

}

