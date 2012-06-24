<?php

class Application_Model_Evento
{
    private $_id;
    private $_usuarioId;
    private $_nombre;
    private $_descripcion;
    private $_lugar;
    private $_cordenadaX;
    private $_cordenadaY;
    private $_fechaCreacion;
    private $_fechaEvento;
    private $_tipoEventoId;
    private $_hora;
    
	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return the $_usuarioId
	 */
	public function getUsuarioId() {
		return $this->_usuarioId;
	}

	/**
	 * @return the $_nombre
	 */
	public function getNombre() {
		return $this->_nombre;
	}

	/**
	 * @return the $_descripcion
	 */
	public function getDescripcion() {
		return $this->_descripcion;
	}

	/**
	 * @return the $_lugar
	 */
	public function getLugar() {
		return $this->_lugar;
	}

	/**
	 * @return the $_cordenadaX
	 */
	public function getCordenadaX() {
		return $this->_cordenadaX;
	}

	/**
	 * @return the $_cordenadaY
	 */
	public function getCordenadaY() {
		return $this->_cordenadaY;
	}

	/**
	 * @return the $_fechaCreacion
	 */
	public function getFechaCreacion() {
		return $this->_fechaCreacion;
	}

	/**
	 * @return the $_fechaEvento
	 */
	public function getFechaEvento() {
		return $this->_fechaEvento;
	}

	/**
	 * @return the $_tipoEventoId
	 */
	public function getTipoEventoId() {
		return $this->_tipoEventoId;
	}

	/**
	 * @return the $_hora
	 */
	public function getHora() {
		return $this->_hora;
	}

	/**
	 * @param field_type $_id
	 */
	public function setId($_id) {
		$this->_id = $_id;
	}

	/**
	 * @param field_type $_usuarioId
	 */
	public function setUsuarioId($_usuarioId) {
		$this->_usuarioId = $_usuarioId;
	}

	/**
	 * @param field_type $_nombre
	 */
	public function setNombre($_nombre) {
		$this->_nombre = $_nombre;
	}

	/**
	 * @param field_type $_descripcion
	 */
	public function setDescripcion($_descripcion) {
		$this->_descripcion = $_descripcion;
	}

	/**
	 * @param field_type $_lugar
	 */
	public function setLugar($_lugar) {
		$this->_lugar = $_lugar;
	}

	/**
	 * @param field_type $_cordenadaX
	 */
	public function setCordenadaX($_cordenadaX) {
		$this->_cordenadaX = $_cordenadaX;
	}

	/**
	 * @param field_type $_cordenadaY
	 */
	public function setCordenadaY($_cordenadaY) {
		$this->_cordenadaY = $_cordenadaY;
	}

	/**
	 * @param field_type $_fechaCreacion
	 */
	public function setFechaCreacion($_fechaCreacion) {
		$this->_fechaCreacion = $_fechaCreacion;
	}

	/**
	 * @param field_type $_fechaEvento
	 */
	public function setFechaEvento($_fechaEvento) {
		$this->_fechaEvento = $_fechaEvento;
	}

	/**
	 * @param field_type $_tipoEventoId
	 */
	public function setTipoEventoId($_tipoEventoId) {
		$this->_tipoEventoId = $_tipoEventoId;
	}

	/**
	 * @param field_type $_hora
	 */
	public function setHora($_hora) {
		$this->_hora = $_hora;
	}

    

    
}

