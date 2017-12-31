function saveUser(){
  $("#LoadingImage").attr('style','display:block');
  $.ajax({
    type:'POST',
    data : $("#formUser").serialize(),
    url: url+'&tipe=saveUser',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
          $("#LoadingImage").hide();
          refreshList();
        }else{
          // alert(resp.err);
          swal({
            position: 'top-right',
            type: 'warning',
            title: (resp.err),
            showConfirmButton: true,
            timer: 5000
          });
          $("#LoadingImage").hide();
        }
      }
  });
}

function refreshList(){
    window.location.reload();
}

function loadTable(){
  $.ajax({
    type:'POST',

    url: url+'&tipe=loadTable',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
          $("#datatables").html(resp.content.tabelUser);
          $('#datatables').DataTable({
              "pagingType": "full_numbers",
              "lengthMenu": [
                  [10, 25, 50, -1],
                  [10, 25, 50, "All"]
              ],
              responsive: true,
              language: {
                  search: "_INPUT_",
                  searchPlaceholder: "Search records",
              }

          });
        }else{
          alert(resp.err);
        }
      }
  });
}


function deleteUser(id){
  $.ajax({
    type:'POST',
    data : {id : id},
    url: url+'&tipe=deleteUser',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
          refreshList();
        }else{
          alert(resp.err);
        }
      }
  });
}
function clearTemp(){
  $("#data2").text("Baru");
  $("#data2").click();
}
function baruUser(){

          $("#divForUsername").attr("class","form-group label-floating ");
          $("#divForPassword").attr("class","form-group label-floating ");
          $("#divForEmail").attr("class","form-group label-floating ");
          $("#divForNama").attr("class","form-group label-floating ");
          $("#divForTelepon").attr("class","form-group label-floating ");
          $("#divForAlamat").attr("class","form-group label-floating ");
          $("#divForInstansi").attr("class","form-group label-floating ");
          $("#usernameUser").val("");
          $("#passwordUser").val("");
          $("#emailUser").val("");
          $("#namaUser").val("");
          $("#teleponUser").val("");
          $("#alamatUser").text("");
          $("#instansiUser").val("");
          $("#statusUser").val("1");
          $("#buttonSubmit").attr("onclick","saveUser()");

}
function updateUser(id){
  $("#LoadingImage").attr('style','display:block');
  $.ajax({
    type:'POST',
    data : {id : id},
    url: url+'&tipe=updateUser',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
          $("#LoadingImage").hide();
          $("#data2").text("Edit");
          $("#data2").click();
          $("#divForUsername").attr("class","form-group label-floating is-focused");
          $("#divForPassword").attr("class","form-group label-floating is-focused");
          $("#divForEmail").attr("class","form-group label-floating is-focused");
          $("#divForNama").attr("class","form-group label-floating is-focused");
          $("#divForTelepon").attr("class","form-group label-floating is-focused");
          $("#divForAlamat").attr("class","form-group label-floating is-focused");
          $("#divForInstansi").attr("class","form-group label-floating is-focused");
          $("#usernameUser").val(resp.content.usernameUser);
          $("#passwordUser").val(resp.content.passwordUser);
          $("#emailUser").val(resp.content.emailUser);
          $("#namaUser").val(resp.content.namaUser);
          $("#teleponUser").val(resp.content.teleponUser);
          $("#alamatUser").text(resp.content.alamatUser);
          $("#instansiUser").val(resp.content.instansiUser);
          $("#statusUser").html(resp.content.statusUser);
          $("#buttonSubmit").attr("onclick","saveEditUser("+id+")");
        }else{
          // alert(resp.err);
          swal({
            position: 'top-right',
            type: 'warning',
            title: (resp.err),
            showConfirmButton: true,
            timer: 5000
          });
          $("#LoadingImage").hide();
        }
      }
  });
}


function saveEditUser(idEdit){
  $("#LoadingImage").attr('style','display:block');
  $.ajax({
    type:'POST',
    data : $("#formUser").serialize()+"&idEdit="+idEdit,
    url: url+'&tipe=saveEditUser',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
          $("#LoadingImage").hide();
          refreshList();
        }else{
          
          // alert(resp.err);
          swal({
            position: 'top-right',
            type: 'warning',
            title: (resp.err),
            showConfirmButton: true,
            timer: 5000
          });
          $("#LoadingImage").hide();
        }
      }
  });
}
