function saveSetting(){
  $("#LoadingImage").attr('style','display:block');
  $.ajax({
    type:'POST',
    data : {
              informasiBackground : $("#informasiBackground").val(),
              produkBackground : $("#produkBackground").val(),
              acaraBackground : $("#acaraBackground").val(),
              sliderBackground : $("#sliderBackground").val(),
              lowonganBackground : $("#lowonganBackground").val(),
              tentangBackground : $("#tentangBackground").val(),
              popularTitleColor : $("#popularTitleColor").val(),
              popularDeskripsiColor : $("#popularDeskripsiColor").val(),
              namaPerusahaan : $("#namaPerusahaan").val(),
              alamatPerusahaan : $("#alamatPerusahaan").val(),
              emailPerusahaan : $("#emailPerusahaan").val(),
              teleponPerusahaan : $("#teleponPerusahaan").val(),
              facebookPerusahaan : $("#facebookPerusahaan").val(),
              twiterPerusahaan : $("#twiterPerusahaan").val(),
              instagramPerusahaan : $("#instagramPerusahaan").val(),
              googlePlus : $("#googlePlus").val(),
              waPerusahaan : $("#waPerusahaan").val(),
              linkedInPerusahaan : $("#linkedInPerusahaan").val(),
              effectSlider : $("#effectSlider").val(),
              tentang : $("#tentang").val(),
            },
    url: url+'&tipe=saveSetting',
      success: function(data) {
        $("#LoadingImage").hide();
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
