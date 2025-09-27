<?php
require_once 'model/categorias_producto.php';

// --- CORRECCIÓN CRÍTICA 1: El nombre de la clase ahora es "CategoriasProductoController" (plural) para coincidir con el enrutador. ---
class CategoriasProductoController
{
    private $model;

    public function __construct()
    {
        $this->model = new CategoriaProducto();
    }

    // --- CORRECCIÓN CRÍTICA 2: El método se llama "IndexPage" para coincidir con el enrutador cuando no hay acción "?a=". ---
    public function IndexPage()
    {
        require_once 'view/header.php';
        require_once 'view/frmcategorias_producto.php';
        require_once 'view/footer.php';
    }

    public function InsEditar()
    {
        $cat = new CategoriaProducto();
        
        $cat->id_categoria_producto = $_POST['id_categoria_producto'] ?? null;
        $cat->nombre_cat = $_POST['nombre_cat'] ?? '';
        $cat->descripcion = $_POST['descripcion'] ?? null;

        $accion = $_REQUEST['ac'] ?? '';

        if ($accion === 'nuevo') {
            $this->model->Registrar($cat);
        } elseif ($accion === 'editar') {
            $this->model->Actualizar($cat);
        }

        header('Location: index.php?c=categorias_producto');
        exit;
    }

    public function Eliminar()
    {
        $id = $_REQUEST['id_categoria_producto'] ?? 0;
        if ($id > 0) {
            $this->model->Eliminar($id);
        }
        header('Location: index.php?c=categorias_producto');
        exit;
    }
}