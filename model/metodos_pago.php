<?php
class MetodoPago
{
    private $conex;

    public $id_metodo_pago;
    public $nombre_metodo;
    public $descripcion;
    public $estado;

    public function __CONSTRUCT()
    {
        try {
            require_once 'conexion.php';
            $this->conex = new Conexion();
            $this->conex = $this->conex->conectar();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function Listar()
    {
        try {
            $stm = $this->conex->prepare("SELECT * FROM vista_metodos_pago WHERE estado = 1 ORDER BY id_metodo_pago ASC");
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die("Error al listar métodos de pago: " . $e->getMessage());
        }
    }

    public function Obtener($id)
    {
        try {
            $stm = $this->conex->prepare("SELECT * FROM metodos_pago WHERE id_metodo_pago = ?");
            $stm->execute(array($id));
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die("Error al obtener método de pago: " . $e->getMessage());
        }
    }

    public function Eliminar($id)
    {
        try {
            $stmt = $this->conex->prepare("CALL sp_metodo_pago_eliminar(:id)");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } catch (Exception $e) {
            die("Error al eliminar método de pago: " . $e->getMessage());
        }
    }

    public function Actualizar(MetodoPago $data)
    {
        try {
            $stmt = $this->conex->prepare("CALL sp_metodo_pago_actualizar(:id, :nombre, :desc, 1)");
            $stmt->bindParam(':id', $data->id_metodo_pago);
            $stmt->bindParam(':nombre', $data->nombre_metodo);
            $stmt->bindParam(':desc', $data->descripcion);
            $stmt->execute();
        } catch (Exception $e) {
            die("Error al actualizar método de pago: " . $e->getMessage());
        }
    }

    public function Registrar(MetodoPago $data)
    {
        try {
            $stmt = $this->conex->prepare("CALL sp_metodo_pago_crear(:nombre, :desc)");
            $stmt->bindParam(':nombre', $data->nombre_metodo);
            $stmt->bindParam(':desc', $data->descripcion);
            $stmt->execute();
        } catch (Exception $e) {
            die("Error al registrar método de pago: " . $e->getMessage());
        }
    }
}