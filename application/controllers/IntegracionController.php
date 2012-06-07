<?php

class IntegracionController extends Zend_Controller_Action
{
    public function indexAction ()
    {
        
    }
        
    public function facebookAction ()
    {    
        Zend_Session::start();
        require 'Facebook/facebook.php';
        
		// Create our Application instance (replace this with your appId and secret).
		$facebook = new Facebook(array(
		  'appId'  => '124399157694592',
		  'secret' => '8c0658db1a7873a56c3551a2ee13464b',
		));
		
		// Get User ID
		$this->view->user = $facebook->getUser();
		
		// We may or may not have this data based on whether the user is logged in.
		//
		// If we have a $user id here, it means we know the user is logged into
		// Facebook, but we don't know if the access token is valid. An access
		// token is invalid if the user logged out of Facebook.
		
		if ($this->view->user) {
			try {
				// Proceed knowing you have a logged in user who's authenticated.
				$user_profile = $facebook->api('/me');
			} catch (FacebookApiException $e) {
				$user = null;
			}
		}
		
		// Login or logout url will be needed depending on current user state.
		if ($this->view->user) {
			$logoutUrl = $facebook->getLogoutUrl();
			$this->view->link = $logoutUrl;
			$this->view->userprofile = $user_profile;
		} else {
			$loginUrl = $facebook->getLoginUrl(array('scope' => 'read_stream,publish_stream'));
			$this->view->link = $loginUrl;
		}
        
    }

    public function twitterAction ()
    {
        Zend_Session::start();
        require 'Twitter/tmhOAuth.php';
        require 'Twitter/tmhUtilities.php';
        
        $now   = new DateTime;
        $tmhOAuth = new tmhOAuth(array(
        		'consumer_key'    => '4m5dr19FvCwe534XDQ92fw',
        		'consumer_secret' => 'dobuyMsMLs8kTzSl5YDoYv9O9UZlEN1MBSGBKst9hE', ));
        
        $here = tmhUtilities::php_self();
        $_REQUEST['authenticate'] = 'authenticate';
        
        
        function outputError($tmhOAuth) {
        	echo 'Error: ' . $tmhOAuth->response['response'] . PHP_EOL;
        	tmhUtilities::pr($tmhOAuth);
        }
        
        // reset request?
        if ( isset($_REQUEST['wipe'])) {
            session_destroy();
        	header("Location: {$here}");
        
        	// already got some credentials stored?
        } elseif ( isset($_SESSION['access_token']) ) {
        	$tmhOAuth->config['user_token']  = $_SESSION['access_token']['oauth_token'];
        	$tmhOAuth->config['user_secret'] = $_SESSION['access_token']['oauth_token_secret'];
        
        	$code = $tmhOAuth->request('GET', $tmhOAuth->url('1/account/verify_credentials'));
        	if ($code == 200) {
        		$resp = json_decode($tmhOAuth->response['response']);        		
        		$objIntegracionDao = new Application_Model_IntegracionDao();
        		$objIntegracion = new Application_Model_Integracion();
        		
        		$objIntegracion->setToken($_SESSION['access_token']['oauth_token']);
        		$objIntegracion->setSecret($_SESSION['access_token']['oauth_token_secret']);
        		$objIntegracion->setFechaPermiso($now->format( 'Y-m-d' ));
        		
        		$aut = Zend_Auth::getInstance();
        		$objIntegracion->setUsuarioId($aut->getIdentity()->usu_id);
        		$objIntegracion->setRedId("2");
        		$objIntegracionDao->guardar($objIntegracion);
        		echo "Integracion con Twitter exitosa"."<BR>";
        		
        		
        	} else {
        		outputError($tmhOAuth);
        	}
        	// we're being called back by Twitter
        } elseif (isset($_REQUEST['oauth_verifier'])) {
        	$tmhOAuth->config['user_token']  = $_SESSION['oauth']['oauth_token'];
        	$tmhOAuth->config['user_secret'] = $_SESSION['oauth']['oauth_token_secret'];
        
        	$code = $tmhOAuth->request('POST', $tmhOAuth->url('oauth/access_token', ''), array(
        			'oauth_verifier' => $_REQUEST['oauth_verifier']
        	));
        
        	if ($code == 200) {
        		$_SESSION['access_token'] = $tmhOAuth->extract_params($tmhOAuth->response['response']);
        		unset($_SESSION['oauth']);
        		header("Location: {$here}");
        	} else {
        		outputError($tmhOAuth);
        	}
        	// start the OAuth dance
        	
        } elseif ( isset($_REQUEST['authenticate']) || isset($_REQUEST['authorize']) ) {
        	$callback = isset($_REQUEST['oob']) ? 'oob' : $here;
        
        	$params = array(
        			'oauth_callback'     => $callback
        	);
        
        	$code = $tmhOAuth->request('POST', $tmhOAuth->url('oauth/request_token', ''), $params);
        
        	if ($code == 200) {
        		$_SESSION['oauth'] = $tmhOAuth->extract_params($tmhOAuth->response['response']);
        		$method = isset($_REQUEST['authenticate']) ? 'authenticate' : 'authorize';
        		$force  = isset($_REQUEST['force']) ? '&force_login=1' : '';
        		$authurl = $tmhOAuth->url("oauth/{$method}", '') .  "?oauth_token={$_SESSION['oauth']['oauth_token']}{$force}";
        		echo '<p>Para completar la integracion ingrese a la siguiente URL: <a href="'. $authurl . '">' . $authurl . '</a></p>';
        	} else {
        		outputError($tmhOAuth);
        	}
        }
        
    }
# --------------------------------------------------------------------------------------------    
    public function linkedinAction ()
    {
        Zend_Session::start();
        // Definimos las llaves secretas
        define('LINKEDIN_KEY', 'qolg75ipkngf');
        define('LINKEDIN_SECRET', 'LNITIz9Vd9hdw7uW');
        
        $now   = new DateTime;
        
        // Funcion que sirve para formatear la url en formato oauth
        function urlencode_oauth($str) {
        	return
        	str_replace('+',' ',str_replace('%7E','~',rawurlencode($str)));
        }
        
        // Links de la API de Linkedin
        $links = array(
        		'request_token'=>'https://api.linkedin.com/uas/oauth/requestToken',
        		'authorize'=>'https://www.linkedin.com/uas/oauth/authorize',
        		'access_token'=>'https://api.linkedin.com/uas/oauth/accessToken'
        );
        
        // Verifica si ya esta autorizada la aplicacion, si no esta procede a solicitarla
        if (empty($_GET['oauth_token']) || empty($_GET['oauth_verifier']) || $_GET['oauth_token']!=$_SESSION['linkedin_oauth_token']) {
        	$params = array(
        			'oauth_callback'=>"http://of.novadvice.com/integracion/linkedin",
        			'oauth_consumer_key'=>LINKEDIN_KEY,
        			'oauth_nonce'=>sha1(microtime()),
        			'oauth_signature_method'=>'HMAC-SHA1',
        			'oauth_timestamp'=>time(),
        			'oauth_version'=>'1.0'
        	);
        
        	// Parte 5 pagina 1
        	ksort($params);
        
        	$q = array();
        	foreach ($params as $key=>$value) {
        		$q[] = urlencode_oauth($key).'='.urlencode_oauth($value);
        	}
        	$q = implode('&',$q);
        
        	$parts = array(
        			'POST',
        			urlencode_oauth($links['request_token']),
        			urlencode_oauth($q)
        	);
        	$base_string = implode('&',$parts);
        
        	// Parte 6 pagina 1
        	$key = urlencode_oauth(LINKEDIN_SECRET) . '&';
        	$signature = base64_encode(hash_hmac('sha1',$base_string,$key,true));
        
        	// Parte 7 pagina 1
        	$params['oauth_signature'] = $signature;
        	$str = array();
        	foreach ($params as $key=>$value) {
        		$str[] = $key . '="'.urlencode_oauth($value).'"';
        	}
        	$str = implode(', ',$str);
        	$headers = array(
        			'POST /uas/oauth/requestToken HTTP/1.1',
        			'Host: api.linkedin.com',
        			'Authorization: OAuth '.$str,
        			'Content-Type: text/xml;charset=UTF-8',
        			'Content-Length: 0',
        			'Connection: close'
        	);
        
        	// Parte 8 pagina 1
        	$fp = fsockopen("ssl://api.linkedin.com",443,$errno,$errstr,30);
        	if (!$fp) {
        		echo 'Unable to connect to LinkedIn'; exit();
        	}
        	$out = implode("\r\n",$headers) . "\r\n\r\n";
        	fputs($fp,$out);
        	$res = '';
        	while (!feof($fp)) $res .= fgets($fp,4096);
        	fclose($fp);
        
        	// Parte 9 pagina 1
        	$parts = explode("\n\n",str_replace("\r",'',$res));
        	$res_headers = explode("\n",$parts[0]);
        	if ($res_headers[0] != 'HTTP/1.1 200 OK') {
        		echo 'Error getting OAuth token and secret.'; exit();
        	}
        	parse_str($parts[1],$data);
        	if (empty($data['oauth_token'])) {
        		echo 'Failed to get LinkedIn request token.'; exit();
        	}
        
        	// Parte 10 pagina 1
        	$_SESSION['linkedin_oauth_token'] = $data['oauth_token'];
        	$linkedin_oauth_token = $data['oauth_token'];
        	$_SESSION['linkedin_oauth_token_secret'] = $data['oauth_token_secret'];
        	$linkedin_oauth_token_secret = $data['oauth_token_secret'];
        
        	// Parte 11 pagina 1
        	header('Location: '.$links['authorize'].
        			'?oauth_token='.urlencode($data['oauth_token']));
        
        }
        
        // Parte 2 pagina 2
        $params = array(
        		'oauth_consumer_key'=>LINKEDIN_KEY,
        		'oauth_nonce'=>sha1(microtime()),
        		'oauth_signature_method'=>'HMAC-SHA1',
        		'oauth_timestamp'=>time(),
        		'oauth_token'=>$_GET['oauth_token'],
        		'oauth_verifier'=>$_GET['oauth_verifier'],
        		'oauth_version'=>'1.0'
        );
        
        // Parte 3 pagina 2
        ksort($params);
        $q = array();
        foreach ($params as $key=>$value) {
        	$q[] = urlencode_oauth($key).'='.urlencode_oauth($value);
        }
        $q = implode('&',$q);
        
        $parts = array(
        		'POST',
        		urlencode_oauth($links['access_token']),
        		urlencode_oauth($q)
        );
        $base_string = implode('&',$parts);
        
        // Parte 4 pagina 2
        $key = urlencode_oauth(LINKEDIN_SECRET) . '&' . urlencode_oauth($_SESSION['linkedin_oauth_token_secret']);
        $signature = base64_encode(hash_hmac('sha1',$base_string,$key,true));
        
        // Parte 5 pagina 2
        $params['oauth_signature'] = $signature;
        $str = array();
        foreach ($params as $key=>$value) {
        	$str[] = $key . '="'.urlencode_oauth($value).'"';
        }
        $str = implode(', ',$str);
        $headers = array(
        		'POST /uas/oauth/accessToken HTTP/1.1',
        		'Host: api.linkedin.com',
        		'Authorization: OAuth '.$str,
        		'Content-Type: text/xml;charset=UTF-8',
        		'Content-Length: 0',
        		'Connection: close'
        );
        
        // Parte 6 pagina 2
        $fp = fsockopen("ssl://api.linkedin.com",443,$errno,$errstr,30);
        if (!$fp) {
        	echo 'Unable to connect to LinkedIn'; exit();
        }
        $out = implode("\r\n",$headers) . "\r\n\r\n";
        fputs($fp,$out);
        
        $res = '';
        while (!feof($fp)) $res .= fgets($fp,4096);
        fclose($fp);
        
        // Parte 7 pagina 2
        $parts = explode("\n\n",str_replace("\r",'',$res));
        $res_headers = explode("\n",$parts[0]);
        if ($res_headers[0] != 'HTTP/1.1 200 OK') {
        	echo 'Error getting access token and secret.'; exit();
        }
        parse_str($parts[1],$data);
        if (empty($data['oauth_token'])) {
        	echo 'Failed to get LinkedIn access token.'; exit();
        }
        
        // Parte 8 pagina 2
        $_SESSION['linkedin_access_token'] = $data['oauth_token'];
        $_SESSION['linkedin_access_token_secret'] = $data['oauth_token_secret'];
        
        $objIntegracionDao = new Application_Model_IntegracionDao();
        $objIntegracion = new Application_Model_Integracion();
        
        $objIntegracion->setToken($data['oauth_token']);
        $objIntegracion->setSecret($data['oauth_token_secret']);
        $objIntegracion->setFechaPermiso($now->format( 'Y-m-d' ));
        
        $aut = Zend_Auth::getInstance();
        $objIntegracion->setUsuarioId($aut->getIdentity()->usu_id);
        $objIntegracion->setRedId("3");
        $objIntegracionDao->guardar($objIntegracion);
        echo "Integracion con Linked In exitosa"."<BR>";
        
        
        unset($_SESSION['linkedin_oauth_token']);
        unset($_SESSION['linkedin_oauth_token_secret']);
       
    }
    
    public function publicarTwitterAction ()
    {
        $this->_helper->layout()->disableLayout();
        require 'Twitter/tmhOAuth.php';
        require 'Twitter/tmhUtilities.php';
        $this->view->title = "Publicar Tweet";
        $form = new Application_Form_Publicar();
        $form->submit->setLabel('Publicar');
        $this->view->form = $form;
        
        if ($this->getRequest()->isPost()) {
        	$formData = $this->getRequest()->getPost();
        	if ($form->isValid($formData)) {
        		$texto = $form->getValue('texto');
        		$objIntegracion = new Application_Model_Integracion();
        		$objIntegracionDao = new Application_Model_IntegracionDao();
        		$aut = Zend_Auth::getInstance();
        		$objIntegracion = $objIntegracionDao->obtenerLlavesIntegracion($aut->getIdentity()->usu_id,2);

        		$tmhOAuth = new tmhOAuth(array(
        				'consumer_key'    => '4m5dr19FvCwe534XDQ92fw',
        				'consumer_secret' => 'dobuyMsMLs8kTzSl5YDoYv9O9UZlEN1MBSGBKst9hE',
        				'user_token'      => $objIntegracion->getToken(),
        				'user_secret'     => $objIntegracion->getSecret(),
        		));
        		
        		$code = $tmhOAuth->request('POST', $tmhOAuth->url('1/statuses/update'), array(
        				'status' => $texto
        		));
        		$this->view->ok = "ok";
        	} else {
        		$form->populate($formData);
        	}
        }
    }
    
    public function publicarLinkedinAction ()
    {
        $this->_helper->layout()->disableLayout();
        $this->view->title = "Publicar Linkedin";
        $form = new Application_Form_Publicar();
        $form->submit->setLabel('Publicar');
        $this->view->form = $form;
        
        if ($this->getRequest()->isPost()) {
        	$formData = $this->getRequest()->getPost();
        	if ($form->isValid($formData)) {
        		$texto = $form->getValue('texto');
        		$objIntegracion = new Application_Model_Integracion();
        		$objIntegracionDao = new Application_Model_IntegracionDao();
        		$aut = Zend_Auth::getInstance();
        		$objIntegracion = $objIntegracionDao->obtenerLlavesIntegracion($aut->getIdentity()->usu_id,3);

        		$xml =
        		'<?xml version="1.0" encoding="UTF-8"?>
        		<share>
        		<comment>'.$texto.'</comment>
        		<content>
        		<title>Publicacion desde Red Social Ciisa</title>
        		<submitted-url>http://redsocial.ipciisa.cl</submitted-url>
        		<submitted-image-url>http://blog.thewebcafes.com/img/example.jpg</submitted-image-url>
        		</content>
        		<visibility>
        		<code>anyone</code>
        		</visibility>
        		</share>';
        		
        		// Funcion que sirve para formatear la url en formato oauth
        		function urlencode_oauth($str) {
        			return
        			str_replace('+',' ',str_replace('%7E','~',rawurlencode($str)));
        		}
        		
        		$params = array(
        				'oauth_consumer_key'=>'qolg75ipkngf',
        				'oauth_nonce'=>sha1(microtime()),
        				'oauth_signature_method'=>'HMAC-SHA1',
        				'oauth_timestamp'=>time(),
        				'oauth_token'=>$objIntegracion->getToken(),
        				'oauth_version'=>'1.0'
        		);
        		
        		// sort parameters according to ascending order of key
        		ksort($params);
        		
        		// prepare URL-encoded query string
        		$q = array();
        		foreach ($params as $key=>$value) {
        			$q[] = urlencode_oauth($key).'='.urlencode_oauth($value);
        		}
        		$q = implode('&',$q);
        		
        		// generate the base string for signature
        		$parts = array(
        				'POST',
        				urlencode_oauth('https://api.linkedin.com/v1/people/~/shares'),
        				urlencode_oauth($q)
        		);
        		$base_string = implode('&',$parts);
        		
        		$key = urlencode_oauth('LNITIz9Vd9hdw7uW') . '&' . urlencode_oauth($objIntegracion->getSecret());
        		$signature = base64_encode(hash_hmac('sha1',$base_string,$key,true));
        		
        		
        		$params['oauth_signature'] = $signature;
        		$str = array();
        		foreach ($params as $key=>$value) {
        			$str[] = $key . '="'.urlencode_oauth($value).'"';
        		}
        		$str = implode(', ',$str);
        		$headers = array(
        				'POST /v1/people/~/shares HTTP/1.1',
        				'Host: api.linkedin.com',
        				'Authorization: OAuth '.$str,
        				'Content-Type: text/xml;charset=UTF-8',
        				'Content-Length: '.strlen($xml),
        				'Connection: close'
        		);
        		
        		$fp = fsockopen("ssl://api.linkedin.com",443,$errno,$errstr,30);
        		if (!$fp) {
        			echo 'Unable to connect to LinkedIn'; exit();
        		}
        		$out = implode("\r\n",$headers)."\r\n\r\n".$xml . "\r\n\r\n";
        		fputs($fp,$out);
        		
        		// getting LinkedIn server response
        		$res = '';
        		while (!feof($fp)) $res .= fgets($fp,4096);
        		fclose($fp);
        		
        		$parts = explode("\n\n",str_replace("\r","",$res));
        		$headers = explode("\n",$parts[0]);
        		if ($headers[0] != 'HTTP/1.1 201 Created') {
        			echo 'Failed';
        		}        		
        		$this->view->ok = "ok";
        	} 
        }
    }
    
    public function publicarFacebookAction ()
    {
        $this->_helper->layout()->disableLayout();
        require 'Facebook/facebook.php';
        $this->view->title = "Publicar Facebook";
        $form = new Application_Form_Publicar();
        $form->submit->setLabel('Publicar');
        $this->view->form = $form;
        
        if ($this->getRequest()->isPost()) {
        	$formData = $this->getRequest()->getPost();
        	if ($form->isValid($formData)) {
        		$texto = $form->getValue('texto');
        		$objIntegracion = new Application_Model_Integracion();
        		$objIntegracionDao = new Application_Model_IntegracionDao();
        		
        		$aut = Zend_Auth::getInstance();
        		$objIntegracion = $objIntegracionDao->obtenerLlavesIntegracion($aut->getIdentity()->usu_id,1);
        		
        		$scope = 'publish_stream';
        		// Create our Application instance (replace this with your appId and secret).
        		$facebook = new Facebook(array(
        				'appId'  => '124399157694592',
        				'secret' => '8c0658db1a7873a56c3551a2ee13464b',
        				'scope' => $scope
        		));
        		
        		$facebook->setAccessToken($objIntegracion->getToken());
        		$user = $facebook->getUser();
        		
        		if ($user) {
        			try {
        				// Proceed knowing you have a logged in user who's authenticated.
        				$user_profile = $facebook->api('/me');
        			} catch (FacebookApiException $e) {
        				error_log($e);
        				$user = null;
        			}
        		}
        		
        		if ($user) {
        			$logoutUrl = $facebook->getLogoutUrl();
        		} else {
        			$loginUrl = $facebook->getLoginUrl();
        		}
        		
        		        		
        		$facebook->api('/me/feed','post', array(
        				'name'=>'campo nombre',
        				'message'=>$texto,
        				'picture'=>'https://www.nakasha-spain.com/shop/images/Hellacopters-In-The-Sign-Of-Th-450786.jpg',
        				'caption'=>'titulo',
        				'description'=>'descripcion',
        		));
        		$this->view->ok = "ok";
        	} else {
        		$form->populate($formData);
        	}
        }
    }
    
    public function quitarFacebookAction()
    {
        $aut = Zend_Auth::getInstance();
        $objIntegracionDao = new Application_Model_IntegracionDao();
        $objFacebook = $objIntegracionDao->obtenerLlavesIntegracion($aut->getIdentity()->usu_id, 1);

        $objIntegracionDao->eliminar($objFacebook->getId());
        $objIntegracionDao = null;
        
        $this->_redirect('/perfil/index');
        
    }	
    
    public function quitarTwitterAction()
    {
        $aut = Zend_Auth::getInstance();
        $objIntegracionDao = new Application_Model_IntegracionDao();
        $objTwitter = $objIntegracionDao->obtenerLlavesIntegracion($aut->getIdentity()->usu_id, 2);
        
        $objIntegracionDao->eliminar($objTwitter->getId());
        $objIntegracionDao = null;
        
        $this->_redirect('/perfil/index');
    }
    
    public function quitarLinkedinAction()
    {
        $aut = Zend_Auth::getInstance();
        $objIntegracionDao = new Application_Model_IntegracionDao();
        $objLinkedin = $objIntegracionDao->obtenerLlavesIntegracion($aut->getIdentity()->usu_id, 3);
        
        $objIntegracionDao->eliminar($objLinkedin->getId());
        $objIntegracionDao = null;
        
        $this->_redirect('/perfil/index');
    }

}
