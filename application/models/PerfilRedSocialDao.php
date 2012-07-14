<?php

class Application_Model_PerfilRedSocialDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_PerfilRedSocialMap();
    }
    
    public function obtenerPorUsuarioId($idUsuario) //PK
    {
    	$objCiisa = new Application_Model_Ciisa();
    	$objPerfilCiisaDao = new Application_Model_PerfilCiisaDao();
    	$objUsuarioDao = new Application_Model_UsuarioDao();
    	
    	$objUsuario = $objUsuarioDao->obtenerPorId($idUsuario);
    	
    	$perfilAluProAca = $objPerfilCiisaDao->obtenerPorPerfilCiisa($objCiisa->obtenerPerfil($objUsuario->getUsuarioCiisa()))->getPerfil();
    
    	$resultado = $this->_table->find($perfilAluProAca);
    
    	$objPerfilRedSocial = null;
    
    	if(count($resultado) > 0){
    
    		$objPerfilRedSocial = new Application_Model_PerfilRedSocial();
    		 
    		$objPerfilRedSocial->setId($resultado->current()->per_red_soc_id);
    		$objPerfilRedSocial->setNombre($resultado->current()->per_red_soc_nombre);
    		 	 	
    	}
    	
    	$objCiisa = null;
    	$objPerfilCiisaDao = null;
    	$objUsuarioDao = null;
    	
    	return $objPerfilRedSocial;
    }

}

