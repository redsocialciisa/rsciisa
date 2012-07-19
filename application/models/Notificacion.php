<?php

class Application_Model_Notificacion
{
    private $_id;
    private $_texto;
    private $_fecha;
    private $_vista;
    private $_usuarioId;
    private $_tipoNotificacionId;
    private $_actividad;
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
	 * @return the $_vista
	 */
	public function getVista() {
		return $this->_vista;
	}

	/**
	 * @return the $_usuarioId
	 */
	public function getUsuarioId() {
		return $this->_usuarioId;
	}

	/**
	 * @return the $_tipoNotificacionId
	 */
	public function getTipoNotificacionId() {
		return $this->_tipoNotificacionId;
	}

	/**
	 * @return the $_actividad
	 */
	public function getActividad() {
		return $this->_actividad;
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
	 * @param field_type $_vista
	 */
	public function setVista($_vista) {
		$this->_vista = $_vista;
	}

	/**
	 * @param field_type $_usuarioId
	 */
	public function setUsuarioId($_usuarioId) {
		$this->_usuarioId = $_usuarioId;
	}

	/**
	 * @param field_type $_tipoNotificacionId
	 */
	public function setTipoNotificacionId($_tipoNotificacionId) {
		$this->_tipoNotificacionId = $_tipoNotificacionId;
	}

	/**
	 * @param field_type $_actividad
	 */
	public function setActividad($_actividad) {
		$this->_actividad = $_actividad;
	}

}

