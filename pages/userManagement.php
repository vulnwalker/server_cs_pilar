<?php
$tipe = @$_GET['tipe'];
$cek = "";
$err = "";
$content = "";
$tableName = "users";
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
      }
      // elseif(empty($emailUser)){
      //     $err = "Isi email";
      // }
      elseif(empty($passwordUser)){
          $err = "Isi password ";
      }elseif(empty($namaUser)){
          $err = "Isi nama ";
      }elseif(empty($hakAkses) && $jenisUser == '2'){
          $err = "Pilih hak akses ";
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
                  'hak_akses' =>  implode(";",$hakAkses),
          );
          $query = sqlInsert("$tableName",$data);
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
      }
      // elseif(empty($emailUser)){
      //     $err = "Isi email";
      // }
      elseif(empty($passwordUser)){
          $err = "Isi password ";
      }elseif(empty($namaUser)){
          $err = "Isi nama ";
      }elseif(empty($hakAkses) && $jenisUser == '2'){
          $err = "Pilih hak akses ";
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
                'hak_akses' =>  implode(";",$hakAkses),
        );
        $query = sqlUpdate("$tableName",$data,"id = '$idEdit'");
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

    case 'Hapus':{
      for ($i=0; $i < sizeof($userManagement_cb) ; $i++) {
        $query = "delete from $tableName where id = '".$userManagement_cb[$i]."'";
        sqlQuery($query);
      }

      $cek = $query;
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'Edit':{

      $content = array("idEdit" => $userManagement_cb[0]);
      echo generateAPI($cek,$err,$content);
    break;
    }

    case 'loadTable':{
      if(!empty($searchData)){
        $getColom = sqlQuery("desc $tableName");
        while ($dataColomn = sqlArray($getColom)) {

        }
        $arrKondisi[] = "nama like '%$searchData%' ";
        $arrKondisi[] = "username like '%$searchData%' ";
        $arrKondisi[] = "email like '%$searchData%' ";
        $arrKondisi[] = "instansi like '%$searchData%' ";
        $arrKondisi[] = "telepon like '%$searchData%' ";
        $arrKondisi[] = "jenis_user like '%$searchData%' ";
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
      // $cek = "select * from $tableName $kondisi $queryLimit $kondisiSort";
      $nomor = 1;
      $nomorCB = 0;
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
                          <td class='text-center'>$nomor</td>
                          <td class='text-center'>
                            <div  class='checkbox checkbox-inline checkbox-styled'>
                                    <label>
                                    ".setCekBox($nomorCB,$id,'','userManagement')."
                                <span></span>
															</label>
														</div>
                            </td>
                          <td  class='col-lg-2'>$nama</td>
                          <td  class='col-lg-2'>$username</td>
                          <td  class='col-lg-2'>$email</td>
                          <td  class='col-lg-3'>$instansi</td>
                          <td  class='col-lg-2'>$telepon</td>
                          <td  class='col-lg-2'>$jenisUser</td>
                       </tr>
                    ";
          $nomor += 1;
          $nomorCB += 1;
      }

      $tabelBody = "

        <thead>
          <tr>
            <th class='text-center' onclick='sortTable(0)'>No</th>
            <th class='text-center'>
             <div class='checkbox checkbox-inline checkbox-styled' >
              <label>
                <input type='checkbox' name='userManagement_toogle' id='userManagement_toogle' onclick=checkSemua($nomorCB,'userManagement_cb','userManagement_toogle','userManagement_jmlcek',this)>
              <span></span>
            </label>
          </div>
            </th>
            <th class='col-lg-2' onclick='sortTable(1)'>Nama</th>
            <th class='col-lg-2' onclick='sortTable(2)'>Username</th>
            <th class='col-lg-2' onclick='sortTable(3)'>Email</th>
            <th class='col-lg-3' onclick='sortTable(4)'>Instansi</th>
            <th class='col-lg-2' onclick='sortTable(5)'>Telepon</th>
            <th class='col-lg-2' onclick='sortTable(6)'>Kategori</th>
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
      <input type='hidden' name='userManagement_jmlcek' id='userManagement_jmlcek' value='0'>";
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
                <div class='col-xs-4 col-sm-2 col-md-2 col-lg-3'>
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
                                <li id='username' onclick=sortData(this);><a href='#' style='width: 100%;' >Username</a></li>
                                <li id='email' onclick=sortData(this);><a href='#' style='width: 100%;' >Email</a></li>
                                <li id='instansi' onclick=sortData(this);><a href='#' style='width: 100%;' >Instansi</a></li>
                                <li id='telepon' onclick=sortData(this);><a href='#' style='width: 100%;' >Telepon</a></li>
                                <li id='jenis_user' onclick=sortData(this);><a href='#' style='width: 100%;' >Kategori</a></li>
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
        var url = "http://"+window.location.hostname+"/api.php?page=userManagement";
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
        /*th{
          cursor: pointer;
        }*/
        .header-nav-options .dropdown .dropdown-menu{
          top: 100%;
        }
        .form .form-group .input-group-addon:first-child{
          min-width: 0px;
        }
        /*#jumlahDataPerhalaman{
          width: 60px;
        }*/
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
        <script src="js/userManagement.js"></script>
        <script type="text/javascript">
          // $('li').click(function(e) {
          //     $(this).addClass('active-tick').siblings().removeClass('active-tick');
          // });


          function sortTable(n) {
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.getElementById("tabelBody");
            switching = true;
            //Set the sorting direction to ascending:
            dir = "asc";
            /*Make a loop that will continue until
            no switching has been done:*/
            while (switching) {
              //start by saying: no switching is done:
              switching = false;
              rows = table.getElementsByTagName("TR");
              /*Loop through all table rows (except the
              first, which contains table headers):*/
              for (i = 1; i < (rows.length - 1); i++) {
                //start by saying there should be no switching:
                shouldSwitch = false;
                /*Get the two elements you want to compare,
                one from current row and one from the next:*/
                x = rows[i].getElementsByTagName("TD")[n];
                y = rows[i + 1].getElementsByTagName("TD")[n];
                /*check if the two rows should switch place,
                based on the direction, asc or desc:*/
                if (dir == "asc") {
                  if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                    //if so, mark as a switch and break the loop:
                    shouldSwitch= true;
                    break;
                  }
                } else if (dir == "desc") {
                  if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                    //if so, mark as a switch and break the loop:
                    shouldSwitch= true;
                    break;
                  }
                }
              }
              if (shouldSwitch) {
                /*If a switch has been marked, make the switch
                and mark that a switch has been done:*/
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                //Each time a switch is done, increase this count by 1:
                switchcount ++;
              } else {
                /*If no switching has been done AND the direction is "asc",
                set the direction to "desc" and run the while loop again.*/
                if (switchcount == 0 && dir == "asc") {
                  dir = "desc";
                  switching = true;
                }
              }
            }
          }
        </script>
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
                  $("#pageTitle").text("USERS");
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
                            <form id='formUserManagement' name="formUserManagement" action="#">
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

                <div id="content">
          				<section>
          					<div class="section-body contain-lg">
          						<div class="row">
          							<div class="col-md-12">
          								<div class="card">
          									<div class="card-body">
          										<form class="form" id ='formUser'>
                                <div class="form-group floating-label">
                                  <?php
                                    $arrayStatus = array(
                                              array('1','MEMBER'),
                                              array('2','ADMIN'),
                                    );
                                    echo cmbArrayEmpty("statusUser","",$arrayStatus,"-- TYPE USER --","class='form-control' ")
                                  ?>
          												<label for="statusUser">TYPE USER</label>
          											</div>
          											<div class="form-group floating-label">
          												<input type="text" class="form-control" id="usernameUser" name='usernameUser'>
          												<label for="usernameUser">Username</label>
          											</div>
          											<div class="form-group floating-label">
          												<input type="password" class="form-control" id="passwordUser" name='passwordUser'>
          												<label for="passwordUser">Password</label>
          											</div>
                                <div class="form-group floating-label">
        												  <input type="email" id='emailUser' name='emailUser' class="form-control">
        												  <label for="emailUser">Email</label>
          											</div>
                                <div class="form-group floating-label">
        												  <input type="text" id='namaUser' name='namaUser' class="form-control">
        												  <label for="namaUser">Nama Lengkap</label>
          											</div>
                                <div class="form-group">
          												<input type="number" id='teleponUser' name='teleponUser' class="form-control">
          												<label for="teleponUser">Telepon</label>
          											</div>
                                <div class="form-group floating-label">
          												<textarea name="alamatUser" id="alamatUser" class="form-control" rows="3" placeholder=""></textarea>
          												<label for="alamatUser">Alamat</label>
          											</div>
                                <div class="form-group floating-label">
        												  <input type="text" id='instansiUser' name='instansiUser' class="form-control">
        												  <label for="instansiUser">Instansi</label>
          											</div>
                                <div class="form-group floating-label">
                                  <select class="form-control select2-list" id='hakAkses' name='hakAkses'  multiple="" tabindex="-1" style="display: none;">
                                    <?php
                                       $getHakAkses = sqlQuery("select * from ref_modul");
                                       while ($dataModul = sqlArray($getHakAkses)) {
                                           echo "<option value='".$dataModul['id']."'>".$dataModul['modul']."</option>";
                                       }
                                    ?>
                                 </select>
                                 <label>Hak Akses</label>
          											</div>
          										</form>
          									</div>
          									<div class="card-actionbar">
          										<div class="card-actionbar-row">
          											<button type="button" class="btn ink-reaction btn-raised btn-primary" onclick="saveUser();">Simpan</button>
          											<button type="button" class="btn ink-reaction btn-raised btn-danger" onclick="refreshList();">batal</button>
          										</div>
          									</div>
          								</div>
          							</div>
          						</div>
          					</div>
          				</section>
          			</div>

                <script type="text/javascript">
                  $(document).ready(function() {
                      setMenuEdit('baru');
                      $("#pageTitle").text("USERS");
                      $(".select2-list").select2({
                        allowClear: true
                      });
                      $(".select2-list").select2({
                        allowClear: true
                      });
                  });
                </script>
                <?php
              }elseif($_GET['action']=='edit'){
                  $getData = sqlArray(sqlQuery("select * from $tableName where id = '".$_GET['idEdit']."'"));
                  $getRealPassword = sqlArray(sqlQuery("select * from wordlist where hash = '".$getData['password']."'"));
                  $arrayHakAkses = explode(";",$getData['hak_akses']);
                  ?>

                  <script type="text/javascript">
                    $(document).ready(function() {
                        setMenuEdit('baru');
                        $("#pageTitle").text("USERS");
                        $(".select2-list").select2({
                          allowClear: true
                        });
                        $(".select2-list").select2({
                          allowClear: true
                        });
                    });
                  </script>
                  <div id="content">
            				<section>
            					<div class="section-body contain-lg">
            						<div class="row">
            							<div class="col-md-12">
            								<div class="card">
            									<div class="card-body">
            										<form class="form" id ='formUser'>
                                  <div class="form-group floating-label">
                                    <?php
                                      $arrayStatus = array(
                                                array('1','MEMBER'),
                                                array('2','ADMIN'),
                                      );
                                      echo cmbArrayEmpty("statusUser",$getData['jenis_user'],$arrayStatus,"-- TYPE USER --","class='form-control' ")
                                    ?>
            												<label for="statusUser">TYPE USER</label>
            											</div>
            											<div class="form-group floating-label">
            												<input type="text" class="form-control" id="usernameUser" name='usernameUser' value="<?php echo $getData['username'] ?>">
            												<label for="usernameUser">Username</label>
            											</div>
            											<div class="form-group floating-label">
            												<input type="password" class="form-control" id="passwordUser" name='passwordUser' value="<?php echo $getRealPassword['password'] ?>">
            												<label for="passwordUser">Password</label>
            											</div>
                                  <div class="form-group floating-label">
          												  <input type="email" id='emailUser' name='emailUser' class="form-control" value="<?php echo $getData['email'] ?>">
          												  <label for="emailUser">Email</label>
            											</div>
                                  <div class="form-group floating-label">
          												  <input type="text" id='namaUser' name='namaUser' class="form-control" value="<?php echo $getData['nama'] ?>" >
          												  <label for="namaUser">Nama Lengkap</label>
            											</div>
                                  <div class="form-group">
            												<input type="number" id='teleponUser' name='teleponUser' class="form-control" value="<?php echo $getData['telepon'] ?>">
            												<label for="teleponUser">Telepon</label>
            											</div>
                                  <div class="form-group floating-label">
            												<textarea name="alamatUser" id="alamatUser" class="form-control" rows="3" placeholder=""><?php echo $getData['alamat'] ?></textarea>
            												<label for="alamatUser">Alamat</label>
            											</div>
                                  <div class="form-group floating-label">
          												  <input type="text" id='instansiUser' name='instansiUser' class="form-control" value="<?php echo $getData['instansi'] ?>">
          												  <label for="instansiUser">Instansi</label>
            											</div>
                                  <div class="form-group floating-label">
                                    <select class="form-control select2-list" id='hakAkses' name='hakAkses'  multiple="" tabindex="-1" style="display: none;">
                                      <?php
                                         $getHakAkses = sqlQuery("select * from ref_modul");
                                         while ($dataModul = sqlArray($getHakAkses)) {
                                            if(in_array($dataModul['id'],$arrayHakAkses)){
                                              echo "<option value='".$dataModul['id']."' selected>".$dataModul['modul']."</option>";
                                            }else{
                                              echo "<option value='".$dataModul['id']."'>".$dataModul['modul']."</option>";
                                            }
                                         }
                                      ?>
                                   </select>
                                   <label>Hak Akses</label>
            											</div>
            										</form>
            									</div>
            									<div class="card-actionbar">
            										<div class="card-actionbar-row">
            											<button type="button" class="btn ink-reaction btn-raised btn-primary" onclick="saveEditUser(<?php echo $_GET['idEdit'] ?>);">Simpan</button>
            											<button type="button" class="btn ink-reaction btn-raised btn-danger" onclick="refreshList();">batal</button>
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
