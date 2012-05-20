<?php

class Application_Model_Album
{

    private $_id;
    private $_nombre;
    private $_fechaCreacion;
    private $_usuario;
    
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
	 * @return the $_fechaCreacion
	 */
	public function getFechaCreacion() {
		return $this->_fechaCreacion;
	}

	/**
	 * @return the $_usuario
	 */
	public function getUsuario() {
		return $this->_usuario;
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
	 * @param field_type $_fechaCreacion
	 */
	public function setFechaCreacion($_fechaCreacion) {
		$this->_fechaCreacion = $_fechaCreacion;
	}

	/**
	 * @param field_type $_usuario
	 */
	public function setUsuario($_usuario) {
		$this->_usuario = $_usuario;
	}

    

    
    
    

}

