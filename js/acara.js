function descChanged(){
  $("#ascHidden").val('desc');
  $('.active-tick').click();
  $('#turun').addClass('active-tick2').siblings().removeClass('active-tick2');
}
function ascChanged(){
  $("#ascHidden").val('');
  $('.active-tick').click();
  $('#naik').addClass('active-tick2').siblings().removeClass('active-tick2');
}
function sortData(sorter){
  $(sorter).addClass('active-tick').siblings().removeClass('active-tick');
  $(".fixed").remove();
  $.ajax({
    type:'POST',
    data : {
      limitTable : $("#jumlahDataPerhalaman").val(),
      pageKe : $(".active").text(),
      searchData : $("#searchData").val(),
      sorter : sorter.id,
      ascending : $("#ascHidden").val()
    },
    url: url+'&tipe=loadTable',
    success: function(data) {
      var resp = eval('(' + data + ')');
      if(resp.err==''){
        $("#tabelBody").html(resp.content.tabelBody);
        $("#tabelFooter").html(resp.content.tabelFooter);
        $("table").fixMe();

      }else{
        alert(resp.err);
      }
    }
  });
}
function sortDataPendaftaran(sorter){
  $(sorter).addClass('active-tick').siblings().removeClass('active-tick');
  $(".fixed").remove();
  $.ajax({
    type:'POST',
    data : {
      limitTable : $("#jumlahDataPerhalaman").val(),
      pageKe : $(".active").text(),
      searchData : $("#searchData").val(),
      sorter : sorter.id,
      ascending : $("#ascHidden").val(),
      idAcara : $("#hiddenIdAcara").val()
    },
    url: url+'&tipe=loadTablePendaftaran',
    success: function(data) {
      var resp = eval('(' + data + ')');
      if(resp.err==''){
        $("#tabelPendaftaran").html(resp.content.tabelPendaftaran);
        $("#tabelFooter").html(resp.content.tabelFooter);
        $("table").fixMe();

      }else{
        alert(resp.err);
      }
    }
  });
}
function filterStatus(sorter){
  $(sorter).addClass('active-tick').siblings().removeClass('active-tick');
  $(".fixed").remove();
  $.ajax({
    type:'POST',
    data : {
      limitTable : $("#jumlahDataPerhalaman").val(),
      pageKe : $(".active").text(),
      searchData : $("#searchData").val(),
      statusPendaftaran : sorter.id,
      ascending : $("#ascHidden").val(),
      idAcara : $("#hiddenIdAcara").val()
    },
    url: url+'&tipe=loadTablePendaftaran',
    success: function(data) {
      var resp = eval('(' + data + ')');
      if(resp.err==''){
        $("#tabelPendaftaran").html(resp.content.tabelPendaftaran);
        $("#tabelFooter").html(resp.content.tabelFooter);
        $("table").fixMe();

      }else{
        alert(resp.err);
      }
    }
  });
}
function loadTable(pageKe,limitTable){
  $.ajax({
    type:'POST',
    data : {
      limitTable : limitTable,
      pageKe : pageKe,
      searchData : $("#searchData").val()
    },
    url: url+'&tipe=loadTable',
    success: function(data) {
      var resp = eval('(' + data + ')');
      if(resp.err==''){
        $("#tabelBody").html(resp.content.tabelBody);
        $("#tabelFooter").html(resp.content.tabelFooter);
        $("table").fixMe();

      }else{
        alert(resp.err);
      }
    }
  });
}
function limitData(){
  $(".fixed").remove();
  loadTable(1,$("#jumlahDataPerhalaman").val());
}
function limitDataPendaftaran(){
  $(".fixed").remove();
  loadTablePendaftaran(1,$("#jumlahDataPerhalaman").val(),$("#hiddenIdAcara").val());
}
function currentPage(pageKE){
  $(".fixed").remove();
  loadTable(pageKE,$("#jumlahDataPerhalaman").val());
}
function refreshList(){
  window.location = "pages.php?page=acara" ;
}
function Baru(){
  window.location = "pages.php?page=acara&action=baru" ;
}
function Batal(){
  window.location = "pages.php?page=acara" ;
}
function Edit(){
  var errMsg = getJumlahChecked("acara");
  if(errMsg == ''){
    $.ajax({
      type:'POST',
      data : $("#formAcara").serialize(),
      url: url+'&tipe=Edit',
      success: function(data) {
        var resp = eval('(' + data + ')');
        if(resp.err==''){
          window.location = "pages.php?page=acara&action=edit&idEdit="+resp.content.idEdit;
        }else{
          errorAlert(resp.err);
        }
      }
    });
  }else{
    errorAlert(errMsg);
  }
}

function Hapus(){
  var errMsg = getJumlahChecked("acara");
  if(errMsg == '' || errMsg=='Pilih hanya satu data'){
    swal({
          title: "Hapus Data ?",
          text: "",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "Ya",
          cancelButtonText: "Tidak",
          closeOnConfirm: false
        },
        function(){
          $.ajax({
              type:'POST',
              data : $("#formAcara").serialize(),
              url: url+'&tipe=Hapus',
              success: function(data) {
                var resp = eval('(' + data + ')');
                if(resp.err==''){
                  suksesAlert("Data Terhapus");
                }else{
                  errorAlert(resp.err);
                }
              }
            });
        });

    }else{
      errorAlert(errMsg);
    }
  }
  function getEditorContent(){
     var editors = textboxio.get('#deskripsiAcara');
     var editor = editors[0];
     return editor.content.get();
 }
function saveAcara(){
  swal({
      title: "Simpan Data ?",
      text: "",
      type: "info",
      confirmButtonText: "Ya",
      cancelButtonText: "Tidak",
      showCancelButton: true,
      closeOnConfirm: false,
      showLoaderOnConfirm: true
    }, function () {
      $.ajax({
        type:'POST',
        data : {
                namaAcara : $("#namaAcara").val(),
                tanggalAcara : $("#tanggalAcara").val(),
                jamAcara : $("#jamAcara").val(),
                tanggalAcaraSelesai : $("#tanggalAcaraSelesai").val(),
                jamAcaraSelesai : $("#jamAcaraSelesai").val(),
                kuotaAcara : $("#kuotaAcara").val(),
                hargaTiket : $("#hargaTiket").val(),
                deadlinePembayaran : $("#deadlinePembayaran").val(),
                lokasiAcara : $("#lokasiAcara").val(),
                deskripsiAcara : getEditorContent(),
                kordinatX : $("#kordinatX").val(),
                kordinatY : $("#kordinatY").val(),
                baseUndangan : $("#baseUndangan").val(),
                namaFile : $("#fileUndangan").val(),
                baseImageTitle : crop,
                tempKordinat : $("#tempKordinat").val(),
                statusPublish : $("#statusPublish").val(),
        },
        url: url+'&tipe=saveAcara',
          success: function(data) {
          var resp = eval('(' + data + ')');
            if(resp.err==''){
              suksesAlert("Data Tersimpan");
            }else{
              errorAlert(resp.err);
            }
          }
      });
    });

  }
function saveEditAcara(idEdit){
  swal({
      title: "Simpan Data ?",
      text: "",
      type: "info",
      confirmButtonText: "Ya",
      cancelButtonText: "Tidak",
      showCancelButton: true,
      closeOnConfirm: false,
      showLoaderOnConfirm: true
    }, function () {
      $.ajax({
        type:'POST',
        data : {
                namaAcara : $("#namaAcara").val(),
                tanggalAcara : $("#tanggalAcara").val(),
                jamAcara : $("#jamAcara").val(),
                tanggalAcaraSelesai : $("#tanggalAcaraSelesai").val(),
                jamAcaraSelesai : $("#jamAcaraSelesai").val(),
                kuotaAcara : $("#kuotaAcara").val(),
                hargaTiket : $("#hargaTiket").val(),
                deadlinePembayaran : $("#deadlinePembayaran").val(),
                lokasiAcara : $("#lokasiAcara").val(),
                deskripsiAcara : getEditorContent(),
                kordinatX : $("#kordinatX").val(),
                kordinatY : $("#kordinatY").val(),
                baseUndangan : $("#baseUndangan").val(),
                namaFile : $("#fileUndangan").val(),
                baseImageTitle : crop,
                tempKordinat : $("#tempKordinat").val(),
                idEdit : idEdit,
                statusPublish : $("#statusPublish").val()
        },
        url: url+'&tipe=saveEditAcara',
          success: function(data) {
          var resp = eval('(' + data + ')');
            if(resp.err==''){
              suksesAlert("Data Tersimpan");
            }else{
              errorAlert(resp.err);
            }
          }
      });
    });

}
function setMenuEdit(statusMenu){
  $.ajax({
    type:'POST',
    data : {statusMenu : statusMenu},
    url: url+'&tipe=setMenuEdit',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
          $("#actionArea").html(resp.content.header);
          $("#filterinTable").html(resp.content.filterinTable);
        }else{
          alert(resp.err);
        }
      }
  });
}

function pendaftaran(id){
  window.location = "pages.php?page=acara&action=pendaftaran&idAcara="+id;
}


function loadTablePendaftaran(pageKe,limitTable,idAcara){
  $.ajax({
    type:'POST',
    data : {
      limitTable : limitTable,
      pageKe : pageKe,
      searchData : $("#searchData").val(),
      idAcara : idAcara
    },
    url: url+'&tipe=loadTablePendaftaran',
    success: function(data) {
      var resp = eval('(' + data + ')');
      if(resp.err==''){
        $("#tabelPendaftaran").html(resp.content.tabelPendaftaran);
        $("#tabelFooter").html(resp.content.tabelFooter);
        $("table").fixMe();
      }else{
        alert(resp.err);
      }
    }
  });
}

function konfirmasiPendaftaran(){
  var errMsg = getJumlahChecked("acara");
  if(errMsg == ''){
    $.ajax({
      type:'POST',
      data : $("#formAcara").serialize(),
      url: url+'&tipe=konfirmasiPendaftaran',
      success: function(data) {
        var resp = eval('(' + data + ')');
        if(resp.err==''){
          $("#contentModal").html(resp.content.contentModal);
          $("#buttonSave").attr('onclick','savePendaftaran('+resp.content.id+')');
          $("#pemicuPopup").click();
        }else{
          errorAlert(resp.err);
        }
      }
    });
  }else{
    errorAlert(errMsg);
  }
}
function konfirmasiPendaftaran(){
  var errMsg = getJumlahChecked("acara");
  if(errMsg == ''){
    $.ajax({
      type:'POST',
      data : $("#formAcara").serialize(),
      url: url+'&tipe=konfirmasiPendaftaran',
      success: function(data) {
        var resp = eval('(' + data + ')');
        if(resp.err==''){
          $("#contentModal").html(resp.content.contentModal);
          $("#buttonSave").attr('onclick','savePendaftaran('+resp.content.id+')');
          $("#pemicuPopup").click();
        }else{
          errorAlert(resp.err);
        }
      }
    });
  }else{
    errorAlert(errMsg);
  }
}

function savePendaftaran(idPendaftaran){
  $.ajax({
    type:'POST',
    data : {
            statusPendaftaran : $("#statusPendaftaran").val(),
            idEdit : idPendaftaran
    },
    url: url+'&tipe=savePendaftaran',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
          suksesAlertPendaftaran("Data Tersimpan");
        }else{
          errorAlert(resp.err);
        }
      }
  });
}

function suksesAlertPendaftaran(pesan){
      swal({
      title: pesan,
      type: "success"
      }).then(function() {
          window.location.reload();
      });
  }


  function konfirmasiPembayaran(id){
    var modal = document.getElementById('myModalImage');
  var img = document.getElementById('myImg');
  var modalImg = document.getElementById("img01");
  var captionText = document.getElementById("captionImage");
    $.ajax({
      type:'POST',
      data : {id : id},
      url: url+'&tipe=konfirmasiPembayaran',
      success: function(data) {
        var resp = eval('(' + data + ')');
          modal.style.display = "block";
          modalImg.src = resp.content.baseImage;
          captionText.innerHTML = resp.content.caption;
      }
    });





  }

  function closeImage(){
    var span = document.getElementsByClassName("close")[0];
      var modal = document.getElementById('myModalImage');
        modal.style.display = "none";
  }


  function getAlamat(koordinat){
     $("#tempKordinat").val(koordinat);
    //  alert($("#tempKordinat").val());
    $.ajax({
      type:'POST',
      data : {
                koordinat : $("#tempKordinat").val()
              },
      url: url+'&tipe=generateLocation',
        success: function(data) {
        var resp = eval('(' + data + ')');
          if(resp.err==''){

               $("#lokasiAcara").val(resp.content.lokasi);
              $("#kordinatX").val(resp.content.lat);
              $("#kordinatY").val(resp.content.lang);
          }else{
            alert(resp.err);
          }
        }
    });
  }

  function imageChanged(){
    var me= this;
    var filesSelected = document.getElementById("imageAcara").files;
    if (filesSelected.length > 0)
    {
      var fileToLoad = filesSelected[0];
      var fileReader = new FileReader();
      fileReader.onload = function(fileLoadedEvent)
      {
        $("#gambarAcara").attr('src',fileLoadedEvent.target.result);
        resizeableImage($('#gambarAcara'));
        $('.component').show();
        // $("#statusKosong").val('1');
      };

      fileReader.readAsDataURL(fileToLoad);
    }
  }
  function undanganChanged(){
    var me= this;
    var filesSelected = document.getElementById("fileUndangan").files;
    if (filesSelected.length > 0)
    {
      var fileToLoad = filesSelected[0];
      var fileReader = new FileReader();
      fileReader.onload = function(fileLoadedEvent)
      {
        $("#baseUndangan").val(fileLoadedEvent.target.result);

      };
      fileReader.readAsDataURL(fileToLoad);
    }
  }
