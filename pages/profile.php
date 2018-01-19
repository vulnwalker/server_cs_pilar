<?php
$tipe = @$_GET['tipe'];
$cek = "";
$err = "";
$content = "";

if(!empty($tipe)){
  include "../include/config.php";
  foreach ($_POST as $key => $value) {
      $$key = $value;
  }
}



switch($tipe){

    case 'saveSetting':{

      if(empty($err)){
        $dataInformasiBackground = array(
                'option_value' => $informasiBackground
        );
        sqlQuery(sqlUpdate("general_setting",$dataInformasiBackground,"option_name = 'informasi_background'"));
        $dataProdukBackground = array(
                'option_value' => $produkBackground
        );
        sqlQuery(sqlUpdate("general_setting",$dataProdukBackground,"option_name = 'produk_background'"));
        $dataAcaraBackground = array(
                'option_value' => $acaraBackground
        );
        sqlQuery(sqlUpdate("general_setting",$dataAcaraBackground,"option_name = 'acara_background'"));
        $dataSlider = array(
                'option_value' => $sliderBackground
        );
        sqlQuery(sqlUpdate("general_setting",$dataSlider,"option_name = 'background_slider'"));
        $dataTentang = array(
                'option_value' => $tentangBackground
        );
        sqlQuery(sqlUpdate("general_setting",$dataTentang,"option_name = 'background_tentang'"));
        $dataLowongan = array(
                'option_value' => $lowonganBackground
        );
        sqlQuery(sqlUpdate("general_setting",$dataLowongan,"option_name = 'background_lowongan'"));
        $dataPopularTitleColor = array(
                'option_value' => $popularTitleColor
        );
        sqlQuery(sqlUpdate("general_setting",$dataPopularTitleColor,"option_name = 'title_popular_color'"));
        $dataPopularDeskripsiColor = array(
                'option_value' => $popularDeskripsiColor
        );
        sqlQuery(sqlUpdate("general_setting",$dataPopularDeskripsiColor,"option_name = 'deskripsi_popular_color'"));
        $dataEffectSlider = array(
                'option_value' => $effectSlider
        );
        sqlQuery(sqlUpdate("general_setting",$dataEffectSlider,"option_name = 'effect_slider'"));

        $dataKontak = array(
                                'nama_perusahaan' => $namaPerusahaan,
                                'alamat' => $alamatPerusahaan,
                                'telepon' => $teleponPerusahaan,
                                'email' => $emailPerusahaan,
                                'tentang' => $tentang,
                                'media_sosial' => json_encode(array(
                                                                'facebook' => $facebookPerusahaan,
                                                                'twiter' => $twiterPerusahaan,
                                                                'instagram' => $instagramPerusahaan,
                                                                'googlePlus' => $googlePlus,
                                                                'linkedIn' => $linkedInPerusahaan,
                                                                'whatsapp' => $waPerusahaan,
                                                              )),
                            );
        sqlQuery(sqlUpdate("kontak_web",$dataKontak,"1=1"));


      }


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
                          <input type='text' class='form-control' id='searchData' name='searchData' onkeyup=limitData();>
                          <label for='searchData'>Search</label>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
                <div class='col-xs-3 col-sm-3 col-md-3 col-lg-3'>
                <form class='form' role='form'>
                    <div class='form-group floating-label' style='padding-top: 0px;'>
                      <div class='input-group'>
                        <div class='input-group-content'>
                          <input type='text' onkeypress='return event.charCode >= 48 && event.charCode <= 57' class='form-control ' id='jumlahDataPerhalaman' name='jumlahDataPerhalaman' value = '50' onkeyup=limitData();>
                          <label for='username10'>Data / Halaman</label>
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
     default:{
        $getDataKontak = sqlArray(sqlQuery("select * from kontak_web"));
        ?>
        <script>
        var url = "http://"+window.location.hostname+"/api.php?page=setting";
        </script>
        <script src="js/setting.js"></script>
        <script src="js/jquery.js"></script>
        <!-- BEGIN CONTENT-->
      <div id="content">
          <div class="section-body contain-lg">

            <!-- BEGIN BASIC VALIDATION -->
            <div class="row">
              <div class="col-md-12">
                <form class="form form-validate floating-label" novalidate="novalidate">
                  <div class="card">
                    <div class="card-body">
                      <div class="form-group">
                        <input type="text" class="form-control" id="username" name="username" required data-rule-minlength="2">
                        <label for="username">Username</label>
                      </div>
                      <div class="form-group">
                        <input type="password" class="form-control" id="Password1" name="Password1" required data-rule-minlength="5">
                        <label for="Password1">Password Sebelumnya</label>
                      </div>
                      <div class="form-group">
                        <input type="password" class="form-control" id="Password2" name="Password2" required data-rule-minlength="5">
                        <label for="Password2">Password Sekarang</label>
                      </div>
                      <div class="form-group">
                        <input type="password" class="form-control" id="Password3" name="Password3" required data-rule-minlength="5">
                        <label for="Password3">Confirmation Password</label>
                      </div>
                    </div><!--end .card-body -->
                    <div class="card-actionbar">
                      <div class="card-actionbar-row">
                        <button type="submit" class="btn btn-primary ink-reaction btn-raised">Simpan</button>
                        <button type="submit" class="btn btn-danger ink-reaction btn-raised">Batal</button>
                      </div>
                    </div><!--end .card-actionbar -->
                  </div><!--end .card -->
                </form>
              </div><!--end .col -->
            </div><!--end .row -->
            <!-- END BASIC VALIDATION -->

          </div><!--end .section-body -->
        </section>
      </div><!--end #content-->
      <!-- END CONTENT -->
        <script type="text/javascript">
          $(document).ready(function() {
              setMenuEdit('baru');
              $("#pageTitle").text("PROFILE");
          });
        </script>
<?php

     break;
     }

}

?>
