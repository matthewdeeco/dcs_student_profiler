<?php
require_once 'field.php';

class Pedigree extends Field {
	public function parse($pedigree, $a = null, $b = null) {
		$this->values['pedigree'] = $pedigree;
	}
}
?>