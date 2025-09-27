<?php
require_once 'model/roles.php';

// Nombre de clase "RolesController" compatible con el enrutador
class RolesController
{
    private $model;

    public function __construct()
    {
        $this->model = new Rol();
    }

    // Método "IndexPage" compatible con el enrutador
    public function IndexPage()
    {
        require_once 'view/header.php';
        require_once 'view/frmroles.php';
        require_once 'view/footer.php';
    }

    public function InsEditar()
    {
        $rol = new Rol();
        $rol->id_rol = $_POST['id_rol'] ?? null;
        $rol->nombre_rol = $_POST['nombre_rol'] ?? '';
        $rol->descripcion = $_POST['descripcion'] ?? null;

        $accion = $_REQUEST['ac'] ?? '';

        if ($accion === 'nuevo') {
            $this->model->Registrar($rol);
        } elseif ($accion === 'editar') {
            $this->model->Actualizar($rol);
        }

        header('Location: index.php?c=roles');
        exit;
    }

    public function Eliminar()
    {
        $id = $_REQUEST['id_rol'] ?? 0;
        if ($id > 0) {
            $this->model->Eliminar($id);
        }
        header('Location: index.php?c=roles');
        exit;
    }
}