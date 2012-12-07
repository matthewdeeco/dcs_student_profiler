<?php

/** Holds a row of data to be added to the database. */
class QueryData {
	
	/** Holds the query data (acad year, last name, grade, etc. */
	private $data = array();
	
	/** Prints the query data in readable form. */
	public function printInfo() {
		echo "Student #: $this->studentno<br>";
		echo "Name: $this->lastname, $this->firstname $this->middlename $this->pedigree<br>";
		echo "Term: $this->termname<br>";
		echo "Class: $this->coursename $this->section<br>";
		echo "Class Code: $this->classcode<br>";
		echo "Grade: $this->grade<br>";
		echo "<br>";
	}
	
	// Groupmates, you don't need to understand everything else below, just leave it as is.
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
