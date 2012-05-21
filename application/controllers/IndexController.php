<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        
		$objCiisa = new Application_Model_Ciisa();
		
		$objCiisa2 = new Application_Model_Ciisa();		
		
		if ($objCiisa->validarAcceso("05805792-4","792")){
		    
		    //obtener datos del usuario
		    //crear obj usuario
		    //guardar en session el obj usuario
		    //redireccionamos
		    
		    $objUsuarioDao  = new Application_Model_AlbumDao();
		    
		    $usuario = $objUsuarioDao->obtenerPorId("6");
		    
		    /*echo $usuario->getId();
		    echo $usuario->getNombre();*/
			
		    $this->view->usuario = $usuario;
		    
		    //$_SESSION["usuario"] = $objCiisa;
		}else{
		    echo "no ok";
		}		
		
		
    }


}

