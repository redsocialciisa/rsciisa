<?php

class Application_Model_Grupo
{
    private $_id;
    private $_nombre;
    private $_descripcion;
    private $_foto;
    private $_fechaCreacion;
    private $_tipoGrupoId;
    
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
	 * @return the $_foto
	 */
	public function getFoto() {
		return $this->_foto;
	}

	/**
	 * @return the $_fechaCreacion
	 */
	public function getFechaCreacion() {
		return $this->_fechaCreacion;
	}

	/**
	 * @return the $_tipoGrupoId
	 */
	public function getTipoGrupoId() {
		return $this->_tipoGrupoId;
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
	 * @param field_type $_foto
	 */
	public function setFoto($_foto) {
		$this->_foto = $_foto;
	}

	/**
	 * @param field_type $_fechaCreacion
	 */
	public function setFechaCreacion($_fechaCreacion) {
		$this->_fechaCreacion = $_fechaCreacion;
	}

	/**
	 * @param field_type $_tipoGrupoId
	 */
	public function setTipoGrupoId($_tipoGrupoId) {
		$this->_tipoGrupoId = $_tipoGrupoId;
	}

    
	

    
    

}

