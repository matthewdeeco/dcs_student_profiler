<div id = "sidebar" style ="width:13%;">
<a href="<?= site_url("update_statistics/index") ?>">Upload</a><br>
Edit
<ul>
<?php
foreach ($table_names as $table_name) {
	$url = site_url("update_statistics/edit/".$table_name);
	echo '<li><a href='.$url.'>'.$table_name.'</a></li>';
}
?>
</ul>
</div>

<div id = "container" style="
	width:80%;
	padding-left:30px;
	padding-top:10px;
	padding-bottom:30px;
	">