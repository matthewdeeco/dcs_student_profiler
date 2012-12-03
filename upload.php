<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>DCS Student Profiler</title>
</head>

<body>

	<?php uploadgrades() ?>
	
</body>
<?php

function uploadgrades(){
	echo '<div id="uploadgrades"><h2></h2>
		<table width="50%">
		<form enctype="multipart/form-data" action="upload_response.php" method="POST">
			<tr><strong>Select the xls file with grades to be uploaded!</strong></tr>
			<tr>
								
		
				<td>Grades Spreadsheet:</td>
				<td>:</td>
				<td><input type="file" id="gradessheet" name="gradessheet" /></td>
			</tr>
			<tr>	
			<td></td>
			<td></td>
			<td>
			<input type="submit" name="uploadgrades" value="Submit" /><input type="reset" name="cancel" value="Cancel"/></td>
			</tr>
		</form>
		</table>
	</div> <!--END OF uploadgrades ID-->';
		
	}
?>