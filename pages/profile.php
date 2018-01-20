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

    case 'saveProfile':{
      if(empty($oldPassword)){
          $err = "Isi password lama";
      }elseif(empty($newPassword)){
          $err = "Isi password baru";
      }elseif(empty($confirmPassword)){
          $err = "Isi confirm baru";
      }

      $getDataUserSebelumnya = sqlArray(sqlQuery("select * from users where username = '".$_SESSION['username']."' "));
      if($getDataUserSebelumnya['password'] != sha1(md5($oldPassword))){
          $err = "Password lama salah";
      }
      if($newPassword != $confirmPassword){
         $err = "password tidak sama";
      }


      if(empty($err)){
        $dataHash = array(
            'hash' => sha1(md5($newPassword)),
            'password' => $newPassword,
        );
        if(mysql_num_rows(mysql_query("select * from wordlist where password = '$passwordUser'")) == 0){
            sqlQuery(sqlInsert("wordlist",$dataHash));
        }
        $newPassword = sha1(md5($newPassword));
        $dataPassword = array(
                                'password' => $newPassword,
                            );
        sqlQuery(sqlUpdate("users",$dataPassword,"username = '".$_SESSION['username']."'"));
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
        ?>
        <script>
        var url = "http://"+window.location.hostname+"/api.php?page=profile";
        </script>
        <script src="js/profile.js"></script>
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
                        <input type="password" class="form-control" id="oldPassword" name="oldPassword" required data-rule-minlength="5">
                        <label for="oldPassword">Password Sebelumnya</label>
                      </div>
                      <div class="form-group">
                        <input type="password" class="form-control" id="newPassword" name="newPassword" required data-rule-minlength="5">
                        <label for="newPassword">Password Sekarang</label>
                      </div>
                      <div class="form-group">
                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required data-rule-minlength="5">
                        <label for="confirmPassword">Confirmation Password</label>
                      </div>
                    </div><!--end .card-body -->
                    <div class="card-actionbar">
                      <div class="card-actionbar-row">
                        <button type="button" class="btn btn-primary ink-reaction btn-raised" onclick="saveChangePassword();">Simpan</button>
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
