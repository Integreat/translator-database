<table id="interpreter-list" class="table table-striped table-hover">
	<?php
		echo "<thead><tr><th>".$lang['de']['requests']."</th><th>".$lang[$locale]['name']."</th><th>".$lang[$locale]['contact']."</th><th>".$lang[$locale]['timeslots']."</th><th>".$lang[$locale]['languages']."</th><th>".$lang[$locale]['remarks']."</th><th></th></tr></thead><tbody class=\"table-striped\">\n"; 
		
		$userlist = new user_list($sqlcon, 0, 1, $_POST['languages'], $_POST['timeslots'],"job_count");
		
		while($cur_user = $userlist->get_user())
		{
			generateTableRow($cur_user,$user);
		}
	?>
</tbody>
</table>
<script>
/*$(document).ready(function() 
{ 
	$("#interpreter-list").tablesorter(); 
});*/ 

function job_increment(user)
{
	var action = "ajax.php?linkid=5",
		method = "POST",
		data   = {"increment":user};
		$.ajax({
			url : action,
			type : method,
			data : data
		}).done(function (data) {
			$("#count-"+user).html(data);
			$("#increment-link-"+user).removeAttr("onclick");
		});
};
</script>
