<?php
require_once 'field.php';

class Grade extends Field {
	public function parse(&$grade, $compgrade, $secondcompgrade) {
		$grade = trim($grade);
		$compgrade = trim($compgrade);
		$secondcompgrade = trim($secondcompgrade);
		if (empty($grade))
			if (empty($compgrade))
				$grade = "NG";
			else
				throw new Exception("Unexpected input in compgrade");
		else if (is_numeric($grade))
			$grade = number_format($grade, 2); // make into 2 decimal places
		else if (ctype_alpha($grade))
			$grade = strtoupper($grade);
		if (preg_match('/^([1-2](\.([27]5|[05]0)))|([3-5](\.00))$/', $grade))
			; // leave grade as is
		else if (preg_match('/^DRP$|^NG$/', $grade))
			; // leave grade as is
		else if (preg_match('/^(4(\.00))$|^INC$/', $grade)) {
			if (empty($compgrade))
				; // leave grade as is
			else if (preg_match('/^([1-2](\.([27]5|[05]0)))|([3-5](\.00))$/', $compgrade))
				$grade = $compgrade;
		}
		else
			throw new Exception("Invalid input in grade");
		$this->values['grade'] = $grade;
	}
}
?>