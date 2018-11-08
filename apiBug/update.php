<?php
include 'config.php';
class listBug extends Config{
  function __construct(){
    foreach ($_POST as $key => $value) {
       $$key = $value;
    }
    $dataUpdate = array(
        'status' => $status,
        'note' => $note,
    );
    $query = $this->sqlUpdate("koreksi_aplikasi",$dataUpdate,"id = '$idEdit'");
    $this->sqlQuery($query);
    $arrayReturn = array(
      'content' => "OK",
      'cek' => "",
      'err' => "",
    );
    echo json_encode($arrayReturn);
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
