<?php
// ARCHIVO: controller/compras.controller.php

require_once 'model/compras.php';
require_once 'model/proveedor.php';
require_once 'model/producto.php';
require_once 'model/usuarios.php';

class ComprasController {
    private $model;
    private $modelProveedor;
    private $modelProducto;
    private $modelUsuario;

    public function __construct() {
        $this->model = new Compras();
        $this->modelProveedor = new Proveedor();
        $this->modelProducto = new Producto();
        $this->modelUsuario = new Usuario();
    }

    public function Index()
{
    // Llama a la función que ya tenías y que muestra la página principal.
    // Reemplaza 'IndexPage' por el nombre real de tu función si es diferente.
    $this->IndexPage(); 
}
    public function IndexPage() {
        require_once 'view/header.php';
        require_once 'view/frmcompras.php';
        require_once 'view/footer.php';
    }

    public function Nueva() {
        $proveedores = $this->modelProveedor->Listar();
        $productos = $this->modelProducto->Listar();
        $usuarios = $this->modelUsuario->Listar();
        require_once 'view/header.php';
        require_once 'view/frmnuevacompra.php';
        require_once 'view/footer.php';
    }

    public function Guardar() {
        $compra = new Compras();
        $compra->id_proveedor = $_POST['id_proveedor'];
        $compra->id_usuario_comprador = $_POST['id_usuario_comprador'];
        $compra->total_compra = $_POST['total_final'];
        $compra->notas_obs = $_POST['notas_obs'];
        $compra->etapa = $_POST['etapa'];
        $detalle = [];
        if (isset($_POST['productos_id'])) {
            for ($i = 0; $i < count($_POST['productos_id']); $i++) {
                $detalle[] = ['id_producto' => $_POST['productos_id'][$i], 'cantidad' => $_POST['productos_cantidad'][$i], 'costo_unitario' => $_POST['productos_costo'][$i]];
            }
        }
        $this->model->Crear($compra, $detalle);
        $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => 'La nueva compra ha sido registrada con éxito.'];
        header('Location: index.php?c=compras&a=IndexPage');
        exit;
    }

    public function ActualizarProceso() {
        $uuid = $_POST['uuid'] ?? '';
        $nuevo_estado = $_POST['estado'] ?? 'Solicitada';
        $nueva_etapa = $_POST['etapa'] ?? 'Indefinida';
        if (!empty($uuid)) {
            $this->model->ActualizarProceso($uuid, $nuevo_estado, $nueva_etapa);
            $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => 'El proceso de la compra ha sido actualizado.'];
        } else {
            $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error: No se encontró el identificador de la compra.'];
        }
        header('Location: index.php?c=compras&a=IndexPage');
        exit;
    }

    public function Anular() {
        $uuid = $_REQUEST['uuid'] ?? '';
        if (!empty($uuid)) {
            $this->model->Anular($uuid);
            $_SESSION['mensaje'] = ['tipo' => 'info', 'texto' => 'La compra ha sido anulada correctamente.'];
        } else {
            $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error: No se encontró el identificador de la compra.'];
        }
        header('Location: index.php?c=compras&a=IndexPage');
        exit;
    }
}