<?php
require_once 'model/producto.php';
require_once 'model/categorias_producto.php'; 

class ProductoController
{
    private $model;
    private $modelCategoria;

    public function __construct()
    {
        $this->model = new Producto();
        $this->modelCategoria = new CategoriaProducto();
    }

    // --- CORRECCIÓN CRÍTICA: El método ahora se llama "IndexPage" para coincidir con el enrutador. ---
    public function IndexPage()
    {
        // Pasamos la lista de categorías a la vista para usarla en los formularios
        $categorias = $this->modelCategoria->Listar(); 
        
        require_once 'view/header.php';
        require_once 'view/frmproducto.php';
        require_once 'view/footer.php';
    }

    public function InsEditar()
    {
        $prod = new Producto();
        $accion = $_REQUEST['ac'] ?? '';
        $nombre_imagen_final = null;

        $prod->id_categoria_producto = $_POST['id_categoria_producto'] ?? null;
        $prod->codigo_sku = $_POST['codigo_sku'] ?? null;
        $prod->nombre_producto = $_POST['nombre_producto'] ?? '';
        $prod->marca = $_POST['marca'] ?? null;
        $prod->nro_serie = $_POST['nro_serie'] ?? null;
        $prod->descripcion = $_POST['descripcion'] ?? null;
        $prod->precio_venta_unit = $_POST['precio_venta_unit'] ?? 0.00;
        $prod->costo_unit_adquisicion = $_POST['costo_unit_adquisicion'] ?? 0.00;
        $prod->stock_actual = $_POST['stock_actual'] ?? 0;
        $prod->unidad_medida = $_POST['unidad_medida'] ?? 'Unidad';
        
        if ($accion === 'editar') {
            $prod->id_producto = $_POST['id_producto'] ?? 0;
            $producto_existente = $this->model->Obtener($prod->id_producto);
            if ($producto_existente) {
                // Mantenemos la imagen existente si no se sube una nueva
                $nombre_imagen_final = $producto_existente->imagen;
            }
        }

        // Lógica para subir/actualizar imagen
        if (isset($_FILES['imagen_nueva']) && $_FILES['imagen_nueva']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/productos/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileName = uniqid() . '_' . basename($_FILES['imagen_nueva']['name']);
            $targetFilePath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['imagen_nueva']['tmp_name'], $targetFilePath)) {
                $nombre_imagen_final = $fileName;
            }
        }

        $prod->imagen = $nombre_imagen_final;

        if ($accion === 'nuevo') {
            $this->model->Registrar($prod);
        } elseif ($accion === 'editar') {
            $this->model->Actualizar($prod);
        }

        header('Location: index.php?c=producto');
        exit;
    }

    public function Eliminar()
    {
        $id = $_REQUEST['id_producto'] ?? 0;
        if ($id > 0) {
            $this->model->Eliminar($id);
        }
        header('Location: index.php?c=producto');
        exit;
    }
}