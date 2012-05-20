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
        $this->_myPass = "qwerty";
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

    
}

?>