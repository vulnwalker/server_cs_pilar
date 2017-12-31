<?php
include "include/config.php";
session_start();
if ($_SESSION['status'] != "login") {
    header("location:index.php");
}
 ?>
<html lang="id">
<head>
	<?php include "head.php"; ?>
    <style type="text/css">
        /*.modal-dialog{
            width: 800px;
        }*/
        .form-group.label-floating label.control-label, .form-group.label-placeholder label.control-label{
            left: 0;
        }
        .checkbox label{
            color: #000;
        }
        .radio label{
            color: #000;
        }
        .btn-group.open>.dropdown-toggle.btn, .btn-group.open>.dropdown-toggle.btn.btn-default, .btn-group-vertical.open>.dropdown-toggle.btn, .btn-group-vertical.open>.dropdown-toggle.btn.btn-default{
            background-color: #17161680;
        }
        .btn.btn-sm, .btn-group-sm .btn, .navbar .navbar-nav>li>a.btn.btn-sm, .btn-group-sm .navbar .navbar-nav>li>a.btn{
            font-size: unset;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="sidebar" data-active-color="rose" data-background-color="black" data-image="assets/img/sidebar-1.jpg">
							<?php include "include/sidebar.php"; ?>
        </div>
        <div class="main-panel">
            <nav class="navbar navbar-transparent navbar-absolute">
                <div class="container-fluid">
                    <div class="navbar-minimize">
                        <button id="minimizeSidebar" class="btn btn-round btn-white btn-fill btn-just-icon">
                            <i class="material-icons visible-on-sidebar-regular">more_vert</i>
                            <i class="material-icons visible-on-sidebar-mini">view_list</i>
                        </button>
                    </div>
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#"> </a>
                    </div>
                </div>
            </nav>
						<?php
$page = @$_GET['page'];
if ($page == "informasi") {
    include 'pages/informasi.php';
}elseif ($page == "produk") {
    include 'pages/produk.php';
}elseif ($page == "acara") {
    include 'pages/acara.php';
}elseif ($page == "slider") {
    include 'pages/slider.php';
}elseif ($page == "setting") {
    include 'pages/setting.php';
}elseif ($page == "chating") {
    include 'pages/chating.php';
}elseif ($page == "userManagement") {
    include 'pages/userManagement.php';
}elseif ($page == "lowonganKerja") {
    include 'pages/lowonganKerja.php';
}else{
echo " 404 ! halaman tidak di temukan ";
}



?>
            <!-- <footer class="footer">
                <div class="container-fluid">
                    <p class="copyright pull-right">
                        &copy;
                        <script>
                            document.write(new Date().getFullYear())
                        </script>
                        <a href="http://www.creative-tim.com/">Creative Tim</a>, made with love for a better web
                    </p>
                </div>
            </footer> -->
        </div>
    </div>
</body>

<?php include "footer.php";
if($page == "acara"){?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
<?php
}
 ?>


<script type="text/javascript">
    function suksesAlert(pesan){
        swal({
        title: pesan,
        type: "success"
        }).then(function() {
          refreshList();
        });
    }

    function errorAlert(pesan){
        swal({
        title: pesan,
        type: "warning"
        }).then(function() {
        });
    }
    $(document).ready(function() {


				loadTable();
        $('.card .material-datatables label').addClass('form-group');


    });
</script>
</html>
