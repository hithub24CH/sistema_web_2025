<?php
date_default_timezone_set('America/La_Paz');
require_once __DIR__ . '/../../vendor/autoload.php';
use Mpdf\Mpdf;


$dbHost = "localhost";
$dbName = "tech2"; 
$dbUser = "root";
$dbPass = ""; 

try {
    $conex = new PDO("mysql:host={$dbHost};dbname={$dbName}", $dbUser, $dbPass);
    $conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}



$stmt = $conex->prepare("SELECT * FROM categorias_producto ORDER BY id_categoria_producto ASC");
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
              <h1>Listado de Categorías de Productos</h1>
              <div class="small">Registros completos</div>
            </td>
          </tr>
        </table>
        <table class="table"><thead>
        <tr>
            <th style="width:60px">ID</th>
            <th>Categoría</th>
            <th>Descripción</th>
        </tr></thead>
        <tbody>
';

if ($rows) {
    foreach ($rows as $r) {
        
        $html .= '<tr>'
               .  '<td>' . (int)$r['id_categoria_producto'] . '</td>'
               .  '<td>' . htmlspecialchars($r['nombre_cat']) . '</td>'
               .  '<td>' . htmlspecialchars($r['descripcion']) . '</td>'
               .  '</tr>';
    }
} else {
    $html .= '<tr><td colspan="3" class="small">No hay categorías para mostrar.</td></tr>'; 
}

$html .= '</tbody></table></div></body></html>';


$stylesheet = file_get_contents('rep_style.css');


$mpdf = new Mpdf(['mode'=>'utf-8', 'format'=>'Letter', 'margin_top'=>10, 'margin_bottom'=>15]);


$mpdf->SetTitle('Reporte - Categorías de Productos'); 
$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->SetHTMLFooter('<div class="small">Generado el '.date('d/m/Y H:i').' · Página {PAGENO}/{nbpg}</div>');
$mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);


$mpdf->Output('categorias_producto.pdf', 'I'); 