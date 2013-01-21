<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="<?= base_url('assets/CSS3 Menu_files/css3menu1/style.css') ?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url('assets/css/index.css') ?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url('assets/css/edit-database.css') ?>" rel="stylesheet" type="text/css" />
<title>UPD DCS Student Profiling System</title>
</head>

<body>

<div id = "container" style="width:1150px">
     <?php editdatabase($tables)?>
</div> <!--container-->

</body>
<?php

function editdatabase($tables){
	$dest = site_url('update_statistics/edit');
	echo "<form enctype='multipart/form-data' action='.$dest.' method='POST'";
	foreach ($tables as $table) {
		$tablename = $table['table_name'];
		echo "<span class='tablename'>$tablename</span><br>";
		echo "<table class='databasetable'>";
		$rows = $table['rows'];
		if (!empty($rows)) {
			echo "<tr>";
			foreach ($rows[0] as $key => $value)
					echo "<th>$key</th>";
			echo "</tr>";
			foreach($rows as $row) {
				echo "<tr>";
				foreach ($row as $value) {
					$length = strlen($value) + 1;
					echo "<td><div class='databasecell'>
					<input type='text' size=$length style='width:100%' value=\"$value\">
					</div></td>";
				}
				echo "</tr>";
			}
		}
		echo "</table><br><br>";
	}
	echo "</form>";
}
?>

</html>
