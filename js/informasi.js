function saveInformasi(){
  $.ajax({
    type:'POST',
    data : {
      isiInformasi : $("#summernote").code(),
      judulInformasi : $("#judulInformasi").val(),
      statusPublish : $("#statusPublish").val(),
      posisiInformasi : $('input[name=posisiInformasi]:checked').val(),
    },
    url: url+'&tipe=saveInformasi',
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
          $("#datatables").html(resp.content.tabelInformasi);
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


function deleteInformasi(id){
  $.ajax({
    type:'POST',
    data : {id : id},
    url: url+'&tipe=deleteInformasi',
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
function baruInformasi(){

          // $("#formInformasiBaru").modal();
          $("#judulInformasi").val("");

          $("#kiri").attr("checked",true);

          $("#summernote").code("");
          $("#buttonSubmit").attr("onclick","saveInformasi()");

}
function updateInformasi(id){
  $.ajax({
    type:'POST',
    data : {id : id},
    url: url+'&tipe=updateInformasi',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
          $("#data2").text("Edit");
          $("#data2").click();
          $("#judulInformasi").val(resp.content.judulInformasi);
          $("#statusPublish").val(resp.content.statusPublish);
          if(resp.content.posisi == "1"){
            $("#kiri").attr("checked",true);
          }else{
            $("#kanan").attr("checked",true);
          }
          $("#summernote").code(resp.content.isiInformasi);
          $("#buttonSubmit").attr("onclick","saveEditInformasi("+id+")");
          // $("#isiInformasi").val(resp.content.isiInformasi);
        }else{
          alert(resp.err);
        }
      }
  });
}


function saveEditInformasi(idEdit){
  $.ajax({
    type:'POST',
    data : {
            idEdit : idEdit,
            isiInformasi : $("#summernote").code(),
            judulInformasi : $("#judulInformasi").val(),
            statusPublish : $("#statusPublish").val(),
            posisiInformasi : $('input[name=posisiInformasi]:checked').val(),
    },
    url: url+'&tipe=saveEditInformasi',
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
