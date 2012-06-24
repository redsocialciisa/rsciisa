<?php

class Application_Model_PublicacionEvento
{
    private $_id;
    private $_publicacionId;
    private $_eventoId;
    
	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return the $_publicacionId
	 */
	public function getPublicacionId() {
		return $this->_publicacionId;
	}

	/**
	 * @return the $_eventoId
	 */
	public function getEventoId() {
		return $this->_eventoId;
	}

	/**
	 * @param field_type $_id
	 */
	public function setId($_id) {
		$this->_id = $_id;
	}

	/**
	 * @param field_type $_publicacionId
	 */
	public function setPublicacionId($_publicacionId) {
		$this->_publicacionId = $_publicacionId;
	}

	/**
	 * @param field_type $_eventoId
	 */
	public function setEventoId($_eventoId) {
		$this->_eventoId = $_eventoId;
	}
    
}

