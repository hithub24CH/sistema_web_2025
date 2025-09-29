<?php
require_once 'model/producto.php';

class CarritoController {

    public function Index() {
        require_once 'view/tienda/carrito.php';
    }

    public function add() {
        if(isset($_GET['id'])){
            $producto_id = $_GET['id'];
            
            if(!isset($_SESSION['carrito'])){
                $_SESSION['carrito'] = array();
            }

            $producto_ya_existe = false;
            if (!empty($_SESSION['carrito'])) {
                foreach($_SESSION['carrito'] as $indice => $elemento){
                    if($elemento['id_producto'] == $producto_id){
                        $_SESSION['carrito'][$indice]['unidades']++;
                        $producto_ya_existe = true;
                        break;
                    }
                }
            }

            if(!$producto_ya_existe){
                $producto_modelo = new Producto();
                $producto_info = $producto_modelo->Obtener($producto_id); 

                if($producto_info){
                    $_SESSION['carrito'][] = array(
                        "id_producto" => $producto_info->id_producto,
                        "precio" => $producto_info->precio_venta_unit,
                        "unidades" => 1,
                        "producto" => $producto_info
                    );
                }
            }
        }
        
        header("Location: index.php");
        exit();
    }
    
    /**
     * CORREGIDO: Elimina un producto específico del carrito.
     */
    public function remove() {
        if(isset($_GET['id'])){
            $producto_id_a_eliminar = $_GET['id'];

            if(isset($_SESSION['carrito'])){
                foreach($_SESSION['carrito'] as $indice => $elemento){
                    // Se asegura de que el 'id_producto' exista antes de compararlo
                    if(isset($elemento['id_producto']) && $elemento['id_producto'] == $producto_id_a_eliminar){
                        unset($_SESSION['carrito'][$indice]);
                        break;
                    }
                }
            }
        }
        
        // Redirigimos de vuelta a la página del carrito
        header("Location: index.php?c=carrito");
        exit();
    }

    /**
     * Elimina TODOS los productos del carrito.
     */
    public function delete_all() {
        unset($_SESSION['carrito']);
        
        header("Location: index.php?c=carrito");
        exit();
    }
}
?>