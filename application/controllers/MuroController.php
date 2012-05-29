<?php

class MuroController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $aut = Zend_Auth::getInstance();
        $perfil = $aut->getIdentity()->perfil_ciisa;
        $objPublicacionDao = new  Application_Model_PublicacionDao();
        
        //se obtiene el listado de publicaciones según el perfil entrante
        $listaPublicaciones = $objPublicacionDao->obtenerMuroPrincipal($perfil);
        
        //plantilla de paginator
        Zend_View_Helper_PaginationControl::setDefaultViewPartial ( 'paginator/items.phtml' );
        
        $paginator = Zend_Paginator::factory( $listaPublicaciones );
        $paginator->setDefaultItemCountPerPage( 5 );
        
        if ($this->_hasParam ( 'page' )) {
        	$paginator->setCurrentPageNumber( $this->_getParam ( 'page' ) );
        }
        
        $this->view->listaPublicaciones = $paginator;
        
    }
    
    public function comentarPublicacionAction()
    {
        $this->_helper->layout()->disableLayout();
        $aut = Zend_Auth::getInstance();
        
        $texto = $this->getRequest()->getParam('texto');
        $idPublicacion = $this->getRequest()->getParam('idPublicacion');

        $objComentario = new Application_Model_Comentario();
        $objComentarioDao = new Application_Model_ComentarioDao();
        $objUsuarioDao = new Application_Model_UsuarioDao();
        
        //se registra el omentario
        $objComentario->setPublicacionId($idPublicacion);
        $objComentario->setTexto($texto);
        $objComentario->setUsuarioId($aut->getIdentity()->usu_id);
        
        $now = new DateTime();
        $objComentario->setFecha($now->format('Y-m-d H:i:s'));     
        
        $idCom = $objComentarioDao->guardar($objComentario);
        
        $listaComentarios = $objComentarioDao->obtenerPorPublicacionId($idPublicacion);
        
        $htmlComentarios = "<div id='divComentariosPublicacion$idPublicacion'>";
        if(count($listaComentarios) > 0){
            foreach ($listaComentarios as $comentario){
                $objUsuario = $objUsuarioDao->obtenerPorId($comentario->getUsuarioId());
                
                $htmlComentarios .= "<table><tr><td valign='top'>";
                
                $htmlComentarios .= "<div style='width: 50px;'><a class='thumbnail'>";
                $htmlComentarios .= "<img src='/imagenes/usuarios/".$objUsuario->getFoto()."'>";
                $htmlComentarios .= "</div>";
                
                $htmlComentarios .= "</td><td valign='top'>";
                
                if($idCom == $comentario->getId())                
                	$htmlComentarios .= "<p class='label label-warning'>".$objUsuario->getNombre()."</p>";
                else
                    $htmlComentarios .= "<p class='label'>".$objUsuario->getNombre()."</p>";
                    
				$htmlComentarios .= "<p>".$comentario->getTexto()."</p>";
                $htmlComentarios .= "<p>".$comentario->getFecha()."</p>";
                
                $htmlComentarios .= "</td></tr></table>";
                
            }
        }
        
        $htmlComentarios .= "comentarios: <span class='badge badge-info'>".count($listaComentarios)."</span>";
        
        $htmlComentarios .= "</div>";

        $objComentario = null;
        $objComentarioDao = null;
        $objUsuarioDao = null;
        
        $this->view->ok = $htmlComentarios;
    }


}




