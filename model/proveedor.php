<?php
class Proveedor
{
    private $conex;

    public $id_proveedor;
    public $nombre_proveedor;
    public $identificador_fiscal;
    public $contacto_principal;
    public $telefono;
    public $correo;
    public $direccion;
    public $id_categoria_producto;
    public $notas;
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
            // Usamos la vista 'vista_proveedores' que ya une la categoría para ser más eficientes
            $stm = $this->conex->prepare("SELECT * FROM vista_proveedores WHERE estado = 1 ORDER BY id_proveedor ASC");
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die("Error al listar proveedores: " . $e->getMessage());
        }
    }

    public function Obtener($id)
    {
        try {
            $stm = $this->conex->prepare("SELECT * FROM proveedor WHERE id_proveedor = ?");
            $stm->execute(array($id));
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die("Error al obtener proveedor: " . $e->getMessage());
        }
    }

    public function Eliminar($id)
    {
        try {
            $stmt = $this->conex->prepare("CALL sp_proveedor_eliminar(:id)");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } catch (Exception $e) {
            die("Error al eliminar proveedor: " . $e->getMessage());
        }
    }

    public function Actualizar(Proveedor $data)
    {
        try {
            $stmt = $this->conex->prepare("CALL sp_proveedor_actualizar(:id, :nombre, :id_fiscal, :contacto, :tel, :correo, :dir, :id_cat, :notas, 1)");
            
            $stmt->bindParam(':id', $data->id_proveedor);
            $stmt->bindParam(':nombre', $data->nombre_proveedor);
            $stmt->bindParam(':id_fiscal', $data->identificador_fiscal);
            $stmt->bindParam(':contacto', $data->contacto_principal);
            $stmt->bindParam(':tel', $data->telefono);
            $stmt->bindParam(':correo', $data->correo);
            $stmt->bindParam(':dir', $data->direccion);
            $stmt->bindParam(':id_cat', $data->id_categoria_producto);
            $stmt->bindParam(':notas', $data->notas);

            $stmt->execute();
        } catch (Exception $e) {
            die("Error al actualizar proveedor: " . $e->getMessage());
        }
    }

    public function Registrar(Proveedor $data)
    {
        try {
            $stmt = $this->conex->prepare("CALL sp_proveedor_crear(:nombre, :id_fiscal, :contacto, :tel, :correo, :dir, :id_cat, :notas)");
            
            $stmt->bindParam(':nombre', $data->nombre_proveedor);
            $stmt->bindParam(':id_fiscal', $data->identificador_fiscal);
            $stmt->bindParam(':contacto', $data->contacto_principal);
            $stmt->bindParam(':tel', $data->telefono);
            $stmt->bindParam(':correo', $data->correo);
            $stmt->bindParam(':dir', $data->direccion);
            $stmt->bindParam(':id_cat', $data->id_categoria_producto);
            $stmt->bindParam(':notas', $data->notas);

            $stmt->execute();
        } catch (Exception $e) {
            die("Error al registrar proveedor: " . $e->getMessage());
        }
    }
}