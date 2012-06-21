<?php

class Application_Model_UsuarioGrupo
{
    private $_id;
    private $_grupoId;
    private $_usuarioId;
    private $_fechaParticipa;
    private $_participa;
    private $_eliminar;
	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return the $_grupoId
	 */
	public function getGrupoId() {
		return $this->_grupoId;
	}

	/**
	 * @return the $_usuarioId
	 */
	public function getUsuarioId() {
		return $this->_usuarioId;
	}

	/**
	 * @return the $_fechaParticipa
	 */
	public function getFechaParticipa() {
		return $this->_fechaParticipa;
	}

	/**
	 * @return the $_participa
	 */
	public function getParticipa() {
		return $this->_participa;
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
	 * @param field_type $_grupoId
	 */
	public function setGrupoId($_grupoId) {
		$this->_grupoId = $_grupoId;
	}

	/**
	 * @param field_type $_usuarioId
	 */
	public function setUsuarioId($_usuarioId) {
		$this->_usuarioId = $_usuarioId;
	}

	/**
	 * @param field_type $_fechaParticipa
	 */
	public function setFechaParticipa($_fechaParticipa) {
		$this->_fechaParticipa = $_fechaParticipa;
	}

	/**
	 * @param field_type $_participa
	 */
	public function setParticipa($_participa) {
		$this->_participa = $_participa;
	}

	/**
	 * @param field_type $_eliminar
	 */
	public function setEliminar($_eliminar) {
		$this->_eliminar = $_eliminar;
	}

	
    
    
    
}

