<?php
require_once 'model/proveedor.php';
// Requerimos el modelo de categorías para poder listar las opciones en el formulario
require_once 'model/categorias_producto.php'; 

class ProveedorController
{
    private $model;
    private $modelCategoria;

    public function __construct()
    {
        $this->model = new Proveedor();
        $this->modelCategoria = new CategoriaProducto();
    }
        public function Index()
{
    // Llama a la función que ya tenías y que muestra la página principal.
    // Reemplaza 'IndexPage' por el nombre real de tu función si es diferente.
    $this->IndexPage(); 
}
    // Este es el método que tu index.php llama por defecto para este controlador
    public function IndexPage()
    {
        // Obtenemos la lista de categorías para pasarla a la vista
        $categorias = $this->modelCategoria->Listar(); 
        
        require_once 'view/header.php';
        require_once 'view/frmproveedor.php';
        require_once 'view/footer.php';
    }

    public function InsEditar()
    {
        $prov = new Proveedor();
        
        $prov->id_proveedor = $_POST['id_proveedor'] ?? null;
        $prov->nombre_proveedor = $_POST['nombre_proveedor'] ?? '';
        $prov->identificador_fiscal = $_POST['identificador_fiscal'] ?? null;
        $prov->contacto_principal = $_POST['contacto_principal'] ?? null;
        $prov->telefono = $_POST['telefono'] ?? null;
        $prov->correo = $_POST['correo'] ?? null;
        $prov->direccion = $_POST['direccion'] ?? null;
        // Asegurarnos de que si no se selecciona categoría, se envíe NULL a la BD
        $prov->id_categoria_producto = !empty($_POST['id_categoria_producto']) ? $_POST['id_categoria_producto'] : null;
        $prov->notas = $_POST['notas'] ?? null;

        $accion = $_REQUEST['ac'] ?? '';

        if ($accion === 'nuevo') {
            $this->model->Registrar($prov);
        } elseif ($accion === 'editar') {
            $this->model->Actualizar($prov);
        }

        header('Location: index.php?c=proveedor');
        exit;
    }

    public function Eliminar()
    {
        $id = $_REQUEST['id_proveedor'] ?? 0;
        if ($id > 0) {
            $this->model->Eliminar($id);
        }
        header('Location: index.php?c=proveedor');
        exit;
    }
}