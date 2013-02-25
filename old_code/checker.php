<?php

  class Checker extends CI_Model {
			
		function __construct() {
			parent::__construct();
		}
		
		protected function checkLastName($lastname) {
			if (empty($lastname))
				throw new Exception("Last name field cannot be empty!");
			else if (preg_match('/\d/', $lastname))
				throw new Exception("Last name field contains numeric characters!");
			else if (preg_match('/[^a-zA-Z\. \x{00D1}\x{00F1}]/u', $lastname))
				throw new Exception("Last name field contains non-alphabetic characters!");
			else
				return true;
		}
		
		protected function checkFirstName($firstname) {
			if (empty($firstname))
				throw new Exception("First name field cannot be empty!");
			else if (preg_match('/\d/', $firstname))
				throw new Exception("First name field contains numeric characters!");
			else if (preg_match('/[^a-zA-Z\. \x{00D1}\x{00F1}]/u', $firstname))
				throw new Exception("First name field contains non-alphabetic characters!");
			else
				return true;
		}
		
		protected function checkMiddleName($middlename) {
			if (preg_match('/\d/', $middlename))
				throw new Exception("Middle name field contains numeric characters!");
			else if (preg_match('/[^a-zA-Z\. \x{00D1}\x{00F1}]/u', $middlename))
				throw new Exception("Middle name field contains non-alphabetic characters!");
			else
				return true;
		}
		
		protected function checkPedigree($pedigree) {
			/*how to check this?*/
			return true;
		}
	
		protected function checkCurriculumName($curriculumname) {
			/*no particular restrictions*/
			if (empty($curriculumname))
				throw new Exception("Curriculum name field cannot be empty!");
			return true;
		}
		
		protected function checkCourseName($coursename) {
			$lowercoursename = strtolower($coursename);
			$query = "SELECT courseid FROM courses WHERE coursename = '$lowercoursename';";
			$result = $this->db->query($query);
			$row = $result->result_array();
			
			if (empty($coursename))
				throw new Exception("Course Name field cannot be empty!");
			else if(empty($row))
				throw new Exception("$coursename is not in the list of courses!");			
			else
				return true;
		}
		
		protected function checkCredits($credits) {
			if (empty($credits))
				throw new Exception("Credits field cannot be empty!");
			else if (!is_numeric($credits))
				throw new Exception("$credits must be numeric!");
				//check if integer?
			else
				return true;
		}
			
		protected function checkClassCode($classcode) {
			if (empty($classcode))
				throw new Exception("Class code field cannot be empty!");
			else if (preg_match('/[^\d]/', $classcode))
				throw new Exception("Class code field contains non-numeric characters!");
			/* else if (strlen($classcode) != 5)
				throw new Exception("Class code must be exactly 5 digits long"); */
			else
				return true;
		}
		
		protected function checkDomain($domain) {
			// $query = "SELECT courseid FROM courses WHERE domain = '$domain'";
			// $this->db->query($query);
			// $row = $result->result_array();
			
			if (empty($domain))
				throw new Exception("Domain field cannot be empty!");
			// else if(empty($row))
				// throw new Exception("$domain is not a valid domain!");
			else
				return true;
		}
		
		protected function checkCommType($domain) {
			/*What kind of checks?*/
			return true;
		}
		
		protected function checkStudentNo($studentno) {
				if (empty($studentno))
					throw new Exception("Student # field cannot be empty!");
				else if (preg_match('/[^\d]/', $studentno))
					throw new Exception("Student # field contains non-numeric characters!");
				else if (strlen($studentno) != 9)
					throw new Exception("Student # must be exactly 9 digits long!");
				else 
					return true;
		}
		
		protected function checkCurriculumId($curriculumid){
			$query = "SELECT * FROM curricula WHERE curriculumid = '$curriculumid'";
			$this->db->query($query);
			$row = $result->result_array();
		
			if (empty($row)) {
				throw new Exception("Curriculum id: $curriculumid does not exist!");
			}
			else
				return true;
		}
		
		protected function checkPersonId($personid){
			$query = "SELECT * FROM persons WHERE personid = '$personid'";
			$this->db->query($query);
			$row = $result->result_array();
		
			if (empty($row)) {
				throw new Exception("Person with personid: $personid does not exist!");
			}
			else{
				$query = "SELECT * FROM students WHERE personid = '$personid'";
				$this->db->query($query);
				$student_row = $result->result_array();
				
				$query = "SELECT * FROM instructors WHERE personid = '$personid'";
				$this->db->query($query);
				$instructor_row = $result->result_array();
				
				if (empty($student_row)) {
					throw new Exception("personid: $personid already references an existing student!");
				}	
				else if (empty($instructor_row)) {
					throw new Exception("personid: $personid already references an instructor!");
				}			
				else
					return true;
			}
		}
		
		protected function checkAcadYear(&$acadyear) {
			$acadyear = preg_replace('/ /', '', $acadyear); // remove spaces
			if (empty($acadyear)) // nothing was left
				throw new Exception("Acad Year field cannot be empty!");
			else if (preg_match('/[^\d\-]/', $acadyear))
				throw new Exception("Acad Year cannot contain non-numeric characters!");
			$acadyear = explode("-", $acadyear); // separate by hyphen (e.g. 2010-2011)
			$acadyear = array_filter($acadyear); // remove empty elements
			$acadyear = array_values($acadyear); // rearrange elements to remove gaps in index
			$start = $acadyear[0];
			if (count($acadyear) == 1) // no end year was specified
				$acadyear = $start."-".($start + 1);
			else { // end year was specified
				$end = $acadyear[1];
				if ($end - $start !== 1)
					throw new Exception("Start and end of Acad Year is not 1 year apart!");
				$acadyear = $start."-".$end;
			}
			return true;
		}
		
		protected function checkSemester($semester){
			$valid_semesters = array('1st','2nd', 'Sum');
		
			if (!in_array($semester, $valid_semesters)){
				throw new Exception("$semester is not a valid semester!");
			}
			else
				return true;
		}
		
		protected function checkIssettled($issettled){
			if (empty($row)) {
				throw new Exception("iissetled field cannot be empty!");
			}
			else if(!is_bool($issettled)){
				throw new Exception("$issettled is not boolean!");
			}
			else
				return true;
		}
		
		protected function checkTermId($termid){
			$query = "SELECT termid FROM terms WHERE termid = '$termid'";
			$this->db->query($query);
			$row = $result->result_array();
		
			if (empty($row)) {
				throw new Exception("Term with termid: $termid does not exist!");
			}
			else
				return true;
		}
		
		protected function checkCourseId($courseid){
			$query = "SELECT * FROM courses WHERE courseid = '$courseid'";
			$this->db->query($query);
			$row = $result->result_array();
		
			if (empty($row)) {
				throw new Exception("Course with courseid: $courseid does not exist!");
			}
			else
				return true;
		}
		
		protected function checkSection($section){
			if (strlen($section) > 7)
				throw new Exception("Section is too long");
			else
				return true;
		}
		
	}//end Checker class
?>
