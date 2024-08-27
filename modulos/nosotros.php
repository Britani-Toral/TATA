<?php
session_start();
include('../conexion.php');

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    echo '
    <script>
    alert("Por favor debes de iniciar sesión");
    window.location = "/TATA/modulos/index.php";
    </script>
    ';
    session_destroy();
    die();
}

// Verificar el rol del usuario (Opcional, solo si necesitas restricciones específicas)
$rolUsuario = $_SESSION['rol'];


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/TATA/imagenes/ICON.png" type="image/x-icon">
    <title>ESFU 5</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Enlace al archivo CSS -->
    <link href="/TATA/estilos/index.css" rel="stylesheet">
    <!-- ICONOS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>

<body>

    <header>
        <!-- Barra de navegación -->
        <nav class="barra navbar navbar-expand-lg navbar-light bg-light">
            <img src="/TATA/imagenes/Nav.png" class="img-fluid" alt="ICON">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:history.back()"><i class="fas fa-arrow-left"></i> Atrás</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/TATA/conexionbd/logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Contenido principal -->
    <div class="background-image">
        <div class="container col-md-10">
            <label for=""></label>
            <!-- Botón para descargar el archivo PDF -->
            <div class="d-flex justify-content-between">
                <img src="/TATA/imagenes/UnoTriptico.png" class="img-fluid" alt="ICON">
            </div>
            <div class="d-flex justify-content-between mt-4">
                <img src="/TATA/imagenes/TripticoDos.png" class="img-fluid" alt="ICON">
            </div>
            <div class="d-flex justify-content-end mt-4">
                <a href="/TATA/imagenes/ESFU 5.pdf" class="btn btn-primary">Descargar tríptico</a>
            </div>
        </div>
    </div>

    <!-- Scripts de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <footer>
        <div class="contenedor d-flex justify-content-center">
            <div class="lzc">
                <img src="/TATA/imagenes/Lazaro.png" class="img-fluid" alt="ICON">
            </div>
            <div>
                <h6><br>Avenida Paricutin sin número, Caltzontzin, Michoacán, C.P. 60220 Tel. (452) 452 3698 Correo electrónico: tatalazaro.uruapan@gmail.com</h6>
                <p>Todos los derechos reservados &copy; 2024 | by: <span class="author">Britani Toral</span></p>
            </div>
        </div>
    </footer>
</body>

</html>
