<?php

/** Holds a row of data to be added to the database. */
class Query_Data extends CI_Model {
	
	public function __construct() {
	  parent::__construct();
	}

	/** Holds the query data (acad year, last name, grade, etc. */
	private $data = array();
	/* Data list:
		acadyear	semester	termname	studentno
		firstname	middlename	lastname	pedigree
		classcode	coursename	section		grade
	*/
	
	/** Prints the query data in readable form. */
	public function tostring() {
		return "
		<td>$this->termname</td>
		<td>$this->studentno</td>
		<td>$this->firstname $this->middlename $this->lastname $this->pedigree</td>
		<td>$this->classcode</td>
		<td>$this->coursename</td>
		<td>$this->section</td>
		<td>$this->grade</td>";
	}
	
	private function get_personid() {
		$query = "SELECT personid FROM persons WHERE lastname = '$this->lastname' AND firstname = '$this->firstname' AND middlename = '$this->middlename' AND pedigree='$this->pedigree';";
		$result = $this->db->query($query);
		$row = $result->result_array();
		
		if (empty($row)) {
			$query = "INSERT INTO persons(lastname, firstname, middlename, pedigree) VALUES ('$this->lastname', '$this->firstname', '$this->middlename', '$this->pedigree');";
			$result = $this->db->query($query);
			$query = "SELECT personid FROM persons WHERE lastname = '$this->lastname' AND firstname = '$this->firstname' AND middlename = '$this->middlename' AND pedigree='$this->pedigree';";
			$result = $this->db->query($query);
			$row = $result->result_array();
		}
		$personid = $row[0]['personid'];
		return $personid;
	}
	
	private function get_termid() {
		$query = "SELECT termid FROM terms WHERE termid = '$this->termid' AND year = '$this->acadyear' AND sem = '$this->semester';";
		$result = $this->db->query($query);
		$row = $result->result_array();
		
		if (empty($row)) {
			$query = "INSERT INTO terms VALUES ('$this->termid', '$this->termname', '$this->acadyear', '$this->semester');";
			$result = $this->db->query($query);
		}
		return $this->termid;
	}
	
	private function get_curriculumid() {
		$query = "SELECT curriculumid FROM curricula WHERE curriculumname='new'";
		$result = $this->db->query($query);
		$row = $result->result_array();
		$new = $row[0]['curriculumid'];
		$query = "SELECT curriculumid FROM curricula WHERE curriculumname='old'";
		$result = $this->db->query($query);
		$row = $result->result_array();
		$old = $row[0]['curriculumid'];
			
		if($this->batch == '2010' || $this->batch == '2011')
			return $old;
		else
			return $new;
	}
	
	private function insertToStudents() {
		$personid = $this->get_personid();
		$curriculumid = $this->get_curriculumid();
		//insert student
		$query = "INSERT INTO students(personid, studentno, curriculumid) VALUES($personid, $this->studentno, $curriculumid);";
		$result = $this->db->query($query);
	}
	
	public function execute() {
		$this->insertToStudents();
		
		/*Classes Table*/
		//get termid
		$termid = $this->get_termid();
		
		//get courseid
		$lowercoursename = strtolower($this->coursename);
		$query = "SELECT courseid FROM courses WHERE coursename = '$lowercoursename';";
		$result = $this->db->query($query);
		$row = $result->result_array();
		$courseid = $row[0]['courseid'];
		
		//insert class
		$query = "INSERT INTO classes(termid, courseid, section, classcode) VALUES($termid, $courseid, '$this->section', '$this->classcode');";
		$result = $this->db->query($query);
		
		/*Student Terms Table*/
		//get studentid
		$query = "SELECT studentid FROM students WHERE studentno = '$this->studentno';";
		$result = $this->db->query($query);
		$row = $result->result_array();
		$studentid = $row[0]['studentid'];
		
		//insert studentterms
		$query = "INSERT INTO studentterms(studentid, termid, ineligibilities, issettled) VALUES($studentid, $termid, 'N/A', TRUE);";
		$result = $this->db->query($query);
		
		/*Student Classes Table*/
		//get studenttermid
		$query = "SELECT studenttermid FROM studentterms WHERE studentid = $studentid;";
		$result = $this->db->query($query);
		$row = $result->result_array();
		$studenttermid = $row[0]['studenttermid'];
		
		//get classid
		$query = "SELECT classid FROM classes WHERE section = '$this->section' AND classcode = '$this->classcode';";
		$result = $this->db->query($query);
		$row = $result->result_array();
		$classid = $row[0]['classid'];
		
		//get gradeid
		$query = "SELECT gradeid FROM grades WHERE gradename = '$this->grade';";
		$result = $this->db->query($query);
		$row = $result->result_array();
		$gradeid = $row[0]['gradeid'];
		
		//insert student classes
		$query = "INSERT INTO studentclasses(studenttermid, classid, gradeid) VALUES($studenttermid, $classid, $gradeid);";
		$result = $this->db->query($query);
	
	}
	
	// Groupmates, you don't need to understand everything else below, just leave it as is.
	// From http://php.net/manual/en/language.oop5.overloading.php#object.get
	public function __get($name) {
		if ($name === "db")
			return parent::__get($name);
		else
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
