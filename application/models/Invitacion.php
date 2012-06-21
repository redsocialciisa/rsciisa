<?php

class Application_Model_Invitacion
{
    private $_id;
    private $_fecha;
    private $_usuarioId;
    private $_tipoInvitacionId;
    private $_idActividad;
    private $_estado;
	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return the $_fecha
	 */
	public function getFecha() {
		return $this->_fecha;
	}

	/**
	 * @return the $_usuarioId
	 */
	public function getUsuarioId() {
		return $this->_usuarioId;
	}

	/**
	 * @return the $_tipoInvitacionId
	 */
	public function getTipoInvitacionId() {
		return $this->_tipoInvitacionId;
	}

	/**
	 * @return the $_idActividad
	 */
	public function getIdActividad() {
		return $this->_idActividad;
	}

	/**
	 * @return the $_estado
	 */
	public function getEstado() {
		return $this->_estado;
	}

	/**
	 * @param field_type $_id
	 */
	public function setId($_id) {
		$this->_id = $_id;
	}

	/**
	 * @param field_type $_fecha
	 */
	public function setFecha($_fecha) {
		$this->_fecha = $_fecha;
	}

	/**
	 * @param field_type $_usuarioId
	 */
	public function setUsuarioId($_usuarioId) {
		$this->_usuarioId = $_usuarioId;
	}

	/**
	 * @param field_type $_tipoInvitacionId
	 */
	public function setTipoInvitacionId($_tipoInvitacionId) {
		$this->_tipoInvitacionId = $_tipoInvitacionId;
	}

	/**
	 * @param field_type $_idActividad
	 */
	public function setIdActividad($_idActividad) {
		$this->_idActividad = $_idActividad;
	}

	/**
	 * @param field_type $_estado
	 */
	public function setEstado($_estado) {
		$this->_estado = $_estado;
	}

    
    
}

