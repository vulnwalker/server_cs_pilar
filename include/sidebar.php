<?php
  $getDataUser = sqlArray(sqlQuery("select * from users where username = '".$_SESSION['username']."'"));
 ?>
            <div class="sidebar-wrapper">
                <div class="user">
                    <div class="photo">
                        <img src="http://ceukokom.com/img/icons/icon-user.png" />
                    </div>
                    <div class="info">
                        <a data-toggle="collapse" href="#collapseExample" class="collapsed">
                            <span>
                                <?php echo $getDataUser['nama']; ?>
                                <b class="caret"></b>
                            </span>
                        </a>
                        <div class="clearfix"></div>
                        <div class="collapse" id="collapseExample">
                            <ul class="nav">
                                <li>
                                    <a href="?page=profile">
                                        <span class="sidebar-mini">></span>
                                        <span class="sidebar-normal">My Profile</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="logout.php">
                                        <span class="sidebar-mini">></span>
                                        <span class="sidebar-normal">Logout</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <ul class="nav">
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
                    }else{
                        $userManagement = "active";
                    }

                        echo "<li class='$userManagement'>
                                  <a href='?page=userManagement'>
                                      <i class='material-icons'>dashboard</i>
                                      <p>User Management</p>
                                  </a>
                              </li>

                              <li class='$produkActive'>
                                  <a href='?page=produk'>
                                       <i class='material-icons'>image</i>
                                      <p>Produk</p>
                                  </a>
                              </li>
                              <li class='$informasiActive'>
                                  <a href='?page=informasi'>
                                      <i class='material-icons'>timeline</i>
                                      <p>Informasi</p>
                                  </a>
                              </li>
                              <li class='$acaraActive'>
                                  <a href='?page=acara'>
                                      <i class='material-icons'>alarm</i>
                                      <p>Acara</p>
                                  </a>
                              </li>
                              <li class='$sliderActive'>
                                  <a href='?page=slider'>
                                      <i class='material-icons'>burst_mode</i>
                                      <p>Slider</p>
                                  </a>
                              </li>
                              <li class='$lowonganKerjaActive'>
                                  <a href='?page=lowonganKerja'>
                                      <i class='material-icons'>person_add</i>
                                      <p>Lowongan Kerja</p>
                                  </a>
                              </li>
                              <li class='$teamActivce'>
                                  <a href='?page=team'>
                                      <i class='material-icons'>supervisor_account</i>
                                      <p>Team</p>
                                  </a>
                              </li>
                              <li class='$settingActive'>
                                  <a href='?page=setting'>
                                      <i class='material-icons'>settings_applications</i>
                                      <p>Setting</p>
                                  </a>
                              </li>
                              <li class='$chatingActive'>
                                  <a href='?page=chating'>
                                      <i class='material-icons'>chat</i>
                                      <p>Live Chat</p>
                                  </a>
                              </li>
                              ";


                     ?>
                </ul>
            </div>
