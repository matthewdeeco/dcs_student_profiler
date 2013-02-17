<script>
$(document).ready(function(){

	$('#sr').removeClass('active');
	$('#cs').removeClass('active');
	$('#et').removeClass('active');
	$('#us').addClass('active');
	$('#ab').removeClass('active');	

	$(".remove_button").click(function(){
		var callback = "<?=site_url('update_statistics/delete')?>";
		var changed_cell = $(this);
		
		 $.ajax({
			type: 'post',
			url: callback,
			data: {
				tablename: $(this).attr("data-tablename"),
				primarykeyname: $(this).attr("data-primarykeyname"),
				primarykeyvalue: $(this).attr("data-primarykeyvalue")
			},
			dataType: 'html',
			success: function (retVal) {
				alert(retVal);
			},
			error: function(){
					alert("Error in connecting to the database.");
			}
		  });//endajax	
	});//endonclick	
	
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
				if (retVal == 'true') {
					$(changed_cell).css('background-color','#AAFFCC').css("color","#555555");
					setTimeout(function() { $(changed_cell).css("background-color","white"); }, 250);
				} else {
					// alert(retVal);
					$(changed_cell).css("background-color","#CF0220").css("color","white");
				}
			},
			error: function(){
				// $(changed_cell).addClass("edit_failure");
				// alert(retVal);
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
	// echo '<div class="well form-search">';
	echo "<form enctype='multipart/form-data' action='".$dest."' method='POST'";
	foreach ($tables as $table) {
		$tablename = $table['table_name'];
		$rows = $table['rows'];
		printTableName($tablename);
		if (!empty($rows))
			printTable($rows, $tablename);
	}
	echo "</form>";
	// echo "</div>";
}

function printTableName($tablename) {
	echo "<span class='databasetablename'>$tablename</span><br>";
}

function printTable($rows, $tablename) {
	echo "<table class='databasetable'>";
	
	echo "<tr>";
	foreach ($rows[0] as $key => $value)
		if($key != 'personid')
			echo "<th>$key</th>";
	
	//echo "<th></th>"; //for the delete icon
	
	echo "</tr>";
	foreach($rows as $row) {
		printRow($row, $tablename);
	}
	echo "</table>";
}

function printRow($row, $tablename) {
	echo "<tr>";
	$column = 0;
	$primarykeyvalue = 0;
	
	foreach ($row as $key => $value) {
		$length = strlen($value) + 1;
		if ($column == 0) {
			$primarykeyname = $key;
			$primarykeyvalue = $value;
			
			//echo "<td class='primarykey'>$primarykeyvalue</td>";
			//printCell($tablename, $primarykeyname, $primarykeyvalue, $key, $value);
		}
		else {
		
			printCell($tablename, $primarykeyname, $primarykeyvalue, $key, $value);
		}
		$column++;
	}
	
	/*echo "<td><input type=\"button\" class=\"remove_button\" name=\"remove\" value=\"x\" data-primarykeyname=\"$primarykeyname\"
	data-primarykeyvalue=\"$primarykeyvalue\" data-tablename=\"$tablename\"></td>";	//for the delete icon
	
	echo "</tr>";*/
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
