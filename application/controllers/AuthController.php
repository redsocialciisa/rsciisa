<?php

class AuthController extends Zend_Controller_Action
{

    public function init()
    {
        $aut = Zend_Auth::getInstance();
        if($aut->hasIdentity()){
            $this->_redirect('/muro/index');
        }
    }

    public function indexAction()
    {        
        $objEmocion = new Application_Model_EmocionDao();       
        $this->view->listaEmociones = $objEmocion->obtenerTodos();
    }
    
    public function validarAceptacionAction()
    {
        $this->_helper->layout()->disableLayout();
        $usuario = $this->getRequest()->getParam('txtUsuario');
        $password = $this->getRequest()->getParam('txtPassword');
        
        $objUsuarioDao = new Application_Model_UsuarioDao();
        $objCiisa = new Application_Model_Ciisa();
        
        if($objCiisa->validarAcceso($usuario, $password))
        {        
	        if($objUsuarioDao->obtenerPorUsuarioCiisa($usuario) != null)
	        {
	        	$objUsuario = $objUsuarioDao->obtenerPorUsuarioCiisa($usuario);
	        	
	        	$this->view->estadoAcepta = $objUsuario->getAcepta();
	        }
        }else{ //no existe en ciisa
            $this->view->estadoAcepta = 2;
        }       
       	
    }
    
    public function grabarAceptacionAction()
    {
        $this->_helper->layout()->disableLayout();
        $usuario = $this->getRequest()->getParam('txtUsuario');
        $objUsuarioDao = new Application_Model_UsuarioDao();
        
        $objUsuario = $objUsuarioDao->obtenerPorUsuarioCiisa($usuario);
                
        $objUsuarioDao->guardarAceptacion($objUsuario);        
    }
    
    public function loginSuccessAction()
    {
        $this->_helper->layout()->disableLayout();
        
        $emocion = $this->getRequest()->getParam('hdnEmocion');
        $usuario = $this->getRequest()->getParam('txtUsuario');
        $password = $this->getRequest()->getParam('txtPassword');
        
        $objCiisa = new Application_Model_Ciisa();
        //$objUsuario = new Application_Model_Usuario();
        $objUsuarioDao = new Application_Model_UsuarioDao();
        
        if($objCiisa->validarAcceso($usuario, $password)) //EXISTE
        {
        	if($objCiisa->obtenerPerfil($usuario) == "ALUMNO" || $objCiisa->obtenerPerfil($usuario) == "EX-ALUMNO")//ES ALUMNO o EX-ALUMNO
        	{
        		if($objUsuarioDao->obtenerPorUsuarioCiisa($usuario) != null) //EXISTE EN RSC
        		{
        			$objUsuario = $objUsuarioDao->obtenerPorUsuarioCiisa($usuario);
        		}else{ 
        		    //NO EXISTE EN RSC
        			$objUsuario = $objCiisa->obtenerUsuarioAlumnoCiisa($usuario);
        			//SE CREA AL USUARIO EN LA TABLA DE LA RSC
        			$objUsuario->setFoto("user.png");
        			$objUsuario->setAcepta(1);
        			$objUsuario->setEmocionId(1);
        			$objUsuario->setPerfilId(2);
        			$objUsuario->setPrivacidadPublicacionId(7); //por defecto 7 - 'todos'
        			$objUsuario->setId($objUsuarioDao->guardar($objUsuario));
        		}
        	}else{ //PROFESOR U OTRO
        		if($objUsuarioDao->obtenerPorUsuarioCiisa($usuario) != null) //EXISTE EN RSC
        		{
        			$objUsuario = $objUsuarioDao->obtenerPorUsuarioCiisa($usuario);
        		}else{
        		    //NO EXISTE EN RSC
        			$objUsuario = $objCiisa->obtenerUsuarioProfesorAcademicoCiisa($usuario);
        			//SE CREA AL USUARIO EN LA TABLA DE LA RSC
        			$objUsuario->setFoto("user.png");
        			$objUsuario->setAcepta(1);
        			$objUsuario->setEmocionId(1);
        			$objUsuario->setPerfilId(2);
        			$objUsuario->setPrivacidadPublicacionId(7); //por defecto 7 - 'todos'
        			$objUsuario->setId($objUsuarioDao->guardar($objUsuario));
        		}
        	}
        	
        	//ACTUALIZA ESTADO DE ANIMO
        	$objUsuario->setEmocionId($emocion);
        	$objUsuarioDao->actualizaEstadoAnimo($objUsuario);
        	 
        	//DEFINICION DATA ADAPTER (1)
        	$autAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
        	$autAdapter->setTableName('rsc_usuarios');
        	$autAdapter->setIdentityColumn('usu_id');
        	$autAdapter->setCredentialColumn('usu_ciisa');
        	 
        	//SETEO DE DATA ADAPTER (2)
        	$autAdapter->setIdentity($objUsuario->getId());
        	$autAdapter->setCredential($objUsuario->getUsuarioCiisa());
        	$aut = Zend_Auth::getInstance();
        	$result = $aut->authenticate($autAdapter);
        	 
        	//(3)
        	switch ($result->getCode())
        	{
        		case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
        			throw new Exception($this->_messages[self::NOT_IDENTITY]);
        			break;
        		case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
        			throw new Exception($this->_messages[self::INVALID_CREDENTIAL]);
        			break;
        		case Zend_Auth_Result::SUCCESS:
        			if ($result->isValid()) {
        				$data = $autAdapter->getResultRowObject();
        				$aut->getStorage()->write($data);
        			} else {
        				throw new Exception($this->_messages[self::INVALID_USER]);
        			}
        			break;
        		default:
        			throw new Exception($this->_messages[self::INVALID_LOGIN]);
        			break;
        	}
        	
        	//se agrega un nuevo campo extra a la session del usuario
        	$aut->getIdentity()->perfil_ciisa = $objCiisa->obtenerPerfil($usuario);
        	
        	//IMPRIMIR UN DATO
        	//$aut = Zend_Auth::getInstance();
        	//echo $aut->getIdentity()->usu_id; exit;
        	 
        	//IMPRIMIR TODA LA SESSION
        	//Zend_Debug::dump($aut->getIdentity());exit;
        	
        	//ENVIA VARIABLES A LA VISTA
        	$this->view->ok = "ok";
        	/*
        	$aut = Zend_Auth::getInstance();
        	if($aut->hasIdentity()){
        		$this->_redirect('/index/index');
        	}*/
        }
        
       
    }
    

}

