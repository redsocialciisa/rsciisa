<?php

class Application_Model_PublicacionGrupo
{
    private $_id;
    private $_publicacionId;
    private $_grupoId;
    
	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return the $_publicacionId
	 */
	public function getPublicacionId() {
		return $this->_publicacionId;
	}

	/**
	 * @return the $_grupoId
	 */
	public function getGrupoId() {
		return $this->_grupoId;
	}

	/**
	 * @param field_type $_id
	 */
	public function setId($_id) {
		$this->_id = $_id;
	}

	/**
	 * @param field_type $_publicacionId
	 */
	public function setPublicacionId($_publicacionId) {
		$this->_publicacionId = $_publicacionId;
	}

	/**
	 * @param field_type $_grupoId
	 */
	public function setGrupoId($_grupoId) {
		$this->_grupoId = $_grupoId;
	}

}

