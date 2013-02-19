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
			throw new Exception("Section is longer than 12 characters");
			
		$mscourses = '/^app physics |^bio |^chem |^env sci |^geol |^math |^mbb |^ms |^physics |^che |^ce |^coe |^ee |^eee |^ece |^ge |^ie |^mate |^me |^mete |^em /i';
		if(preg_match('/^cs /i', $coursename))
			$domain = "CSE";
		else if(preg_match($mscourses, $coursename))
			$domain = "MSEE";
		else
			$domain = "FE";
			
		$this->values['coursename'] = $coursename;
		$this->values['section'] = $section;
		$this->values['domain'] = $domain;
	}
}
?>