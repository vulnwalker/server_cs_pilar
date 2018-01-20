<script type="text/javascript" src='js/Acara.js'></script>
<style type="text/css">
  .form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
    background: #f8f8f8;
    color: rgb(98, 98, 98);
    border: 1px solid #a7a2a2;
}
.form-control{
  border: 1px solid #a7a2a2;
}

</style>

<?php
$getId = @$_GET['id'];
$data = mysql_fetch_array(mysql_query("SELECT * FROM acara where id='$getId'"));
     $isi = strip_tags($data['deskripsi']);

      if (strlen($isi) > 150) {

          $stringCut = substr($isi, 0,250);

          $isi = substr($stringCut, 0, strrpos($stringCut, ' '));
      }

      $kuota = $data['kuota'] - $data['reversed'];
?>


<div class=" container-fluid   container-fixed-lg">

<div class="card card-transparent">
<div class="card-header ">
<div class="card-title"><i class="fa fa-map-marker" aria-hidden="true"></i> <?php echo $data['lokasi']; ?>
</div>
</div>
<div class="card-block">
<div class="row">
<div class="col-md-10">
<h3>Acara <?php echo $data['nama_acara']; ?></h3>
<p><?php echo $isi; ?>
</p>
<br>
<p class="small hint-text">
<a href="http://pilar.web.id/?page=viewAcara&id=<?php echo $data['id']; ?>&koordinat=<?php echo $data['koordinat']; ?>" class="btn btn-success btn-cons"> Lihat Acara Selengkapnya</a></p>
<form id="form-work" class="form-horizontal" role="form" autocomplete="off" novalidate="novalidate">
<div class="form-group row">
<label for="nama" class="col-md-3 control-label" style="color: black;">Nama Saya</label>
<div class="col-md-9">
<input type="text" class="form-control" id="nama" value="<?php echo $dataUser['nama']; ?>" readonly placeholder="Full name" name="name" required="" aria-required="true">
<input type="hidden" class="form-control" id="id" value="<?php echo $dataUser['id']; ?>">
<input type="hidden" class="form-control" id="idAcara" value="<?php echo $getId; ?>">
</div>
</div>
<div class="form-group row">
<label class="col-md-3 control-label" style="color: black;">Data Diri</label>
<div class="col-md-9">
<div class="row">
<div class="col-md-5">
<input type="text" id="email" readonly value="<?php echo $dataUser['email']; ?>"  class="form-control" required="" aria-required="true">
</div>
<div class="col-md-5 sm-m-t-10">
<input type="text" id="instansi" readonly value="<?php echo $dataUser['instansi']; ?>"  class="form-control">
</div>
</div>
</div>
</div>

<div class="form-group row">
<label class="col-md-3 control-label" style="color: black;">-</label>
<div class="col-md-9">
<div class="row">
<div class="col-md-4">
<label class="col-md-12 control-label" style="color: black;">Harga / 1 Tiket</label>
<input type="text" readonly value="<?php echo numberFormat($data['harga_tiket'] )?>" id="hargaTiket"  class="form-control" required="" aria-required="true">
</div>
<div class="col-md-4 sm-m-t-10">
<label class="col-md-12 control-label" style="color: black;">Harga / 1 Kamar</label>
<input type="text" readonly value="<?php echo numberFormat($data['harga_kamar']) ?>" id="hargaKamar" class="form-control">
</div>
<div class="col-md-4 sm-m-t-10">
<label class="col-md-12 control-label"  style="color: black;">Harga / 1 Extra Bed</label>
<input type="text" readonly value="<?php echo numberFormat($data['extra_bed']) ?>" id="hargaBed" class="form-control">
</div>
</div>
</div>
</div>


<div class="form-group row">
<label class="col-md-3 control-label" style="color: black;">-</label>
<div class="col-md-9">
<div class="row">
<div class="col-md-4">
<label class="col-md-12 control-label" style="color: black;">Jumlah Angota</label>
<input type="number" min="0" value="0" onkeyup="hitung()" onchange="hitung()" id="jlmAngota"  class="form-control" required="" aria-required="true">
</div>
<div class="col-md-4 sm-m-t-10">
<label class="col-md-12 control-label"   style="color: black;">Jumlah Kamar</label>
<input type="number" min="0" value="0"  class="form-control" onkeyup="hitung()" onchange="hitung()" id="jlmKamar">
</div>
<div class="col-md-4 sm-m-t-10">
<label class="col-md-12 control-label"  style="color: black;">Extra Bed</label>
<input type="number" min="0" value="0"  class="form-control" onkeyup="hitung()" onchange="hitung()" id="jlmBed" >
</div>
</div>
</div>
</div>

<div class="form-group row">
<label for="fname" class="col-md-3 control-label" style="color: black;">Total Pembayaran</label>
<div class="col-md-9">
<input type="text" class="form-control" id="total" value="0" readonly  name="name" required="" aria-required="true">
</div>
</div>

<br>
<div class="row">
<div class="col-md-3">
<p>Jika anda yakin tekan tombol Ikuti Acara. </p>
</div>
<div class="col-md-9">
<button class="btn btn-success" type="button" id="button" onclick="Acara()">Ikuti Acara</button>
<button class="btn btn-default"><i class="pg-close"></i> Clear</button>
</div>
</div>
</form>
</div>
</div>
</div>
</div>

</div>
