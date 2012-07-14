<?php

class Application_Model_TipoNotificacion
{
    private $_id;
    private $_nombre;
    private $_url;
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
	 * @return the $_url
	 */
	public function getUrl() {
		return $this->_url;
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
	 * @param field_type $_url
	 */
	public function setUrl($_url) {
		$this->_url = $_url;
	}
}

