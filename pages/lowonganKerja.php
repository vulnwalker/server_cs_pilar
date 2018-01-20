<?php
$tipe = @$_GET['tipe'];
$cek = "";
$err = "";
$content = "";
$tableName = "lowongan_kerja";
if(!empty($tipe)){
  include "../include/config.php";
  foreach ($_POST as $key => $value) {
      $$key = $value;
  }
}



switch($tipe){

  case 'downloadCV':{
    $getData = sqlArray(sqlQuery("select * from lamaran where id = '$id'"));
    $content = array('cv' => "http://pilar.web.id/cv/".$getData['cv']);
    echo generateAPI($cek,$err,$content);
  break;
  }
  case 'saveLowonganKerja':{
      if(empty($judulLowongan)){
          $err = "Isi judul lowongan";
      }elseif(empty($posisiLowongan)){
          $err = "Pilih Posisi Pekerjaan";
      }elseif(empty($spesifikasi)){
          $err = "Isi qualifikasi lowongan";
      }elseif(empty($deskripsiLowongan)){
          $err = "Isi deskripsi pekerjaan ";
      }elseif(empty($jenisKelamin)){
          $err = "Pilih jenis kelamin";
      }
      if(empty($err)){
          $expodeSpesifikasi = explode("\n",$spesifikasi);
          for ($i=0; $i < sizeof($expodeSpesifikasi) ; $i++) {
            $listSpesifikasi .= "<li>".$expodeSpesifikasi[$i]."</li>";
          }
          $imageTitle = baseToImage($baseImageTitle,"images/loker/".md5(date("Y-m-d")).md5(date("H:i:s")));
          $data = array(
                  'publish' => $statusPublish,
                  'judul' => $judulLowongan,
                  'tanggal_buat' => date("Y-m-d"),
                  'posisi' => $posisiLowongan,
                  'pendidikan' => implode(";",$pendidikan),
                  'salary' => removeExtHarga($salaryMinimum)."-".removeExtHarga($salaryMaximum),
                  'jam_kerja' => $jamKerja,
                  'pengalaman' => $pengalamanMinimal."-".$pengalamanMaximal,
                  'deskripsi' =>  base64_encode($deskripsiLowongan),
                  'spesifikasi' =>  $listSpesifikasi,
                  'usia' => $usiaMinimal."-".$usiaMaximal,
                  'gender' => $jenisKelamin,
                  'image_title' => "images/loker/".md5(date("Y-m-d")).md5(date("H:i:s")),
          );
          $query = sqlInsert($tableName,$data);
          sqlQuery($query);
          $cek = $query;
      }
      echo generateAPI($cek,$err,$content);
    break;
    }

 case 'saveEditLowonganKerja':{
   if(empty($judulLowongan)){
       $err = "Isi judul lowongan";
   }elseif(empty($posisiLowongan)){
       $err = "Pilih Posisi Pekerjaan";
   }elseif(empty($spesifikasi)){
       $err = "Isi qualifikasi lowongan";
   }elseif(empty($deskripsiLowongan)){
       $err = "Isi deskripsi pekerjaan ";
   }elseif(empty($jenisKelamin)){
       $err = "Pilih jenis kelamin";
   }
   if(empty($err)){
       $expodeSpesifikasi = explode("\n",$spesifikasi);
       for ($i=0; $i < sizeof($expodeSpesifikasi) ; $i++) {
         $listSpesifikasi .= "<li>".$expodeSpesifikasi[$i]."</li>";
       }
       $getDataSebelumnya = sqlArray(sqlQuery("select * from lowongan_kerja where id ='$idEdit'"));
       unlink($getDataSebelumnya['image_title']);
       $imageTitle = baseToImage($baseImageTitle,"images/loker/".md5(date("Y-m-d")).md5(date("H:i:s")));
       $data = array(
               'publish' => $statusPublish,
               'judul' => $judulLowongan,
               'tanggal_buat' => date("Y-m-d"),
               'posisi' => $posisiLowongan,
               'pendidikan' => implode(";",$pendidikan),
               'salary' => removeExtHarga($salaryMinimum)."-".removeExtHarga($salaryMaximum),
               'jam_kerja' => $jamKerja,
               'pengalaman' => $pengalamanMinimal."-".$pengalamanMaximal,
               'deskripsi' =>  base64_encode($deskripsiLowongan),
               'spesifikasi' =>  $listSpesifikasi,
               'usia' => $usiaMinimal."-".$usiaMaximal,
               'gender' => $jenisKelamin,
               'image_title' => "images/loker/".md5(date("Y-m-d")).md5(date("H:i:s")),
       );
       $query = sqlUpdate($tableName,$data,"id = '$idEdit'");
       sqlQuery($query);
       $cek = $query;
   }
   echo generateAPI($cek,$err,$content);
   break;
   }

    case 'Hapus':{
      for ($i=0; $i < sizeof($lowonganKerja_cb) ; $i++) {
        $query = "delete from $tableName where id = '".$lowonganKerja_cb[$i]."'";
        sqlQuery($query);
      }

      $cek = $query;
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'Edit':{

      $content = array("idEdit" => $lowonganKerja_cb[0]);
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'loadTable':{
      if(!empty($searchData)){
        $getColom = sqlQuery("desc $tableName");
        while ($dataColomn = sqlArray($getColom)) {

        }
        $arrKondisi[] = "judul like '%$searchData%' ";
        $arrKondisi[] = "posisi like '%$searchData%' ";
        $arrKondisi[] = "pendidikan like '%$searchData%' ";
        $arrKondisi[] = "salary like '%$searchData%' ";
        $arrKondisi[] = "jam_kerja like '%$searchData%' ";
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
          $getPosisi = sqlArray(sqlQuery("select * from ref_posisi where id = '$posisi'"));
          $namaPosisi = $getPosisi['posisi'];
          $explodePendidikan = explode(';',$pendidikan);
          if(sizeof($explodePendidikan) != 0){
              for ($i=0; $i < sizeof($explodePendidikan) ; $i++) {
                  $getNamaPendidikan = sqlArray(sqlQuery("select * from ref_pendidikan where id ='".$explodePendidikan[$i]."'"));
                  $listPendidikan .= $getNamaPendidikan['tingkat'].", ";
              }
          }else{
              $getNamaPendidikan = sqlArray(sqlQuery("select * from ref_pendidikan where id ='$pendidikan'"));
              $listPendidikan = $getNamaPendidikan['tingkat'];
          }
          $explodeSalary = explode("-",$salary);
          if($publish == '1'){
              $statusPublish = "YA";
          }elseif($publish == '2'){
              $statusPublish = "TIDAK";
          }
          $jumlahLamaran = sqlNumRow(sqlQuery("select * from lamaran where id_lowongan = '$id'"));

        $data .= "     <tr>
                          <td class='text-center' width='20px;'  style='vertical-align:middle;'>$nomor</td>
                          <td class='text-center' width='20px;'  style='vertical-align:middle;'>
                            <div  class='checkbox checkbox-inline checkbox-styled'>
                                    <label>
                                    ".setCekBox($nomorCB,$id,'','lowonganKerja')."
                                <span></span>
															</label>
														</div>
                            </td>
                          <td style='vertical-align:middle;'>$judul</td>
                          <td style='vertical-align:middle;'>$namaPosisi</td>
                          <td style='vertical-align:middle;'>$listPendidikan</td>
                          <td style='vertical-align:middle;'>".numberFormat($explodeSalary[0])."~".numberFormat($explodeSalary[1])."</td>
                          <td style='vertical-align:middle;'>$jam_kerja</td>
                          <td style='vertical-align:middle;text-align:center'>$statusPublish</td>
                          <td style='vertical-align:middle;'>$jumlahLamaran</td>
                          <td style='vertical-align:middle;text-align:center;'><div class='demo-icon-hover' style='cursor:pointer;' onclick=lamaran($id);>
        											<i class='md md-launch'></i>
        										</div></td>
                               </tr>
                    ";
          $nomor += 1;
          $nomorCB += 1;
          $listPendidikan = "";
      }

      $tabelBody = "

        <thead>
          <tr>
            <th class='text-center' width='20px;'>No</th>
            <th class='text-center' width='20px;'>
             <div class='checkbox checkbox-inline checkbox-styled' >
              <label>
                <input type='checkbox' name='lowonganKerja_toogle' id='lowonganKerja_toogle' onclick=checkSemua($nomorCB,'lowonganKerja_cb','lowonganKerja_toogle','lowonganKerja_jmlcek',this)>
              <span></span>
            </label>
          </div>
            </th>
            <th class='col-lg-3'>Judul</th>
            <th class='col-lg-1'>Posisi</th>
            <th class='col-lg-2'>Pendidikan</th>
            <th class='col-lg-2 text-center '>Salary</th>
            <th class='col-lg-1'>Jam Kerja</th>
            <th class='col-lg-1 text-center'>Publish</th>
            <th class='col-lg-1'>Lamaran</th>
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
      <input type='hidden' name='lowonganKerja_jmlcek' id='lowonganKerja_jmlcek' value='0'>";
      $content = array("tabelBody" => $tabelBody, 'tabelFooter' => $tabelFooter);
      echo generateAPI($cek,$err,$content);
    break;
    }
    case 'loadTableLamaran':{
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
      $getData = sqlQuery("select * from lamaran where id_lowongan = '$idLowongan' order by id asc $queryLimit");
      $cek = "select * from lamaran where id_lowongan = '$idLowongan' order by id desc $queryLimit";
      $nomor = 1;
      $nomorCB = 0;
      while($dataUser = sqlArray($getData)){
        foreach ($dataUser as $key => $value) {
            $$key = $value;
        }
          $getPendidikan = sqlArray(sqlQuery("select * from ref_pendidikan where id = '$pendidikan'"));
          $namaPendidikan = $getPendidikan['tingkat'];
          // $explodePendidikan = explode(';',$pendidikan);
          // if(sizeof($explodePendidikan) != 0){
          //     for ($i=0; $i < sizeof($explodePendidikan) ; $i++) {
          //         $getNamaPendidikan = sqlArray(sqlQuery("select * from ref_pendidikan where id ='".$explodePendidikan[$i]."'"));
          //         $listPendidikan .= $getNamaPendidikan['tingkat'].", ";
          //     }
          // }else{
          //     $getNamaPendidikan = sqlArray(sqlQuery("select * from ref_pendidikan where id ='$pendidikan'"));
          //     $listPendidikan = $getNamaPendidikan['tingkat'];
          // }
          // $explodeSalary = explode("-",$salary);


        $data .= "     <tr>
                          <td class='text-center' width='20px;'  style='vertical-align:middle;'>$nomor</td>
                          <td style='vertical-align:middle;'>$nama</td>
                          <td style='vertical-align:middle;'>$namaPendidikan</td>
                          <td style='vertical-align:middle;'>$email</td>
                          <td style='vertical-align:middle;'>$telepon</td>
                          <td style='vertical-align:middle;text-align:center;'><div class='demo-icon-hover' style='cursor:pointer;' onclick=onclick=downloadCV($id);>
        											<i class='md md-file-download'></i>
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
            <th class='col-lg-5'>Nama</th>
            <th class='col-lg-2'>Pendidikan</th>
            <th class='col-lg-2'>Email</th>
            <th class='col-lg-2'>Telepon</th>
            <th class='col-lg-1 text-center'>CV</th>
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
      <input type='hidden' name='lowonganKerja_jmlcek' id='lowonganKerja_jmlcek' value='0'>";
      $content = array("tabelLamaran" => $tabelBody, 'tabelFooter' => $tabelFooter);
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
                          <input type='text' class='form-control' id='searchData' name='searchData' onkeyup=limitData(); placeholder='Cari. . .'>
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
                                <li id='judul' onclick=sortData(this);><a href='#' style='width: 100%;' >Judul</a></li>
                                <li id='posisi' onclick=sortData(this);><a href='#' style='width: 100%;' >Posisi</a></li>
                                <li id='pendidikan' onclick=sortData(this);><a href='#' style='width: 100%;' >Pendidikan</a></li>
                                <li id='salary' onclick=sortData(this);><a href='#' style='width: 100%;' >Salary</a></li>
                                <li id='jam_kerja' onclick=sortData(this);><a href='#' style='width: 100%;' >Jam Kerja</a></li>
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
        var url = "http://"+window.location.hostname+"/api.php?page=lowonganKerja";
        </script>

        <style>
        .active-tick a::after {
          content: '\f00c';
          font-family: 'FontAwesome';
          float: right;
        }
        .active-tick2 a::after {
          content: '\f00c';
          font-family: 'FontAwesome';
          float: right;
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
        tbody tr:nth-child(even){
          background:#ECF0F1;
        }
        tbody tr:hover{
        background:#BDC3C7;
        }
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
        <script src="js/lowonganKerja.js"></script>
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
                  $("#pageTitle").text("LOKER");
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
                            <form id='formLowonganKerja' name="formLowonganKerja" action="#">
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
                <link rel="stylesheet" type="text/css" href="js/ImageResizeCropCanvas/css/componentLowongan.css" />
                <link rel="stylesheet" type="text/css" href="js/ImageResizeCropCanvas/css/demoLowongan.css" />
                <script src="js/ImageResizeCropCanvas/js/componentLowongan.js"></script>
                <script type="text/javascript" src="js/textboxio/textboxio.js"></script>
                <div id="content">
          				<section>
          					<div class="section-body contain-lg">
                      <form class="form" id='formLowonganKerja'>
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
      												<div class="col-sm-11">
      													<div class="form-group">
      														<input type="text" class="form-control" id="judulLowongan" name='judulLowongan'>
      														<label for="judulLowongan">Judul Lowongan</label>
      													</div>
      												</div>
                            </div>
      											<div class="row">
                              <div class="col-sm-2">
                                <div class="form-group">
                                  <?php
                                    echo cmbQuery("posisiLowongan","1","select id,posisi from ref_posisi","class='form-control' ","-- POSISI --")
                                  ?>
                                  <label for="posisiLowongan">Posisi</label>
                                </div>
                              </div>
      												<div class="col-sm-6">
                                <div class="form-group">
                                   <select class="form-control select2-list" id='pendidikan' name='pendidikan'  multiple="" tabindex="-1" style="display: none;">
                                     <?php
                                        $getDataPendidikan = sqlQuery("select * from ref_pendidikan");
                                        while ($dataPendidikan = sqlArray($getDataPendidikan)) {
                                            echo "<option value='".$dataPendidikan['id']."'>".$dataPendidikan['tingkat']."</option>";
                                        }
                                     ?>
          												</select>
          												<label>Pendidikan</label>
          											</div>
      												</div>
                              <div class="col-sm-2">
                                <div class="form-group">
                                  <?php
                                  $arrayJenisKelamin = array(
                                                          array('1','LAKI-LAKI'),
                                                          array('2','PEREMPUAN'),
                                                          array('3','LAKI-LAKI DAN PEREMPUAN'),
                                                        );
                                  echo cmbArray("jenisKelamin","1",$arrayJenisKelamin,"-- JENIS KELAMIN --","class='form-control'")
                                  ?>
                                  <label for="jenisKelamin">Jenis Kelamin</label>
                                </div>
                              </div>
                              <div class="col-sm-2">
                                <div class="form-group">
                                  <?php
                                  $arrayJamKerja = array(
                                                          array('FULL TIME','FULL TIME'),
                                                          array('PART TIME','PART TIME'),
                                                        );
                                  echo cmbArray("jamKerja","1",$arrayJamKerja,"-- JAM KERJA --","class='form-control'")
                                  ?>
                                  <label for="jamKerja">Jam Kerja</label>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-3">
                                <div class="form-group">
                                  <label >Usia</label><br>
          												<div class="input-group">
          													<div class="input-group-addon" id="usiaMinimal">21</div>
          													<div class="input-group-content form-control-static">
          														<div id="rangeUsia" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"><div class="ui-slider-range ui-widget-header ui-corner-all" style="left: 9%; width: 43%;"></div><span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0" style="left: 9%;"></span><span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0" style="left: 52%;"></span></div>
          													</div>
          													<div class="input-group-addon" id="usiaMaximal">40</div>
          												</div>

          											</div>
                              </div>
                              <div class="col-sm-3">
                                <div class="form-group">
                                  <label>Pengalaman</label>
                                  <br>
          												<div class="input-group">
          													<div class="input-group-addon" id="pengalamanMinimal">0</div>
          													<div class="input-group-content form-control-static">
          														<div id="rangePengalaman" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"><div class="ui-slider-range ui-widget-header ui-corner-all" style="left: 9%; width: 43%;"></div><span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0" style="left: 9%;"></span><span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0" style="left: 52%;"></span></div>
          													</div>
          													<div class="input-group-addon" id="pengalamanMaximal">1</div>
          												</div>

          											</div>
                              </div>
                              <div class="col-sm-3">
                                <div class="form-group">
          												<input type="text" id='salaryMinimum' name='salaryMinimum' class="form-control">
          												<label>Salary Minimum</label>
          											</div>
                              </div>
                              <div class="col-sm-3">
                                <div class="form-group">
          												<input type="text" id='salaryMaximum' name='salaryMaximum' class="form-control">
          												<label>Salary Maximum</label>
          											</div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-12">
                                <div class="form-group">
                                  <textarea name="spesifikasi" id="spesifikasi" class="form-control" rows="8" placeholder=""></textarea>
                                  <label>Qualifikasi</label>
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
                                  <img class="resize-image" id='gambarSlider' alt="image for resizing">
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-4" style="margin-bottom: 1.5%;">
                                <span class="btn ink-reaction btn-raised btn-primary">
                                  <span class="fileinput-exists" onclick='$("#imageSlider").click();'>Pilih Gambar</span>
                                  <input type="hidden" id='statusKosong' name='statusKosong'>
                                  <input style="display:none;" type="file" accept='image/x-png,image/gif,image/jpeg' onchange="imageChanged();" id='imageSlider' name="imageSlider">
                                </span>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-12">
                                  <textarea id='deskripsiLowongan' style="height:400px;"></textarea>
                              </div>
                            </div>
                            <div class="card-actionbar">
        											<div class="card-actionbar-row">
                                <button type="button" class="btn ink-reaction btn-raised btn-primary" onclick="saveLowonganKerja();">Simpan</button>
                                <button type="button" class="btn ink-reaction btn-raised btn-danger" onclick="refreshList();">batal</button>
        											</div>
        										</div>
      										</div>
      									</div>
      								</form>
      							</div>
          				</section>
          			</div>
                <script type="text/javascript">
                  $(document).ready(function() {
                      setMenuEdit('baru');
                      $(".select2-list").select2({
                        allowClear: true
                      });
                      $(".select2-list").select2({
                        allowClear: true
                      });
                      $("#pageTitle").text("LOKER");
                      textboxio.replaceAll('#deskripsiLowongan', {
                        paste: {
                          style: 'clean'
                        },
                        css: {
                          stylesheets: ['js/textboxio/example.css']
                        }
                      });


                      $("#rangeUsia").slider({range: true, min: 16, max: 60, values: [21, 40],
                          slide: function (event, ui) {
                            $('#usiaMinimal').empty().append(ui.values[ 0 ]);
                            $('#usiaMaximal').empty().append(ui.values[ 1 ]);
                          }
                        });

                      $("#rangePengalaman").slider({range: true, min: 0, max: 20, values: [0, 1],
                          slide: function (event, ui) {
                            $('#pengalamanMinimal').empty().append(ui.values[ 0 ]);
                            $('#pengalamanMaximal').empty().append(ui.values[ 1 ]);
                          }
                        });
                      $("#salaryMinimum").inputmask('Rp 999.999.999', {numericInput: true, rightAlignNumerics: false});
                      $("#salaryMaximum").inputmask('Rp 999.999.999', {numericInput: true, rightAlignNumerics: false});
                  });
                </script>
                <?php
              }elseif($_GET['action']=='edit'){
                  $getData = sqlArray(sqlQuery("select * from $tableName where id = '".$_GET['idEdit']."'"));
                  $arrayPendidikan = explode(";",$getData['pendidikan']);
                  $explodeUsiaRange = explode("-",$getData['usia']);
                  $explodePengalamanRange = explode("-",$getData['usia']);
                  $usiaMinimal = $explodeUsiaRange[0];
                  $usiaMaximal = $explodeUsiaRange[1];
                  $pengalamanMinimal = $explodePengalamanRange[0];
                  $pengalamanMaximal = $explodePengalamanRange[1];
                  $explodeSalary = explode("-",$getData['salary']);
                  $salaryMinimum = $explodeSalary[0];
                  $salaryMaximum = $explodeSalary[1];
                  $spesifikasiLowongan = str_replace('<li>',"",$getData['spesifikasi']);
                  $spesifikasiLowongan = str_replace('</li>',"\n",$spesifikasiLowongan);
                  ?>
                  <link rel="stylesheet" type="text/css" href="js/ImageResizeCropCanvas/css/componentLowongan.css" />
                  <link rel="stylesheet" type="text/css" href="js/ImageResizeCropCanvas/css/demoLowongan.css" />
                  <script src="js/ImageResizeCropCanvas/js/componentLowongan.js"></script>
                  <script type="text/javascript" src="js/textboxio/textboxio.js"></script>
                  <div id="content">
            				<section>
            					<div class="section-body contain-lg">
                        <form class="form" id='formLowonganKerja'>
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
        												<div class="col-sm-11">
        													<div class="form-group">
        														<input type="text" class="form-control" id="judulLowongan" name='judulLowongan' value='<?php echo $getData['judul'] ?>'>
        														<label for="judulLowongan">Judul Lowongan</label>
        													</div>
        												</div>
                              </div>
        											<div class="row">
                                <div class="col-sm-2">
                                  <div class="form-group">
                                    <?php
                                      echo cmbQuery("posisiLowongan",$getData['posisi'],"select id,posisi from ref_posisi","class='form-control' ","-- POSISI --")
                                    ?>
                                    <label for="posisiLowongan">Posisi</label>
                                  </div>
                                </div>
        												<div class="col-sm-6">
                                  <div class="form-group">
                                     <select class="form-control select2-list" id='pendidikan' name='pendidikan'  multiple="" tabindex="-1" style="display: none;">
                                       <?php
                                          $getDataPendidikan = sqlQuery("select * from ref_pendidikan");
                                          while ($dataPendidikan = sqlArray($getDataPendidikan)) {
                                              if(in_array($dataPendidikan['id'],$arrayPendidikan)){
                                                echo "<option value='".$dataPendidikan['id']."' selected>".$dataPendidikan['tingkat']."</option>";
                                              }else{
                                                echo "<option value='".$dataPendidikan['id']."'>".$dataPendidikan['tingkat']."</option>";
                                              }

                                          }
                                       ?>
            												</select>
            												<label>Pendidikan</label>
            											</div>
        												</div>
                                <div class="col-sm-2">
                                  <div class="form-group">
                                    <?php
                                    $arrayJenisKelamin = array(
                                                            array('1','LAKI-LAKI'),
                                                            array('2','PEREMPUAN'),
                                                            array('3','LAKI-LAKI DAN PEREMPUAN'),
                                                          );
                                    echo cmbArray("jenisKelamin",$getData['gender'],$arrayJenisKelamin,"-- JENIS KELAMIN --","class='form-control'")
                                    ?>
                                    <label for="jenisKelamin">Jenis Kelamin</label>
                                  </div>
                                </div>
                                <div class="col-sm-2">
                                  <div class="form-group">
                                    <?php
                                    $arrayJamKerja = array(
                                                            array('FULL TIME','FULL TIME'),
                                                            array('PART TIME','PART TIME'),
                                                          );
                                    echo cmbArray("jamKerja",$getData['jam_kerja'],$arrayJamKerja,"-- JAM KERJA --","class='form-control'")
                                    ?>
                                    <label for="jamKerja">Jam Kerja</label>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-sm-3">
                                  <div class="form-group">
                                    <label >Usia</label><br>
            												<div class="input-group">
            													<div class="input-group-addon" id="usiaMinimal"><?php echo $usiaMinimal ?></div>
            													<div class="input-group-content form-control-static">
            														<div id="rangeUsia" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"><div class="ui-slider-range ui-widget-header ui-corner-all" style="left: 9%; width: 43%;"></div><span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0" style="left: 9%;"></span><span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0" style="left: 52%;"></span></div>
            													</div>
            													<div class="input-group-addon" id="usiaMaximal"><?php echo $usiaMaximal ?></div>
            												</div>

            											</div>
                                </div>
                                <div class="col-sm-3">
                                  <div class="form-group">
                                    <label>Pengalaman</label>
                                    <br>
            												<div class="input-group">
            													<div class="input-group-addon" id="pengalamanMinimal"><?php echo $pengalamanMinimal ?></div>
            													<div class="input-group-content form-control-static">
            														<div id="rangePengalaman" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"><div class="ui-slider-range ui-widget-header ui-corner-all" style="left: 9%; width: 43%;"></div><span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0" style="left: 9%;"></span><span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0" style="left: 52%;"></span></div>
            													</div>
            													<div class="input-group-addon" id="pengalamanMaximal"><?php echo $pengalamanMaximal ?></div>
            												</div>

            											</div>
                                </div>
                                <div class="col-sm-3">
                                  <div class="form-group">
            												<input type="text" id='salaryMinimum' name='salaryMinimum' value="<?php echo $salaryMinimum ?>" class="form-control">
            												<label>Salary Minimum</label>
            											</div>
                                </div>
                                <div class="col-sm-3">
                                  <div class="form-group">
            												<input type="text" id='salaryMaximum' name='salaryMaximum' value="<?php echo $salaryMaximum ?>" class="form-control">
            												<label>Salary Maximum</label>
            											</div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-sm-12">
                                  <div class="form-group">
                                    <textarea name="spesifikasi" id="spesifikasi" class="form-control" rows="8" placeholder=""><?php echo $spesifikasiLowongan ?></textarea>
                                    <label>Qualifikasi</label>
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
                                    <img class="resize-image" id='gambarSlider' src="<?php echo $getData['image_title'] ?>"  alt="image for resizing">
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-sm-4" style="margin-bottom: 1.5%;">
                                  <span class="btn ink-reaction btn-raised btn-primary">
                                    <span class="fileinput-exists" onclick='$("#imageSlider").click();'>Pilih Gambar</span>
                                    <input type="hidden" id='statusKosong' name='statusKosong'>
                                    <input style="display:none;" type="file" accept='image/x-png,image/gif,image/jpeg' onchange="imageChanged();" id='imageSlider' name="imageSlider">
                                  </span>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-sm-12">
                                    <textarea id='deskripsiLowongan' style="height:400px;"><?php echo base64_decode($getData['deskripsi']) ?></textarea>
                                </div>
                              </div>
                              <div class="card-actionbar">
          											<div class="card-actionbar-row">
                                  <button type="button" class="btn ink-reaction btn-raised btn-primary" onclick="saveEditLowonganKerja(<?php echo $_GET['idEdit'] ?>);">Simpan</button>
                                  <button type="button" class="btn ink-reaction btn-raised btn-danger" onclick="refreshList();">batal</button>
          											</div>
          										</div>
        										</div>
        									</div>
        								</form>
        							</div>
            				</section>
            			</div>
                  <script type="text/javascript">
                    $(document).ready(function() {
                        setMenuEdit('baru');
                        $(".select2-list").select2({
                          allowClear: true
                        });
                        $(".select2-list").select2({
                          allowClear: true
                        });
                        $("#pageTitle").text("LOKER");
                        textboxio.replaceAll('#deskripsiLowongan', {
                          paste: {
                            style: 'clean'
                          },
                          css: {
                            stylesheets: ['js/textboxio/example.css']
                          }
                        });

                        $("#rangeUsia").slider({range: true, min: 16, max: 60, values: [<?php echo $usiaMinimal ?>, <?php echo $usiaMaximal ?>],
                            slide: function (event, ui) {
                              $('#usiaMinimal').empty().append(ui.values[ 0 ]);
                              $('#usiaMaximal').empty().append(ui.values[ 1 ]);
                            }
                          });

                        $("#rangePengalaman").slider({range: true, min: 0, max: 20, values: [<?php echo $pengalamanMinimal ?>, <?php echo $pengalamanMaximal ?>],
                            slide: function (event, ui) {
                              $('#pengalamanMinimal').empty().append(ui.values[ 0 ]);
                              $('#pengalamanMaximal').empty().append(ui.values[ 1 ]);
                            }
                          });
                        $("#salaryMinimum").inputmask('Rp 999.999.999', {numericInput: true, rightAlignNumerics: false});
                        $("#salaryMaximum").inputmask('Rp 999.999.999', {numericInput: true, rightAlignNumerics: false});
                        resizeableImage($('#gambarSlider'));
                        $('.component').show();
                    });
                  </script>
                  <?php
              }elseif($_GET['action'] == 'lamaran'){
                ?>
                <script type="text/javascript">
                  $(document).ready(function() {
                      loadTableLamaran(1,50,<?php echo $_GET['idLowongan'] ?>);
                      setMenuEdit('baru');
                      $("#pageTitle").text("LAMARAN");
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
                                <form id='formLowonganKerja' name="formLowonganKerja" action="#">
                                  <table class="table table-striped no-margin table-hover blue" id='tabelLamaran'>
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
