<?php

class Application_Model_Foto
{
    private $_id;
    private $_nombre;
    private $_foto;
    private $_fecha;
    private $_albumId;
    
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
	 * @return the $_foto
	 */
	public function getFoto() {
		return $this->_foto;
	}

	/**
	 * @return the $_fecha
	 */
	public function getFecha() {
		return $this->_fecha;
	}

	/**
	 * @return the $_albumId
	 */
	public function getAlbumId() {
		return $this->_albumId;
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
	 * @param field_type $_foto
	 */
	public function setFoto($_foto) {
		$this->_foto = $_foto;
	}

	/**
	 * @param field_type $_fecha
	 */
	public function setFecha($_fecha) {
		$this->_fecha = $_fecha;
	}

	/**
	 * @param field_type $_albumId
	 */
	public function setAlbumId($_albumId) {
		$this->_albumId = $_albumId;
	}

    
	
    
    

}

