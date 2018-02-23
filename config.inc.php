<?php

$locale = "de";

//SQL-Configuration

global $sqlcon;



$config['sql']['host']		= "localhost";
$config['sql']['user']		= "interpreter";
$config['sql']['database']	= "interpreter";
$config['sql']['password']	= "insert";

$sqlcon = new mysqli($config['sql']['host'], $config['sql']['user'], $config['sql']['password'], $config['sql']['database']) or die ("No connection.");

$charset="utf8";
mysqli_query($sqlcon,"SET NAMES '".$charset."'");

//SMTP-Configuration

$config['mail']['host'] 	= "smtp.1und1.de";
$config['mail']['auth']		= true;
$config['mail']['port']		= 465;
$config['mail']['secure']	= "ssl";
$config['mail']['user']		= "mail@domain.tld";
$config['mail']['password']	= "insert";
$config['mail']['name']		= "Interpreter Database";

//Other options
$config['other']['anti_brutforce'] 	= false; //enable ip logging for brute force prevention
$config['path']['bootstrap']		= "/bootstrap/";
$config['path']['server'] = "https://domain.tld";
$config['path']['folder'] = "/";
$config['login']['timeout']			= 600;
?>
