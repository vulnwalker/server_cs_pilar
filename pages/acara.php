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

switch($tipe){
  case 'loadTablePendaftaran':{
    // if(!empty($searchData)){
    //   $getColom = sqlQuery("desc $tableName");
    //   while ($dataColomn = sqlArray($getColom)) {
    //     $arrKondisi[] = $dataColomn['Field']." like '%$searchData%' ";
    //   }
    //   $kondisi = join(" or ",$arrKondisi);
    //   $kondisi = " where $kondisi ";
    // }
    // if(!empty($limitTable)){
    //     if($pageKe == 1){
    //        $queryLimit  = " limit 0,$limitTable";
    //     }else{
    //        $dataMulai = ($pageKe - 1)  * $limitTable;
    //        $dataMulai +=1;
    //        $queryLimit  = " limit $dataMulai,$limitTable";
    //     }
    //
    // }
    $getData = sqlQuery("select * from reservasi_acara where id_acara = '$idAcara' order by id asc $queryLimit");
    $cek = "select * from reservasi_acara where id_acara = '$idAcara' order by id asc $queryLimit";
    $nomor = 1;
    $nomorCB = 0;
    while($dataUser = sqlArray($getData)){
      foreach ($dataUser as $key => $value) {
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
                        <td style='vertical-align:middle;'>".generateDate($tanggal_daftar)."</td>
                        <td style='vertical-align:middle;'>$jumlah_orang Orang x ".numberFormat($getDataAcara['harga_tiket'])." = ".numberFormat($totalTiket)." </td>
                        <td style='vertical-align:middle;'>$jumlah_kamar Kamar x ".$getDataAcara['lama_acara']." Hari x ".numberFormat($getDataAcara['harga_kamar'])." = ".numberFormat($totalKamar)."</td>
                        <td style='vertical-align:middle;'>$extra_bed Bed x ".$getDataAcara['lama_acara']." Hari x ".numberFormat($getDataAcara['extra_bed'])." = ".numberFormat($totalExtraBed)."</td>
                        <td style='vertical-align:middle;'>".numberFormat($totalTiket + $totalKamar + $totalExtraBed)."</td>
                        <td style='vertical-align:middle;text-align:center;'>$statusPendaftaran</td>
                             </tr>
                  ";
        $nomor += 1;
        $nomorCB += 1;
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
          <th class='col-lg-1'>Nama</th>
          <th class='col-lg-1'>Tanggal Pendaftaran</th>
          <th class='col-lg-3'>Jumlah Orang</th>
          <th class='col-lg-3'>Kamar</th>
          <th class='col-lg-3'>Extra Bed</th>
          <th class='col-lg-1'>Total</th>
          <th class='col-lg-1 text-center'>Status

<button type='button' id='pemicuPopup' style='display:none;' data-toggle='modal' data-target='#myModal'>SHOW</button>
</th>
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
    $tabelFooter = "<input type='hidden' name='acara_jmlcek' id='acara_jmlcek' value='0'>";
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
       $err = "Isi tanggal acara";
   }elseif(empty(removeExtJam($jamAcara)) || removeExtJam($jamAcara) == ':'){
       $err = "Isi jam acara";
   }elseif(empty($kuotaAcara)){
       $err = "Isi kuota acara";
   }elseif(empty($lamaAcara)){
       $err = "Isi lama acara";
   }elseif(empty(removeExtHarga($hargaTiket))){
       $err = "Isi harga partisipasi";
   }elseif(empty($deadlinePembayaran)){
       $err = "Isi lama deadline pembayaran";
   }elseif(empty($lokasiAcara)){
       $err = "Isi deskripsi acara";
   }elseif(empty($deskripsiAcara)){
       $err = "Isi deskripsi acara";
   }
   if(empty($err)){
     $kordinatLocation = getKordinat($lokasiAcara);
     $data = array(
               'nama_acara' => $namaAcara,
               'tanggal' => generateDate($tanggalAcara),
               'jam' => removeExtJam($jamAcara),
               'lokasi' =>  $lokasiAcara,
               'deskripsi' =>  base64_encode($deskripsiAcara),
               'koordinat' => $kordinatLocation,
               'kuota' => $kuotaAcara,
               'harga_tiket' => removeExtHarga($hargaTiket),
               'harga_kamar' => removeExtHarga($hargaKamar),
               'extra_bed' => removeExtHarga($hargaExtraBed),
               'lama_acara' => $lamaAcara,
               'reversed' => 0,
               'deadline_pembayaran' => $deadlinePembayaran,
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
       $err = "Isi tanggal acara";
   }elseif(empty(removeExtJam($jamAcara)) || removeExtJam($jamAcara) == ':'){
       $err = "Isi jam acara";
   }elseif(empty($kuotaAcara)){
       $err = "Isi kuota acara";
   }elseif(empty($lamaAcara)){
       $err = "Isi lama acara";
   }elseif(empty(removeExtHarga($hargaTiket))){
       $err = "Isi harga partisipasi";
   }elseif(empty($deadlinePembayaran)){
       $err = "Isi lama deadline pembayaran";
   }elseif(empty($lokasiAcara)){
       $err = "Isi deskripsi acara";
   }elseif(empty($deskripsiAcara)){
       $err = "Isi deskripsi acara";
   }
   if(empty($err)){
     $kordinatLocation = getKordinat($lokasiAcara);
     $data = array(
               'nama_acara' => $namaAcara,
               'tanggal' => generateDate($tanggalAcara),
               'jam' => removeExtJam($jamAcara),
               'lokasi' =>  $lokasiAcara,
               'deskripsi' =>  base64_encode($deskripsiAcara),
               'koordinat' => $kordinatLocation,
               'kuota' => $kuotaAcara,
               'harga_tiket' => removeExtHarga($hargaTiket),
               'harga_kamar' => removeExtHarga($hargaKamar),
               'extra_bed' => removeExtHarga($hargaExtraBed),
               'lama_acara' => $lamaAcara,
               'reversed' => 0,
               'deadline_pembayaran' => $deadlinePembayaran,
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
         if(!empty($getDataPendaftaran['jumlah_kamar'])){
            $detailInvoice.= "
            <tr>
              <td style='width: 43.0463%;'>Kamar</td>
              <td style='width: 12.9184%; text-align:right;'>".$getDataPendaftaran['jumlah_kamar']."</td>
              <td style='width: 25.0000%; text-align:right;'>".numberFormat($getDataAcara['harga_kamar'])."</td>
              <td style='width: 18.9897%; text-align:right;'>".numberFormat($getDataPendaftaran['jumlah_kamar'] * $getDataAcara['harga_kamar'] * $getDataAcara['lama_acara'])."</td>
            </tr>";
            $totalKamar = $getDataPendaftaran['jumlah_kamar'] * $getDataAcara['harga_kamar'] * $getDataAcara['lama_acara'];
         }
         if(!empty($getDataPendaftaran['extra_bed'])){
            $detailInvoice.= "
            <tr>
              <td style='width: 43.0463%;'>Extra Bed</td>
              <td style='width: 12.9184%; text-align:right;'>".$getDataPendaftaran['extra_bed']."</td>
              <td style='width: 25.0000%; text-align:right;'>".numberFormat($getDataAcara['extra_bed'])."</td>
              <td style='width: 18.9897%; text-align:right;'>".numberFormat($getDataPendaftaran['extra_bed'] * $getDataAcara['extra_bed'] * $getDataAcara['lama_acara'])."</td>
            </tr>";
            $totalExtraBed = $getDataPendaftaran['extra_bed'] * $getDataAcara['extra_bed'] * $getDataAcara['lama_acara'];

         }
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

          												<p>Pembayaran anda telah kami terima, dengan rincian sebagai berikut :</p>

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
          															<td style='width: 18.9897%; text-align:right;'>".numberFormat(($getDataPendaftaran['jumlah_orang'] * $getDataAcara['harga_tiket']) + $totalKamar + $totalExtraBed)."</td>
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
      while($dataUser = sqlArray($getData)){
        foreach ($dataUser as $key => $value) {
            $$key = $value;
        }
        if(str_replace('-','',generateDate($tanggal)) < str_replace("-","",date("Y-m-d"))){
            $status = "SELESAI";
            sqlQuery("update acara set status = 'SELESAI' where id = '$id'");
        }else{
            sqlQuery("update acara set status = 'BELUM' where id = '$id'");
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
                          <td class='text-center' style='vertical-align:middle;'>$status</td>
                          <td class='text-center' style='vertical-align:middle;'><div class='demo-icon-hover' style='cursor:pointer;' onclick=pendaftaran($id);>
        											<i class='md md-launch'></i>
        										</div></td>
                               </tr>
                    ";
          $nomor += 1;
          $nomorCB += 1;
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
                      <li><a href='#'>Ganti Password</a></li>
                      <li><a href='#'>Logout</a></li>
                    </ul>
                  </div><!--end .btn-group -->
                </div><!--end .col -->
              </div>
            </li>
          </ul>
          ";
      }elseif($statusMenu == 'pendaftaran'){
        // $filterinTable = "
        //   <ul class='header-nav header-nav-options'>
        //     <li class='dropdown'>
        //       <div class='row'>
        //         <div class='col-xs-3 col-sm-3 col-md-3 col-lg-3'>
        //           <form class='form' role='form'>
        //             <div class='form-group floating-label' style='padding-top: 0px;'>
        //               <div class='input-group'>
        //                 <span class='input-group-addon'></span>
        //                 <div class='input-group-content'>
        //                   <input type='text' class='form-control' id='searchData' name='searchData' onkeyup=limitData();>
        //                   <label for='searchData'>Search</label>
        //                 </div>
        //               </div>
        //             </div>
        //           </form>
        //         </div>
        //         <div class='col-xs-3 col-sm-3 col-md-3 col-lg-3'>
        //         <form class='form' role='form'>
        //             <div class='form-group floating-label' style='padding-top: 0px;'>
        //               <div class='input-group'>
        //                 <div class='input-group-content'>
        //                   <input type='text' onkeypress='return event.charCode >= 48 && event.charCode <= 57' class='form-control ' id='jumlahDataPerhalaman' name='jumlahDataPerhalaman' value = '50' onkeyup=limitData();>
        //                   <label for='username10'>Data / Halaman</label>
        //                 </div>
        //               </div>
        //             </div>
        //         </form>
        //         </div>
        //       </div>
        //     </li>
        //   </ul>";
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

                <div id="content">
          				<section>
          					<div class="section-body contain-lg">
                      <form class="form" id='formAcara'>
      									<div class="card">
      										<div class="card-body floating-label">
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
          												<input type="number" id='lamaAcara' name='lamaAcara' class="form-control">
          												<label>Lama</label>
          											</div>
      												</div>
                            </div>
                            <div class="row">
                              <div class="col-sm-12 col-xs-12 col-md-3 col-lg-3">
                                <div class="form-group date" >
                                    <input type="text" id='tanggalAcara' name='tanggalAcara' class="form-control">
                                    <label>Tanggal Selesai</label>
                                  <span class="input-group-addon"></span>
                                </div>
                              </div>
                              <div class="col-sm-12 col-xs-12 col-md-3 col-lg-3">
                                <div class="form-group">
                                  <input type="text" id='jamAcara' name='jamAcara' class="form-control time-mask">
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
                                <div class="form-group">
                                  <textarea name="lokasiAcara" id="lokasiAcara" class="form-control" rows="3" placeholder=""></textarea>
                                  <label>Lokasi Acara</label>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <!-- BEGIN MARKER MAP -->
                              <div class="col-md-12 col-lg-12">
                                <div class="card">
                                  <div class="card-body no-padding">
                                     <div id="map" style="height: 400px;width: 100%;"></div>
                                  </div>
                                </div><!--end .card -->
                                <em class="text-caption">Map with marker</em>
                              </div><!--end .col -->
                              <!-- END MARKER MAP -->
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
                <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCwJqGylmwWguljWAO6QfZLWrz-StuMbw8&callback=initMap"></script>
                <script>
                  function initMap() {
                    var uluru = {lat: -25.363, lng: 131.044};
                    var map = new google.maps.Map(document.getElementById('map'), {
                      zoom: 4,
                      center: uluru
                    });
                    var marker = new google.maps.Marker({
                      position: uluru,
                      map: map
                    });
                  }
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
                  ?>
                  <script type="text/javascript" src="js/textboxio/textboxio.js"></script>
                  <div id="content">
            				<section>
            					<div class="section-body contain-lg">
                        <form class="form" id='formAcara'>
        									<div class="card">
        										<div class="card-body floating-label">
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
        												<div class="col-sm-3 col-xs-12 col-md-3 col-lg-3">
                                  <div class="form-group">
            												<input type="number" id='lamaAcara' name='lamaAcara' class="form-control" value="<?php echo $getData['lama_acara'] ?>">
            												<label>Lama</label>
            											</div>
        												</div>
                              </div>

                              <div class="row">
                                <div class="col-sm-3 col-xs-12 col-md-3 col-lg-3">
                                  <div class="form-group date" >
                                      <input type="text" id='tanggalAcara' name='tanggalAcara' class="form-control" value="<?php echo generateDate($getData['tanggal']) ?>">
                                      <label>Tanggal Selesai</label>
                                    <span class="input-group-addon"></span>
                                  </div>
                                </div>
                                <div class="col-sm-3 col-xs-12 col-md-3 col-lg-3">
                                  <div class="form-group">
                                    <input type="text" id='jamAcara' name='jamAcara' class="form-control time-mask" value="<?php echo $getData['jam'] ?>">
                                    <label>Jam Selesai</label>
                                  </div>
                                </div>
                                <div class="col-sm-3 col-xs-12 col-md-3 col-lg-3">
                                  <div class="form-group">
                                    <input type="text" id='hargaTiket' name='hargaTiket' class="form-control" value="<?php echo $getData['harga_tiket'] ?>">
                                    <label>Harga Partisipasi</label>
                                  </div>
                                </div>
                                <div class="col-sm-3 col-xs-12 col-md-3 col-lg-3">
                                  <div class="form-group">
                                    <input type="number" id='deadlinePembayaran' name='deadlinePembayaran' class="form-control" value="<?php echo $getData['deadline_pembayaran'] ?>">
                                    <label>Deadline Pembayaran</label>
                                  </div>
                                </div>
                              </div>

                              <div class="row">
                                <div class="col-sm-12">
                                  <div class="form-group">
                                    <textarea name="lokasiAcara" id="lokasiAcara" class="form-control" rows="3" placeholder=""><?php echo $getData['lokasi'] ?></textarea>
                                    <label>Lokasi Acara</label>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="row">
                                  <!-- BEGIN MARKER MAP -->
                                  <div class="col-md-12 col-lg-12">
                                    <div class="card">
                                      <div class="card-body no-padding">
                                         <div id="map" style="height: 400px;width: 100%;"></div>
                                      </div>
                                    </div><!--end .card -->
                                    <em class="text-caption">Map with marker</em>
                                  </div><!--end .col -->
                                  <!-- END MARKER MAP -->
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
                  <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCwJqGylmwWguljWAO6QfZLWrz-StuMbw8&callback=initMap"></script>
                  <script>
                    function initMap() {
                      var uluru = {lat: -25.363, lng: 131.044};
                      var map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 4,
                        center: uluru
                      });
                      var marker = new google.maps.Marker({
                        position: uluru,
                        map: map
                      });
                    }
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
                      background-color: rgb(0,0,0); /* Fallback color */
                      background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
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
