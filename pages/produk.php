<?php
$tipe = @$_GET['tipe'];
$cek = "";
$err = "";
$content = "";
session_start();

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
    case 'showGambarProduk':{
      $getNamaImage = sqlArray(sqlQuery("SELECT * from produk where id = '$idproduk' "));
      $decodedJSON = json_decode($getNamaImage[screen_shot]);
      for ($i=0; $i < sizeof($decodedJSON) ; $i++) {
          $explodeNamaGambar = explode('/',$decodedJSON[$i]->fileName);
          $jsonScreenshot[] = array(
                    'name' => $explodeNamaGambar[3],
                    'type' => 'image/jpeg',
                    'imageLocation' => "temp/".$_SESSION['username']."/".$explodeNamaGambar[3],
          );
          if ($number == "") {
            $listImage .="
                <div class='item active'>
                  <img src='".$decodedJSON[$i]->fileName."' alt='Awesome Image'>
                  <div class='carousel-caption'>
                  </div>
                  <h5>".$decodedJSON[$i]->desc."</h5>
                </div>

          ";
          }else{
            $listImage .="
                <div class='item'>
                  <img src='".$decodedJSON[$i]->fileName."' alt='Awesome Image'>
                  <div class='carousel-caption'>
                  </div>
                  <h5>".$decodedJSON[$i]->desc."</h5>
                </div>
                
          ";
          }
        $number = "1";
          
      }
      $imagesProduks = "
        <!-- Carousel Card -->
        <div class='card card-raised card-carousel'>
          <div id='carousel-example-generic' class='carousel slide' data-ride='carousel'>
            <div class='carousel slide' data-ride='carousel'>

              <!-- Indicators -->

              <!-- <ol class='carousel-indicators'>
                <li data-target='#carousel-example-generic' data-slide-to='0' class='active'></li>
                <li data-target='#carousel-example-generic' data-slide-to='1'></li>
                <li data-target='#carousel-example-generic' data-slide-to='2'></li>
              </ol> -->

              <!-- Wrapper for slides -->
              <div class='carousel-inner'>
                
                ".$listImage."
                <!-- <div class='item'>
                  <img src='images/produk/ATISISBADA/b3c18adb84f2548b04467090a673c529.jpg' alt='Awesome Image'>
                  <div class='carousel-caption'>
                  </div>
                </div>
                <div class='item'>
                  <img src='images/produk/ATISISBADA/e8c6d95650a17cd8530834a8ce5ab45a.jpg' alt='Awesome Image'>
                  <div class='carousel-caption'>
                  </div>
                </div> -->

              </div>

              <!-- Controls -->
              <a class='left carousel-control' href='#carousel-example-generic' data-slide='prev' style='background: linear-gradient(to right, #0a0a0a45 , #0a0a0a00);'>
                <i class='material-icons'><!-- keyboard_arrow_left --></i>
              </a>
              <a class='right carousel-control' href='#carousel-example-generic' data-slide='next' style='background: linear-gradient(to right, #08080800 , #0a0a0a45);'>
                <i class='material-icons'><!-- keyboard_arrow_right --></i>
              </a>
            </div>
          </div>
        </div>
        <!-- End Carousel Card -->
      ";
      $content = array("imagesProduks" => $imagesProduks);

      echo generateAPI($cek,$err,$content);
      break;
}
    case 'saveProduk':{
      if(empty($namaProduk)){
          $err = "Isi nama produk";
      }elseif(empty($statusPublish)){
          $err = "Pilih status publish";
      }elseif(empty($gambarProduk)){
          $err = "Pilih gambar Produk";
      }
      if(empty($err)){

        // $listImage = getImage("../temp","../images/produk/$namaProduk");
        // $imageTitle = baseToImage($gambarProduk,"../images/produk/$namaProduk/title.jpg");
        $listImage = getImage("temp/".$_SESSION['username'],"images/produk/$namaProduk");
        $imageTitle = baseToImage($gambarProduk,"images/produk/$namaProduk/title.jpg");
        $data = array(
                'nama_produk' => $namaProduk,
                'status' => $statusPublish,
                'tanggal' =>  date("Y-m-d"),
                'image_title' => "images/produk/$namaProduk/title.jpg",
                'deskripsi' => $deskripsiProduk,
                'screen_shot' => $listImage,
        );
        $query = sqlInsert("produk",$data);
        sqlQuery($query);
        $cek = $query;

      }
      $content = array("judulProduk" => $judulProduk);

      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'saveEditProduk':{
      if(empty($namaProduk)){
          $err = "Isi nama produk";
      }elseif(empty($statusPublish)){
          $err = "Pilih status publish";
      }elseif(empty($gambarProduk)){
          $err = "Pilih gambar Produk";
      }
      if(empty($err)){
        $getOldProduk = sqlArray(sqlQuery("select * from produk where id = '$idEdit'"));
        // $files = glob("images/produk/".$getOldProduk['nama_produk']."/*"); // get all file names
        //   foreach($files as $file){ // iterate files
        //     if(is_file($file))
        //       unlink($file); // delete file
        //   }
        unlinkDir("images/produk/".$getOldProduk['nama_produk']);
        $listImage = getImage("temp/".$_SESSION['username'],"images/produk/$namaProduk");
        $imageTitle = baseToImage($gambarProduk,"images/produk/$namaProduk/title.jpg");
        $data = array(
                'nama_produk' => $namaProduk,
                'status' => $statusPublish,
                'tanggal' =>  date("Y-m-d"),
                'image_title' => "images/produk/$namaProduk/title.jpg",
                'deskripsi' => $deskripsiProduk,
                'screen_shot' => $listImage,
        );
        $query = sqlUpdate("produk",$data,"id = '$idEdit'");
        sqlQuery($query);
        $cek = $query;
      }
      $content = array("judulProduk" => $judulProduk);

      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'deleteProduk':{
      $getData = sqlArray(sqlQuery("select * from produk where id = '$id'"));
      unlinkDir("images/produk/".$getData['nama_produk']);
      $query = "delete from produk where id = '$id'";
      sqlQuery($query);
      $cek = $query;
      echo generateAPI($cek,$err,$content);
    break;
    }
    case 'removeTemp':{
      unlink('temp/'.$_SESSION['username']."/".$id);
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'updateProduk':{
      clearDirectory("temp/".$_SESSION['username']);
      $getData = sqlArray(sqlQuery("select * from produk where id = '$id'"));
      $decodedJSON = json_decode($getData['screen_shot']);
      for ($i=0; $i < sizeof($decodedJSON) ; $i++) {
          $explodeNamaGambar = explode('/',$decodedJSON[$i]->fileName);
          copy($decodedJSON[$i]->fileName,"temp/".$_SESSION['username']."/".$explodeNamaGambar[3]);
          createDescFile($explodeNamaGambar[3],$decodedJSON[$i]->desc);
          $jsonScreenshot[] = array(
                    'name' => $explodeNamaGambar[3],
                    'type' => 'image/jpeg',
                    'imageLocation' => "temp/".$_SESSION['username']."/".$explodeNamaGambar[3],
          );;
      }



      $type = pathinfo($getData['image_title'], PATHINFO_EXTENSION);
      $data = file_get_contents($getData['image_title']);
      //$baseOfFile = 'data:image/' . $type . ';base64,' . base64_encode($data);
      $content = array("namaProduk" => $getData['nama_produk']
                      ,"statusPublish" => $getData['status']
                      , "deskripsi" => $getData['deskripsi']
                      , "baseOfFile" => $baseOfFile
                      ,"screenShot" => json_encode($jsonScreenshot));
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

    case 'loadTable':{
      $getData = sqlQuery("select * from produk");
      while($dataProduk = sqlArray($getData)){
        foreach ($dataProduk as $key => $value) {
            $$key = $value;
        }

        if($status == "1"){
            $status = "PUBLISH";
        }else{
            $status = "NON PUBLISH";
        }
        $data .= "     <tr>
                          <td>$nama_produk</td>
                          <td><img src='$image_title'  class='materialboxed' style='width:100px;height:100px;'></img> </td>
                          <td>".generateDate($tanggal)."</td>
                          <td>$status</td>
                          <td>
                            <!-- <input type='button'  class='waves-effect waves-light btn btn-primary' value='Show'> -->
                            <button class='btn btn-raised btn-round btn-primary' data-toggle='modal' data-target='#noticeModal' onclick=showGambarProduk($id);>
                                show
                            </button>
                            <!-- notice modal -->
                                            <div class='modal fade' id='noticeModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
                                                <div class='modal-dialog modal-notice'>
                                                    <div class='modal-content'>
                                                        <div class='modal-header'>
                                                            <button type='button' class='close' data-dismiss='modal' aria-hidden='true'> <!-- <i class='material-icons'>clear</i> --></button>
                                                            <!-- <h5 class='modal-title' id='myModalLabel'>
                                                              How Do You Become an Affiliate?
                                                            </h5> -->
                                                        </div>
                                                        <div class='modal-body'>
                                                            <div class='instruction'>
                                                                <div class='row'>
                                                                    <div class='col-md-12'>
                                                                        
                                                                      <div id='tempatGambar'></div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end notice modal -->
                          </td>
                          <td class='text-right'>
                              <a onclick=updateProduk($id) class='btn btn-simple btn-warning btn-icon edit'><i class='material-icons'>dvr</i></a>
                              <a onclick=deleteProduk($id) class='btn btn-simple btn-danger btn-icon remove'><i class='material-icons'>close</i></a>
                          </td>
                      </tr>
                    ";
      }

      $tabel = "<table id='datatables' class='table table-striped table-no-bordered table-hover' cellspacing='0' width='100%' style='width:100%'>
          <thead>
              <tr>
                  <th>Produk</th>
                  <th>Gambar</th>
                  <th>Tanggal</th>
                  <th>Status</th>
                  <th>Screen Shot</th>
                  <th class='disabled-sorting text-right'>Actions</th>
              </tr>
          </thead>
          <tbody>
            $data
          </tbody>
      </table>";
      $content = array("tabelProduk" => $tabel);

      echo generateAPI($cek,$err,$content);
    break;
    }

     default:{
        ?>
        <script>
        var url = "http://"+window.location.hostname+"/api.php?page=produk";

        </script>
        <script src="js/dropzone/dropzone.js"></script>
        <script src="js/produk.js"></script>
        <link rel="stylesheet" href="js/dropzone/dropzone.css">

        <?php
            if(!isset($_GET['edit'])){
              clearDirectory("temp/".$_SESSION['username']);
              ?>
              <div class="content">
                  <div class="container-fluid">
                      <div class="row">
                          <div class="col-md-12">
                                  <div class="card">
                                      <div class="card-content">
                                          <ul class="nav nav-pills nav-pills-primary">
                                              <li class="active">
                                                  <a href="#dataProduk" id='data1' data-toggle="tab" aria-expanded="true" onclick="clearTemp();">Produk</a>
                                              </li>
                                              <li>
                                                  <a href="#produkBaru" id='data2' data-toggle="tab" aria-expanded="false" onclick="baruProduk();">Baru</a>
                                              </li>
                                          </ul>
                                          <div class="tab-content">
                                              <div class="tab-pane active" id="dataProduk">
                                                  <div class="col-md-12" id='tableInformasi'>
                                                    <div class="card">
                                                        <div class="card-header card-header-icon" data-background-color="purple">
                                                            <i class="material-icons">assignment</i>
                                                        </div>
                                                        <div class="card-content">
                                                            <h4 class="card-title">Data Produk</h4>
                                                            <div class="toolbar">
                                                            </div>
                                                            <div class="material-datatables">
                                                                <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Judul</th>
                                                                            <th>Posisi</th>
                                                                            <th>Tanggal</th>
                                                                            <th>Penulis</th>
                                                                            <th>Status</th>
                                                                            <th class="disabled-sorting text-right">Actions</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                              </div>
                                              <div class="tab-pane" id="produkBaru">
                                                  <div class="row">
                                                    <div class="col-lg-3 col-md-6 col-sm-3">
                                                      <label class="control-label">Status</label>
                                                        <?php
                                                          $arrayStatus = array(
                                                                    array('1','PUBLISH'),
                                                                    array('2','NON PUBLISH'),
                                                          );
                                                          echo cmbArray("statusPublish","1",$arrayStatus,"STATUS","class='selectpicker' data-style='btn btn-primary btn-round' title='Single Select' data-size='7'")
                                                        ?>
                                                    </div>
                                                  </div>
                                                  <div class="row">
                                                    <div class="col-md-4 col-sm-4">
                                                      <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                                                          <div class="fileinput-new thumbnail">
                                                              <img  src="assets/img/image_placeholder.jpg" id='tempImageProduk' alt="...">
                                                          </div>
                                                          <div class="fileinput-preview fileinput-exists thumbnail"></div>
                                                          <div>
                                                              <span class="btn btn-rose btn-round btn-file">
                                                                  <span class="fileinput-new">Select image</span>
                                                                  <span class="fileinput-exists">Change</span>
                                                                  <input type="hidden" id='gambarProduk' name='gambarProduk'>
                                                                  <input type="file" accept='image/x-png,image/gif,image/jpeg' onchange="imageChanged();" id='imageProduk' name="imageProduk">
                                                              </span>
                                                              <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
                                                          </div>
                                                      </div>
                                                    </div>
                                                  </div>
                                                  <div class="row">
                                                    <div class="col-lg-12">
                                                      <form method="#" action="#">
                                                        <div class="form-group label-floating">
                                                            <label class="control-label">Nama Produk</label>
                                                            <input type="text" id="namaProduk" class="form-control">
                                                        </div>
                                                      </form>
                                                    </div>
                                                  </div>
                                                  <div class="row">
                                                    <div class="col-md-12 col-sm-12">
                                                      Screen Shot
                                                        <form action="upload.php" id='dropzone'  >
                                                        </form>
                                                        <input type="file" multiple="multiple"  accept='image/x-png,image/gif,image/jpeg' class="dz-hidden-input" style="visibility: hidden; position: absolute; top: 0px; left: 0px; height: 0px; width: 0px;">
                                                    </div>
                                                  </div>
                                                  <div class="row">
                                                    <div class="card">
                                                        <div class="card-body no-padding">
                                                            <div id="summernote">
                                                            </div>
                                                        </div>
                                                    </div>
                                                  </div>
                                                  <div class="row">
                                                    <div class="col-lg-12">
                                                      <button type="button" class="btn btn-primary" id='buttonSubmit' onclick="saveProduk();" data-dismiss="modal">Simpan</button>
                                                    </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          <div class="col-md-12" id='tableProduk'>
                              <div class="card">
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <?php
            }else{
              $getDataEdit = sqlArray(sqlQuery("select * from produk where id='".$_GET['edit']."'"));

              $type = pathinfo($getDataEdit['image_title'], PATHINFO_EXTENSION);
              $data = file_get_contents($getDataEdit['image_title']);
              $baseOfFile = 'data:image/' . $type . ';base64,' . base64_encode($data);
              ?>
              <div class="content">
                  <div class="container-fluid">
                      <div class="row">
                          <div class="col-md-12">
                                  <div class="card">
                                      <div class="card-content">
                                          <ul class="nav nav-pills nav-pills-primary">
                                              <li >
                                                  <a href="pages.php?page=produk" >Produk</a>
                                              </li>
                                              <li class="active">
                                                  <a >Edit</a>
                                              </li>
                                          </ul>
                                          <div class="tab-content">
                                              <div class="tab-pane active" id="produkBaru">
                                                  <div class="row">
                                                    <div class="col-lg-3 col-md-6 col-sm-3">
                                                        <label class="control-label">Status</label>
                                                        <?php
                                                          $arrayStatus = array(
                                                                    array('1','PUBLISH'),
                                                                    array('2','NON PUBLISH'),
                                                          );
                                                          echo cmbArray("statusPublish",$getDataEdit['status'],$arrayStatus,"STATUS","class='selectpicker' data-style='btn btn-primary btn-round' title='Single Select' data-size='7'")
                                                        ?>
                                                    </div>
                                                  </div>
                                                  <div class="row">
                                                    <div class="col-md-4 col-sm-4">
                                                      <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                                                          <div class="fileinput-new thumbnail">
                                                              <img  src="<?php echo $baseOfFile ?>" id='tempImageProduk' alt="...">
                                                          </div>
                                                          <div class="fileinput-preview fileinput-exists thumbnail"></div>
                                                          <div>
                                                              <span class="btn btn-rose btn-round btn-file">
                                                                  <span class="fileinput-new">Select image</span>
                                                                  <span class="fileinput-exists">Change</span>
                                                                  <input type="hidden" id='gambarProduk' name='gambarProduk' value='<?php echo $baseOfFile ?>'>
                                                                  <input type="file" accept='image/x-png,image/gif,image/jpeg' onchange="imageChanged();" id='imageProduk' name="imageProduk">
                                                              </span>
                                                              <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
                                                          </div>
                                                      </div>
                                                    </div>
                                                  </div>
                                                  <div class="row">
                                                    <div class="col-lg-12">
                                                      <form method="#" action="#">
                                                        <div class="form-group label-floating">
                                                            <label class="control-label">Nama Produk</label>
                                                            <input type="text" id="namaProduk" class="form-control" value='<?php echo $getDataEdit['nama_produk'] ?>'>
                                                        </div>
                                                      </form>
                                                    </div>
                                                  </div>
                                                  <div class="row">
                                                    <div class="col-md-12 col-sm-12">
                                                      Screen Shot
                                                        <form action="upload.php" id='dropzone'  >
                                                        </form>
                                                        <input type="file" multiple="multiple"  accept='image/x-png,image/gif,image/jpeg' class="dz-hidden-input" style="visibility: hidden; position: absolute; top: 0px; left: 0px; height: 0px; width: 0px;">
                                                    </div>
                                                  </div>
                                                  <div class="row">
                                                    <div class="card">
                                                        <div class="card-body no-padding">
                                                            <div id="summernote">
                                                              <?php echo $getDataEdit['deskripsi']?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                  </div>
                                                  <div class="row">
                                                    <div class="col-lg-12">
                                                      <button type="button" class="btn btn-primary" id='buttonSubmit' onclick="saveEditProduk(<?php echo $getDataEdit['id'] ?>);" data-dismiss="modal">Simpan</button>
                                                    </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          <div class="col-md-12" id='tableProduk'>
                              <div class="card">
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <script>

              </script>
              <?php
            }

         ?>



  <!-- Popup Area -->

        <div class="modal fade" id="formDeskripsiScreenShot" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <i class="material-icons">clear</i>
                        </button>
                        <h4 class="modal-title">Screen Shot</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group label-floating">
                                  <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                                              <div class="fileinput-new thumbnail">
                                                  <img  src="assets/img/image_placeholder.jpg" id='tempScreenShot' alt="...">
                                              </div>
                                              <div class="fileinput-preview fileinput-exists thumbnail"></div>
                                          </div>
                                </div>
                            </div>
                        </div>
                        <!-- Start Form Input -->
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group label-floating" id='divForDesc'>
                                    <label class="control-label">Deskripsi Srenshot</label>
                                    <textarea id='descSreenShot' class="form-control" style="height:100px;"></textarea>
                                </div>
                            </div>
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-simple" id='buttonSubmitScreenShot' data-dismiss="modal">Simpan</button>
                        <button type="button" class="btn btn-danger btn-simple" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
<?php

     break;
     }

}


?>
