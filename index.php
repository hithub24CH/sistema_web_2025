<?php
// =====================================================================
//           AÑADE ESTAS DOS LÍNEAS PARA VER LOS ERRORES
// =====================================================================
ini_set('display_errors', 1);
error_reporting(E_ALL);
// =====================================================================
// CAMBIO AÑADIDO: Inicia la sesión. Debe ser la PRIMERA línea.
session_start();
// =====================================================================

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
require_once 'model/conexion.php';

// ======================================================================================
// CAMBIO CLAVE: El controlador por defecto ahora es 'tienda' en lugar de 'cliente'.
// ¡Este es el único cambio a la lógica de tu archivo!
// ======================================================================================
$controller = 'tienda';

// El resto de tu código original se mantiene intacto.
if (!isset($_REQUEST['c'])) {
    $isFlutterRequest = isset($_REQUEST['dsn']) && $_REQUEST['dsn'] === 'flutter';
    if ($isFlutterRequest) {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        require_once "controller/$controller.controller.php";
        $controller = str_replace(' ', '', ucwords(str_replace('_', ' ', $controller))) . 'Controller';
        $controller = new $controller;
        $controller->ListarJson();
    } else {
        require_once "controller/$controller.controller.php";
        // IMPORTANTE: Tu código llama al método 'Index' con 'I' mayúscula.
        $controller = str_replace(' ', '', ucwords(str_replace('_', ' ', $controller))) . 'Controller';
        $controller = new $controller;
        $controller->Index(); 
    }
} else {
    $controller = strtolower($_REQUEST['c']);
    $accion = isset($_REQUEST['a']) ? $_REQUEST['a'] : 'Index'; // Ajustado a 'Index' como parece ser tu estándar    

    require_once "controller/$controller.controller.php";
    
    $controller = str_replace(' ', '', ucwords(str_replace('_', ' ', $controller))) . 'Controller';
    $controller = new $controller;    
    
    call_user_func(array($controller, $accion));
}
?>
