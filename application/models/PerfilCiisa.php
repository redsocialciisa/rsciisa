<?php

class Application_Model_PerfilCiisa
{
    private $_id;
    private $_perfil;
    private $_perfilCiisa;
    
	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return the $_perfil
	 */
	public function getPerfil() {
		return $this->_perfil;
	}

	/**
	 * @return the $_perfilCiisa
	 */
	public function getPerfilCiisa() {
		return $this->_perfilCiisa;
	}

	/**
	 * @param field_type $_id
	 */
	public function setId($_id) {
		$this->_id = $_id;
	}

	/**
	 * @param field_type $_perfil
	 */
	public function setPerfil($_perfil) {
		$this->_perfil = $_perfil;
	}

	/**
	 * @param field_type $_perfilCiisa
	 */
	public function setPerfilCiisa($_perfilCiisa) {
		$this->_perfilCiisa = $_perfilCiisa;
	}
}

