<?php

class Application_Model_PublicacionDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_PublicacionMap();
    }
    
    public function obtenerPorId($id)
    {
    	$id = (int)$id;
    
    	$resultado = $this->_table->find($id);
    
    	$objPublicacion = null;
    
    	if(count($resultado) > 0){
    
    		$objPublicacion = new Application_Model_Publicacion();
    		 
    		$objPublicacion->setId($resultado->current()->pub_id);
    		$objPublicacion->setTexto($resultado->current()->pub_texto);
    		$objPublicacion->setFoto($resultado->current()->pub_foto);
    		$objPublicacion->setVideo($resultado->current()->pub_video);
    		$objPublicacion->setFecha($resultado->current()->pub_fecha);
    		$objPublicacion->setPrivacidadId($resultado->current()->pri_pub_id);
    		$objPublicacion->setTipoId($resultado->current()->tip_pub_id);
    		$objPublicacion->setUsuarioId($resultado->current()->usu_id);
    		$objPublicacion->setUsuarioPara($resultado->current()->usu_id_para);
    
    	}
    	return $objPublicacion;
    }   
    
    public function obtenerMuroPrincipal($perfilCiisa)
    {
    	$lista = new SplObjectStorage();
    	$objPerfilCiisaDao = new Application_Model_PerfilCiisaDao();
    	
    	//con el perfil ciisa ej: 'alumno',profesor' etc... se obtiene el perfil que el usuario tiene en nuestra red social.
    	$objPerfilCiisa = $objPerfilCiisaDao->obtenerPorPerfilCiisa($perfilCiisa);
    	
    	if($objPerfilCiisa->getPerfil() == 1)
    	{
    	    $where = 'pri_pub_id IN (1,4,5,7)';
    	}
    	if($objPerfilCiisa->getPerfil() == 2)
    	{
    		$where = 'pri_pub_id IN (2,4,6,7)';
    	}
    	if($objPerfilCiisa->getPerfil() == 3)
    	{
    		$where = 'pri_pub_id IN (3,5,6,7)';
    	}
    	 
    	$order = 'pub_fecha desc';
    	$resultado = $this->_table->fetchAll($where,$order);
    
    	if(count($resultado) > 0){
    
    		foreach ($resultado as $item)
    		{
    			$lista->attach($this->obtenerPorId($item->pub_id));
    		}
    	}
    
    	return $lista;
    }
    
    public function guardar(Application_Model_Publicacion $publicacion)
    {
    	$data = array('pub_id' => $publicacion->getId(),
    			'pub_texto' => $publicacion->getTexto(),
    			'pub_foto' => $publicacion->getFoto(),
    			'pub_video' => $publicacion->getVideo(),
    	        'pub_fecha' => $publicacion->getFecha(),
    	        'pri_pub_id' => $publicacion->getPrivacidadId(),
    	        'tip_pub_id' => $publicacion->getTipoId(),
    	        'usu_id' => $publicacion->getUsuarioId(),
    	        'usu_id_para' => $publicacion->getUsuarioPara()
    	);
    
    	if($publicacion->getId() != null){
    		$where = 'pub_id = ' . $publicacion->getId();
    		 
    		return $this->_table->update($data, $where);
    	}
    
    	return $this->_table->insert($data);
    }
    
    public function eliminar($pub_id)
    {
    	$where = 'pub_id = ' . $pub_id;
    
    	return $this->_table->delete($where);
    }

}

