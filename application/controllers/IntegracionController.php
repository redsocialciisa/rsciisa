<?php

#require_once 'Zend/Controller/Action.php';

class IntegracionController extends Zend_Controller_Action
{
    public function indexAction ()
    {
        echo "Integraciones";
        exit();
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
        
        	if (isset($_REQUEST['force_write'])) :
        	$params['x_auth_access_type'] = 'write';
        	elseif (isset($_REQUEST['force_read'])) :
        	$params['x_auth_access_type'] = 'read';
        	endif;
        
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
    	 
    }

}
