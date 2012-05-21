<?php

class Application_Model_UsuarioDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_UsuarioMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objUsuario = null;
    
    	if(count($resultado) > 0){
    
    		$objUsuario = new Application_Model_Usuario();
    		 
    		$objUsuario->setId($resultado->current()->usu_id);
    		$objUsuario->setUsuarioCiisa($resultado->current()->usu_ciisa);
    		$objUsuario->setPassword($resultado->current()->usu_password);
    		$objUsuario->setNombre($resultado->current()->usu_nombre);
    		$objUsuario->setCorreo($resultado->current()->usu_correo);
    		$objUsuario->setCv($resultado->current()->usu_cv);
    		$objUsuario->setFechaNacimiento($resultado->current()->usu_fecha_nacimiento);
    		$objUsuario->setEmocionId($resultado->current()->emo_id);
    		$objUsuario->setPerfilId($resultado->current()->per_id);
    
    	}
    	return $objUsuario;
    }
    
    public function obtenerTodos()
    {
    	$lista = new SplObjectStorage();
    
    	$resultado = $this->_table->fetchAll();
    
    	if(count($resultado) > 0)
    	{
    		foreach ($resultado as $item)
    		{
    			$lista->attach($this->obtenerPorId($item->usu_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function obtenerPorNombre($nombre)
    {
    	$lista = new SplObjectStorage();
    	$where = 'usu_nombre like %'. $nombre .'%';
    	$order = 'usu_nombre';
    
    	$resultado = $this->_table->fetchAll($where, $order);
    
    	if(count($resultado) > 0)
    	{
    		foreach ($resultado as $item)
    		{
    			$lista->attach($this->obtenerPorId($item->usu_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function eliminar($usu_id)
    {
    	$where = 'usu_id = ' . $usu_id;
    
    	return $this->_table->delete($where);
    }
    

}


