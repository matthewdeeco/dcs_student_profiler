<?php
	
	$grades_spreadsheet = $_FILES['gradessheet']['name'];
	$spreadsheet_type = $_FILES['gradessheet']['type'];
	$spreadsheet_size = $_FILES['gradessheet']['size'];
	
	/*customize filename for ease of access?*/
	
	echo "$spreadsheet_type<br>";
	
	/*check for filetypes that are allowed*/
	
	$target =  "gradesfiles/".$grades_spreadsheet;	
	
	if (move_uploaded_file($_FILES['gradessheet']['tmp_name'], $target)) {
			  
		/*maintain a table to store uploaded gradessheets?*/
		
		echo "File successfully uploaded.\nParsing about to begin.<br><br>";
		
		/*begin parsing*/
		require_once 'excel_parser.php';
		$parser = new ExcelParser($target);
		$parser->parse();
	}
	else{
		$upload_error = "file upload";
		echo "There seems to be a problem with uploading the file.";
	}
?>