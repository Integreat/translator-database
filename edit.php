<?php

if($_POST['id'] AND $user->is_admin)
{
	$edit_user = new user($sqlcon,0,0,0,$_POST['id']);
}
elseif($user->session_id == session_id())
{
	$edit_user = $user;
}
if($edit_user->userid)
{
	?>

	<td colspan="7">
		<a name="<?php echo $_POST['id']; ?>"></a>
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
			<div class='row'><div class='col-md-3'></div><div class='col-md-3'><?php echo $lang[$locale]['gender']; ?></div><div class='col-md-3'>
				<div class="btn-group">
					<button data-toggle="dropdown" class="btn dropdown-toggle btn-info" data-placeholder="<?php echo $lang[$locale]['please_select']; ?>"><?php echo $lang[$locale]['please_select']; ?><span class="caret"></span></button>
					<ul class="dropdown-menu">
						<li><input type="radio" id="gender1" value="male" name="gender" ><label for="gender1" name="gender" value="1"><?php echo $lang[$locale]['male']; ?></label></li>
						<li><input type="radio" id="gender2" value="female" name="gender" ><label for="gender2" name="gender" value="2"><?php echo $lang[$locale]['female']; ?></label></li>
					</ul>
				</div></div>
				<div class='col-md-3'></div>
			</div>
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
?>
