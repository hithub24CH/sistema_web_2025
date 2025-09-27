<?php
class CategoriaServicio
{
    private $conex;

    public $id_categoria_servicio;
    public $nombre_categoria_servicio;
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
            $stm = $this->conex->prepare("SELECT * FROM vista_categorias_servicio WHERE estado = 1 ORDER BY id_categoria_servicio ASC");
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die("Error al listar categorías de servicio: " . $e->getMessage());
        }
    }

    public function Obtener($id)
    {
        try {
            $stm = $this->conex->prepare("SELECT * FROM categorias_servicio WHERE id_categoria_servicio = ?");
            $stm->execute(array($id));
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die("Error al obtener categoría de servicio: " . $e->getMessage());
        }
    }

    public function Eliminar($id)
    {
        try {
            $stmt = $this->conex->prepare("CALL sp_categoriags_eliminar(:id)");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } catch (Exception $e) {
            die("Error al eliminar categoría de servicio: " . $e->getMessage());
        }
    }

    public function Actualizar(CategoriaServicio $data)
    {
        try {
            $stmt = $this->conex->prepare("CALL sp_categoriags_actualizar(:id, :nombre, :desc, 1)");
            $stmt->bindParam(':id', $data->id_categoria_servicio);
            $stmt->bindParam(':nombre', $data->nombre_categoria_servicio);
            $stmt->bindParam(':desc', $data->descripcion);
            $stmt->execute();
        } catch (Exception $e) {
            die("Error al actualizar categoría de servicio: " . $e->getMessage());
        }
    }

    public function Registrar(CategoriaServicio $data)
    {
        try {
            $stmt = $this->conex->prepare("CALL sp_categoriags_crear(:nombre, :desc)");
            $stmt->bindParam(':nombre', $data->nombre_categoria_servicio);
            $stmt->bindParam(':desc', $data->descripcion);
            $stmt->execute();
        } catch (Exception $e) {
            die("Error al registrar categoría de servicio: " . $e->getMessage());
        }
    }
}