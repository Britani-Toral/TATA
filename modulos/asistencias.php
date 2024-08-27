<?php
session_start();
include("../conexion.php");

// Establece la zona horaria a Ciudad de México o Guadalajara
date_default_timezone_set('America/Mexico_City');

// Verificación de sesión de usuario
if (!isset($_SESSION['usuario'])) {
    echo '
    <script>
    alert("Por favor, debes iniciar sesión");
    window.location = "/TATA/modulos/index.php";
    </script>
    ';
    session_destroy();
    die();
}

// Verificar si el usuario tiene el rol de Docente
if ($_SESSION['rol'] !== 'Docente') {
    echo '
    <script>
    alert("No tienes permiso para acceder a esta sección");
    window.location = "/TATA/modulos/navegacion.php";
    </script>
    ';
    die();
}

// Inicializar variables
$mensaje = '';
$listaAlumnos = [];
$datosSeleccion = [
    'txtIdDocente' => $_SESSION['docente_id'] ?? '',
    'txtIdMateria' => '',
    'txtIdGrado' => '',
    'txtIdGrupo' => '',
    'txtCicloEscolar' => '',
    'FechaA' => date('Y-m-d')  // Fecha actual
];

// Procesar formulario de selección
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'CARGAR_ALUMNOS') {
    // Validar y sanitizar entradas
    $datosSeleccion['txtIdMateria'] = htmlspecialchars($_POST['txtIdMateria']);
    $datosSeleccion['txtIdGrado'] = htmlspecialchars($_POST['txtIdGrado']);
    $datosSeleccion['txtIdGrupo'] = htmlspecialchars($_POST['txtIdGrupo']);
    $datosSeleccion['txtCicloEscolar'] = htmlspecialchars($_POST['txtCicloEscolar']);

    // Consulta para obtener la lista de alumnos
    $queryAlumnos = "SELECT IdAlumno, NombreA, ApaternoA, AmaternoA FROM alumnos WHERE IdGrado = :IdGrado AND IdGrupo = :IdGrupo";
    $stmtAlumnos = $pdo->prepare($queryAlumnos);
    $stmtAlumnos->bindParam(':IdGrado', $datosSeleccion['txtIdGrado']);
    $stmtAlumnos->bindParam(':IdGrupo', $datosSeleccion['txtIdGrupo']);
    $stmtAlumnos->execute();
    $listaAlumnos = $stmtAlumnos->fetchAll(PDO::FETCH_ASSOC);

    if (empty($listaAlumnos)) {
        $mensaje = "No se encontraron alumnos para la selección realizada.";
    }
}

date_default_timezone_set('America/Mexico_City'); // Establece la zona horaria

// Obtener la fecha de hoy con la zona horaria correcta
$FechaA = date('Y-m-d');

// Procesar formulario de registro de asistencias
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'GUARDAR_ASISTENCIAS') {
    // Validar y sanitizar entradas
    $datosSeleccion['txtIdMateria'] = htmlspecialchars($_POST['txtIdMateria']);
    $datosSeleccion['txtIdGrado'] = htmlspecialchars($_POST['txtIdGrado']);
    $datosSeleccion['txtIdGrupo'] = htmlspecialchars($_POST['txtIdGrupo']);
    $datosSeleccion['txtCicloEscolar'] = htmlspecialchars($_POST['txtCicloEscolar']);
    $datosSeleccion['FechaA'] = htmlspecialchars($_POST['FechaA']);
    $asistencias = $_POST['asistencia'] ?? [];

    if (!empty($asistencias)) {
        try {
            $pdo->beginTransaction();
            foreach ($asistencias as $idAlumno => $estadoAsistencia) {
                // Preparar y ejecutar inserción o actualización
                $stmt = $pdo->prepare("INSERT INTO asistencias (IdDocente, IdAlumno, IdMateria, IdGrado, IdGrupo, FechaA, Asistencia) 
                                       VALUES (:IdDocente, :IdAlumno, :IdMateria, :IdGrado, :IdGrupo, :FechaA, :Asistencia)
                                       ON DUPLICATE KEY UPDATE Asistencia = :Asistencia");
                $stmt->bindParam(':IdDocente', $datosSeleccion['txtIdDocente']);
                $stmt->bindParam(':IdAlumno', $idAlumno);
                $stmt->bindParam(':IdMateria', $datosSeleccion['txtIdMateria']);
                $stmt->bindParam(':IdGrado', $datosSeleccion['txtIdGrado']);
                $stmt->bindParam(':IdGrupo', $datosSeleccion['txtIdGrupo']);
                $stmt->bindParam(':FechaA', $datosSeleccion['FechaA']);
                $stmt->bindParam(':Asistencia', $estadoAsistencia);
                $stmt->execute();
            }
            $pdo->commit();
            $mensaje = "Asistencias guardadas correctamente.";
            // Limpiar lista de alumnos después de guardar
            $listaAlumnos = [];
        } catch (Exception $e) {
            $pdo->rollBack();
            $mensaje = "Error al guardar asistencias: " . $e->getMessage();
        }
    } else {
        $mensaje = "No se recibieron datos de asistencia.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESFU 5</title>
    <link rel="icon" href="/TATA/imagenes/ICON.png" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="/TATA/estilos/style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>

<body>
    <header>
        <!-- Barra de navegación -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <li class="navbar-item" style="list-style: none;">
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
                        <a class="nav-link" href="planeaciones.php"><i class="fas fa-file-alt"></i> Planeaciones</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="asistencias.php"><i class="fas fa-tasks"></i> Asistencias</a>
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

    <main class="container my-5">
        <?php if ($mensaje): ?>
            <div class="alert alert-info">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <!-- Formulario de selección -->
        <div class="card mb-4">
            <div class="card-header">
                <h1>BUSCAR GRUPOS DE ALUMNOS</h1>
                <div class="form-group">
                        <label for="">Docente:</label>
                        <input type="text" class="form-control" value="<?php echo $_SESSION['nombre_docente']; ?>" readonly>
                        <input type="hidden" name="txtIdDocente" value="<?php echo $_SESSION['docente_id']; ?>">
                    </div>
            </div>
            <div class="card-body">
                <form method="POST" action="asistencias.php">
                    <div class="form-row">
                        <!-- Seleccionar Grado -->
                        <div class="form-group col-md-3">
                            <label for="txtIdGrado">Grado:</label>
                            <select class="form-control" name="txtIdGrado" id="txtIdGrado" required>
                                <option value="">Seleccione</option>
                                <?php
                                $queryGrados = "SELECT IdGrado, NombreGrado FROM grados";
                                $stmtGrados = $pdo->prepare($queryGrados);
                                $stmtGrados->execute();
                                $grados = $stmtGrados->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($grados as $grado) {
                                    echo '<option value="' . $grado['IdGrado'] . '">' . htmlspecialchars($grado['NombreGrado']) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <!-- Seleccionar Grupo -->
                        <div class="form-group col-md-3">
                            <label for="txtIdGrupo">Grupo:</label>
                            <select class="form-control" name="txtIdGrupo" id="txtIdGrupo" required>
                                <option value="">Seleccione</option>
                                <?php
                                $queryGrupos = "SELECT IdGrupo, NombreGrupo FROM grupos";
                                $stmtGrupos = $pdo->prepare($queryGrupos);
                                $stmtGrupos->execute();
                                $grupos = $stmtGrupos->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($grupos as $grupo) {
                                    echo '<option value="' . $grupo['IdGrupo'] . '">' . htmlspecialchars($grupo['NombreGrupo']) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <!-- Seleccionar Materia -->
                        <div class="form-group col-md-3">
                            <label for="txtIdMateria">Asignatura:</label>
                            <select class="form-control" name="txtIdMateria" id="txtIdMateria" required>
                                <option value="">Seleccione</option>
                                <?php
                                $queryMaterias = "SELECT IdMateria, NombreMateria FROM materias";
                                $stmtMaterias = $pdo->prepare($queryMaterias);
                                $stmtMaterias->execute();
                                $materias = $stmtMaterias->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($materias as $materia) {
                                    echo '<option value="' . $materia['IdMateria'] . '">' . htmlspecialchars($materia['NombreMateria']) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <!-- Ciclo Escolar -->
                        <div class="form-group col-md-3">
                            <label for="txtCicloEscolar">Ciclo Escolar:</label>
                            <input type="text" class="form-control" name="txtCicloEscolar" id="txtCicloEscolar"
                                placeholder="2023-2024" required>
                        </div>
                    </div>
                    <button type="submit" name="accion" value="CARGAR_ALUMNOS" class="btn btn-primary">
                        Cargar Alumnos
                    </button>
                </form>
            </div>
        </div>

        <!-- Formulario de registro de asistencias -->
        <?php if (!empty($listaAlumnos)): ?>
            <div class="card">
                <div class="card-header">
                    <h4>Registro de Asistencias - Fecha: <?php echo $datosSeleccion['FechaA']; ?></h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="asistencias.php">
                        <!-- Campos ocultos -->
                        <input type="hidden" name="txtIdDocente" value="<?php echo $datosSeleccion['txtIdDocente']; ?>">
                        <input type="hidden" name="txtIdMateria" value="<?php echo $datosSeleccion['txtIdMateria']; ?>">
                        <input type="hidden" name="txtIdGrado" value="<?php echo $datosSeleccion['txtIdGrado']; ?>">
                        <input type="hidden" name="txtIdGrupo" value="<?php echo $datosSeleccion['txtIdGrupo']; ?>">
                        <input type="hidden" name="txtCicloEscolar" value="<?php echo $datosSeleccion['txtCicloEscolar']; ?>">
                        <input type="hidden" name="FechaA" value="<?php echo $datosSeleccion['FechaA']; ?>">

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre del Alumno</th>
                                    <th>Asistencia</th>
                                    <th>Retardo</th>
                                    <th>Falta</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($listaAlumnos as $alumno): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($alumno['NombreA'] . ' ' . $alumno['ApaternoA'] . ' ' . $alumno['AmaternoA']); ?></td>
                                        <td>
                                            <input type="radio" name="asistencia[<?php echo $alumno['IdAlumno']; ?>]" value="Asistencia" required>
                                        </td>
                                        <td>
                                            <input type="radio" name="asistencia[<?php echo $alumno['IdAlumno']; ?>]" value="Retardo">
                                        </td>
                                        <td>
                                            <input type="radio" name="asistencia[<?php echo $alumno['IdAlumno']; ?>]" value="Falta">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <button type="submit" name="accion" value="GUARDAR_ASISTENCIAS" class="btn btn-success">
                            Guardar Asistencias
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </main>

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

    <!-- Scripts de JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>