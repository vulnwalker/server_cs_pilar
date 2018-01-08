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

    case 'saveUser':{
      if(empty($statusUser)){
          $err = "Pilih status user";
      }elseif(empty($usernameUser)){
          $err = "Isi username";
      }elseif(empty($emailUser)){
          $err = "Isi email";
      }elseif(empty($passwordUser)){
          $err = "Isi password ";
      }elseif(empty($namaUser)){
          $err = "Isi nama ";
      }

      if(empty($err)){
          $data = array(
                  'email' => $emailUser,
                  'username' => $usernameUser,
                  'password' => sha1(md5($passwordUser)),
                  'nama' => $namaUser,
                  'telepon' => $teleponUser,
                  'alamat' =>  $alamatUser,
                  'instansi' =>  $instansiUser,
                  'jenis_user' =>  $statusUser,
          );
          $query = sqlInsert("users",$data);
          sqlQuery($query);
          $cek = $query;

          $dataHash = array(
              'hash' => sha1(md5($passwordUser)),
              'password' => $passwordUser,
          );
          if(mysql_num_rows(mysql_query("select * from wordlist where password = '$passwordUser'")) == 0){
              sqlQuery(sqlInsert("wordlist",$dataHash));
          }

      }
      $content = array("judulUser" => $judulUser);

      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'saveEditUser':{
      if(empty($statusUser)){
          $err = "Pilih status user";
      }elseif(empty($usernameUser)){
          $err = "Isi username";
      }elseif(empty($emailUser)){
          $err = "Isi email";
      }elseif(empty($passwordUser)){
          $err = "Isi password ";
      }elseif(empty($namaUser)){
          $err = "Isi nama ";
      }

      if(empty($err)){
        $data = array(
                'email' => $emailUser,
                'username' => $usernameUser,
                'password' => sha1(md5($passwordUser)),
                'nama' => $namaUser,
                'telepon' => $teleponUser,
                'alamat' =>  $alamatUser,
                'instansi' =>  $instansiUser,
                'jenis_user' =>  $statusUser,
        );
        $query = sqlUpdate("users",$data,"id = '$idEdit'");
        sqlQuery($query);
        $cek = $query;

        $dataHash = array(
            'hash' => sha1(md5($passwordUser)),
            'password' => $passwordUser,
        );
        if(mysql_num_rows(mysql_query("select * from wordlist where password = '$passwordUser'")) == 0){
            sqlQuery(sqlInsert("wordlist",$dataHash));
        }
      }
      $content = array("judulUser" => $judulUser);

      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'deleteUser':{
      $query = "delete from users where id = '$id'";
      sqlQuery($query);
      $cek = $query;
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'updateUser':{
      $getData = sqlArray(sqlQuery("select * from users where id = '$id'"));
      $getRealPassword = sqlArray(sqlQuery("select * from wordlist where hash = '".$getData['password']."'"));
      $arrayStatus = array(
                array('1','MEMBER'),
                array('2','ADMIN'),
      );
      $content = array("usernameUser" => $getData['username'],"statusUser" => cmbArray("statusUser",$getData['jenis_user'],$arrayStatus,"-- TYPE USER --","class='selectpicker' data-style='btn btn-primary btn-round' title='Single Select' data-size='7'"),
       "emailUser" => $getData['email'], "passwordUser" => $getRealPassword['password'], "namaUser" => $getData['nama'], "teleponUser" => $getData['telepon'], "alamatUser" => $getData['alamat'], "instansiUser" => $getData['instansi']);
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'loadTable':{
      $getData = sqlQuery("select * from users");
      while($dataUser = sqlArray($getData)){
        foreach ($dataUser as $key => $value) {
            $$key = $value;
        }
        if($jenis_user == '1'){
            $jenisUser = "MEMBER";
        }else{
            $jenisUser = "ADMIN";
        }
        $data .= "     <tr>
                          <td>$email</td>
                          <td>$nama</td>
                          <td>$instansi</td>
                          <td>$telepon</td>
                          <td>$alamat</td>
                          <td>$jenisUser</td>
                          <td class='text-right'>
                              <a onclick=updateUser($id) class='btn btn-simple btn-warning btn-icon edit'><i class='material-icons'>dvr</i></a>
                              <a onclick=deleteUser($id) class='btn btn-simple btn-danger btn-icon remove'><i class='material-icons'>close</i></a>
                          </td>
                      </tr>
                    ";
      }

      $tabel = "<table id='datatables' class='table table-striped table-no-bordered table-hover' cellspacing='0' width='100%' style='width:100%'>
          <thead>
              <tr>
                  <th style='width: 380px!important;'>Email</th>
                  <th>Nama</th>
                  <th>Instansi</th>
                  <th>Telepon</th>
                  <th>Alamat</th>
                  <th>Kategori</th>
                  <th class='disabled-sorting text-right'>Actions</th>
              </tr>
          </thead>
          <tbody>
            $data
          </tbody>
      </table>";
      $content = array("tabelUser" => $tabel);

      echo generateAPI($cek,$err,$content);
    break;
    }

     default:{
        ?>
        <script>
        var url = "http://"+window.location.hostname+"/api.php?page=userManagement";

        </script>
        <script src="js/userManagement.js"></script>

        <div class="content" style="margin: 0; min-height: unset; padding-top: 0; padding-bottom: 0;">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-4">
                <h4>USER MANAGEMENT</h4>
                <button class="btn btn-primary">
                  BARU
                </button>
                <button class="btn btn-warning">
                  EDIT
                </button>
                <button class="btn btn-rose">
                  HAPUS
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="content" style="margin: 0; min-height: unset; padding-top: 0; padding-bottom: 0;">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                            <div class="card">
                                <div class="card-content">
                                    <!-- <ul class="nav nav-pills nav-pills-primary">
                                        <li class="active">
                                            <a href="#dataInfo" id='data1' data-toggle="tab" aria-expanded="true" onclick="clearTemp();">User</a>
                                        </li>
                                        <li>
                                            <a href="#infoBaru" id='data2' data-toggle="tab" aria-expanded="false" onclick="baruUser();">Baru</a>
                                        </li>
                                    </ul> -->
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="dataInfo">
                                            <div class="col-md-12" id='tableUser'>
                                              <div class="card">
                                                  <!-- <div class="card-header card-header-icon" data-background-color="purple">
                                                      <i class="material-icons">assignment</i>
                                                  </div> -->
                                                  <div class="card-content">
                                                      <!-- <h4 class="card-title">Data User</h4> -->
                                                      <div class="toolbar">
                                                          <!--        Here you can write extra buttons/actions for the toolbar              -->
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
                                                  <!-- end content-->
                                              </div>
                                              <!--  end card  -->
                                          </div>
                                        </div>

                                        <div class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" id="LoadingImage" style="display: none;">
                                                <div class="modal-dialog modal-notice">
                                                    <div class="modal-content" style="background-color: transparent; border: unset; box-shadow: unset;">
                                                        <div class="modal-body">
                                                            <!-- <div id="LoadingImage"> -->
                                                              <img src="img/unnamed.gif" style="width: 30%; height: 30%; display: block; margin: auto;">
                                                            <!-- </div> -->
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>

                                        <div class="tab-pane" id="infoBaru">
                                          <form id='formUser'>
                                          <div class="row">
                                            <div class="col-lg-3 col-md-6 col-sm-3">
                                                <label class="control-label">Type</label>
                                                <?php
                                                  $arrayStatus = array(
                                                            array('1','MEMBER'),
                                                            array('2','ADMIN'),
                                                  );
                                                  echo cmbArray("statusUser","1",$arrayStatus,"-- TYPE USER --","class='selectpicker' data-style='btn btn-primary btn-round' title='Single Select' data-size='7'")
                                                ?>
                                            </div>
                                          </div>
                                          <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group label-floating" id='divForUsername'>
                                                    <label class="control-label">Username</label>
                                                    <input type="text" id='usernameUser' name='usernameUser' class="form-control">
                                                </div>
                                            </div>
                                          </div>
                                            <div class="row">
                                              <div class="col-lg-12">
                                                  <div class="form-group label-floating" id='divForPassword'>
                                                      <label class="control-label">Password</label>
                                                      <input type="password" id='passwordUser' name='passwordUser' class="form-control">
                                                  </div>
                                              </div>
                                            </div>
                                            <div class="row">
                                              <div class="col-lg-12">
                                                  <div class="form-group label-floating" id='divForEmail'>
                                                      <label class="control-label">Email</label>
                                                      <input type="email" id='emailUser' name='emailUser' class="form-control">
                                                  </div>
                                              </div>
                                            </div>
                                            <div class="row">
                                              <div class="col-lg-12">
                                                  <div class="form-group label-floating" id='divForNama'>
                                                      <label class="control-label">Nama Lengkap</label>
                                                      <input type="text" id='namaUser' name='namaUser' class="form-control">
                                                  </div>
                                              </div>
                                            </div>
                                            <div class="row">
                                              <div class="col-lg-12">
                                                  <div class="form-group label-floating" id='divForTelepon'>
                                                      <label class="control-label">Telepon</label>
                                                      <input type="number" id='teleponUser' name='teleponUser' class="form-control">
                                                  </div>
                                              </div>
                                            </div>
                                            <div class="row">
                                              <div class="col-lg-12">
                                                  <div class="form-group label-floating" id='divForAlamat'>
                                                      <label class="control-label">Alamat</label>
                                                      <textarea id='alamatUser' name='alamatUser' class="form-control"></textarea>
                                                  </div>
                                              </div>
                                            </div>
                                            <div class="row">
                                              <div class="col-lg-12">
                                                  <div class="form-group label-floating" id='divForInstansi'>
                                                      <label class="control-label">Instansi</label>
                                                      <input id='instansiUser' name='instansiUser' class="form-control">
                                                  </div>
                                              </div>
                                            </div>
                                            <div class="row">
                                              <div class="col-lg-12">
                                                <button type="button" class="btn btn-primary" id='buttonSubmit' onclick="saveUser();" data-dismiss="modal">Simpan</button>
                                              </div>
                                            </div>
                                          </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <!-- end row -->
            </div>
        </div>



  <!-- Popup Area -->

        <div class="modal fade" id="formUserBaru" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="width:80%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <i class="material-icons">clear</i>
                        </button>
                        <h4 class="modal-title">User</h4>
                    </div>
                    <div class="modal-body">
                        <form id='formUser'>
                        <!-- Start Customisable -->
                        <div class="row">
                          <div class="col-md-6">
                              <div class="row">
                                  <div class="col-lg-6 col-md-4 col-sm-3">
                                    <?php
                                        $arrayStatus = array(
                                                  array('1','PUBLISH'),
                                                  array('2','NON PUBLISH'),
                                        );
                                        echo cmbArray("statusPublish","1",$arrayStatus,"STATUS","class='selectpicker' data-style='btn btn-primary btn-round' title='Single Select' data-size='7'")
                                     ?>
                                  </div>
                              </div>
                          </div>
                      </div>
                        <!-- End Customisable -->

                        <!-- Start Form Input -->
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group label-floating">
                                    <label class="control-label">Judul User</label>
                                    <input type="text" id='judulUser' class="form-control">
                                </div>
                            </div>
                        </div>
                        <!-- End Form Input -->



                        <!-- Start Checkbox and Radio Buttons -->
                        <div class="row">
                          <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                <div class="radio">
                                    <label class="control-label">Posisi User</label>
                                </div>
                          </div>
                          <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                              <div class="radio">
                                  <label>
                                      <input type="radio" value='1' id='kiri' name="posisiUser" checked> Kiri
                                  </label>
                              </div>
                          </div>
                          <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                              <div class="radio">
                                  <label>
                                      <input type="radio" value='2' id='kanan' name="posisiUser"> Kanan
                                  </label>
                              </div>
                          </div>
                        </div>
                        <!-- End Checkbox and Radio Buttons -->

                        <!-- BEGIN SUMMERNOTE -->
                        <div class="card">
                            <div class="card-body no-padding">
                                <div id="summernote">
                                </div>
                            </div><!--end .card-body -->
                        </div><!--end .card -->
                        <!-- END SUMMERNOTE -->

                    </div>
                  </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-simple" id='buttonSubmit' onclick="saveUser();" data-dismiss="modal">Simpan</button>
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
