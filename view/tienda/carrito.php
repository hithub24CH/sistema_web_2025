<?php 
// Incluimos el encabezado de nuestra tienda
require_once 'view/layouts/header_tienda.php'; 
?>

<!-- BREADCRUMB -->
<div id="breadcrumb" class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="breadcrumb-header">Carrito de Compras</h3>
                <ul class="breadcrumb-tree">
                    <li><a href="index.php">Inicio</a></li>
                    <li class="active">Carrito</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- /BREADCRUMB -->

<!-- SECTION -->
<div class="section">
    <div class="container">
        <div class="row">

            <?php if(isset($_SESSION['carrito']) && count($_SESSION['carrito']) >= 1): ?>
                
                <h2>Resumen de tu compra</h2>
                
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Unidades</th>
                            <th>Subtotal</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        foreach($_SESSION['carrito'] as $indice => $elemento): 
                            $producto = $elemento['producto'];
                            $subtotal = $elemento['precio'] * $elemento['unidades'];
                            $total += $subtotal;
                        ?>
                            <tr>
                                <td>
                                    <img src="uploads/productos/<?= $producto->imagen ?>" width="80px" alt="<?= $producto->nombre_producto ?>">
                                </td>
                                <td><?= $producto->nombre_producto ?></td>
                                <td>$<?= number_format($elemento['precio'], 2) ?></td>
                                <td><?= $elemento['unidades'] ?></td>
                                <td>$<?= number_format($subtotal, 2) ?></td>
                                <td>
                                    <a href="index.php?c=carrito&a=remove&id=<?= $producto->id_producto ?>" class="btn btn-danger btn-sm">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div class="cart-summary pull-right">
                    <h3>Total del Carrito: <strong>$<?= number_format($total, 2) ?></strong></h3>
                    
                    <!-- ===================================================================== -->
                    <!--            AQUÍ ESTÁ LA LÍNEA CORREGIDA DEL PASO 3                   -->
                    <!-- ===================================================================== -->
                    <a href="index.php?c=ventas&a=Checkout" class="btn btn-success">Proceder al Pago</a>
                    
                    <a href="index.php?c=carrito&a=delete_all" class="btn btn-warning">Vaciar Carrito</a>
                </div>

            <?php else: ?>
                
                <div class="alert alert-warning text-center">
                    <h3>Tu carrito de compras está vacío.</h3>
                    <a href="index.php" class="primary-btn">Volver a la tienda</a>
                </div>

            <?php endif; ?>

        </div>
    </div>
</div>
<!-- /SECTION -->

<?php 
// Incluimos el pie de página
require_once 'view/layouts/footer_tienda.php'; 
?>