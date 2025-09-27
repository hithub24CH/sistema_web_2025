<?php
date_default_timezone_set('America/La_Paz');
require_once __DIR__ . '/../../vendor/autoload.php'; 
use Mpdf\Mpdf;

try {
    $conex = new PDO("mysql:host=localhost;dbname=tech5", "root", "");
    $conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conex->exec("SET NAMES 'utf8'");
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}


$query = "
    SELECT
        c.id_compra,
        c.fecha_compra,
        c.total_compra,
        c.estado,
        p.nombre_completo AS nombre_proveedor,
        u.nombre AS nombre_comprador
    FROM
        compras AS c
    LEFT JOIN
        proveedor AS p ON c.id_proveedor = p.id_proveedor
    LEFT JOIN
        usuarios AS u ON c.id_usuario_comprador = u.id_usuario
    ORDER BY
        c.id_compra ASC
";

$stmt = $conex->prepare($query);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);


$logo_path = __DIR__ . '/../img/logito.png';
$logoHtml = '<img src="' . $logo_path . '" alt="logo" style="height:60px;">';


$html = '
  <!DOCTYPE html>
  <html>
  <head>
      <link rel="stylesheet" type="text/css" href="rep_style.css">
  </head>
  <body>
      <div class="container">
        <table class="header-table">
          <tr>
             <td class="header-logo">'.$logoHtml.'<br></td>
            <td>
              <h1>Reporte de Compras</h1>
              <div class="small">Registros completos</div>
            </td>
          </tr>
        </table>
        <table class="table"><thead>
        <tr>
            <th style="width:50px">ID</th>
            <th>Proveedor</th>
            <th>Comprador</th>
            <th style="width:150px">Fecha y Hora</th>
            <th style="width:110px">Total (Bs.)</th>
            <th style="width:100px">Estado</th>
        </tr></thead>
        <tbody>
';

if ($rows) {
    foreach ($rows as $r) {
        
        $fecha = new DateTime($r['fecha_compra']);
        $fechaFormateada = $fecha->format('d/m/Y H:i:s');
        
        $html .= '<tr>'
               .  '<td>' . (int)$r['id_compra'] . '</td>'
               .  '<td>' . htmlspecialchars($r['nombre_proveedor'] ?? 'N/A') . '</td>'
               .  '<td>' . htmlspecialchars($r['nombre_comprador'] ?? 'N/A') . '</td>'
               .  '<td>' . $fechaFormateada . '</td>'
               
               .  '<td style="text-align: right;">' . number_format($r['total_compra'], 2, ',', '.') . '</td>'
               .  '<td>' . htmlspecialchars($r['estado']) . '</td>'
               .  '</tr>';
    }
} else {
    
    $html .= '<tr><td colspan="6" class="small">No hay compras para mostrar.</td></tr>';
}

$html .= '</tbody></table></div></body></html>';


$stylesheet = file_get_contents('rep_style.css');


$mpdf = new Mpdf(['mode'=>'utf-8', 'format'=>'Letter', 'margin_top'=>10, 'margin_bottom'=>15]);
$mpdf->SetTitle('Reporte - Compras');
$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->SetHTMLFooter('<div class="small">Generado el '.date('d/m/Y H:i').' · Página {PAGENO}/{nbpg}</div>');
$mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

// Salida del PDF
$mpdf->Output('compras.pdf', 'I');