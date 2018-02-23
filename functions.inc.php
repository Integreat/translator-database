<?php

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function select_include($user)
{
	if($_GET['linkid'] == 2 AND $user->is_viewer)
		return "list.php";
	elseif($_GET['linkid'] == 3 AND $user->is_admin)
		return "admin.php";
	elseif($_GET['linkid'] == 4 AND $user->session_id == session_id())
		return "settings.php";
	elseif($_GET['linkid'] == 5 AND ($user->is_admin OR $user->session_id == session_id())) //list
		return("saveuser.php");
	elseif($_GET['linkid'] == 6)
		return "register.php";
	elseif($_GET['linkid'] == 7 AND ($user->is_viewer OR $user->is_admin))
		return "list-table.php";
	elseif($_GET['linkid'] == 8 AND $user->is_admin) //admin
		return("saveuser.php");
	elseif($_GET['linkid'] == 9 AND $user->is_admin) //edit user
		return("edit.php");
	elseif($_GET['linkid'] == 10 AND $user->is_admin) //delete user
		return("saveuser.php");
	else
		return("home.php");
}

function generateLanguageSelect($sqlcon,$default_text,$name,$checked = 0,$onclick = 0)
{
$result = $sqlcon->query("SELECT * FROM languages ORDER BY language ASC");

$return = "<div class=\"btn-group\">
	<button data-toggle=\"dropdown\" class=\"btn dropdown-toggle btn-info\"  data-placeholder=\"".$default_text."\">".$default_text."<span class=\"caret\"></span></button>
	<ul class=\"dropdown-menu\">\n";
	while($row = $result->fetch_assoc())
      $return .= "<li><input ".($checked[$row['id']] ? "checked " : "")."type=\"checkbox\" id=\"".$name."[".$row['id']."]\" value=\"".$row['id']."\" name=\"".$name."[".$row['id']."]\" ".($onclick ? "onclick=\"$onclick\"" : "")."><label for=\"".$name."[".$row['id']."]\" name=\"".$name."[".$row['language']."]\" value=\"".$row['id']."\">".$row['language']."</label></li>\n";
$return .= "	</ul>\n
</div>";
return $return;
}

function generateTimeslotSelect($sqlcon,$default_text,$name,$checked = 0,$onclick = 0)
{
$result = $sqlcon->query("SELECT * FROM timeslots");

$return = "<div class=\"btn-group\">
	<button data-toggle=\"dropdown\" class=\"btn dropdown-toggle btn-info\"  data-placeholder=\"".$default_text."\">".$default_text."<span class=\"caret\"></span></button>
	<ul class=\"dropdown-menu\">\n";
	while($row = $result->fetch_assoc())
      $return .= "<li><input ".($checked[$row['id']] ? "checked " : "")."type=\"checkbox\" id=\"".$name."[".$row['id']."]\" value=\"".$row['id']."\" name=\"".$name."[".$row['id']."]\" ".($onclick ? "onclick=\"$onclick\"" : "")."><label for=\"".$name."[".$row['id']."]\" name=\"".$name."[".$row['name']."]\" value=\"".$row['id']."\">".$row['name']."</label></li>\n";
$return .= "	</ul>\n
</div>";
return $return;
}

function generateJobMultiplierSelect($sqlcon,$default_text,$name,$job_multiplier,$lang)
{

	$return = "<div class=\"btn-group\">
	<button data-toggle=\"dropdown\" class=\"btn dropdown-toggle btn-info\"  data-placeholder=\"".$default_text."\">".$default_text."<span class=\"caret\"></span></button>
	<ul class=\"dropdown-menu\">\n";
	$return .= "<li><input ".($job_multiplier==1 ? "checked " : "")."type=\"radio\" id=\"".$name."[1]\" value=\"1\" name=\"$name\"><label for=\"".$name."[1]\" name=\"job_multiplier_label[1]\" value=\"1\">".$lang['job_normal']."</label></li>\n";
	$return .= "<li><input ".($job_multiplier==3 ? "checked " : "")."type=\"radio\" id=\"".$name."[2]\" value=\"3\" name=\"$name\"><label for=\"".$name."[2]\" name=\"job_multiplier_label[2]\" value=\"3\">".$lang['job_less']."</label></li>\n";
	$return .= "<li><input ".($job_multiplier==5 ? "checked " : "")."type=\"radio\" id=\"".$name."[3]\" value=\"5\" name=\"$name\"><label for=\"".$name."[3]\" name=\"job_multiplier_label[3]\" value=\"5\">".$lang['job_rare']."</label></li>\n";
	$return .= "	</ul>\n
	</div>";
	return $return;
}

function generateTableRow($user,$admin)
// (displayed user, admin user)
{
	echo "<tr id=\"user-".$user->userid."\"><td><span id=\"count-".$user->userid."\">#".(string)$user->job_count."</span> <a href=\"#\" id=\"increment-link-".$user->userid."\" onclick=\"job_increment(".$user->userid.");\"><span class=\"label label-success label-as-badge\" span=\"\">+1</span></a></td><td>".$user->name.($user->gender=='male'? "♂" : "").($user->gender=='female'? "♀" : "")."</td><td><a href=\"mailto: ".$user->email."\">".$user->email."</a>".($user->email_verified ? "<span class=\"glyphicon glyphicon-ok\" style=\"color: green;\"></span>":"")."<br>".$user->telephone."</td><td>";
	if(is_array($user->timeslots)) foreach($user->timeslots as $timeslot) {echo $timeslot."<br>";}
	echo"</td><td>";
	if(is_array($user->languages)) foreach($user->languages as $language) {echo $language."<br>";}
	echo "</td><td>".$user->remarks."<br>".$user->zip." ".$user->city."</td><td>"; 
	if($admin->is_admin)
	{
		echo "<a onclick=\"edit_user(".$user->userid.",'#user-".$user->userid."');\" href=\"#\"><span class=\"glyphicon glyphicon-pencil\"></span></a>";
		echo "<a onclick=\"del_user(".$user->userid.",'#user-".$user->userid."','".$lang[$locale]['confirm_remove_user']."');\" href=\"#\" class=\"text-danger\"><span class=\"glyphicon glyphicon-remove\"></span></a>";
	}
	echo "</td></tr>\n";
}
?>
