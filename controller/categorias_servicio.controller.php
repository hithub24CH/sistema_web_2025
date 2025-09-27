<?php
require_once 'model/categorias_servicio.php';

// El nombre de la clase es compatible con el enrutador de index.php
class CategoriasServicioController
{
    private $model;

    public function __construct()
    {
        $this->model = new CategoriaServicio();
    }

    // El método por defecto es IndexPage()
    public function IndexPage()
    {
        require_once 'view/header.php';
        require_once 'view/frmcategorias_servicio.php';
        require_once 'view/footer.php';
    }

    public function InsEditar()
    {
        $cat = new CategoriaServicio();
        $cat->id_categoria_servicio = $_POST['id_categoria_servicio'] ?? null;
        $cat->nombre_categoria_servicio = $_POST['nombre_categoria_servicio'] ?? '';
        $cat->descripcion = $_POST['descripcion'] ?? null;

        $accion = $_REQUEST['ac'] ?? '';

        if ($accion === 'nuevo') {
            $this->model->Registrar($cat);
        } elseif ($accion === 'editar') {
            $this->model->Actualizar($cat);
        }

        header('Location: index.php?c=categorias_servicio');
        exit;
    }

    public function Eliminar()
    {
        $id = $_REQUEST['id_categoria_servicio'] ?? 0;
        if ($id > 0) {
            $this->model->Eliminar($id);
        }
        header('Location: index.php?c=categorias_servicio');
        exit;
    }
}