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

}

