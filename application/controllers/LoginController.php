<?php

class LoginController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        
    }
    
    public function accesoAction()
    {
        //DataAdapter Para la Session
        $autAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
        $autAdapter->setTableName('rsc_usuarios');
        $autAdapter->setIdentityColumn('usu_id');
        $autAdapter->setCredentialColumn('usu_ciisa');
        
        //creación de objetos
        $objUsuarioDao = new Application_Model_UsuarioDao();
        //$objUsuario = new Application_Model_Usuario();
        $objCiisa = new Application_Model_Ciisa();       
        
        //recuperan los datos del formulario de login
        $usuario = $this->getRequest()->getParam('txtUsuario');
        $password = $this->getRequest()->getParam('txtPassword');
        
        if ($objUsuarioDao->validarAdministrador($usuario, $password) != null)
        {
            //es administrador, se debe enviar a otra URL                      
        }else{
            //es usuario            
            if($objCiisa->validarAcceso($usuario, $password)){

                if(strlen($usuario) > 4)
                {
                    //es alumno y no existe
                    if($objUsuarioDao->obtenerPorUsuarioCiisa($usuario) == null)
                    {
		                $objUsuario = $objCiisa->obtenerUsuarioAlumnoCiisa($usuario);
		                $objUsuario->setEmocionId(1);
		                $objUsuario->setPerfilId(1);
		                $objUsuario->setAcepta(1);
		                $objUsuarioDao->guardar($objUsuario);		                
		                
		                $objUsuario = $objUsuarioDao->obtenerPorUsuarioCiisa($usuario);
		                //se crea la session
		                $autAdapter->setIdentity($objUsuario->getId());
		                $autAdapter->setCredential($objUsuario->getUsuarioCiisa());
		                $aut = Zend_Auth::getInstance();
		                $aut->authenticate($autAdapter);
		                
		                //se utiliza para imprimir lo que tiene la session
		               // Zend_Debug::dump($aut->getIdentity());exit;	
		                	                
                    }else{
                        $objUsuario = $objUsuarioDao->obtenerPorUsuarioCiisa($usuario);
                                                
                        //se crea la session
                        $autAdapter->setIdentity($objUsuario->getId());
                        $autAdapter->setCredential($objUsuario->getUsuarioCiisa());                                                	
                        $aut = Zend_Auth::getInstance();                        
                        $result = $aut->authenticate($autAdapter);
                        	
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
                        
                        //imprimir
                        $aut = Zend_Auth::getInstance();
                        //echo $aut->getIdentity()->usu_correo; exit;
                        
                        
                        //se utiliza para imprimir lo que tiene la session
                        //Zend_Debug::dump($aut->getIdentity());exit;
                    }
                }else{
                    //es profesor y no existe
                    if($objUsuarioDao->obtenerPorUsuarioCiisa($usuario) == null)
                    {
	                    $objUsuario = $objCiisa->obtenerUsuarioProfesorAcademicoCiisa($usuario);
	                    $objUsuario->setEmocionId(1);
	                    $objUsuario->setPerfilId(1);
	                    $objUsuario->setAcepta(1);
	                    $objUsuarioDao->guardar($objUsuario);
	                    
	                    $objUsuario = $objUsuarioDao->obtenerPorUsuarioCiisa($usuario);
	                    //se crea la session
	                    $autAdapter->setIdentity($objUsuario->getId());
	                    $autAdapter->setCredential($objUsuario->getUsuarioCiisa());
	                    $aut = Zend_Auth::getInstance();
	                    $aut->authenticate($autAdapter);
	                    
	                    //se utiliza para imprimir lo que tiene la session
	                   // Zend_Debug::dump($aut->getIdentity());exit;
	                    
                    }else{
                        $objUsuario = $objUsuarioDao->obtenerPorUsuarioCiisa($usuario);
                        
                        //se crea la session
                        $autAdapter->setIdentity($objUsuario->getId());
                        $autAdapter->setCredential($objUsuario->getUsuarioCiisa());
                        $aut = Zend_Auth::getInstance();
                        $aut->authenticate($autAdapter);
                        
                        //se utiliza para imprimir lo que tiene la session
                      //  Zend_Debug::dump($aut->getIdentity());exit;
                    }                    
                }
                
            }
            
        }
        
    }
    
    public function validarAceptacionCondicionesAction()
    {     
		$this->_helper->layout()->disableLayout();		
		$objUsuarioDao = new Application_Model_UsuarioDao();
		
        $usuario = $this->getRequest()->getParam('txtUsuario');
               
    	if($objUsuarioDao->obtenerPorUsuarioCiisa($usuario) != null)
    	{
    		$objUsuario = $objUsuarioDao->obtenerPorUsuarioCiisa($usuario);
    		echo "hola";
    	}        
        
        //$this->view->saludo = "123123123";
    }    

}

