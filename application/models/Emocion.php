<?php

class Application_Model_Emocion
{
    private $_id;
    private $_nombre;
    private $_foto;
    
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
	 * @return the $_foto
	 */
	public function getFoto() {
		return $this->_foto;
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

	/**
	 * @param field_type $_foto
	 */
	public function setFoto($_foto) {
		$this->_foto = $_foto;
	}

    
	
    
    

}

