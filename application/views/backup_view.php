<div id="backup">
<table width="50%" class="noborder">
	<form enctype="multipart/form-data" action="<?=site_url('update_statistics/performBackup')?>" method="POST">
		<tr>
			<td>&nbsp;Enter the location of pg_dump: </td>
			<td><input type="text" id="pg_dump_location" name="pg_dump_location" /></td>
		</tr>
		<tr>
			<td></td>
			<td>
				<input type="submit" name="pgdumplocation" value="Submit" />
				<input type="reset" name="cancel" value="Cancel"/>
			</td>
		</tr>
	</form>
</table>
</div>
