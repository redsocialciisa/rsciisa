<?php

class Application_Model_Oferta
{
    private $_id;
    private $_nombre;
    private $_descripcion;
    private $_fechaCreacion;
    private $_usuarioId;
    private $_areaId;
    
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
	 * @return the $_fechaCreacion
	 */
	public function getFechaCreacion() {
		return $this->_fechaCreacion;
	}

	/**
	 * @return the $_usuarioId
	 */
	public function getUsuarioId() {
		return $this->_usuarioId;
	}

	/**
	 * @return the $_areaId
	 */
	public function getAreaId() {
		return $this->_areaId;
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
	 * @param field_type $_fechaCreacion
	 */
	public function setFechaCreacion($_fechaCreacion) {
		$this->_fechaCreacion = $_fechaCreacion;
	}

	/**
	 * @param field_type $_usuarioId
	 */
	public function setUsuarioId($_usuarioId) {
		$this->_usuarioId = $_usuarioId;
	}

	/**
	 * @param field_type $_areaId
	 */
	public function setAreaId($_areaId) {
		$this->_areaId = $_areaId;
	}

    
    

}

