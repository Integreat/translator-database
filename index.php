<?php

include("includes.php");

session_start();

$user = new user($sqlcon,session_id(),$_POST['email'],$_POST['password']);

if($_POST['logout']) { $user->logout(); }

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title><?php echo $lang[$locale]['title']; ?></title>

		<!-- Bootstrap -->
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="bootstrap/css/dropdowns-enhancement.css" rel="stylesheet">

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="bootstrap/js/jquery.min.js"></script>
		<!--<script src="bootstrap/js/jquery.tablesorter.js"></script> -->
		
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="bootstrap/js/bootstrap.min.js"></script>
		<script src="bootstrap/js/dropdowns-enhancement.js"></script> 
		<!-- custom java script -->
		<script src="functions.js"></script>
	</head>
	<body>

		<div class="container">

			<!-- Static navbar -->
			<nav class="navbar navbar-default">
				<div class="container-fluid">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="?"><?php echo $lang[$locale]['title']; ?></a>
					</div>
					<div id="navbar" class="navbar-collapse collapse">
						<ul class="nav navbar-nav">
							<li class="<?php if(!$_GET['linkid'] OR $_GET['linkid']==1) echo "active"; ?>"><a href="?linkid=1"><?php echo $lang[$locale]['menu_home']; ?></a></li>
							<?php if($user->is_viewer) { ?><li class="<?php if($_GET['linkid']==2) echo "active"; ?>"><a href="?linkid=2"><?php echo $lang[$locale]['menu_list']; ?></a></li><?php } ?>
							<?php if($user->is_admin) { ?><li class="<?php if($_GET['linkid']==3) echo "active"; ?>"><a href="?linkid=3"><?php echo $lang[$locale]['menu_admin']; ?></a></li> <?php } ?>
							<?php if($user->session_id == session_id()) { ?><li class="<?php if($_GET['linkid']==4) echo "active"; ?>"><a href="?linkid=4"><?php echo $lang[$locale]['menu_settings']; ?></a></li> <?php } ?>
						</ul>
						<?php 
							if($user->session_id == session_id()) 
							{
								?>
								
								<form class="navbar-form navbar-right" method="post" action="?">
									<div class="form-group">
										<?php echo $lang[$locale]['logged_in']." ".$user->email; ?>
									</div>
									<div class="form-group">
										<button type="submit" class="btn btn-warning"><?php echo $lang['de']['logout']; ?></button>
										<input type="hidden" name="logout" value="1">
									</div>
								</form>
								<?php
							}
							else
							{ 
								?>
								
								<form class="navbar-form navbar-right" method="post" action="?">		
									<div class="form-group">
										<input name="email" type="email" placeholder="Email" class="form-control">
									</div>
									<div class="form-group">
										<input name="password" type="password" placeholder="Password" class="form-control">
									</div>
									<button type="submit" class="btn btn-success"><?php echo $lang['de']['login']; ?></button>
								</form>
								<?php
							}
							?>

					</div><!--/.nav-collapse -->
				</div><!--/.container-fluid -->
			</nav>
			
			<?php
				include select_include($user);
			?>
		</div>
	</body>
</html>
