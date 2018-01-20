<?php
$tipe = @$_GET['tipe'];
$cek = "";
$err = "";
$content = "";
$tableName = "acara";
if(!empty($tipe)){
  include "../include/config.php";
  foreach ($_POST as $key => $value) {
      $$key = $value;
  }
}
function getKordinat($alamat){
  $curl = curl_init();
  curl_setopt($curl,CURLOPT_URL, "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($alamat)."&country:ID&key=AIzaSyCJNf9tt4XIkzl5mAaAA0aehyVrdaS6awU");
  curl_setopt($curl,CURLOPT_POST, sizeof($arrayData));
  curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36");
  curl_setopt($curl,CURLOPT_POSTFIELDS, $arrayData);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  $result = json_decode(curl_exec($curl));
  $resultJSON = $result->results;
  $kordinatX = $resultJSON[0]->geometry->location->lat;
  $kordinatY = $resultJSON[0]->geometry->location->lng;
  return $kordinatX.",".$kordinatY;
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

switch($tipe){
  case 'generateLocation':{
      $explodeKordinat = explode(',',$koordinat);
      $kordinatX = str_replace("(","",$explodeKordinat[0]);
      $kordinatY = str_replace(')','',$explodeKordinat[1]);
      $kordinatY = str_replace(' ','',$kordinatY);
      $curl = curl_init();
			curl_setopt($curl,CURLOPT_URL, "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$kordinatX.",".$kordinatY."&key=AIzaSyCJNf9tt4XIkzl5mAaAA0aehyVrdaS6awU");
			curl_setopt($curl,CURLOPT_POST, sizeof($arrayData));
			curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36");
			curl_setopt($curl,CURLOPT_POSTFIELDS, $arrayData);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      $result = json_decode(curl_exec($curl));
      $resultJSON = $result->results;
      $lokasi = $resultJSON[0]->formatted_address;
      $content = array('lat' => str_replace("(","",$explodeKordinat[0]),'lang' => str_replace(')','',$explodeKordinat[1]), 'lokasi' => $lokasi );
      echo generateAPI($cek,$err,$content);
    break;
    }
  case 'loadTablePendaftaran':{
    if(!empty($searchData)){
      //$getColom = sqlQuery("desc $tableName");
      // while ($dataColomn = sqlArray($getColom)) {
      //   $arrKondisi[] = $dataColomn['Field']." like '%$searchData%' ";
      // }
      $getDataUser = sqlQuery("select * from users where nama like '%$searchData%'");
      while ($dataUser = sqlArray($getDataUser)) {
          $arrKondisi[] = " id_user =  '".$dataUser['id']."'";
      }
      $getDataInstansi = sqlQuery("select * from users where instansi like '%$searchData%'");
      while ($dataInstansi = sqlArray($getDataInstansi)) {
          $arrKondisi[] = " id_user =  '".$dataInstansi['id']."'";
      }

      $arrKondisi[] = " tanggal_daftar like '%".generateDate($searchData)."%'";
      $arrKondisi[] = " nomor_invoice like '%$searchData%'";
      $arrKondisi[] = " jumlah_orang like '%$searchData%'";

      $kondisi = join(" or ",$arrKondisi);
      $kondisi = " and ($kondisi) ";
    }
    if (!empty($sorter)) {
          $kondisiSort = "ORDER BY $sorter $ascending";
    }
    if(!empty($limitTable)){
        if($pageKe == 1){
           $queryLimit  = " limit 0,$limitTable";
        }else{
           $dataMulai = ($pageKe - 1)  * $limitTable;
           $dataMulai +=1;
           $queryLimit  = " limit $dataMulai,$limitTable";
        }

    }
    if(!empty($statusPendaftaran)){
      if($statusPendaftaran == 'menungguPembayaran'){
        $kondisiStatus = " and status = '0'";
      }elseif($statusPendaftaran == 'konfirmasiPembayaran'){
        $kondisiStatus = " and status = '1'";
      }elseif($statusPendaftaran == 'pembayaranDiterima'){
        $kondisiStatus = " and status = '2'";
      }elseif($statusPendaftaran == 'diBatalkan'){
        $kondisiStatus = " and status = '4'";
      }elseif($statusPendaftaran == 'deadlinePembayaran'){
        $kondisiStatus = " and status = '3'";
      }
    }
    $getData = sqlQuery("select * from reservasi_acara where id_acara = '$idAcara' $kondisiStatus $kondisi $kondisiSort $queryLimit");
    $cek = "select * from reservasi_acara where id_acara = '$idAcara' $kondisiStatus $kondisi $kondisiSort $queryLimit";
    $nomor = 1;
    $nomorCB = 0;
    while($dataAcara = sqlArray($getData)){
      foreach ($dataAcara as $key => $value) {
          $$key = $value;
      }

        $getNamaMember = sqlArray(sqlQuery("select * from users where id = '$id_user'"));
        $nama = $getNamaMember['nama'];
        $getDataAcara = sqlArray(sqlQuery("select * from acara where id = '$id_acara'"));
        $totalTiket = $jumlah_orang  * $getDataAcara['harga_tiket'];
        $totalKamar = $jumlah_kamar  * $getDataAcara['harga_kamar'] * $getDataAcara['lama_acara'];
        $totalExtraBed = $extra_bed  * $getDataAcara['extra_bed'] * $getDataAcara['lama_acara'];
        if(empty($status)){
            $statusPendaftaran = "MENUNGGU PEMBAYARAN";
        }elseif($status == '1'){
            $statusPendaftaran = "<span style='cursor:pointer' onclick='konfirmasiPembayaran($id)'> KONFIRMASI PEMBAYARAN </span>";
        }elseif($status == '2'){
            $statusPendaftaran = "<span style='cursor:pointer' onclick='pembayaranDiterima($id)'> PEMBAYARAN DITERIMA </span>";
        }elseif($status == '3'){
            $statusPendaftaran = "<span stye='color:red'>DEALINE BAYAR</span>";
        }elseif($status == '4'){
            $statusPendaftaran = "<span stye='color:red'>DIBATALKAN</span>";
        }
        $getDataUser = sqlArray(sqlQuery("select * from users where id ='$id_user'"));
        $instansiUser = $getDataUser['instansi'];
      $data .= "     <tr>
                        <td class='text-center' width='20px;'  style='vertical-align:middle;'>$nomor</td>
                        <td class='text-center' width='20px;'  style='vertical-align:middle;'>
                          <div  class='checkbox checkbox-inline checkbox-styled'>
                                  <label>
                                  ".setCekBox($nomorCB,$id,'','acara')."
                              <span></span>
                            </label>
                          </div>
                        </td>
                        <td style='vertical-align:middle;'>$nama</td>
                        <td style='vertical-align:middle;'>$instansiUser</td>
                        <td style='vertical-align:middle;'>".generateDate($tanggal_daftar)."</td>
                        <td style='vertical-align:middle;'>$nomor_invoice</td>
                        <td style='vertical-align:middle;text-align:right;'>$jumlah_orang </td>
                        <td style='vertical-align:middle;text-align:right;'>".numberFormat($getDataAcara['harga_tiket'])."</td>
                        <td style='vertical-align:middle;text-align:right;'>".numberFormat($totalTiket)."</td>
                        <td style='vertical-align:middle;text-align:center;'>$statusPendaftaran</td>

                             </tr>
                  ";
        $nomor += 1;
        $nomorCB += 1;
        $totalSumTiket += $totalTiket;
        // <td style='vertical-align:middle;text-align:center;'>$keterangan</td>
    }

    $tabelBody = "

      <thead>
        <tr>
          <th class='text-center' width='20px;'>No</th>
          <th class='text-center' width='20px;'>
           <div class='checkbox checkbox-inline checkbox-styled' >
            <label>
              <input type='checkbox' name='acara_toogle' id='acara_toogle' onclick=checkSemua($nomorCB,'acara_cb','acara_toogle','acara_jmlcek',this)>
              <span></span>
            </label>
          </div>
          </th>
          <th class='col-lg-2'>Nama</th>
          <th class='col-lg-2'>Instansi</th>
          <th class='col-lg-1'>Tanggal Pendaftaran</th>
          <th class='col-lg-1'>Nomor Invoice</th>
          <th class='col-lg-1' style='text-align:center;'>Jumlah Daftar</th>
          <th class='col-lg-1' style='text-align:center;'>Harga Partisipasi</th>
          <th class='col-lg-2' style='text-align:center;'>Total</th>
          <th class='col-lg-2 text-center' >Status
<input type='hidden' name='hiddenIdAcara' id='hiddenIdAcara' value='$idAcara'>
<button type='button' id='pemicuPopup' style='display:none;' data-toggle='modal' data-target='#myModal'>SHOW</button>
</th>
        </tr>
      </thead>
      <tbody>
        $data

        <tr>
                          <td class='text-center' width='20px;'  style='vertical-align:middle;'></td>
                          <td class='text-center' width='20px;'  style='vertical-align:middle;'></td>
                          <td style='vertical-align:middle;'></td>
                          <td style='vertical-align:middle;'></td>
                          <td style='vertical-align:middle;'></td>
                          <td style='vertical-align:middle;'></td>
                          <td style='vertical-align:middle;text-align:right;'></td>
                          <td style='vertical-align:middle;text-align:right;'><b>TOTAL</b></td>
                          <td style='vertical-align:middle;text-align:right;'>".numberFormat($totalSumTiket)."</td>
                          <td style='vertical-align:middle;text-align:center;'></td>
                          <td style='vertical-align:middle;text-align:center;'></td>
                               </tr>
      </tbody>


    ";

    $jumlahData = sqlNumRow(sqlQuery("select * from reservasi_acara where id_acara = '$idAcara' $kondisi "));
    $jumlahPage =ceil($jumlahData / $limitTable) ;
    for ($i=1; $i <= $jumlahPage ; $i++) {
        if($pageKe == $i){
          $dataPagging .= "<li class='active'>
                              <a onclick=currentPage($i)>$i</a>
                          </li>";
        }else{
          $dataPagging .= "<li >
                              <a onclick=currentPage($i)>$i</a>
                          </li>";
        }

    }
    $tabelFooter = "
      <ul class='pagination pagination-info'>
        $dataPagging
      </ul>
    <input type='hidden' name='acara_jmlcek' id='acara_jmlcek' value='0'>";
    $content = array("tabelPendaftaran" => $tabelBody, 'tabelFooter' => $tabelFooter);
    echo generateAPI($cek,$err,$content);
  break;
  }
  case 'konfirmasiPendaftaran':{

    $getData = sqlArray(sqlQuery("select * from reservasi_acara where id = '".$acara_cb[0]."'"));
    $arrayStatus = array(
                            array('1','KONFIRMASI PEMBAYARAN'),
                            array('2','PEMBAYARAN DITERIMA'),
                            array('4','BATALKAN PENDAFTAR'),
                          );
    $contentModal = "
    <div class='section-body contain-lg'>
        <div class='card'>
          <div class='card-body floating-label'>
            <div class='row'>
              <div class='col-sm-12'>
                <div class='form-group'>
                  ".cmbArray("statusPendaftaran",$getData['status'],$arrayStatus,"-- STATUS --","class='form-control'")."
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>";

    $content = array('contentModal' => $contentModal,'id' => $acara_cb[0]);

    echo generateAPI($cek,$err,$content);
  break;
  }
  case 'saveAcara':{
   if(empty($namaAcara)){
       $err = "Isi Nama Acara";
   }elseif(empty($tanggalAcara)){
       $err = "Isi tanggal acara mulai";
   }elseif(empty($tanggalAcaraSelesai)){
       $err = "Isi tanggal acara selesai";
   }elseif(empty(removeExtJam($jamAcara)) || removeExtJam($jamAcara) == ':'){
       $err = "Isi jam acara mulai";
   }elseif(empty(removeExtJam($jamAcaraSelesai)) || removeExtJam($jamAcaraSelesai) == ':'){
       $err = "Isi jam acara selesai";
   }elseif(empty($kuotaAcara)){
       $err = "Isi kuota acara";
   }elseif(empty(removeExtHarga($hargaTiket))){
       $err = "Isi harga partisipasi";
   }elseif(empty($deadlinePembayaran)){
       $err = "Isi lama deadline pembayaran";
   }elseif(empty($lokasiAcara)){
       $err = "Isi deskripsi acara";
   }elseif(empty($deskripsiAcara)){
       $err = "Isi deskripsi acara";
   }elseif(empty($statusPublish)){
       $err = "pilih status publish";
   }
   if(empty($err)){
     if(!empty($tempKordinat)){
       $kordinatLocation = $kordinatX.",".$kordinatY;
     }else{
       $kordinatLocation = getKordinat($lokasiAcara);
     }

     $imageTitle = baseToImage($baseImageTitle,"images/acara/".md5(date("Y-m-d")).md5(date("H:i:s")));
     $undangan = baseToImage($baseUndangan,"images/acara/".md5(date("Y-m-d")).md5(date("H:i:s")).".pdf");
     $data = array(
               'nama_acara' => $namaAcara,
               'tanggal' => generateDate($tanggalAcara),
               'jam' => removeExtJam($jamAcara),
               'tanggal_selesai' => generateDate($tanggalAcaraSelesai),
               'jam_selesai' => removeExtJam($jamAcaraSelesai),
               'lokasi' =>  $lokasiAcara,
               'deskripsi' =>  base64_encode($deskripsiAcara),
               'koordinat' => $kordinatLocation,
               'kuota' => $kuotaAcara,
               'harga_tiket' => removeExtHarga($hargaTiket),
               'lama_acara' => getRangeDate(generateDate($tanggalAcara),generateDate($tanggalAcaraSelesai)),
               'reversed' => 0,
               'deadline_pembayaran' => $deadlinePembayaran,
               'image_title' => "images/acara/".md5(date("Y-m-d")).md5(date("H:i:s")),
               'undangan' => "images/acara/".md5(date("Y-m-d")).md5(date("H:i:s")).".pdf",
               'publish' => $statusPublish
     );
     $query = sqlInsert("acara",$data);
     sqlQuery($query);
     $cek = $query;
   }
   echo generateAPI($cek,$err,$content);
 break;
 }

 case 'saveEditAcara':{
   if(empty($namaAcara)){
       $err = "Isi Nama Acara";
   }elseif(empty($tanggalAcara)){
       $err = "Isi tanggal acara mulai";
   }elseif(empty($tanggalAcaraSelesai)){
       $err = "Isi tanggal acara selesai";
   }elseif(empty(removeExtJam($jamAcara)) || removeExtJam($jamAcara) == ':'){
       $err = "Isi jam acara mulai";
   }elseif(empty(removeExtJam($jamAcaraSelesai)) || removeExtJam($jamAcaraSelesai) == ':'){
       $err = "Isi jam acara selesai";
   }elseif(empty($kuotaAcara)){
       $err = "Isi kuota acara";
   }elseif(empty(removeExtHarga($hargaTiket))){
       $err = "Isi harga partisipasi";
   }elseif(empty($deadlinePembayaran)){
       $err = "Isi lama deadline pembayaran";
   }elseif(empty($lokasiAcara)){
       $err = "Isi deskripsi acara";
   }elseif(empty($deskripsiAcara)){
       $err = "Isi deskripsi acara";
   }elseif(empty($statusPublish)){
       $err = "pilih status publish";
   }
   if(empty($err)){
     if(!empty($tempKordinat)){
       $kordinatLocation = $kordinatX.",".$kordinatY;
     }else{
       $kordinatLocation = getKordinat($lokasiAcara);
     }

     $imageTitle = baseToImage($baseImageTitle,"images/acara/".md5(date("Y-m-d")).md5(date("H:i:s")));
     $undangan = baseToImage($baseUndangan,"images/acara/".md5(date("Y-m-d")).md5(date("H:i:s")).".pdf");
     $data = array(
               'nama_acara' => $namaAcara,
               'tanggal' => generateDate($tanggalAcara),
               'jam' => removeExtJam($jamAcara),
               'tanggal_selesai' => generateDate($tanggalAcaraSelesai),
               'jam_selesai' => removeExtJam($jamAcaraSelesai),
               'lokasi' =>  $lokasiAcara,
               'deskripsi' =>  base64_encode($deskripsiAcara),
               'koordinat' => $kordinatLocation,
               'kuota' => $kuotaAcara,
               'harga_tiket' => removeExtHarga($hargaTiket),
               'lama_acara' => getRangeDate(generateDate($tanggalAcara),generateDate($tanggalAcaraSelesai)),
               'reversed' => 0,
               'deadline_pembayaran' => $deadlinePembayaran,
               'image_title' => "images/acara/".md5(date("Y-m-d")).md5(date("H:i:s")),
               'undangan' => "images/acara/".md5(date("Y-m-d")).md5(date("H:i:s")).".pdf",
               'publish' => $statusPublish
     );
     $query = sqlUpdate("acara",$data,"id = '$idEdit'");
     sqlQuery($query);
     $cek = $query;
     }
     echo generateAPI($cek,$err,$content);
   break;
   }

   case 'savePendaftaran':{
     if(empty($statusPendaftaran)){
         $err = "Pilih status pendaftaran";
     }
     if(empty($err)){
       $getDataPendaftaran = sqlArray(sqlQuery("select * from reservasi_acara where id = '$idEdit'"));
       $getDataAcara = sqlArray(sqlQuery("select * from acara where id ='".$getDataPendaftaran['id_acara']."'"));
       $getEmailTujuan = sqlArray(sqlQuery("select * from users where id = '".$getDataPendaftaran['id_user']."'"));
       $namaAcara = $getDataAcara['nama_acara'];
       $tanggalAcara = generateDate($getDataAcara['tanggal']);

       if($statusPendaftaran == '2'){

          $isiEmail = "<table border='0' cellpadding='0' cellspacing='0' width='600'>
          	<tbody>
          		<tr>
          			<td align='center' valign='top'>

          				<table border='0' cellpadding='0' cellspacing='0' width='600'>
          					<tbody>
          						<tr>
          							<td valign='top'>

          								<table border='0' cellpadding='20' cellspacing='0' width='100%'>
          									<tbody>
          										<tr>
          											<td valign='top'>

          												<h2>$namaAcara ($tanggalAcara)</h2>

          												<p>
          													<br>
          												</p>

          												<p>Pembayaran anda telah kami terima, dengan nomor invoice <b>".$getDataAcara['nomor_invoice']." rincian sebagai berikut :</p>

          												<table border='1' cellpadding='6' cellspacing='0'>
                                    <thead>
                                      <th>Item</th>
                                      <th>Jumlah</th>
                                      <th>Harga</th>
                                      <th>Total</th>
                                    </thead>
          													<tbody>
          														<tr>
          															<td style='width: 43.0463%;'>Jumlah Perserta</td>
          															<td style='width: 12.9184%; text-align:right;'>".$getDataPendaftaran['jumlah_orang']."</td>
          															<td style='width: 25.0000%; text-align:right;'>".numberFormat($getDataAcara['harga_tiket'])."</td>
          															<td style='width: 18.9897%; text-align:right;'>".numberFormat($getDataPendaftaran['jumlah_orang'] * $getDataAcara['harga_tiket'])."</td>
          														</tr>
                                      $detailInvoice

                                    </tbody>
          													<tfoot>
          														<tr>
          															<th colspan='3' scope='row' style='width: 55.9647%;'>Total:
          															</th>
          															<td style='width: 18.9897%; text-align:right;'>".numberFormat(($getDataPendaftaran['jumlah_orang'] * $getDataAcara['harga_tiket']))."</td>
          														</tr>
          													</tfoot>
          												</table>

          												<table border='0' cellpadding='0' cellspacing='0'>
          													<tbody>
          														<tr>
          															<td valign='top' width='50%'>
          																<h2>Detail acara : <a href='http://pilar.web.id/?page=viewAcara&id=".$getDataAcara['id']."'>http://pilar.web.id/?page=viewAcara&id=".$getDataAcara['id']."</a></h2><address><br></address></td>
          														</tr>
          													</tbody>
          												</table>
          											</td>
          										</tr>
          									</tbody>
          								</table>
          							</td>
          						</tr>
          					</tbody>
          				</table>
          			</td>
          		</tr>
          		<tr>
          			<td align='center' valign='top'>

          				<table border='0' cellpadding='10' cellspacing='0' width='600'>
          					<tbody>
          						<tr>
          							<td valign='top'>

          								<table border='0' cellpadding='10' cellspacing='0' width='100%'>
          									<tbody>
          										<tr>
          											<td colspan='2' valign='middle'>

          												<p>office@pilar.web.id</p>
          											</td>
          										</tr>
          									</tbody>
          								</table>
          							</td>
          						</tr>
          					</tbody>
          				</table>
          			</td>
          		</tr>
          	</tbody>
          </table>

          <p>
          	<br>
          </p>
";
        if($getDataPendaftaran['status'] !='2'){
          sendMail("Event Pilar <event@pilar.web.id>",$getEmailTujuan['email'],"Pembayaran diterima",$isiEmail);
          sqlQuery("update acara set reversed = reversed + ".$getDataPendaftaran['jumlah_orang']." where id = '".$getDataAcara['id']."'");
        }
      }elseif ($statusPendaftaran == '4') {
          $isiEmail = "Pendaftaran anda untuk acara ".$getDataAcara['nama_acara']." telah di batalkan oleh kami, info lebih lanjut hubungi kami via email office@pilar.web.id";
          sendMail("Event Pilar <event@pilar.web.id>",$getEmailTujuan['email'],"Pendaftaran Dibatalkan",$isiEmail);

      }
       $data = array(
                 'status' => $statusPendaftaran,
       );
       $query = sqlUpdate("reservasi_acara",$data,"id = '$idEdit'");
       sqlQuery($query);
       $cek = $query;
       }
       echo generateAPI($cek,$err,$content);
   break;
   }

    case 'Hapus':{
      for ($i=0; $i < sizeof($acara_cb) ; $i++) {
        $query = "delete from $tableName where id = '".$acara_cb[$i]."'";
        sqlQuery($query);
      }

      $cek = $query;
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'Edit':{

      $content = array("idEdit" => $acara_cb[0]);
      echo generateAPI($cek,$err,$content);
    break;
    }
    case 'konfirmasiPembayaran':{
      $getDataReservasi = sqlArray(sqlQuery("select * from reservasi_acara where id = '$id'"));
      $getNamaUser = sqlArray(sqlQuery("select * from users where id = '".$getDataReservasi['id']."'"));
      $content = array("baseImage" => $getDataReservasi['bukti_transfer'], 'caption' => "Bukti Transfer ".$getNamaUser['nama'] );
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'loadTable':{
      if(!empty($searchData)){
        $getColom = sqlQuery("desc $tableName");
        while ($dataColomn = sqlArray($getColom)) {

        }
        $arrKondisi[] = "nama_acara like '%$searchData%' ";
        $arrKondisi[] = "lokasi like '%$searchData%' ";
        $arrKondisi[] = "tanggal like '%$searchData%' ";
        $arrKondisi[] = "jam like '%$searchData%' ";
        $arrKondisi[] = "lama_acara like '%$searchData%' ";
        $arrKondisi[] = "reversed like '%$searchData%' ";
        $arrKondisi[] = "kuota like '%$searchData%' ";
        $kondisi = join(" or ",$arrKondisi);
        $kondisi = " where $kondisi ";
      }
      if(!empty($limitTable)){
          if($pageKe == 1){
             $queryLimit  = " limit 0,$limitTable";
          }else{
             $dataMulai = ($pageKe - 1)  * $limitTable;
             $dataMulai +=1;
             $queryLimit  = " limit $dataMulai,$limitTable";
          }

      }
      if (!empty($sorter)) {
            $kondisiSort = "ORDER BY $sorter $ascending";
      }
      $getData = sqlQuery("select * from $tableName $kondisi $kondisiSort $queryLimit");
      $cek = "select * from $tableName $kondisi $queryLimit";
      $nomor = 1;
      $nomorCB = 0;
      while($dataAcara = sqlArray($getData)){
        foreach ($dataAcara as $key => $value) {
            $$key = $value;
        }
        if(str_replace('-','',$tanggal_selesai) < str_replace("-","",date("Y-m-d"))){
            $status = "SELESAI ";
            sqlQuery("update acara set status = 'SELESAI' where id = '$id'");
        }else{
            $status = "BELUM ";
            sqlQuery("update acara set status = 'BELUM' where id = '$id'");
        }

        if($publish == '1'){
            $statusPublish = "YA";
        }elseif($publish == '2'){
            $statusPublish = "TIDAK";
        }

        $data .= "     <tr>
                          <td class='text-center' width='20px;'  style='vertical-align:middle;'>$nomor</td>
                          <td class='text-center' width='20px;'  style='vertical-align:middle;'>
                            <div  class='checkbox checkbox-inline checkbox-styled'>
                                    <label>
                                    ".setCekBox($nomorCB,$id,'','acara')."
                                <span></span>
															</label>
														</div>
                            </td>
                          <td style='vertical-align:middle;'>$nama_acara</td>
                          <td style='vertical-align:middle;'>$lokasi</td>
                          <td class='text-center' style='vertical-align:middle;'>".generateDate($tanggal)." $jam</td>
                          <td class='text-center' style='vertical-align:middle;'>$lama_acara Hari</td>
                          <td class='text-center' style='vertical-align:middle;'>$reversed</td>
                          <td class='text-center' style='vertical-align:middle;'>$kuota</td>
                          <td class='text-center' style='vertical-align:middle;'>$statusPublish</td>
                          <td class='text-center' style='vertical-align:middle;'>$status</td>
                          <td class='text-center' style='vertical-align:middle;'><div class='demo-icon-hover' style='cursor:pointer;' onclick=pendaftaran($id);>
        											<i class='md md-launch'></i>
        										</div></td>
                               </tr>
                    ";
          $nomor += 1;
          $nomorCB += 1;
          $status = "";
      }

      $tabelBody = "

        <thead>
          <tr>
            <th class='text-center' width='20px;'>No</th>
            <th class='text-center' width='20px;'>
             <div class='checkbox checkbox-inline checkbox-styled' >
              <label>
                <input type='checkbox' name='acara_toogle' id='acara_toogle' onclick=checkSemua($nomorCB,'acara_cb','acara_toogle','acara_jmlcek',this)>
              <span></span>
            </label>
          </div>
            </th>
            <th class='col-lg-3'>Nama Acara</th>
            <th class='col-lg-3'>Lokasi</th>
            <th class='col-lg-2 text-center'>Tanggal</th>
            <th class='col-lg-1 text-center'>Lama Acara</th>
            <th class='col-lg-1 text-center'>Pendaftar</th>
            <th class='col-lg-1 text-center'>Kuota</th>
            <th class='col-lg-1 text-center'>Publish</th>
            <th class='col-lg-1 text-center'>Status</th>
            <th class='col-lg-1 text-center'>Action</th>
          </tr>
        </thead>
        <tbody>
          $data
        </tbody>

      ";

      $jumlahData = sqlNumRow(sqlQuery("select * from $tableName $kondisi"));
      $jumlahPage =ceil($jumlahData / $limitTable) ;
      for ($i=1; $i <= $jumlahPage ; $i++) {
          if($pageKe == $i){
            $dataPagging .= "<li class='active'>
                                <a onclick=currentPage($i)>$i</a>
                            </li>";
          }else{
            $dataPagging .= "<li >
                                <a onclick=currentPage($i)>$i</a>
                            </li>";
          }

      }
      $tabelFooter = "
        <ul class='pagination pagination-info'>
          $dataPagging
        </ul>
      <input type='hidden' name='acara_jmlcek' id='acara_jmlcek' value='0'>";
      $content = array("tabelBody" => $tabelBody, 'tabelFooter' => $tabelFooter);
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'setMenuEdit':{
      $sqlNama = sqlArray(sqlQuery("SELECT * from users where username = '".$_SESSION[username]."' "));
      $getNama = $sqlNama[username];
      if($statusMenu == 'index'){
        $filterinTable = "
          <ul class='header-nav header-nav-options'>
            <li class='dropdown'>
              <div class='row'>
                <div class='col-xs-4 col-sm-2 col-md-2 col-lg-2'>
                  <form class='form' role='form'>
                    <div class='form-group floating-label' style='padding-top: 0px;'>
                      <div class='input-group'>
                        <span class='input-group-addon'></span>
                        <div class='input-group-content'>
                          <input type='text' class='form-control' id='searchData' name='searchData' onkeyup=limitData(); placeholder='Search'>
                          <!-- <label for='searchData'>Search</label> -->
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
                <div class='col-xs-4 col-sm-1 col-md-1 col-lg-1'>
                  <form class='form' role='form'>
                      <div class='form-group' style='padding-top: 0px;'>
                        <div class='input-group'>
                          <div class='input-group-content'>
                            <input type='text' onkeypress='return event.charCode >= 48 && event.charCode <= 57' class='form-control ' id='jumlahDataPerhalaman' name='jumlahDataPerhalaman' value = '50' onkeyup=limitData(); placeholder='Data / Halaman'>
                            <label for='username10'>Data</label>
                          </div>
                        </div>
                      </div>
                  </form>
                </div>
                <div class='col-xs-4 col-sm-2 col-md-2 col-lg-2'>
                  <form class='form' role='form'>
                      <div class='form-group' style='padding-top: 0px;'>
                        <div class='input-group'>
                          <div class='input-group-content'>
                            <div class='btn-group'>
                              <a class='btn dropdown-toggle' data-toggle='dropdown' href='#'>
                                <b>
                                  Urutkan
                                  <span class='glyphicon glyphicon-sort'></span>
                                </b>
                              </a>
                              <ul class='dropdown-menu'>
                                <li id='nama_acara' onclick=sortData(this);><a href='#' style='width: 100%;' >Nama Acara</a></li>
                                <li id='lokasi' onclick=sortData(this);><a href='#' style='width: 100%;' >Lokasi</a></li>
                                <li id='tanggal' onclick=sortData(this);><a href='#' style='width: 100%;' >Tanggal</a></li>
                                <li id='lama_acara' onclick=sortData(this);><a href='#' style='width: 100%;' >Lama Acara</a></li>
                                <li id='reversed' onclick=sortData(this);><a href='#' style='width: 100%;' >Pendaftaran</a></li>
                                <li id='kuota' onclick=sortData(this);><a href='#' style='width: 100%;' >Kuota</a></li>
                                <li id='status' onclick=sortData(this);><a href='#' style='width: 100%;' >Status</a></li>
                                <li id='naik' class='active-tick2' onclick=ascChanged();><a href='#' style='width: 100%; border-top: 2px solid #0aa89e; font-weight: bold;'>Naik</a></li>
                                <li id='turun' onclick=descChanged();><a href='#' style='width: 100%; font-weight: bold;'>Turun</a></li>
                                <input type='hidden' id='ascHidden' name='ascHidden'>
                              </ul>
                            </div>
                          </div>
                        </div>
                      </div>
                  </form>
                </div>
              </div>
            </li>
          </ul>";
        $header = "

          <ul class='header-nav header-nav-options'>
            <li class='dropdown'>
              <div class='row'>

                <div class='col-xs-3 col-sm-3 col-md-3 col-lg-3'>
                  <button type='button' class='btn ink-reaction btn-flat btn-primary' onclick=Baru();>
                      <i class='fa fa-plus'></i>
                      baru
                  </button>
                </div>
                <div class='col-xs-3 col-sm-3 col-md-3 col-lg-3'>
                  <button type='button' class='btn ink-reaction btn-flat btn-primary' onclick=Edit();>
                    <i class='fa fa-magic'></i>
                    edit
                  </button>
                </div>
                <div class='col-xs-3 col-sm-3 col-md-3 col-lg-3'>
                  <button type='button' class='btn ink-reaction btn-flat btn-primary' onclick=Hapus();>
                    <i class='fa fa-close'></i>
                    hapus
                  </button>
                </div>
                <div class='col-sm-3'>
                  <div class='btn-group'>
                    <button type='button' class='btn ink-reaction btn-flat dropdown-toggle' data-toggle='dropdown' style='color: #0aa89e;'>
                       <i class='fa fa-user text-default-light' style='color: #0aa89e;'></i> ".$getNama."
                    </button>
                    <ul class='dropdown-menu animation-expand' role='menu'>
                      <li><a href='pages.php?page=profile'>Ganti Password</a></li>
                      <li><a href='logout.php'>Logout</a></li>
                    </ul>
                  </div><!--end .btn-group -->
                </div><!--end .col -->
              </div>
            </li>
          </ul>
          ";
      }elseif($statusMenu == 'pendaftaran'){
        $filterinTable = "

        <ul class='header-nav header-nav-options'>
          <li class='dropdown'>
            <div class='row'>
              <div class='col-xs-4 col-sm-2 col-md-2 col-lg-2'>
                <form class='form' role='form'>
                  <div class='form-group floating-label' style='padding-top: 0px;'>
                    <div class='input-group'>
                      <span class='input-group-addon'></span>
                      <div class='input-group-content'>
                        <input type='text' class='form-control' id='searchData' name='searchData' onkeyup=limitDataPendaftaran(); placeholder='Search'>
                        <!-- <label for='searchData'>Search</label> -->
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class='col-xs-4 col-sm-1 col-md-1 col-lg-1'>
                <form class='form' role='form'>
                    <div class='form-group' style='padding-top: 0px;'>
                      <div class='input-group'>
                        <div class='input-group-content'>
                          <input type='text' onkeypress='return event.charCode >= 48 && event.charCode <= 57' class='form-control ' id='jumlahDataPerhalaman' name='jumlahDataPerhalaman' value = '50' onkeyup=limitDataPendaftaran(); placeholder='Data / Halaman'>
                          <label for='username10'>Data</label>
                        </div>
                      </div>
                    </div>
                </form>
              </div>
              <div class='col-xs-4 col-sm-1 col-md-1 col-lg-1'>
                <form class='form' role='form'>
                    <div class='form-group' style='padding-top: 0px;'>
                      <div class='input-group'>
                        <div class='input-group-content'>
                          <div class='btn-group'>
                            <a class='btn dropdown-toggle' data-toggle='dropdown' href='#'>
                              <b>
                                Status
                                <span class='glyphicon glyphicon-sort'></span>
                              </b>
                            </a>
                            <ul class='dropdown-menu'>
                              <li id='menungguPembayaran' onclick=filterStatus(this);><a href='#' style='width: 100%;' >Menunggu Pembayaran</a></li>
                              <li id='konfirmasiPembayaran' onclick=filterStatus(this);><a href='#' style='width: 100%;' >Konfirmasi Pembayaran</a></li>
                              <li id='diBatalkan' onclick=filterStatus(this);><a href='#' style='width: 100%;' >Di Batalkan</a></li>
                              <li id='deadlinePembayaran' onclick=filterStatus(this);><a href='#' style='width: 100%;' >Deadline Pembbayaran</a></li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                </form>
              </div>
              <div class='col-xs-4 col-sm-2 col-md-2 col-lg-2'>
                <form class='form' role='form'>
                    <div class='form-group' style='padding-top: 0px;'>
                      <div class='input-group'>
                        <div class='input-group-content'>
                          <div class='btn-group'>
                            <a class='btn dropdown-toggle' data-toggle='dropdown' href='#'>
                              <b>
                                Urutkan
                                <span class='glyphicon glyphicon-sort'></span>
                              </b>
                            </a>
                            <ul class='dropdown-menu'>
                              <li id='nomor_invoice' onclick=sortDataPendaftaran(this);><a href='#' style='width: 100%;' >Nomor Invoice</a></li>
                              <li id='tanggal_daftar' onclick=sortDataPendaftaran(this);><a href='#' style='width: 100%;' >Tanggal Daftar</a></li>
                              <li id='jumlah_orang' onclick=sortDataPendaftaran(this);><a href='#' style='width: 100%;' >Jumlah Orang</a></li>
                              <li id='naik' class='active-tick2' onclick=ascChanged();><a href='#' style='width: 100%; border-top: 2px solid #0aa89e; font-weight: bold;'>Naik</a></li>
                              <li id='turun' onclick=descChanged();><a href='#' style='width: 100%; font-weight: bold;'>Turun</a></li>
                              <input type='hidden' id='ascHidden' name='ascHidden'>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                </form>
              </div>
            </div>
          </li>
        </ul>";
        $header = "

          <ul class='header-nav header-nav-options'>
            <li class='dropdown'>
              <div class='row'>

                <div class='col-xs-3 col-sm-3 col-md-3 col-lg-3'>
                  <button type='button' class='btn ink-reaction btn-flat btn-primary' onclick=konfirmasiPendaftaran();>
                      <i class='md md-link'></i>
                      Action
                  </button>

                </div>

              </div>
            </li>
          </ul>
          ";
      }else{
        $header = "
          <ul class='header-nav header-nav-options'>

          </ul>
          ";
          $filterinTable = "";
      }

      $content = array("header" => $header, 'filterinTable' => $filterinTable);
      echo generateAPI($cek,$err,$content);
    break;
    }
    case 'removeTemp':{
      unlink('temp/'.$_SESSION['username']."/".$id);
      echo generateAPI($cek,$err,$content);
    break;
    }

     default:{
        ?>
        <script>
        var url = "http://"+window.location.hostname+"/api.php?page=acara";
        </script>

        <style>
        .active-tick2 a::after {
          content: '\f00c';
          font-family: 'FontAwesome';
          float: right;
        }
        .active-tick a::after {
          content: '\f00c';
          font-family: 'FontAwesome';
          float: right;
        }
        th.datepicker-switch{
          color: black;
        }
        th.next{
          color: black;
        }
        th.prev{
          color: black;
        }
        th.dow{
          color: black;
        }
        .header-nav-options .dropdown .dropdown-menu{
          top: 100%;
        }
        .form .form-group .input-group-addon:first-child{
          min-width: 0px;
        }
        #jumlahDataPerhalaman{
          width: 60px;
        }
        .form-control:focus{
          border-bottom-color: #0aa89e!important;
        }
        table{
          border-collapse:collapse;
          width:100%;
        }
        .blue thead{
          background:#1ABC9C;
        }
        thead{
          color:white;
        }
        th,td{
          padding:5px 0;
        }
        /*tbody tr:nth-child(even){
          background:#ECF0F1;
        }*/
        /*tbody tr:hover{
        background:#BDC3C7;
        }*/
        .fixed {
            top: 65px;
            position: fixed;
            width: auto;
            display: none;
            border: none;
            background: #ffffff;
        }
        .scrollMore{
          margin-top:600px;
        }
        .up{
          cursor:pointer;
        }
        </style>
        <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="js/acara.js"></script>
        <script>
          function klik(monkey){

            $(monkey).addClass('active-tick').siblings().removeClass('active-tick');

          }
        (function($) {
          $.fn.fixMe = function() {
            return this.each(function() {
              var $this = $(this),
                $t_fixed;
              function init() {
                // $this.wrap('<div class="container" />');
                $t_fixed = $this.clone();
                $t_fixed
                  .find("tbody")
                  .remove()
                  .end()
                  .addClass("fixed")
                  .insertBefore($this);
                resizeFixed();
              }
              function resizeFixed() {
                $t_fixed.find("th").each(function(index) {
                  $(this).css(
                    "width",
                    $this
                      .find("th")
                      .eq(index)
                      .outerWidth() + "px"
                  );
                });
              }
              function scrollFixed() {
                var offset = $(this).scrollTop(),
                  tableOffsetTop = $this.offset().top,
                  tableOffsetBottom =
                    tableOffsetTop + $this.height() - $this.find("thead").height();
                if (offset < tableOffsetTop || offset > tableOffsetBottom)
                  $t_fixed.hide();
                else if (
                  offset >= tableOffsetTop &&
                  offset <= tableOffsetBottom &&
                  $t_fixed.is(":hidden")
                )
                  $t_fixed.show();
              }
              $(window).resize(resizeFixed);
              $(window).scroll(scrollFixed);
              init();
            });
          };
        })(jQuery);
        </script>



        <?php
          if(!isset($_GET['action'])){
            ?>
            <script type="text/javascript">
              $(document).ready(function() {
                  loadTable(1,50);
                  setMenuEdit('index');
                  $("#pageTitle").text("ACARA");
              });
            </script>
            <div id="content">
      				<section>
      					<div class="section-body contain-lg">
      						<div class="row">
      							<div class="col-lg-12">
      								<div class="card">
      									<div class="card-body no-padding">
      										<div class="table-responsive no-margin">
                            <form id='formAcara' name="formAcara" action="#">
                              <table class="table table-striped no-margin table-hover blue" id='tabelBody'>
                                <thead>
                                  <tr>
                                    <th>Colonne 1</th>
                                    <th>Colonne 2</th>
                                    <th>Colonne 3</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>Non</td>
                                    <td>Mais</td>
                                    <td>Allo !</td>
                                  </tr>

                                </tbody>
                              </table>
        											<div class="col-lg-12" style="text-align: right;" id='tabelFooter'>
        												<ul class="pagination pagination-info">
        		                        <li class="active">
        		                            <a href="javascript:void(0);"> prev</a>
        		                        </li>
        		                        <li>
        		                            <a href="javascript:void(0);">1</a>
        		                        </li>
        		                        <li>
        		                            <a href="javascript:void(0);">2</a>
        		                        </li>
        		                        <li >
        		                            <a href="javascript:void(0);">3</a>
        		                        </li>
        		                        <li>
        		                            <a href="javascript:void(0);">4</a>
        		                        </li>
        		                        <li>
        		                            <a href="javascript:void(0);">5</a>
        		                        </li>
        		                        <li>
        		                            <a href="javascript:void(0);">next </a>
        		                        </li>
        		                    </ul>
        											</div>
                            </form>
      										</div>
      									</div>
      								</div>
      							</div>
      						</div>
      					</div>
      				</section>
      			</div>
            <?php
          }else{
              if($_GET['action'] == 'baru'){
                ?>
                <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons" />
                <script type="text/javascript" src="js/textboxio/textboxio.js"></script>
                <link rel="stylesheet" type="text/css" href="js/ImageResizeCropCanvas/css/componentAcara.css" />
                <link rel="stylesheet" type="text/css" href="js/ImageResizeCropCanvas/css/demoAcara.css" />
                <script src="js/ImageResizeCropCanvas/js/componentAcara.js"></script>

                <div id="content">
          				<section>
          					<div class="section-body contain-lg">
                      <form class="form" id='formAcara'>
      									<div class="card">
      										<div class="card-body floating-label">
      											<div class="row">
                              <div class="col-sm-1">
      													<div class="form-group">
                                  <?php
                                    $arrayStatus = array(
                                              array('1','YA'),
                                              array('2','TIDAK'),
                                    );
                                    echo cmbArrayEmpty("statusPublish","",$arrayStatus,"-- PUBLISH --","class='form-control' ")
                                  ?>
      														<label for="Firstname2">PUBLISH</label>
      													</div>
      												</div>
                            </div>
      											<div class="row">
      												<div class="col-sm-12">
      													<div class="form-group">
      														<input type="text" class="form-control" id="namaAcara" name='namaAcara'>
      														<label for="namaAcara">Nama Acara</label>
      													</div>
      												</div>
                            </div>
      											<div class="row">
      												<div class="col-sm-12 col-xs-12 col-md-3 col-lg-3">
                                <div class="form-group date" >
        														<input type="text" id='tanggalAcara' name='tanggalAcara' class="form-control">
        														<label>Tanggal Mulai</label>
        													<span class="input-group-addon"></span>
        												</div>
      												</div>
      												<div class="col-sm-12 col-xs-12 col-md-3 col-lg-3">
                                <div class="form-group">
          												<input type="text" id='jamAcara' name='jamAcara' class="form-control time-mask">
          												<label>Jam Mulai</label>
          											</div>
      												</div>
      												<div class="col-sm-12 col-xs-12 col-md-3 col-lg-3">
                                <div class="form-group">
          												<input type="number" id='kuotaAcara' name='kuotaAcara' class="form-control">
          												<label>Kuota</label>
          											</div>
      												</div>
      												<div class="col-sm-12 col-xs-12 col-md-3 col-lg-3">
                                <div class="form-group">
                                    <input type="hidden" id='baseUndangan' name='baseUndangan'>
                                    <input  type="file" accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf" onchange="undanganChanged();" id='fileUndangan' name="fileUndangan">
          											   	<label>Undangan</label>
          											</div>
      												</div>
                            </div>
                            <div class="row">
                              <div class="col-sm-12 col-xs-12 col-md-3 col-lg-3">
                                <div class="form-group date" >
                                    <input type="text" id='tanggalAcaraSelesai' name='tanggalAcaraSelesai' class="form-control">
                                    <label>Tanggal Selesai</label>
                                  <span class="input-group-addon"></span>
                                </div>
                              </div>
                              <div class="col-sm-12 col-xs-12 col-md-3 col-lg-3">
                                <div class="form-group">
                                  <input type="text" id='jamAcaraSelesai' name='jamAcaraSelesai' class="form-control time-mask">
                                  <label>Jam Selesai</label>
                                </div>
                              </div>
                              <div class="col-sm-12 col-xs-12 col-md-3 col-lg-3">
                                <div class="form-group">
                                  <input type="text" id='hargaTiket' name='hargaTiket' class="form-control">
                                  <label>Harga Partisipasi</label>
                                </div>
                              </div>
                              <div class="col-sm-12 col-xs-12 col-md-3 col-lg-3">
                                <div class="form-group">
                                  <input type="number" id='deadlinePembayaran' name='deadlinePembayaran' class="form-control">
                                  <label>Deadline Pembayaran</label>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-12">
                                <div class="component" style="display: none;">
                                  <div class="overlay">
                                    <div class="overlay-inner">
                                    </div>
                                  </div>
                                  <img class="resize-image" id='gambarAcara' alt="image for resizing">
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-4" style="margin-bottom: 1.5%;">
                                <span class="btn ink-reaction btn-raised btn-primary">
                                  <span class="fileinput-exists" onclick='$("#imageAcara").click();'>Pilih Gambar</span>
                                  <input type="hidden" id='statusKosong' name='statusKosong'>
                                  <input style="display:none;" type="file" accept='image/x-png,image/gif,image/jpeg' onchange="imageChanged();" id='imageAcara' name="imageAcara">
                                </span>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-12">
                                <div class="form-group">
                                  <textarea name="lokasiAcara" id="lokasiAcara" class="form-control" rows="3" placeholder=""></textarea>
                                  <input type="hidden" id='kordinatX' class="">
                                  <input type="hidden" id='kordinatY' class="">
                                  <input type="hidden" id='tempKordinat' class="">
                                  <label>Lokasi Acara</label>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-12 col-lg-12">
                                <div class="card">
                                  <div class="card-body no-padding">
                                    <input id="pac-input" class="controls" type="text" placeholder="Enter a location">
                                                  <div id="type-selector" class="controls">
                                                  </div>
                                     <div id="map" style="height: 800px;width: 100%;"></div>
                                  </div>
                                </div>
                                <em class="text-caption">Map with marker</em>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-12">
                                  <textarea id='deskripsiAcara' style="height:400px;"></textarea>
                              </div>
                            </div>
                            <div class="card-actionbar">
        											<div class="card-actionbar-row">
                                <button type="button" class="btn ink-reaction btn-raised btn-primary" onclick="saveAcara();">Simpan</button>
                                <button type="button" class="btn ink-reaction btn-raised btn-danger" onclick="refreshList();">batal</button>
        											</div>
        										</div>
      										</div>
      									</div>
      								</form>
      							</div>
          				</section>
          			</div>
                <style>
                      #map {
                      height: 100%;
                      }

                      .controls {
                      margin-top: 10px;
                      border: 1px solid transparent;
                      border-radius: 2px 0 0 2px;
                      box-sizing: border-box;
                      -moz-box-sizing: border-box;
                      height: 32px;
                      outline: none;
                      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
                      }

                      #pac-input {
                      background-color: #fff;
                      font-family: Roboto;
                      font-size: 15px;
                      font-weight: 300;
                      margin-left: 12px;
                      padding: 0 11px 0 13px;
                      text-overflow: ellipsis;
                      width: 300px;
                      }

                      #pac-input:focus {
                      border-color: #4d90fe;
                      }

                      .pac-container {
                      font-family: Roboto;
                      }

                      #type-selector {
                      color: #fff;
                      background-color: #4d90fe;
                      padding: 5px 11px 0px 11px;
                      }

                      #type-selector label {
                      font-family: Roboto;
                      font-size: 13px;
                      font-weight: 300;
                      }
                  </style>
                  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJNf9tt4XIkzl5mAaAA0aehyVrdaS6awU&libraries=places&callback=initMap"
                    async ></script>
                <!-- <script>
                  function initMap() {
                    var uluru = {lat: -6.9066217615554235, lng: 107.6347303390503};
                    var map = new google.maps.Map(document.getElementById('map'), {
                      zoom: 18,
                      center: uluru
                    });
                    var marker = new google.maps.Marker({
                      position: uluru,
                      map: map
                    });
                  }
                </script> -->

                <script>
                          var markers = [];
                          var map;
                          function initMap() {
                          var origin = {lat: -6.9066217615554235, lng: 107.6347303390503};

                           map = new google.maps.Map(document.getElementById('map'), {
                            zoom: 18,
                            center: origin
                          });
                          var clickHandler = new ClickEventHandler(map, origin);

                          var input = /** @type {!HTMLInputElement} */(
                            document.getElementById('pac-input'));

                          var types = document.getElementById('type-selector');
                          map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
                          map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);

                          var autocomplete = new google.maps.places.Autocomplete(input);
                          autocomplete.bindTo('bounds', map);

                          var infowindow = new google.maps.InfoWindow();
                          var marker = new google.maps.Marker({
                          map: map,
                          anchorPoint: new google.maps.Point(0, -29)
                          });
                          google.maps.event.addListener(map, "click", function (e) {
                            //lat and lng is available in e object
                            var latLng = e.latLng;

                            getAlamat(latLng);
                            deleteMarkers();
                            addMarker(latLng);
                          });

                          autocomplete.addListener('place_changed', function() {
                          infowindow.close();
                          marker.setVisible(false);
                          var place = autocomplete.getPlace();
                          if (!place.geometry) {
                            // User entered the name of a Place that was not suggested and
                            // pressed the Enter key, or the Place Details request failed.
                            window.alert("No details available for input: '" + place.name + "'");
                            return;
                          }

                          // If the place has a geometry, then present it on a map.
                          if (place.geometry.viewport) {
                            map.fitBounds(place.geometry.viewport);
                          } else {
                            map.setCenter(place.geometry.location);
                            map.setZoom(17);  // Why 17? Because it looks good.
                          }
                          marker.setIcon(/** @type {google.maps.Icon} */({
                            url: place.icon,
                            size: new google.maps.Size(71, 71),
                            origin: new google.maps.Point(0, 0),
                            anchor: new google.maps.Point(17, 34),
                            scaledSize: new google.maps.Size(35, 35)
                          }));
                          marker.setPosition(place.geometry.location);
                          marker.setVisible(true);

                          var address = '';
                          if (place.address_components) {
                            address = [
                              (place.address_components[0] && place.address_components[0].short_name || ''),
                              (place.address_components[1] && place.address_components[1].short_name || ''),
                              (place.address_components[2] && place.address_components[2].short_name || '')
                            ].join(' ');
                          }

                          infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
                          infowindow.open(map, marker);
                          });

                          // Sets a listener on a radio button to change the filter type on Places
                          // Autocomplete.
                          function setupClickListener(id, types) {
                          var radioButton = document.getElementById(id);
                          radioButton.addEventListener('click', function() {
                            autocomplete.setTypes(types);
                          });
                          }

                          setupClickListener('changetype-all', []);
                          setupClickListener('changetype-address', ['address']);
                          setupClickListener('changetype-establishment', ['establishment']);
                          setupClickListener('changetype-geocode', ['geocode']);
                          }

                          function deleteMarkers() {
                          clearMarkers();
                          markers = [];
                          }
                          function setMapOnAll(map) {
                          for (var i = 0; i < markers.length; i++) {
                           markers[i].setMap(map);
                          }
                          }

                          // Removes the markers from the map, but keeps them in the array.
                          function clearMarkers() {
                          setMapOnAll(null);
                          }

                          function addMarker(location) {
                            var marker = new google.maps.Marker({
                            position: location,
                            map: map,
                            title: 'Lokasi'
                          });
                          markers.push(marker);
                          map.setCenter(marker.getPosition())
                          }


                          var ClickEventHandler = function(map, origin) {
                          this.origin = origin;
                          this.map = map;
                          this.directionsService = new google.maps.DirectionsService;
                          this.directionsDisplay = new google.maps.DirectionsRenderer;
                          this.directionsDisplay.setMap(map);
                          this.placesService = new google.maps.places.PlacesService(map);
                          this.infowindow = new google.maps.InfoWindow;
                          this.infowindowContent = document.getElementById('infowindow-content');
                          this.infowindow.setContent(this.infowindowContent);

                          // Listen for clicks on the map.
                          this.map.addListener('click', this.handleClick.bind(this));
                          };

                          ClickEventHandler.prototype.handleClick = function(event) {
                          console.log('You clicked on: ' + event.latLng);
                          // If the event has a placeId, use it.
                          if (event.placeId) {
                          console.log('You clicked on place:' + event.placeId);

                          // Calling e.stop() on the event prevents the default info window from
                          // showing.
                          // If you call stop here when there is no placeId you will prevent some
                          // other map click event handlers from receiving the event.
                          event.stop();
                          this.calculateAndDisplayRoute(event.placeId);
                          this.getPlaceInformation(event.placeId);
                          }
                          };

                          ClickEventHandler.prototype.calculateAndDisplayRoute = function(placeId) {
                          var me = this;
                          this.directionsService.route({
                          origin: this.origin,
                          destination: {placeId: placeId},
                          travelMode: 'WALKING'
                          }, function(response, status) {
                          if (status === 'OK') {
                          me.directionsDisplay.setDirections(response);
                          } else {
                          window.alert('Directions request failed due to ' + status);
                          }
                          });
                          };

                          ClickEventHandler.prototype.getPlaceInformation = function(placeId) {
                          var me = this;
                          this.placesService.getDetails({placeId: placeId}, function(place, status) {
                          if (status === 'OK') {
                          me.infowindow.close();
                          me.infowindow.setPosition(place.geometry.location);
                          me.infowindowContent.children['place-icon'].src = place.icon;
                          me.infowindowContent.children['place-name'].textContent = place.name;
                          me.infowindowContent.children['place-id'].textContent = place.place_id;
                          me.infowindowContent.children['place-address'].textContent =
                            place.formatted_address;
                          me.infowindow.open(me.map);
                          }
                          });
                          };
                          </script>
                <script type="text/javascript">
                  $(document).ready(function() {
                      setMenuEdit('baru');
                      $("#pageTitle").text("ACARA");
                      $('.date').datepicker({
                                              autoclose: true,
                                              todayHighlight: true,
                                              format : 'dd-mm-yyyy'
                                            });
                      $(".form-control.time-mask").inputmask('h:s', {placeholder: 'JJ:MM'});
                      $("#hargaTiket").inputmask('Rp 999.999.999', {numericInput: true, rightAlignNumerics: false});
                      $("#hargaKamar").inputmask('Rp 999.999.999', {numericInput: true, rightAlignNumerics: false});
                      $("#hargaTiket").inputmask('Rp 999.999.999', {numericInput: true, rightAlignNumerics: false});
                      $("#hargaExtraBed").inputmask('Rp 999.999.999', {numericInput: true, rightAlignNumerics: false});
                      textboxio.replaceAll('#deskripsiAcara', {
                        paste: {
                          style: 'clean'
                        },
                        css: {
                          stylesheets: ['js/textboxio/example.css']
                        }
                      });
                  });
                </script>
                <?php
              }elseif($_GET['action']=='edit'){
                  $getData = sqlArray(sqlQuery("select * from $tableName where id = '".$_GET['idEdit']."'"));
                  $explodeKoordinat = explode(",",$getData['koordinat']);
                  $baseUndangan = imageToBase($getData['undangan']);
                  ?>
                  <script type="text/javascript" src="js/textboxio/textboxio.js"></script>
                  <link rel="stylesheet" type="text/css" href="js/ImageResizeCropCanvas/css/componentAcara.css" />
                  <link rel="stylesheet" type="text/css" href="js/ImageResizeCropCanvas/css/demoAcara.css" />
                  <script src="js/ImageResizeCropCanvas/js/componentAcara.js"></script>
                  <div id="content">
            				<section>
            					<div class="section-body contain-lg">
                        <form class="form" id='formAcara'>
        									<div class="card">
        										<div class="card-body floating-label">
                              <div class="row">
                                <div class="col-sm-1">
        													<div class="form-group">
                                    <?php
                                      $arrayStatus = array(
                                                array('1','YA'),
                                                array('2','TIDAK'),
                                      );
                                      echo cmbArrayEmpty("statusPublish",$getData['publish'],$arrayStatus,"-- PUBLISH --","class='form-control' ")
                                    ?>
        														<label for="Firstname2">PUBLISH</label>
        													</div>
        												</div>
                              </div>
        											<div class="row">
        												<div class="col-sm-12">
        													<div class="form-group">
        														<input type="text" class="form-control" id="namaAcara" name='namaAcara' value="<?php echo $getData['nama_acara'] ?>">
        														<label for="namaAcara">Nama Acara</label>
        													</div>
        												</div>
                              </div>
        											<div class="row">
        												<div class="col-sm-3 col-xs-12 col-md-3 col-lg-3">
                                  <div class="form-group date" >
          														<input type="text" id='tanggalAcara' name='tanggalAcara' class="form-control" value="<?php echo generateDate($getData['tanggal']) ?>">
          														<label>Tanggal Mulai</label>
          													<span class="input-group-addon"></span>
          												</div>
        												</div>
        												<div class="col-sm-3 col-xs-12 col-md-3 col-lg-3">
                                  <div class="form-group">
            												<input type="text" id='jamAcara' name='jamAcara' class="form-control time-mask" value="<?php echo $getData['jam'] ?>">
            												<label>Jam Mulai</label>
            											</div>
        												</div>
        												<div class="col-sm-3 col-xs-12 col-md-3 col-lg-3">
                                  <div class="form-group">
            												<input type="number" id='kuotaAcara' name='kuotaAcara' class="form-control" value="<?php echo $getData['kuota'] ?>">
            												<label>Kuota</label>
            											</div>
        												</div>
                                <div class="col-sm-12 col-xs-12 col-md-3 col-lg-3">
                                  <div class="form-group">
                                      <input type="hidden" id='baseUndangan' name='baseUndangan' value="<?php echo $baseUndangan ?>">
                                      <input  type="file" accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf" onchange="undanganChanged();" id='fileUndangan' name="fileUndangan">
            											   	<label>Undangan</label>
            											</div>
        												</div>
                              </div>
                              <div class="row">
                                <div class="col-sm-12 col-xs-12 col-md-3 col-lg-3">
                                  <div class="form-group date" >
                                      <input type="text" id='tanggalAcaraSelesai' name='tanggalAcaraSelesai' class="form-control" value="<?php echo generateDate($getData['tanggal_selesai']) ?>">
                                      <label>Tanggal Selesai</label>
                                    <span class="input-group-addon"></span>
                                  </div>
                                </div>
                                <div class="col-sm-12 col-xs-12 col-md-3 col-lg-3">
                                  <div class="form-group">
                                    <input type="text" id='jamAcaraSelesai' name='jamAcaraSelesai' class="form-control time-mask" value="<?php echo $getData['jam_selesai'] ?>">
                                    <label>Jam Selesai</label>
                                  </div>
                                </div>
                                <div class="col-sm-12 col-xs-12 col-md-3 col-lg-3">
                                  <div class="form-group">
                                    <input type="text" id='hargaTiket' name='hargaTiket' class="form-control" value="<?php echo $getData['harga_tiket'] ?>">
                                    <label>Harga Partisipasi</label>
                                  </div>
                                </div>
                                <div class="col-sm-12 col-xs-12 col-md-3 col-lg-3">
                                  <div class="form-group">
                                    <input type="number" id='deadlinePembayaran' name='deadlinePembayaran' class="form-control" value="<?php echo $getData['deadline_pembayaran'] ?>">
                                    <label>Deadline Pembayaran</label>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-sm-12">
                                  <div class="component" style="display: none;">
                                    <div class="overlay">
                                      <div class="overlay-inner">
                                      </div>
                                    </div>
                                    <img class="resize-image" id='gambarAcara' src="<?php echo $getData['image_title'] ?>" alt="image for resizing">
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-sm-4" style="margin-bottom: 1.5%;">
                                  <span class="btn ink-reaction btn-raised btn-primary">
                                    <span class="fileinput-exists" onclick='$("#imageAcara").click();'>Pilih Gambar</span>
                                    <input type="hidden" id='statusKosong' name='statusKosong'>
                                    <input style="display:none;" type="file" accept='image/x-png,image/gif,image/jpeg' onchange="imageChanged();" id='imageAcara' name="imageAcara">
                                  </span>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-sm-12">
                                  <div class="form-group">
                                    <textarea name="lokasiAcara" id="lokasiAcara" class="form-control" rows="3"placeholder=""><?php echo $getData['lokasi'] ?></textarea>
                                    <input type="hidden" id='kordinatX' class="" value='<?php echo $explodeKoordinat[0] ?>'>
                                    <input type="hidden" id='kordinatY' class="" value='<?php echo $explodeKoordinat[1] ?>'>
                                    <input type="hidden" id='tempKordinat' class="">
                                    <label>Lokasi Acara</label>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-12 col-lg-12">
                                  <div class="card">
                                    <div class="card-body no-padding">
                                      <input id="pac-input" class="controls" type="text" placeholder="Enter a location">
                                                    <div id="type-selector" class="controls">
                                                    </div>
                                       <div id="map" style="height: 800px;width: 100%;"></div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-sm-12">
                                    <textarea id='deskripsiAcara' style="height:400px;"><?php echo base64_decode($getData['deskripsi']) ?></textarea>
                                </div>
                              </div>
                              <div class="card-actionbar">
          											<div class="card-actionbar-row">
                                  <button type="button" class="btn ink-reaction btn-raised btn-primary" onclick="saveEditAcara(<?php echo $_GET['idEdit'] ?>);">Simpan</button>
                                  <button type="button" class="btn ink-reaction btn-raised btn-danger" onclick="refreshList();">batal</button>
          											</div>
          										</div>
        										</div>
        									</div>
        								</form>
        							</div>
            				</section>
            			</div>
                  <style>
                        #map {
                        height: 100%;
                        }

                        .controls {
                        margin-top: 10px;
                        border: 1px solid transparent;
                        border-radius: 2px 0 0 2px;
                        box-sizing: border-box;
                        -moz-box-sizing: border-box;
                        height: 32px;
                        outline: none;
                        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
                        }

                        #pac-input {
                        background-color: #fff;
                        font-family: Roboto;
                        font-size: 15px;
                        font-weight: 300;
                        margin-left: 12px;
                        padding: 0 11px 0 13px;
                        text-overflow: ellipsis;
                        width: 300px;
                        }

                        #pac-input:focus {
                        border-color: #4d90fe;
                        }

                        .pac-container {
                        font-family: Roboto;
                        }

                        #type-selector {
                        color: #fff;
                        background-color: #4d90fe;
                        padding: 5px 11px 0px 11px;
                        }

                        #type-selector label {
                        font-family: Roboto;
                        font-size: 13px;
                        font-weight: 300;
                        }
                    </style>
                    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJNf9tt4XIkzl5mAaAA0aehyVrdaS6awU&libraries=places&callback=initMap"
                      async ></script>
                  <!-- <script>
                    function initMap() {
                      var uluru = {lat: -6.9066217615554235, lng: 107.6347303390503};
                      var map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 18,
                        center: uluru
                      });
                      var marker = new google.maps.Marker({
                        position: uluru,
                        map: map
                      });
                    }
                  </script> -->

                  <script>
                            var markers = [];
                            var map;
                            function initMap() {
                            var origin = {lat: <?php echo $explodeKoordinat[0] ?>, lng: <?php echo $explodeKoordinat[1] ?>};

                             map = new google.maps.Map(document.getElementById('map'), {
                              zoom: 18,
                              center: origin
                            });
                            var clickHandler = new ClickEventHandler(map, origin);

                            var input = /** @type {!HTMLInputElement} */(
                              document.getElementById('pac-input'));

                            var types = document.getElementById('type-selector');
                            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
                            map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);

                            var autocomplete = new google.maps.places.Autocomplete(input);
                            autocomplete.bindTo('bounds', map);

                            var infowindow = new google.maps.InfoWindow();
                            var marker = new google.maps.Marker({
                            map: map,
                            anchorPoint: new google.maps.Point(0, -29)
                            });
                            google.maps.event.addListener(map, "click", function (e) {
                              //lat and lng is available in e object
                              var latLng = e.latLng;

                              getAlamat(latLng);
                              deleteMarkers();
                              addMarker(latLng);
                            });

                            autocomplete.addListener('place_changed', function() {
                            infowindow.close();
                            marker.setVisible(false);
                            var place = autocomplete.getPlace();
                            if (!place.geometry) {
                              // User entered the name of a Place that was not suggested and
                              // pressed the Enter key, or the Place Details request failed.
                              window.alert("No details available for input: '" + place.name + "'");
                              return;
                            }

                            // If the place has a geometry, then present it on a map.
                            if (place.geometry.viewport) {
                              map.fitBounds(place.geometry.viewport);
                            } else {
                              map.setCenter(place.geometry.location);
                              map.setZoom(17);  // Why 17? Because it looks good.
                            }
                            marker.setIcon(/** @type {google.maps.Icon} */({
                              url: place.icon,
                              size: new google.maps.Size(71, 71),
                              origin: new google.maps.Point(0, 0),
                              anchor: new google.maps.Point(17, 34),
                              scaledSize: new google.maps.Size(35, 35)
                            }));
                            marker.setPosition(place.geometry.location);
                            marker.setVisible(true);

                            var address = '';
                            if (place.address_components) {
                              address = [
                                (place.address_components[0] && place.address_components[0].short_name || ''),
                                (place.address_components[1] && place.address_components[1].short_name || ''),
                                (place.address_components[2] && place.address_components[2].short_name || '')
                              ].join(' ');
                            }

                            infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
                            infowindow.open(map, marker);
                            });

                            // Sets a listener on a radio button to change the filter type on Places
                            // Autocomplete.
                            function setupClickListener(id, types) {
                            var radioButton = document.getElementById(id);
                            radioButton.addEventListener('click', function() {
                              autocomplete.setTypes(types);
                            });
                            }

                            setupClickListener('changetype-all', []);
                            setupClickListener('changetype-address', ['address']);
                            setupClickListener('changetype-establishment', ['establishment']);
                            setupClickListener('changetype-geocode', ['geocode']);
                            }

                            function deleteMarkers() {
                            clearMarkers();
                            markers = [];
                            }
                            function setMapOnAll(map) {
                            for (var i = 0; i < markers.length; i++) {
                             markers[i].setMap(map);
                            }
                            }

                            // Removes the markers from the map, but keeps them in the array.
                            function clearMarkers() {
                            setMapOnAll(null);
                            }

                            function addMarker(location) {
                              var marker = new google.maps.Marker({
                              position: location,
                              map: map,
                              title: 'Lokasi'
                            });
                            markers.push(marker);
                            map.setCenter(marker.getPosition())
                            }


                            var ClickEventHandler = function(map, origin) {
                            this.origin = origin;
                            this.map = map;
                            // this.directionsService = new google.maps.DirectionsService;
                            // this.directionsDisplay = new google.maps.DirectionsRenderer;
                            // this.directionsDisplay.setMap(map);
                            this.placesService = new google.maps.places.PlacesService(map);
                            this.infowindow = new google.maps.InfoWindow;
                            this.infowindowContent = document.getElementById('infowindow-content');
                            this.infowindow.setContent(this.infowindowContent);
                            deleteMarkers();
                            var lastLocation = new google.maps.LatLng(<?php echo $explodeKoordinat[0] ?>,<?php echo $explodeKoordinat[1] ?>);
                            addMarker(lastLocation);

                            // Listen for clicks on the map.
                            this.map.addListener('click', this.handleClick.bind(this));
                            };

                            ClickEventHandler.prototype.handleClick = function(event) {
                            console.log('You clicked on: ' + event.latLng);
                            // If the event has a placeId, use it.
                            if (event.placeId) {
                            console.log('You clicked on place:' + event.placeId);

                            // Calling e.stop() on the event prevents the default info window from
                            // showing.
                            // If you call stop here when there is no placeId you will prevent some
                            // other map click event handlers from receiving the event.
                            event.stop();
                            this.calculateAndDisplayRoute(event.placeId);
                            this.getPlaceInformation(event.placeId);
                            }
                            };

                            ClickEventHandler.prototype.calculateAndDisplayRoute = function(placeId) {
                            var me = this;
                            this.directionsService.route({
                            origin: this.origin,
                            destination: {placeId: placeId},
                            travelMode: 'WALKING'
                            }, function(response, status) {
                            if (status === 'OK') {
                            me.directionsDisplay.setDirections(response);
                            } else {
                            window.alert('Directions request failed due to ' + status);
                            }
                            });
                            };

                            ClickEventHandler.prototype.getPlaceInformation = function(placeId) {
                            var me = this;
                            this.placesService.getDetails({placeId: placeId}, function(place, status) {
                            if (status === 'OK') {
                            me.infowindow.close();
                            me.infowindow.setPosition(place.geometry.location);
                            me.infowindowContent.children['place-icon'].src = place.icon;
                            me.infowindowContent.children['place-name'].textContent = place.name;
                            me.infowindowContent.children['place-id'].textContent = place.place_id;
                            me.infowindowContent.children['place-address'].textContent =
                              place.formatted_address;
                            me.infowindow.open(me.map);
                            }
                            });
                            };
                            </script>
                  <script type="text/javascript">
                    $(document).ready(function() {
                        setMenuEdit('baru');
                        resizeableImage($('#gambarAcara'));
                        $('.component').show();
                        $("#pageTitle").text("ACARA");
                        $('.date').datepicker({
                                                autoclose: true,
                                                todayHighlight: true,
                                                format : 'dd-mm-yyyy'
                                              });
                        $(".form-control.time-mask").inputmask('h:s', {placeholder: 'JJ:MM'});
                        $("#hargaTiket").inputmask('Rp 999.999.999', {numericInput: true, rightAlignNumerics: false});
                        $("#hargaKamar").inputmask('Rp 999.999.999', {numericInput: true, rightAlignNumerics: false});
                        $("#hargaTiket").inputmask('Rp 999.999.999', {numericInput: true, rightAlignNumerics: false});
                        $("#hargaExtraBed").inputmask('Rp 999.999.999', {numericInput: true, rightAlignNumerics: false});
                        textboxio.replaceAll('#deskripsiAcara', {
                          paste: {
                            style: 'clean'
                          },
                          css: {
                            stylesheets: ['js/textboxio/example.css']
                          }
                        });
                    });
                  </script>
                  <?php
              }elseif($_GET['action'] == 'pendaftaran'){
                  ?>
                  <script type="text/javascript">
                    $(document).ready(function() {
                        loadTablePendaftaran(1,50,<?php echo $_GET['idAcara'] ?>);
                        setMenuEdit('pendaftaran');
                        $("#pageTitle").text("PENDAFTARAN ACARA");
                    });
                  </script>
                  <style>
                  /*popup image*/

                          #myImg {
                      border-radius: 5px;
                      cursor: pointer;
                      transition: 0.3s;
                  }

                  #myImg:hover {opacity: 0.7;}

                  /* The Modal (background) */
                  .modal {
                      display: none; /* Hidden by default */
                      position: fixed; /* Stay in place */
                      z-index: 1; /* Sit on top */
                      padding-top: 100px; /* Location of the box */
                      left: 0;
                      top: 0;
                      width: 100%; /* Full width */
                      height: 100%; /* Full height */
                      overflow: auto; /* Enable scroll if needed */
                      background-color: transparent; /* Fallback color */
                      /*background-color: rgba(0,0,0,0.9); /* Black w/ opacity */*/
                  }

                  /* Modal Content (image) */
                  .modal-content {
                      margin: auto;
                      display: block;
                      width: 80%;
                      max-width: 700px;
                  }

                  /* Caption of Modal Image */
                  #captionImage {
                      margin: auto;
                      display: block;
                      width: 80%;
                      max-width: 700px;
                      text-align: center;
                      color: #ccc;
                      padding: 10px 0;
                      height: 150px;
                  }

                  /* Add Animation */
                  .modal-content, #captionImage {
                      -webkit-animation-name: zoom;
                      -webkit-animation-duration: 0.6s;
                      animation-name: zoom;
                      animation-duration: 0.6s;
                  }

                  @-webkit-keyframes zoom {
                      from {-webkit-transform:scale(0)}
                      to {-webkit-transform:scale(1)}
                  }

                  @keyframes zoom {
                      from {transform:scale(0)}
                      to {transform:scale(1)}
                  }



                  @media only screen and (max-width: 700px){
                      .modal-content {
                          width: 100%;
                      }
                  }
                  </style>
                  <div id="content">
            				<section>
            					<div class="section-body contain-lg">
            						<div class="row">
            							<div class="col-lg-12">
            								<div class="card">
            									<div class="card-body no-padding">
            										<div class="table-responsive no-margin">
                                  <form id='formAcara' name="formAcara" action="#">
                                    <table class="table table-striped no-margin table-hover blue" id='tabelPendaftaran'>
                                      <thead>
                                        <tr>
                                          <th>Nama</th>
                                          <th>Pendidikan</th>
                                          <th>Email</th>
                                          <th>Telepon</th>
                                          <th>CV</th>
                                        </tr>
                                      </thead>
                                      <tbody>

                                      </tbody>
                                    </table>
              											<div class="col-lg-12" style="text-align: right;" id='tabelFooter'>
              												<ul class="pagination pagination-info">
              		                        <li class="active">
              		                            <a href="javascript:void(0);"> prev</a>
              		                        </li>
              		                        <li>
              		                            <a href="javascript:void(0);">1</a>
              		                        </li>
              		                        <li>
              		                            <a href="javascript:void(0);">2</a>
              		                        </li>
              		                        <li >
              		                            <a href="javascript:void(0);">3</a>
              		                        </li>
              		                        <li>
              		                            <a href="javascript:void(0);">4</a>
              		                        </li>
              		                        <li>
              		                            <a href="javascript:void(0);">5</a>
              		                        </li>
              		                        <li>
              		                            <a href="javascript:void(0);">next </a>
              		                        </li>
              		                    </ul>
              											</div>
                                  </form>
            										</div>
            									</div>
            								</div>
            							</div>
            						</div>
            					</div>
            				</section>

                    <div id='myModal' class='modal fade' role='dialog'>
                    <div class='modal-dialog'>
                      <div class='modal-content'>
                        <div class='modal-header'>
                          <button type='button' class='close' data-dismiss='modal'>&times;</button>
                          <h4 class='modal-title'>KONFIRMASI PENDAFTARAN</h4>
                        </div>
                          <div id ='contentModal'>

                          </div>
                        <div class='modal-footer'>
                          <button type="button" id = 'buttonSave' class="btn ink-reaction btn-raised btn-primary" onclick="saveEditPendaftaran(<?php echo $_GET['idEdit'] ?>);">Simpan</button>
                          <button type="button" id='buttonDismiss' class="btn ink-reaction btn-raised btn-danger" data-dismiss='modal'>batal</button>
                        </div>
                      </div>

                    </div>
                  </div>


                  <div id="myModalImage" class="modal" onclick="closeImage();">
                    <img class="modal-content" id="img01">
                    <div id="captionImage"></div>
                  </div>

            			</div>
                  <?php
              }
          }
         ?>

<div class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" id="LoadingImage" style="display: none;">
  <div class="modal-dialog modal-notice" style="height: 100%;">
      <div class="modal-content" style="background-color: transparent; border: unset; box-shadow: unset; margin-top: 50%;">
          <div class="modal-body">
              <!-- <div id="LoadingImage"> -->
                <img src="img/unnamed.gif" style="width: 30%; height: 30%; display: block; margin: auto;">
              <!-- </div> -->
          </div>
      </div>
  </div>
</div>

<?php
     break;
     }

}

?>
