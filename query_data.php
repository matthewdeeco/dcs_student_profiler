<?php

require_once ('pg_connect.php');

/** Holds a row of data to be added to the database. */
class QueryData {
	
	/** Holds the query data (acad year, last name, grade, etc. */
	private $data = array();
	
	/** Prints the query data in readable form. */
	public function printInfo() {
		echo "
		<td>$this->termname</td>
		<td>$this->studentno</td>
		<td>$this->firstname $this->middlename $this->lastname $this->pedigree</td>
		<td>$this->coursename</td>
		<td>$this->section</td>
		<td>$this->classcode</td>
		<td>$this->grade</td>";
	}
	
	public function addToDatabase() {
		/*Persons Table*/
		$sql = "INSERT INTO persons(lastname, firstname, middlename, pedigree) VALUES('$this->lastname', '$this->firstname', '$this->middlename', '$this->pedigree');"
		$result = pg_query($conn, $sql);
		
		/*Students Table*/
		//find person
		$sql = "SELECT personid FROM persons WHERE lastname = '$this->lastname' AND firstname = '$this->firstname' AND middlename = '$this->middlename' AND pedigree='$this->pedigree';"
		$result = pg_query($conn, $sql);
		$row = pg_fetch_array($result);
		$personid = $row['personid'];
		
		//get batch for curriculumid
		$batch = substr($this->studentno, 0, 4);
		if($batch = '2010' || $batch = '2011')
			$curriculumid = 2;
		else
			$curriculumid = 1;
		
		//insert student
		$sql = "INSERT INTO students(personid, studentno, curriculumid) VALUES($personid, '$this->studentno', $curriculumid);"
		$result = pg_query($conn, $sql);
		
		/*Terms Table -- insert every sem a new entry*/
		
		/*Classes Table*/
		//get termid
		$sql = "SELECT termid FROM terms WHERE year = '$this->acadyear' AND sem = '$this->semester';"
		$result = pg_query($conn, $sql);
		$row = pg_fetch_array($result);
		$termid = $row['termid'];
		
		//get courseid
		$sql = "SELECT courseid FROM courses WHERE coursename = '$this->coursename';"
		$result = pg_query($conn, $sql);
		$row = pg_fetch_array($result);
		$courseid = $row['courseid'];
		
		//insert class
		$sql = "INSERT INTO classes(termid, courseid, section, classcode) VALUES($termid, $courseid, '$this->section', '$this->classcode');"
		$result = pg_query($conn, $sql);
		
		/*Student Terms Table*/
		//get studentid
		$sql = "SELECT studentid FROM students WHERE studentno = '$this->studentno';"
		$result = pg_query($conn, $sql);
		$row = pg_fetch_array($result);
		$studentid = $row['studentid'];
		
		//insert studentterms
		$sql = "INSERT INTO studentterms(studentid, termid, ineligibilities, issettled) VALUES($studentid, $termid, 'N/A', TRUE);"
		$result = pg_query($conn, $sql);
		
		/*Student Classes Table*/
		//get studenttermid
		$sql = "SELECT studenttermid FROM studentterms WHERE studentid = $studentid;"
		$result = pg_query($conn, $sql);
		$row = pg_fetch_array($result);
		$studenttermid = $row['studenttermid'];
		
		//get classid
		$sql = "SELECT classid FROM classes WHERE section = '$this->section' AND classcode = '$this->classcode';"
		$result = pg_query($conn, $sql);
		$row = pg_fetch_array($result);
		$classid = $row['classid'];
		
		//get gradeid
		$sql = "SELECT gradeid FROM grades WHERE gradevalue = $this->grade;"
		$result = pg_query($conn, $sql);
		$row = pg_fetch_array($result);
		$gradeid = $row['gradeid'];
		
		//insert student classes
		$sql = "INSERT INTO studentclasses(studenttermid, classid, gradeid) VALUES($studenttermid, $classid, $gradeid);"
		$result = pg_query($conn, $sql);
	
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
