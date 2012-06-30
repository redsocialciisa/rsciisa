<?php

class Application_Model_MensajeDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_MensajeMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objMensaje = null;
    
    	if(count($resultado) > 0){
    
    		$objMensaje = new Application_Model_Mensaje();
    		 
    		$objMensaje->setId($resultado->current()->men_id);
    		$objMensaje->setDe($resultado->current()->usu_id_de);
    		$objMensaje->setPara($resultado->current()->usu_id_para);
    		$objMensaje->setTexto($resultado->current()->men_texto);
    		$objMensaje->setFecha($resultado->current()->men_fecha);
    
    	}
    	return $objMensaje;
    }
    
    public function guardar(Application_Model_Mensaje $mensaje)
    {
    	$data = array('men_id' => $mensaje->getId(),
    			'usu_id_de' => $mensaje->getDe(),
    			'usu_id_para' => $mensaje->getPara(),
    	        'men_fecha' => $mensaje->getFecha(),
    	        'men_texto' => $mensaje->getTexto()
    			
    	);
    
    	if($mensaje->getId() != null){
    		$where = 'men_id = ' . $mensaje->getId();
    		 
    		return $this->_table->update($data, $where);
    	}
    
    	return $this->_table->insert($data);
    }
    
    public function eliminar($men_id)
    {
    	$where = 'men_id = ' . $men_id;
    
    	return $this->_table->delete($where);
    }

    public function UsuariosEnviadosRecibidos($usu_id)
    {
        $lista = new SplObjectStorage();
        $listaId = array();
        $objUsuarioDao = new Application_Model_UsuarioDao();
        
        $where = 'usu_id_de ='. $usu_id;
        $resultado = $this->_table->fetchAll($where);
        
        if(count($resultado) > 0)
        {
        	foreach ($resultado as $item)
        	{
        	    $listaId[] = $this->obtenerPorId($item->men_id)->getPara();
        	}
        }
        
        $where = 'usu_id_para ='. $usu_id;
        $resultado = $this->_table->fetchAll($where);
        
        if(count($resultado) > 0)
        {
        	foreach ($resultado as $item)
        	{
        		$listaId[] = $this->obtenerPorId($item->men_id)->getDe();
        	}
        }
        
        $listaId = array_unique($listaId);
        
        foreach ($listaId as $item)
        {
            $lista->attach($objUsuarioDao->obtenerPorId($item));
        }
        
        return $lista;
    }
    
    public function obtenerMensajes($usu_id,$usu_id_menu)
    {
        $lista = new SplObjectStorage();
        $where = "usu_id_de = ".$usu_id." and usu_id_para = ".$usu_id_menu." or usu_id_de = ".$usu_id_menu." and usu_id_para = ".$usu_id;
        $order = "men_fecha asc";
        
        $resultado = $this->_table->fetchAll($where, $order);
        
        if(count($resultado) > 0)
        {
        	foreach ($resultado as $item)
        	{
        			$lista->attach($this->obtenerPorId($item->men_id));
        	}
        }
        
        return $lista;
    }
    

}

