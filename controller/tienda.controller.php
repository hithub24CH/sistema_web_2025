<?php
require_once 'model/producto.php';

class TiendaController {
    
    /**
     * IMPORTANTE: El nombre de la función es 'Index' (con I mayúscula)
     * para coincidir con la llamada que hace tu enrutador principal.
     */
    public function Index() {
        
        $producto_modelo = new Producto();
        
        // La variable $productos contendrá el array de objetos que devuelve tu modelo
        $productos = $producto_modelo->Listar(); 
        
        // Cargamos la vista de la tienda y le pasamos la variable $productos
        require_once 'view/tienda/index.php';
    }
    
}
?>s