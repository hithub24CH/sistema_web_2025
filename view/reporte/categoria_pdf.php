<?php
// 0) Configuración de zona horaria
date_default_timezone_set('America/La_Paz');

// 1) Autoload de Composer (ajusta ruta si tu vendor está en otra carpeta)
require_once __DIR__ . '/../../vendor/autoload.php'; // ventas/view/reporte -> ventas/vendor

use Mpdf\Mpdf;

// 2) Conexión PDO (ajusta credenciales)
$DB_HOST = 'localhost';
$DB_NAME = 'dbventas_mk';
$DB_USER = 'root';
$DB_PASS = '';
$DB_CHARSET = 'utf8mb4';

$pdo = new PDO(
    "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=$DB_CHARSET",
    $DB_USER,
    $DB_PASS,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
);

// 3) Filtro opcional por estado
$estado = isset($_GET['estado']) && $_GET['estado'] !== '' ? (int)$_GET['estado'] : null;

// 4) Consulta
$sql = "SELECT id, categoria, estado, fecha_registro FROM categorias";
$params = [];
if ($estado !== null) {
    $sql .= " WHERE estado = :estado";
    $params[':estado'] = $estado;
}
$sql .= " ORDER BY id ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();

// 5) Ruta fija del logo
$logo_path = __DIR__ . '/../img/lolo.jpg'; // ventas/view/reporte -> ventas/view/img/logo.png

// 6) CSS y encabezado
$css = <<<CSS
body { font-family: sans-serif; font-size: 11px; }
h1 { font-size: 18px; margin: 0; }
.table { width: 100%; border-collapse: collapse; margin-top: 8px; }
.table th, .table td { border: 1px solid #ddd; padding: 6px; }
.table th { background: #f5f5f5; text-align: left; }
.small { color:#666; font-size:10px; }
.header-table { width: 100%; border-collapse: collapse; }
.header-table td { vertical-align: middle; }
.header-logo { width: 30px; }             
.header-logo img { max-width: 30px; height: auto; display: block; } 
CSS;

$subfiltro = $estado === null ? 'Todos' : ($estado ? 'Activos (1)' : 'Inactivos (0)');

$logoHtml = $logo_path
    ? '<img src="'.htmlspecialchars($logo_path).'" alt="logo">'
    : '<div style="width:90px;height:90px;border:1px solid #ccc;background:#f5f5f5"></div>';

$header_html = '
<table class="header-table">
  <tr>
    <td class="header-logo">'.$logoHtml.'</td>
    <td>
      <h1>Listado de Categorías</h1>
      <div class="small">Filtro: '.htmlspecialchars($subfiltro).'</div>
    </td>
  </tr>
</table>';

// 7) Construir tabla
$tabla  = '<table class="table"><thead><tr>'
        . '<th style="width:60px">ID</th>'
        . '<th>Categoría</th>'
        . '<th style="width:90px">Estado</th>'
        . '<th style="width:170px">Fecha registro</th>'
        . '</tr></thead><tbody>';

if ($rows) {
    foreach ($rows as $r) {
        $tabla .= '<tr>'
               .  '<td>' . (int)$r['id'] . '</td>'
               .  '<td>' . htmlspecialchars($r['categoria']) . '</td>'
               .  '<td>' . ((int)$r['estado'] === 1 ? '1 (activo)' : '0 (inactivo)') . '</td>'
               .  '<td>' . htmlspecialchars($r['fecha_registro']) . '</td>'
               .  '</tr>';
    }
} else {
    $tabla .= '<tr><td colspan="4" class="small">No hay categorías para mostrar.</td></tr>';
}
$tabla .= '</tbody></table>';

// 8) Crear PDF
$mpdf = new Mpdf(['mode'=>'utf-8', 'format'=>'A4', 'margin_top'=>40, 'margin_bottom'=>15]);
$mpdf->SetTitle('Reporte - Categorías');
$mpdf->SetHTMLHeader('<div style="border-bottom:1px solid #ddd;padding-bottom:6px;">'.$header_html.'</div>');
$mpdf->SetHTMLFooter('<div class="small">Generado el '.date('d/m/Y H:i').' · Página {PAGENO}/{nbpg}</div>');

$html = '<style>'.$css.'</style>'.$tabla;
$mpdf->WriteHTML($html);
$mpdf->Output('categorias.pdf', 'I'); // 'I' = mostrar en navegador
