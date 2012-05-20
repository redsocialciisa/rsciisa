<?php

class Application_Model_TipoPublicacion
{
    private $_id;
    private $_nombre;
    
	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return the $_nombre
	 */
	public function getNombre() {
		return $this->_nombre;
	}

	/**
	 * @param field_type $_id
	 */
	public function setId($_id) {
		$this->_id = $_id;
	}

	/**
	 * @param field_type $_nombre
	 */
	public function setNombre($_nombre) {
		$this->_nombre = $_nombre;
	}

    
    

}

