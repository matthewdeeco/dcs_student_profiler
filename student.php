<?php

class Student {
	
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
	
	function setAcadYear($acadyear) {
		$this->acadyear = $acadyear;
	}
	
	function setSemester($semester) {
		$this->semester = $semester;
	}
	
	function setStudentNo($studentno) {
		$this->studentno = $studentno;
	}
	
	function setLastName($lastname) {
		$this->lastname = $lastname;
	}
	
	function setFirstName($firstname) {
		$this->firstname = $firstname;
	}
	
	function setMiddleName($middlename) {
		$this->middlename = $middlename;
	}
	
	function setPedigree($pedigree) {
		$this->pedigree = $pedigree;
	}
	
	function setClassCode($classcode) {
		$this->classcode = $classcode;
	}
	
	function setCourseName($coursename) {
		$this->coursename = $coursename;
	}
	
	function setSection($section) {
		$this->section = $section;
	}
	
	function setGrade($grade) {
		$this->grade = $grade;
	}

}

?>
