<script>
$(document).ready(function(){
	$(".remove_button").click(function(){
			alert("Remove?");//check 
		var callback = "<?=site_url('update_statistics/delete')?>";	//not yet existent
		var changed_cell = $(this);
		
		 $.ajax({
			type: 'post',
			url: callback,
			data: {
				tablename: $(this).attr("data-tablename"),
				primarykeyname: $(this).attr("data-primarykeyname"),
				primarykeyvalue: $(this).attr("data-primarykeyvalue"),
				changedkeyname: $(this).attr("data-changedkeyname"),
				changedkeyvalue: $(this).val()
			},
			dataType: 'html',
			success: function (retVal) {
			
			},
			error: function(){
					alert("Error in connecting to the database.");
			}
		  });//endajax	
	})//endonclick
});
$(document).ready(function() {
	$(".inputcell").change(function() {
		var callback = "<?=site_url('update_statistics/update')?>";
		var changed_cell = $(this);
		
		 $.ajax({
			type: 'post',
			url: callback,
			data: {
				tablename: $(this).attr("data-tablename"),
				primarykeyname: $(this).attr("data-primarykeyname"),
				primarykeyvalue: $(this).attr("data-primarykeyvalue"),
				changedkeyname: $(this).attr("data-changedkeyname"),
				changedkeyvalue: $(this).val()
			},
			dataType: 'html',
			success: function (retVal) {
				//show check mark beside the row
				$(changed_cell).css("background-color","white").css("color","#555555");
			},
			error: function(){
				// $(changed_cell).addClass("edit_failure");
				$(changed_cell).css("background-color","#CF0220").css("color","white");
			}
		  });//endajax
	});//endchange
});//end ready ()

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
	
	echo "<th></th>"; //for the delete icon
	
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
	
	//GIVE CELL DETAILS TO THIS BUTTON
	echo "<td><input type=\"button\" class=\"remove_button\" name=\"remove\" value=\"x\"></td>";	//for the delete icon
	echo "</tr>";
}

function printCell($tablename, $primarykeyname, $primarykeyvalue, $key, $value = '') {
	$length = strlen($value) + 1;
	$cell_id = $tablename."_".$primarykeyvalue;
	$data = array('name'=>'databasecell', 'id'=>$cell_id, 'class'=>'inputcell', 'size'=>$length, 'value'=>$value, 
		'data-tablename'=>$tablename, 'data-primarykeyname'=>$primarykeyname,
		'data-primarykeyvalue'=>$primarykeyvalue, 'data-changedkeyname'=>$key);
	echo "<td><div class='databasecell'>".form_input($data)."</div></td>";
}
?>