<?php

include 'include/config.php';
$username = $_POST['username'];
$password = sha1(md5($_POST['password']));

if (sqlNumRow(sqlQuery("SELECT * from users where username = '$username' and password = '$password' and jenis_user = '2' ")) !=0) {
	session_start();
	mkdir("temp/".$username);
	$_SESSION['username'] = $username;
	$_SESSION['status'] = "login";
	header("location:pages.php?page=userManagement");
}else{
	header("location:index.php");
}

?>
