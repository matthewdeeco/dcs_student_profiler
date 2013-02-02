<script>
$(document).ready(function() {
	$(".inputcell").change(function() {
		var url = "<?=site_url('update_statistics/update')?>";
		$.post(url, {
			tablename: $(this).attr("data-tablename"),
			primarykeyname: $(this).attr("data-primarykeyname"),
			primarykeyvalue: $(this).attr("data-primarykeyvalue"),
			changedkeyname: $(this).attr("data-changedkeyname"),
			changedkeyvalue: $(this).val()
		}, function(){});
	})
});	
</script>
<?php
if (!empty($errormessage))
	echo $errormessage;
else
	printTables($tables);
	
function printTables($tables) {
	$dest = site_url('update_statistics/edit');
	echo "<form enctype='multipart/form-data' action='".$dest."' method='POST'";
	foreach ($tables as $table) {
		$tablename = $table['table_name'];
		$rows = $table['rows'];
		printTableName($tablename);
		if (!empty($rows))
			printTable($rows, $tablename);
	}
	echo "</form>";
}

function printTableName($tablename) {
	echo "<span class='databasetablename'>$tablename</span><br>";
}

function printTable($rows, $tablename) {
	echo "<table class='databasetable'>";
	echo "<tr>";
	foreach ($rows[0] as $key => $value)
		echo "<th>$key</th>";
	echo "</tr>";
	foreach($rows as $row) {
		printRow($row, $tablename);
	}
	echo "</table>";
}

function printRow($row, $tablename) {
	echo "<tr>";
	$column = 0;
	foreach ($row as $key => $value) {
		$length = strlen($value) + 1;
		if ($column == 0) {
			$primarykeyname = $key;
			$primarykeyvalue = $value;
			echo "<td class='primarykey'>$primarykeyvalue</td>";
		}
		else {
			printCell($tablename, $primarykeyname, $primarykeyvalue, $key, $value);
		}
		$column++;
	}
	echo "</tr>";
}

function printCell($tablename, $primarykeyname, $primarykeyvalue, $key, $value = '') {
	$length = strlen($value) + 1;
	$data = array('name'=>'databasecell', 'class'=>'inputcell', 'size'=>$length, 'value'=>$value, 
		'data-tablename'=>$tablename, 'data-primarykeyname'=>$primarykeyname,
		'data-primarykeyvalue'=>$primarykeyvalue, 'data-changedkeyname'=>$key);
	echo "<td><div class='databasecell'>".form_input($data)."</div></td>";
}
?>