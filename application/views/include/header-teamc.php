<script>
$(document).ready(function(){	
	$('#sr').removeClass('active');
	$('#cs').removeClass('active');
	$('#et').removeClass('active');
	$('#us').addClass('active');
	$('#ab').removeClass('active');	

	$('#up').addClass('active');
	$('#ed').removeClass('active');
	$('#bu').removeClass('active');
	$('#re').removeClass('active');
	$('#rs').removeClass('active');	

	$('a.teamcnav').click(function(e) {
		// prevent the default action when a nav button link is clicked
		e.preventDefault();
		
		$actionid = $(this).parent('li').attr('id');
		
		$('#up').removeClass('active');
		$('#ed').removeClass('active');
		$('#bu').removeClass('active');
		$('#re').removeClass('active');
		$('#rs').removeClass('active');	
		
		if($actionid == "up"){
			$('#up').addClass('active');
		}else if($actionid == "ed"){
			$('#ed').addClass('active');
		}else if($actionid == "bu"){
			$('#bu').addClass('active');
		}else if($actionid == "re"){
			$('#re').addClass('active');
		}else if($actionid == "rs"){
			$('#rs').addClass('active');
		}
		
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
	margin-left: -25px;
	">
<ul class="nav nav-list-team-c">
<li class="nav-header">Update Navigation</li>
<li id="up"><a class="teamcnav" href="<?= site_url("update_statistics/upload") ?>">Upload</a></li>
<li id="ed"><a class="teamcnav" href="<?= site_url("update_statistics/edit") ?>">Edit</a></li>
<li id="bu"><a class="teamcnav" href="<?= site_url("update_statistics/backup") ?>">Backup</a></li>
<li id="re"><a class="teamcnav" href="<?= site_url("update_statistics/restore") ?>">Restore</a></li>
<li id="rs"><a class="teamcnav" href="<?= site_url("update_statistics/sql") ?>">Run SQL</a></li>
</ul>
</div>

<div id = "container" style="
	width:80%;
	padding-left:30px;
	padding-top:10px;
	padding-bottom:20px;
	padding-right:10px;
	overflow-x:auto;
	">