<?php

class AdminController extends Zend_Controller_Action
{

    public function init()
    {
        $aut = Zend_Auth::getInstance();
		if($aut->hasIdentity() == false){
			$this->_redirect('/auth');
		}
    }

    public function indexAction()
    {
        $aut = Zend_Auth::getInstance();
        if($aut->hasIdentity()){
            
            if($aut->getIdentity()->per_id == 1) // es alumno no tiene permisos para estar acá
            {
                $this->_redirect('/muro');
            }
            
        }else{
        	$this->_redirect('/auth');
        }
        
        $usuario = $this->getRequest()->getParam('txtUsuario');
        $estado = $this->getRequest()->getParam('rbtBloquearActivar');
        
        if($usuario != null){
        
	        $objUsuarioDao = new Application_Model_UsuarioDao();
	        $objUsuario = $objUsuarioDao->obtenerPorUsuarioCiisa($usuario);
	        
	        if($objUsuario != null)
	        {
	            $objUsuario->setBloqueado($estado);
	            
	            $objUsuarioDao->bloquearDesbloquear($objUsuario);
	            
		        if($estado == 1)
		        {
		            $this->view->estado = "<div class='alert alert-success'><button class='close' data-dismiss='alert'>×</button><strong>Éxito!</strong> El Usuario ha sido bloqueado satisfactoriamente !!</div>";
		            $this->view->usuario = $objUsuario;
		        }
		        if($estado == 0)
		        {        
		            $this->view->estado = "<div class='alert alert-success'><button class='close' data-dismiss='alert'>×</button><strong>Éxito!</strong> El Usuario ha sido activado satisfactoriamente !!</div>";
		            $this->view->usuario = $objUsuario;
		        }
		        
		        $objUsuario = null;
		        $objUsuarioDao = null;
		        
	        }else{
           		 $this->view->estado = "<div class='alert alert-error'><button class='close' data-dismiss='alert'>×</button><strong>Error!</strong> El Usuario ingresado no existe !!</div>";
       		}
	        
        }
       
    }

}


