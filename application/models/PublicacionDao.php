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
    	
    	if(count($listaAmigos) > 0){
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
    	}
    	//note: la variable $usu_id_in se genera de la siguiente forma = 2,3,5,3
    	//ademas de obtener las publicaciones de mis amigos... debo agragar mis publicaciones al muro, agregamos nuestro ID también.
    	
    	if(count($listaAmigos) > 0){
    		$where = 'usu_id IN ('.$aut->getIdentity()->usu_id.','.$usu_id_in.') AND ';
    	}else{
    	    $where = 'usu_id IN ('.$aut->getIdentity()->usu_id.') AND ';
    	}
    	
    	//con el perfil ciisa ej: 'alumno',profesor' etc... se obtiene el perfil que el usuario tiene en nuestra red social.
    	$objPerfilRsc = $objPerfilCiisaDao->obtenerPorPerfilCiisa($perfilCiisa);
    	
    	/*
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
    	}*/
    	
    	$where .= 'tip_pub_id IN (1,2,3)';
    	
    	$order = 'pub_fecha desc';
    	$resultado = $this->_table->fetchAll($where,$order);
    
    	if(count($resultado) > 0){ 
    		foreach ($resultado as $item)
    		{
    		    $objPublicacionGrupoDao = new Application_Model_PublicacionGrupoDao();
    		     
    		    if($objPublicacionGrupoDao->obtenerPorPublicacionId($item->pub_id) == null)
    		    {
    		        switch ($objPerfilRsc->getPerfil()) {
	    		    	case 1:
	    		    	    if($item->pri_pub_id == 1 || $item->pri_pub_id == 4 || $item->pri_pub_id == 5 || $item->pri_pub_id == 7 || $item->usu_id == $aut->getIdentity()->usu_id){
	    		    	    	$lista->attach($this->obtenerPorId($item->pub_id));
	    		    	    }
	    		    		break;
	    		    	case 2:
    		    	        if($item->pri_pub_id == 2 || $item->pri_pub_id == 4 || $item->pri_pub_id == 6 || $item->pri_pub_id == 7 || $item->usu_id == $aut->getIdentity()->usu_id){
    		    	       		$lista->attach($this->obtenerPorId($item->pub_id));
    		    	        }
	    		    		break;
	    		    	case 3:
    		    	        if($item->pri_pub_id == 3 || $item->pri_pub_id == 5 || $item->pri_pub_id == 6 || $item->pri_pub_id == 7 || $item->usu_id == $aut->getIdentity()->usu_id){
    		    	    		$lista->attach($this->obtenerPorId($item->pub_id));
    		    	        }
	    		    		break;
	    		    }
    		    }
    		    $objPublicacionGrupoDao = null;
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
    	$where .= 'usu_id_para ='.$aut->getIdentity()->usu_id.' AND ';
    	$where .= 'tip_pub_id IN (1,2,3)';
    	
    	$order = 'pub_fecha desc';
    	$resultado = $this->_table->fetchAll($where,$order);
    	
    	if(count($resultado) > 0){
    
    		foreach ($resultado as $item)
    		{
    		    $objPublicacionGrupoDao = new Application_Model_PublicacionGrupoDao();
    		    
    		    if($objPublicacionGrupoDao->obtenerPorPublicacionId($item->pub_id) == null)
    		    {
    		        $lista->attach($this->obtenerPorId($item->pub_id));
    		    }
    		    
    			$objPublicacionGrupoDao = null;
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
    
    	$where .= ' AND usu_id_para ='.$usu_id_para.' AND ';
    	$where .= 'tip_pub_id IN (1,2,3)';
    	 
    	$order = 'pub_fecha desc';
    	$resultado = $this->_table->fetchAll($where,$order);
    	 
    	if(count($resultado) > 0){
    
    		foreach ($resultado as $item)
    		{
    			$objPublicacionGrupoDao = new Application_Model_PublicacionGrupoDao();
    			
    			if($objPublicacionGrupoDao->obtenerPorPublicacionId($item->pub_id) == null)
    			{
    				$lista->attach($this->obtenerPorId($item->pub_id));
    			}
    			
    			$objPublicacionGrupoDao = null;
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
    	$objPublicacionGrupoDao = new Application_Model_PublicacionGrupoDao();
    	$objPublicacionEventoDao = new Application_Model_PublicacionEventoDao();
    	
    	$objGrupoDao = new Application_Model_GrupoDao();
    	$objEventoDao = new Application_Model_EventoDao();
    
    	$where = 'tip_pub_id IN (4,5)';
    	$order = 'pub_fecha desc';
    	
    	$resultado = $this->_table->fetchAll($where,$order);
    	 
    	if(count($resultado) > 0){
    
    		foreach ($resultado as $item)
    		{
    		    if($item->tip_pub_id == 5)
    		    {
    		    	if($objGrupoDao->obtenerPorId($objPublicacionGrupoDao->obtenerPorPublicacionId($item->pub_id)->getGrupoId())->getTipoGrupoId() == 0)
    		    	{
    					$lista->attach($this->obtenerPorId($item->pub_id));
    		    	}
    		    }
    		    
    		    if($item->tip_pub_id == 4)
    		    {
    		        $objEventoAux = $objEventoDao->obtenerPorId($objPublicacionEventoDao->obtenerPorPublicacionId($item->pub_id)->getEventoId());
    		        
    		        if($objEventoAux->getTipoEventoId() == 1 && $objEventoAux->getCancelado() == 0)
    		    	{
    		    		$lista->attach($this->obtenerPorId($item->pub_id));
    		    	}
    		    	
    		    	$objEventoAux = null;
    		    }
    		    
    		}
    	}
    	
    	$objGrupoDao = null;
    	$objPublicacionGrupoDao = null;
    	$objUsuarioDao = null;
    
    	return $lista;
    
    }
    
    public function calcularTiempoTranscurrido($date1)
    {
    
        $fecha = new DateTime();
    	$diff = abs(strtotime($fecha->format('Y-m-d H:i:s')) - strtotime($date1));
    
    	$years   = floor($diff / (365*60*60*24));
    	$months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
    	$days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
    	$hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60));
    	$minuts  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60);
    	$seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minuts*60));
    
    	if($years > 0)
    	{
    		if($years == 1)
    		{
    			return "hace " . $years . " Año";
    		}else{
    			return "hace " . $years . " Años";
    		}
    	}
    
    	if($years < 1 && $months > 0)
    	{
    		if($months == 1)
    		{
    			return "hace " . $months . " Mes";
    		}else{
    			return "hace " . $months . " Meses";
    		}
    	}
    
    	if($years < 1 && $months < 1 && $days > 0)
    	{
    		if($days == 1)
    		{
    			return "hace " . $days . " Dia";
    		}else{
    			return "hace " . $days . " Dias";
    		}
    	}
    
    	if($years < 1 && $months < 1 && $days < 1 && $hours > 0)
    	{
    		if($hours == 1)
    		{
    			return "hace " . $hours . " hora";
    		}else{
    			return "hace " . $hours . " horas";
    		}
    	}
    
    	if($years < 1 && $months < 1 && $days < 1 && $hours < 1 && $minuts > 0)
    	{
    		if($minuts == 1)
    		{
    			return "hace " . $minuts . " minuto";
    		}else{
    			return "hace " . $minuts . " minutos";
    		}
    	}
    
    	if($years < 1 && $months < 1 && $days < 1 && $hours < 1 && $minuts < 1 && $seconds > 0)
    	{
    		if($seconds == 1)
    		{
    			return "hace " . $seconds . " segundo";
    		}else{
    			return "hace " . $seconds . " segundos";
    		}
    	}
    }

}