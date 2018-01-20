<?php
$tipe = @$_GET['tipe'];
$cek = "";
$err = "";
$content = "";
$tableName = "slider";
if(!empty($tipe)){
  include "../include/config.php";
  foreach ($_POST as $key => $value) {
      $$key = $value;
  }
}
switch($tipe){

  case 'saveSlider':{
      if(empty($namaSlider)){
          $err = "Isi nama slider";
      }elseif(empty($statusKosong)){
          $err = "Pilih gambar";
      }
      if(empty($err)){
           baseToImage($baseGambarSlider,"images/slider/".md5($namaSlider).md5(date("Y-m-d").date("H:i:s")).".jpg");
          $data = array(
                  'nama' => $namaSlider,
                  'gambar' => "images/slider/".md5($namaSlider).md5(date("Y-m-d").date("H:i:s")).".jpg",
                  'status' =>  $statusPublish,
          );
          $query = sqlInsert("slider",$data);
          sqlQuery($query);
          $cek = $query;
      }
      echo generateAPI($cek,$err,$content);
    break;
    }
    case 'saveEditSlider':{
      if(empty($namaSlider)){
          $err = "Isi nama slider";
      }
      if(empty($err)){
           baseToImage($baseGambarSlider,"images/slider/".md5($namaSlider).md5(date("Y-m-d").date("H:i:s")).".jpg");
          $data = array(
                  'nama' => $namaSlider,
                  'gambar' => "images/slider/".md5($namaSlider).md5(date("Y-m-d").date("H:i:s")).".jpg",
                  'status' =>  $statusPublish,
          );
          $query = sqlUpdate("slider",$data,"id = '$idEdit'");
          sqlQuery($query);
          $cek = $query;
      }
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'Hapus':{
      for ($i=0; $i < sizeof($slider_cb) ; $i++) {
        $query = "delete from $tableName where id = '".$slider_cb[$i]."'";
        sqlQuery($query);
      }

      $cek = $query;
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'Edit':{

      $content = array("idEdit" => $slider_cb[0]);
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'loadTable':{
      if(!empty($searchData)){
        $getColom = sqlQuery("desc $tableName");
        while ($dataColomn = sqlArray($getColom)) {
          
        }
        $arrKondisi[] = "gambar like '%$searchData%' ";
        $arrKondisi[] = "nama like '%$searchData%' ";
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
                                    ".setCekBox($nomorCB,$id,'','slider')."
                                <span></span>
                              </label>
                            </div>
                            </td>
                            <td class='col-lg-2' style='vertical-align:middle;'><img src='$gambar' onclick = imageClicked(this); alt='$nama_slider' class='materialboxed' style='width:100px;height:100px;'></img></td>
                          <td class='col-lg-2' style='vertical-align:middle;'>$nama</td>
                          <td class='col-lg-2 text-center' style='vertical-align:middle;'>$status</td>
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
                <input type='checkbox' name='slider_toogle' id='slider_toogle' onclick=checkSemua($nomorCB,'slider_cb','slider_toogle','slider_jmlcek',this)>
                <span></span>
              </label>
            </div>
            </th>
            <th class='col-lg-2'>Gambar</th>
            <th class='col-lg-8'>Nama</th>
            <th class='col-lg-1 text-center'>Publish</th>
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
      <input type='hidden' name='slider_jmlcek' id='slider_jmlcek' value='0'>";
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
                                <li id='nama' onclick=sortData(this);><a href='#' style='width: 100%;' >Nama</a></li>
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
       clearDirectory("temp/".$_SESSION['username']);
        ?>
        <script>
        var url = "http://"+window.location.hostname+"/api.php?page=slider";
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
        <script src="js/slider.js"></script>
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
                  $("#pageTitle").text("SLIDER");
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
                            <form id='formSlider' name="formSlider" action="#">
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
                ?>
                <link rel="stylesheet" type="text/css" href="js/ImageResizeCropCanvas/css/component.css" />
                <link rel="stylesheet" type="text/css" href="js/ImageResizeCropCanvas/css/demo.css" />
            		<script src="js/ImageResizeCropCanvas/js/component.js"></script>
                <div id="content">
          				<section>
          					<div class="section-body contain-lg">
                      <form class="form" id='formSlider'>
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
      														<label for="statusPublish">PUBLISH</label>
      													</div>
      												</div>
      												<div class="col-sm-10">
      													<div class="form-group">
      														<input type="text" class="form-control" id="namaSlider" name='namaSlider'>
      														<label for="namaSlider">Nama Slider</label>
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
                                  <img class="resize-image" id='gambarSlider' alt="image for resizing">
                                </div>
      												</div>
      											</div>
      											<div class="row">
      												<div class="col-sm-4">
                                <span class="btn ink-reaction btn-raised btn-primary">
                                  <span class="fileinput-exists" onclick='$("#imageSlider").click();'>Pilih Gambar</span>
                                  <input type="hidden" id='statusKosong' name='statusKosong'>
                                  <input style="display:none;" type="file" accept='image/x-png,image/gif,image/jpeg' onchange="imageChanged();" id='imageSlider' name="imageSlider">
                                </span>
      												</div>
      											</div>
                          </div>
                          <div class="card-actionbar">
      											<div class="card-actionbar-row">
                              <button type="button" class="btn ink-reaction btn-raised btn-primary" onclick="saveSlider();">Simpan</button>
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
                      $('.component').hide();
                      setMenuEdit('baru');
                      $("#pageTitle").text("SLIDER");
                  });
                </script>
                <?php
              }elseif($_GET['action']=='edit'){
                  $getData = sqlArray(sqlQuery("select * from $tableName where id = '".$_GET['idEdit']."'"));
                  ?>
                  <link rel="stylesheet" type="text/css" href="js/ImageResizeCropCanvas/css/component.css" />
                  <link rel="stylesheet" type="text/css" href="js/ImageResizeCropCanvas/css/demo.css" />
              		<script src="js/ImageResizeCropCanvas/js/component.js"></script>
                  <div id="content">
            				<section>
            					<div class="section-body contain-lg">
                        <form class="form" id='formSlider'>
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
        														<label for="statusPublish">PUBLISH</label>
        													</div>
        												</div>
        												<div class="col-sm-10">
        													<div class="form-group">
        														<input type="text" class="form-control" id="namaSlider" name='namaSlider' value="<?php echo $getData['nama'] ?>">
        														<label for="namaSlider">Nama Slider</label>
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
                                    <img class="resize-image" id='gambarSlider' src='<?php echo $getData['gambar'] ?>' alt="image for resizing">
                                  </div>
        												</div>
        											</div>
        											<div class="row">
        												<div class="col-sm-4">
                                  <span class="btn ink-reaction btn-raised btn-primary">
                                    <span class="fileinput-exists" onclick='$("#imageSlider").click();'>Pilih Gambar</span>
                                    <input type="hidden" id='statusKosong' name='statusKosong' value="1">
                                    <input style="display:none;" type="file" accept='image/x-png,image/gif,image/jpeg' onchange="imageChanged();" id='imageSlider' name="imageSlider">
                                  </span>
        												</div>
        											</div>
                            </div>
                            <div class="card-actionbar">
        											<div class="card-actionbar-row">
                                <button type="button" class="btn ink-reaction btn-raised btn-primary" onclick="saveEditSlider(<?php echo $_GET['idEdit'] ?>);">Simpan</button>
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
                        resizeableImage($('#gambarSlider'));
                        setMenuEdit('baru');
                        $("#pageTitle").text("SLIDER");
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
