<?php

#require_once 'Zend/Controller/Action.php';

class IntegracionController extends Zend_Controller_Action
{
    public function indexAction ()
    {
        
    }
    
    public function facebookAction ()
    {
    	
    }
    
    public function twitterAction ()
    {

        session_start();
        require 'Twitter/tmhOAuth.php';
        require 'Twitter/tmhUtilities.php';
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
        		echo $resp->screen_name."<BR>";
        		echo  "user_token: ".$_SESSION['access_token']['oauth_token']."<BR>";
        		echo  "user_secret: ".$_SESSION['access_token']['oauth_token_secret']."<BR>";
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
    
    public function linkedinAction ()
    {
        session_start();
        // Definimos las llaves secretas
        define('LINKEDIN_KEY', 'qolg75ipkngf');
        define('LINKEDIN_SECRET', 'LNITIz9Vd9hdw7uW');
        
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
        
        echo "Linkedin Key: qolg75ipkngf <BR>";
        echo "Linkedin Secret: LNITIz9Vd9hdw7uW <BR>";
        echo "oauth_token (antes de autentificar): ".$_GET['oauth_token']."<BR>";
        echo "oauth_verifier (antes de autentificar): ".$_GET['oauth_verifier']."<BR>";
        
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
        
        echo "oauth_token (finales): ".$data['oauth_token']."<BR>";
        echo "oauth_token_secret (finales): ".$data['oauth_token_secret']."<BR>";
        
        unset($_SESSION['linkedin_oauth_token']);
        unset($_SESSION['linkedin_oauth_token_secret']);
       
    }

}
