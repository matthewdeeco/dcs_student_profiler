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
		echo "<span class='databasetablename'>$tablename</span><br>";
		echo "<table class='databasetable'>";
		$rows = $table['rows'];
		if (!empty($rows)) {
			printTableRows($rows, $tablename);
			//printBlankRow($rows);
		}
		echo "</table><br>";
	}
	echo "</form>";
}

function printTableRows($rows, $tablename) {
	echo "<tr>";
	foreach ($rows[0] as $key => $value)
		echo "<th>$key</th>";
	echo "</tr>";
	foreach($rows as $row) {
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
				$data = array('name'=>'databasecell', 'class'=>'inputcell', 'size'=>$length, 'value'=>$value, 
					'data-tablename'=>$tablename, 'data-primarykeyname'=>$primarykeyname,
					'data-primarykeyvalue'=>$primarykeyvalue, 'data-changedkeyname'=>$key);
				// $js = 'onchange="updatedb(\''.$tablename.'\', \''.$primarykeyname.'\', \''.$primarykey.'\', \''.$key.'\', '.'this.value)"';
				echo "<td><div class='databasecell'>".form_input($data)."</div></td>";
				// echo "<td><div class='databasecell'><input type='text' size=$length value=\"$value\"></div></td>";
			}
			$column++;
		}
		echo "</tr>";
	}
}

function printBlankRow($rows) {
	echo "<tr>";
	$column = 0;
	foreach ($rows[0] as $key => $value) {
		if ($column == 0) {
			$rowcount = count($rows);
			$nextkey = $rows[$rowcount - 1][$key] + 1;
			echo "<td class='primarykey'>$nextkey</td>";
		}
		else
			echo "<td><div class='databasecell'><input type='text' size=1></div></td>";
		$column++;
	}
	
	echo "</tr>";
}
?>