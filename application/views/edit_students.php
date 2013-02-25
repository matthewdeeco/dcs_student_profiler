<script>
$(document).ready(function(){
	
	$('a.view_grades').click(function(e) {
		// prevent the default action when a nav button link is clicked
		e.preventDefault();
		// ajax query to retrieve the HTML view without refreshing the page.
		$('#container').load($(this).attr('href'));
	});
	
	$(".inputcell").change(function() {
	
		var callback = "<?=site_url('update_statistics/updateStudentInfo')?>";
		var changed_cell = $(this);
		
		$.ajax({
			type: 'post',
			url: callback,
			data: {
				personid: $(this).attr('id'),
				changedfield_name: $(this).attr("data-changedfieldname"),
				changedfield_value: $(this).val()
			},
			dataType: 'html',
			success: function (retVal) {
				if (retVal == 'true') {
					$(changed_cell).css('background-color','#AAFFCC').css("color","#555555");
					setTimeout(function() { $(changed_cell).css("background-color","white"); }, 250);
				} else {
					// alert(retVal);
					$(changed_cell).prop("title", retVal);
					$(changed_cell).css("background-color","#CF0220").css("color","white");
				}
			},
			error: function(){
				alert("Call to database failed.");
				$(changed_cell).css("background-color","#CF0220").css("color","white");
			}
		});//endajax
	});//endchange
});//end ready ()
</script>

<script src="http://localhost/cs192dcs/assets/js/jquery.tablesorter.js"></script>

<style>
#students {
	font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
	width:500;
	border-collapse:collapse;
	color:#FFFFFF;
}
#students td, #students th {
	font-size:1.2em;
	border:1px solid #006600;
	padding:3px 7px 2px 7px;
	padding-bottom: 3px;
}
#students th {
	font-size:1.4em;
	color:#FFFFFF;
	text-align:left;
	padding-top:3px;
	padding-bottom:2px;
	background-color:green;
}
tr:nth-child(even), tr:nth-child(even) input[type="text"] {
	background-color: #F5F0EB;
}
tr:nth-child(odd), tr:nth-child(even) input[type="text"] {
	background-color: #FFF;
}

</style>
<style type="text/css">
    table thead tr .header {
        background-image: url(http://localhost/cs192dcs/images/bg.gif);
        background-repeat: no-repeat;
        background-position: center right;
        background-color:#4D9900;
    }
    table thead tr .headerSortUp {
        background-image: url(http://localhost/cs192dcs/images/asc.gif);
        background-color:#336600;
    }
    table thead tr .headerSortDown {
        background-image: url(http://localhost/cs192dcs/images/desc.gif);
        background-color:#336600;
    }
</style>
<span class="page-header">
	<h3>Edit Student Information</h3>
</span>
<form action="" method="post"> 
<table id="students" class="table table-bordered table-striped table-hover">
	<thead>
		<tr>
			<?php
				if (empty($students)) {
					echo "Database is empty.";
				}
				else {
					echo '<th width="10%"><center>Student Number</center></th>
					<th width="20%"><center>Last Name</center></th>
					<th width="20%"><center>First Name</center></th>
					<th width="20%" ><center>Middle Name</center></th>
					<th width="10%"><center>Pedigree</center></th>
					<th width="20%"><center></center></th>';
				}
			?>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach($students as $student){
			
				$personid = $student['personid'];
				$studentno = $student['studentno'];
				$lastname = $student['lastname'];
				$firstname = $student['firstname'];
				$middlename = $student['middlename'];
				$pedigree = $student['pedigree'];

				echo "<tr>";
				printCell($personid, 'studentno', $studentno);
				printCell($personid, 'lastname', $lastname);
				printCell($personid, 'firstname', $firstname);				
				printCell($personid, 'middlename', $middlename);
				printCell($personid, 'pedigree', $pedigree);
				
				$grade_url = site_url("update_statistics/editGrades/$personid");
				echo "<td><a class='view_grades btn btn-primary small' href=$grade_url>Edit Grades</a></td>";
				echo "</tr>";
			}
			
			function printCell($personid, $fieldname, $fieldvalue) {
				$length = strlen($fieldvalue) + 1;
				$data = array('name'=>'databasecell', 'id'=>$personid, 'class'=>'inputcell',
				'data-changedfieldname'=>$fieldname, 'size'=>$length, 'value'=>$fieldvalue);
				echo "<td><div class='databasecell'>".form_input($data)."</div></td>";
			}
		?>
	
	</body>
</table>
</form>