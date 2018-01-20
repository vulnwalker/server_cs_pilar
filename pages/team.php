<?php
$tipe = @$_GET['tipe'];
$cek = "";
$err = "";
$content = "";
$tableName = "team";
if(!empty($tipe)){
  include "../include/config.php";
  foreach ($_POST as $key => $value) {
      $$key = $value;
  }
}


switch($tipe){

    case 'saveTeam':{
      if(empty($namaLengkap)){
          $err = "Isi nama lengkap";
      }elseif(empty($posisiTeam)){
          $err = "Pilih jabatan";
      }elseif(empty($tempatLahir)){
          $err = "Isi tempat lahir";
      }elseif(empty($tanggalLahir)){
          $err = "Isi tanggal lahir ";
      }elseif(empty($tentang)){
          $err = "Isi tentang ";
      }elseif(empty($statusPublish)){
          $err = "Pilih status publish";
      }

      if(empty($err)){
        $arraySosmed = array(
              'facebook'=> $facebook,
              'twiter'=> $twiter,
              'instagram'=> $instagram,
              'linkedIn'=> $linkedIn,
              'googlePlus'=> $googlePlus,
        );
        baseToImage($baseFotoTeam,"images/team/$namaLengkap.jpg");
          $data = array(
                'nama' => $namaLengkap,
                'tempat_lahir' => $tempatLahir,
                'tanggal_lahir' => generateDate($tanggalLahir),
                'posisi' => $posisiTeam,
                'foto' => "images/team/$namaLengkap.jpg",
                'tentang' =>  base64_encode($tentang),
                'sosial_media' =>  json_encode($arraySosmed),
                'publish' => $statusPublish,
          );
          $query = sqlInsert($tableName,$data);
          sqlQuery($query);
          $cek = $query;

      }
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'saveEditTeam':{
      if(empty($namaLengkap)){
          $err = "Isi nama lengkap";
      }elseif(empty($posisiTeam)){
          $err = "Pilih jabatan";
      }elseif(empty($tempatLahir)){
          $err = "Isi tempat lahir";
      }elseif(empty($tanggalLahir)){
          $err = "Isi tanggal lahir ";
      }elseif(empty($tentang)){
          $err = "Isi tentang ";
      }elseif(empty($statusPublish)){
          $err = "Pilih status publish";
      }

      if(empty($err)){
        $arraySosmed = array(
              'facebook'=> $facebook,
              'twiter'=> $twiter,
              'instagram'=> $instagram,
              'linkedIn'=> $linkedIn,
              'googlePlus'=> $googlePlus,
        );
        baseToImage($baseFotoTeam,"images/team/$namaLengkap.jpg");
          $data = array(
                'nama' => $namaLengkap,
                'tempat_lahir' => $tempatLahir,
                'tanggal_lahir' => generateDate($tanggalLahir),
                'posisi' => $posisiTeam,
                'foto' => "images/team/$namaLengkap.jpg",
                'tentang' =>  base64_encode($tentang),
                'sosial_media' =>  json_encode($arraySosmed),
                'publish' => $statusPublish,
          );
          $query = sqlUpdate($tableName,$data,"id = '$idEdit'");
          sqlQuery($query);
          $cek = $query;

      }
      $content = array("judulTeam" => $judulTeam);

      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'Hapus':{
      for ($i=0; $i < sizeof($team_cb) ; $i++) {
        $query = "delete from $tableName where id = '".$team_cb[$i]."'";
        sqlQuery($query);
      }

      $cek = $query;
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'Edit':{

      $content = array("idEdit" => $team_cb[0]);
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'loadTable':{
      if(!empty($searchData)){
        $getColom = sqlQuery("desc $tableName");
        while ($dataColomn = sqlArray($getColom)) {

        }
        $arrKondisi[] = "nama like '%$searchData%' ";
        $arrKondisi[] = "posisi like '%$searchData%' ";
        $arrKondisi[] = "tanggal_lahir like '%$searchData%' ";
        $arrKondisi[] = "foto like '%$searchData%' ";
        $arrKondisi[] = "tempat_lahir like '%$searchData%' ";
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
      while($dataTeam = sqlArray($getData)){
        foreach ($dataTeam as $key => $value) {
            $$key = $value;
        }
        $getJabatan = sqlArray(sqlQuery("select * from ref_posisi where id = '$posisi'"));
        $jabatan = $getJabatan['posisi'];
        if($publish == '1'){
            $statusPublish = "YA";
        }else{
            $statusPublish = "TIDAK";
        }
        $data .= "     <tr>
                          <td class='text-center'>$nomor</td>
                          <td class='text-center'>
                            <div  class='checkbox checkbox-inline checkbox-styled'>
                                    <label>
                                    ".setCekBox($nomorCB,$id,'','team')."
                                <span></span>
															</label>
														</div>
                            </td>
                          <td>$nama</td>
                          <td>$jabatan</td>
                          <td>$tempat_lahir</td>
                          <td style='text-align:center;'>$statusPublish</td>
                          <td>".generateDate($tanggal_lahir)."</td>
                          <td class='text-center'><img src='$foto' onclick = imageClicked(this); alt='$nama' class='materialboxed' style='width:100px;height:100px;cursor:pointer;'></img></td>
                       </tr>
                    ";
          $nomor += 1;
          $nomorCB += 1;
      }

      $tabelBody = "

        <thead>
          <tr>
            <th class='text-center'>No</th>
            <th class='text-center'>
             <div class='checkbox checkbox-inline checkbox-styled' >
              <label>
                <input type='checkbox' name='team_toogle' id='team_toogle' onclick=checkSemua($nomorCB,'team_cb','team_toogle','team_jmlcek',this)>
              <span></span>
            </label>
          </div>
            </th>
            <th class='col-lg-5'>Nama</th>
            <th class='col-lg-2'>Jabatan</th>
            <th class='col-lg-2'>Tempat Lahir</th>
            <th class='col-lg-2'>Tanggal Lahir</th>
            <th class='col-lg-1 text-center'>Publish</th>
            <th class='col-lg-1 text-center'>Foto</th>
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
      <input type='hidden' name='team_jmlcek' id='team_jmlcek' value='0'>";
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
                                <li id='posisi' onclick=sortData(this);><a href='#' style='width: 100%;' >Jabatan</a></li>
                                <li id='tempat_lahir' onclick=sortData(this);><a href='#' style='width: 100%;' >Tempat Lahir</a></li>
                                <li id='tanggal_lahir' onclick=sortData(this);><a href='#' style='width: 100%;' >Tanggal Lahir</a></li>
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

     default:{
        ?>
        <script>
        var url = "http://"+window.location.hostname+"/api.php?page=team";
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
        <script src="js/team.js"></script>
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
                  $("#pageTitle").text("TEAM");
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
                            <form id='formTeam' name="formTeam" action="#">
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
                <link rel="stylesheet" type="text/css" href="js/ImageResizeCropCanvas/css/componentTeam.css" />
                <link rel="stylesheet" type="text/css" href="js/ImageResizeCropCanvas/css/demoTeam.css" />
                <script src="js/ImageResizeCropCanvas/js/componentTeam.js"></script>
                <script type="text/javascript">
                  $(document).ready(function() {
                      $(".component").hide();
                      setMenuEdit('baru');
                      $("#pageTitle").text("TEAM");
                      $("#tanggalLahir").inputmask('dd-mm-yyyy', {placeholder: ''});
                  });
                </script>
                <div id="content">
          				<section>
          					<div class="section-body contain-lg">
                      <form class="form" id='formProduk'>
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
                              <div class="col-sm-9">
                                <div class="form-group">
                                  <input type="text" class="form-control" id="namaLengkap" name='namaLengkap'>
                                  <label for="namaLengkap">Nama Lengkap</label>
                                </div>
                              </div>
      												<div class="col-sm-2">
      													<div class="form-group">
                                  <?php
                                    echo cmbQuery("posisiTeam","1","select * from ref_posisi","class='form-control' ","-- JABATAN --")
                                  ?>
      														<label for="Firstname2">Jabatan</label>
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
                                  <img class="resize-image" id='fotoTeam' alt="image for resizing">
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
      											<div class="row">
      												<div class="col-sm-2">
                                <div class="form-group">
      														<input type="text" class="form-control" id="tempatLahir" name='tempatLahir'>
      														<label for="tempatLahir">Tempat Lahir</label>
      													</div>
      												</div>
                              <div class="col-sm-1">
                                <div class="form-group">
          												<input type="text" id='tanggalLahir' name='tanggalLahir' class="form-control" data-inputmask="'alias': 'date'">
          												<label>Tanggal Lahir</label>
          											</div>
      												</div>
                              <div class="col-sm-2">
                                <div class="form-group">
          												<input type="text" id='googlePlus' name='googlePlus' class="form-control" >
          												<label>Google Plus</label>
          											</div>
      												</div>
                              <div class="col-sm-2">
                                <div class="form-group">
          												<input type="text" id='twiter' name='twiter' class="form-control" >
          												<label>Twiter</label>
          											</div>
      												</div>
                              <div class="col-sm-2">
                                <div class="form-group">
          												<input type="text" id='instagram' name='instagram' class="form-control" >
          												<label>Instagram</label>
          											</div>
      												</div>

                              <div class="col-sm-2">
                                <div class="form-group">
          												<input type="text" id='linkedIn' name='linkedIn' class="form-control" >
          												<label>Linked In</label>
          											</div>
      												</div>
                              <div class="col-sm-1">
                                <div class="form-group">
          												<input type="text" id='faceBook' name='faceBook' class="form-control" >
          												<label>Facebook</label>
          											</div>
      												</div>
      											</div>
                            <div class="row">
                              <div class="col-sm-12">
                                <div class="form-group">
                                  <textarea name="tentang" id="tentang" class="form-control" rows="8" placeholder=""></textarea>
                                  <label>Tentang</label>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="card-actionbar">
      											<div class="card-actionbar-row">
                              <button type="button" class="btn ink-reaction btn-raised btn-primary" onclick="saveTeam();">Simpan</button>
                              <button type="button" class="btn ink-reaction btn-raised btn-danger" onclick="refreshList();">batal</button>
      											</div>
      										</div>
      										</div>
      									</div>
      								</form>
      							</div>
          				</section>
          			</div>
                <?php
              }elseif($_GET['action']=='edit'){
                  $getData = sqlArray(sqlQuery("select * from $tableName where id = '".$_GET['idEdit']."'"));
                  $dataSosmed = json_decode($getData['sosial_media']);
                  $facebook = $dataSosmed->facebook;
                  $twiter = $dataSosmed->twiter;
                  $instagram = $dataSosmed->instagram;
                  $linkedIn = $dataSosmed->linkedIn;
                  $googlePlus = $dataSosmed->googlePlus;
                  ?>
                  <link rel="stylesheet" type="text/css" href="js/ImageResizeCropCanvas/css/componentTeam.css" />
                  <link rel="stylesheet" type="text/css" href="js/ImageResizeCropCanvas/css/demoTeam.css" />
                  <script src="js/ImageResizeCropCanvas/js/componentTeam.js"></script>
                  <script type="text/javascript">
                    $(document).ready(function() {
                        resizeableImage($('#fotoTeam'));
                        setMenuEdit('baru');
                        $("#pageTitle").text("TEAM");
                        $("#tanggalLahir").inputmask('dd-mm-yyyy', {placeholder: ''});
                    });
                  </script>
                  <div id="content">
            				<section>
            					<div class="section-body contain-lg">
                        <form class="form" id='formProduk'>
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
                                <div class="col-sm-9">
                                  <div class="form-group">
                                    <input type="text" class="form-control" id="namaLengkap" name='namaLengkap' value="<?php echo $getData['nama'] ?>">
                                    <label for="namaLengkap">Nama Lengkap</label>
                                  </div>
                                </div>
        												<div class="col-sm-2">
        													<div class="form-group">
                                    <?php
                                      echo cmbQuery("posisiTeam",$getData['posisi'],"select * from ref_posisi","class='form-control' ","-- JABATAN --")
                                    ?>
        														<label for="Firstname2">Jabatan</label>
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
                                    <img class="resize-image" id='fotoTeam' src="<?php echo $getData['foto'] ?>" alt="image for resizing">
                                  </div>
        												</div>
        											</div>
        											<div class="row">
        												<div class="col-sm-4">
                                  <span class="btn ink-reaction btn-raised btn-primary">
                                    <span class="fileinput-exists" onclick='$("#imageProduk").click();'>Pilih Gambar</span>
                                    <input type="hidden" id='statusKosong' name='statusKosong' value='1'>
                                    <input style="display:none;" type="file" accept='image/x-png,image/gif,image/jpeg' onchange="imageChanged();" id='imageProduk' name="imageProduk">
                                  </span>
        												</div>
        											</div>
        											<div class="row">
        												<div class="col-sm-2">
                                  <div class="form-group">
        														<input type="text" class="form-control" id="tempatLahir" name='tempatLahir' value="<?php echo $getData['tempat_lahir'] ?>">
        														<label for="tempatLahir">Tempat Lahir</label>
        													</div>
        												</div>
                                <div class="col-sm-1">
                                  <div class="form-group">
            												<input type="text" id='tanggalLahir' name='tanggalLahir' class="form-control" data-inputmask="'alias': 'date'" value="<?php echo generateDate($getData['tanggal_lahir']) ?>">
            												<label>Tanggal Lahir</label>
            											</div>
        												</div>
                                <div class="col-sm-2">
                                  <div class="form-group">
            												<input type="text" id='googlePlus' name='googlePlus' class="form-control" value="<?php echo $googlePlus ?>" >
            												<label>Google Plus</label>
            											</div>
        												</div>
                                <div class="col-sm-2">
                                  <div class="form-group">
            												<input type="text" id='twiter' name='twiter' class="form-control" value="<?php echo $twiter ?>" >
            												<label>Twiter</label>
            											</div>
        												</div>
                                <div class="col-sm-2">
                                  <div class="form-group">
            												<input type="text" id='instagram' name='instagram' class="form-control" value="<?php echo $instagram ?>" >
            												<label>Instagram</label>
            											</div>
        												</div>

                                <div class="col-sm-2">
                                  <div class="form-group">
            												<input type="text" id='linkedIn' name='linkedIn' class="form-control" value="<?php echo $linkedIn ?>" >
            												<label>Linked In</label>
            											</div>
        												</div>
                                <div class="col-sm-1">
                                  <div class="form-group">
            												<input type="text" id='faceBook' name='faceBook' class="form-control" value="<?php echo $facebook ?>" >
            												<label>Facebook</label>
            											</div>
        												</div>
        											</div>
                              <div class="row">
                                <div class="col-sm-12">
                                  <div class="form-group">
                                    <textarea name="tentang" id="tentang" class="form-control" rows="8" placeholder=""><?php  echo base64_decode($getData['tentang']) ?></textarea>
                                    <label>Tentang</label>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="card-actionbar">
        											<div class="card-actionbar-row">
                                <button type="button" class="btn ink-reaction btn-raised btn-primary" onclick="saveEditTeam(<?php echo $_GET['idEdit'] ?>);">Simpan</button>
                                <button type="button" class="btn ink-reaction btn-raised btn-danger" onclick="refreshList();">batal</button>
        											</div>
        										</div>
        										</div>
        									</div>
        								</form>
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
