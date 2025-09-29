<?php
require_once 'model/metodos_pago.php';

class MetodosPagoController
{
    private $model;

    public function __construct()
    {
        $this->model = new MetodoPago();
    }

        public function Index()
{
    // Llama a la función que ya tenías y que muestra la página principal.
    // Reemplaza 'IndexPage' por el nombre real de tu función si es diferente.
    $this->IndexPage(); 
}
    public function IndexPage()
    {
        require_once 'view/header.php';
        require_once 'view/frmmetodos_pago.php';
        require_once 'view/footer.php';
    }

    public function InsEditar()
    {
        $met = new MetodoPago();
        $met->id_metodo_pago = $_POST['id_metodo_pago'] ?? null;
        $met->nombre_metodo = $_POST['nombre_metodo'] ?? '';
        $met->descripcion = $_POST['descripcion'] ?? null;

        $accion = $_REQUEST['ac'] ?? '';

        if ($accion === 'nuevo') {
            $this->model->Registrar($met);
        } elseif ($accion === 'editar') {
            $this->model->Actualizar($met);
        }

        header('Location: index.php?c=metodos_pago');
        exit;
    }

    public function Eliminar()
    {
        $id = $_REQUEST['id_metodo_pago'] ?? 0;
        if ($id > 0) {
            $this->model->Eliminar($id);
        }
        header('Location: index.php?c=metodos_pago');
        exit;
    }
}