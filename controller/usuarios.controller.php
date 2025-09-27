<?php
require_once 'model/usuarios.php';
require_once 'model/empleado.php';
require_once 'model/roles.php';

class UsuariosController
{
    private $model;
    private $modelEmpleado;
    private $modelRol;

    public function __construct()
    {
        $this->model = new Usuario();
        $this->modelEmpleado = new Empleado();
        $this->modelRol = new Rol();
    }

    public function IndexPage()
    {
        // Pasamos la lista de empleados y roles a la vista para los <select>
        $empleados = $this->modelEmpleado->Listar();
        $roles = $this->modelRol->Listar();
        
        require_once 'view/header.php';
        require_once 'view/frmusuarios.php';
        require_once 'view/footer.php';
    }

    public function InsEditar()
    {
        $usr = new Usuario();
        $accion = $_REQUEST['ac'] ?? '';
        
        $usr->id_usuario = $_POST['id_usuario'] ?? null;
        $usr->id_empleado = $_POST['id_empleado'] ?? null;
        $usr->id_rol = $_POST['id_rol'] ?? null;
        $usr->nombre_usuario = $_POST['nombre_usuario'] ?? '';
        $usr->correo_login = $_POST['correo_login'] ?? '';
        
        // La contraseña solo se establece al crear un nuevo usuario
        if ($accion === 'nuevo') {
            $usr->contraseña_login = $_POST['contraseña_login'] ?? '';
            $this->model->Registrar($usr);
        } elseif ($accion === 'editar') {
            $this->model->Actualizar($usr);
        }

        header('Location: index.php?c=usuarios');
        exit;
    }

    public function Eliminar()
    {
        $id = $_REQUEST['id_usuario'] ?? 0;
        if ($id > 0) {
            $this->model->Eliminar($id);
        }
        header('Location: index.php?c=usuarios');
        exit;
    }
}