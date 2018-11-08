<?php

date_default_timezone_set('Asia/Jakarta');
error_reporting(0);
class Config{
	 function connection(){
		return mysqli_connect("localhost", "root", "12345", "pilar_web");
	}
	 function connectionBaru(){
		return mysqli_connect("localhost", "root", "rf09thebye", "db_atsb_karawang_mapping_disdik");
	}
   function sqlQuery($script){
    return mysqli_query($this->connection(), $script);
  }
   function sqlQueryBaru($script){
    return mysqli_query($this->connectionBaru(), $script);
  }

  function sqlInsert($table, $data){
  	    if (is_array($data)) {
  	        $key   = array_keys($data);
  	        $kolom = implode(',', $key);
  	        $v     = array();
  	        for ($i = 0; $i < count($data); $i++) {
  	            array_push($v, "'" . $data[$key[$i]] . "'");
  	        }
  	        $values = implode(',', $v);
  	        $query  = "INSERT INTO $table ($kolom) VALUES ($values)";
  	    } else {
  	        $query = "INSERT INTO $table $data";
  	    }
  		  return $query;

  	}

  function sqlUpdate($table, $data, $where){
      if (is_array($data)) {
          $key   = array_keys($data);
          $kolom = implode(',', $key);
          $v     = array();
          for ($i = 0; $i < count($data); $i++) {
              array_push($v, $key[$i] . " = '" . $data[$key[$i]] . "'");
          }
          $values = implode(',', $v);
          $query  = "UPDATE $table SET $values WHERE $where";
      } else {
          $query = "UPDATE $table SET $data WHERE $where";
      }

     return $query;
  }

	function sqlArray($sqlQuery){
			return mysqli_fetch_assoc($sqlQuery);
	}

	function sqlRowCount($sqlQuery){
			return mysqli_num_rows($sqlQuery);
	}

	function curlJNEFrom($kota){
		$kota = strtoupper($kota);
		$data_to_post = array();
		$data_to_post['attacker'] = 'VulnWalker';
		$curl = curl_init();
		curl_setopt($curl,CURLOPT_URL, "http://www.cektarif.com/exp/jne/jne.getoption.php?s=asal&term=".$kota);
		curl_setopt($curl,CURLOPT_POST, sizeof($data_to_post));
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)");
		curl_setopt($curl,CURLOPT_POSTFIELDS, $data_to_post);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($curl);
		return $result;
	}

	function curlJNETo($kota){
		$kota = strtoupper($kota);
		$data_to_post = array();
		$data_to_post['attacker'] = 'VulnWalker';
		$curl = curl_init();
		curl_setopt($curl,CURLOPT_URL, "http://www.cektarif.com/exp/jne/jne.getoption.php?s=tujuan&term=".$kota);
		curl_setopt($curl,CURLOPT_POST, sizeof($data_to_post));
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)");
		curl_setopt($curl,CURLOPT_POSTFIELDS, $data_to_post);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($curl);
		return $result;
	}
}
?>
