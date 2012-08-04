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
	        	
	        	if($objUsuario->getBloqueado() == 1) //PRIMERO SE VALIDA SI ESTA BLOQUEADO O NO.
	        	{
	        	    $this->view->estadoAcepta = 4;
	        	}else{
	        		$this->view->estadoAcepta = $objUsuario->getAcepta();
	        	}
	        	
	        }
        }else{ //no existe en ciisa
            
            //VALIDACIÓN ACCESO ADMINISTRADOR
            $objUsuario = $objUsuarioDao->validarAdministrador($usuario, $password);
            
            if($objUsuario != null)
            {
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
                $aut->getIdentity()->perfil_ciisa = "admin";
                $aut->getIdentity()->carrera = "";
                $aut->getIdentity()->twitterArregloDatos = null;
                $aut->getIdentity()->facebookNombre = null;
                $aut->getIdentity()->linkedinNombre = null;
                
            	$this->view->estadoAcepta = 3;
            }else{
            	$this->view->estadoAcepta = 2;
            }
        }       
       	
    }
    
    public function validarIntegracionAction()
    {
    	$this->_helper->layout()->disableLayout();
    	$usuario = $this->getRequest()->getParam('txtUsuario');
    	$objUsuarioDao = new Application_Model_UsuarioDao();
    	$objIntegracionDao = new Application_Model_IntegracionDao();

    	$objUsuario = $objUsuarioDao->obtenerPorUsuarioCiisa($usuario);
    	
    	$tieneFacebook = $objIntegracionDao->booleanObtenerLlavesIntegracion($objUsuario->getId(), 1);
    	$tieneTwitter = $objIntegracionDao->booleanObtenerLlavesIntegracion($objUsuario->getId(), 2);
    	$tieneLinkedin = $objIntegracionDao->booleanObtenerLlavesIntegracion($objUsuario->getId(), 3);
    	
    	if ($tieneTwitter == false && $tieneFacebook == false && $tieneLinkedin == false){
    		$htmlLoader = "<img src='/imagenes/proyecto/ajax-loader.gif'><br>";
    	}else{
    	    $htmlLoader = "Sincronizando con tus redes sociales, por favor espera...<br>";
    	}
    	
    	if ($tieneTwitter){
    		$htmlLoader = $htmlLoader . "<img src='/imagenes/proyecto/twitter.png'><img src='/imagenes/proyecto/ajax-loader-bar.gif'><br>";
    	}
    	if ($tieneFacebook){
    		$htmlLoader = $htmlLoader . "<img src='/imagenes/proyecto/facebook.png'><img src='/imagenes/proyecto/ajax-loader-bar.gif'><br>";
    	}
    	if ($tieneLinkedin){
    		$htmlLoader = $htmlLoader . "<img src='/imagenes/proyecto/linkedin.png'><img src='/imagenes/proyecto/ajax-loader-bar.gif'>";
    	}
    	
    	$objIntegracionDao = null;
    	$objUsuario = null;
    	$objUsuarioDao = null;
    	
    	$this->view->valor = $htmlLoader;
    
    }
    
    public function grabarAceptacionAction()
    {
        $this->_helper->layout()->disableLayout();
        $usuario = $this->getRequest()->getParam('txtUsuario');
        $objUsuarioDao = new Application_Model_UsuarioDao();
        
        $objUsuario = $objUsuarioDao->obtenerPorUsuarioCiisa($usuario);
		echo $objUsuario;
		exit();
        
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
        			$objUsuario->setPerfilId(1);
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
        			$objUsuario->setPerfilId(1);
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
        	$aut->getIdentity()->carrera = $objCiisa->obtenerCarrera($usuario);
        	
        	$objIntegracion = new Application_Model_Integracion();
        	$objIntegracionDao = new Application_Model_IntegracionDao();
        	if ($objIntegracionDao->obtenerLlavesIntegracion($objUsuario->getId(),3) != null)
        	{
        		define("API_CONSUMER_KEY", "qolg75ipkngf");
        		define("API_CONSUMER_SECRET", "LNITIz9Vd9hdw7uW");
        		require 'linkedin/helpers.php';
        		$oauth = new OAuth(API_CONSUMER_KEY, API_CONSUMER_SECRET);
        		$objIntegracion = $objIntegracionDao->obtenerLlavesIntegracion($objUsuario->getId(),3);
        		$oauth->setToken($objIntegracion->getToken(),$objIntegracion->getSecret());
        		$api_url = "http://api.linkedin.com/v1/people/~:(first-name,last-name,headline,picture-url,public-profile-url)";
        		$oauth->fetch($api_url, array(), OAUTH_HTTP_METHOD_GET, array('x-li-format' => 'json'));
        		$profile = json_decode($oauth->getLastResponse());
        		
        		$aut->getIdentity()->linkedinNombre = $profile->firstName;
        		$aut->getIdentity()->linkedinApellido = $profile->lastName;
        		$aut->getIdentity()->linkedinEmpleo = $profile->headline;
        		$aut->getIdentity()->linkedinFoto = $profile->pictureUrl;
        		
        		$aut->getIdentity()->linkedinPerfil = $profile->publicProfileUrl;
        		
        		$oauth = null;
        		$objIntegracion = null;
        		$profile = null;
        	}
        	else
        	{
        	    $aut->getIdentity()->linkedinNombre = null;
        	}    
        	
        	if ($objIntegracionDao->obtenerLlavesIntegracion($objUsuario->getId(),1) != null)
        	{
        		require("Facebook/facebook.php");
        		$facebook = new Facebook(array(
        				'appId'  => '124399157694592',
        				'secret' => '8c0658db1a7873a56c3551a2ee13464b',
        		));
        		$objIntegracion = $objIntegracionDao->obtenerLlavesIntegracion($objUsuario->getId(),1);
        		$facebook->setAccessToken($objIntegracion->getToken());
        		$user = $facebook->getUser();
        		$user_profile = $facebook->api('me','GET');
        		$fields = $facebook->api('me', array('fields'=>'picture'));
        		
        		$aut->getIdentity()->facebookNombre = $user_profile['name'];
        		$aut->getIdentity()->facebookFoto = $fields['picture'];
        		$aut->getIdentity()->facebookLink = $user_profile['link'];
        		
        		$facebook = null;
        		$objIntegracion = null;
        		$user_profile = null;
        		$fields = null;
        	}
        	else 
        	{
        	    $aut->getIdentity()->facebookNombre = null;
        	}    
        	
        	if ($objIntegracionDao->obtenerLlavesIntegracion($objUsuario->getId(),2) != null)
        	{
        		require 'Twitter/tmhOAuth.php';
        		require 'Twitter/tmhUtilities.php';
        		$objIntegracion = $objIntegracionDao->obtenerLlavesIntegracion($objUsuario->getId(),2);
        		$tmhOAuth = new tmhOAuth(array(
        				'consumer_key'    => '4m5dr19FvCwe534XDQ92fw',
        				'consumer_secret' => 'dobuyMsMLs8kTzSl5YDoYv9O9UZlEN1MBSGBKst9hE',
        				'user_token'      => $objIntegracion->getToken(),
        				'user_secret'     => $objIntegracion->getSecret(),
        		));
        	
        		$code_datos = $tmhOAuth->request('GET', $tmhOAuth->url('1/account/verify_credentials'));
        	
        		if ($code_datos == 200) {
        			$datos = json_decode($tmhOAuth->response['response']);
        		}
        	
        		$code_tweet = $tmhOAuth->request('GET', $tmhOAuth->url('1/statuses/user_timeline'), array(
        				'include_entities' => '1',
        				'include_rts'      => '1',
        				'screen_name'      => $datos->screen_name,
        				'count'            => 5,
        		));
        		
        		$aut->getIdentity()->twitterArregloDatos = $datos;
        		$aut->getIdentity()->twitterArregloTweet = json_decode($tmhOAuth->response['response'], true);
        	
        	}
        	else
        	{
        	    $aut->getIdentity()->twitterArregloDatos = null;
        	}    
        	//IMPRIMIR UN DATO
        	//$aut = Zend_Auth::getInstance();
        	//echo $aut->getIdentity()->usu_id; exit;
        	 
        	//IMPRIMIR TODA LA SESSION
        	//Zend_Debug::dump($aut->getIdentity());exit();
        	
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

