<?php
abstract class Field extends CI_Model {
	protected $values = array();
	
	public function __contruct() {
		parent::__construct();
	}
	
	public abstract function parse();
	public function getName() {
	}
	
	public function initialize($values) {
		$this->values = $values;
	}
	
	public function getValue($i = 0) {
		return $this->values[$i];
	}
	
	public function toString() {
		return $this->values[0];
	}
}
?>