<?php

class Application_Model_Amigo
{
    private $_id; //pk
	private $_fechaSolicitud;
	private $_fechaAmistad;
	private $_usuarioId;
	private $_amigoUsuarioId;
	private $_estadoAmistad;
	
	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return the $_fechaSolicitud
	 */
	public function getFechaSolicitud() {
		return $this->_fechaSolicitud;
	}

	/**
	 * @return the $_fechaAmistad
	 */
	public function getFechaAmistad() {
		return $this->_fechaAmistad;
	}

	/**
	 * @return the $_usuarioId
	 */
	public function getUsuarioId() {
		return $this->_usuarioId;
	}

	/**
	 * @return the $_amigoUsuarioId
	 */
	public function getAmigoUsuarioId() {
		return $this->_amigoUsuarioId;
	}

	/**
	 * @return the $_estadoAmistad
	 */
	public function getEstadoAmistad() {
		return $this->_estadoAmistad;
	}

	/**
	 * @param field_type $_id
	 */
	public function setId($_id) {
		$this->_id = $_id;
	}

	/**
	 * @param field_type $_fechaSolicitud
	 */
	public function setFechaSolicitud($_fechaSolicitud) {
		$this->_fechaSolicitud = $_fechaSolicitud;
	}

	/**
	 * @param field_type $_fechaAmistad
	 */
	public function setFechaAmistad($_fechaAmistad) {
		$this->_fechaAmistad = $_fechaAmistad;
	}

	/**
	 * @param field_type $_usuarioId
	 */
	public function setUsuarioId($_usuarioId) {
		$this->_usuarioId = $_usuarioId;
	}

	/**
	 * @param field_type $_amigoUsuarioId
	 */
	public function setAmigoUsuarioId($_amigoUsuarioId) {
		$this->_amigoUsuarioId = $_amigoUsuarioId;
	}

	/**
	 * @param field_type $_estadoAmistad
	 */
	public function setEstadoAmistad($_estadoAmistad) {
		$this->_estadoAmistad = $_estadoAmistad;
	}

	
	
	

}

