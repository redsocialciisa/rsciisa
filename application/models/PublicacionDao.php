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
        $perfilCiisa = trim($perfilCiisa);
        $aut = Zend_Auth::getInstance();
        
    	$lista = new SplObjectStorage();
    	$objPerfilCiisaDao = new Application_Model_PerfilCiisaDao();
    	$objAmigo = new Application_Model_AmigoDao();
    	
    	//se obtiene la lista de mis amigos, para obtener solo las publicaciones de ellos. 
    	$usu_id_in = "";
    	$listaAmigos = $objAmigo->obtenerTodosPorUsuarioId($aut->getIdentity()->usu_id);
    	$i = 1;
    	foreach ($listaAmigos as $amigo)
    	{
    	    if($i != count($listaAmigos))
    	    {
    	    	$usu_id_in .= $amigo->getAmigoUsuarioId().",";
    	    }else{
    	        $usu_id_in .= $amigo->getAmigoUsuarioId();
    	    }
    	    $i = $i + 1;
    	}
    	//note: la variable $usu_id_in se genera de la siguiente forma = 2,3,5,3
    	//ademas de obtener las publicaciones de mis amigos... debo agragar mis publicaciones al muro, agregamos nuestro ID también.
    	
    	$where = 'usu_id IN ('.$aut->getIdentity()->usu_id.','.$usu_id_in.') AND ';
    	
    	//con el perfil ciisa ej: 'alumno',profesor' etc... se obtiene el perfil que el usuario tiene en nuestra red social.
    	$objPerfilRsc = $objPerfilCiisaDao->obtenerPorPerfilCiisa($perfilCiisa);
    	
    	switch ($objPerfilRsc->getPerfil()) {
    		case 1:
    			$where .= 'pri_pub_id IN (1,4,5,7) AND ';
    			break;
    		case 2:
    			$where .= 'pri_pub_id IN (2,4,6,7) AND ';
    			break;
    		case 3:
    			$where .= 'pri_pub_id IN (3,5,6,7) AND ';
    			break;
    	}
    	
    	$where .= 'tip_pub_id IN (1,2,3)';
    	
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
    
    public function obtenerMisPublicaciones()
    {
        $aut = Zend_Auth::getInstance();
        $lista = new SplObjectStorage();
        
        $objUsuarioDao = new Application_Model_UsuarioDao();
        $objCiisa = new Application_Model_Ciisa();
        $objPerfilCiisaDao = new Application_Model_PerfilCiisaDao();
        
        $perfilCiisa = $objCiisa->obtenerPerfil($aut->getIdentity()->usu_ciisa); //profesor, alumno, secretaria, admin etc...
        
        $objPerfilRsc = $objPerfilCiisaDao->obtenerPorPerfilCiisa($perfilCiisa);
        
        $where = "";
    	      
        switch ($objPerfilRsc->getPerfil()) {
        	case 1:
        		//$where .= 'pri_pub_id IN (1,4,5,7)';
        		break;
        	case 2:
        		//$where .= 'pri_pub_id IN (2,4,6,7)';
        		break;
        	case 3:
        		//$where .= 'pri_pub_id IN (3,5,6,7)';
        		break;
        }
        
    	//$where .= ' AND usu_id_para ='.$aut->getIdentity()->usu_id;
    	$where .= 'usu_id_para ='.$aut->getIdentity()->usu_id;
    	
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
    
    public function obtenerPublicacionesContacto($usu_id_para)
    {
    	$aut = Zend_Auth::getInstance();
    	$lista = new SplObjectStorage();
    
    	$objUsuarioDao = new Application_Model_UsuarioDao();
    	$objCiisa = new Application_Model_Ciisa();
    	$objPerfilCiisaDao = new Application_Model_PerfilCiisaDao();
    
    	$perfilCiisa = $objCiisa->obtenerPerfil($aut->getIdentity()->usu_ciisa); //profesor, alumno, secretaria, admin etc...
    
    	$objPerfilRsc = $objPerfilCiisaDao->obtenerPorPerfilCiisa($perfilCiisa);
    
    	$where = "";
    	 
    	switch ($objPerfilRsc->getPerfil()) {
    		case 1:
    			$where .= 'pri_pub_id IN (1,4,5,7)';
    			break;
    		case 2:
    			$where .= 'pri_pub_id IN (2,4,6,7)';
    			break;
    		case 3:
    			$where .= 'pri_pub_id IN (3,5,6,7)';
    			break;
    	}
    
    	$where .= ' AND usu_id_para ='.$usu_id_para;
    	 
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
    
    public function obtenerPublicacionesGrupoEventoOferta()
    {
    	$aut = Zend_Auth::getInstance();
    	$lista = new SplObjectStorage();
    
    	$objUsuarioDao = new Application_Model_UsuarioDao();
    
    	$where = 'tip_pub_id IN (4,5,6)';
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

}