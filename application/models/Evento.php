<?php

class Application_Model_Evento
{
    private $_id;
    private $_nombre;
    private $_descripcion;
    private $_lugar;
    private $_cordenadaX;
    private $cordenadaY;
    private $tipoEventoId;
    
	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
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
	 * @return the $cordenadaY
	 */
	public function getCordenadaY() {
		return $this->cordenadaY;
	}

	/**
	 * @return the $tipoEventoId
	 */
	public function getTipoEventoId() {
		return $this->tipoEventoId;
	}

	/**
	 * @param field_type $_id
	 */
	public function setId($_id) {
		$this->_id = $_id;
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
	 * @param field_type $cordenadaY
	 */
	public function setCordenadaY($cordenadaY) {
		$this->cordenadaY = $cordenadaY;
	}

	/**
	 * @param field_type $tipoEventoId
	 */
	public function setTipoEventoId($tipoEventoId) {
		$this->tipoEventoId = $tipoEventoId;
	}

    
	
    
    

}

