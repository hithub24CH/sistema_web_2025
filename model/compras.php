<?php
// ARCHIVO: model/compras.php

require_once 'conexion.php';

class Compras
{
    private $conex;

    // --- PROPIEDADES DE LA CLASE ---
    public $id_compra;
    public $uuid;
    public $id_proveedor;
    public $id_usuario_comprador;
    public $etapa;
    
    // CORRECCIÓN: Se añaden las propiedades que faltaban y que el editor detectó.
    public $total_compra;
    public $notas_obs;
    
    // Propiedades de las vistas
    public $proveedor;
    public $comprador;
    public $estado;

    public function __construct() { 
        $this->conex = conexion::Conectar(); 
    }

    public function Listar() {
        $stmt = $this->conex->prepare("SELECT * FROM vista_compras ORDER BY id_compra DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function ObtenerDetalles($uuid) {
        $stmtCabecera = $this->conex->prepare("SELECT * FROM vista_compras WHERE uuid = ?");
        $stmtCabecera->execute([$uuid]);
        $cabecera = $stmtCabecera->fetch(PDO::FETCH_OBJ);
        
        if (!$cabecera) {
            return ['cabecera' => null, 'detalle' => []]; // Evita errores si no encuentra la compra
        }

        $stmtDetalle = $this->conex->prepare("SELECT * FROM vista_detalle_compra WHERE id_compra = ?");
        $stmtDetalle->execute([$cabecera->id_compra]);
        $detalle = $stmtDetalle->fetchAll(PDO::FETCH_OBJ);

        return ['cabecera' => $cabecera, 'detalle' => $detalle];
    }

    public function Crear(Compras $data, array $detalle) {
        try {
            $this->conex->beginTransaction();
            $stmtCompra = $this->conex->prepare("CALL sp_compra_crear(:id_prov, :id_usuario, :total, :notas, :etapa)");
            $stmtCompra->execute([
                ':id_prov' => $data->id_proveedor,
                ':id_usuario' => $data->id_usuario_comprador,
                ':total' => $data->total_compra,
                ':notas' => $data->notas_obs,
                ':etapa' => $data->etapa
            ]);
            $resultado = $stmtCompra->fetch(PDO::FETCH_ASSOC);
            $id_nueva_compra = $resultado['nueva_compra_id'];
            $stmtCompra->closeCursor();

            foreach ($detalle as $prod) {
                $stmtDetalle = $this->conex->prepare("CALL sp_detalle_compra_crear(:id_compra, :id_prod, :cant, :precio, :notas)");
                $stmtDetalle->execute([
                    ':id_compra' => $id_nueva_compra,
                    ':id_prod' => $prod['id_producto'],
                    ':cant' => $prod['cantidad'],
                    ':precio' => $prod['costo_unitario'],
                    ':notas' => null
                ]);
            }
            $this->conex->commit();
            return true;
        } catch (Exception $e) {
            $this->conex->rollBack();
            die("Error en Modelo (Crear): " . $e->getMessage());
        }
    }

    public function ActualizarProceso($uuid, $nuevo_estado, $nueva_etapa) {
        try {
            $stmt = $this->conex->prepare("CALL sp_compra_actualizar_proceso(:uuid, :estado, :etapa)");
            $stmt->execute([':uuid' => $uuid, ':estado' => $nuevo_estado, ':etapa' => $nueva_etapa]);
        } catch (Exception $e) {
            die("Error en Modelo (Actualizar): " . $e->getMessage());
        }
    }

    public function Anular($uuid) {
        $stmt = $this->conex->prepare("CALL sp_compra_eliminar(:uuid)");
        $stmt->execute([':uuid' => $uuid]);
    }
}