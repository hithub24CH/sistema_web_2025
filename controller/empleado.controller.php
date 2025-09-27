<?php
require_once 'model/empleado.php';

class EmpleadoController
{
    private $model;

    public function __construct()
    {
        $this->model = new Empleado();
    }

    public function IndexPage()
    {
        require_once 'view/header.php';
        require_once 'view/frmempleado.php';
        require_once 'view/footer.php';
    }

    public function InsEditar()
    {
        $emp = new Empleado();
        $accion = $_REQUEST['ac'] ?? '';
        $nombre_imagen_final = null;

        $emp->id_empleado = $_POST['id_empleado'] ?? null;
        $emp->ci = $_POST['ci'] ?? '';
        $emp->nombre = $_POST['nombre'] ?? '';
        $emp->appaterno = $_POST['appaterno'] ?? '';
        $emp->apmaterno = $_POST['apmaterno'] ?? null;
        $emp->fecha_nacimiento = !empty($_POST['fecha_nacimiento']) ? $_POST['fecha_nacimiento'] : null;
        $emp->genero = $_POST['genero'] ?? null;
        $emp->estado_civil = $_POST['estado_civil'] ?? null;
        $emp->nacionalidad = $_POST['nacionalidad'] ?? null;
        $emp->cargo = $_POST['cargo'] ?? '';
        $emp->telefono = $_POST['telefono'] ?? null;
        $emp->direccion = $_POST['direccion'] ?? null;
        $emp->correo = $_POST['correo'] ?? null;
        $emp->notas_observaciones = $_POST['notas_observaciones'] ?? null;
        $emp->fecha_ingreso = !empty($_POST['fecha_ingreso']) ? $_POST['fecha_ingreso'] : date('Y-m-d');
        $emp->fecha_fin_contrato = !empty($_POST['fecha_fin_contrato']) ? $_POST['fecha_fin_contrato'] : null;

        if ($accion === 'editar') {
            $empleado_existente = $this->model->Obtener($emp->id_empleado);
            if ($empleado_existente) {
                $nombre_imagen_final = $empleado_existente->imagen;
            }
        }

        if (isset($_FILES['imagen_nueva']) && $_FILES['imagen_nueva']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/empleados/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileName = uniqid() . '_' . basename($_FILES['imagen_nueva']['name']);
            $targetFilePath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['imagen_nueva']['tmp_name'], $targetFilePath)) {
                $nombre_imagen_final = $fileName;
            }
        }

        $emp->imagen = $nombre_imagen_final;

        if ($accion === 'nuevo') {
            $this->model->Registrar($emp);
        } elseif ($accion === 'editar') {
            $this->model->Actualizar($emp);
        }

        header('Location: index.php?c=empleado');
        exit;
    }

    public function Eliminar()
    {
        $id = $_REQUEST['id_empleado'] ?? 0;
        if ($id > 0) {
            $this->model->Eliminar($id);
        }
        header('Location: index.php?c=empleado');
        exit;
    }
}