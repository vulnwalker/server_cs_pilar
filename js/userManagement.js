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
function limitData(){
  $(".fixed").remove();
  loadTable(1,$("#jumlahDataPerhalaman").val());
}
function currentPage(pageKE){
  $(".fixed").remove();

  loadTable(pageKE,$("#jumlahDataPerhalaman").val());
}
function refreshList(){
  window.location = "pages.php?page=userManagement" ;
}
function Baru(){
  window.location = "pages.php?page=userManagement&action=baru" ;
}
function Batal(){
  window.location = "pages.php?page=userManagement" ;
}
function Edit(){
  var errMsg = getJumlahChecked("userManagement");
  if(errMsg == ''){
    $.ajax({
      type:'POST',
      data : $("#formUserManagement").serialize(),
      url: url+'&tipe=Edit',
      success: function(data) {
        var resp = eval('(' + data + ')');
        if(resp.err==''){
          window.location = "pages.php?page=userManagement&action=edit&idEdit="+resp.content.idEdit;
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
  var errMsg = getJumlahChecked("userManagement");
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
              data : $("#formUserManagement").serialize(),
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
function saveUser(){
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
                  statusUser : $("#statusUser").val(),
                  usernameUser : $("#usernameUser").val(),
                  passwordUser : $("#passwordUser").val(),
                  emailUser : $("#emailUser").val(),
                  namaUser : $("#namaUser").val(),
                  alamatUser : $("#alamatUser").val(),
                  instansiUser : $("#instansiUser").val(),
                  teleponUser : $("#teleponUser").val(),
                  hakAkses : $("#hakAkses").val(),
          },
          url: url+'&tipe=saveUser',
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
function saveEditUser(idEdit){
  // $("#LoadingImage").attr('style','display:block');
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
        //data : $("#formUser").serialize()+"&idEdit="+idEdit,
        data : {
                statusUser : $("#statusUser").val(),
                usernameUser : $("#usernameUser").val(),
                passwordUser : $("#passwordUser").val(),
                emailUser : $("#emailUser").val(),
                namaUser : $("#namaUser").val(),
                alamatUser : $("#alamatUser").val(),
                instansiUser : $("#instansiUser").val(),
                teleponUser : $("#teleponUser").val(),
                hakAkses : $("#hakAkses").val(),
                idEdit : idEdit,
        },
        url: url+'&tipe=saveEditUser',
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
