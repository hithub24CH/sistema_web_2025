<?php
class Empleado
{
    private $conex;

    public $id_empleado;
    public $ci;
    public $nombre;
    public $appaterno;
    public $apmaterno;
    public $fecha_nacimiento;
    public $imagen;
    public $genero;
    public $estado_civil;
    public $nacionalidad;
    public $cargo;
    public $telefono;
    public $direccion;
    public $correo;
    public $notas_observaciones;
    public $fecha_ingreso;
    public $fecha_fin_contrato;
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
            $stm = $this->conex->prepare("SELECT * FROM vista_empleados WHERE estado = 1 ORDER BY id_empleado ASC");
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die("Error al listar empleados: " . $e->getMessage());
        }
    }

    public function Obtener($id)
    {
        try {
            $stm = $this->conex->prepare("SELECT * FROM empleado WHERE id_empleado = ?");
            $stm->execute(array($id));
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die("Error al obtener empleado: " . $e->getMessage());
        }
    }

    public function Eliminar($id)
    {
        try {
            $stmt = $this->conex->prepare("CALL sp_empleado_eliminar(:id)");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } catch (Exception $e) {
            die("Error al eliminar empleado: " . $e->getMessage());
        }
    }

    public function Actualizar(Empleado $data)
    {
        try {
            $stmt = $this->conex->prepare("CALL sp_empleado_actualizar(:id, :ci, :nombre, :appaterno, :apmaterno, :fecha_nac, :img, :genero, :estado_civil, :nacionalidad, :cargo, :tel, :dir, :correo, :notas, :fecha_ing, :fecha_fin, 1)");
            
            $stmt->bindParam(':id', $data->id_empleado);
            $stmt->bindParam(':ci', $data->ci);
            $stmt->bindParam(':nombre', $data->nombre);
            $stmt->bindParam(':appaterno', $data->appaterno);
            $stmt->bindParam(':apmaterno', $data->apmaterno);
            $stmt->bindParam(':fecha_nac', $data->fecha_nacimiento);
            $stmt->bindParam(':img', $data->imagen);
            $stmt->bindParam(':genero', $data->genero);
            $stmt->bindParam(':estado_civil', $data->estado_civil);
            $stmt->bindParam(':nacionalidad', $data->nacionalidad);
            $stmt->bindParam(':cargo', $data->cargo);
            $stmt->bindParam(':tel', $data->telefono);
            $stmt->bindParam(':dir', $data->direccion);
            $stmt->bindParam(':correo', $data->correo);
            $stmt->bindParam(':notas', $data->notas_observaciones);
            $stmt->bindParam(':fecha_ing', $data->fecha_ingreso);
            $stmt->bindParam(':fecha_fin', $data->fecha_fin_contrato);

            $stmt->execute();
        } catch (Exception $e) {
            die("Error al actualizar empleado: " . $e->getMessage());
        }
    }

    public function Registrar(Empleado $data)
    {
        try {
            $stmt = $this->conex->prepare("CALL sp_empleado_crear(:ci, :nombre, :appaterno, :apmaterno, :fecha_nac, :img, :genero, :estado_civil, :nacionalidad, :cargo, :tel, :dir, :correo, :notas, :fecha_ing, :fecha_fin)");
            
            $stmt->bindParam(':ci', $data->ci);
            $stmt->bindParam(':nombre', $data->nombre);
            $stmt->bindParam(':appaterno', $data->appaterno);
            $stmt->bindParam(':apmaterno', $data->apmaterno);
            $stmt->bindParam(':fecha_nac', $data->fecha_nacimiento);
            $stmt->bindParam(':img', $data->imagen);
            $stmt->bindParam(':genero', $data->genero);
            $stmt->bindParam(':estado_civil', $data->estado_civil);
            $stmt->bindParam(':nacionalidad', $data->nacionalidad);
            $stmt->bindParam(':cargo', $data->cargo);
            $stmt->bindParam(':tel', $data->telefono);
            $stmt->bindParam(':dir', $data->direccion);
            $stmt->bindParam(':correo', $data->correo);
            $stmt->bindParam(':notas', $data->notas_observaciones);
            $stmt->bindParam(':fecha_ing', $data->fecha_ingreso);
            $stmt->bindParam(':fecha_fin', $data->fecha_fin_contrato);

            $stmt->execute();
        } catch (Exception $e) {
            die("Error al registrar empleado: " . $e->getMessage());
        }
    }
}