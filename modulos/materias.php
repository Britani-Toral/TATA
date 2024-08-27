<?php
session_start();
include("../conexionbd/bdmaterias.php");

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

// Verificar si el usuario tiene el rol de Administrador
if ($_SESSION['rol'] !== 'Admin') {
    echo '
    <script>
    alert("No tienes permiso para acceder a esta sección");
    window.location = "/TATA/modulos/navegacion.php";
    </script>
    ';
    die();
}

$mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : "";
unset($_SESSION['mensaje']);

// Obtener la lista de materias desde la sesión o inicializarla como un array vacío
if (isset($_SESSION['listaMaterias'])) {
    $listaMaterias = $_SESSION['listaMaterias'];
    unset($_SESSION['listaMaterias']);
} else {
    $listaMaterias = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/TATA/imagenes/ICON.png" type="image/x-icon">
    <title>ESFU 5</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="/TATA/estilos/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <header>
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
                    <li class="nav-item">
                        <a class="nav-link" href="docentes.php"><i class="fas fa-chalkboard-teacher"></i> Docentes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="materias.php"><i class="fas fa-book"></i> Materias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="alumnos.php"><i class="fas fa-user-graduate"></i> Alumnos</a>
                    </li>
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

    <div class="background-image">
        <div class="container col-md-6">
            <h1 class="text-center">Registro de Materias.</h1>
            <!-- Mensaje de confirmación -->
            <?php if ($mensaje) : ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <form action="" method="post">
                <div class="d-flex justify-content-between mt-4">
                    <div class="form-group">
                        <label for="">ID:</label>
                        <input type="text" class="form-control" name="txtIdMateria" value="<?php echo $txtIdMateria; ?>" id="txt1" readonly>
                    </div>
                    <div class="form-group">
                        <label for="">Nombre de la materia:</label>
                        <input type="text" class="form-control" name="txtNombreMateria" value="<?php echo $txtNombreMateria; ?>" id="txt2" required>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" name="accion" <?php echo $accionCancelar; ?> value="CANCELAR" class="btn btn-primary btn-block">CANCELAR</button>
                    <button type="submit" name="accion" <?php echo $accionModificar; ?> value="MODIFICAR" class="btn btn-primary btn-block">MODIFICAR</button>
                    <button type="submit" name="accion" <?php echo $accionEliminar; ?> value="ELIMINAR" class="btn btn-primary btn-block">ELIMINAR</button>
                    <button type="submit" name="accion" <?php echo $accionGuardar; ?> value="GUARDAR" class="btn btn-primary btn-block">GUARDAR</button>
                </div>
            </form>
            <div class="d-flex justify-content-between mt-4">
                <form action="" method="post" class="form-inline mb-3">
                    <input type="text" class="form-control mr-2" name="searchTerm" placeholder="Buscar materia">
                    <button type="submit" name="accion" value="BUSCAR" class="btn btn-primary">Buscar</button>
                </form>
                <form action="" method="post" class="mb-3">
                    <button type="submit" name="accion" value="MOSTRAR_TODO" class="btn btn-primary btn-block">Mostrar Todos</button>
                </form>
            </div>

        </div>
        <div class="container col-md-6">
            <h1 class="text-center">Materias Registradas</h1>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nombre de la materia</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($_SESSION['listaMaterias']) && !empty($_SESSION['listaMaterias'])) : ?>
                        <?php foreach ($_SESSION['listaMaterias'] as $materia) : ?>
                            <tr>
                                <td><?php echo $materia['NombreMateria']; ?></td>
                                <td>
                                    <form method="post">
                                        <input type="hidden" name="txtIdMateria" value="<?php echo $materia['IdMateria']; ?>">
                                        <input type="hidden" name="txtNombreMateria" value="<?php echo $materia['NombreMateria']; ?>">
                                        <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php elseif (empty($listaMaterias)) : ?>
                        <tr>
                            <td colspan="3" class="text-center">No se encontraron registros</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($listaMaterias as $materia) : ?>
                            <tr>
                                <td><?php echo $materia['NombreMateria']; ?></td>
                                <td>
                                    <form method="post">
                                        <input type="hidden" name="txtIdMateria" value="<?php echo $materia['IdMateria']; ?>">
                                        <input type="hidden" name="txtNombreMateria" value="<?php echo $materia['NombreMateria']; ?>">
                                        <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer>
        <div class="contenedor d-flex justify-content-center">
            <div class="lzc">
                <img src="/TATA/imagenes/Lazaro.png" class="img-fluid" alt="ICON">
            </div>
            <div>
                <h6><br>Avenida Paricutin sin número, Caltzontzin, Michoacán, C.P. 60220 Tel. (452) 452 3698 Correo
                    electrónico: tatalazaro.uruapan@gmail.com</h6>
                <p>Todos los derechos reservados &copy; 2024 | by: <span class="author">Britani Toral</span></p>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>