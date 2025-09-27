<?php
// CORRECCIÓN: Se usa el nombre de archivo en plural
require_once 'model/servicios.php'; 
require_once 'model/categorias_servicio.php';

// CORRECCIÓN: El nombre de la clase ahora está en plural para coincidir con el archivo
class ServiciosController
{
    private $model;
    private $modelCategoria;

    public function __construct()
    {
        // CORRECCIÓN: Se instancia la clase en plural
        $this->model = new Servicios();
        $this->modelCategoria = new CategoriaServicio();
    }

    public function IndexPage()
    {
        $categorias = $this->modelCategoria->Listar(); 
        
        require_once 'view/header.php';
        // CORRECCIÓN: Se llama a la vista en plural
        require_once 'view/frmservicios.php';
        require_once 'view/footer.php';
    }

    public function InsEditar()
    {
        // CORRECCIÓN: Se instancia la clase en plural
        $serv = new Servicios();
        $accion = $_REQUEST['ac'] ?? '';

        $serv->id_categoria_servicio = $_POST['id_categoria_servicio'] ?? null;
        $serv->codigo_servicio = $_POST['codigo_servicio'] ?? null;
        $serv->nombre_servicio = $_POST['nombre_servicio'] ?? '';
        $serv->descripcion = $_POST['descripcion'] ?? null;
        $serv->tipo_cobro = $_POST['tipo_cobro'] ?? null;
        $serv->precio_base = $_POST['precio_base'] ?? 0.00;
        $serv->duracion_estimada = $_POST['duracion_estimada'] ?? null;
        
        if ($accion === 'editar') {
            $serv->id_servicio = $_POST['id_servicio'] ?? 0;
            $this->model->Editar($serv);
        } elseif ($accion === 'nuevo') {
            $this->model->Insertar($serv);
        }

        // CORRECCIÓN: El parámetro 'c' apunta al controlador en plural
        header('Location: index.php?c=servicios&a=IndexPage');
        exit;
    }

    public function Eliminar()
    {
        $id = $_REQUEST['id_servicio'] ?? 0;
        if ($id > 0) {
            $this->model->Eliminar($id);
        }
        header('Location: index.php?c=servicios&a=IndexPage');
        exit;
    }
}