<?php

class Application_Model_Integracion
{
    private $_id;
    private $_token;
    private $_secret;
    private $_fechaPermiso;
    private $_usuarioId;
    private $_redId;
    
	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return the $_token
	 */
	public function getToken() {
		return $this->_token;
	}

	/**
	 * @return the $_secret
	 */
	public function getSecret() {
		return $this->_secret;
	}

	/**
	 * @return the $_fechaPermiso
	 */
	public function getFechaPermiso() {
		return $this->_fechaPermiso;
	}

	/**
	 * @return the $_usuarioId
	 */
	public function getUsuarioId() {
		return $this->_usuarioId;
	}

	/**
	 * @return the $_redId
	 */
	public function getRedId() {
		return $this->_redId;
	}

	/**
	 * @param field_type $_id
	 */
	public function setId($_id) {
		$this->_id = $_id;
	}

	/**
	 * @param field_type $_token
	 */
	public function setToken($_token) {
		$this->_token = $_token;
	}

	/**
	 * @param field_type $_secret
	 */
	public function setSecret($_secret) {
		$this->_secret = $_secret;
	}

	/**
	 * @param field_type $_fechaPermiso
	 */
	public function setFechaPermiso($_fechaPermiso) {
		$this->_fechaPermiso = $_fechaPermiso;
	}

	/**
	 * @param field_type $_usuarioId
	 */
	public function setUsuarioId($_usuarioId) {
		$this->_usuarioId = $_usuarioId;
	}

	/**
	 * @param field_type $_redId
	 */
	public function setRedId($_redId) {
		$this->_redId = $_redId;
	}

    
    

}

