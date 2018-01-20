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
function loadTableLamaran(pageKe,limitTable,idLowongan){
  $.ajax({
    type:'POST',
    data : {
      limitTable : limitTable,
      pageKe : pageKe,
      searchData : $("#searchData").val(),
      idLowongan : idLowongan
    },
    url: url+'&tipe=loadTableLamaran',
    success: function(data) {
      var resp = eval('(' + data + ')');
      if(resp.err==''){
        $("#tabelLamaran").html(resp.content.tabelLamaran);
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
  window.location = "pages.php?page=lowonganKerja" ;
}
function Baru(){
  window.location = "pages.php?page=lowonganKerja&action=baru" ;
}
function Batal(){
  window.location = "pages.php?page=lowonganKerja" ;
}
function Edit(){
  var errMsg = getJumlahChecked("lowonganKerja");
  if(errMsg == ''){
    $.ajax({
      type:'POST',
      data : $("#formLowonganKerja").serialize(),
      url: url+'&tipe=Edit',
      success: function(data) {
        var resp = eval('(' + data + ')');
        if(resp.err==''){
          window.location = "pages.php?page=lowonganKerja&action=edit&idEdit="+resp.content.idEdit;
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
  var errMsg = getJumlahChecked("lowonganKerja");
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
              data : $("#formLowonganKerja").serialize(),
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
     var editors = textboxio.get('#deskripsiLowongan');
     var editor = editors[0];
     return editor.content.get();
 }
function saveLowonganKerja(){
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
                  statusPublish : $("#statusPublish").val(),
                  judulLowongan : $("#judulLowongan").val(),
                  posisiLowongan : $("#posisiLowongan").val(),
                  pendidikan : $("#pendidikan").val(),
                  jenisKelamin : $("#jenisKelamin").val(),
                  jamKerja : $("#jamKerja").val(),
                  usiaMinimal : $("#usiaMinimal").text(),
                  usiaMaximal : $("#usiaMaximal").text(),
                  pengalamanMinimal : $("#pengalamanMinimal").text(),
                  pengalamanMaximal : $("#pengalamanMaximal").text(),
                  salaryMinimum : $("#salaryMinimum").val(),
                  salaryMaximum : $("#salaryMaximum").val(),
                  spesifikasi : $("#spesifikasi").val(),
                  deskripsiLowongan : getEditorContent(),
                  baseImageTitle : crop
          },
          url: url+'&tipe=saveLowonganKerja',
            success: function(data) {
            // $("#LoadingImage").hide();
            var resp = eval('(' + data + ')');
              if(resp.err==''){
                suksesAlert("Data Tersimpan");
              }else{
                errorAlert(resp.err);
              }
            }
        });
      });
    // $("#LoadingImage").attr('style','display:block');

  }
function saveEditLowonganKerja(idEdit){
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
                  statusPublish : $("#statusPublish").val(),
                  judulLowongan : $("#judulLowongan").val(),
                  posisiLowongan : $("#posisiLowongan").val(),
                  pendidikan : $("#pendidikan").val(),
                  jenisKelamin : $("#jenisKelamin").val(),
                  jamKerja : $("#jamKerja").val(),
                  usiaMinimal : $("#usiaMinimal").text(),
                  usiaMaximal : $("#usiaMaximal").text(),
                  pengalamanMinimal : $("#pengalamanMinimal").text(),
                  pengalamanMaximal : $("#pengalamanMaximal").text(),
                  salaryMinimum : $("#salaryMinimum").val(),
                  salaryMaximum : $("#salaryMaximum").val(),
                  spesifikasi : $("#spesifikasi").val(),
                  deskripsiLowongan : getEditorContent(),
                  baseImageTitle : crop,
                  idEdit : idEdit
          },
          url: url+'&tipe=saveEditLowonganKerja',
            success: function(data) {
            // $("#LoadingImage").hide();
            var resp = eval('(' + data + ')');
              if(resp.err==''){
                suksesAlert("Data Tersimpan");
              }else{
                errorAlert(resp.err);
              }
            }
        });
      });
  // $("#LoadingImage").attr('style','display:block');

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

function lamaran(id){
      window.location = "pages.php?page=lowonganKerja&action=lamaran&idLowongan="+id;
}

function downloadCV(id){
  $.ajax({
    type:'POST',
    data:{id : id},
    url: url+'&tipe=downloadCV',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
          var win = window.open(resp.content.cv, '_blank');
          win.focus();
        }else{
          alert(resp.err);
        }
      }
  });
}
function imageChanged(){
  var me= this;
  var filesSelected = document.getElementById("imageSlider").files;
  if (filesSelected.length > 0)
  {
    var fileToLoad = filesSelected[0];
    var fileReader = new FileReader();
    fileReader.onload = function(fileLoadedEvent)
    {
      $("#gambarSlider").attr('src',fileLoadedEvent.target.result);
      resizeableImage($('#gambarSlider'));
      $('.component').show();
      $("#statusKosong").val('1');
    };

    fileReader.readAsDataURL(fileToLoad);
  }
}
