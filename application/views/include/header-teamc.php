<script>
$(document).ready(function(){
	$('a.teamcnav').click(function(e) {
		// prevent the default action when a nav button link is clicked
		e.preventDefault();
		// ajax query to retrieve the HTML view without refreshing the page.
		$('#container').load($(this).attr('href'));
	});
	// $('form').submit(function(e) {
		// e.preventDefault();
		// action = $(this).attr('action');
		// alert(action);
		// $.ajaxFileUpload({
			// url: action,
			// secureuri: false,
			// fileElementId: 'upload_file',
			// dataType: 'json',
			// data: {title:''},
			// success: function(data, status){
				// alert("Success");
				// $('#container').load(action);
			// },
			// error: function() {
				// alert("There was an error with form submission.");
			// }
		// });
	// });
});
</script>
<div id="sidebar" style ="
	width:13%;
	padding-left: 5px;
	">
<a class="teamcnav" href="<?= site_url("update_statistics/upload") ?>">Upload</a><br>
Edit
<ul>
<?php
$url = site_url("update_statistics/edit");
foreach ($table_names as $table_name) {
	echo "<li><a class='teamcnav' href='$url/$table_name'>$table_name</a></li>";
}
?>
</ul>
<a class="teamcnav" href="<?= site_url("update_statistics/backup") ?>">Backup</a><br>
<a class="teamcnav" href="<?= site_url("update_statistics/restore") ?>">Restore</a><br>
<a class="teamcnav" href="<?= site_url("update_statistics/sql") ?>">Run SQL</a><br>
</div>

<div id = "container" style="
	width:78%;
	padding-left:30px;
	padding-top:10px;
	padding-bottom:20px;
	padding-right:10px;
	overflow-x:auto;
	">