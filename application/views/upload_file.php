<?php
$dest = site_url('update_statistics/upload');
echo '<div id="uploadgrades"><h2></h2>
<table width="50%">
	<form enctype="multipart/form-data" action='.$dest.' method="POST">
		<tr><strong>Select the xls file with grades to be uploaded!</strong></tr>
		<tr>
			<td>Grades Spreadsheet:</td>
			<td><input type="file" id="gradessheet" name="gradessheet" /></td>
		</tr>
		<tr>
		<td></td>
		<td>
		<input type="submit" name="uploadgrades" value="Submit" /><input type="reset" name="cancel" value="Cancel"/></td>
		</tr>
	</form>
</table>
</div>';
?>