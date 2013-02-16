<?php
require_once 'field.php';

class Classname extends Field {
	public function parse($classname, $a = null, $b = null) {
		if (empty($classname))
			throw new Exception("Class is empty");
		if (($lastspace = strrpos($classname, " ")) === false)
			throw new Exception("Course name and section cannot be distinguished");
		$coursename = substr($classname, 0, $lastspace);
		$section = substr($classname, $lastspace + 1);
		if (strlen($section) > 12)
			throw new Exception("Section is too long");
		$this->values['coursename'] = $coursename;
		$this->values['section'] = $section;
	}
}
?>