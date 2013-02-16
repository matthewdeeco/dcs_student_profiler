<?php
require_once 'field.php';

class Grade extends Field {
	public function parse($grade, $compgrade, $secondcompgrade) {
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
		if (preg_match('/^([1-2](\.([27]5|[05]0)))|([3-5](\.00))$/', trim($grade)))
			; // leave grade as is
		else if (preg_match('/^DRP$|^NG|^P|^IP|^F$/', trim($grade)))
			; // leave grade as is
		else if (preg_match('/^(4(\.00))$|^INC$/', trim($grade))) {
			if (empty($compgrade))
				; // leave grade as is
			else if (preg_match('/^([1-2](\.([27]5|[05]0)))|([3-5](\.00))$/', trim($compgrade)))
				; // leave grade as is
		}
		else
			throw new Exception("Invalid input in grade");
		$this->values['grade'] = $grade;
	}
}
?>