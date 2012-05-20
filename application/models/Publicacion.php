<?php

class Application_Model_Publicacion
{
    private $_id;
    private $_texto;
    private $_foto;
    private $_video;
    private $_fecha;
    private $_privacidadId;
    private $_tipoId;
    private $_usuarioId;
    
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
	 * @return the $_foto
	 */
	public function getFoto() {
		return $this->_foto;
	}

	/**
	 * @return the $_video
	 */
	public function getVideo() {
		return $this->_video;
	}

	/**
	 * @return the $_fecha
	 */
	public function getFecha() {
		return $this->_fecha;
	}

	/**
	 * @return the $_privacidadId
	 */
	public function getPrivacidadId() {
		return $this->_privacidadId;
	}

	/**
	 * @return the $_tipoId
	 */
	public function getTipoId() {
		return $this->_tipoId;
	}

	/**
	 * @return the $_usuarioId
	 */
	public function getUsuarioId() {
		return $this->_usuarioId;
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
	 * @param field_type $_foto
	 */
	public function setFoto($_foto) {
		$this->_foto = $_foto;
	}

	/**
	 * @param field_type $_video
	 */
	public function setVideo($_video) {
		$this->_video = $_video;
	}

	/**
	 * @param field_type $_fecha
	 */
	public function setFecha($_fecha) {
		$this->_fecha = $_fecha;
	}

	/**
	 * @param field_type $_privacidadId
	 */
	public function setPrivacidadId($_privacidadId) {
		$this->_privacidadId = $_privacidadId;
	}

	/**
	 * @param field_type $_tipoId
	 */
	public function setTipoId($_tipoId) {
		$this->_tipoId = $_tipoId;
	}

	/**
	 * @param field_type $_usuarioId
	 */
	public function setUsuarioId($_usuarioId) {
		$this->_usuarioId = $_usuarioId;
	}

    
    

}

