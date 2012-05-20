<?php

class Application_Model_ComentarioFoto
{
    private $_id;
    private $_texto;
    private $_fecha;
    private $_fotoId;
    
	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return the $_texto
	 */
	public function getTexto() {
		return $this->_texto;
	}

	/**
	 * @return the $_fecha
	 */
	public function getFecha() {
		return $this->_fecha;
	}

	/**
	 * @return the $_fotoId
	 */
	public function getFotoId() {
		return $this->_fotoId;
	}

	/**
	 * @param field_type $_id
	 */
	public function setId($_id) {
		$this->_id = $_id;
	}

	/**
	 * @param field_type $_texto
	 */
	public function setTexto($_texto) {
		$this->_texto = $_texto;
	}

	/**
	 * @param field_type $_fecha
	 */
	public function setFecha($_fecha) {
		$this->_fecha = $_fecha;
	}

	/**
	 * @param field_type $_fotoId
	 */
	public function setFotoId($_fotoId) {
		$this->_fotoId = $_fotoId;
	}

    
	

}

