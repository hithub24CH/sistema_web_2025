<?php
session_start();
$msg = '';
try {
    $conex = new PDO("mysql:host=localhost;dbname=ventasmvc","root","root");
    $conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
    if (isset($_POST["login"])) {
        if (empty($_POST["email"]) || empty($_POST["password"])) {
            $msg = '<label>Todos los campos son requeridos</label>';
        } else {
            $sql = "SELECT * FROM usuario WHERE email = :email";
            $stmt = $conex->prepare($sql);
            $stmt->execute(array(':email' => $_POST["email"]));
            $sqlresult = $stmt->fetch(PDO::FETCH_ASSOC);            
            if ($sqlresult) {
                $_SESSION["email"] = $_POST["email"];
                header("location:index.php");
            } else {
                $msg = '<label>Datos incorrectos del usuario</label>';
            }
        }
    }
} catch (PDOException $error) {
    $msg = $error->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Ventas MVC</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon
		============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">
    <!-- Google Fonts
		============================================ -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i,800" rel="stylesheet">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <!-- adminpro icon CSS
		============================================ -->
    <link rel="stylesheet" href="assets/css/adminpro-custon-icon.css">
    <!-- meanmenu icon CSS
		============================================ -->
    <link rel="stylesheet" href="assets/css/animate.css">
    <!-- data-table CSS
		============================================ -->
    <link rel="stylesheet" href="assets/css/normalize.css">
    <!-- charts C3 CSS  
		============================================ -->
    <link rel="stylesheet" href="assets/css/buttons.css">
      <!-- buttons CSS
		============================================ -->
    <link rel="stylesheet" href="assets/css/c3.min.css">
    <!-- forms CSS
		============================================ -->
    <link rel="stylesheet" href="assets/css/form/all-type-forms.css">
    <link rel="stylesheet" href="assets/css/form.css">
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="view/style.css">
    <!-- responsive CSS
		============================================ -->
    <link rel="stylesheet" href="assets/css/responsive.css">
    <!-- modernizr JS
		============================================ -->
    <script src="assets/js/vendor/modernizr-2.8.3.min.js"></script>
</head>
<body class="materialdesign">
  <div class="wrapper-pro">
      <div class="content-inner-login">
            <!-- login Start-->
            <div class="login-form-area mg-t-30 mg-b-40">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-4"></div>
                        <form id="vlogin" class="adminpro-form" action="" method="POST" novalidate>
                            <div class="col-lg-4">
                                <div class="login-bg">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="logo">
                                                <a href="#"><img src="assets/img/logo/logo.png" alt="" />
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="login-title">
                                                <h1><b>Inicio de Sesion</b></h1>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="login-input-head">
                                                <p>Correo</p>
                                            </div>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="login-input-area">
                                                <input type="email" name="email" />
                                                <i class="fa fa-envelope login-user" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="login-input-head">
                                                <p>Contraseña</p>
                                            </div>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="login-input-area">
                                                <input type="password" name="password" />
                                                <i class="fa fa-lock login-user"></i>
                                            </div>                                           
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">

                                        </div>
                                        <div class="col-lg-8">
                                            <div class="login-button-pro">
                                                <button class="login-button login-button-lg" id="login" name="login" type="submit">Login</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="col-lg-4"></div>
                    </div>
                </div>
            </div>
            <!-- login End-->              
      </div>
  </div>
  <!-- jquery
		============================================ -->
    <script src="assets/js/vendor/jquery-1.11.3.min.js"></script>
    <!-- bootstrap JS
		============================================ -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- meanmenu JS
		============================================ -->
    <script src="assets/js/jquery.meanmenu.js"></script>
    <!-- mCustomScrollbar JS
		============================================ -->
    <script src="assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
    <!-- sticky JS
		============================================ -->
    <script src="assets/js/jquery.sticky.js"></script>
    <!-- scrollUp JS
		============================================ -->
    <script src="assets/js/jquery.scrollUp.min.js"></script>
    <!-- counterup JS
		============================================ -->
    <script src="assets/js/counterup/jquery.counterup.min.js"></script>
    <script src="assets/js/counterup/waypoints.min.js"></script>
    <script src="assets/js/counterup/counterup-active.js"></script>
    <!-- peity JS
		============================================ -->
    <script src="assets/js/peity/jquery.peity.min.js"></script>
    <script src="assets/js/peity/peity-active.js"></script>
    <!-- sparkline JS
		============================================ -->
    <script src="assets/js/sparkline/jquery.sparkline.min.js"></script>
    <script src="assets/js/sparkline/sparkline-active.js"></script>
    <!-- flot JS
		============================================ -->
    <script src="assets/js/flot/jquery.flot.js"></script>
    <script src="assets/js/flot/jquery.flot.tooltip.min.js"></script>
    <script src="assets/js/flot/jquery.flot.spline.js"></script>
    <script src="assets/js/flot/jquery.flot.resize.js"></script>
    <script src="assets/js/flot/jquery.flot.pie.js"></script>
    <script src="assets/js/flot/Chart.min.js"></script>
    <script src="assets/js/flot/flot-active.js"></script>
    <!-- map JS
		============================================ -->
    <script src="assets/js/map/raphael.min.js"></script>
    <script src="assets/js/map/jquery.mapael.js"></script>
    <script src="assets/js/map/france_departments.js"></script>
    <script src="assets/js/map/world_countries.js"></script>
    <script src="assets/js/map/usa_states.js"></script>
    <script src="assets/js/map/map-active.js"></script>
    <!-- data table JS
		============================================ -->
    <script src="assets/js/data-table/bootstrap-table.js"></script>
    <script src="assets/js/data-table/tableExport.js"></script>
    <script src="assets/js/data-table/data-table-active.js"></script>
    <script src="assets/js/data-table/bootstrap-table-editable.js"></script>
    <script src="assets/js/data-table/bootstrap-editable.js"></script>
    <script src="assets/js/data-table/bootstrap-table-resizable.js"></script>
    <script src="assets/js/data-table/colResizable-1.5.source.js"></script>
    <script src="assets/js/data-table/bootstrap-table-export.js"></script>
    <!-- main JS
		============================================ -->
    <script src="assets/js/main.js"></script>
</body>
</html>
