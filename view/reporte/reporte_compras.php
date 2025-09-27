<?php
date_default_timezone_set('America/La_Paz');
require_once __DIR__ . '/../../vendor/autoload.php';

use Mpdf\Mpdf;

// 1) Conexión PDO a la base de datos
try {
    $conex = new PDO("mysql:host=localhost;dbname=tech_f3", "root", "");
    $conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// 2) Consulta SQL para obtener todos los detalles de las compras en un formato plano
$query = "
    SELECT
        CO.id_compra,
        CO.fecha_compra,
        
        IFNULL(EMP.razon_social, CONCAT(PER.nombres, ' ', PER.apellido_paterno)) AS nombre_proveedor,
        
        CONCAT(E.nombre, ' ', E.appaterno) AS nombre_comprador,
        
        P.nombre_producto,
        DC.cantidad,
        DC.precio_unitario_adquisicion,
        
        (DC.cantidad * DC.precio_unitario_adquisicion) AS subtotal_linea
    FROM compras AS CO
    INNER JOIN
         proveedor AS PR ON CO.id_proveedor = PR.id_proveedor
    LEFT JOIN 
        empresa AS EMP ON PR.id_empresa = EMP.id_empresa
    LEFT JOIN 
        persona AS PER ON PR.id_persona = PER.id_persona
    INNER JOIN
        usuarios AS U ON CO.id_usuario_comprador = U.id_usuario
    INNER JOIN 
        empleado AS E ON U.id_empleado = E.id_empleado
    INNER JOIN 
        detalle_compra AS DC ON CO.id_compra = DC.id_compra
    INNER JOIN 
        producto AS P ON DC.id_producto = P.id_producto
    WHERE CO.estado = 1 AND DC.estado = 1
    ORDER BY CO.id_compra ASC;
";
$stmt = $conex->prepare($query);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ruta al logo
$logo_path = __DIR__ . '/../img/logito.png';
$logoHtml = '<img src="' . $logo_path . '" alt="logo" style="height:60px;">';

// 3) Construcción del HTML para el PDF, usando una tabla simple
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
             <td class="header-logo">' . $logoHtml . '<br></td>
            <td>
              <h1>Listado de Compras</h1>
              <div class="small">Detalle de Productos Adquiridos a Proveedores</div>
            </td>
          </tr>
        </table>
        <table class="table"><thead>
        <tr>
            <th style="width:50px">ID Compra</th>
            <th style="width:80px">Fecha</th>
            <th>Proveedor</th>
            <th>Comprador</th>
            <th>Producto Adquirido</th>
            <th style="width:50px">Cant.</th>
            <th style="width:80px">Costo Unit.</th>
            <th style="width:80px">Subtotal</th>
        </tr></thead>
        <tbody>
';

if ($rows) {
    // 4) Se recorren todos los resultados y se crea una fila <tr> por cada producto comprado
    foreach ($rows as $r) {
        $html .= '<tr>'
            .  '<td>' . (int)$r['id_compra'] . '</td>'
            .  '<td>' . date('d/m/Y', strtotime($r['fecha_compra'])) . '</td>'
            .  '<td>' . htmlspecialchars($r['nombre_proveedor']) . '</td>'
            .  '<td>' . htmlspecialchars($r['nombre_comprador']) . '</td>'
            .  '<td>' . htmlspecialchars($r['nombre_producto']) . '</td>'
            .  '<td>' . (int)$r['cantidad'] . '</td>'
            .  '<td style="text-align:right;">' . number_format($r['precio_unitario_adquisicion'], 2) . '</td>'
            .  '<td style="text-align:right;">' . number_format($r['subtotal_linea'], 2) . '</td>'
            .  '</tr>';
    }
} else {
    // Se ajusta el colspan al nuevo número de columnas (8)
    $html .= '<tr><td colspan="8" class="small">No hay compras para mostrar.</td></tr>';
}

$html .= '</tbody></table></div></body></html>';

// Cargar la hoja de estilos CSS
$stylesheet = file_get_contents('rep_style.css');

// Configuración de mPDF
$mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'Letter', 'margin_top' => 10, 'margin_bottom' => 15]);
$mpdf->SetTitle('Reporte - Listado de Compras');
$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->SetHTMLFooter('<div class="small">Generado el ' . date('d/m/Y H:i') . ' · Página {PAGENO}/{nbpg}</div>');
$mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

// Salida del PDF
$mpdf->Output('listado_compras.pdf', 'I');
