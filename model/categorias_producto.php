<?php
class CategoriaProducto
{
    private $conex;

    public $id_categoria_producto;
    public $nombre_cat;
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
            // Usamos la vista para ser consistentes
            $stm = $this->conex->prepare("SELECT * FROM vista_categorias_producto WHERE estado = 1 ORDER BY id_categoria_producto ASC");
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die("Error al listar categorías de producto: " . $e->getMessage());
        }
    }

    public function Obtener($id)
    {
        try {
            $stm = $this->conex->prepare("SELECT * FROM categorias_producto WHERE id_categoria_producto = ?");
            $stm->execute(array($id));
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die("Error al obtener categoría de producto: " . $e->getMessage());
        }
    }

    public function Eliminar($id)
    {
        try {
            // Usamos el SP correspondiente
            $stmt = $this->conex->prepare("CALL sp_categoriap_eliminar(:id)");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } catch (Exception $e) {
            die("Error al eliminar categoría de producto: " . $e->getMessage());
        }
    }

    public function Actualizar(CategoriaProducto $data)
    {
        try {
            // Usamos el SP correspondiente
            $stmt = $this->conex->prepare("CALL sp_categoriap_actualizar(:id, :nombre_cat, :descripcion, 1)");
            
            $stmt->bindParam(':id', $data->id_categoria_producto);
            $stmt->bindParam(':nombre_cat', $data->nombre_cat);
            $stmt->bindParam(':descripcion', $data->descripcion);

            $stmt->execute();
        } catch (Exception $e) {
            die("Error al actualizar categoría de producto: " . $e->getMessage());
        }
    }

    public function Registrar(CategoriaProducto $data)
    {
        try {
            // Usamos el SP correspondiente
            $stmt = $this->conex->prepare("CALL sp_categoriap_crear(:nombre_cat, :descripcion)");
            
            $stmt->bindParam(':nombre_cat', $data->nombre_cat);
            $stmt->bindParam(':descripcion', $data->descripcion);

            $stmt->execute();
        } catch (Exception $e) {
            die("Error al registrar categoría de producto: " . $e->getMessage());
        }
    }
}