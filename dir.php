<?php
$jsonScreenshot = '["images/produk/BPKAD/e42b6cff28b1ecd03d98319f6277dbda.jpg","images/produk/BPKAD/710b6d8a8ff497d4d983dbdd92557b6d.jpg","images/produk/BPKAD/f3c87c199958c35dc3d62eb2b6cae0f4.jpg"]';
$decodedJSON = json_decode($jsonScreenshot);
for ($i=0; $i < sizeof($decodedJSON) ; $i++) {
    echo $decodedJSON[$i]."\n";
}
 ?>
