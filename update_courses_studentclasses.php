<?php


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

?>
