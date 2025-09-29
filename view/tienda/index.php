<?php 
// 1. Incluimos el encabezado de nuestra tienda
require_once 'view/layouts/header_tienda.php'; 
?>

<!-- BANNERS DE CATEGORÍAS -->
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-xs-6">
                <div class="shop"><div class="shop-img"><img src="public/img/shop01.png" alt=""></div><div class="shop-body"><h3>Laptops<br>y PCs</h3><a href="#" class="cta-btn">Comprar <i class="fa fa-arrow-circle-right"></i></a></div></div>
            </div>
            <div class="col-md-4 col-xs-6">
                <div class="shop"><div class="shop-img"><img src="public/img/shop03.png" alt=""></div><div class="shop-body"><h3>Accesorios<br>y Redes</h3><a href="#" class="cta-btn">Comprar <i class="fa fa-arrow-circle-right"></i></a></div></div>
            </div>
            <div class="col-md-4 col-xs-6">
                <div class="shop"><div class="shop-img"><img src="public/img/shop02.png" alt=""></div><div class="shop-body"><h3>Cámaras<br>de Seguridad</h3><a href="#" class="cta-btn">Comprar <i class="fa fa-arrow-circle-right"></i></a></div></div>
            </div>
        </div>
    </div>
</div>
<!-- /BANNERS -->


<!-- SECCIÓN DE PRODUCTOS DINÁMICOS -->
<div class="section">
    <div class="container">
        <div class="row">

            <div class="col-md-12">
                <div class="section-title">
                    <h3 class="title">Nuestros Productos</h3>
                </div>
            </div>

            <div class="col-md-12">
                <div class="row">
                    <div class="products-tabs">
                        <div id="tab1" class="tab-pane active">
                            <div class="products-slick" data-nav="#slick-nav-1">

                                <?php 
                                // BUCLE ADAPTADO PARA EL ARRAY DE OBJETOS DE TU MODELO PDO
                                if (isset($productos) && !empty($productos)): 
                                    foreach($productos as $prod): 
                                ?>
                                    <!-- Tarjeta de Producto Individual -->
                                    <div class="product">
                                        <div class="product-img">
                                            <img src="uploads/productos/<?= htmlspecialchars($prod->imagen) ?>" alt="<?= htmlspecialchars($prod->nombre_producto) ?>" style="height: 180px; object-fit: cover;">
                                        </div>
                                        <div class="product-body">
                                            <p class="product-category"><?= htmlspecialchars($prod->nombre_categoria ?? 'General') ?></p>
                                            <h3 class="product-name"><a href="#"><?= htmlspecialchars($prod->nombre_producto) ?></a></h3>
                                            <h4 class="product-price">$<?= number_format($prod->precio_venta_unit, 2) ?></h4>
                                            <div class="product-rating">
                                                <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i>
                                            </div>
                                        </div>
                                        <div class="add-to-cart">
                                            <a href="index.php?c=carrito&a=add&id=<?= $prod->id_producto ?>" class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> Añadir al carrito</a>
                                        </div>
                                    </div>
                                    <!-- /Tarjeta de Producto -->
                                <?php 
                                    endforeach;
                                else:
                                ?>
                                    <p style="text-align: center;">No hay productos disponibles en este momento.</p>
                                <?php endif; ?>

                            </div>
                            <div id="slick-nav-1" class="products-slick-nav"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /SECCIÓN DE PRODUCTOS -->


<?php 
// 3. Incluimos el pie de página
require_once 'view/layouts/footer_tienda.php'; 
?>