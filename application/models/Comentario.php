<?php

class Application_Model_Comentario
{
    private $_id;
    private $_texto;
    private $_fecha;
    private $_publicacionId;
    private $_usuarioId;
    
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
	 * @return the $_publicacionId
	 */
	public function getPublicacionId() {
		return $this->_publicacionId;
	}

	/**
	 * @return the $_usuarioId
	 */
	public function getUsuarioId() {
		return $this->_usuarioId;
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
	 * @param field_type $_publicacionId
	 */
	public function setPublicacionId($_publicacionId) {
		$this->_publicacionId = $_publicacionId;
	}

	/**
	 * @param field_type $_usuarioId
	 */
	public function setUsuarioId($_usuarioId) {
		$this->_usuarioId = $_usuarioId;
	}

    
	
    
    

}

