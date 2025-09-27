<?php
date_default_timezone_set('America/La_Paz');
require_once __DIR__ . '/../../vendor/autoload.php';
use Mpdf\Mpdf;

// 1) Conexión PDO a tu base de datos 'tech_f3'
try {
    $conex = new PDO("mysql:host=localhost;dbname=tech_f3", "root", "");
    $conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

// 2) Consulta SQL con INNER JOIN para obtener los detalles de todas las ventas
//    Usamos UNION ALL para combinar productos y servicios en un solo resultado.
$stmt = $conex->prepare("
    SELECT
        V.id_venta,
        V.fecha_venta,
        IFNULL(CONCAT(PER.nombres, ' ', PER.apellido_paterno), EMP.razon_social) AS nombre_cliente,
        CONCAT(E.nombre, ' ', E.appaterno) AS nombre_vendedor,
        'Producto' AS tipo_item,
        P.nombre_producto AS nombre_item, 
        DP.cantidad,
        DP.precio_unitario,
        DP.subtotal_linea
    FROM ventas AS V
    INNER JOIN 
        cliente AS C ON V.id_cliente = C.id_cliente LEFT JOIN persona AS PER ON C.id_persona = PER.id_persona  LEFT JOIN empresa AS EMP ON C.id_empresa = EMP.id_empresa
    INNER JOIN 
        usuarios AS U ON V.id_usuario_vendedor = U.id_usuario
    INNER JOIN
        empleado AS E ON U.id_empleado = E.id_empleado
    INNER JOIN 
        detalle_producto AS DP ON V.id_venta = DP.id_venta
    INNER JOIN producto AS P ON DP.id_producto = P.id_producto
    WHERE V.estado = 1

    UNION ALL

    SELECT
        V.id_venta,
        V.fecha_venta,
        IFNULL(CONCAT(PER.nombres, ' ', PER.apellido_paterno), EMP.razon_social) AS nombre_cliente,
        CONCAT(E.nombre, ' ', E.appaterno) AS nombre_vendedor,
        'Servicio' AS tipo_item,
        S.nombre_servicio AS nombre_item,
        DS.cantidad,
        DS.precio_unitario,
        DS.subtotal_linea
    FROM ventas AS V
    INNER JOIN 
        cliente AS C ON V.id_cliente = C.id_cliente   LEFT JOIN persona AS PER ON C.id_persona = PER.id_persona  LEFT JOIN empresa AS EMP ON C.id_empresa = EMP.id_empresa
    INNER JOIN 
        usuarios AS U ON V.id_usuario_vendedor = U.id_usuario
    INNER JOIN 
        empleado AS E ON U.id_empleado = E.id_empleado
    INNER JOIN 
        detalle_servicio AS DS ON V.id_venta = DS.id_venta
    INNER JOIN 
        servicios AS S ON DS.id_servicio = S.id_servicio
    WHERE V.estado = 1

    ORDER BY id_venta ASC, tipo_item DESC
");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 3) Ruta del logo
$logo_path = __DIR__ . '/../img/logito.png';
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
              <h1>Reporte de Ventas Detallado</h1>
              <div class="small">Listado de productos y servicios por transacción</div>
            </td>
          </tr>
        </table>
        <table class="table"><thead>
        <tr>
            <th style="width:50px">ID Venta</th>
            <th style="width:80px">Fecha</th>
            <th>Cliente</th>
            <th>Vendedor</th>
            <th style="width:60px">Tipo</th>
            <th>Descripción</th>
            <th style="width:50px">Cant.</th>
            <th style="width:70px">P. Unit.</th>
            <th style="width:70px">Subtotal</th>
        </tr></thead>
        <tbody>
';

if ($rows) {
    foreach ($rows as $r) {
        $html .= '<tr>'
               .  '<td>' . (int)$r['id_venta'] . '</td>'
               .  '<td>' . date('d/m/Y', strtotime($r['fecha_venta'])) . '</td>'
               .  '<td>' . htmlspecialchars($r['nombre_cliente']) . '</td>'
               .  '<td>' . htmlspecialchars($r['nombre_vendedor']) . '</td>'
               .  '<td>' . htmlspecialchars($r['tipo_item']) . '</td>'
               .  '<td>' . htmlspecialchars($r['nombre_item']) . '</td>'
               .  '<td style="text-align:center;">' . (int)$r['cantidad'] . '</td>'
               .  '<td style="text-align:right;">' . number_format($r['precio_unitario'], 2) . '</td>'
               .  '<td style="text-align:right;">' . number_format($r['subtotal_linea'], 2) . '</td>'
               .  '</tr>';
    }
} else {
    // El colspan debe coincidir con el número de columnas (9)
    $html .= '<tr><td colspan="9" class="small">No hay ventas registradas para mostrar.</td></tr>';
}
$html .= '</tbody></table></div></body></html>';

// 5) Carga del CSS para mPDF
$stylesheet = file_get_contents('rep_style.css');

// 6) Configuración y salida de mPDF
$mpdf = new Mpdf(['mode'=>'utf-8', 'format'=>'Letter', 'margin_top'=>10, 'margin_bottom'=>15]);
$mpdf->SetTitle('Reporte de Ventas Detallado');
$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->SetHTMLFooter('<div class="small">Generado el '.date('d/m/Y H:i').' · Página {PAGENO}/{nbpg}</div>');
$mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
$mpdf->Output('reporte_ventas_detallado.pdf', 'I');
?>```
