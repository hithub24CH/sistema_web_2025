<?php
class Rol
{
    private $conex;

    public $id_rol;
    public $nombre_rol;
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
            $stm = $this->conex->prepare("SELECT * FROM vista_roles WHERE estado = 1 ORDER BY id_rol ASC");
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die("Error al listar roles: " . $e->getMessage());
        }
    }

    public function Obtener($id)
    {
        try {
            $stm = $this->conex->prepare("SELECT * FROM roles WHERE id_rol = ?");
            $stm->execute(array($id));
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die("Error al obtener rol: " . $e->getMessage());
        }
    }

    public function Eliminar($id)
    {
        try {
            $stmt = $this->conex->prepare("CALL sp_rol_eliminar(:id)");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } catch (Exception $e) {
            die("Error al eliminar rol: " . $e->getMessage());
        }
    }

    public function Actualizar(Rol $data)
    {
        try {
            $stmt = $this->conex->prepare("CALL sp_rol_actualizar(:id, :nombre_rol, :descripcion, 1)");
            $stmt->bindParam(':id', $data->id_rol);
            $stmt->bindParam(':nombre_rol', $data->nombre_rol);
            $stmt->bindParam(':descripcion', $data->descripcion);
            $stmt->execute();
        } catch (Exception $e) {
            die("Error al actualizar rol: " . $e->getMessage());
        }
    }

    public function Registrar(Rol $data)
    {
        try {
            $stmt = $this->conex->prepare("CALL sp_rol_crear(:nombre_rol, :descripcion)");
            $stmt->bindParam(':nombre_rol', $data->nombre_rol);
            $stmt->bindParam(':descripcion', $data->descripcion);
            $stmt->execute();
        } catch (Exception $e) {
            die("Error al registrar rol: " . $e->getMessage());
        }
    }
}