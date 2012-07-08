<?php

class Application_Model_Utilidad
{
	public function girarFecha($fecha)
	{
	    $date = strtotime($fecha);
	    return date("d", $date)."-".date("m", $date)."-".date("Y", $date);
	}    
	
	public function devolverFechaParaBD($fecha)
	{
		$date = strtotime($fecha);
		return date("Y", $date)."-".date("m", $date)."-".date("d", $date);
	}
	
	function recortarImagen($rutaImagenOriginal,$extension,$max_ancho,$max_alto)
	{
		//Creamos una variable imagen a partir de la imagen original
		
	    if($extension == "png")
	    {
	        $img_original = imagecreatefrompng($rutaImagenOriginal);
	    }else{
	        $img_original = imagecreatefromjpeg($rutaImagenOriginal);
	    }
		 
		//Ancho y alto de la imagen original
		list($ancho,$alto)=getimagesize($rutaImagenOriginal);
		 
		//Se calcula ancho y alto de la imagen final
		$x_ratio = $max_ancho / $ancho;
		$y_ratio = $max_alto / $alto;
		 
		//Si el ancho y el alto de la imagen no superan los maximos,
		//ancho final y alto final son los que tiene actualmente
		if( ($ancho <= $max_ancho) && ($alto <= $max_alto)){//Si ancho
			$ancho_final = $ancho;
			$alto_final = $alto;
		}
		/*
		 * si proporcion horizontal*alto mayor que el alto maximo,
		* alto final es alto por la proporcion horizontal
		* es decir, le quitamos al alto, la misma proporcion que
		* le quitamos al alto
		*
		*/
		elseif (($x_ratio * $alto) < $max_alto){
			$alto_final = ceil($x_ratio * $alto);
			$ancho_final = $max_ancho;
		}
		/*
		 * Igual que antes pero a la inversa
		*/
		else{
			$ancho_final = ceil($y_ratio * $ancho);
			$alto_final = $max_alto;
		}
		 
		//Creamos una imagen en blanco de tama�o $ancho_final  por $alto_final .
		$tmp=imagecreatetruecolor($ancho_final,$alto_final);
		 
		//Copiamos $img_original sobre la imagen que acabamos de crear en blanco ($tmp)
		imagecopyresampled($tmp,$img_original,0,0,0,0,$ancho_final, $alto_final,$ancho,$alto);
		 
		//Se destruye variable $img_original para liberar memoria
		imagedestroy($img_original);
		 
		//Definimos la calidad de la imagen final
		$calidad=95;
		 
		return $tmp;
	
	}

}

