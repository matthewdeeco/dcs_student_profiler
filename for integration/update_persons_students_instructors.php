<?php

	include "pg_connect.php";

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

?>