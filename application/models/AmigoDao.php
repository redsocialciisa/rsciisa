<?php

class Application_Model_AmigoDao
{
    private $_table;
    
    public function __construct()
    {
    	$this->_table = new Application_Model_DbTable_AmigoMap();
    }
    
}

?>