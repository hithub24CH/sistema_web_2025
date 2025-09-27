<?php
// =====================================================================
// CAMBIO AÑADIDO: Inicia la sesión. Debe ser la PRIMERA línea.
session_start();
// =====================================================================

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
require_once 'model/conexion.php';

$controller = 'cliente';
// Verificar si la solicitud es de Flutter (se puede distinguir por el Content-Type o por un parámetro especial)

if (!isset($_REQUEST['c'])) {
    // Si la solicitud es desde Flutter, redirigir al método ListarJson
    $isFlutterRequest = isset($_REQUEST['dsn']) && $_REQUEST['dsn'] === 'flutter';
    if ($isFlutterRequest) {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        require_once "controller/$controller.controller.php";
        // --- LÍNEA CORREGIDA 1 ---
        $controller = str_replace(' ', '', ucwords(str_replace('_', ' ', $controller))) . 'Controller';
        $controller = new $controller;
        $controller->ListarJson();  // Llama al método para Flutter
    } else {
        // Si es desde la web, carga la página principal
        require_once "controller/$controller.controller.php";
        // --- LÍNEA CORREGIDA 2 ---
        $controller = str_replace(' ', '', ucwords(str_replace('_', ' ', $controller))) . 'Controller';
        $controller = new $controller;
        $controller->Index();  // Llama al método Index() para la página web
    }
} else {
    // Obtiene el controlador y la acción a cargar
    $controller = strtolower($_REQUEST['c']);
    $accion = isset($_REQUEST['a']) ? $_REQUEST['a'] : 'IndexPage';    

    // Instancia el controlador
    require_once "controller/$controller.controller.php";
    
    $controller = str_replace(' ', '', ucwords(str_replace('_', ' ', $controller))) . 'Controller';
    $controller = new $controller;    
    
    // Llama la acción correspondiente
    call_user_func(array($controller, $accion));
}
?>