<?php
	
	echo '<input type="text" value="sdfsdfj"/>';
	
	include_once "pg_connect.php";
	
	$table = $_POST['tableNum'];
	
	function printTable ($table, $num) {
		$query = "SELECT * FROM $table";
		$result = pg_query($conn, $query);
		
		while($row = pg_fetch_rows($result)) {
			echo '<tr>';
				for($i=0; $i<$num; $i++) {
					echo '<td> <input type="text" value=$row[i]/>';
					echo '</td>';	
				}
			echo '</tr>';
		}
	}
	
	if($table=="1") {
		 echo '<table>';
		 echo '<tr>';
				echo '<td>';
					echo 'Requirement ID';
		 		echo '</td>';
				echo '<td>';
					echo 'Requirement Name';
				echo '</td>';
				echo '<td>';
					echo 'Function Name';
				echo '</td>';
		 echo '</tr>';
		 printTable("requirements", 3);
		 echo '</table>';
	}
	
	else if($table==2) {
		echo '<table>';
		echo '<tr>';
			echo '<td>';
				echo 'Person ID';
			echo '</td>';
			echo '<td>';
				echo 'Lastname';
			echo '</td>';
			echo '<td>';
				echo 'Firstname';
			echo '</td>';
			echo '<td>';
				echo 'Middlename';
			echo '</td>';
			echo '<td>';
				echo 'Pedigree';
			echo '</td>';
		echo '</tr>';
		printTable("persons", 5);
		echo '</table>';
		
	}
	
	else if($table==3) {
		echo '<table>';
		echo '<tr>';
			echo '<td>';
				echo 'Instructor ID';
			echo '</td>';
			echo '<td>';
				echo 'Person ID';
			echo '</td>';
		echo '</tr>';
		printTable("instructors", 2);
		echo '</table>';
	}
	
	else if($table==4) {
		echo '<table>';
		echo '<tr>';
			echo '<td>';
				echo 'Curriculum ID';
			echo '</td>';
			echo '<td>';
				echo 'Curriculum Name';
			echo '</td>';
		echo '</tr>';
		printTable("curricula", 2);
		echo '</table>';
	}
	
	else if($table==5) {
		echo '<table>';
		echo '<tr>';
			echo '<td>';
				echo 'Student ID';
			echo '</td>';
			echo '<td>';
				echo 'Person ID';
			echo '</td>';
			echo '<td>';
				echo 'Student No.';
			echo '</td>';
			echo '<td>';
				echo 'Curriculum ID';
			echo '</td>';
		echo '</tr>';
		printTable("students", 4);
		echo '</table>';
	}
	
	else if($table==6) {
		echo '<table>';
		echo '<tr>';
			echo '<td>';
				echo 'Term ID';
			echo '</td>';
			echo '<td>';
				echo 'Name';
			echo '</td>';
			echo '<td>';
				echo 'Year';
			echo '</td>';
			echo '<td>';
				echo 'Semester';
			echo '</td>';
		echo '</tr>';
		printTable("terms", 4);
		echo '</table>';
	}
	
	else if($table==7) {
		echo '<table>';
		echo '<tr>';
			echo '<td>';
				echo 'Grade ID';
			echo '</td>';
			echo '<td>';
				echo 'Grade Name';
			echo '</td>';
			echo '<td>';
				echo 'Grade Value';
			echo '</td>';
		echo '</tr>';
		printTable("grades", 3);
		echo '</table>';
	}
	
	else if($table==8) {
		echo '<table>';
		echo '<tr>';
			echo '<td>';
				echo 'Course ID';
			echo '</td>';
			echo '<td>';
				echo 'Course Name';
			echo '</td>';
			echo '<td>';
				echo 'Credits';
			echo '</td>';
			echo '<td>';
				echo 'Domain';
			echo '</td>';
			echo '<td>';
				echo 'Communication Type';
			echo '</td>';
		echo '</tr>';
		printTable("courses", 5);
		echo '</table>';
	}
	
	else if($table==9) {
		echo '<table>';
		echo '<tr>';
			echo '<td>';
				echo 'Class ID';
			echo '</td>';
			echo '<td>';
				echo 'Term ID';
			echo '</td>';
			echo '<td>';
				echo 'Course ID';
			echo '</td>';
			echo '<td>';
				echo 'Section';
			echo '</td>';
			echo '<td>';
				echo 'Communication Type';
			echo '</td>';
		echo '</tr>';
		printTable("classes", 5);
		echo '</table>';	
	}
	
	else if($table==10) {
		echo '<table>';
		echo '<tr>';
			echo '<td>';
				echo 'Class ID';
			echo '</td>';
			echo '<td>';
				echo 'Term ID';
			echo '</td>';
			echo '<td>';
				echo 'Course ID';
			echo '</td>';
			echo '<td>';
				echo 'Section';
			echo '</td>';
			echo '<td>';
				echo 'Communication Type';
			echo '</td>';
		echo '</tr>';
		printTable("instructorclasses", 5);
		echo '</table>';
	}
	
	else if($table==11) {
		echo '<table>';
		echo '<tr>';
			echo '<td>';
				echo 'Student Term ID';
			echo '</td>';
			echo '<td>';
				echo 'Student ID';
			echo '</td>';
			echo '<td>';
				echo 'Term ID';
			echo '</td>';
			echo '<td>';
				echo 'Ineligibilities';
			echo '</td>';
			echo '<td>';
				echo 'Is Settled?';
			echo '</td>';
		echo '</tr>';
		printTable("studentterms", 5);
		echo '</table>';
	}
	
	else {
		echo '<table>';
		echo '<tr>';
			echo '<td>';
				echo 'Student Class ID';
			echo '</td>';
			echo '<td>';
				echo 'Student Term ID';
			echo '</td>';
			echo '<td>';
				echo 'Class ID';
			echo '</td>';
			echo '<td>';
				echo 'Grade ID';
			echo '</td>';
		echo '</tr>';
		printTable("studentclasses", 4);
		echo '</table>';
	}

?>