<?php
date_default_timezone_set('America/La_Paz');
require_once __DIR__ . '/../../vendor/autoload.php'; // ventas/view/reporte -> ventas/vendor
use Mpdf\Mpdf;

// 2) Conexión PDO
$conex = new PDO("mysql:host=localhost;dbname=dbventas_mk","root","");
$conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 4) Consulta
$stmt =$conex->prepare("SELECT * FROM categorias WHERE estado = 1  ORDER BY id ASC");
$stmt->execute();
$rows = $stmt->fetchAll();

$logo_path = __DIR__ . '/../img/logito.png';                  //ventas/view/img/logo.png
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
              <h1>Listado de Categorías</h1>
              <div class="small">Registros completos</div>
            </td>
          </tr>
        </table>
        <table class="table"><thead>
        <tr>
            <th style="width:60px">ID</th>
            <th>Categoría</th>
            <th style="width:90px">Estado</th>
            <th style="width:170px">Fecha registro</th>
        </tr></thead>
        <tbody>
';
if ($rows) {
    foreach ($rows as $r) {
        $html .= '<tr>'
               .  '<td>' . (int)$r['id'] . '</td>'
               .  '<td>' . htmlspecialchars($r['categoria']) . '</td>'
               .  '<td>' . ((int)$r['estado'] === 1 ? '1 (activo)' : '0 (inactivo)') . '</td>'
               .  '<td>' . htmlspecialchars($r['fecha_registro']) . '</td>'
               .  '</tr>';
    }
} else {
    $html .= '<tr><td colspan="4" class="small">No hay categorías para mostrar.</td></tr>';
}
$html .= '</tbody></table></div></body></html>';

$stylesheet = file_get_contents('rep_style.css');

$mpdf = new Mpdf(['mode'=>'utf-8', 'format'=>'Letter', 'margin_top'=>10, 'margin_bottom'=>15]);
$mpdf->SetTitle('Reporte - Categorías');
$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->SetHTMLFooter('<div class="small">Generado el '.date('d/m/Y H:i').' · Página {PAGENO}/{nbpg}</div>');
$mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
$mpdf->Output('categorias.pdf', 'I'); 
