<?php
// CORRECCIÓN: Nombre de la clase actualizado a plural para mantener consistencia.
class Servicios
{
    private $conex;

    // Propiedades del modelo
    public $id_servicio;
    public $id_categoria_servicio;
    public $codigo_servicio;
    public $nombre_servicio;
    public $descripcion;
    public $tipo_cobro;
    public $precio_base;
    public $duracion_estimada;
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
            // Se usa un JOIN para obtener el nombre de la categoría y se ordena por ASC
            $stm = $this->conex->prepare("
                SELECT 
                    s.*, 
                    cs.nombre_categoria_servicio 
                FROM servicios s
                LEFT JOIN categorias_servicio cs ON s.id_categoria_servicio = cs.id_categoria_servicio
                WHERE s.estado = 1 
                ORDER BY s.id_servicio ASC
            ");
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die("Error al listar servicios: " . $e->getMessage());
        }
    }

    public function Obtener($id)
    {
        try {
            $stm = $this->conex->prepare("SELECT * FROM servicios WHERE id_servicio = ?");
            $stm->execute(array($id));
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die("Error al obtener servicio: " . $e->getMessage());
        }
    }

    public function Eliminar($id)
    {
        try {
            $stmt = $this->conex->prepare("CALL sp_servicio_eliminar(:id)");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } catch (Exception $e) {
            die("Error al eliminar servicio: " . $e->getMessage());
        }
    }

    public function Editar(Servicios $data)
    {
        try {
            $stmt = $this->conex->prepare("CALL sp_servicio_actualizar(:id, :id_cat, :codigo, :nombre, :desc, :tipo_cobro, :precio, :duracion, 1)");
            
            $stmt->bindParam(':id', $data->id_servicio);
            $stmt->bindParam(':id_cat', $data->id_categoria_servicio);
            $stmt->bindParam(':codigo', $data->codigo_servicio);
            $stmt->bindParam(':nombre', $data->nombre_servicio);
            $stmt->bindParam(':desc', $data->descripcion);
            $stmt->bindParam(':tipo_cobro', $data->tipo_cobro);
            $stmt->bindParam(':precio', $data->precio_base);
            $stmt->bindParam(':duracion', $data->duracion_estimada);

            $stmt->execute();
        } catch (Exception $e) {
            die("Error al actualizar servicio: " . $e->getMessage());
        }
    }

    public function Insertar(Servicios $data)
    {
        try {
            $stmt = $this->conex->prepare("CALL sp_servicio_crear(:id_cat, :codigo, :nombre, :desc, :tipo_cobro, :precio, :duracion)");
            
            $stmt->bindParam(':id_cat', $data->id_categoria_servicio);
            $stmt->bindParam(':codigo', $data->codigo_servicio);
            $stmt->bindParam(':nombre', $data->nombre_servicio);
            $stmt->bindParam(':desc', $data->descripcion);
            $stmt->bindParam(':tipo_cobro', $data->tipo_cobro);
            $stmt->bindParam(':precio', $data->precio_base);
            $stmt->bindParam(':duracion', $data->duracion_estimada);

            $stmt->execute();
        } catch (Exception $e) {
            die("Error al insertar servicio: " . $e->getMessage());
        }
    }
}