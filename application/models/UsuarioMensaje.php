<?php

class Application_Model_UsuarioMensaje
{
    private $_id;
    private $_leido;
    private $_usuarioId;
    private $_mensajeId;
    
	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return the $_leido
	 */
	public function getLeido() {
		return $this->_leido;
	}

	/**
	 * @return the $_usuarioId
	 */
	public function getUsuarioId() {
		return $this->_usuarioId;
	}

	/**
	 * @return the $_mensajeId
	 */
	public function getMensajeId() {
		return $this->_mensajeId;
	}

	/**
	 * @param field_type $_id
	 */
	public function setId($_id) {
		$this->_id = $_id;
	}

	/**
	 * @param field_type $_leido
	 */
	public function setLeido($_leido) {
		$this->_leido = $_leido;
	}

	/**
	 * @param field_type $_usuarioId
	 */
	public function setUsuarioId($_usuarioId) {
		$this->_usuarioId = $_usuarioId;
	}

	/**
	 * @param field_type $_mensajeId
	 */
	public function setMensajeId($_mensajeId) {
		$this->_mensajeId = $_mensajeId;
	}

    
    
    
    

}

