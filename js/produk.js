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
  window.location = "pages.php?page=produk" ;
}
function Baru(){
  window.location = "pages.php?page=produk&action=baru" ;
}
function Batal(){
  window.location = "pages.php?page=produk" ;
}
function Edit(){
  var errMsg = getJumlahChecked("produk");
  if(errMsg == ''){
    $.ajax({
      type:'POST',
      data : $("#formProduk").serialize(),
      url: url+'&tipe=Edit',
      success: function(data) {
        var resp = eval('(' + data + ')');
        if(resp.err==''){
          window.location = "pages.php?page=produk&action=edit&idEdit="+resp.content.idEdit;
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
  var errMsg = getJumlahChecked("produk");
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
              data : $("#formProduk").serialize(),
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
     var editors = textboxio.get('#deskripsiProduk');
     var editor = editors[0];
     return editor.content.get();
 }
function saveProduk(){
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
          // data : $("#formProduk").serialize()+"&deskripsiProduk="+$("#summernote").code()+"&baseGambarProduk="+crop,
          data : {
                namaProduk : $("#namaProduk").val(),
                statusKosong : $("#statusKosong").val(),
                baseGambarProduk : $('#gambarProduk').cropper('getCroppedCanvas').toDataURL(),
                statusPublish : $("#statusPublish").val(),
                linkDemo : $("#linkDemo").val(),
                deskripsiProduk : getEditorContent()
          },
          url: url+'&tipe=saveProduk',
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
function saveEditProduk(idEdit){
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
          // data : $("#formProduk").serialize()+"&deskripsiProduk="+$("#summernote").code()+"&baseGambarProduk="+crop,
          data : {
                namaProduk : $("#namaProduk").val(),
                statusKosong : $("#statusKosong").val(),
                baseGambarProduk : $('#gambarProduk').cropper('getCroppedCanvas').toDataURL(),
                statusPublish : $("#statusPublish").val(),
                linkDemo : $("#linkDemo").val(),
                deskripsiProduk : getEditorContent(),
                idEdit : idEdit
          },
          url: url+'&tipe=saveEditProduk',
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


function imageClicked(aa){
  var modal = document.getElementById('myModal');

// Get the image and insert it inside the modal - use its "alt" text as a caption
var img = document.getElementById('myImg');
var modalImg = document.getElementById("img01");
var captionText = document.getElementById("captionImage");

    modal.style.display = "block";
    modalImg.src = aa.src;
    captionText.innerHTML = aa.alt;



}

function closeImage(){
  var span = document.getElementsByClassName("close")[0];
    var modal = document.getElementById('myModal');
      modal.style.display = "none";
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
      $("#gambarProduk").attr('src',fileLoadedEvent.target.result);
      $('#gambarProduk').cropper('destroy');
      $("#gambarProduk").cropper({
          aspectRatio: 1392/880,
          minCropBoxWidth: 1392,
          minCropBoxHeight: 880,
          resizable: true,
          autoCropArea: 0,
          strict: false,
          guides: false,
          highlight: false,
          dragCrop: false,
          cropBoxMovable: true,
          cropBoxResizable: false,
          dragMode: 'move',
      });

      $("#statusKosong").val('1');
    };

    fileReader.readAsDataURL(fileToLoad);
  }
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
          $("#pemicuPopup").click();
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

function upUrutan(id){
  $.ajax({
    type:'POST',
    data : {
            id : id
    },
    url: url+'&tipe=upUrutan',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
          $(".active-tick").click();
        }else{
          errorAlert(resp.err);
        }
      }
  });
}
function downUrutan(id){
  $.ajax({
    type:'POST',
    data : {
            id : id
    },
    url: url+'&tipe=downUrutan',
      success: function(data) {
      var resp = eval('(' + data + ')');
        if(resp.err==''){
          $(".active-tick").click();
        }else{
          errorAlert(resp.err);
        }
      }
  });
}
