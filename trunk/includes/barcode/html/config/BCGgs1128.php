<?php
$classFile = 'BCGgs1128.barcode.php';
$className = 'BCGgs1128';
$baseClassFile = 'BCGBarcode1D.php';
$codeVersion = '5.0.2';

function customSetup($barcode, $get) {
    if (isset($get['start'])) {
        $barcode->setStart($get['start'] === 'NULL' ? null : $get['start']);
    }
}
?>