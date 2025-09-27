<?php
date_default_timezone_set('America/La_Paz');
require_once __DIR__ . '/../../vendor/autoload.php'; // Revisa que la ruta a 'vendor' sea correcta
use Mpdf\Mpdf;




try {
    $conex = new PDO("mysql:host=localhost;dbname=tech5", "root", "");
    $conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Es buena práctica usar UTF-8 para la conexión
    $conex->exec("SET NAMES 'utf8'");
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}


$query = "
    SELECT
        v.id_venta,
        v.fecha_venta,
        v.total_venta,
        v.estado_venta,
        c.nombre_completo AS nombre_cliente,
        u.nombre AS nombre_vendedor
    FROM
        ventas AS v
    LEFT JOIN
        cliente AS c ON v.id_cliente = c.id_cliente
    LEFT JOIN
        usuarios AS u ON v.id_usuario_vendedor = u.id_usuario
    ORDER BY
        v.id_venta ASC
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
              <h1>Reporte de Ventas</h1>
              <div class="small">Registros completos</div>
            </td>
          </tr>
        </table>
        <table class="table"><thead>
        <tr>
            <th style="width:50px">ID</th>
            <th>Cliente</th>
            <th>Vendedor</th>
            <th style="width:150px">Fecha y Hora</th>
            <th style="width:100px">Total (Bs.)</th>
            <th style="width:100px">Estado</th>
        </tr></thead>
        <tbody>
';

if ($rows) {
    foreach ($rows as $r) {
        // Formatear la fecha para mejor lectura
        $fecha = new DateTime($r['fecha_venta']);
        $fechaFormateada = $fecha->format('d/m/Y H:i:s');
        
        $html .= '<tr>'
               .  '<td>' . (int)$r['id_venta'] . '</td>'
               // Usamos los alias 'nombre_cliente' y 'nombre_vendedor' de la consulta
               .  '<td>' . htmlspecialchars($r['nombre_cliente'] ?? 'N/A') . '</td>'
               .  '<td>' . htmlspecialchars($r['nombre_vendedor'] ?? 'N/A') . '</td>'
               .  '<td>' . $fechaFormateada . '</td>'
               // Formateamos el número para que tenga 2 decimales
               .  '<td style="text-align: right;">' . number_format($r['total_venta'], 2, ',', '.') . '</td>'
               .  '<td>' . htmlspecialchars($r['estado_venta']) . '</td>'
               .  '</tr>';
    }
} else {
    // El colspan se ajusta al número de columnas (6)
    $html .= '<tr><td colspan="6" class="small">No hay ventas para mostrar.</td></tr>';
}

$html .= '</tbody></table></div></body></html>';

// Cargar la hoja de estilos CSS
$stylesheet = file_get_contents('rep_style.css');

// Configuración de mPDF
$mpdf = new Mpdf(['mode'=>'utf-8', 'format'=>'Letter', 'margin_top'=>10, 'margin_bottom'=>15]);
$mpdf->SetTitle('Reporte - Ventas');
$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->SetHTMLFooter('<div class="small">Generado el '.date('d/m/Y H:i').' · Página {PAGENO}/{nbpg}</div>');
$mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

// Salida del PDF
$mpdf->Output('ventas.pdf', 'I');