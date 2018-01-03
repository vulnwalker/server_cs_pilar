<?php
include "include/config.php";
session_start();
if ($_SESSION['status'] != "login") {
    header("location:index.php");
}
 ?>
<!-- <!DOCTYPE html> -->
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
        .carousel .carousel-control.left, .carousel .carousel-control.right{
            background-image: none;
        }
        .carousel .right{
            cursor: url(assets/img/arrow-right.png), url(assets/img/arrow-right.cur), default !important;
        }
        .carousel .left{
            cursor: url(assets/img/arrow-left.png), url(assets/img/arrow-left.cur), default !important;
        }
        .carousel .carousel-control{
            width: 50%;
        }
        .carousel-control:hover, .carousel-control:focus{
            outline: 0;
            color: #fff;
            text-decoration: none;
            opacity: .9;
            filter: alpha(opacity=90);
        }
        .carousel-control.right{
            left: auto;
            right: 0;
            background-image: -webkit-linear-gradient(left, rgba(0,0,0,0.0001) 0, rgba(0,0,0,0.5) 100%);
            background-image: -o-linear-gradient(left, rgba(0,0,0,0.0001) 0, rgba(0,0,0,0.5) 100%);
            background-image: -webkit-gradient(linear, left top, right top, color-stop(0, rgba(0,0,0,0.0001)), to(rgba(0,0,0,0.5)));
            background-image: linear-gradient(to right, rgba(0,0,0,0.0001) 0, rgba(0,0,0,0.5) 100%);
            background-repeat: repeat-x;
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#00000000', endColorstr='#80000000', GradientType=1);
        }
        .carousel-control.left{
            background-image: -webkit-linear-gradient(left, rgba(0,0,0,0.5) 0, rgba(0,0,0,0.0001) 100%);
            background-image: -o-linear-gradient(left, rgba(0,0,0,0.5) 0, rgba(0,0,0,0.0001) 100%);
            background-image: -webkit-gradient(linear, left top, right top, color-stop(0, rgba(0,0,0,0.5)), to(rgba(0,0,0,0.0001)));
            background-image: linear-gradient(to right, rgba(0,0,0,0.5) 0, rgba(0,0,0,0.0001) 100%);
            background-repeat: repeat-x;
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#80000000', endColorstr='#00000000', GradientType=1);
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
}elseif ($page == "team") {
    include 'pages/team.php';
}elseif ($page == "profile") {
    include 'pages/profile.php';
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

 ?>

<script type="text/javascript">
    function suksesAlert(pesan){
        swal({
        title: pesan,
        type: "success"
        }).then(function() {
          <?php
            if($_GET['action'] == 'confirm'){
              ?>
              window.location.reload();
              <?php
            }else{
              ?>
                refreshList();
              <?php
            }
            ?>

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
        <?php
          if($_GET['action'] !='confirm'){
          ?>
				   loadTable();
           <?php
         }else{
           ?>
           loadKonfirmasi(<?php echo $_GET['idAcara'] ?>);
           <?php
         }
          ?>
        $('.card .material-datatables label').addClass('form-group');
        <?php
            if($_GET['page'] == 'produk' && isset($_GET['edit'])){
              $getDataEdit = sqlArray(sqlQuery("select * from produk where id='".$_GET['edit']."'"));
              //clearDirectory("temp/".$_SESSION['username']);
              $decodedJSON = json_decode($getDataEdit['screen_shot']);
              for ($i=0; $i < sizeof($decodedJSON) ; $i++) {
                  $explodeNamaGambar = explode('/',$decodedJSON[$i]->fileName);
                  $jsonScreenshot[] = array(
                            'name' => $explodeNamaGambar[3],
                            'size' => filesize("temp/".$_SESSION['username']."/".$explodeNamaGambar[3]),
                            'type' => 'image/jpeg',
                            'imageLocation' => "temp/".$_SESSION['username']."/".$explodeNamaGambar[3],
                  );;
              }
                ?>
                Dropzone.autoDiscover = false;
                var myDropzone = new Dropzone("#dropzone", {
                    url: "upload.php",
                    maxFileSize: 50,
                    acceptedFiles: ".jpeg,.jpg,.png,.gif",
                    addRemoveLinks: true,
                    init: function() {
                        this.on("complete", function(file) {
                            $(".dz-remove").html("<div><span class='fa fa-trash text-danger' style='font-size: 1.5em;cursor:pointer;' >REMOVE</span></div>");
                            // $(".dz-details").attr("onclick","deskripsiScreenShot('"+file.name+"')");
                            $(".dz-details").attr("style","cursor:pointer;");
                        });
                        this.on("thumbnail", function(file) {
                          console.log(file); // will send to console all available props
                          file.previewElement.addEventListener("click", function() {
                             deskripsiScreenShot(file.name);
                          });
                      });
                        this.on("removedfile", function(file) {
                             removeTemp(file.name);
                      });
                    }

                });
                $("#dropzone").attr('class','dropzone dz-clickable');
                var existingFiles = <?php echo json_encode($jsonScreenshot) ?>;
                for (i = 0; i < existingFiles.length; i++) {
                    myDropzone.emit("addedfile", existingFiles[i]);
                    myDropzone.emit("thumbnail", existingFiles[i], existingFiles[i].imageLocation);
                    myDropzone.emit("complete", existingFiles[i]);
                }
                <?php
            }
        ?>z
    });
</script>
</html>
