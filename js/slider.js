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
  window.location = "pages.php?page=slider" ;
}
function Baru(){
  window.location = "pages.php?page=slider&action=baru" ;
}
function Batal(){
  window.location = "pages.php?page=slider" ;
}
function Edit(){
  var errMsg = getJumlahChecked("slider");
  if(errMsg == ''){
    $.ajax({
      type:'POST',
      data : $("#formSlider").serialize(),
      url: url+'&tipe=Edit',
      success: function(data) {
        var resp = eval('(' + data + ')');
        if(resp.err==''){
          window.location = "pages.php?page=slider&action=edit&idEdit="+resp.content.idEdit;
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
  var errMsg = getJumlahChecked("slider");
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
              data : $("#formSlider").serialize(),
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
function saveSlider(){
    // $("#LoadingImage").attr('style','display:block');
    $.ajax({
      type:'POST',
      // data : $("#formSlider").serialize()+"&deskripsiSlider="+$("#summernote").code()+"&baseGambarSlider="+crop,
      data : {
            namaSlider : $("#namaSlider").val(),
            statusKosong : $("#statusKosong").val(),
            baseGambarSlider : crop,
            statusPublish : $("#statusPublish").val()
      },
      url: url+'&tipe=saveSlider',
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
function saveEditSlider(idEdit){
  $.ajax({
    type:'POST',
    // data : $("#formSlider").serialize()+"&deskripsiSlider="+$("#summernote").code()+"&baseGambarSlider="+crop,
    data : {
          namaSlider : $("#namaSlider").val(),
          statusKosong : $("#statusKosong").val(),
          baseGambarSlider : crop,
          idEdit : idEdit,
          statusPublish : $("#statusPublish").val()
    },
    url: url+'&tipe=saveEditSlider',
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


function imageClicked(aa){
  var modal = document.getElementById('myModal');
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
  var filesSelected = document.getElementById("imageSlider").files;
  if (filesSelected.length > 0)
  {
    var fileToLoad = filesSelected[0];
    var fileReader = new FileReader();
    fileReader.onload = function(fileLoadedEvent)
    {
      $("#gambarSlider").attr('src',fileLoadedEvent.target.result);
      resizeableImage($('#gambarSlider'));
      $('.component').show();
      $("#statusKosong").val('1');
    };

    fileReader.readAsDataURL(fileToLoad);
  }
}
