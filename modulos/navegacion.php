<?php
session_start();
include('../conexion.php');

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

// Obtener el rol del usuario desde la sesión
$rolUsuario = $_SESSION['rol'];

// Debug: Verificar el rol del usuario en la vista
//echo "Rol actual: " . $rolUsuario;
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
    <link href="/TATA/estilos/style.css" rel="stylesheet">
    <!-- ICONOS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>

<body>

    <header>
        <!-- Barra de navegación -->
        <nav class="barra navbar navbar-expand-lg navbar-light bg-light">
            <li class="nav-item" style="list-style: none;">
                <a class="nav-link" href="#">
                    <i class="fas fa-user"></i> <?php echo $_SESSION['usuario']; ?>
                </a>
            </li>
            <img src="/TATA/imagenes/Na.png" class="img-fluid" alt="ICON">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <?php if ($rolUsuario == 'Admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="docentes.php"><i class="fas fa-chalkboard-teacher"></i> Docentes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="materias.php"><i class="fas fa-book"></i> Materias</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="alumnos.php"><i class="fas fa-user-graduate"></i> Alumnos</a>
                        </li>
                    <?php endif; ?>

                    <?php if ($rolUsuario == 'Docente'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="asistencias.php"><i class="fas fa-tasks"></i> Asistencias</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="planeaciones.php"><i class="fas fa-file-alt"></i> Planeaciones</a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link" href="nosotros.php"><i class="fas fa-info-circle"></i> Nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/TATA/conexionbd/logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Contenido principal -->
    <div class="container d-flex justify-content-center col-lg-auto">
        <div class="align-self-center">
            <div class="col-md-10">
                <h1 class="text-center">Bienvenido </h1>
                <p class="text-center">Selecciona un módulo del menú para comenzar.</p>
            </div>
        </div>
        <div class="escudo col-md-4">
            <img src="/TATA/imagenes/ICON.png" class="img-fluid" alt="ICON">
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
