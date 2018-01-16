<?php
$tipe = @$_GET['tipe'];
$cek = "";
$err = "";
$content = "";
$tableName = "produk";
if(!empty($tipe)){
  include "../include/config.php";
  foreach ($_POST as $key => $value) {
      $$key = $value;
  }
}
function unlinkDir($dir)
{
    $dirs = array($dir);
    $files = array() ;
    for($i=0;;$i++)
    {
        if(isset($dirs[$i]))
            $dir =  $dirs[$i];
        else
            break ;
        if($openDir = opendir($dir))
        {
            while($readDir = @readdir($openDir))
            {
                if($readDir != "." && $readDir != "..")
                {
                    if(is_dir($dir."/".$readDir))
                    {
                        $dirs[] = $dir."/".$readDir ;
                    }
                    else
                    {
                        $files[] = $dir."/".$readDir ;
                    }
                }
            }
        }
    }
    foreach($files as $file)
    {
        unlink($file) ;
    }
    $dirs = array_reverse($dirs) ;
    foreach($dirs as $dir)
    {
        rmdir($dir) ;
    }
}
function createDescFile($fileName,$descSreenShot) {
  $fileDesc = fopen( "temp/".$_SESSION['username']."/$fileName".".desc", 'wb' );
  fwrite( $fileDesc, $descSreenShot );
  fclose( $fileDesc );
}
if(!empty($tipe)){
  // include "../include/config.php";
  foreach ($_POST as $key => $value) {
      $$key = $value;
  }
}

switch($tipe){
  case 'saveDescSreenshot':{
      $fileDesc = fopen( "temp/".$_SESSION['username']."/$namaFile".".desc", 'wb' );
      fwrite( $fileDesc, $descSreenShot );
      fclose( $fileDesc );

      $content = array(
                        'srcImage' => "temp/".$_SESSION['username']."/$namaFile",
                        'descScreenShot' => file_get_contents($namaFile.".desc")
                      );
      echo generateAPI($cek,$err,$content);
    break;
  }
  case 'deskripsiScreenShot':{
      $descScreenShot = file_get_contents("temp/".$_SESSION['username']."/$namaFile".".desc");
      if($descScreenShot){

      }else{
          $descScreenShot = "";
      }
      $content = array(
                        'srcImage' => "temp/".$_SESSION['username']."/$namaFile",
                        'descScreenShot' =>$descScreenShot
                      );
      echo generateAPI($cek,$err,$content);
    break;
    }
  case 'saveProduk':{
    if(empty($namaProduk)){
        $err = "Isi nama produk";
    }elseif(empty($statusPublish)){
        $err = "Pilih status publish";
    }elseif(empty($statusKosong)){
        $err = "Pilih gambar Produk";
    }elseif(sqlNumRow(sqlQuery("select * from $tableName where nama_produk = '$namaProduk'")) !=0){
        $err = "Nama produk sudah ada !";
    }
    if(empty($err)){

      $listImage = getImage("temp/".$_SESSION['username'],"images/produk/$namaProduk");
      $imageTitle = baseToImage($baseGambarProduk,"images/produk/$namaProduk/title.jpg");
      $data = array(
              'nama_produk' => $namaProduk,
              'status' => $statusPublish,
              'tanggal' =>  date("Y-m-d"),
              'image_title' => "images/produk/$namaProduk/title.jpg",
              'deskripsi' => $deskripsiProduk,
              'screen_shot' => $listImage,
      );
      $query = sqlInsert($tableName,$data);
      sqlQuery($query);
      $cek = $query;

    }

    echo generateAPI($cek,$err,$content);
  break;
  }

  case 'saveEditProduk':{
    if(empty($namaProduk)){
        $err = "Isi nama produk";
    }elseif(empty($statusPublish)){
        $err = "Pilih status publish";
    }elseif(sqlNumRow(sqlQuery("select * from $tableName where nama_produk = '$namaProduk' and id !='$idEdit'")) !=0){
        $err = "Nama produk sudah ada !";
    }
    if(empty($err)){
      $getOldProduk = sqlArray(sqlQuery("select * from produk where id = '$idEdit'"));
      unlinkDir("images/produk/".$getOldProduk['nama_produk']);
      $listImage = getImage("temp/".$_SESSION['username'],"images/produk/$namaProduk");
      $imageTitle = baseToImage($baseGambarProduk,"images/produk/$namaProduk/title.jpg");
      $data = array(
              'nama_produk' => $namaProduk,
              'status' => $statusPublish,
              'tanggal' =>  date("Y-m-d"),
              'image_title' => "images/produk/$namaProduk/title.jpg",
              'deskripsi' => $deskripsiProduk,
              'screen_shot' => $listImage,
      );
      $query = sqlUpdate($tableName,$data,"id = '$idEdit'");
      sqlQuery($query);
      $cek = $query;
   }

    echo generateAPI($cek,$err,$content);
  break;
  }

    case 'Hapus':{
      for ($i=0; $i < sizeof($produk_cb) ; $i++) {
        $query = "delete from $tableName where id = '".$produk_cb[$i]."'";
        sqlQuery($query);
      }

      $cek = $query;
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'Edit':{

      $content = array("idEdit" => $produk_cb[0]);
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'loadTable':{
      if(!empty($searchData)){
        $getColom = sqlQuery("desc $tableName");
        while ($dataColomn = sqlArray($getColom)) {
          
        }
        $arrKondisi[] = "nama_produk like '%$searchData%' ";
        $arrKondisi[] = "image_title like '%$searchData%' ";
        $arrKondisi[] = "deskripsi like '%$searchData%' ";
        $arrKondisi[] = "status like '%$searchData%' ";
        $arrKondisi[] = "screen_shot like '%$searchData%' ";
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
      $getData = sqlQuery("select * from $tableName $kondisi order by id desc $queryLimit");
      $cek = "select * from $tableName $kondisi $queryLimit";
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
        $data .= "     <tr>
                          <td class='text-center' width='20px;'  style='vertical-align:middle;'>$nomor</td>
                          <td class='text-center' width='20px;'  style='vertical-align:middle;'>
                            <div  class='checkbox checkbox-inline checkbox-styled'>
                                    <label>
                                    ".setCekBox($nomorCB,$id,'','produk')."
                                <span></span>
															</label>
														</div>
                            </td>
                            <td  class='col-lg-2 text-center' style='vertical-align:middle;'><img src='$image_title' onclick = imageClicked(this); alt='$nama_produk' class='materialboxed' style='width:100px;height:100px;'></img></td>
                          <td  class='col-lg-2' style='vertical-align:middle;'>$nama_produk</td>
                          <td  class='col-lg-2 text-center' style='vertical-align:middle;'>$status</td>
                        <!--  <td  class='col-lg-2  text-center' style='vertical-align:middle;'><input type='button' class='btn ink-reaction btn-raised btn-primary' onclick=showGambarProduk($id); value='Lihat'></td> -->
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
                <input type='checkbox' name='produk_toogle' id='produk_toogle' onclick=checkSemua($nomorCB,'produk_cb','produk_toogle','produk_jmlcek',this)>
              <span></span>
            </label>
          </div>
            </th>
            <th class='col-lg-1 text-center'>Gambar</th>
            <th class='col-lg-10'>Nama</th>
            <th class='col-lg-1 text-center'>Publish</th>
          <!--  <th class='col-lg-3 text-center'>Screen Shot</th> -->
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
      <input type='hidden' name='produk_jmlcek' id='produk_jmlcek' value='0'>";
      $content = array("tabelBody" => $tabelBody, 'tabelFooter' => $tabelFooter);
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'setMenuEdit':{
      if($statusMenu == 'index'){
        $filterinTable = "
          <ul class='header-nav header-nav-options'>
            <li class='dropdown'>
              <div class='row'>
                <div class='col-xs-3 col-sm-3 col-md-3 col-lg-3'>
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
                <div class='col-xs-3 col-sm-3 col-md-3 col-lg-3'>
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
        var url = "http://"+window.location.hostname+"/api.php?page=produk";
        </script>

        <style>
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
        <script src="js/produk.js"></script>
        <script>
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
                  $("#pageTitle").text("PRODUK");
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
                            <form id='formProduk' name="formProduk" action="#">
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


    <div id="myModal" class="modal" onclick="closeImage();">
      <img class="modal-content" id="img01">
      <div id="captionImage"></div>
    </div>

            <?php
          }else{
              if($_GET['action'] == 'baru'){
                clearDirectory("temp/".$_SESSION['username']);
                ?>
                <link rel="stylesheet" type="text/css" href="js/ImageResizeCropCanvas/css/componentProduk.css" />
                <link rel="stylesheet" type="text/css" href="js/ImageResizeCropCanvas/css/demoProduk.css" />
            		<script src="js/ImageResizeCropCanvas/js/component.js"></script>

                <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.css">
                <link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.3/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
                <link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.3/css/froala_style.min.css" rel="stylesheet" type="text/css" />
                <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
                <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.js"></script>
                <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/mode/xml/xml.min.js"></script>
                <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.3/js/froala_editor.pkgd.min.js"></script> -->
                <script type="text/javascript" src="js/textboxio/textboxio.js"></script>


                <script src="js/dropzone/dropzone.js"></script>
                <link rel="stylesheet" href="js/dropzone/dropzone.css">
                <script>

                </script>


                <div id="content">
          				<section>
          					<div class="section-body contain-lg">
                      <form class="form" id='formProduk'>
      									<div class="card">
      										<div class="card-body floating-label">
      											<div class="row">
      												<div class="col-sm-2">
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
      												<div class="col-sm-10">
      													<div class="form-group">
      														<input type="text" class="form-control" id="namaProduk" name='namaProduk'>
      														<label for="namaProduk">Nama Produk</label>
      													</div>
      												</div>
      											</div>
      											<div class="row">
      												<div class="col-sm-12">
                                <div class="component">
                                  <div class="overlay">
                                    <div class="overlay-inner">
                                    </div>
                                  </div>
                                  <img class="resize-image" id='gambarProduk' alt="image for resizing">
                                </div>
      												</div>
      											</div>
      											<div class="row">
      												<div class="col-sm-4">
                                <span class="btn ink-reaction btn-raised btn-primary">
                                  <span class="fileinput-exists" onclick='$("#imageProduk").click();'>Pilih Gambar</span>
                                  <input type="hidden" id='statusKosong' name='statusKosong'>
                                  <input style="display:none;" type="file" accept='image/x-png,image/gif,image/jpeg' onchange="imageChanged();" id='imageProduk' name="imageProduk">
                                </span>
      												</div>
      											</div>
                          </div>
                          <div class="row">
                            <div class="col-sm-12">
                                <div class='dropzone'  >
                                  <h3>Screen Shot</h3>
                                <input type="file" multiple="multiple"  accept='image/x-png,image/gif,image/jpeg' class="dz-hidden-input" style="visibility: hidden; position: absolute; top: 0px; left: 0px; height: 0px; width: 0px;">
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-12">
                                <textarea id='deskripsiProduk'  style="height: 400px;" ></textarea>
                            </div>
                          </div>
                          <div class="card-actionbar">
      											<div class="card-actionbar-row">
                              <button type="button" class="btn ink-reaction btn-raised btn-primary" onclick="saveProduk();">Simpan</button>
                              <button type="button" class="btn ink-reaction btn-raised btn-danger" onclick="refreshList();">batal</button>
      											</div>
      										</div>
      										</div><!--end .card-body -->

      									</div><!--end .card -->
      								</form>
                      <button type='button' id='pemicuPopup' style='display:none;' data-toggle='modal' data-target='#myModal'>SHOW</button>
                      <div id='myModal' class='modal fade' role='dialog'>
                        <div class='modal-dialog'>
                          <div class='modal-content'>
                            <div class='modal-header'>
                              <button type='button' class='close' data-dismiss='modal'>&times;</button>
                              <h4 class='modal-title'>SCREEN SHOT</h4>
                            </div>
                              <div id ='contentModal'>
                                <div class='section-body contain-lg'>
                                    <div class='card'>
                                      <div class='card-body floating-label'>
                                        <div class='row'>
                                          <div class='col-sm-12'>
                                            <div class='form-group'>
                                            <cente>  <img src="assets/img/image_placeholder.jpg" id='tempScreenShot' style="width:200px;height:200px;" alt="..."></center>
                                            </div>
                                          </div>
                                        </div>
                                        <div class='row'>
                                          <div class='col-sm-12'>
                                            <div class='form-group'>
                                              <textarea id='descSreenShot' class="form-control" rows="3"></textarea>
                                              <label for='descSreenShot'>Deskripsi</textarea>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                </div>
                              </div>
                            <div class='modal-footer'>
                              <button type="button" id = 'buttonSubmitScreenShot' class="btn ink-reaction btn-raised btn-primary" data-dismiss='modal'>Simpan</button>
                              <button type="button" id='buttonDismiss' class="btn ink-reaction btn-raised btn-danger" data-dismiss='modal'>batal</button>
                            </div>
                          </div>

                        </div>
                    </div>

      							</div>
          				</section>
          			</div>
                <script type="text/javascript">
                  $(document).ready(function() {
                      $('.component').hide();
                      setMenuEdit('baru');
                      $("#pageTitle").text("PRODUK");
                      textboxio.replaceAll('#deskripsiProduk', {
                        paste: {
                          style: 'clean'
                        },
                        css: {
                          stylesheets: ['js/textboxio/example.css']
                        }
                      });
                      Dropzone.autoDiscover = false;
                      var myDropzone = new Dropzone("div.dropzone", {
                          url: "upload.php",
                          maxFileSize: 50,
                          acceptedFiles: ".jpeg,.jpg,.png,.gif",
                          addRemoveLinks: true,
                          init: function() {
                              this.on("complete", function(file) {
                                  $(".dz-remove").html("<div><span class='fa fa-trash text-danger' style='font-size: 1.5em;cursor:pointer;' >REMOVE</span></div>");
                                  // $(".dz-details").attr("onclick","deskripsiScreenShot('"+file.name+"')");
                                  $(".dz-details").attr("style","cursor:pointer;");
                              });
                              this.on("thumbnail", function(file) {
                                console.log(file); // will send to console all available props
                                file.previewElement.addEventListener("click", function() {
                                   deskripsiScreenShot(file.name);
                                });
                            });
                              this.on("removedfile", function(file) {
                                   removeTemp(file.name);
                            });
                          }
                      });
                      $("div.dropzone").attr('class','dropzone dz-clickable');
                  });
                </script>
                <?php
              }elseif($_GET['action']=='edit'){
                  clearDirectory("temp/".$_SESSION['username']);
                  $getData = sqlArray(sqlQuery("select * from $tableName where id = '".$_GET['idEdit']."'"));
                  $decodedJSON = json_decode($getData['screen_shot']);
                   for ($i=0; $i < sizeof($decodedJSON) ; $i++) {
                       $explodeNamaGambar = explode('/',$decodedJSON[$i]->fileName);
                       copy($decodedJSON[$i]->fileName,"temp/".$_SESSION['username']."/".$explodeNamaGambar[3]);
                       createDescFile($explodeNamaGambar[3],$decodedJSON[$i]->desc);
                       $jsonScreenshot[] = array(
                                 'name' => $explodeNamaGambar[3],
                                 'size' => filesize("temp/".$_SESSION['username']."/".$explodeNamaGambar[3]),
                                 'type' => 'image/jpeg',
                                 'imageLocation' => "temp/".$_SESSION['username']."/".$explodeNamaGambar[3],
                       );
                   }

                  ?>
                  <link rel="stylesheet" type="text/css" href="js/ImageResizeCropCanvas/css/componentProduk.css" />
                  <link rel="stylesheet" type="text/css" href="js/ImageResizeCropCanvas/css/demoProduk.css" />
              		<script src="js/ImageResizeCropCanvas/js/component.js"></script>

                  <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
                  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.css">
                  <link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.3/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
                  <link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.3/css/froala_style.min.css" rel="stylesheet" type="text/css" />
                  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
                  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.js"></script>
                  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/mode/xml/xml.min.js"></script>
                  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.3/js/froala_editor.pkgd.min.js"></script> -->
                  <script type="text/javascript" src="js/textboxio/textboxio.js"></script>


                  <script src="js/dropzone/dropzone.js"></script>
                  <link rel="stylesheet" href="js/dropzone/dropzone.css">
                  <script>

                  </script>


                  <div id="content">
            				<section>
            					<div class="section-body contain-lg">
                        <form class="form" id='formProduk'>
        									<div class="card">
        										<div class="card-body floating-label">
        											<div class="row">
        												<div class="col-sm-2">
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
        												<div class="col-sm-10">
        													<div class="form-group">
        														<input type="text" class="form-control" id="namaProduk" name='namaProduk' value="<?php echo $getData['nama_produk'] ?>">
        														<label for="namaProduk">Nama Produk</label>
        													</div>
        												</div>
        											</div>
        											<div class="row">
        												<div class="col-sm-12">
                                  <div class="component">
                                    <div class="overlay">
                                      <div class="overlay-inner">
                                      </div>
                                    </div>
                                    <img class="resize-image" id='gambarProduk' src ='<?php echo $getData['image_title'] ?>' alt="image for resizing">
                                  </div>
        												</div>
        											</div>
        											<div class="row">
        												<div class="col-sm-4">
                                  <span class="btn ink-reaction btn-raised btn-primary">
                                    <span class="fileinput-exists" onclick='$("#imageProduk").click();'>Pilih Gambar</span>
                                    <input type="hidden" id='statusKosong' name='statusKosong' value="1">
                                    <input type="hidden" id='statusEdit' name='statusEdit' >
                                    <input type="file" style="display:none;" accept='image/x-png,image/gif,image/jpeg' onchange="imageChanged();" id='imageProduk' name="imageProduk">
                                  </span>
        												</div>
        											</div>
                            </div>
                            <div class="row">
                              <div class="col-sm-12">
                                  <div  class='dropzone'  >
                                    <h3>Screen Shot</h3>
                                  <input type="file" multiple="multiple"  accept='image/x-png,image/gif,image/jpeg' class="dz-hidden-input" style="visibility: hidden; position: absolute; top: 0px; left: 0px; height: 0px; width: 0px;">
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-12">
                                  <div id='deskripsiProduk'  style="height:400px;"><?php echo $getData['deskripsi'] ?></div>
                              </div>
                            </div>
                            <div class="card-actionbar">
        											<div class="card-actionbar-row">
                                <button type="button" class="btn ink-reaction btn-raised btn-primary" onclick="saveEditProduk(<?php echo $_GET['idEdit'] ?>);">Simpan</button>
                                <button type="button" class="btn ink-reaction btn-raised btn-danger" onclick="refreshList();">batal</button>
        											</div>
        										</div>
        										</div><!--end .card-body -->

        									</div><!--end .card -->
        								</form>
        							</div>
            				</section>

                    <button type='button' id='pemicuPopup' style='display:none;' data-toggle='modal' data-target='#myModal'>SHOW</button>
                    <div id='myModal' class='modal fade' role='dialog'>
                      <div class='modal-dialog'>
                        <div class='modal-content'>
                          <div class='modal-header'>
                            <button type='button' class='close' data-dismiss='modal'>&times;</button>
                            <h4 class='modal-title'>SCREEN SHOT</h4>
                          </div>
                            <div id ='contentModal'>
                              <div class='section-body contain-lg'>
                                  <div class='card'>
                                    <div class='card-body floating-label'>
                                      <div class='row'>
                                        <div class='col-sm-12'>
                                          <div class='form-group'>
                                          <cente>  <img src="assets/img/image_placeholder.jpg" id='tempScreenShot' style="width:200px;height:200px;" alt="..."></center>
                                          </div>
                                        </div>
                                      </div>
                                      <div class='row'>
                                        <div class='col-sm-12'>
                                          <div class='form-group'>
                                            <textarea id='descSreenShot' class="form-control" rows="3"></textarea>
                                            <label for='descSreenShot'>Deskripsi</textarea>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                              </div>
                            </div>
                          <div class='modal-footer'>
                            <button type="button" id = 'buttonSubmitScreenShot' class="btn ink-reaction btn-raised btn-primary" data-dismiss='modal'>Simpan</button>
                            <button type="button" id='buttonDismiss' class="btn ink-reaction btn-raised btn-danger" data-dismiss='modal'>batal</button>
                          </div>
                        </div>

                      </div>
            			</div>
                  <script type="text/javascript">
                    $(document).ready(function() {
                        resizeableImage($('#gambarProduk'));
                        setMenuEdit('baru');
                        $("#pageTitle").text("PRODUK");
                        textboxio.replaceAll('#deskripsiProduk', {
                          paste: {
                            style: 'clean'
                          },
                          css: {
                            stylesheets: ['js/textboxio/example.css']
                          }
                        });
                        Dropzone.autoDiscover = false;
                        var myDropzone = new Dropzone("div.dropzone", {
                            url: "upload.php",
                            maxFileSize: 50,
                            acceptedFiles: ".jpeg,.jpg,.png,.gif",
                            addRemoveLinks: true,
                            init: function() {
                                this.on("complete", function(file) {
                                    $(".dz-remove").html("<div><span class='fa fa-trash text-danger' style='font-size: 1.5em;cursor:pointer;' >REMOVE</span></div>");
                                    // $(".dz-details").attr("onclick","deskripsiScreenShot('"+file.name+"')");
                                    $(".dz-details").attr("style","cursor:pointer;");
                                });
                                this.on("thumbnail", function(file) {
                                  console.log(file); // will send to console all available props
                                  file.previewElement.addEventListener("click", function() {
                                     deskripsiScreenShot(file.name);
                                  });
                              });
                                this.on("removedfile", function(file) {
                                     removeTemp(file.name);
                              });
                            }
                        });
                        $("div.dropzone").attr('class','dropzone dz-clickable');
                        var existingFiles = <?php echo json_encode($jsonScreenshot) ?>;
                        for (i = 0; i < existingFiles.length; i++) {
                            myDropzone.emit("addedfile", existingFiles[i]);
                            myDropzone.emit("thumbnail", existingFiles[i], existingFiles[i].imageLocation);
                            myDropzone.emit("complete", existingFiles[i]);
                        }

                    });
                  </script>
                  <?php
              }
          }
         ?>



<?php

     break;
     }

}

?>
