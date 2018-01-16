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
          title: 'Yakin Hapus Data ?',
          text: '',
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya',
          cancelButtonText: 'Tidak'
        }).then((result) => {
          if (result.value) {
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
          } else if (result.dismiss === 'cancel') {
          }
        })

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
    $.ajax({
      type:'POST',
      data : {
              namaAcara : $("#namaAcara").val(),
              tanggalAcara : $("#tanggalAcara").val(),
              jamAcara : $("#jamAcara").val(),
              kuotaAcara : $("#kuotaAcara").val(),
              lamaAcara : $("#lamaAcara").val(),
              hargaTiket : $("#hargaTiket").val(),
              hargaKamar : $("#hargaKamar").val(),
              hargaExtraBed : $("#hargaExtraBed").val(),
              deadlinePembayaran : $("#deadlinePembayaran").val(),
              lokasiAcara : $("#lokasiAcara").val(),
              deskripsiAcara : getEditorContent(),
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
  }
function saveEditAcara(idEdit){
  $.ajax({
    type:'POST',
    data : {
            namaAcara : $("#namaAcara").val(),
            tanggalAcara : $("#tanggalAcara").val(),
            jamAcara : $("#jamAcara").val(),
            kuotaAcara : $("#kuotaAcara").val(),
            lamaAcara : $("#lamaAcara").val(),
            hargaTiket : $("#hargaTiket").val(),
            hargaKamar : $("#hargaKamar").val(),
            hargaExtraBed : $("#hargaExtraBed").val(),
            deadlinePembayaran : $("#deadlinePembayaran").val(),
            lokasiAcara : $("#lokasiAcara").val(),
            deskripsiAcara : getEditorContent(),
            idEdit : idEdit
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
