<?php
require_once 'field.php';

class Pedigree extends Field {
	public function parse() {
		$pedigree = $this->values[0];
		return $pedigree;	
	}
}
?>