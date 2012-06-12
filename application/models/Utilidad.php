<?php

class Application_Model_Utilidad
{
	public function girarFecha($fecha)
	{
	    $date = strtotime($fecha);
	    return date("d", $date)."-".date("m", $date)."-".date("Y", $date);
	}    

}

