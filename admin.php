<h3><?php echo $lang[$locale]['admin_interpreters']; ?></h3>
<table id="approved-list" class="table table-striped table-hover">
	<?php
		echo "<thead><tr><th>".$lang['de']['requests']."</th><th>".$lang[$locale]['name']."</th><th>".$lang[$locale]['contact']."</th><th>".$lang[$locale]['timeslots']."</th><th>".$lang[$locale]['languages']."</th><th>".$lang[$locale]['remarks']."</th><th></th></tr></thead><tbody class=\"table-striped\">\n"; 
		
		$userlist = new user_list($sqlcon, 0, 1, $_POST['languages'], $_POST['timeslots'],"name");
		
		while($cur_user = $userlist->get_user())
		{
			generateTableRow($cur_user,$user);
		}
	?>
	</tbody>
</table>

<h3><?php echo $lang[$locale]['admin_non_interpreters']; ?></h3>
<table id="not-approved-list" class="table table-striped table-hover">
	<?php
		echo "<thead><tr><th>".$lang['de']['requests']."</th><th>".$lang[$locale]['name']."</th><th>".$lang[$locale]['contact']."</th><th>".$lang[$locale]['timeslots']."</th><th>".$lang[$locale]['languages']."</th><th>".$lang[$locale]['remarks']."</th><th></th></tr></thead><tbody class=\"table-striped\">\n"; 
		
		$userlist = new user_list($sqlcon, 0, -1, $_POST['languages'], $_POST['timeslots'],"name");
		
		while($cur_user = $userlist->get_user())
		{
			generateTableRow($cur_user,$user);
		}
	?>
	</tbody>
</table>
