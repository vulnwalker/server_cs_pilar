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
  window.location = "pages.php?page=informasi" ;
}
function Baru(){
  window.location = "pages.php?page=informasi&action=baru" ;
}
function Batal(){
  window.location = "pages.php?page=informasi" ;
}
function Edit(){
  var errMsg = getJumlahChecked("informasi");
  if(errMsg == ''){
    $.ajax({
      type:'POST',
      data : $("#formInformasi").serialize(),
      url: url+'&tipe=Edit',
      success: function(data) {
        var resp = eval('(' + data + ')');
        if(resp.err==''){
          window.location = "pages.php?page=informasi&action=edit&idEdit="+resp.content.idEdit;
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
  var errMsg = getJumlahChecked("informasi");
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
              data : $("#formInformasi").serialize(),
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
    var editors = textboxio.get('#isiInformasi');
    var editor = editors[0];
    return editor.content.get();
}
function saveInformasi(){
    $.ajax({
      type:'POST',
      data : {
              statusPublish : $("#statusPublish").val(),
              judulInformasi : $("#judulInformasi").val(),
              isiInformasi : getEditorContent(),
      },
      url: url+'&tipe=saveInformasi',
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
function saveEditInformasi(idEdit){
  $.ajax({
    type:'POST',
    data : {
          statusPublish : $("#statusPublish").val(),
          judulInformasi : $("#judulInformasi").val(),
          isiInformasi : getEditorContent(),
          idEdit : idEdit
    },
    url: url+'&tipe=saveEditInformasi',
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

function priview(id){
  var win = window.open("http://pilar.web.id/?page=informasi&id="+id, '_blank');
  win.focus();
}
