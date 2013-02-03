<?php

	include 'parser.php';
	
	class Edit_Database_Model extends CI_Model {
	
	public function __construct() {
	  parent::__construct();
	}	

		
	public function validateRowUpdate($tablename, $primarykeyname, $primarykeyvalue, $changedkeyname, $changedkeyvalue){
		if($tablename == 'persons'){
			$this->updatePersons($changedkeyname, $changedkeyvalue);
		}else if($tablename == 'curricula'){
			$this->updateCurricula($changedkeyname, $changedkeyvalue);
		}else if($tablename == 'courses'){
			$this->updateCourses($changedkeyname, $changedkeyvalue);
		}else if($tablename == 'students'){
			$this->updateStudents($changedkeyname, $changedkeyvalue);
		}else if($tablename == terms){
			$this->updateTerms($changedkeyname, $changedkeyvalue);
		}else if($tablename == studentterms){
			$this->updateStudentTerms($changedkeyname, $changedkeyvalue);
		}else if($tablename == classes){
			$this->updateClasses($changedkeyname, $changedkeyvalue);
		}else if($tablename == studentclasses){		//Pati ba 'to pwedeng mabago?
			$this->updateStudentClasses($changedkeyname, $changedkeyvalue);
		}
		
		this->updateRow($tablename, $primarykeyname, $primarykeyvalue, $changedkeyname, $changedkeyvalue);		
	}
	
	public function updateRow($tablename, $primarykeyname, $primarykeyvalue $changedkeyname, $changedkeyvalue)(
		$query = "UPDATE $tablename SET $changedkeyname='$changedkeyvalue' WHERE $primarykeyname='$primarykeyvalue'";
		$this->db->query($query);
	}
	
	public function updatePersons($changedkeyname, $changedkeyvalue){
		if($changedkeyname == 'lastname'){
			$this->parseLastName($changedkeyvalue);
		}else if($changedkeyname == 'firstname'){
			$this->parseFirstName($changedkeyvalue);
		}else if($changedkeyname == 'middlename'){
			$this->parseMiddleName($changedkeyvalue);
		}else if($changedkeyname == 'pedigree'){
			$this->parsePedigree($changedkeyvalue);
		}
	}	
	
	public function updateCurricula($changedkeyname, $changedkeyvalue){
		//$this->parseCurriculumName($changedkeyvalue)	THIS FUNCTION DOES NOT EXIST YET
	}
	
	public function updateStudents($changedkeyname, $changedkeyvalue){
		if($changedkeyname == 'studentno'){			
			$this->parseStudentNo($changedkeyvalue)			//THIS FUNCTION DOES NOT EXIST YET
		}else if($changedkeyname == 'curriculumid'){
			$this->parseCurriculumId($changedkeyvalue);		//THIS FUNCTION DOES NOT EXIST YET	
		}else if($changedkeyname == 'personid'){
			$this->parsePersonId(($changedkeyvalue);		//THIS FUNCTION DOES NOT EXIST YET, Can we really change this?
		}	
	}	
	
	public function updateCourses($changedkeyname, $changedkeyvalue){
		if($changedkeyname == 'coursename'){	
			$this->parseCourseName($changedkeyvalue);		//THIS FUNCTION DOES NOT EXIST YET
		}else if($changedkeyname == 'credits'){
			$this->parseCredits($changedkeyvalue);			//THIS FUNCTION DOES NOT EXIST YET
		}else if($changedkeyname == 'domain'){
			$this->parseDomain($changedkeyvalue);			//THIS FUNCTION DOES NOT EXIST YET
		}else if($changedkeyname == 'commtype'){
			$this->parseCommType($changedkeyvalue);			//THIS FUNCTION DOES NOT EXIST YET
		}
	}
	
	public function updateTerms($changedkeyname, $changedkeyvalue){
		if($changedkeyname == 'name'){
			$this->checkTermName($changedkeyvalue);			//THIS FUNCTION DOES NOT EXIST YET, different from the parseTermName in parser
		}else if($changedkeyname == 'year'){
			$this->checkAcadYear($changedkeyvalue);			//THIS FUNCTION DOES NOT EXIST YET, different from the parseAcadYear in parser
		}else if($changedkeyname == 'sem'){
			$this->checkSemester($changedkeyvalue);			//THIS FUNCTION DOES NOT EXIST YET
		}
	}
	
	public function updateStudentTerms($changedkeyname, $changedkeyvalue){
		if($changedkeyname == 'termid'){
			$this->checkTermId($changedkeyvalue);			//THIS FUNCTION DOES NOT EXIST YET
		}else if($changedkeyname == 'ineligibilities'){
			$this->checkIneligibilities($changedkeyvalue);	//THIS FUNCTION DOES NOT EXIST YET
		}else if($changedkeyname == 'issettled'){
			$this->checkIssettled($changedkeyvalue);		//THIS FUNCTION DOES NOT EXIST YET
		}
	}
	
	public function updateClasses($changedkeyname, $changedkeyvalue){
		if($changedkeyname == 'termid'){
			$this->checkTermId($changedkeyvalue);		//THIS FUNCTION DOES NOT EXIST YET
		}else if($changedkeyname == 'courseid'){
			$this->checkCourseId($changedkeyvalue);		//THIS FUNCTION DOES NOT EXIST YET
		}else if($changedkeyname == 'section'){
			$this->checkSection($changedkeyvalue);		//THIS FUNCTION DOES NOT EXIST YET
		}else if(($changedkeyname == 'classcode'){
			$this->checkClassCode($changedkeyvalue);	//THIS FUNCTION DOES NOT EXIST YET
		}
	}
	
	
	/*
	function updateCourses(){
		$coursename = $_POST['coursename'];
		$credits = $_POST['credits'];
		$domain = $_POST['domain'];
		$commtype = $_POST['commtype'];
		
		//check if course already exists
		$query = "SELECT * FROM students WHERE coursename == $coursename";
		$result = $this->db->query($query);
		$row = $result->result_array();
		
		//new entry 
		if(empty($row)){
			$query = "INSERT INTO courses(coursename, credits, domain, commtype) VALUES ($coursename, $credits, $domain, $commtype);"
			$result = $this->db->query($query);
		} else{
			$query = "UPDATE courses SET coursename=$coursename, credits=$credits, domain=$domain, commtype=$commtype);"
			$result = $this->db->query($query);
		}
	}
	
	function updateStudentClasses(){
		$studenttermid = $_POST['studenttermid'];
		$classid = $_POST['classid'];
		$gradeid = $_POST['gradeid'];
		
		//check if studentclasses already exists
		$query = "SELECT * FROM studentsclasses WHERE studenttermid == $studenttermid;"
		$result = $this->db->query($query);
		$row = $result->result_array();
		
		//new entry 
		if(empty($row)){
			$query = "INSERT INTO studentclasses(studenttermid, classid, gradeid) VALUES ($studenttermid, $classid, $gradeid);"
			$result = $this->db->query($query);
		} else{
			$query = "UPDATE studentclasses SET studenttermid=$studenttermid, classid=$classid, gradeid=$gradeid);"
			$result = $this->db->query($query);
		}
	}
	
	function updateStudents(){
		$personid = $_POST['personid'];
		$lastname = $_POST['lastname'];
		$firstname = $_POST['firstname'];
		$middlename = $_POST['middlename'];
		$pedigree = $_POST['pedigree'];
		$studentno = $_POST['studentno'];
		$curriculumid = $_POST['curriculumid'];
		
		//check for error in fields - Use parser class
	
		//check if student already exists
		$query = "SELECT * FROM students WHERE personid == $personid";
		$result = pg_query($conn, $query);
		$num_rows = pg_numrows($result);
		
		//new entry 
		if($num_rows != 1){
			//insert into persons table first
			$query = "INSERT INTO persons(lastname, firstname, middlename, pedigree) VALUES ($lastname, $firstname, $middlename, $pedigree RETURNING personid";
			$result = pg_query($conn, $query);
			$row = pg_fetch_row($result);
			
			$personid = $row['personid'];			
			//insert into students table
			$query = "INSERT INTO students(personid, studentno, curriculumid) VALUES ($personid, $studentno, $curriculumid)";
			$result = pg_query($conn, $query);
		}else{
			//edit already existing tuple in persons and students table
			$query = "UPDATE persons SET lastname = $lastname, firstname = $firstname, middlename = $middlename, pedigree = $pedigree WHERE personid = $personid";
			$result = pg_query($conn, $query);
			
			$query = "UPDATE students SET studentno = $studentno, curriculumid = $curriculumid WHERE personid = $personid";
		
		}	
	}
	
	function updateInstructors(){
		$personid = $_POST['personid'];
		$lastname = $_POST['lastname'];
		$firstname = $_POST['firstname'];
		$middlename = $_POST['middlename'];
		$pedigree = $_POST['pedigree'];
		
		//check for error in fields - Use parser class
	
		//check if instructor already exists
		$query = "SELECT * FROM instructors WHERE personid == $personid";
		$result = pg_query($conn, $query);
		$num_rows = pg_numrows($result);
		
		//new entry
		if($num_rows != 1){
			//insert into persons table first
			$query = "INSERT INTO persons(lastname, firstname, middlename, pedigree) VALUES ($lastname, $firstname, $middlename, $pedigree RETURNING personid";
			$result = pg_query($conn, $query);
			$row = pg_fetch_row($result);
			
			$personid = $row['personid'];			
			//insert into instructors table
			$query = "INSERT INTO instructors(personid) VALUES ($personid)";
			$result = pg_query($conn, $query);
		}else{
			//edit already existing tuple in persons instructors table
			$query = "UPDATE persons SET lastname = $lastname, firstname = $firstname, middlename = $middlename, pedigree = $pedigree WHERE personid = $personid";
			$result = pg_query($conn, $query);		
			
			//result cascades to instructors table
		}
	}
	
	function updateTerms(){
  	$termid = $_POST['termid'];
		$name = $_POST['name'];
		$year = $_POST['year'];
		$sem = $_POST['sem'];

		//check if term already exists
		$query = "SELECT * FROM terms WHERE name == $name";
		$result = $this->db->query($query);
		$row = $result->result_array();

		//new entry 
		if(empty($row)){
			$query = "INSERT INTO terms(name, year, sem) VALUES ($name, $year, $sem);"
			$result = $this->db->query($query);
		} else{
			$query = "UPDATE terms SET name=$name, year=$year, sem = $sem WHERE termid = $termid ;"
			$result = $this->db->query($query);
		}
	}

	function updateClasses(){
		$termid = $_POST['termid'];
		$courseid = $_POST['courseid'];
		$section = $_POST['section'];
		$classcode = $_POST['classcode'];

		//check if class already exists
		$query = "SELECT * FROM classes WHERE section == $section AND courseid == $courseid;"
		$result = $this->db->query($query);
		$row = $result->result_array();

		//new entry 
		if(empty($row)){
			$query = "INSERT INTO classes(termid, courseid, section, classcode) VALUES ($termid, $courseid, $section, $classcode);"
			$result = $this->db->query($query);
		} else{
			$query = "UPDATE classes SET termid=$termid, courseid=$courseid, section=$section, classcode=$classcode WHERE classid=$classid;"
			$result = $this->db->query($query);
		}
	}
	*/
?>