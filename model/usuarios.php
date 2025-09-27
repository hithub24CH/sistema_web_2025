<?php
class Usuario
{
    private $conex;

    public $id_usuario;
    public $id_empleado;
    public $id_rol;
    public $nombre_usuario;
    public $correo_login;
    public $contraseña_login;
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
            // Creamos una consulta más completa para la vista
            $stm = $this->conex->prepare("
                SELECT 
                    u.id_usuario,
                    u.nombre_usuario,
                    u.correo_login,
                    r.nombre_rol,
                    CONCAT(e.nombre, ' ', e.appaterno) AS nombre_empleado,
                    u.estado
                FROM usuarios u
                JOIN roles r ON u.id_rol = r.id_rol
                JOIN empleado e ON u.id_empleado = e.id_empleado
                WHERE u.estado = 1
                ORDER BY u.id_usuario ASC
            ");
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die("Error al listar usuarios: " . $e->getMessage());
        }
    }

    public function Obtener($id)
    {
        try {
            $stm = $this->conex->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
            $stm->execute(array($id));
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die("Error al obtener usuario: " . $e->getMessage());
        }
    }

    public function Eliminar($id)
    {
        try {
            $stmt = $this->conex->prepare("CALL sp_usuario_eliminar(:id)");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } catch (Exception $e) {
            die("Error al eliminar usuario: " . $e->getMessage());
        }
    }

    // El SP de actualizar solo permite cambiar el rol, nombre de usuario y estado.
    public function Actualizar(Usuario $data)
    {
        try {
            $stmt = $this->conex->prepare("CALL sp_usuario_actualizar(:id, :id_rol, :nombre_usuario, 1)");
            
            $stmt->bindParam(':id', $data->id_usuario);
            $stmt->bindParam(':id_rol', $data->id_rol);
            $stmt->bindParam(':nombre_usuario', $data->nombre_usuario);

            $stmt->execute();
        } catch (Exception $e) {
            die("Error al actualizar usuario: " . $e->getMessage());
        }
    }

    public function Registrar(Usuario $data)
    {
        try {
            // Hasheamos la contraseña antes de guardarla por seguridad
            $hashed_password = password_hash($data->contraseña_login, PASSWORD_BCRYPT);

            $stmt = $this->conex->prepare("CALL sp_usuario_crear(:id_empleado, :id_rol, :nombre_usuario, :correo, :pass)");
            
            $stmt->bindParam(':id_empleado', $data->id_empleado);
            $stmt->bindParam(':id_rol', $data->id_rol);
            $stmt->bindParam(':nombre_usuario', $data->nombre_usuario);
            $stmt->bindParam(':correo', $data->correo_login);
            $stmt->bindParam(':pass', $hashed_password);

            $stmt->execute();
        } catch (Exception $e) {
            die("Error al registrar usuario: " . $e->getMessage());
        }
    }
}