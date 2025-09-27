<?php
class Cliente
{
    private $conex;

    public $id_cliente;
    public $codigo_cliente;
    public $tipo_cliente;
    public $direccion;
    public $telefono;
    public $correo;
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
            $stm = $this->conex->prepare("
                SELECT 
                    c.id_cliente, c.codigo_cliente, c.tipo_cliente, c.telefono, c.correo, c.estado, c.direccion, c.notas,
                    IFNULL(CONCAT(p.nombres, ' ', p.apellido_paterno), emp.razon_social) AS nombre_completo,
                    p.id_persona, p.nombres, p.apellido_paterno, p.apellido_materno, p.ci,
                    emp.id_empresa, emp.razon_social, emp.nombre_comercial, emp.identificador_fiscal
                FROM cliente c
                LEFT JOIN persona p ON c.id_cliente = p.id_cliente
                LEFT JOIN empresa emp ON c.id_cliente = emp.id_cliente
                WHERE c.estado = 1 
                ORDER BY c.id_cliente ASC
            ");
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die("Error al listar clientes: " . $e->getMessage());
        }
    }

    public function Obtener($id)
    {
        try {
            $stm = $this->conex->prepare("
                SELECT 
                    c.*, p.id_persona, p.nombres, p.apellido_paterno, p.apellido_materno, p.ci,
                    emp.id_empresa, emp.razon_social, emp.nombre_comercial, emp.identificador_fiscal
                FROM cliente c
                LEFT JOIN persona p ON c.id_cliente = p.id_cliente
                LEFT JOIN empresa emp ON c.id_cliente = emp.id_cliente
                WHERE c.id_cliente = ?
            ");
            $stm->execute(array($id));
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die("Error al obtener cliente: " . $e->getMessage());
        }
    }

    public function Eliminar($id)
    {
        try {
            $stmt = $this->conex->prepare("CALL sp_cliente_eliminar(:id)");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } catch (Exception $e) {
            die("Error al eliminar cliente: " . $e->getMessage());
        }
    }
    
    public function Actualizar(Cliente $data)
    {
        try {
            $stmt = $this->conex->prepare("CALL sp_cliente_actualizar(:id, :codigo, :direccion, :telefono, :correo, :notas, 1)");
            $stmt->bindParam(':id', $data->id_cliente);
            $stmt->bindParam(':codigo', $data->codigo_cliente);
            $stmt->bindParam(':direccion', $data->direccion);
            $stmt->bindParam(':telefono', $data->telefono);
            $stmt->bindParam(':correo', $data->correo);
            $stmt->bindParam(':notas', $data->notas);
            $stmt->execute();
        } catch (Exception $e) {
            die("Error al actualizar datos del cliente: " . $e->getMessage());
        }
    }

    public function Registrar(Cliente $data)
    {
        try {
            $stmt = $this->conex->prepare("CALL sp_cliente_crear(:codigo, :tipo, :direccion, :telefono, :correo, :notas)");
            $stmt->bindParam(':codigo', $data->codigo_cliente);
            $stmt->bindParam(':tipo', $data->tipo_cliente);
            $stmt->bindParam(':direccion', $data->direccion);
            $stmt->bindParam(':telefono', $data->telefono);
            $stmt->bindParam(':correo', $data->correo);
            $stmt->bindParam(':notas', $data->notas);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die("Error al registrar en tabla cliente: " . $e->getMessage());
        }
    }
}