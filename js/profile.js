function saveChangePassword(){
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
                    oldPassword : $("#oldPassword").val(),
                    newPassword : $("#newPassword").val(),
                    confirmPassword : $("#confirmPassword").val(),
                  },
          url: url+'&tipe=saveProfile',
            success: function(data) {
            var resp = eval('(' + data + ')');
              if(resp.err==''){
                suksesAlert("Password diubah");
              }else{
                errorAlert(resp.err);
              }
            }
        });
      });

}

function refreshList(){
    window.location.reload();
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
