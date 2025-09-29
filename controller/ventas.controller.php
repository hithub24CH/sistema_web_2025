<?php
// ARCHIVO: controller/ventas.controller.php

require_once 'model/ventas.php';
require_once 'model/cliente.php';
require_once 'model/producto.php';
require_once 'model/servicios.php';
require_once 'model/usuarios.php';

class VentasController
{
    private $model;
    private $modelCliente;
    private $modelProducto;
    private $modelServicio;
    private $modelUsuario;

    public function __construct() {
        $this->model = new Ventas();
        $this->modelCliente = new Cliente();
        $this->modelProducto = new Producto();
        $this->modelServicio = new Servicios();
        $this->modelUsuario = new Usuario();
    }

    // =====================================================================
    //          MÉTODOS ORIGINALES DEL PANEL DE ADMINISTRACIÓN (INTACTOS)
    // =====================================================================

    public function Index() {
        $this->IndexPage(); 
    }

    public function IndexPage() {
        require_once 'view/header.php';
        require_once 'view/frmventa.php';
        require_once 'view/footer.php';
    }

    public function Nueva() {
        // Tu código original va aquí...
    }

    public function Guardar() {
        // Tu código original va aquí...
    }

    public function ActualizarProceso() {
        // Tu código original va aquí...
    }

    public function Anular() {
        // Tu código original va aquí...
    }

    // =====================================================================
    //              MÉTODOS PARA LA TIENDA ONLINE
    // =====================================================================

    /**
     * Muestra la página de checkout/confirmación de la tienda.
     */
    public function Checkout() {
        require_once 'view/tienda/checkout.php';
    }

    /**
     * Registra una venta que viene desde la tienda online (VERSIÓN DE DEPURACIÓN).
     */
    public function RegistrarDesdeTienda() {
        
        echo "<h1>Iniciando proceso de registro...</h1>";

        if(isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])){
            
            echo "<p><strong>Checkpoint 1:</strong> Carrito encontrado. Preparando datos...</p>";

            // 1. PREPARAMOS LOS DATOS DE LA VENTA PRINCIPAL
            $venta = new Ventas();
            $venta->id_cliente = 1;
            $venta->id_usuario_vendedor = 1;
            $venta->notas_obs = "Venta generada desde la tienda online.";
            $venta->etapa = 'Confirmada';
            $venta->tipo_venta = 1;

            // 2. PREPARAMOS LOS PRODUCTOS DEL CARRITO
            $productos_para_guardar = [];
            foreach ($_SESSION['carrito'] as $item) {
                // Verificamos que los datos necesarios existan en el carrito
                if(isset($item['id_producto']) && isset($item['unidades']) && isset($item['precio'])){
                    $productos_para_guardar[] = [
                        'id' => $item['id_producto'],
                        'cantidad' => $item['unidades'],
                        'precio' => $item['precio'],
                        'descuento' => 0
                    ];
                }
            }
            
            $servicios_para_guardar = [];

            // --- CHECKPOINT 2: MOSTRAMOS LOS DATOS QUE VAMOS A GUARDAR ---
            echo "<p><strong>Checkpoint 2:</strong> Datos listos para enviar al modelo. Por favor, revisa que sean correctos:</p>";
            echo "<pre>";
            
            echo "<strong>Objeto Venta:</strong><br>";
            print_r($venta);

            echo "<br><strong>Array de Productos:</strong><br>";
            print_r($productos_para_guardar);

            echo "</pre>";

            // --- DETENEMOS LA EJECUCIÓN A PROPÓSITO ---
            die("--- Fin de la depuración. El guardado en la base de datos se ha detenido. Revisa los datos de arriba. ---");

            // 3. LLAMAMOS A TU MÉTODO 'Crear' (No se ejecutará por ahora)
            $exito = $this->model->Crear($venta, $productos_para_guardar, $servicios_para_guardar);

            if($exito){
                unset($_SESSION['carrito']);
                require_once 'view/tienda/compra_exitosa.php';
            } else {
                echo "Error: No se pudo registrar la venta.";
            }

        } else {
            echo "<p><strong>ERROR:</strong> No se encontró nada en el carrito de compras.</p>";
            die();
        }
    }
}
?>