<?php 
// Incluimos el encabezado de nuestra tienda
require_once 'view/layouts/header_tienda.php'; 
require_once 'view/layouts/footer_tienda.php';
?>

<!-- BREADCRUMB -->
<div id="breadcrumb" class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="breadcrumb-header">Finalizar Compra</h3>
                <ul class="breadcrumb-tree">
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="index.php?c=carrito">Carrito</a></li>
                    <li class="active">Checkout</li>
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

            <div class="col-md-7">
                <!-- Billing Details -->
                <div class="billing-details">
                    <div class="section-title">
                        <h3 class="title">Tus Datos</h3>
                    </div>
                    <p>Por ahora, realizaremos la venta como cliente genérico. ¡Más adelante podrás iniciar sesión!</p>
                    <!-- Aquí iría un formulario para clientes registrados o nuevos -->
                    <div class="form-group">
                        <input class="input" type="text" name="first-name" placeholder="Nombre (ej: Cliente Varios)" disabled>
                    </div>
                    <div class="form-group">
                        <input class="input" type="email" name="email" placeholder="Correo (opcional)" disabled>
                    </div>
                </div>
                <!-- /Billing Details -->
            </div>

            <div class="col-md-5 order-details">
                <div class="section-title text-center">
                    <h3 class="title">Tu Orden</h3>
                </div>
                <div class="order-summary">
                    <div class="order-col">
                        <div><strong>PRODUCTO</strong></div>
                        <div><strong>SUBTOTAL</strong></div>
                    </div>
                    <div class="order-products">
                        <?php 
                        $total = 0;
                        if(isset($_SESSION['carrito'])):
                            foreach($_SESSION['carrito'] as $elemento):
                                $producto = $elemento['producto'];
                                $subtotal = $elemento['precio'] * $elemento['unidades'];
                                $total += $subtotal;
                        ?>
                        <div class="order-col">
                            <div><?= $elemento['unidades'] ?>x <?= $producto->nombre_producto ?></div>
                            <div>$<?= number_format($subtotal, 2) ?></div>
                        </div>
                        <?php 
                            endforeach;
                        endif; 
                        ?>
                    </div>
                    <div class="order-col">
                        <div>Envío</div>
                        <div><strong>GRATIS</strong></div>
                    </div>
                    <div class="order-col">
                        <div><strong>TOTAL</strong></div>
                        <div><strong class="order-total">$<?= number_format($total, 2) ?></strong></div>
                    </div>
                </div>
                
                <form action="index.php?c=ventas&a=RegistrarDesdeTienda" method="POST">
                    <!-- Podemos añadir campos ocultos si es necesario, como el total -->
                    <input type="hidden" name="total" value="<?= $total ?>">
                    
                    <button type="submit" class="primary-btn btn-block order-submit">Confirmar y Realizar Pedido</button>
                </form>

            </div>

        </div>
    </div>
</div>
<!-- /SECTION -->

<?php 
// Incluimos el pie de página
require_once 'view/layouts/footer_tienda.php'; 
?>