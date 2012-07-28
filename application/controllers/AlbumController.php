<?php

class AlbumController extends Zend_Controller_Action
{

    public function init()
    {
        $aut = Zend_Auth::getInstance();
        if($aut->hasIdentity() == false){
        	$this->_redirect('/auth');
        }
        //
        //
    }

    public function indexAction()
    {
        $aut = Zend_Auth::getInstance();
        
        //SEGURIDAD
		$objAmigoDao = new Application_Model_AmigoDao();
		if($this->getRequest()->getParam('id') != null)
		{
			if($aut->getIdentity()->usu_id != $this->getRequest()->getParam('id'))
			{
				if($objAmigoDao->sonAmigos($aut->getIdentity()->usu_id, $this->getRequest()->getParam('id')) == false)
				{
				    echo "<img src='/imagenes/proyecto/denegado.png'>&nbsp;&nbsp;&nbsp;TÚ NO TIENES ACCESO A ESTA INFORMACIÓN.";
				    exit();
				}
			}
		} 
		//SEGURIDAD
        $form = new Application_Form_FormAlbum();
        $objAlbumDao = new Application_Model_AlbumDao();
        
		$id = $this->getRequest()->getParam('id');
		if($id == null || trim($id) == "")
		{
		    $id = $aut->getIdentity()->usu_id;
		}
        
        if($this->getRequest()->isPost())
        {
        	$formData = $this->_request->getPost();
        	if($form->isValid($this->_getAllParams()))
        	{
        	    $objAlbumDao = new Application_Model_AlbumDao();
        	    $objAlbum = new Application_Model_Album();
        	    $objFoto = new Application_Model_Foto();
        	    $objFotoDao = new Application_Model_FotoDao();
        	    $objUtilidad = new Application_Model_Utilidad();
        	    
        	    $fecha = new DateTime();
        	    $fechahora = str_replace(" ","",str_replace("-","",str_replace(":","",$fecha->format('Y-m-d H:i:s'))));

        	    $objAlbum->setNombre($this->getRequest()->getParam('txtNombre'));
        	    $objAlbum->setDescripcion($this->getRequest()->getParam('txtDescripcion'));
        	    $objAlbum->setFechaCreacion($fechahora);
        	    $objAlbum->setUsuario($aut->getIdentity()->usu_id);
        	    
        	    if(isset($_FILES['fileFoto1']['name']) && $_FILES['fileFoto1']['name'] != "")
        	    {
        	        $foto_name1  = $_FILES['fileFoto1']['name'];
        	        $foto_tmp1	= $_FILES['fileFoto1']['tmp_name'];
        	        $foto_ext1   = str_replace("image/","",$_FILES['fileFoto1']['type']);
        	        
        	        $location_perfil = "/var/www/rsciisa/public/imagenes/fotos/".$fechahora."_".$foto_name1;
        	        $location_ico = "/var/www/rsciisa/public/imagenes/fotos/icono/".$fechahora."_".$foto_name1;
        	        
        	        copy($foto_tmp1, $location_perfil);
        	        
        	        $tmp_perfil = $objUtilidad->recortarImagen($location_perfil,$foto_ext1,800,600);
        	        $tmp_ico = $objUtilidad->recortarImagen($location_perfil,$foto_ext1,240,160);
        	        
        	        if($foto_ext1 == "png")
        	        {
        	        	imagepng($tmp_perfil,$location_perfil,0);
        	        	imagepng($tmp_ico,$location_ico,0);
        	        }else{
        	        	//jpeg, jpg
        	        	imagejpeg($tmp_perfil,$location_perfil,95);
        	        	imagejpeg($tmp_ico,$location_ico,95);
        	        }
        	        
        	        //GRABA EL ALBUM Y SE OBTIENE SU ID
        	        $objAlbum->setPortada($fechahora."_".$foto_name1);
        	        $idAlbum = $objAlbumDao->guardar($objAlbum);
        	        
        	        //registra la foto
        	        $objFoto->setFecha($fechahora);
        	        $objFoto->setFoto($fechahora."_".$foto_name1);
        	        $objFoto->setAlbumId($idAlbum);
        	        $objFoto->setNombre($this->getRequest()->getParam('fotoDesc1'));
        	        $objFotoDao->guardar($objFoto);
        	        
        	    }
        	    
        		$this->view->error = "ok";
        
        	}else{
        		$form->populate($formData);
        		$this->view->error = "error";
        	}
        }
        
        $this->view->usuario = $id;
        $this->view->listaAlbumes = $objAlbumDao->obtenerTodosPorUsuarioId($id);
        $this->view->form = $form;
        
    }
    
    public function contenidoAction()
    {
        
        $aut = Zend_Auth::getInstance();
        $id = $this->getRequest()->getParam('id');
        $objFotoDao = new Application_Model_FotoDao();
        $listaFotos = $objFotoDao->obtenerPorAlbumId($id);
        
        $form = new Application_Form_FormFotos();
        $objAlbumDao = new Application_Model_AlbumDao();
        $objFoto = new Application_Model_Foto();
        $objFotoDao = new Application_Model_FotoDao();
        $objUtilidad = new Application_Model_Utilidad();
        
        //SEGURIDAD
        $objAmigoDao = new Application_Model_AmigoDao();
        $usuarioAlbumId = $objAlbumDao->obtenerPorId($id)->getUsuario();
        if($this->getRequest()->getParam('id') != null){
	        if($aut->getIdentity()->usu_id != $usuarioAlbumId)
	        {
		        if($objAmigoDao->sonAmigos($aut->getIdentity()->usu_id, $usuarioAlbumId) == false)
		        {
		        	echo "<img src='/imagenes/proyecto/denegado.png'>&nbsp;&nbsp;&nbsp;TÚ NO TIENES ACCESO A ESTA INFORMACIÓN.";
		        	exit();
		        }
	        } 
        }
        //SEGURIDAD
        
        if($this->getRequest()->isPost())
        {
        	$formData = $this->_request->getPost();
        	if($form->isValid($this->_getAllParams()))
        	{
        	    $fecha = new DateTime();
        	    $fechahora = str_replace(" ","",str_replace("-","",str_replace(":","",$fecha->format('Y-m-d H:i:s'))));
        	    
        	    if(isset($_FILES['fileFoto1']['name']) && $_FILES['fileFoto1']['name'] != "")
        	    {
        	    	$foto_name1  = "1".$_FILES['fileFoto1']['name'];
        	    	$foto_tmp1	= $_FILES['fileFoto1']['tmp_name'];
        	    	$foto_ext1   = str_replace("image/","",$_FILES['fileFoto1']['type']);
        	    	
        	    	$location_perfil = "/var/www/rsciisa/public/imagenes/fotos/".$fechahora."_".$foto_name1;
        	    	$location_ico = "/var/www/rsciisa/public/imagenes/fotos/icono/".$fechahora."_".$foto_name1;
        	    	
        	    	copy($foto_tmp1, $location_perfil);
        	    	
        	    	$tmp_perfil = $objUtilidad->recortarImagen($location_perfil,$foto_ext1,800,600);
        	    	$tmp_ico = $objUtilidad->recortarImagen($location_perfil,$foto_ext1,200,120);
        	    	
        	    	if($foto_ext1 == "png")
        	    	{
        	    		imagepng($tmp_perfil,$location_perfil,0);
        	    		imagepng($tmp_ico,$location_ico,0);
        	    	}else{
        	    		//jpeg, jpg
        	    		imagejpeg($tmp_perfil,$location_perfil,95);
        	    		imagejpeg($tmp_ico,$location_ico,95);
        	    	}
        	    	
        	    	//registra la foto
        	    	$objFoto->setFecha($fechahora);
        	    	$objFoto->setFoto($fechahora."_".$foto_name1);
        	    	$objFoto->setAlbumId($id);
        	    	$objFoto->setNombre($this->getRequest()->getParam('fotoDesc1'));
        	    	$objFotoDao->guardar($objFoto);
        	    		
        	    }
        	    
        	    
        	    if(isset($_FILES['fileFoto2']['name']) && $_FILES['fileFoto2']['name'] != "")
        	    {
        	    	$foto_name2  = "2".$_FILES['fileFoto2']['name'];
        	    	$foto_tmp2	= $_FILES['fileFoto2']['tmp_name'];
        	    	$foto_ext2   = str_replace("image/","",$_FILES['fileFoto2']['type']);
        	    
        	    	$location_perfil = "/var/www/rsciisa/public/imagenes/fotos/".$fechahora."_".$foto_name2;
        	    	$location_ico = "/var/www/rsciisa/public/imagenes/fotos/icono/".$fechahora."_".$foto_name2;
        	    
        	    	copy($foto_tmp2, $location_perfil);
        	    
        	    	$tmp_perfil = $objUtilidad->recortarImagen($location_perfil,$foto_ext2,800,600);
        	    	$tmp_ico = $objUtilidad->recortarImagen($location_perfil,$foto_ext2,200,120);
        	    
        	    	if($foto_ext2 == "png")
        	    	{
        	    		imagepng($tmp_perfil,$location_perfil,0);
        	    		imagepng($tmp_ico,$location_ico,0);
        	    	}else{
        	    		//jpeg, jpg
        	    		imagejpeg($tmp_perfil,$location_perfil,95);
        	    		imagejpeg($tmp_ico,$location_ico,95);
        	    	}
        	    
        	    	//registra la foto
        	    	$objFoto->setFecha($fechahora);
        	    	$objFoto->setFoto($fechahora."_".$foto_name2);
        	    	$objFoto->setAlbumId($id);
        	    	$objFoto->setNombre($this->getRequest()->getParam('fotoDesc2'));
        	    	$objFotoDao->guardar($objFoto);
        	    	 
        	    }
        	    
        	    if(isset($_FILES['fileFoto3']['name']) && $_FILES['fileFoto3']['name'] != "")
        	    {
        	    	$foto_name3  = "3".$_FILES['fileFoto3']['name'];
        	    	$foto_tmp3	= $_FILES['fileFoto3']['tmp_name'];
        	    	$foto_ext3   = str_replace("image/","",$_FILES['fileFoto3']['type']);
        	    	 
        	    	$location_perfil = "/var/www/rsciisa/public/imagenes/fotos/".$fechahora."_".$foto_name3;
        	    	$location_ico = "/var/www/rsciisa/public/imagenes/fotos/icono/".$fechahora."_".$foto_name3;
        	    	 
        	    	copy($foto_tmp3, $location_perfil);
        	    	 
        	    	$tmp_perfil = $objUtilidad->recortarImagen($location_perfil,$foto_ext2,800,600);
        	    	$tmp_ico = $objUtilidad->recortarImagen($location_perfil,$foto_ext2,200,120);
        	    	 
        	    	if($foto_ext3 == "png")
        	    	{
        	    		imagepng($tmp_perfil,$location_perfil,0);
        	    		imagepng($tmp_ico,$location_ico,0);
        	    	}else{
        	    		//jpeg, jpg
        	    		imagejpeg($tmp_perfil,$location_perfil,95);
        	    		imagejpeg($tmp_ico,$location_ico,95);
        	    	}
        	    	 
        	    	//registra la foto
        	    	$objFoto->setFecha($fechahora);
        	    	$objFoto->setFoto($fechahora."_".$foto_name3);
        	    	$objFoto->setAlbumId($id);
        	    	$objFoto->setNombre($this->getRequest()->getParam('fotoDesc3'));
        	    	$objFotoDao->guardar($objFoto);
        	    	 
        	    }
        	    
        	    if(isset($_FILES['fileFoto4']['name']) && $_FILES['fileFoto4']['name'] != "")
        	    {
        	    	$foto_name4  = $_FILES['fileFoto4']['name'];
        	    	$foto_tmp4	= $_FILES['fileFoto4']['tmp_name'];
        	    	$foto_ext4   = str_replace("image/","",$_FILES['fileFoto4']['type']);
        	    	 
        	    	$location_perfil = "/var/www/rsciisa/public/imagenes/fotos/".$fechahora."_".$foto_name4;
        	    	$location_ico = "/var/www/rsciisa/public/imagenes/fotos/icono/".$fechahora."_".$foto_name4;
        	    	 
        	    	copy($foto_tmp4, $location_perfil);
        	    	 
        	    	$tmp_perfil = $objUtilidad->recortarImagen($location_perfil,$foto_ext4,800,600);
        	    	$tmp_ico = $objUtilidad->recortarImagen($location_perfil,$foto_ext4,200,120);
        	    	 
        	    	if($foto_ext4 == "png")
        	    	{
        	    		imagepng($tmp_perfil,$location_perfil,0);
        	    		imagepng($tmp_ico,$location_ico,0);
        	    	}else{
        	    		//jpeg, jpg
        	    		imagejpeg($tmp_perfil,$location_perfil,95);
        	    		imagejpeg($tmp_ico,$location_ico,95);
        	    	}
        	    	 
        	    	//registra la foto
        	    	$objFoto->setFecha($fechahora);
        	    	$objFoto->setFoto($fechahora."_".$foto_name4);
        	    	$objFoto->setAlbumId($id);
        	    	$objFoto->setNombre($this->getRequest()->getParam('fotoDesc4'));
        	    	$objFotoDao->guardar($objFoto);
        	    	 
        	    }
        	    
        	    if(isset($_FILES['fileFoto5']['name']) && $_FILES['fileFoto5']['name'] != "")
        	    {
        	    	$foto_name5  = $_FILES['fileFoto5']['name'];
        	    	$foto_tmp5	= $_FILES['fileFoto5']['tmp_name'];
        	    	$foto_ext5   = str_replace("image/","",$_FILES['fileFoto5']['type']);
        	    	 
        	    	$location_perfil = "/var/www/rsciisa/public/imagenes/fotos/".$fechahora."_".$foto_name5;
        	    	$location_ico = "/var/www/rsciisa/public/imagenes/fotos/icono/".$fechahora."_".$foto_name5;
        	    	 
        	    	copy($foto_tmp5, $location_perfil);
        	    	 
        	    	$tmp_perfil = $objUtilidad->recortarImagen($location_perfil,$foto_ext5,800,600);
        	    	$tmp_ico = $objUtilidad->recortarImagen($location_perfil,$foto_ext5,200,120);
        	    	 
        	    	if($foto_ext5 == "png")
        	    	{
        	    		imagepng($tmp_perfil,$location_perfil,0);
        	    		imagepng($tmp_ico,$location_ico,0);
        	    	}else{
        	    		//jpeg, jpg
        	    		imagejpeg($tmp_perfil,$location_perfil,95);
        	    		imagejpeg($tmp_ico,$location_ico,95);
        	    	}
        	    	 
        	    	//registra la foto
        	    	$objFoto->setFecha($fechahora);
        	    	$objFoto->setFoto($fechahora."_".$foto_name5);
        	    	$objFoto->setAlbumId($id);
        	    	$objFoto->setNombre($this->getRequest()->getParam('fotoDesc5'));
        	    	$objFotoDao->guardar($objFoto);
        	    	 
        	    }
        	    
        		$this->view->error = "ok";
        		$this->_redirect('/album/contenido/id/'.$id.'/success/ok');
        
        	}else{
        		$form->populate($formData);
        		$this->view->error = "error";
        	}
        }
        
        $this->view->album = $objAlbumDao->obtenerPorId($id);
        $this->view->success = $this->getRequest()->getParam('success');
        $this->view->form = $form;
        $this->view->listaFotos = $listaFotos;
        $this->view->idAlbum = $id;
        
    }
    
    public function eliminarFotoAction()
    {
        $this->_helper->layout()->disableLayout();
        $idFoto = $this->getRequest()->getParam('idFoto');
        
        $objFotoDao = new Application_Model_FotoDao();
        $objFoto = $objFotoDao->obtenerPorId($idFoto);
        
        unlink("/var/www/rsciisa/public/imagenes/fotos/icono/".$objFoto->getFoto());
        unlink("/var/www/rsciisa/public/imagenes/fotos/".$objFoto->getFoto());
        
        $objFotoDao->eliminar($idFoto);
        
        $this->view->ok = "ok";
    }
    
    public function eliminarAlbumAction()
    {
    	$this->_helper->layout()->disableLayout();
    	$idAlbum = $this->getRequest()->getParam('idAlbum');
    
    	$objAlbumDao = new Application_Model_AlbumDao();
    	$objFotoDao = new Application_Model_FotoDao();
    	$listaFotos = $objFotoDao->obtenerPorAlbumId($idAlbum);
    	$objComentarioFotoDao = new Application_Model_ComentarioFotoDao();
    	
    	foreach ($listaFotos as $foto)
    	{
    	    unlink("/var/www/rsciisa/public/imagenes/fotos/icono/".$foto->getFoto());
    	    unlink("/var/www/rsciisa/public/imagenes/fotos/".$foto->getFoto());
    	    $objComentarioFotoDao->eliminarComentariosPorFotoId($foto->getId());
    	}
    	
    	$objFotoDao->eliminarFotosPorAlbumId($idAlbum);
    	$objAlbumDao->eliminar($idAlbum);

    	$this->view->ok = "ok";
    }
    
    public function obtenerComentariosFotoAction()
    {
    	$this->_helper->layout()->disableLayout();
    	$idFoto = $this->getRequest()->getParam('id');
    	
    	$objUsuarioDao = new Application_Model_UsuarioDao();
    	$objComentariosFotos = new Application_Model_ComentarioFotoDao();
    	$listaComentarios = $objComentariosFotos->obtenerPorFotoIdDesc($idFoto);
    	
    	$htmlComentarios = "";
    	if(count($listaComentarios) > 0){
    		foreach ($listaComentarios as $comentario){
    			$objUsuarioDao = new Application_Model_UsuarioDao();
    			$objUtilidad = new Application_Model_PublicacionDao();
    			$objUsuario = $objUsuarioDao->obtenerPorId($comentario->getUsuId());
    			
    				$htmlComentarios .= "<div id='divContenedorComentarios".$idFoto."'><hr>";
	    			$htmlComentarios .= "<table width='100%;'>";
					$htmlComentarios .= "<tr><td colspan='2' class='label' width='100%'>".$objUsuario->getNombre()."<br><br><img style='width: 50px;' src='/imagenes/usuarios/icono/".$objUsuario->getFoto()."'>".$comentario->getTexto()."</td></tr>";
    				$htmlComentarios .= "<tr>";
					$htmlComentarios .= "<td>".$objUtilidad->calcularTiempoTranscurrido($comentario->getFecha())."</td></tr></table>";
					$htmlComentarios .= "</div>";
    		}
    	}
    	
    	$this->view->html = $htmlComentarios;
    }
    
    public function guardarComentarioAction()
    {
    	$this->_helper->layout()->disableLayout();
    	$aut = Zend_Auth::getInstance();
    	
    	$idFoto = $this->getRequest()->getParam('id');
    	$texto = $this->getRequest()->getParam('texto');
    	
    	$objComentarioFotoDao = new Application_Model_ComentarioFotoDao();
    	$objComentarioFoto = new Application_Model_ComentarioFoto();
    	$objUsuarioDao = new Application_Model_UsuarioDao();
    	$objComentariosFotos = new Application_Model_ComentarioFotoDao();
    	
    	//guardar comentario de la foto
    	$objComentarioFoto->setFotoId($idFoto);
    	$objComentarioFoto->setTexto($texto);
    	$objComentarioFoto->setUsuId($aut->getIdentity()->usu_id);
    	$fecha = new DateTime();
    	$fechahora = str_replace(" ","",str_replace("-","",str_replace(":","",$fecha->format('Y-m-d H:i:s'))));
    	$objComentarioFoto->setFecha($fechahora);
    	
    	$idUltimoComentario = $objComentarioFotoDao->guardar($objComentarioFoto);
    	
    	
    	//obtener nuevamente los comentarios
    	$listaComentarios = $objComentariosFotos->obtenerPorFotoIdDesc($idFoto);
    	 
    	$htmlComentarios = "";
    	if(count($listaComentarios) > 0){
    		foreach ($listaComentarios as $comentario){
    			$objUsuarioDao = new Application_Model_UsuarioDao();
    			$objUtilidad = new Application_Model_PublicacionDao();
    			$objUsuario = $objUsuarioDao->obtenerPorId($comentario->getUsuId());
    			 
    			$htmlComentarios .= "<div id='divContenedorComentarios".$idFoto."'><hr>";
    			$htmlComentarios .= "<table width='100%;'>";
    			
    			if($idUltimoComentario == $comentario->getId()){
    			    $htmlComentarios .= "<tr><td colspan='2' class='label label-info' width='100%'>".$objUsuario->getNombre()."<br><br><img style='width: 50px;' src='/imagenes/usuarios/icono/".$objUsuario->getFoto()."'>".$comentario->getTexto()."</td></tr>";
    			}else{
    			    $htmlComentarios .= "<tr><td colspan='2' class='label' width='100%'>".$objUsuario->getNombre()."<br><br><img style='width: 50px;' src='/imagenes/usuarios/icono/".$objUsuario->getFoto()."'>".$comentario->getTexto()."</td></tr>";
    			}
    			$htmlComentarios .= "<tr>";
    			$htmlComentarios .= "<td>".$objUtilidad->calcularTiempoTranscurrido($comentario->getFecha())."</td></tr></table>";
    			$htmlComentarios .= "</div>";
    		}
    	}
    	 
    	$this->view->ok = $htmlComentarios;
    }
    
    public function editarAction()
    {
    	$aut = Zend_Auth::getInstance();
    	$fecha = new DateTime();
    	$objAlbumDao = new Application_Model_AlbumDao();
    	$objAlbum = new Application_Model_Album();
    	$objFoto = new Application_Model_Foto();
    	$objFotoDao = new Application_Model_FotoDao();
    	$form = new Application_Form_FormEditarAlbum();
    	
    	if($this->getRequest()->isPost() == false)
    	{
    		$albumId = $this->getRequest()->getParam('albumId');
    		$objAlbum = $objAlbumDao->obtenerPorId($albumId);
    		$objFoto = $objFotoDao->obtenerPorNombre($objAlbum->getPortada());
    
    		//SEGURIDAD
    		$usuarioAlbumId = $objAlbumDao->obtenerPorId($albumId)->getUsuario();
    		if($aut->getIdentity()->usu_id != $usuarioAlbumId)
    		{
    			echo "<img src='/imagenes/proyecto/denegado.png'>&nbsp;&nbsp;&nbsp;TÚ NO TIENES PERMISOS PARA MODIFICAR ESTA INFORMACIÓN.";
    			exit();
    		}
    		//SEGURIDAD
    		
    		$array = array(
    				"txtNombre" => $objAlbum->getNombre(),
    				"fotoDesc1" => $objFoto->getNombre(),
    				"hdnIdAlbum" => $objAlbum->getId(),
    				"txtDescripcion" => $objAlbum->getDescripcion()
    		);
    		$form->populate($array);
    	}
    	 
    	if($this->getRequest()->isPost())
    	{
    	        	    
    		$formData = $this->_request->getPost();
    		if($form->isValid($this->_getAllParams()))
    		{
    			$objAlbumDao = new Application_Model_AlbumDao();
    			$objAlbum = new Application_Model_Album();
    			$objFoto = new Application_Model_Foto();
    			$objFotoDao = new Application_Model_FotoDao();
    			$objUtilidad = new Application_Model_Utilidad();
    			$album = $objAlbumDao->obtenerPorId($this->getRequest()->getParam('hdnIdAlbum'));
    			$idFoto = $this->getRequest()->getParam('hdnFotoSeleccionada');
    			$objFoto = $objFotoDao->obtenerPorId($idFoto);
    			 
    			$fecha = new DateTime();
    			$fechahora = str_replace(" ","",str_replace("-","",str_replace(":","",$fecha->format('Y-m-d H:i:s'))));
    			 
    			$objAlbum->setId($this->getRequest()->getParam('hdnIdAlbum'));
    			$objAlbum->setNombre($this->getRequest()->getParam('txtNombre'));
    			$objAlbum->setDescripcion($this->getRequest()->getParam('txtDescripcion'));
    			$objAlbum->setFechaCreacion($fechahora);
    			$objAlbum->setUsuario($aut->getIdentity()->usu_id);
    			 
    			$objFoto->setNombre($this->getRequest()->getParam('fotoDesc1'));
    			$objFotoDao->guardar($objFoto);
    			 
    			$objAlbum->setPortada($objFoto->getFoto());
    			$objAlbumDao->guardar($objAlbum);
    			 
    
    			$this->_redirect('/album/');
    		}else{
    			$form->populate($formData);
    		}
    		 
    	}
    	$this->view->form = $form;
    }
    
    
    function obtenerFotosAction()
    {
    	$this->_helper->layout()->disableLayout();
    	$aut = Zend_Auth::getInstance();
    
    	$objFotoDao = new Application_Model_FotoDao();
    	$idAlbum = $this->getRequest()->getParam('idAlbum');
    
    	$objFoto = new Application_Model_Foto();
    	$objFoto->getFoto();
    
    	$listafotos = $objFotoDao->obtenerPorAlbumId($idAlbum);
    
    	$columna = 0;
    	$cantColumna = 8;
    
    	$html =  "<table width='100%' align='center'>";
    	foreach ($listafotos as $foto){
    		if($columna == 0){
    			$html .= "<tr>";
    		}
    		$html .= "<td>";
    		$html .= "<table align='center'>";
    		$html .= "<tr>";
    		$html .= "<td align='center'>";
    		$html .= "<input type='radio' name='optUsuario' value='".$foto->getId()."' onclick='elDestinatario(".$foto->getId().")'>";
    		$html .= "</td>";
    		$html .= "</tr>";
    		$html .= "<tr>";
    		$html .= "<td align='center'>";
    		$html .= "<img width='70px' src='/imagenes/fotos/icono/".$foto->getFoto()."'></img>";
    		$html .= "</td>";
    		$html .= "</tr>";
    		$html .= "</table>";
    		$html .= "</td>";
    		$columna++;
    		if($columna == $cantColumna){
    			$html .= "</tr>";
    			$columna = 0;
    		}
    	}
    	$html .= "</tr>";
    	$html .= "</table>";
    		
    	$this->view->ok = $html;
    
    }

}


