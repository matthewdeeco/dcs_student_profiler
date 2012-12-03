<?php

	error_reporting(E_ALL ^ E_NOTICE);
	require_once 'excel_reader.php';
	require_once 'fields.php';
	require_once 'student.php';
	
	class ExcelParser {
	
		function ExcelParser($excelFile) {
			$this->spreadsheet = new Spreadsheet_Excel_Reader($excelFile);
		}
		
		function parse() {
			$rows = $this->spreadsheet->rowcount();
			
			// If 1st row is not a header, change to $i = 1
			for ($i = 2; $i <= $rows; $i++) {
				try {
					$student = $this->parseRow($i);
					$student->printInfo();
					// add $student to db
				} catch (Exception $e) {
					echo "Row $i has an error: ".$e->getMessage()."<br><br>";
				}
			}
		}
			
		private function parseRow($row) {
			$student = new Student();
			
			// parse acad year
			$acadyear = $this->spreadsheet->val($row, Fields::AcadYear);
			$acadyear = preg_replace('/[^\d]/', '', $acadyear, 1); // skip to first numeric char
			$acadyear = preg_replace('/[^\d\-]/', '', $acadyear); // strip all other non-numeric and non-hyphen chars
			if (empty($acadyear))
				throw new Exception("Acad Year has no numeric characters");
			$acadyear = explode("-", $acadyear); // separate by hyphen (e.g. 2010-2011)
			$start = $acadyear[0];
			if (count($acadyear) == 1)
				$student->setAcadYear($start."-".($start + 1));
			else {
				$end = $acadyear[1];
				if ($end - $start !== 1)
					throw new Exception("Start and end of Acad Year is not 1 year apart");
				$student->setAcadYear($start."-".$end);
			}
			
			// parse semester
			$semester = $this->spreadsheet->val($row, Fields::Semester);
			if (empty($semester))
				throw new Exception("Semester cannot be blank");
			if (strcasecmp($semester, 'First') == 0)
				$student->setSemester(1);
			else if (strcasecmp($semester, 'Second') == 0)
				$student->setSemester(2);
			else if (strcasecmp($semester, 'Summer') == 0)
				$student->setSemester(3);
			else {
				$semester = preg_replace('/[^\d]/', '', $semester); // strip non-numeric chars
				if ($semester >= 1 && $semester <= 3)
					$student->setSemester($semester);
				else
					throw new Exception("Semester is invalid");
			}
			
			// parse student no
			$studentno = $this->spreadsheet->val($row, Fields::StudentNo);
			if (empty($studentno))
				throw new Exception("Student no cannot be blank");
			$studentno = str_replace("-", "", $studentno);
			if (strlen($studentno) != 9)
				throw new Exception("Student no must be exactly 9 digits long");
			$student->setStudentNo($studentno);
			
			$lastname = $this->spreadsheet->val($row, Fields::LastName);
			// parse last name code here
			$student->setLastName($lastname);
			
			$firstname = $this->spreadsheet->val($row, Fields::FirstName);
			// parse first name
			$student->setFirstName($firstname);
			
			$middlename = $this->spreadsheet->val($row, Fields::MiddleName);
			// parse middle name
			$student->setMiddleName($middlename);
			
			$pedigree = $this->spreadsheet->val($row, Fields::Pedigree);
			// parse pedigree
			$student->setPedigree($pedigree);
			
			$classcode = $this->spreadsheet->val($row, Fields::ClassCode);
			// parse class code
			$student->setClassCode($classcode);
			
			// parse course name and section
			$classname = $this->spreadsheet->val($row, Fields::ClassName);
			if (empty($classname))
				throw new Exception("Class is empty");
			if ($lastspace = strrpos($classname, " ") == false)// no spaces
				throw new Exception("Course name and section cannot be distinguished");
			$coursename = substr($classname, 0, -$lastspace);
			$section = substr($classname, $lastspace);
			// check if $coursename is in table?
			$student->setCourseName($coursename);
			$student->setSection($section);
			
			$grade = $this->spreadsheet->val($row, Fields::Grade);
			$compgrade = $this->spreadsheet->val($row, Fields::CompGrade);
			$secondcompgrade = $this->spreadsheet->val($row, Fields::SecondCompGrade);
			// parse grade
			$student->setGrade($grade);
			
			return $student;
		}
	}

?>
