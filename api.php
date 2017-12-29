<?php include "include/config.php";
  $page = @$_GET['page'];

switch($page){
  case 'informasi':{
    include 'pages/informasi.php';
    break;
  }
  case 'produk':{
    include 'pages/produk.php';
    break;
  }
  case 'acara':{
    include 'pages/acara.php';
    break;
  }
  case 'slider':{
    include 'pages/slider.php';
    break;
  }
  case 'setting':{
    include 'pages/setting.php';
    break;
  }

  default:{

  }

}

?>
