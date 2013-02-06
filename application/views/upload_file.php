<?php
if (!isset($success));
else if ($success) {
	echo "<span class='success'>File successfully uploaded<br></span><br>";
} else if (!$success) {
	echo "<span class='error'>$errormessage</span><br><br>";
}
?>
<form id="upload_form" enctype="multipart/form-data" action="<?=$dest?>" method="POST">
	<table width="50%" class="noborder">
		<tr><strong><?=$message?></strong></tr>
		<tr>
			<td>&nbsp;Upload File:</td>
			<td><input type="file" id="upload_file" name="upload_file" /></td>
		</tr>
		<tr>
			<td></td>
			<td>
				<input type="submit" name="submit" value="Submit" />
				<input type="reset" name="cancel" value="Cancel"/>
			</td>
		</tr>
	</table>
</form>
<?php
if (isset($success) && $success) {
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
	
	
}
?>