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
        <div id="content">
          <section>
            <div class="section-body contain-lg">
              <form class="form" id='formInformasi'>
                <div class="card">
                  <div class="card-body floating-label">
                    <div class="row">
                      <div class="col-sm-4">
                        <div class="form-group">
                          <?php
                            $getInformasiBackground = sqlArray(sqlQuery("select * from general_setting where option_name = 'informasi_background'"));
                          ?>
                          <input type="color" id='informasiBackground' class='form-control' value='<?php echo $getInformasiBackground['option_value'] ?>' >
                          <label>Informasi Background</label>
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group">
                          <?php
                            $getProdukBackground = sqlArray(sqlQuery("select * from general_setting where option_name = 'produk_background'"));
                          ?>
                          <input type="color" id='produkBackground' class='form-control' value='<?php echo $getProdukBackground['option_value'] ?>' >
                          <label>Produk Background</label>
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group">
                          <?php
                            $getAcaraBackground = sqlArray(sqlQuery("select * from general_setting where option_name = 'acara_background'"));
                          ?>
                          <input type="color" id='acaraBackground' class='form-control' value='<?php echo $getAcaraBackground['option_value'] ?>' >
                          <label>Acara Background</label>
                        </div>
                      </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-4">
                      <div class="form-group">
                        <?php
                          $getSliderBackground = sqlArray(sqlQuery("select * from general_setting where option_name = 'background_slider'"));
                        ?>
                        <input type="color" id='sliderBackground' class='form-control' value='<?php echo $getSliderBackground['option_value'] ?>' >
                        <label>Slider Background</label>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <?php
                          $getTentangBackground = sqlArray(sqlQuery("select * from general_setting where option_name = 'background_tentang'"));
                        ?>
                        <input type="color" id='tentangBackground' class='form-control' value='<?php echo $getTentangBackground['option_value'] ?>' >
                        <label>Tentang Background</label>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <?php
                          $getLowonganBackground = sqlArray(sqlQuery("select * from general_setting where option_name = 'background_lowongan'"));
                        ?>
                        <input type="color" id='lowonganBackground' class='form-control' value='<?php echo $getLowonganBackground['option_value'] ?>' >
                        <label>Lowongan Background</label>
                      </div>
                    </div>
                </div>
                  <div class="row">
                    <div class="col-sm-4">
                      <div class="form-group">
                        <?php
                          $getPopularTitleColor = sqlArray(sqlQuery("select * from general_setting where option_name = 'title_popular_color'"));
                        ?>
                        <input type="color" id='popularTitleColor' class='form-control' value='<?php echo $getPopularTitleColor['option_value'] ?>' >
                        <label>Warna Title Popular</label>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <?php
                          $getPopularDeskripsiColor = sqlArray(sqlQuery("select * from general_setting where option_name = 'deskripsi_popular_color'"));
                        ?>
                        <input type="color" id='popularDeskripsiColor' class='form-control' value='<?php echo $getPopularDeskripsiColor['option_value'] ?>' >
                        <label>Warna Deskripsi Popular</label>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <?php
                          $arrayEffect = array(
                                    array('1','PARTICKELS'),
                                    array('2','SQUARE'),
                                    array('3','SNOW'),
                                    array('4','STARS'),
                                    array('5','BOKEH'),
                          );
                          $getEffectSlider = sqlArray(sqlQuery("select * from general_setting where option_name = 'effect_slider' "));
                          echo cmbArray("effectSlider",$getEffectSlider['option_value'],$arrayEffect,"- EFFECT SLIDER -","class='form-control'")
                        ?>
                        <label>Slider Effect</label>
                      </div>
                    </div>
                </div>
                  <div class="row">
                    <div class="col-sm-2">
                      <div class="form-group">
                        <input type="text" id='namaPerusahaan' class='form-control' value='<?php echo $getDataKontak['nama_perusahaan'] ?>' >
                        <label>Nama Perusahaan</label>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <input type="text" id='alamatPerusahaan' class='form-control' value='<?php echo $getDataKontak['alamat'] ?>' >
                        <label>Alamat Perusahaan</label>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="form-group">
                        <input type="text" id='emailPerusahaan' class='form-control' value='<?php echo $getDataKontak['email'] ?>' >
                        <label>Email</label>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="form-group">
                        <input type="text" id='teleponPerusahaan' class='form-control' value='<?php echo $getDataKontak['telepon'] ?>' >
                        <label>Telepon</label>
                      </div>
                    </div>
                </div>
                  <div class="row">
                    <?php
                        $dataSosmed = json_decode($getDataKontak['media_sosial']);
                        $facebookPerusahaan = $dataSosmed->facebook;
                        $twiterPerusahaan = $dataSosmed->twiter;
                        $instagramPerusahaan = $dataSosmed->instagram;
                        $googlePlus = $dataSosmed->googlePlus;
                        $waPerusahaan = $dataSosmed->whatsapp;
                        $linkedInPerusahaan = $dataSosmed->linkedIn;
                     ?>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <input type="text" id='facebookPerusahaan' class='form-control' value='<?php echo $facebookPerusahaan ?>' >
                        <label>Facebook</label>
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <div class="form-group">
                        <input type="text" id='twiterPerusahaan' class='form-control' value='<?php echo $twiterPerusahaan ?>' >
                        <label>Twiter</label>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <input type="text" id='instagramPerusahaan' class='form-control' value='<?php echo $instagramPerusahaan ?>' >
                        <label>Instagram</label>
                      </div>
                    </div>
                </div>
                  <div class="row">
                    <div class="col-sm-4">
                      <div class="form-group">
                        <input type="text" id='googlePlus' class='form-control' value='<?php echo $googlePlus ?>' >
                        <label>Google Plus</label>
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <div class="form-group">
                        <input type="text" id='waPerusahaan' class='form-control' value='<?php echo $waPerusahaan ?>' >
                        <label>WhatsApp</label>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <input type="text" id='linkedInPerusahaan' class='form-control' value='<?php echo $linkedInPerusahaan ?>' >
                        <label>Linked In</label>
                      </div>
                    </div>
                </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <textarea  id='tentang' class='form-control' row='4' ><?php echo $getDataKontak['tentang'] ?></textarea>
                        <label>Tentang</label>
                      </div>
                    </div>

                </div>



                  <div class="card-actionbar">
                    <div class="card-actionbar-row">
                      <button type="button" class="btn ink-reaction btn-raised btn-primary" onclick="saveSetting();">Simpan</button>
                      <button type="button" class="btn ink-reaction btn-raised btn-danger" onclick="refreshList();">batal</button>
                    </div>
                  </div>
                  </div><!--end .card-body -->

                </div><!--end .card -->
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
              </form>
            </div>
          </section>
        </div>
        <script type="text/javascript">
          $(document).ready(function() {
              setMenuEdit('baru');
              $("#pageTitle").text("SETTING");
          });
        </script>
<?php

     break;
     }

}

?>
