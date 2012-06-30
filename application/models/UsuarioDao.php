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
    		$objUsuario->setAcepta($resultado->current()->usu_acepta);
    		$objUsuario->setPerfilId($resultado->current()->per_id);
    		$objUsuario->setFoto($resultado->current()->usu_foto);
    		$objUsuario->setPrivacidadPublicacionId($resultado->current()->pri_pub_id);
    	}
    	return $objUsuario;
    }
    
    public function obtenerPorUsuarioCiisa($usuario)
    {
    	$where = "usu_ciisa = '" .$usuario . "'";
    	 
    	$resultado = $this->_table->fetchAll($where);
    	 
    	if(count($resultado) > 0)
    	{
    		return $this->obtenerPorId($resultado->current()->usu_id);
    	}
    
    	return null;
    }   
    
       
	public function validarAdministrador($usuario, $password)    
	{
    	$where = "usu_ciisa = '" .$usuario . "' AND usu_password = '". $password. "'";
    	
    	$resultado = $this->_table->fetchAll($where);
    	
    	if(count($resultado) > 0)
    	{
    	    return $this->obtenerPorId($resultado->current()->usu_id);
    	}
    
    	return null;
    }
    
    public function guardar(Application_Model_Usuario $usuario)
    {
    	$data = array('usu_id' => $usuario->getId(),
    			'usu_ciisa' => $usuario->getUsuarioCiisa(),
    	        'usu_password' => $usuario->getPassword(),
    	        'usu_nombre' => $usuario->getNombre(),
    	        'usu_correo' => $usuario->getCorreo(),
    	        'usu_fecha_nacimiento' => $usuario->getFechaNacimiento(),
    	        'emo_id' => $usuario->getEmocionId(),
    	        'usu_acepta' => $usuario->getAcepta(),
    	        'usu_foto' => $usuario->getFoto(),
    	        'per_id' => $usuario->getPerfilId(),
    	        'pri_pub_id' => $usuario->getPrivacidadPublicacionId() 
    	);
    
    	if($usuario->getId() != null){
    		$where = 'usu_id = ' . $usuario->getId();
    		 
    		return $this->_table->update($data, $where);
    	}
    
    	return $this->_table->insert($data);
    }
    
    public function actualizar(Application_Model_Usuario $usuario)
    {
    	$data = array('usu_id' => $usuario->getId(),
    			'usu_nombre' => $usuario->getNombre(),
    			'usu_correo' => $usuario->getCorreo(),
    			'usu_fecha_nacimiento' => $usuario->getFechaNacimiento(),
    	        'usu_cv' => $usuario->getCv(),
    			'usu_foto' => $usuario->getFoto(),
    			'pri_pub_id' => $usuario->getPrivacidadPublicacionId(),
    	        'emo_id' => $usuario->getPrivacidadPublicacionId()
    	);
    
    	if($usuario->getId() != null){
    		$where = 'usu_id = ' . $usuario->getId();
    		 
    		return $this->_table->update($data, $where);
    	}
    
    }
    
    public function guardarAceptacion(Application_Model_Usuario $usuario)
    {
    	$data = array('usu_id' => $usuario->getId(),
    			'usu_acepta' => "1",
    	);
    
    	if($usuario->getId() != null){
    		$where = 'usu_id = ' . $usuario->getId();
    		 
    		return $this->_table->update($data, $where);
    	}
    }
    
    public function actualizaEstadoAnimo(Application_Model_Usuario $usuario)
    {
    	$data = array('usu_id' => $usuario->getId(),
    			'emo_id' => $usuario->getEmocionId(),
    	);
    
    	if($usuario->getId() != null){
    		$where = 'usu_id = ' . $usuario->getId();
    		 
    		return $this->_table->update($data, $where);
    	}
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
    
    public function obtenerTodosLosNombres()
    {
    	$lista = new SplObjectStorage();
    
    	$resultado = $this->_table->fetchAll();
    
    	if(count($resultado) > 0)
    	{
    		foreach ($resultado as $item)
    		{
    			$lista->attach($this->obtenerPorNombrePorId($item->usu_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function obtenerTodosPorString($cadena)
    {
        $cadena = trim($cadena);
        
    	$lista = new SplObjectStorage();
    	$where = "usu_nombre like '%".$cadena."%'";
    	$order = "usu_nombre asc";
    	
    	$resultado = $this->_table->fetchAll($where, $order);
    
    	if(count($resultado) > 0)
    	{
    		foreach ($resultado as $item)
    		{
    		    if($item->usu_id != 6) //administrador
    		    {
    				$lista->attach($this->obtenerPorId($item->usu_id));
    		    }
    		}
    	}
    
    	return $lista;
    }

    public function obtenerPorNombrePorId($id)
    {/*exclusivo para el usu de buscar contactos*/
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objUsuario = null;
    
    	if(count($resultado) > 0){
    
    		$objUsuario = new Application_Model_Usuario();
    		 
    		$objUsuario->setId($resultado->current()->usu_id);
    		$objUsuario->setNombre($resultado->current()->usu_nombre);
    	}
    	return $objUsuario;
    }
    
    public function obtenerPorNombre($nombre)
    {
    	$lista = new SplObjectStorage();
    	$where = 'usu_nombre like %'. $nombre .'%';
    	//$order = 'usu_nombre';
    
    	$resultado = $this->_table->fetchAll($where);
    
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
    
    public function obtenerNombreAmigo($usu_id)
    {
    	$lista = new SplObjectStorage();
    	$where = 'usu_id = ' . $usu_id;
    	$resultado = $this->_table->fetchAll($where);
    
    	if(count($resultado) > 0)
    	{
    		foreach ($resultado as $item)
    		{
    			$lista->attach($this->obtenerPorNombrePorId($item->usu_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function obtenerPorNombreExacto($nombre)
    {
    	$where = "usu_nombre = '" .$nombre . "'";
    
    	$resultado = $this->_table->fetchAll($where);
    
    	if(count($resultado) > 0)
    	{
    		return $this->obtenerPorId($resultado->current()->usu_id);
    	}
    
    	return null;
    }
    
    
}


