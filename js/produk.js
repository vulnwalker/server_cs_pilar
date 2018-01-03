function showGambarProduk(id){
  $.ajax({
    type:'POST',
    data : {
        idproduk : id,
    },
    url: url+'&tipe=showGambarProduk',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
         $("#tempatGambar").html(resp.content.imagesProduks);
        }else{
          errorAlert(resp.err);
        }
      }
  });
}
function saveProduk(){
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
         suksesAlert("Data Tersimpan");
        }else{
          errorAlert(resp.err);
        }
      }
  });
}

function refreshList(){
    window.location= 'pages.php?page=produk';
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
  swal({
      title: "Yakin Hapus Data",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: '#DD6B55',
      confirmButtonText: 'Ya',
      cancelButtonText: "Tidak"
   }).then(
         function () {
           $.ajax({
             type:'POST',
             data : {id:id},
             url: url+'&tipe=deleteProduk',
               success: function(data) {
               var resp = eval('(' + data + ')');
                 if(resp.err==''){
                   suksesAlert("Data Terhapus");
                 }else{
                   errorAlert(resp.err);
                 }
               }
           });
         },
         function () { return false; });
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
                              $(".dz-remove").html("<div><span class='fa fa-trash text-danger' style='font-size: 1.5em;cursor:pointer;' >REMOVE</span></div>");
                              // $(".dz-details").attr("onclick","deskripsiScreenShot('"+file.name+"')");
                              $(".dz-details").attr("style","cursor:pointer;");
                          });
                          this.on("thumbnail", function(file) {
                            console.log(file); // will send to console all available props
                            file.previewElement.addEventListener("click", function() {
                               deskripsiScreenShot(file.name);
                            });
                        });
                          this.on("removedfile", function(file) {
                               removeTemp(file.name);
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
function deskripsiScreenShot(namaFile){
  $.ajax({
    type:'POST',
    data : {namaFile : namaFile},
    url: url+'&tipe=deskripsiScreenShot',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
          $("#descSreenShot").text("");
          $("#tempScreenShot").attr('src',resp.content.srcImage);
          $("#descSreenShot").val(resp.content.descScreenShot);
          $("#divForDesc").attr("class","form-group label-floating is-focused");
          $("#buttonSubmitScreenShot").attr("onclick","saveDescSreenshot('"+namaFile+"')");
          $("#formDeskripsiScreenShot").modal();
        }else{
          alert(resp.err);
        }
      }
  });
}
function saveDescSreenshot(namaFile){
  $.ajax({
    type:'POST',
    data : {
              namaFile : namaFile,
              descSreenShot : $("#descSreenShot").val()
            },
    url: url+'&tipe=saveDescSreenshot',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
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
function updateProduk(id){
  $.ajax({
    type:'POST',
    data : {id : id},
    url: url+'&tipe=updateProduk',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
          // $("#data2").text("Edit");
          // $("#data2").click();
          // $("#namaProduk").val(resp.content.namaProduk);
          // $("#statusPublish").val(resp.content.statusPublish);
          // $("#summernote").code(resp.content.deskripsi);
          // $("#buttonSubmit").attr("onclick","saveEditProduk("+id+")");
          // $("#tempImageProduk").attr("src",resp.content.baseOfFile);
          // $("#gambarProduk").val(resp.content.baseOfFile);
          // Dropzone.autoDiscover = false;
          //   var myDropzone = new Dropzone("#dropzone", {
          //       url: "upload.php",
          //       maxFileSize: 50,
          //       acceptedFiles: ".jpeg,.jpg,.png,.gif",
          //       addRemoveLinks: true,
          //       init: function() {
          //           this.on("complete", function(file) {
          //               $(".dz-remove").html("<div><span class='fa fa-trash text-danger' style='font-size: 1.5em;cursor:pointer;' onclick=removeTemp('"+file.name+"');>REMOVE</span></div>");
          //
          //           });
          //       }
          //
          //   });
          // $("#dropzone").attr('class','dropzone dz-clickable');
          // var existingFiles = JSON.parse(resp.content.screenShot);
          // for (i = 0; i < existingFiles.length; i++) {
          //     myDropzone.emit("addedfile", existingFiles[i]);
          //     myDropzone.emit("thumbnail", existingFiles[i], existingFiles[i].imageLocation);
          //     myDropzone.emit("complete", existingFiles[i]);
          // }
          //
          // $("#isiProduk").val(resp.content.isiProduk);
            window.location = "pages.php?page=produk&edit="+id;
        }else{
          alert(resp.err);
        }
      }
  });

}


function saveEditProduk(idEdit){
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
         suksesAlert("Data Tersimpan");
        }else{
          errorAlert(resp.err);
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
