<?php
// ARCHIVO: controller/ventas.controller.php (VERSIÓN FINAL COMPLETA)

require_once 'model/ventas.php';
require_once 'model/cliente.php';
require_once 'model/producto.php';
require_once 'model/servicios.php';
require_once 'model/usuarios.php';

class VentasController
{
    private $model;
    
    public function __construct() {
        $this->model = new Ventas();
    }

    // =====================================================================
    //          MÉTODOS ORIGINALES DEL PANEL DE ADMINISTRACIÓN
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
        $modelCliente = new Cliente();
        $modelProducto = new Producto();
        $modelServicio = new Servicios();
        $modelUsuario = new Usuario();

        $clientes = $modelCliente->Listar();
        $productos = $modelProducto->Listar();
        $servicios = $modelServicio->Listar();
        $usuarios = $modelUsuario->Listar();
        
        require_once 'view/header.php';
        require_once 'view/frmnuevaventa.php';
        require_once 'view/footer.php';
    }

    public function Guardar() {
        $venta = new Ventas();
        $venta->id_cliente = $_POST['id_cliente'];
        $venta->id_usuario_vendedor = $_POST['id_usuario_vendedor'];
        $venta->notas_obs = $_POST['notas_obs'];
        $venta->etapa = $_POST['etapa'];

        $productos = [];
        if (isset($_POST['productos_id'])) {
            for ($i = 0; $i < count($_POST['productos_id']); $i++) {
                $productos[] = [
                    'id' => $_POST['productos_id'][$i],
                    'cantidad' => $_POST['productos_cantidad'][$i],
                    'precio' => $_POST['productos_precio'][$i],
                    'descuento' => $_POST['productos_descuento'][$i]
                ];
            }
        }
        
        $servicios = [];
        if (isset($_POST['servicios_id'])) {
            for ($i = 0; $i < count($_POST['servicios_id']); $i++) {
                $servicios[] = [
                    'id' => $_POST['servicios_id'][$i],
                    'cantidad' => $_POST['servicios_cantidad'][$i],
                    'precio' => $_POST['servicios_precio'][$i]
                ];
            }
        }
        
        $tiene_productos = !empty($productos);
        $tiene_servicios = !empty($servicios);

        if ($tiene_productos && $tiene_servicios) {
            $venta->tipo_venta = 3; // Mixta
        } elseif ($tiene_productos) {
            $venta->tipo_venta = 1; // Solo Productos
        } elseif ($tiene_servicios) {
            $venta->tipo_venta = 2; // Solo Servicios
        } else {
            $venta->tipo_venta = null; // Venta vacía
        }

        $this->model->Crear($venta, $productos, $servicios);
        $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => 'La nueva venta ha sido registrada con éxito.'];
        header('Location: index.php?c=ventas&a=IndexPage');
        exit;
    }

    public function ActualizarProceso() {
        $uuid = $_POST['uuid'] ?? '';
        $etapa = $_POST['etapa'] ?? 'Confirmada';
        $estado_pago = $_POST['estado_pago'] ?? 'Pendiente';
        $notas = $_POST['notas_obs'] ?? '';
        
        if (!empty($uuid)) {
            $this->model->ActualizarProceso($uuid, $etapa, $estado_pago, $notas);
            $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => 'El proceso de la venta ha sido actualizado.'];
        }
        header('Location: index.php?c=ventas&a=IndexPage');
        exit;
    }

    public function Anular() {
        $uuid = $_REQUEST['uuid'] ?? '';
        if (!empty($uuid)) {
            $this->model->Anular($uuid);
            $_SESSION['mensaje'] = ['tipo' => 'info', 'texto' => 'La venta ha sido anulada correctamente.'];
        }
        header('Location: index.php?c=ventas&a=IndexPage');
        exit;
    }

    // =====================================================================
    //              MÉTODOS PARA LA TIENDA ONLINE
    // =====================================================================

    public function Checkout() {
        require_once 'view/tienda/checkout.php';
    }

    public function RegistrarDesdeTienda() {
        if(isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])){
            
            $ventaData = new Ventas();
            $ventaData->id_cliente = 1; 
            $ventaData->id_usuario_vendedor = 1;
            $ventaData->notas_obs = "Venta generada desde la tienda online.";
            $ventaData->etapa = 'Confirmada';
            $ventaData->tipo_venta = 1;

            $id_nueva_venta = $this->model->RegistrarVentaDesdeTienda($ventaData);
            
            if($id_nueva_venta){
                foreach($_SESSION['carrito'] as $item){
                    $this->model->RegistrarDetalleDesdeTienda(
                        $id_nueva_venta,
                        $item['id_producto'],
                        $item['unidades'],
                        $item['precio']
                    );
                }
                
                unset($_SESSION['carrito']);
                require_once 'view/tienda/compra_exitosa.php';
                
            } else {
                echo "Error: No se pudo registrar la venta. El modelo no devolvió un ID.";
            }

        } else {
            header('Location: index.php');
            exit();
        }
    }
}