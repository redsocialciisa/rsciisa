<?php

class Application_Model_Mensaje
{
    private $_id;
    private $_fecha;
    private $_asunto;
    private $_de;
    private $_para;
    
	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return the $_fecha
	 */
	public function getFecha() {
		return $this->_fecha;
	}

	/**
	 * @return the $_asunto
	 */
	public function getAsunto() {
		return $this->_asunto;
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
	 * @param field_type $_id
	 */
	public function setId($_id) {
		$this->_id = $_id;
	}

	/**
	 * @param field_type $_fecha
	 */
	public function setFecha($_fecha) {
		$this->_fecha = $_fecha;
	}

	/**
	 * @param field_type $_asunto
	 */
	public function setAsunto($_asunto) {
		$this->_asunto = $_asunto;
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

    
    

}

