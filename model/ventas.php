<?php
// ARCHIVO: model/ventas.php (VERSIÓN FINAL COMPLETA)

require_once 'conexion.php';

class Ventas
{
    private $conex;
    public $id_venta;
    public $uuid;
    public $id_cliente;
    public $id_usuario_vendedor;
    public $etapa;
    public $estado_pago;
    public $notas_obs;
    public $tipo_venta;

    public function __construct() { $this->conex = conexion::Conectar(); }

    public function Listar() {
        $stmt = $this->conex->prepare("SELECT * FROM vista_ventas ORDER BY id_venta DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function ObtenerDetalles($uuid) {
        $stmtCabecera = $this->conex->prepare("SELECT * FROM vista_ventas WHERE uuid = ?");
        $stmtCabecera->execute([$uuid]);
        $cabecera = $stmtCabecera->fetch(PDO::FETCH_OBJ);
        if(!$cabecera) return ['cabecera' => null, 'productos' => [], 'servicios' => []];
        $stmtProductos = $this->conex->prepare("SELECT dp.*, p.nombre_producto FROM detalle_producto dp JOIN producto p ON dp.id_producto = p.id_producto WHERE dp.id_venta = ?");
        $stmtProductos->execute([$cabecera->id_venta]);
        $productos = $stmtProductos->fetchAll(PDO::FETCH_OBJ);
        $stmtServicios = $this->conex->prepare("SELECT ds.*, s.nombre_servicio FROM detalle_servicio ds JOIN servicios s ON ds.id_servicio = s.id_servicio WHERE ds.id_venta = ?");
        $stmtServicios->execute([$cabecera->id_venta]);
        $servicios = $stmtServicios->fetchAll(PDO::FETCH_OBJ);
        return ['cabecera' => $cabecera, 'productos' => $productos, 'servicios' => $servicios];
    }

    public function Crear(Ventas $data, array $productos, array $servicios) {
        try {
            $this->conex->beginTransaction();
            $stmtVenta = $this->conex->prepare("CALL sp_venta_crear(:id_cliente, :id_usuario, :notas, :etapa, :tipo_venta)");
            $stmtVenta->execute([':id_cliente' => $data->id_cliente, ':id_usuario' => $data->id_usuario_vendedor, ':notas' => $data->notas_obs, ':etapa' => $data->etapa, ':tipo_venta' => $data->tipo_venta]);
            $resultado = $stmtVenta->fetch(PDO::FETCH_ASSOC);
            $id_nueva_venta = $resultado['nueva_venta_id'];
            $stmtVenta->closeCursor();
            foreach ($productos as $prod) {
                $stmtDetalle = $this->conex->prepare("CALL sp_detalle_producto_crear(:id_venta, :id_prod, :tipo, :cant, :precio, :desc)");
                $stmtDetalle->execute([':id_venta' => $id_nueva_venta, ':id_prod' => $prod['id'], ':tipo' => 'Venta', ':cant' => $prod['cantidad'], ':precio' => $prod['precio'], ':desc' => $prod['descuento']]);
            }
            foreach ($servicios as $serv) {
                $stmtDetalle = $this->conex->prepare("CALL sp_detalle_servicio_crear(:id_venta, :id_serv, :tipo, :cant, :precio, :total, :fecha)");
                $stmtDetalle->execute([':id_venta' => $id_nueva_venta, ':id_serv' => $serv['id'], ':tipo' => 'Servicio', ':cant' => $serv['cantidad'], ':precio' => $serv['precio'], ':total' => null, ':fecha' => null]);
            }
            $this->conex->commit();
            return true;
        } catch (Exception $e) {
            $this->conex->rollBack();
            die("Error en Modelo Venta (Crear): " . $e->getMessage());
        }
    }

    public function ActualizarProceso($uuid, $nueva_etapa, $nuevo_estado_pago, $notas) {
        $stmt = $this->conex->prepare("CALL sp_venta_actualizar(:uuid, :etapa, :estado_pago, :notas)");
        $stmt->execute([':uuid' => $uuid, ':etapa' => $nueva_etapa, ':estado_pago' => $nuevo_estado_pago, ':notas' => $notas]);
    }

    public function Anular($uuid) {
        $stmt = $this->conex->prepare("CALL sp_venta_eliminar(:uuid)");
        $stmt->execute([':uuid' => $uuid]);
    }

    // --- MÉTODOS PARA LA TIENDA ONLINE ---

    public function RegistrarVentaDesdeTienda(Ventas $data) {
        try {
            $stmt = $this->conex->prepare("CALL sp_venta_crear(?, ?, ?, ?, ?)");
            $stmt->execute([$data->id_cliente, $data->id_usuario_vendedor, $data->notas_obs, $data->etapa, $data->tipo_venta]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            // Asegurarse de que la clave 'nueva_venta_id' exista antes de devolverla
            return $resultado['nueva_venta_id'] ?? false;
        } catch (Exception $e) {
            die("ERROR FATAL DENTRO DEL MODELO (RegistrarVentaDesdeTienda): " . $e->getMessage());
        }
    }

    public function RegistrarDetalleDesdeTienda($id_venta, $id_producto, $cantidad, $precio) {
        try {
            $stmt = $this->conex->prepare("CALL sp_detalle_producto_crear(?, ?, ?, ?, ?, ?)");
            $stmt->execute([$id_venta, $id_producto, 'Venta', $cantidad, $precio, 0]);
        } catch (Exception $e) {
            die("ERROR FATAL DENTRO DEL MODELO (RegistrarDetalleDesdeTienda): " . $e->getMessage());
        }
    }
}