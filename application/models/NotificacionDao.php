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
    		$objNotificacion->setVista($resultado->current()->not_vista);
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
    	        'not_vista' => $notificacion->getVista(),
    			'usu_id' => $notificacion->getUsuarioId(),
    			'tip_not_id' => $notificacion->getTipoNotificacionId(),
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
    	$order = "not_fecha desc";
    
    	$resultado = $this->_table->fetchAll($where,$order);
    
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

    
    public function cantidadNotificacionesNuevas($usu_id)
    {
        $where = 'usu_id ='. $usu_id .' and not_vista = 0';
        
        $resultado = $this->_table->fetchAll($where);
        
        return count($resultado);
        
    }
    
    public function marcarComoLeido($usu_id)
    {
        $data = array(
        		'not_vista' => '1'
        );
        
        $where = 'usu_id ='. $usu_id .' and not_vista = 0';
        return $this->_table->update($data, $where);
    }
    
}

