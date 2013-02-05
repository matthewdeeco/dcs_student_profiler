<?php
if (!isset($success));
else if ($success) {
	echo "<span class='success'>File successfully uploaded<br></span><br>";
	echo "<br><b><span class='success'>$success_rows</span></b> rows added, ";
	echo "<b><span class='error'>$error_rows</span></b> rows with errors. ";
	echo $excel_dump;
	if($error_rows > 0){
		echo "<br>Rows with errors:<br>";
		echo $parse_output;
		echo "<br><br>";
		echo "<input type=\"submit\" name=\"updatefile\" value=\"Update\" />";
		echo "<input type=\"reset\" name=\"cancel\" value=\"Cancel\" />";
	}
	else echo "<br>Upload complete! There are no rows with errors.<br>";
} else if (!$success) {
	echo "<span class='error'>$errormessage</span><br><br>";
}
?>
