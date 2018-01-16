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
          } else if (result.dismiss === 'cancel') {
          }
        })

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
    $.ajax({
      type:'POST',
      data : {
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
      },
      url: url+'&tipe=saveLowonganKerja',
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
function saveEditLowonganKerja(idEdit){
  $.ajax({
    type:'POST',
    data : {
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
            idEdit : idEdit
    },
    url: url+'&tipe=saveEditLowonganKerja',
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
