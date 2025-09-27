<?php
require_once __DIR__ . '/../../vendor/autoload.php';

echo 'autoload: ', file_exists(__DIR__ . '/../../vendor/autoload.php') ? 'ok' : 'no', "<br>";
echo 'class Mpdf: ', class_exists(\Mpdf\Mpdf::class) ? 'ok' : 'no', "<br>";

if (class_exists(\Mpdf\Mpdf::class)) {
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML('<h1>Hola mPDF</h1><p>Funciona.</p>');
    $mpdf->Output();
}
