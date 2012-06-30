<?php

class Application_Model_Mensaje
{
    private $_id;
    private $_de;
    private $_para;
    private $_fecha;
    private $_texto;
	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return the $_de
	 */
	public function getDe() {
		return $this->_de;
	}

	/**
	 * @return the $_para
	 */
	public function getPara() {
		return $this->_para;
	}

	/**
	 * @return the $_fecha
	 */
	public function getFecha() {
		return $this->_fecha;
	}

	/**
	 * @return the $_texto
	 */
	public function getTexto() {
		return $this->_texto;
	}

	/**
	 * @param field_type $_id
	 */
	public function setId($_id) {
		$this->_id = $_id;
	}

	/**
	 * @param field_type $_de
	 */
	public function setDe($_de) {
		$this->_de = $_de;
	}

	/**
	 * @param field_type $_para
	 */
	public function setPara($_para) {
		$this->_para = $_para;
	}

	/**
	 * @param field_type $_fecha
	 */
	public function setFecha($_fecha) {
		$this->_fecha = $_fecha;
	}

	/**
	 * @param field_type $_texto
	 */
	public function setTexto($_texto) {
		$this->_texto = $_texto;
	}


    
}

