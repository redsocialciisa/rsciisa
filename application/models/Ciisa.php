<?php

class Application_Model_Ciisa
{
    private $_myServer;
    private $_myUser;
    private $_myPass;
    private $_myDB;    
    private $_con;
    private $_d;   

	/**
	 * @return the $_myServer
	 */
	public function getMyServer() {
		return $this->_myServer;
	}

	/**
	 * @param string $_myServer
	 */
	public function setMyServer($_myServer) {
		$this->_myServer = $_myServer;
	}

	function __construct ()
    {
        $this->_myServer = "127.0.0.1";
        $this->_myUser = "sa";
        $this->_myPass = "180376";
        $this->_myDB = "[RC RCA 2007]";
        $this->_con = @mssql_connect($this->_myServer, $this->_myUser, $this->_myPass) or die("Couldn't connect to SQL Server on $this->_myServer");
        $this->_d = @mssql_select_db($this->_myDB, $this->_con) or die("Couldn't open database $this->_myDB");        
    }
    
    function validarAcceso($usuario, $password) {        
        
        $query = "SELECT 1 
        		  FROM [RC RCA 2007].[dbo].[ACC_LOGIN] 
        		  WHERE PERS_LOGIN = '$usuario' 
        		  AND PERS_PASSWORD = '$password'";
        
        $result = mssql_query($query);
        $numRows = mssql_num_rows($result);
        
        if($numRows > 0)
        {
            return true;
        }else{
            return false;
        }
        
        mssql_close($this->_con);
    }
    
    function obtenerUsuarioAlumnoCiisa($usuario) {    
   	
    	$query = "SELECT
    				ALU_RUT,
    				ALU_NOMBRE,    				
    				ALU_EMAIL,
    				convert(nvarchar,Year(ALU_FECNAC)) + '-' + convert(nvarchar,Month(ALU_FECNAC)) + '-' + convert(nvarchar,Day(ALU_FECNAC)) AS ALU_FECNAC
    			FROM
    				[RC RCA 2007].[dbo].[ALUMNOS]
    			WHERE 
    				ALU_RUT = '$usuario'"; 
		    
    	$result = mssql_query($query);
    	$numRows = mssql_num_rows($result);   	
    	
    	if($numRows > 0)
    	{
    	    $objUsuario = new Application_Model_Usuario();
    	    $row = mssql_fetch_array($result);
	        	    
    	    $objUsuario->setUsuarioCiisa(trim($row['ALU_RUT']));
    	    $objUsuario->setNombre(trim(iconv("CP850", "UTF-8", $row["ALU_NOMBRE"])));
    	    $objUsuario->setCorreo(trim($row['ALU_EMAIL']));
    	    $objUsuario->setFechaNacimiento($row['ALU_FECNAC']);
    	    			
    	    return $objUsuario;
    	}else{
    	    return null;
    	}   	
    	
    	mssql_close($this->_con);
    }
    
    function obtenerUsuarioProfesorAcademicoCiisa($usuario) {
    
    	$query = "SELECT 
					 PFE_SIGLA,
					 PFE_RUT,
					 PFE_NOMBRE,
				 	 PFE_EMAIL	
				 FROM 
					 PROFESOR
				WHERE 
					 PFE_SIGLA = '$usuario'";
    
    	$result = mssql_query($query);
    	$numRows = mssql_num_rows($result);
    	 
    	if($numRows > 0)
    	{
    		$objUsuario = new Application_Model_Usuario();
    		$row = mssql_fetch_array($result);
    			
    		$objUsuario->setUsuarioCiisa(trim($row['PFE_SIGLA']));
    		$objUsuario->setNombre(trim(iconv("CP850", "UTF-8", $row["PFE_NOMBRE"])));
    		$objUsuario->setCorreo(trim($row['PFE_EMAIL']));
    
    		return $objUsuario;
    	}else{
    		return null;
    	}
    	
    	mssql_close($this->_con);
    
    }
    
    function obtenerPerfil($usuario) {
    
    	$query = "SELECT
				    	PERFIL_ID
				  FROM
				   	 [RC RCA 2007].[dbo].[ACC_PERFILES_USUARIOS]
				  WHERE
				  	  PERS_LOGIN = '$usuario'";
    
    	$result = mssql_query($query);
    	$numRows = mssql_num_rows($result);
    	
    
    	if($numRows > 0)
    	{
    		$objUsuario = new Application_Model_Usuario();
    		$row = mssql_fetch_array($result);
    
    		return trim($row['PERFIL_ID']);
    	}
    	
    	mssql_close($this->_con);
    	
    }
    
    function obtenerCarrera($usuario) {
    
    	$query = "SELECT
				    	rtrim(C.CAR_NOMBRE) as CAR_NOMBRE
				  FROM
				    	[RC RCA 2007].[dbo].[ALUMNOS] AS A,
				    	[RC RCA 2007].[dbo].[ALUMNO_CARRERA] AS AC,
				    	[RC RCA 2007].[dbo].[CARRERA] AS C
				  WHERE
				  	  	A.ALU_RUT = '$usuario'
				  AND   A.ALU_RUT = AC.ALU_RUT
				  AND   AC.CAR_CODIGO = C.CAR_CODIGO";
    
    	$result = mssql_query($query);
    	$numRows = mssql_num_rows($result);
    	 
    
    	if($numRows > 0)
    	{
    		$objUsuario = new Application_Model_Usuario();
    		$row = mssql_fetch_array($result);
    
    		return trim(iconv("CP850", "UTF-8", $row["CAR_NOMBRE"]));
    	}else{
    	    return "";
    	}
    	 
    	mssql_close($this->_con);
    	 
    }
    
    function obtenerDescripcionPerfil($perfilCiisa) {
    
    	$query = "SELECT
    					rtrim(PERFIL_NOMBRE) AS PERFIL_NOMBRE
    			  FROM
    					[RC RCA 2007].[dbo].[ACC_PERFILES]
    			  WHERE
    					PERFIL_ID = '$perfilCiisa'";
    
    	$result = mssql_query($query);
    	$numRows = mssql_num_rows($result);
    	mssql_close($this->_con);
    
    	if($numRows > 0)
    	{
    	$objUsuario = new Application_Model_Usuario();
    	$row = mssql_fetch_array($result);
    	
    	return trim($row['PERFIL_NOMBRE']);
    	}else{
    	return "";
    	}
    
    	}
    
}

?>