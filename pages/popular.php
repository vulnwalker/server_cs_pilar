<?php
$tipe = @$_GET['tipe'];
$cek = "";
$err = "";
$content = "";
$tableName = "popular";
if(!empty($tipe)){
  include "../include/config.php";
  foreach ($_POST as $key => $value) {
      $$key = $value;
  }
}

if(!empty($tipe)){
  // include "../include/config.php";
  foreach ($_POST as $key => $value) {
      $$key = $value;
  }
}

switch($tipe){

  case 'savePopular':{
   if(empty($judulPopular)){
       $err = "Isi Judul";
   }elseif(empty($statusPublish)){
       $err = "Pilih status publish";
   }
   if(empty($err)){
     $getDataUser = sqlArray(sqlQuery("select * from users where username ='".$_SESSION['username']."'"));
     $data = array(
             'judul' => $judulPopular,
             'isi_popular' => base64_encode($isiPopular),
             'status' => $statusPublish,
             'tanggal_create' =>  date("Y-m-d"),
             'jam_create' =>  date("H:i"),
             'tanggal_update' =>  date("Y-m-d"),
             'jam_update' =>  date("H:i"),
             'penulis' => $getDataUser['id']
     );
     $query = sqlInsert("popular",$data);
     sqlQuery($query);
     $cek = $query;
   }
   echo generateAPI($cek,$err,$content);
 break;
 }

 case 'saveEditPopular':{
     if(empty($judulPopular)){
         $err = "Isi Judul";
     }elseif(empty($statusPublish)){
         $err = "Pilih status publish";
     }
     if(empty($err)){
       $getDataUser = sqlArray(sqlQuery("select * from users where username ='".$_SESSION['username']."'"));
       $data = array(
               'judul' => $judulPopular,
               'isi_popular' => base64_encode($isiPopular),
               'status' => $statusPublish,
               'tanggal_update' =>  date("Y-m-d"),
               'jam_update' =>  date("H:i"),
               'penulis' => $getDataUser['id']
       );
       $query = sqlUpdate("popular",$data,"id = '$idEdit'");
       sqlQuery($query);
       $cek = $query;
     }
     echo generateAPI($cek,$err,$content);
   break;
   }

    case 'Hapus':{
      for ($i=0; $i < sizeof($popular_cb) ; $i++) {
        $query = "delete from $tableName where id = '".$popular_cb[$i]."'";
        sqlQuery($query);
      }

      $cek = $query;
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'Edit':{

      $content = array("idEdit" => $popular_cb[0]);
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'loadTable':{
      if(!empty($searchData)){
        $getColom = sqlQuery("desc $tableName");
        while ($dataColomn = sqlArray($getColom)) {

        }
        $arrKondisi[] = "judul like '%$searchData%' ";
        $arrKondisi[] = "tanggal_create like '%$searchData%' ";
        $arrKondisi[] = "jam_create like '%$searchData%' ";
        $arrKondisi[] = "penulis like '%$searchData%' ";
        $arrKondisi[] = "status like '%$searchData%' ";
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
      // if (!empty($sorter)) {
        // $kondisiSort = "ORDER BY jumlah_viewer desc";
      // }
      $getData = sqlQuery("select * from $tableName $kondisi order by jumlah_viewer desc $queryLimit");
      $cek = "select * from $tableName $kondisi order by jumlah_viewer desc $queryLimit";
      $nomor = 1;
      $nomorCB = 0;
      while($dataUser = sqlArray($getData)){
        foreach ($dataUser as $key => $value) {
            $$key = $value;
        }
        if($status == '1'){
            $status = "YA";
        }else{
            $status = "TIDAK";
        }
        if($kategori == 'INFORMASI'){
          $getNamaInformasi = sqlArray(sqlQuery("select * from informasi where id = '".$id_item."' "));
          $judul = $getNamaInformasi['judul'];
        }elseif($kategori == 'ACARA'){
          $getNamaAcara = sqlArray(sqlQuery("select * from acara where id = '".$id_item."' "));
          $judul = $getNamaAcara['nama_acara'];
        }elseif($kategori == 'PRODUK'){
          $getNamaProduk = sqlArray(sqlQuery("select * from produk where id = '".$id_item."' "));
          $judul = $getNamaProduk['nama_produk'];
        }elseif($kategori == 'LOWONGAN KERJA'){
          $getNamaLowonganKerja = sqlArray(sqlQuery("select * from lowongan_kerja where id = '".$id_item."' "));
          $judul = $getNamaLowonganKerja['judul'];
        }
        $data .= "     <tr>
                          <td class='text-center' width='20px;'  style='vertical-align:middle;'>$nomor</td>
                          <td  class='col-lg-2' style='vertical-align:middle;'>$judul</td>
                          <td  class='col-lg-2' style='vertical-align:middle;'>$kategori</td>
                          <td  class='col-lg-2' style='vertical-align:middle;'>$jumlah_viewer</td>

                               </tr>
                    ";
          $nomor += 1;
          $nomorCB += 1;
          $judul = "";
      }

      $tabelBody = "

        <thead>
          <tr>
            <th class='text-center' width='20px;'>No</th>
            <th class='col-lg-9'>Judul</th>
            <th class='col-lg-2 '>KATEGORI</th>
            <th class='col-lg-1 '>VIEWER</th>
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
      <input type='hidden' name='popular_jmlcek' id='popular_jmlcek' value='0'>";
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
                                <li id='tanggal_create' onclick=sortData(this);><a href='#' style='width: 100%;' >Tanggal</a></li>
                                <li id='jam_create' onclick=sortData(this);><a href='#' style='width: 100%;' >Jam</a></li>
                                <li id='penulis' onclick=sortData(this);><a href='#' style='width: 100%;' >Penulis</a></li>
                                <li id='status' onclick=sortData(this);><a href='#' style='width: 100%;' >Publish</a></li>
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
          $filterinTable = "";
        $header = "

          <ul class='header-nav header-nav-options'>
            <li class='dropdown'>
              <div class='row'>
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
       clearDirectory("temp/".$_SESSION['username']);
        ?>
        <script>
        var url = "http://"+window.location.hostname+"/api.php?page=popular";
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
        <script src="js/popular.js"></script>
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
                  $("#pageTitle").text("POPULAR");
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
                            <form id='formPopular' name="formPopular" action="#">
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
                <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.css">
                <link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.3/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
                <link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.3/css/froala_style.min.css" rel="stylesheet" type="text/css" />
                <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
                <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.js"></script>
                <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/mode/xml/xml.min.js"></script>
                <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.3/js/froala_editor.pkgd.min.js"></script> -->
                <script type="text/javascript" src="js/textboxio/textboxio.js"></script>


                <div id="content">
          				<section>
          					<div class="section-body contain-lg">
                      <form class="form" id='formPopular'>
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
      														<input type="text" class="form-control" id="judulPopular" name='judulPopular'>
      														<label for="judulPopular">Judul Popular</label>
      													</div>
      												</div>
                          </div>

                          <div class="row">
                            <div class="col-sm-12">
                                <textarea id='isiPopular' style="height: 800px;"></textarea>
                            </div>
                          </div>
                          <div class="card-actionbar">
      											<div class="card-actionbar-row">
                              <button type="button" class="btn ink-reaction btn-raised btn-primary" onclick="savePopular();">Simpan</button>
                              <button type="button" class="btn ink-reaction btn-raised btn-danger" onclick="refreshList();">batal</button>
      											</div>
      										</div>
      										</div><!--end .card-body -->

      									</div><!--end .card -->
      								</form>
      							</div>
          				</section>
          			</div>
                <script type="text/javascript">
                  $(document).ready(function() {
                      setMenuEdit('baru');
                      $("#pageTitle").text("POPULAR");
                      // $("#isiPopular").froalaEditor();
                      textboxio.replaceAll('#isiPopular', {
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
                  <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
                  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.css">
                  <link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.3/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
                  <link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.3/css/froala_style.min.css" rel="stylesheet" type="text/css" />
                  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
                  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.js"></script>
                  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/mode/xml/xml.min.js"></script>
                  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.3/js/froala_editor.pkgd.min.js"></script> -->
                  <script type="text/javascript" src="js/textboxio/textboxio.js"></script>

                  <div id="content">
                    <section>
                      <div class="section-body contain-lg">
                        <form class="form" id='formPopular'>
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
                                      echo cmbArrayEmpty("statusPublish",$getData['status'],$arrayStatus,"-- PUBLISH --","class='form-control' ")
                                    ?>
                                    <label for="Firstname2">PUBLISH</label>
                                  </div>
                                </div>
                                <div class="col-sm-11">
                                  <div class="form-group">
                                    <input type="text" class="form-control" id="judulPopular" name='judulPopular' value='<?php echo $getData['judul'] ?>'>
                                    <label for="judulPopular">Judul Popular</label>
                                  </div>
                                </div>
                            </div>

                            <div class="row">
                              <div class="col-sm-12">
                                  <textarea id='isiPopular' style="height: 800px;"><?php echo base64_decode($getData['isi_popular']) ?></textarea>
                              </div>
                            </div>
                            <div class="card-actionbar">
                              <div class="card-actionbar-row">
                                <button type="button" class="btn ink-reaction btn-raised btn-primary" onclick="saveEditPopular(<?php echo $_GET['idEdit'] ?>);">Simpan</button>
                                <button type="button" class="btn ink-reaction btn-raised btn-danger" onclick="refreshList();">batal</button>
                              </div>
                            </div>
                            </div><!--end .card-body -->

                          </div><!--end .card -->
                        </form>
                      </div>
                    </section>
                  </div>
                  <script type="text/javascript">
                    $(document).ready(function() {
                        setMenuEdit('baru');
                        $("#pageTitle").text("POPULAR");
                        // $("#isiPopular").froalaEditor();
                        textboxio.replaceAll('#isiPopular', {
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
