<div id="uploadgrades">
<?php
if (!isset($success));
else if ($success) {
	echo "<span class='success'>File successfully uploaded<br></span>";
	echo $success_message;
	echo $excel_dump;
	echo "<br>";
	// echo $parse_output;
	// echo "<br>";
} else if (!$success) {
	echo "<span class='error'>$errormessage</span><br><br>";
}
?>
<table width="50%" class="noborder">
	<form enctype="multipart/form-data" action="<?=$dest?>" method="POST">
		<tr><strong><?=$message?></strong></tr>
		<tr>
			<td>&nbsp;Upload File:</td>
			<td><input type="file" id="upload_file" name="upload_file" /></td>
		</tr>
		<tr>
			<td></td>
			<td>
				<input type="submit" name="uploadfile" value="Submit" />
				<input type="reset" name="cancel" value="Cancel"/>
			</td>
		</tr>
	</form>
</table>
</div>