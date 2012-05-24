<?php

class Application_Model_Usuario
{
    private $_id;
    private $_usuarioCiisa;
    private $_nombre;
    private $_correo;
    private $_cv;
    private $_fechaNacimiento;
    private $_emocionId;
    private $_password;
    private $_acepta;
    private $_perfilId;
    private $_sexo;
    
	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return the $_usuarioCiisa
	 */
	public function getUsuarioCiisa() {
		return $this->_usuarioCiisa;
	}

	/**
	 * @return the $_nombre
	 */
	public function getNombre() {
		return $this->_nombre;
	}

	/**
	 * @return the $_correo
	 */
	public function getCorreo() {
		return $this->_correo;
	}

	/**
	 * @return the $_cv
	 */
	public function getCv() {
		return $this->_cv;
	}

	/**
	 * @return the $_fechaNacimiento
	 */
	public function getFechaNacimiento() {
		return $this->_fechaNacimiento;
	}

	/**
	 * @return the $_emocionId
	 */
	public function getEmocionId() {
		return $this->_emocionId;
	}

	/**
	 * @return the $_password
	 */
	public function getPassword() {
		return $this->_password;
	}

	/**
	 * @return the $_acepta
	 */
	public function getAcepta() {
		return $this->_acepta;
	}

	/**
	 * @return the $_perfilId
	 */
	public function getPerfilId() {
		return $this->_perfilId;
	}

	/**
	 * @return the $_sexo
	 */
	public function getSexo() {
		return $this->_sexo;
	}

	/**
	 * @param field_type $_id
	 */
	public function setId($_id) {
		$this->_id = $_id;
	}

	/**
	 * @param field_type $_usuarioCiisa
	 */
	public function setUsuarioCiisa($_usuarioCiisa) {
		$this->_usuarioCiisa = $_usuarioCiisa;
	}

	/**
	 * @param field_type $_nombre
	 */
	public function setNombre($_nombre) {
		$this->_nombre = $_nombre;
	}

	/**
	 * @param field_type $_correo
	 */
	public function setCorreo($_correo) {
		$this->_correo = $_correo;
	}

	/**
	 * @param field_type $_cv
	 */
	public function setCv($_cv) {
		$this->_cv = $_cv;
	}

	/**
	 * @param field_type $_fechaNacimiento
	 */
	public function setFechaNacimiento($_fechaNacimiento) {
		$this->_fechaNacimiento = $_fechaNacimiento;
	}

	/**
	 * @param field_type $_emocionId
	 */
	public function setEmocionId($_emocionId) {
		$this->_emocionId = $_emocionId;
	}

	/**
	 * @param field_type $_password
	 */
	public function setPassword($_password) {
		$this->_password = $_password;
	}

	/**
	 * @param field_type $_acepta
	 */
	public function setAcepta($_acepta) {
		$this->_acepta = $_acepta;
	}

	/**
	 * @param field_type $_perfilId
	 */
	public function setPerfilId($_perfilId) {
		$this->_perfilId = $_perfilId;
	}

	/**
	 * @param field_type $_sexo
	 */
	public function setSexo($_sexo) {
		$this->_sexo = $_sexo;
	}

    
    
    

    
    
    
	
    
	
    
    

}

