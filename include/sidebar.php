<?php
  $getDataUser = sqlArray(sqlQuery("select * from users where username = '".$_SESSION['username']."'"));
 ?>
 <!-- BEGIN MENUBAR-->
 <div id="menubar" class="menubar-inverse ">
   <div class="menubar-fixed-panel">
     <div>
       <a class="btn btn-icon-toggle btn-default menubar-toggle" data-toggle="menubar" href="javascript:void(0);">
         <i class="fa fa-bars"></i>
       </a>
     </div>
     <div class="expanded">
       <a href="#">
         <span class="text-lg text-bold text-primary ">MATERIAL&nbsp;ADMIN</span>
       </a>
     </div>
   </div>
   <div class="menubar-scroll-panel">

     <!-- BEGIN MAIN MENU -->
     <ul id="main-menu" class="gui-controls">
       <?php
                    $page = @$_GET['page'];
                    if ($page == "informasi") {
                        $informasiActive = "active";
                    }elseif ($page == "produk") {
                        $produkActive = "active";
                    }elseif ($page == "acara") {
                        $acaraActive = "active";
                    }elseif ($page == "slider") {
                        $sliderActive = "active";
                    }elseif ($page == "setting") {
                        $settingActive = "active";
                    }elseif ($page == "chating") {
                        $chatingActive = "active";
                    }elseif ($page == "lowonganKerja") {
                        $lowonganKerjaActive = "active";
                    }elseif ($page == "team") {
                        $teamActivce = "active";
                    }elseif ($page == "profile") {
                        $profileActive = "active";
                    }elseif ($page == "popular") {
                        $popularActive = "active";
                    }elseif ($page == "member"){
                        $memberActive = "active";
                    }else{
                        $userManagement = "active";
                    }


                    $modulUserManagement = "
                    <li>
                      <a href='?page=userManagement' class='$userManagement'>
                        <div class='gui-icon'><i class='fa fa-users'></i></div>
                        <span class='title'>Users</span>
                      </a>
                    </li>";
                    $modulMember = "
                    <li>
                      <a href='?page=member' class='$memberActive'>
                        <div class='gui-icon'><i class='md md-account-child'></i></div>
                        <span class='title'>Member</span>
                      </a>
                    </li>
                    ";
                    $modulProduk = "
                    <li>
                      <a href='?page=produk' class='$produkActive'>
                        <div class='gui-icon'><i class='md md-computer'></i></div>
                        <span class='title'>Produk</span>
                      </a>
                    </li>
                    ";
                    $modulInformasi = "
                    <li>
                      <a href='?page=informasi' class='$informasiActive'>
                        <div class='gui-icon'><i class='md md-info'></i></div>
                        <span class='title'>Informasi</span>
                      </a>
                    </li>
                    ";
                    $modulAcara = "
                    <li>
                      <a href='?page=acara' class='$acaraActive'>
                        <div class='gui-icon'><i class='md md-alarm-on'></i></div>
                        <span class='title'>Acara</span>
                      </a>
                    </li>
                    ";
                    $modulSlider = "
                    <li>
                      <a href='?page=slider' class='$sliderActive'>
                        <div class='gui-icon'><i class='md md-image'></i></div>
                        <span class='title'>Slider</span>
                      </a>
                    </li>
                    <li>
                    ";
                    $modulKerja = "
                    <li>
                      <a href='?page=lowonganKerja' class='$lowonganKerjaActive'>
                        <div class='gui-icon'><i class='md md-assessment'></i></div>
                        <span class='title'>Loker</span>
                      </a>
                    </li>
                    ";
                    $modulTeam = "
                    <li>
                      <a href='?page=team' class='$teamActivce'>
                        <div class='gui-icon'><i class='md md-quick-contacts-dialer'></i></div>
                        <span class='title'>Team</span>
                      </a>
                    </li>
                    ";
                    $modulSetting = "
                    <li>
                      <a href='?page=setting' class='$settingActive'>
                        <div class='gui-icon'><i class='md md-settings'></i></div>
                        <span class='title'>Setting</span>
                      </a>
                    </li>
                    ";
                    $arrayHakAkses = explode(";",$getDataUser['hak_akses']);

                    if(in_array(1,$arrayHakAkses))echo $modulUserManagement;
                    if(in_array(9,$arrayHakAkses))echo $modulMember;
                    if(in_array(2,$arrayHakAkses))echo $modulProduk;
                    if(in_array(3,$arrayHakAkses))echo $modulInformasi;
                    if(in_array(4,$arrayHakAkses))echo $modulAcara;
                    if(in_array(5,$arrayHakAkses))echo $modulSlider;
                    if(in_array(6,$arrayHakAkses))echo $modulKerja;
                    if(in_array(7,$arrayHakAkses))echo $modulTeam;
                    if(in_array(8,$arrayHakAkses))echo $modulSetting;
                        echo "

                                <li>
                                  <a href='?page=popular' class='$popularActive'>
                                    <div class='gui-icon'><i class='md md-star-rate'></i></div>
                                    <span class='title'>Popular</span>
                                  </a>
                                </li>
                                <li>
                                  <a href='?page=profile' class='$profileActive'>
                                    <div class='gui-icon'><i class='md md-timer-auto'></i></div>
                                    <span class='title'>Profile</span>
                                  </a>
                                </li>
                                <!-- <li>
                                  <a href='?page=chating' class='$chatingActive'>
                                    <div class='gui-icon'><i class='md md-forum'></i></div>
                                    <span class='title'>Live Chat</span>
                                  </a>
                                </li> -->



                              ";

                     ?>
     </ul>

   </div><!--end .menubar-scroll-panel-->
 </div><!--end #menubar-->
 <!-- END MENUBAR -->
