<?php
include "include/config.php";
session_start();
if ($_SESSION['status'] != "login") {
    header("location:index.php");
}
?>
<!DOCTYPE html>


<html lang="en">
	<head>
		<title>CS PILAR</title>
		<?php include "head.php";
	 ?>
	</head>
	<body class="menubar-hoverable header-fixed menubar-pin">

		<?php
      if($_GET['page'] !='chating'){
        ?>
        <header id="header" >
    			<div class="headerbar">
    				<div class="headerbar-left" style="width: 11.1%;">
    					<ul class="header-nav header-nav-options">
    						<li class="header-nav-brand" >
    							<div class="brand-holder">
    								<a href="#">
    									<span class="text-lg text-bold text-primary" id='pageTitle'>USER MANAGEMENT</span>
    								</a>
    							</div>
    						</li>
    					</ul>
    				</div>
    				<div class="headerbar-left" style="margin-left: 11.1%;" id='filterinTable'>
    					<ul class='header-nav header-nav-options'>
    						<li class='dropdown'>
                              <div class="row">
                              	<div class='col-xs-3 col-sm-3 col-md-3 col-lg-3'>
                              		<div class="form-group floating-label" style="padding-top: 0px;">
										<div class="input-group">
											<span class="input-group-addon"></span>
											<div class="input-group-content">
												<input type="text" class="form-control" id="searchData">
												<label for="id="searchData"">Search</label>
											</div>
										</div>
									</div>
                              	</div>
                              	<div class='col-xs-3 col-sm-3 col-md-3 col-lg-3'>
                                  <div class="form-group floating-label " style="padding-top: 0px;">
                                    <div class="input-group">
                                      <div class="input-group-content">
                                        <input type="number" class="form-control" id="jumlahDataPerhalaman" value="50" name = 'jumlahDataPerhalaman'>
                                        <label for="jumlahDataPerhalaman">Data Perhalaman</label>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </li>
    					</ul>
    				</div>
    				<div class="headerbar-right" id='actionArea'>
    					<ul class="header-nav header-nav-options">
    						<li class="dropdown">
    							<div class="row">

    								<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
    									<a href="baru.php">
    										<button type="submit" class="btn ink-reaction btn-flat btn-primary">
    											<i class="fa fa-plus"></i>
    											baru
    										</button>
    									</a>
    								</div>
    								<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
    									<button type="submit" class="btn ink-reaction btn-flat btn-primary">
    										<i class="fa fa-magic"></i>
    										edit
    									</button>
    								</div>
    								<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
    									<button type="submit" class="btn ink-reaction btn-flat btn-primary">
    										<i class="fa fa-close"></i>
    										hapus
    									</button>
    								</div>
    							</div>
    						</li>
    						<li class="dropdown" id='findArea'>
    							<form class="navbar-search" role="search">
    								<div class="form-group">
    									<input type="text" class="form-control" name="headerSearch" placeholder="Enter your keyword">
    								</div>
    								<button type="submit" class="btn btn-icon-toggle ink-reaction">
    									<i class="fa fa-search"></i>
    								</button>
    							</form>
    						</li>
    					</ul>
    				</div>
    			</div>
    		</header>
        <?php
      }else{
        ?>
        <header id="header" >
    			<div class="headerbar">
    				<div class="headerbar-left" style="width: 11.1%;">
    					<ul class="header-nav header-nav-options">
    						<li class="header-nav-brand" >
    							<div class="brand-holder">
    								<a href="html/dashboards/dashboard.html">
    									<span class="text-lg text-bold text-primary" id='pageTitle'>Live Chat</span>
    								</a>
    							</div>
    						</li>
    						<li class="hidden-lg hidden-md hidden-sm">
    							<a class="btn btn-icon-toggle menubar-toggle" data-toggle="menubar" href="javascript:void(0);">
    								<i class="fa fa-bars"></i>
    							</a>
    						</li>
    					</ul>
    				</div>
    				<div class="headerbar-left" style="margin-left: 11.1%;" id='filterinTable'>
    				</div>
    				<div class="headerbar-right" id='actionArea'>
    				</div>
    			</div>
    		</header>
        <?php
      }

    ?>

		<div id="base">
<?php
$getDataUser = sqlArray(sqlQuery("select * from users where username = '".$_SESSION['username']."'"));
$arrayHakAkses = explode(';',$getDataUser['hak_akses']);
		$page = @$_GET['page'];
		if ($page == "informasi") {
      if(in_array('2',$arrayHakAkses)){
        include 'pages/informasi.php';
      }
		}elseif ($page == "produk") {
      if(in_array('2',$arrayHakAkses)){
        include 'pages/produk.php';
      }
		}elseif ($page == "acara") {
      if(in_array('4',$arrayHakAkses)){
        include 'pages/acara.php';
      }
		}elseif ($page == "slider") {
      if(in_array('5',$arrayHakAkses)){
        include 'pages/slider.php';
      }
		}elseif ($page == "setting") {
      if(in_array('8',$arrayHakAkses)){
        include 'pages/setting.php';
      }
		}elseif ($page == "chating") {
		  include 'pages/chating.php';
		}elseif ($page == "userManagement") {
      if(in_array('1',$arrayHakAkses)){
        include 'pages/userManagement.php';
      }
		}elseif ($page == "member") {
      if (in_array('9',$arrayHakAkses)) {
        include 'pages/member.php';
      }
    }elseif ($page == "lowonganKerja") {
      if(in_array('6',$arrayHakAkses)){
        include 'pages/lowonganKerja.php';
      }
		}elseif ($page == "team") {
      if(in_array('7',$arrayHakAkses)){
        include 'pages/team.php';
      }
		}elseif ($page == "profile") {
		  include 'pages/profile.php';
		}elseif ($page == "popular") {
		  include 'pages/popular.php';
		}else{
			echo " 404 ! halaman tidak di temukan ";
		}
?>
			<?php include "include/sidebar.php"; ?>
		</div>
		<?php include "footer.php"; ?>
	</body>
</html>
