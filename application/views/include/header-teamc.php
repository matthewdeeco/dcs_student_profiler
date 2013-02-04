<div id="sidebar" style ="
	width:13%;
	padding-left: 5px;
	">
<a href="<?= site_url("update_statistics/index") ?>">Upload</a><br>
Edit
<ul>
<?php
$url = site_url("update_statistics/edit");
foreach ($table_names as $table_name) {
	echo "<li><a href='$url/$table_name'>$table_name</a></li>";
}
?>
</ul>
<a href="<?= site_url("update_statistics/backup") ?>">Backup</a><br>
<a href="<?= site_url("update_statistics/restore") ?>">Restore</a><br>
</div>

<div id = "container" style="
	width:78%;
	padding-left:30px;
	padding-top:10px;
	padding-bottom:20px;
	">