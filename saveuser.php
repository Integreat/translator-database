<?php

if($_POST['post-uid'] AND $user->is_admin)
{
	$user_data = new user_data();
	$user_data->userid = $_POST['post-uid'];
	$user_data->name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
	$user_data->email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
	$user_data->telephone = filter_var(_POST['telephone'], FILTER_SANITIZE_STRING);
	$user_data->remarks = filter_var($_POST['remarks'], FILTER_SANITIZE_STRING);
	$user_data->is_admin = ($_POST['admin'] ? "1" : "0");
	$user_data->is_interpreter = ($_POST['interpreter'] ? "1" : "0");
	$user_data->is_viewer = ($_POST['viewer'] ? "1" : "0");
	$user_data->language_ids = $_POST['edit_languages'];
	$user_data->timeslot_ids = $_POST['edit_timeslots'];
	$user_data->job_multiplier = $_POST['edit_job_multiplier'];
	$user_data->email_verified = ($_POST['email_verified'] ? "1" : "0");
	$user_data->gender = $_POST['gender'];
	
	$save_user = new user($sqlcon,0,0,0,$user_data->userid);
	$save_user->change_user_data($user_data);
	
	if($_POST['pw1'] == $_POST['pw2'] AND $_POST['pw1'])
	{
		$save_user->change_password($_POST['pw1'], $_POST['pw2']);
	}
	$user_data = new user($sqlcon,0,0,0,$user_data->userid);
	echo ($user_data->is_interpreter ? "<td><span id=\"count-".$save_user->userid."\">#".(string)$save_user->job_count."</span> ".(($_GET['linkid'] == 7 OR $_GET['linkid'] == 5) ? "<a href=\"#\" id=\"increment-link-".$save_user->userid."\" onclick=\"job_increment(".$save_user->userid.");\"><span class=\"label label-success label-as-badge\" span=\"\">+1</span></a>":"")."</td>":"<td></td>");
	echo "<td>".$save_user->name."</td><td><a href=\"mailto: ".$save_user->email."\">".$save_user->email."</a>".($save_user->email_verified ? "<span class=\"glyphicon glyphicon-ok\" style=\"color: green;\"></span>":"")."<br>".$save_user->telephone."</td><td>";
	if(is_array($user_data->timeslots)) foreach($user_data->timeslots as $timeslot) {echo $timeslot."<br>";}
	echo"</td><td>";
	if(is_array($user_data->languages)) foreach($user_data->languages as $language) {echo $language."<br>";}
	echo "</td><td>".$save_user->remarks."<br>".$save_user->zip." ".$save_user->city."</td><td>"; 
	if($user->is_admin)
	{
		echo "<a onclick=\"edit_user(".$save_user->userid.",'#user-".$save_user->userid."');\" href=\"#\"><span class=\"glyphicon glyphicon-pencil\"></span></a> ";
		if($_GET['linkid'] != 5)
			echo "<a onclick=\"del_user(".$cur_user->userid.",'#user-".$cur_user->userid."','".$lang[$locale]['confirm_remove_user']."');\" href=\"#\" class=\"text-danger\"><span class=\"glyphicon glyphicon-remove\"></span></a>";
	}
	echo "</td>\n";
	
}
elseif($_POST['increment'] AND $user->is_viewer)
{
	$sqlcon->query("UPDATE users SET job_count = job_count + job_multiplier WHERE id='".$_POST['increment']."'");
	echo "#".$sqlcon->query("SELECT job_count FROM users WHERE id='".$_POST['increment']."'")->fetch_assoc()['job_count'];
}
elseif($_POST['post-uid'] AND $user->session_id == session_id())
{
	$user_data = new user_data();
	$user_data->userid = $user->userid;
	$user_data->name = $_POST['name'];
	$user_data->email = $user->email;
	$user_data->telephone = $_POST['telephone'];
	$user_data->remarks = $_POST['remarks'];
	$user_data->is_admin = ($_POST['admin'] ? "1" : "0");
	$user_data->is_interpreter = ($_POST['interpreter'] ? "1" : "0");
	$user_data->is_viewer = ($_POST['viewer'] ? "1" : "0");
	$user_data->language_ids = $_POST['edit_languages'];
	$user_data->timeslot_ids = $_POST['edit_timeslots'];
	$user_data->job_multiplier = $_POST['edit_job_multiplier'];
	$user_data->email_verified = $user->email_verified;
	$user_data->gender = $_POST['gender'];
	
	$save_user = new user($sqlcon,0,0,0,$user_data->userid);
	$save_user->change_user_data($user_data);
	
	if($_POST['pw1'] == $_POST['pw2'] AND $_POST['pw1'])
	{
		$save_user->change_password($_POST['pw1'], $_POST['pw2']);
	}
	$edit_user = new user($sqlcon,0,0,0,$user_data->userid);
	?>
	<td colspan="7">
		<form id="<?php echo ($_GET['linkid']==4 ? "edit-user" : "edit-user-".$_POST['id']); ?>">
			<div class='row'>
				<div class="col-md-12">
					<h4 class="text-center">
						<?php echo $lang[$locale]['edit_user']; ?> <input type="hidden" name="post-uid" value="<?php echo ($_POST['id'] ? $_POST['id'] : $user->userid);?>">
					</h4>
				</div>
			</div>
			<div class='row'><div class='col-md-3'></div><div class='col-md-3'><?php echo $lang[$locale]['name']; ?></div><div class='col-md-3'><input class="form-control" type="text" name="name" value="<?php echo $edit_user->name; ?>"></div><div class='col-md-3'></div></div>
			<div class='row'><div class='col-md-3'></div><div class='col-md-3'><?php echo $lang[$locale]['email']; ?></div><div class='col-md-3'><input class="form-control" type="text" name="email" value="<?php echo $edit_user->email; ?>"></div><div class='col-md-3'></div></div>
			<div class='row'><div class='col-md-3'></div><div class='col-md-3'><?php echo $lang[$locale]['telephone']; ?></div><div class='col-md-3'><input class="form-control" type="text" name="telephone" value="<?php echo $edit_user->telephone; ?>"></div><div class='col-md-3'></div></div>
			<div class='row'><div class='col-md-3'></div><div class='col-md-3'><?php echo $lang[$locale]['enter_languages']; ?></div><div class='col-md-3'><?php echo generateLanguageSelect($sqlcon,$lang[$locale]['please_select'],"edit_languages",$edit_user->language_ids); ?></div><div class='col-md-3'></div></div>
			<div class='row'><div class='col-md-3'></div><div class='col-md-3'><?php echo $lang[$locale]['enter_timeslots']; ?></div><div class='col-md-3'><?php echo generateTimeslotSelect($sqlcon,$lang[$locale]['please_select'],"edit_timeslots",$edit_user->timeslot_ids); ?></div><div class='col-md-3'></div></div>
			<div class='row'><div class='col-md-3'></div><div class='col-md-3'><?php echo $lang[$locale]['enter_job_multiplier']; ?></div><div class='col-md-3'><?php echo generateJobMultiplierSelect($sqlcon,$lang[$locale]['please_select'],"edit_job_multiplier",$edit_user->job_multiplier,$lang[$locale]); ?></div><div class='col-md-3'></div></div>
			<div class='row'><div class='col-md-3'></div><div class='col-md-3'><?php echo $lang[$locale]['remarks']; ?></div><div class='col-md-3'><input class="form-control" type="text" name="remarks" value="<?php echo $edit_user->remarks; ?>"></div><div class='col-md-3'></div></div>
			<div class='row'><div class="col-md-12"><h4 class="text-center"><?php echo $lang[$locale]['edit_password']; ?> </h4></div></div>
			<div class='row'><div class='col-md-3'></div><div class='col-md-3'><?php echo $lang[$locale]['enter_password']; ?></div><div class='col-md-3'><input class="form-control" type="password" name="pw1"></div><div class='col-md-3'></div></div>
			<div class='row'><div class='col-md-3'></div><div class='col-md-3'><?php echo $lang[$locale]['confirm_password']; ?></div><div class='col-md-3'><input class="form-control" type="password" name="pw2"></div><div class='col-md-3'></div></div>
<?php if($user->is_admin) {?>
			<div class='row'><div class='col-md-3'></div><div class='col-md-3'><?php echo $lang[$locale]['admin']; ?></div><div class='col-md-3'><input class="form-control" type="checkbox" name="admin" <?php echo ($edit_user->is_admin ? "checked" : ""); ?>></div><div class='col-md-3'></div></div>
			<div class='row'><div class='col-md-3'></div><div class='col-md-3'><?php echo $lang[$locale]['interpreter']; ?></div><div class='col-md-3'><input class="form-control" type="checkbox" name="interpreter" <?php echo ($edit_user->is_interpreter ? "checked" : ""); ?>></div><div class='col-md-3'></div></div>
			<div class='row'><div class='col-md-3'></div><div class='col-md-3'><?php echo $lang[$locale]['viewer']; ?></div><div class='col-md-3'><input class="form-control" type="checkbox" name="viewer" <?php echo ($edit_user->is_viewer ? "checked" : ""); ?>></div><div class='col-md-3'></div></div>
			<div class='row'><div class='col-md-3'></div><div class='col-md-3'><?php echo $lang[$locale]['email_verified']; ?></div><div class='col-md-3'><input class="form-control" type="checkbox" name="email_verified" <?php echo ($edit_user->email_verified ? "checked" : ""); ?>></div><div class='col-md-3'></div></div>
<?php } ?>

		</form>
		<div class='row'><div class='col-md-3'></div><div class='col-md-3'></div><div class='col-md-3'><button onclick="<?php echo ($_GET['linkid']==4 ? "save_user" : "save_user_".$_POST['id']); ?>('#user-<?php echo ($_POST['id'] ? $_POST['id'] : $user->userid);?>');" class="btn btn-success"><?php echo $lang[$locale]['save']; ?></button></div><div class='col-md-3'></div></div>
		
		<script>
			function <?php echo ($_GET['linkid']==4 ? "save_user" : "save_user_".$_POST['id']); ?>(target) {

				var action = "ajax.php?linkid=5",
					method = "POST",
					data   = $("#<?php echo ($_GET['linkid']==4 ? "edit-user" : "edit-user-".$_POST['id']); ?>").serialize();
					
				$.ajax({
					url : action,
					type : method,
					data : data
				}).done(function (data) {
					$(target).html(data);
				});
			};
		</script>
	</td>
	<?php
	
}
elseif($_GET['linkid']==10 AND $user->is_admin)
{
	$del_user = new user($sqlcon,0,0,0,$_POST['id']);
	$del_user->delete();
	echo "<td colspan=\"7\"><div class=\"alert alert-danger\">".$lang[$locale]['user_removed']."</div></td>";
}
?>
