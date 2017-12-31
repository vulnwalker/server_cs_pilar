function saveSlider(){
  $("#LoadingImage").attr('style','display:block');
  $.ajax({
    type:'POST',
    data : {
        statusPublish : $("#statusPublish").val(),
        gambarSlider : $("#gambarSlider").val(),
        namaSlider : $("#namaSlider").val(),
    },
    url: url+'&tipe=saveSlider',
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
          $("#datatables").html(resp.content.tabelSlider);
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


function deleteSlider(id){
  $.ajax({
    type:'POST',
    data : {id : id},
    url: url+'&tipe=deleteSlider',
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

function baruSlider(){

          $("#formSliderBaru").modal();
          $("#buttonSubmit").attr("onclick","saveSlider()");

}
function updateSlider(id){
  $("#LoadingImage").attr('style','display:block');
  $.ajax({
    type:'POST',
    data : {id : id},
    url: url+'&tipe=updateSlider',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
          $("#LoadingImage").hide();
          $("#formSliderBaru").modal();
          $("#namaSlider").val(resp.content.namaSlider);
          $("#statusPublish").val(resp.content.statusPublish);
          $("#tempImage").attr('src',resp.content.gambarSlider);
          $("#gambarSlider").val(resp.content.baseImage);
          $("#buttonSubmit").attr("onclick","saveEditSlider("+id+")");
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


function saveEditSlider(idEdit){
  $("#LoadingImage").attr('style','display:block');
  $.ajax({
    type:'POST',
    data : {
        statusPublish : $("#statusPublish").val(),
        gambarSlider : $("#gambarSlider").val(),
        namaSlider : $("#namaSlider").val(),
        idEdit : idEdit,
    },
    url: url+'&tipe=saveEditSlider',
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

function imageChanged(){
  var me= this;
  var filesSelected = document.getElementById("imageSlider").files;
  if (filesSelected.length > 0)
  {
    var fileToLoad = filesSelected[0];

    var fileReader = new FileReader();

    fileReader.onload = function(fileLoadedEvent)
    {
      var textAreaFileContents = document.getElementById
      (
        "gambarSlider"
      );

      textAreaFileContents.value = fileLoadedEvent.target.result;
    };

    fileReader.readAsDataURL(fileToLoad);
  }
}
