function saveProduk(){
  $("#LoadingImage").attr('style','display:block');
  $.ajax({
    type:'POST',
    data : {
        statusPublish : $("#statusPublish").val(),
        gambarProduk : $("#gambarProduk").val(),
        namaProduk : $("#namaProduk").val(),
        deskripsiProduk : $("#summernote").code(),
    },
    url: url+'&tipe=saveProduk',
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
          $("#datatables").html(resp.content.tabelProduk);
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
          // $("#dropzone").attr('class','dropzone dz-clickable');
          //     Dropzone.autoDiscover = false;
          //         var myDropzone = new Dropzone("#dropzone", {
          //             url: "upload.php",
          //             maxFileSize: 50,
          //             acceptedFiles: ".jpeg,.jpg,.png,.gif",
          //             addRemoveLinks: true,
          //
          //         });
        }else{
          alert(resp.err);
        }
      }
  });
}


function deleteProduk(id){
  $.ajax({
    type:'POST',
    data : {id : id},
    url: url+'&tipe=deleteProduk',
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

function removeTemp(id){
  $.ajax({
    type:'POST',
    data : {id : id},
    url: url+'&tipe=removeTemp',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){

        }else{
          alert(resp.err);
        }
      }
  });
}
function baruProduk(){

          // $("#formProdukBaru").modal();
          $("#buttonSubmit").attr("onclick","saveProduk()");
                Dropzone.autoDiscover = false;
                  var myDropzone = new Dropzone("#dropzone", {
                      url: "upload.php",
                      maxFileSize: 50,
                      acceptedFiles: ".jpeg,.jpg,.png,.gif",
                      addRemoveLinks: true,
                      init: function() {
                          this.on("complete", function(file) {
                              $(".dz-remove").html("<div><span class='fa fa-trash text-danger' style='font-size: 1.5em;cursor:pointer;' onclick=removeTemp('"+file.name+"');>REMOVE</span></div>");
                          });
                      }

                  });
                  $("#dropzone").attr('class','dropzone dz-clickable');
                  $("#namaProduk").val("");
                  $("#statusPublish").val(1);
                  $("#summernote").code("");
                  $("#buttonSubmit").attr("onclick","saveProduk()");
                  $("#tempImageProduk").attr("src","assets/img/image_placeholder.jpg");
                  $("#gambarProduk").val("");

}
function clearTemp(){
  $("#data2").text("Baru");
  $("#data2").click();
}
function updateProduk(id){
  $("#LoadingImage").attr('style','display:block');
  $.ajax({
    type:'POST',
    data : {id : id},
    url: url+'&tipe=updateProduk',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
          $("#LoadingImage").hide();
          $("#data2").text("Edit");
          $("#data2").click();
          $("#namaProduk").val(resp.content.namaProduk);
          $("#statusPublish").val(resp.content.statusPublish);
          $("#summernote").code(resp.content.deskripsi);
          $("#buttonSubmit").attr("onclick","saveEditProduk("+id+")");
          $("#tempImageProduk").attr("src",resp.content.baseOfFile);
          $("#gambarProduk").val(resp.content.baseOfFile);
          Dropzone.autoDiscover = false;
            var myDropzone = new Dropzone("#dropzone", {
                url: "upload.php",
                maxFileSize: 50,
                acceptedFiles: ".jpeg,.jpg,.png,.gif",
                addRemoveLinks: true,
                init: function() {
                    this.on("complete", function(file) {
                        $(".dz-remove").html("<div><span class='fa fa-trash text-danger' style='font-size: 1.5em;cursor:pointer;' onclick=removeTemp('"+file.name+"');>REMOVE</span></div>");
                    });
                }

            });
          $("#dropzone").attr('class','dropzone dz-clickable');
          var existingFiles = JSON.parse(resp.content.screenShot);
          for (i = 0; i < existingFiles.length; i++) {
              myDropzone.emit("addedfile", existingFiles[i]);
              myDropzone.emit("thumbnail", existingFiles[i], existingFiles[i].imageLocation);
              myDropzone.emit("complete", existingFiles[i]);
          }
          // $("#isiProduk").val(resp.content.isiProduk);
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


function saveEditProduk(idEdit){
  $("#LoadingImage").attr('style','display:block');
  $.ajax({
    type:'POST',
    data : {
        statusPublish : $("#statusPublish").val(),
        gambarProduk : $("#gambarProduk").val(),
        namaProduk : $("#namaProduk").val(),
        deskripsiProduk : $("#summernote").code(),
        idEdit : idEdit
    },
    url: url+'&tipe=saveEditProduk',
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
  var filesSelected = document.getElementById("imageProduk").files;
  if (filesSelected.length > 0)
  {
    var fileToLoad = filesSelected[0];

    var fileReader = new FileReader();

    fileReader.onload = function(fileLoadedEvent)
    {
      var textAreaFileContents = document.getElementById
      (
        "gambarProduk"
      );

      textAreaFileContents.value = fileLoadedEvent.target.result;
    };

    fileReader.readAsDataURL(fileToLoad);
  }
}
