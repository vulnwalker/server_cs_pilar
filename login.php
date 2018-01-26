<?php

include 'include/config.php';
$username = $_POST['username'];
$password = sha1(md5($_POST['password']));

if (sqlNumRow(sqlQuery("SELECT * from users where username = '$username' and password = '$password' and jenis_user = '2' ")) !=0) {
	session_start();
	mkdir("temp/".$username);
	$_SESSION['username'] = $username;
	$_SESSION['status'] = "login";
	$getDataUser = sqlArray(sqlQuery("select * from users where username = '".$username."'"));
	$arrayHakAkses = explode(';',$getDataUser['hak_akses']);
	if(in_array('1',$arrayHakAkses)){
		header("location:pages.php?page=userManagement");
	}elseif(in_array('2',$arrayHakAkses)){
		header("location:pages.php?page=produk");
	}elseif(in_array('3',$arrayHakAkses)){
		header("location:pages.php?page=informasi");
	}elseif(in_array('4',$arrayHakAkses)){
		header("location:pages.php?page=acara");
	}elseif(in_array('5',$arrayHakAkses)){
		header("location:pages.php?page=slider");
	}elseif(in_array('6',$arrayHakAkses)){
		header("location:pages.php?page=lowonganKerja");
	}elseif(in_array('7',$arrayHakAkses)){
		header("location:pages.php?page=team");
	}elseif(in_array('8',$arrayHakAkses)){
		header("location:pages.php?page=setting");
	}else{
		header("location:pages.php?page=profile");
	}
}else{
	header("location:index.php");
}

?>
