<?php
include 'config.php';
class listBug extends Config{
  function __construct(){
      $kondisiFilter = $_POST['kondisi'];
      $getDataKoreksi = $this->sqlQuery("select * from koreksi_aplikasi $kondisiFilter");
      while ($dataKoreksi = $this->sqlArray($getDataKoreksi)) {
          $getNamaPemda = $this->sqlArray($this->sqlQuery("select * from ref_pemda where id = '".$dataKoreksi['id_pemda']."'"));
          $getDataProduk = $this->sqlArray($this->sqlQuery("select * from produk where id = '".$dataKoreksi['id_aplikasi']."'"));
          $dir = "../../pilar.web.id/member/upload/laporan/".$dataKoreksi['id'];
          $scandir = scandir($dir);
          $arrayFile = array();
          foreach($scandir as $file) {
      			$ftype = filetype("$dir/$file");
      			$ftime = date("F d Y g:i:s", filemtime("$dir/$file"));
      			$size = filesize("$dir/$file")/1024;
      			$size = round($size,3);
      			if(function_exists('posix_getpwuid')) {
      				$fowner = @posix_getpwuid(fileowner("$dir/$file"));
      				$fowner = $fowner['name'];
      			} else {
      				//$downer = $uid;
      				$fowner = fileowner("$dir/$file");
      			}
      			if(function_exists('posix_getgrgid')) {
      				$fgrp = @posix_getgrgid(filegroup("$dir/$file"));
      				$fgrp = $fgrp['name'];
      			} else {
      				$fgrp = filegroup("$dir/$file");
      			}
      			if($size > 1024) {
      				$size = round($size/1024,2). 'MB';
      			} else {
      				$size = $size. 'KB';
      			}
      			if(!is_file("$dir/$file")) continue;
            $arrayFile[] = array(
              'namaFile' => $file,
              'fileLocation' => str_replace("../../","http://",$dir)."/".urlencode($file),
              'typeFile' => $ftype,
              'sizeFile' => $size,
              'last_modified' => $ftime,
              'owner' => $fowner,
              'permission' => $this->w("$dir/$file",$this->perms("$dir/$file")),
            );
      		}
          $arrayListKoreksi[] = array(
            'id' => $dataKoreksi['id'],
	    'note' => $dataKoreksi['note'],
            'namaPemda' => $getNamaPemda['nama'],
            'deskripsi' => $dataKoreksi['description'],
            'namaProduk' => $getDataProduk['nama_produk'],
            'status' => $dataKoreksi['status'],
            'tanggal' => $dataKoreksi['tanggal'],
            'file' => $arrayFile,
          );
      }
      echo json_encode($arrayListKoreksi);
  }

  function stringDetector($string){
      $string = str_replace("`","",$string);
      $string = str_replace("'","",$string);
      $string = str_replace(" ","",$string);
      return $string;
  }
  function getTahun($tanggal){
    $explodeTanggal = explode("-",$tanggal);
    return $explodeTanggal[0];
  }
  function w($dir,$perm) {
		if(!is_writable($dir)) {
			return "DENIED";
		} else {
			return "GRANTED";
		}
	}
	function perms($file){
		$perms = fileperms($file);
		if (($perms & 0xC000) == 0xC000) {
		// Socket
		$info = 's';
		} elseif (($perms & 0xA000) == 0xA000) {
		// Symbolic Link
		$info = 'l';
		} elseif (($perms & 0x8000) == 0x8000) {
		// Regular
		$info = '-';
		} elseif (($perms & 0x6000) == 0x6000) {
		// Block special
		$info = 'b';
		} elseif (($perms & 0x4000) == 0x4000) {
		// Directory
		$info = 'd';
		} elseif (($perms & 0x2000) == 0x2000) {
		// Character special
		$info = 'c';
		} elseif (($perms & 0x1000) == 0x1000) {
		// FIFO pipe
		$info = 'p';
		} else {
		// Unknown
		$info = 'u';
		}
			// Owner
		$info .= (($perms & 0x0100) ? 'r' : '-');
		$info .= (($perms & 0x0080) ? 'w' : '-');
		$info .= (($perms & 0x0040) ?
		(($perms & 0x0800) ? 's' : 'x' ) :
		(($perms & 0x0800) ? 'S' : '-'));
		// Group
		$info .= (($perms & 0x0020) ? 'r' : '-');
		$info .= (($perms & 0x0010) ? 'w' : '-');
		$info .= (($perms & 0x0008) ?
		(($perms & 0x0400) ? 's' : 'x' ) :
		(($perms & 0x0400) ? 'S' : '-'));
		// World
		$info .= (($perms & 0x0004) ? 'r' : '-');
		$info .= (($perms & 0x0002) ? 'w' : '-');
		$info .= (($perms & 0x0001) ?
		(($perms & 0x0200) ? 't' : 'x' ) :
		(($perms & 0x0200) ? 'T' : '-'));
		return $info;
	}


}

$wakwaw = new listBug();







 ?>
