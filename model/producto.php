<?php
class Producto
{
    private $conex;

    public $id_producto;
    public $id_categoria_producto;
    public $codigo_sku;
    public $nombre_producto;
    public $imagen;
    public $marca;
    public $nro_serie;
    public $descripcion;
    public $precio_venta_unit;
    public $costo_unit_adquisicion;
    public $stock_actual;
    public $unidad_medida;
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
            $stm = $this->conex->prepare("
                SELECT 
                    p.*, 
                    cp.nombre_cat as nombre_categoria 
                FROM producto p
                LEFT JOIN categorias_producto cp ON p.id_categoria_producto = cp.id_categoria_producto
                WHERE p.estado = 1 
                ORDER BY p.id_producto ASC
            ");
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die("Error al listar productos: " . $e->getMessage());
        }
    }

    public function Obtener($id)
    {
        try {
            $stm = $this->conex->prepare("SELECT * FROM producto WHERE id_producto = ?");
            $stm->execute(array($id));
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die("Error al obtener producto: " . $e->getMessage());
        }
    }

    public function Eliminar($id)
    {
        try {
            $stmt = $this->conex->prepare("CALL sp_producto_eliminar(:id)");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } catch (Exception $e) {
            die("Error al eliminar producto: " . $e->getMessage());
        }
    }

    public function Actualizar(Producto $data)
    {
        try {
            $stmt = $this->conex->prepare("CALL sp_producto_actualizar(:id, :id_cat, :sku, :nombre, :img, :marca, :nro_serie, :desc, :precio, :costo, :stock, :unidad, 1)");
            
            $stmt->bindParam(':id', $data->id_producto);
            $stmt->bindParam(':id_cat', $data->id_categoria_producto);
            $stmt->bindParam(':sku', $data->codigo_sku);
            $stmt->bindParam(':nombre', $data->nombre_producto);
            $stmt->bindParam(':img', $data->imagen);
            $stmt->bindParam(':marca', $data->marca);
            $stmt->bindParam(':nro_serie', $data->nro_serie);
            $stmt->bindParam(':desc', $data->descripcion);
            $stmt->bindParam(':precio', $data->precio_venta_unit);
            $stmt->bindParam(':costo', $data->costo_unit_adquisicion);
            $stmt->bindParam(':stock', $data->stock_actual);
            $stmt->bindParam(':unidad', $data->unidad_medida);

            $stmt->execute();
        } catch (Exception $e) {
            die("Error al actualizar producto: " . $e->getMessage());
        }
    }

    public function Registrar(Producto $data)
    {
        try {
            $stmt = $this->conex->prepare("CALL sp_producto_crear(:id_cat, :sku, :nombre, :img, :marca, :nro_serie, :desc, :precio, :costo, :stock, :unidad)");
            
            $stmt->bindParam(':id_cat', $data->id_categoria_producto);
            $stmt->bindParam(':sku', $data->codigo_sku);
            $stmt->bindParam(':nombre', $data->nombre_producto);
            $stmt->bindParam(':img', $data->imagen);
            $stmt->bindParam(':marca', $data->marca);
            $stmt->bindParam(':nro_serie', $data->nro_serie);
            $stmt->bindParam(':desc', $data->descripcion);
            $stmt->bindParam(':precio', $data->precio_venta_unit);
            $stmt->bindParam(':costo', $data->costo_unit_adquisicion);
            $stmt->bindParam(':stock', $data->stock_actual);
            $stmt->bindParam(':unidad', $data->unidad_medida);

            $stmt->execute();
        } catch (Exception $e) {
            die("Error al registrar producto: " . $e->getMessage());
        }
    }
}