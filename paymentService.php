<?php
include "include/config.php";

$getAcara = sqlQuery("select * from acara where replace(tanggal,'-','') >= ".str_replace('-','',date("Y-m-d"))." ");
while ($dataAcara = sqlArray($getAcara)) {
  echo "masuk while acara \n";
  $getReservasi = sqlQuery("select * from reservasi_acara where id_acara = '".$dataAcara['id']."' and status !='2' and status !='4'  and status !='3'");
  while ($dataReservasi = sqlArray($getReservasi)) {
    echo "masuk while pendaftaran \n";
      if(str_replace('-','',getDateDealine($dataReservasi['tanggal_daftar'],$dataAcara['deadline_pembayaran'])) < str_replace('-','',date("Y-m-d"))){
        sqlQuery("update reservasi_acara set status = '3' where id = '".$dataReservasi['id']."'");
        $getDataUser = sqlArray(sqlQuery("select * from users where id = '".$dataReservasi['id_user']."'"));
        sendMail("payment@pilar.web.id",$getDataUser['email'],"Pendaftaran di batalkan","Mohon maaf pendaftaran anda atas acara ".$dataAcara['nama_acara']." dibatalkan secara otomatis, dikarenakan anda tidak melakukan pembyaran dalam batas waktu yang di tentukan <br> Terimakasih");
      }
  }
}

function getRangeDate($start, $end, $format = 'Y-m-d') {
    $array = array();
    $interval = new DateInterval('P1D');

    $realEnd = new DateTime($end);
    $realEnd->add($interval);

    $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

    foreach($period as $date) {
        $array[] = $date->format($format);
    }

    return sizeof($array);
}

function getDateDealine($tanggalDaftar,$deadlinePembayaran){
  $date = new DateTime($tanggalDaftar);
  $date->modify("+$deadlinePembayaran day");
  return $date->format('Y-m-d');
}




?>
