<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Tienda TECH_F4</title>

	<!-- Google font -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

	<!-- RUTAS CORREGIDAS PARA APUNTAR A LA CARPETA /public -->
	<link type="text/css" rel="stylesheet" href="public/css/bootstrap.min.css" />
	<link type="text/css" rel="stylesheet" href="public/css/slick.css" />
	<link type="text/css" rel="stylesheet" href="public/css/slick-theme.css" />
	<link type="text/css" rel="stylesheet" href="public/css/nouislider.min.css" />
	<link rel="stylesheet" href="public/css/font-awesome.min.css">
	<link type="text/css" rel="stylesheet" href="public/css/style.css" />

</head>

<body>
	<!-- HEADER -->
	<header>
		<!-- TOP HEADER -->
		<div id="top-header">
			<div class="container">
				<ul class="header-links pull-left">
					<li><a href="#"><i class="fa fa-phone"></i> +591-72546492</a></li>
					<li><a href="#"><i class="fa fa-envelope-o"></i> smart_tech@gmail.com</a></li>
					<li><a href="#"><i class="fa fa-map-marker"></i> Bolivia</a></li>
				</ul>
				<ul class="header-links pull-right">
					<li><a href="#"><i class="fa fa-dollar"></i> Bs.</a></li>
					<!-- Enlace al panel de administración -->
					<li><a href="login.php"><i class="fa fa-user-o"></i> Administración</a></li>
				</ul>
			</div>
		</div>
		<!-- /TOP HEADER -->

		<!-- MAIN HEADER -->
		<div id="header">
			<div class="container">
				<div class="row">
					<!-- LOGO -->
					<div class="col-md-3">
						<div class="header-logo">
							<a href="index.php" class="logo">
								<img src="public/img/logo.png" alt="Logo TECH_FERIA">
							</a>
						</div>
					</div>
					<!-- /LOGO -->

					<!-- SEARCH BAR -->
					<div class="col-md-6">
						<div class="header-search">
							<form>
								<select class="input-select">
									<option value="0">Categorías</option>
									<option value="1">Cámaras</option>
									<option value="1">Alarmas</option>
								</select>
								<input class="input" placeholder="Buscar aquí">
								<button class="search-btn">Buscar</button>
							</form>
						</div>
					</div>
					<!-- /SEARCH BAR -->

					<!-- ACCOUNT -->
					<div class="col-md-3 clearfix">
						<div class="header-ctn">
							<!-- Wishlist -->
							<div>
								<a href="#">
									<i class="fa fa-heart-o"></i>
									<span>Favoritos</span>
									<div class="qty">0</div>
								</a>
							</div>
							<!-- /Wishlist -->

							<!-- ====================================================== -->
							<!--            CARRITO DE COMPRAS (VERSIÓN ÚNICA Y CORRECTA) -->
							<!-- ====================================================== -->
							<div class="dropdown">
								<a href="index.php?c=carrito" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
									<i class="fa fa-shopping-cart"></i>
									<span>Carrito</span>
									<?php
									$items_en_carrito = 0;
									if (isset($_SESSION['carrito'])) {
										$items_en_carrito = count($_SESSION['carrito']);
									}
									?>
									<div class="qty"><?= $items_en_carrito ?></div>
								</a>
								<div class="cart-dropdown">
									<div class="cart-summary">
										<small><?= $items_en_carrito ?> Item(s) en el carrito</small>
										<!-- Aquí podríamos calcular el subtotal en el futuro -->
									</div>
									<div class="cart-btns">
										<a href="index.php?c=carrito">Ver Carrito</a>
										<a href="#">Comprar <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>
							</div>
							<!-- /Cart -->

							<!-- Menu Toogle -->
							<div class="menu-toggle">
								<a href="#">
									<i class="fa fa-bars"></i>
									<span>Menu</span>
								</a>
							</div>
							<!-- /Menu Toogle -->
						</div>
					</div>
					<!-- /ACCOUNT -->
				</div>
			</div>
		</div>
		<!-- /MAIN HEADER -->
	</header>
	<!-- /HEADER -->

	<!-- NAVIGATION -->
	<nav id="navigation">
		<div class="container">
			<div id="responsive-nav">
				<!-- NAV -->
				<ul class="main-nav nav navbar-nav">
					<li class="active"><a href="index.php">Inicio</a></li>
					<li><a href="#">Ofertas</a></li>
					<li><a href="#">Cámaras</a></li>
					<li><a href="#">Alarmas</a></li>
					<li><a href="#">Biométricos</a></li>
					<li><a href="#">Redes</a></li>
					<li><a href="#">Servicios</a></li>
				</ul>
				<!-- /NAV -->
			</div>
		</div>
	</nav>
	<!-- /NAVIGATION -->