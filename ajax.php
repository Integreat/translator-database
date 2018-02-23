<?php
include("includes.php");

session_start();

$user = new user($sqlcon,session_id());

include select_include($user,$_GET);

?>
