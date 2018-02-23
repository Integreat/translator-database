<?php
if($_GET['code'])
{
	$code = $sqlcon->real_escape_string($_GET['code']);
	$update = "UPDATE users SET email_verified=1 WHERE session_id='$code'";
	$sqlcon->query($update);
	if($sqlcon->affected_rows)
	{
		?>
		<div class="alert alert-success" role="alert">
			<?php echo $lang[$locale]['email_confirmed']; ?>
		</div>
		<?php
	}
	else
	{
		?>
		<div class="alert alert-danger" role="alert">
			<?php echo $lang[$locale]['email_not_found']; ?>
		</div>
		<?php
	}
}
else
{
	$code = generateRandomString(32);
	$name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
	$remarks = filter_var($_POST["remarks"], FILTER_SANITIZE_STRING);
	$email = filter_var($_POST["email"], FILTER_SANITIZE_STRING);
	$telephone = filter_var($_POST["telephone"], FILTER_SANITIZE_STRING);
	$pw1 = $sqlcon->real_escape_string($_POST["pw1"]);
	$pw2 = $sqlcon->real_escape_string($_POST["pw2"]);
	$gender = $sqlcon->real_escape_string($_POST["gender"]);

	$insert = "INSERT INTO users (name,email,session_id,telephone,remarks,gender) VALUES (?,?,?,?,?,?)";
	$stmt = $sqlcon->prepare($insert);
	$stmt->bind_param("ssssss", $name, $email, $code, $telephone, $remarks, $gender);

	if($stmt->execute())
	{
		$user_data = new user_data();
		
		$user_data->language_ids = $_POST['languages'];
		$user_data->timeslot_ids = $_POST['timeslots'];
		$user_data->job_multiplier = $_POST['job_multiplier'];
		
		$save_user = new user($sqlcon,0,0,0,$sqlcon->insert_id);
		$save_user->change_user_data($user_data);
		$save_user->change_password($pw1,$pw2);

		//if(!$user->is_admin)
		if(true)
		{
			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail->Host       = $config['mail']['host'];
			$mail->SMTPDebug  = 0;
			$mail->SMTPAuth   = $config['mail']['auth'];
			$mail->Host       = $config['mail']['host'];
			$mail->Port       = $config['mail']['port'];
			$mail->Username   = $config['mail']['user'];
			$mail->Password   = $config['mail']['password'];
			$mail->SMTPSecure = $config['mail']['secure'];
			$mail->CharSet = 'utf-8';
			$mail->isHTML(false);
			$mail->AddReplyTo($config['mail']['user'], $config['mail']['name']);
			$mail->AddAddress($_POST['email'], $_POST['name']);
			$mail->SetFrom($config['mail']['user'], $config['mail']['name']);
			$mail->Subject = $lang[$locale]['registration_subject'];
			$mail->Body = $lang[$locale]['registration_mailbody_1'].$config['path']['server'].$config['path']['folder']."?linkid=6&code=$code".$lang[$locale]['registration_mailbody_2'];
			if(!$mail->send()) 
			{
				?>
				<div class="alert alert-danger" role="alert">
					<?php echo $lang[$locale]['email_error']; ?>
				</div>
				<?php
			}
			else
			{
			?>
				<div class="alert alert-success" role="alert">
					<?php echo $lang[$locale]['email_success']; ?>
				</div>
			<?php
			}
		}
		else
		{
			$user_data->email_verified=1;
			$save_user->change_user_data($user_data);
		}
	}
	else
	{
		?>
		<div class="alert alert-danger" role="alert">
			<?php echo $lang[$locale]['mail_already_registered']; ?>
		</div>
			<h2 class="text-center"><?php echo $lang[$locale]['register']; ?></h2>
			<form id="register-user">
				<div class='row'>
					<div class="col-md-12">
						<h4 class="text-center">
							<?php echo $lang[$locale]['new_user_data']; ?> <input type="hidden" name="new-uid" value="1">
						</h4>
					</div>
				</div>
				<div class='row'><div class='col-md-3'></div><div class='col-md-3'><?php echo $lang[$locale]['name']; ?></div><div class='col-md-3'><input class="form-control" type="text" name="name" value="<?php echo $edit_user->name; ?>"></div><div class='col-md-3'></div></div>
				<div class='row'><div class='col-md-3'></div><div class='col-md-3'><?php echo $lang[$locale]['email']; ?></div><div class='col-md-3'><input class="form-control" type="text" name="email" value="<?php echo $edit_user->email; ?>"></div><div class='col-md-3'></div></div>
				<div class='row'><div class='col-md-3'></div><div class='col-md-3'><?php echo $lang[$locale]['telephone']; ?></div><div class='col-md-3'><input class="form-control" type="text" name="telephone" value="<?php echo $edit_user->telephone; ?>"></div><div class='col-md-3'></div></div>
				<div class='row'><div class='col-md-3'></div><div class='col-md-3'><?php echo $lang[$locale]['gender']; ?></div><div class='col-md-3'>
					<div class="btn-group">
						<button data-toggle="dropdown" class="btn dropdown-toggle btn-info"  data-placeholder="Bitte w&auml;hlen">Bitte w&auml;hlen<span class="caret"></span></button>
						<ul class="dropdown-menu">
							<li><input type="radio" id="gender1" value="male" name="gender" ><label for="gender1" name="gender" value="1"><?php echo $lang[$locale]['male']; ?></label></li>
							<li><input type="radio" id="gender2" value="female" name="gender" ><label for="gender2" name="gender" value="2"><?php echo $lang[$locale]['female']; ?></label></li>
						</ul>
					</div></div>
					<div class='col-md-3'></div>
				</div>
				<div class='row'><div class='col-md-3'></div><div class='col-md-3'><?php echo $lang[$locale]['enter_languages']; ?></div><div class='col-md-3'><?php echo generateLanguageSelect($sqlcon,$lang[$locale]['please_select'],"languages"); ?></div><div class='col-md-3'></div></div>
				<div class='row'><div class='col-md-3'></div><div class='col-md-3'><?php echo $lang[$locale]['enter_timeslots']; ?></div><div class='col-md-3'><?php echo generateTimeslotSelect($sqlcon,$lang[$locale]['please_select'],"timeslots"); ?></div><div class='col-md-3'></div></div>
				<div class='row'><div class='col-md-3'></div><div class='col-md-3'><?php echo $lang[$locale]['enter_job_multiplier']; ?></div><div class='col-md-3'><?php echo generateJobMultiplierSelect($sqlcon,$lang[$locale]['please_select'],"job_multiplier",0,$lang[$locale]); ?></div><div class='col-md-3'></div></div>
				<div class='row'><div class='col-md-3'></div><div class='col-md-3'><?php echo $lang[$locale]['remarks']; ?></div><div class='col-md-3'><input class="form-control" type="text" name="remarks" value="<?php echo $edit_user->remarks; ?>"></div><div class='col-md-3'></div></div>
				<div class='row'><div class="col-md-12"><h4 class="text-center"><?php echo $lang[$locale]['edit_password']; ?> </h4></div></div>
				<div class='row'><div class='col-md-3'></div><div class='col-md-3'><?php echo $lang[$locale]['enter_password']; ?></div><div class='col-md-3'><input class="form-control" type="password" name="pw1"></div><div class='col-md-3'></div></div>
				<div class='row'><div class='col-md-3'></div><div class='col-md-3'><?php echo $lang[$locale]['confirm_password']; ?></div><div class='col-md-3'><input class="form-control" type="password" name="pw2"></div><div class='col-md-3'></div></div>
			</form>
		<div class='row'><div class='col-md-3'></div><div class='col-md-3'></div><div class='col-md-3'><button onclick="register_user();" class="btn btn-success"><?php echo $lang[$locale]['save']; ?></button></div><div class='col-md-3'></div></div>
		<?php
	}
	$stmt->close();
}
?>
