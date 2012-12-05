<?php

class QueryData {
	
	private $data = array();
	
	function printInfo() {
		echo "Student #: $this->studentno<br>";
		echo "Name: $this->lastname, $this->firstname $this->middlename $this->pedigree<br>";
		echo "Acad Year: $this->acadyear<br>";
		echo "Semester: $this->semester<br>";
		echo "Class: $this->coursename $this->section<br>";
		echo "Class Code: $this->classcode<br>";
		echo "Grade: $this->grade<br>";
		echo "<br>";
	}
	
	// From http://php.net/manual/en/language.oop5.overloading.php#object.get
	public function __get($name) {
		if (isset($this->data[$name]))
			return $this->data[$name];
	} 

	public function __set($name, $value) {
		$this->data[$name] = $value;
	} 

	public function __isset($name) {
		return isset($this->data[$name]); 
	} 

	public function __unset($name) {
		unset($this->data[$name]); 
	}
}

?>
