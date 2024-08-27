<?php
session_start();
include("../conexionbd/bdalumnos.php");

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


// Inicializar lista de alumnos como vacío
$listaAlumnos = [];
// Obtener la lista de alumnos desde la sesión o inicializarla como un array vacío
if (isset($_SESSION['listaAlumnos'])) {
    $listaAlumnos = $_SESSION['listaAlumnos'];
    unset($_SESSION['listaAlumnos']);
} else {
    $listaAlumnos = [];
}
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
    <link href="/TATA/estilos/alumnos.css" rel="stylesheet">
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
            <h1 class="text-center">Registro de Alumnos.</h1>
            <!-- Mensaje de confirmación -->
            <?php if ($mensaje) : ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <!-- Formulario de registro -->
            <form action="" method="post">
                <!-- Campos del formulario -->
                <div class="d-flex justify-content-between">
                    <div class="form-group">
                        <label for="">ID Alumno:</label>
                        <input type="text" class="form-control" name="txtIdAlumno" value="<?php echo $txtIdAlumno; ?>" placeholder="" id="txt1" readonly>
                    </div>
                    <div class="form-group">
                        <label for="">Apellido Paterno:</label>
                        <input type="text" class="form-control" name="txtApaternoA" value="<?php echo $txtApaternoA; ?>" placeholder="" id="txt2" pattern="[a-zA-Z\s]+" required onkeypress="return event.charCode >= 65 && event.charCode <= 90 || event.charCode >= 97 && event.charCode <= 122 || event.charCode == 32">
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="form-group">
                        <label for="">Apellido Materno:</label>
                        <input type="text" class="form-control" name="txtAmaternoA" value="<?php echo $txtAmaternoA; ?>" placeholder="" id="txt3" required onkeypress="return event.charCode >= 65 && event.charCode <= 90 || event.charCode >= 97 && event.charCode <= 122 || event.charCode == 32">
                    </div>
                    <div class="form-group">
                        <label for="">Nombre del Alumno:</label>
                        <input type="text" class="form-control" name="txtNombreA" value="<?php echo $txtNombreA; ?>" placeholder="" id="txt4" required onkeypress="return event.charCode >= 65 && event.charCode <= 90 || event.charCode >= 97 && event.charCode <= 122 || event.charCode == 32">
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="form-group">
                        <label for="">CURP:</label>
                        <input type="text" class="form-control" name="txtCurpA" value="<?php echo $txtCurpA; ?>" placeholder="" id="txt5" required>
                    </div>
                    <div class="form-group">
                        <label for="">Asignaturas:</label>
                        <select class="form-control" name="txtIdMateria[]" id="txt6" multiple required>
                            <option value="">Seleccionar</option>
                            <?php
                            // Obtener las materias
                            $queryMaterias = "SELECT * FROM materias";
                            $stmtMaterias = $pdo->prepare($queryMaterias);
                            $stmtMaterias->execute();
                            $Materias = $stmtMaterias->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($Materias as $Materia) {
                                echo '<option value="' . $Materia['IdMateria'] . '">' . $Materia['NombreMateria'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="form-group">
                        <label for="">Grado:</label>
                        <select class="form-control" name="txtIdGrado" id="txt7" required>
                            <option value="">Seleccionar</option>
                            <?php
                            // Obtener los grados
                            $queryGrados = "SELECT * FROM grados";
                            $stmtGrados = $pdo->prepare($queryGrados);
                            $stmtGrados->execute();
                            $Grados = $stmtGrados->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($Grados as $Grado) {
                                echo '<option value="' . $Grado['IdGrado'] . '">' . $Grado['NombreGrado'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Grupo:</label>
                        <select class="form-control" name="txtIdGrupo" id="txt8" required>
                            <option value="">Seleccionar</option>
                            <?php
                            // Obtener los grupos
                            $queryGrupos = "SELECT * FROM grupos";
                            $stmtGrupos = $pdo->prepare($queryGrupos);
                            $stmtGrupos->execute();
                            $Grupos = $stmtGrupos->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($Grupos as $Grupo) {
                                echo '<option value="' . $Grupo['IdGrupo'] . '">' . $Grupo['NombreGrupo'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <!-- Botones -->
                    <button type="submit" name="accion" value="CANCELAR" class="btn btn-primary btn-block" <?php echo $accionCancelar; ?>>CANCELAR</button>
                    <button type="submit" name="accion" value="MODIFICAR" class="btn btn-primary btn-block" <?php echo $accionModificar; ?>>MODIFICAR</button>
                    <button type="submit" name="accion" value="ELIMINAR" class="btn btn-primary btn-block" <?php echo $accionEliminar; ?>>ELIMINAR</button>
                    <button type="submit" name="accion" value="GUARDAR" class="btn btn-primary btn-block" <?php echo $accionGuardar; ?>>GUARDAR</button>
                </div>
            </form>
            <div class="d-flex justify-content-between mt-4">
                <form action="" method="post" class="form-inline mb-3">
                    <input type="text" class="form-control mr-2" name="searchTerm" placeholder="Buscar alumno">
                    <button type="submit" name="accion" value="BUSCAR" class="btn btn-primary">Buscar</button>
                </form>
                <form action="" method="post" class="mb-3">
                    <button type="submit" name="accion" value="MOSTRAR_TODO" class="btn btn-primary btn-block">Mostrar Todos</button>
                </form>
            </div>
        </div>
        <div class="container col-md-6">
            <h1 class="text-center">Alumnos Registrados</h1>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Grado y Grupo</th>
                        <th>Asignaturas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($_SESSION['listaAlumnos']) && !empty($_SESSION['listaAlumnos'])) : ?>
                        <?php foreach ($_SESSION['listaAlumnos'] as $alumno) : ?>
                            <tr>
                                <td><?php echo $alumno['ApaternoA'] . ' ' . $alumno['AmaternoA'] . ' ' . $alumno['NombreA']; ?></td>
                                <td><?php echo $alumno['NombreGrado'] . ' ' . $alumno['NombreGrupo']; ?></td>
                                <td><?php echo $alumno['Materias']; ?></td>
                                <td>
                                    <form action="" method="post">
                                        <input type="hidden" name="txtIdAlumno" value="<?php echo $alumno['IdAlumno']; ?>">
                                        <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php elseif (empty($listaAlumnos)) : ?>
                        <tr>
                            <td colspan="5" class="text-center">No se encontraron registros</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($listaAlumnos as $alumno) : ?>
                            <tr>
                                <td><?php echo $alumno['ApaternoA'] . ' ' . $alumno['AmaternoA'] . ' ' . $alumno['NombreA']; ?></td>
                                <td><?php echo $alumno['NombreGrado'] . ' ' . $alumno['NombreGrupo']; ?></td>
                                <td><?php echo $alumno['Materias']; ?></td>
                                <td>
                                    <form action="" method="post">
                                        <input type="hidden" name="txtIdAlumno" value="<?php echo $alumno['IdAlumno']; ?>">
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
    <!-- Bootstrap y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="/TATA/js/alumnos.js"></script>
</body>

</html>