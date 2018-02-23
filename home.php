			<!-- Main component for a primary marketing message or call to action -->
			<div class="jumbotron">
				<h1 class="text-center">Willkommen bei der CampusAsyl Dolmetscher Datenbank</h1>
				<p>Wenn Sie als Dolmetscher Geflüchteten helfen möchten, können Sie sich unten als Dolmetscher registrieren. Hilfsorganisationen können spezielle Zugänge beantragen, um Zugriff auf die Dolmetscher-Datenbank zu erhalten.</p>
				<p>Mit Hilfe der Dolmetscher versuchen wir Gefl&uuml;chteten bei Amtsg&auml;ngen, Krankenhausbesuchen, beim Deutschunterricht oder bei Bedarf auch einfach im Alltag zu helfen.</p>
			</div>

			<div id="register-form">
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
			</div>
			<script>
				function register_user() {

					var action = "ajax.php?linkid=6",
						method = "POST",
						data   = $("#register-user").serialize();
						
					$.ajax({
						url : action,
						type : method,
						data : data
					}).done(function (data) {
						$("#register-form").html(data);
					});
				};
			</script>



