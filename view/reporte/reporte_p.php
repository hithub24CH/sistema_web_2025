<?php
date_default_timezone_set('America/La_Paz');
require_once __DIR__ . '/../../vendor/autoload.php';
use Mpdf\Mpdf;

// 1) Conexión PDO a tu base de datos 'tech_f3'
try {
    // Conectamos a la base de datos correcta
    $conex = new PDO("mysql:host=localhost;dbname=tech_f3", "root", "");
    $conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

// 2) Consulta SQL con INNER JOIN para Productos y Categorías
// Seleccionamos datos del producto y el nombre de su categoría correspondiente
$stmt = $conex->prepare("
    SELECT
        p.nro_serie,
        p.nombre_producto,
        p.marca,
        p.precio_venta_unit,
        p.stock_actual,
        cp.nombre_cat AS categoria_nombre
    FROM
        producto AS p
    INNER JOIN
        categorias_producto AS cp ON p.id_categoria_producto = cp.id_categoria_producto
    WHERE
        p.estado = 1
    ORDER BY
        categoria_nombre ASC, p.nombre_producto ASC
");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC); // Usamos FETCH_ASSOC para mayor claridad

// 3) Ruta del logo
$logo_path = __DIR__ . '/../img/logito.png'; // Usando la ruta de tu logo
$logoHtml = '<img src="' . $logo_path . '" alt="logo" style="height:60px;">';

// 4) Generación del HTML para mPDF
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
              <h1>Reporte de Productos por Categoría</h1>
              <div class="small">Listado de inventario de productos agrupados por su categoría</div>
            </td>
          </tr>
        </table>
        <table class="table"><thead>
        <tr>
            <th>Categoría</th>
            <th>Nro_Serie</th>
            <th>Nombre del Producto</th>
            <th>Marca</th>
            <th style="width:90px">Precio Venta</th>
            <th style="width:70px">Stock</th>
        </tr></thead>
        <tbody>
';

if ($rows) {
    foreach ($rows as $r) {
        $html .= '<tr>'
               .  '<td>' . htmlspecialchars($r['categoria_nombre']) . '</td>'
               .  '<td>' . htmlspecialchars($r['nro_serie']) . '</td>'
               .  '<td>' . htmlspecialchars($r['nombre_producto']) . '</td>'
               .  '<td>' . htmlspecialchars($r['marca']) . '</td>'
               .  '<td style="text-align:right;">' . htmlspecialchars(number_format($r['precio_venta_unit'], 2)) . ' Bs.</td>'
               .  '<td style="text-align:center;">' . (int)$r['stock_actual'] . '</td>'
               .  '</tr>';
    }
} else {
    // El colspan debe coincidir con el número de columnas (6)
    $html .= '<tr><td colspan="6" class="small">No hay productos registrados para mostrar.</td></tr>';
}
$html .= '</tbody></table></div></body></html>';

// 5) Carga del CSS para mPDF
$stylesheet = file_get_contents('rep_style.css');

// 6) Configuración y salida de mPDF
$mpdf = new Mpdf(['mode'=>'utf-8', 'format'=>'Letter', 'margin_top'=>10, 'margin_bottom'=>15]);
$mpdf->SetTitle('Reporte de Productos por Categoría');
$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->SetHTMLFooter('<div class="small">Generado el '.date('d/m/Y H:i').' · Página {PAGENO}/{nbpg}</div>');
$mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
$mpdf->Output('reporte_productos_categoria.pdf', 'I');
?>