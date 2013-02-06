<?php
	require_once 'fieldchecker.php';
	
	  $checker = new FieldChecker();
	
	class Edit_Database_Model extends Checker {
	
	public function __construct() {
	  parent::__construct();
	}	

		
	public function validateRowUpdate($tablename, $primarykeyname, $primarykeyvalue, $changedkeyname, $changedkeyvalue){
<<<<<<< HEAD
=======
		
>>>>>>> 9d575be603dddc25c6087cf06547c16041eb7498
		if($tablename == 'persons'){
			$this->checkPersonsField($changedkeyname, $changedkeyvalue);
		}else if($tablename == 'curricula'){
			$this->checkCurricula($changedkeyname, $changedkeyvalue);
		}else if($tablename == 'courses'){
			$this->checkCourses($changedkeyname, $changedkeyvalue);
		}else if($tablename == 'students'){
			$this->checkStudents($changedkeyname, $changedkeyvalue);
		}else if($tablename == 'terms'){
			$this->checkTerms($changedkeyname, $changedkeyvalue);
		}else if($tablename == 'studentterms'){
			$this->checkStudentTerms($changedkeyname, $changedkeyvalue);
		}else if($tablename == 'classes'){
			$this->checkClasses($changedkeyname, $changedkeyvalue);
		}/*else if($tablename == 'studentclasses'){		//Pati ba 'to pwedeng mabago?
			$this->checkStudentClasses($changedkeyname, $changedkeyvalue);
		}
		
		$this->updateRow($tablename, $primarykeyname, $primarykeyvalue, $changedkeyname, $changedkeyvalue);		
	}
	
	public function updateRow($tablename, $primarykeyname, $primarykeyvalue,$changedkeyname, $changedkeyvalue){
		$query = "UPDATE $tablename SET $changedkeyname='$changedkeyvalue' WHERE $primarykeyname='$primarykeyvalue'";
		$this->db->query($query);
		$this->db->_error_message();
	}

	public function checkPersonsField($changedkeyname, $changedkeyvalue){
		if($changedkeyname == 'lastname'){
			$checker->checkLastName($changedkeyvalue);
		}else if($changedkeyname == 'firstname'){
			$checker->checkFirstName($changedkeyvalue);
		}else if($changedkeyname == 'middlename'){
			$checker->checkMiddleName($changedkeyvalue);
		}else if($changedkeyname == 'pedigree'){
			$checker->checkPedigree($changedkeyvalue);
		}
	}	
	
	public function checkCurricula($changedkeyname, $changedkeyvalue){
		$checker->checkCurriculumName($changedkeyvalue);
	}
	
	public function checkCourses($changedkeyname, $changedkeyvalue){
		if($changedkeyname == 'coursename'){	
			$checker->checkCourseName($changedkeyvalue);		
		}else if($changedkeyname == 'credits'){
			$checker->checkCredits($changedkeyvalue);			
		}else if($changedkeyname == 'domain'){
			$checker->checkDomain($changedkeyvalue);			
		}else if($changedkeyname == 'commtype'){
			$checker->checkCommType($changedkeyvalue);			//THIS FUNCTION DOES NOT EXIST YET
		}
	}
	
	public function checkStudents($changedkeyname, $changedkeyvalue){
		if($changedkeyname == 'studentno'){			
			$checker->checkStudentNo($changedkeyvalue);	
		}else if($changedkeyname == 'curriculumid'){
			$checker->checkCurriculumId($changedkeyvalue);		
		}else if($changedkeyname == 'personid'){
			$checker->checkPersonId($changedkeyvalue);		
		}	
	}	
	
	public function checkTerms($changedkeyname, $changedkeyvalue){
		if($changedkeyname == 'name'){
			$checker->checkTermName($changedkeyvalue);			//THIS FUNCTION DOES NOT EXIST YET, different from the checkTermName in checkr
		}else if($changedkeyname == 'year'){
			$checker->checkAcadYear($changedkeyvalue);			
		}else if($changedkeyname == 'sem'){
			$checker->checkSemester($changedkeyvalue);			
		}
	}
	
	public function checkStudentTerms($changedkeyname, $changedkeyvalue){
		if($changedkeyname == 'termid'){
			$checker->checkTermId($changedkeyvalue);			
		}else if($changedkeyname == 'ineligibilities'){
			$checker->checkIneligibilities($changedkeyvalue);	//THIS FUNCTION DOES NOT EXIST YET
		}else if($changedkeyname == 'issettled'){
			$checker->checkIssettled($changedkeyvalue);		
		}
	}
	
	public function checkClasses($changedkeyname, $changedkeyvalue){
		if($changedkeyname == 'termid'){
			$checker->checkTermId($changedkeyvalue);		
		}else if($changedkeyname == 'courseid'){
			$checker->checkCourseId($changedkeyvalue);		
		}else if($changedkeyname == 'section'){
			$checker->checkSection($changedkeyvalue);		
		}else if($changedkeyname == 'classcode'){
			$checker->checkClassCode($changedkeyvalue);	
		}
	}
	
	
	/*
	function checkCourses(){
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
		
		//check for error in fields - Use checkr class
	
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
		
		//check for error in fields - Use checkr class
	
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
	}
?>
