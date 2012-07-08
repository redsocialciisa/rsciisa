<?php

class Application_Model_UsuarioEvento
{
	private $_id;
	private $_eventoId;
	private $_usuarioId;
  	private $_eliminar;
  	
	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return the $_eventoId
	 */
	public function getEventoId() {
		return $this->_eventoId;
	}

	/**
	 * @return the $_usuarioId
	 */
	public function getUsuarioId() {
		return $this->_usuarioId;
	}

	/**
	 * @return the $_eliminar
	 */
	public function getEliminar() {
		return $this->_eliminar;
	}

	/**
	 * @param field_type $_id
	 */
	public function setId($_id) {
		$this->_id = $_id;
	}

	/**
	 * @param field_type $_eventoId
	 */
	public function setEventoId($_eventoId) {
		$this->_eventoId = $_eventoId;
	}

	/**
	 * @param field_type $_usuarioId
	 */
	public function setUsuarioId($_usuarioId) {
		$this->_usuarioId = $_usuarioId;
	}

	/**
	 * @param field_type $_eliminar
	 */
	public function setEliminar($_eliminar) {
		$this->_eliminar = $_eliminar;
	}

  	
	
}

