<?php
session_start();
include("../conexionbd/bddocentes.php");

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

// Obtener la lista de docentes desde la sesión o inicializarla como un array vacío
if (isset($_SESSION['listaDocentes'])) {
    $listaDocentes = $_SESSION['listaDocentes'];
    unset($_SESSION['listaDocentes']);
} else {
    $listaDocentes = [];
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
            <h1 class="text-center">Registro de Docentes.</h1>
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
                        <label for="">ID:</label>
                        <input type="text" class="form-control" name="txtIdDocente" value="<?php echo $txtIdDocente; ?>" placeholder="" id="txt1" required readonly>
                    </div>
                    <div class="form-group">
                        <label for="">Nombre(s):</label>
                        <input type="text" class="form-control" name="txtNombreD" value="<?php echo $txtNombreD; ?>" placeholder="" id="txt2" required>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="form-group">
                        <label for="">Apellido paterno:</label>
                        <input type="text" class="form-control" name="txtApaternoD" value="<?php echo $txtApaternoD; ?>" placeholder="" id="txt3" required>
                    </div>
                    <div class="form-group">
                        <label for="">Apellido Materno:</label>
                        <input type="text" class="form-control" name="txtAmaternoD" value="<?php echo $txtAmaternoD; ?>" placeholder="" id="txt4" required>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="form-group">
                        <label>Nombre de Usuario:</label>
                        <input type="text" class="form-control" name="txtUsuarioD" value="<?php echo $txtUsuarioD; ?>" placeholder="" id="txt5" required>
                    </div>
                    <div class="form-group col-sm-5">
                        <label>Contraseña:</label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="txtContrasenaD" value="<?php echo $txtContrasenaD; ?>" placeholder="" id="txt6" required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility()">
                                    <i class="fa fa-eye" id="togglePasswordIcon"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="form-group">
                        <label for="">Asignatura:</label>
                        <select class="form-control" name="txtIdMateria[]" placeholder="" id="txt7" multiple required>
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
                    <div class="form-group col-sm-5">
                        <label for="">Grado:</label>
                        <select class="form-control" name="txtIdGrado[]" placeholder="" id="txt8" multiple required>
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
                </div>
                <div class="d-flex justify-content-between">
                    <div class="form-group">
                        <label>Grupo:</label>
                        <select class="form-control" name="txtIdGrupo[]" placeholder="" id="txt9" multiple required>
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
                    <div class="form-group col-sm-5">
                        <label for="">Rol de usuario:</label>
                        <select class="form-control" name="txtIdRolUsuario" value="<?php echo $txtIdRolUsuario; ?>" placeholder="" id="txt10" required>
                            <option value="">Seleccionar</option>
                            <?php
                            // Obtener los roles de usuario
                            $queryRoles = "SELECT * FROM rolusuarios";
                            $stmtRoles = $pdo->prepare($queryRoles);
                            $stmtRoles->execute();
                            $Roles = $stmtRoles->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($Roles as $Rol) {
                                echo '<option value="' . $Rol['IdRolUsuario'] . '">' . $Rol['NombreRolUsuario'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <!-- Botones -->
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
            <h1 class="text-center">Docentes Registrados</h1>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nombre completo</th>
                        <th>Materia</th>
                        <th>Grado</th>
                        <th>Grupo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($listaDocentes)) { ?>
                        <tr>
                            <td colspan="6" class="text-center">No se encontraron registros</td>
                        </tr>
                    <?php } else { ?>
                        <?php foreach ($listaDocentes as $docente) { ?>
                            <tr>
                                <td><?php echo $docente['ApaternoD'] . ' ' . $docente['AmaternoD'] . ' ' . $docente['NombreD']; ?></td>
                                <td><?php echo $docente['Materias']; ?></td>
                                <td><?php echo $docente['Grados']; ?></td>
                                <td><?php echo $docente['Grupos']; ?></td>
                                <td>
                                    <form method="post">
                                        <input type="hidden" name="txtIdDocente" value="<?php echo $docente['IdDocente']; ?>">
                                        <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary">
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
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
                <h6><br>Avenida Paricutin sin número, Caltzontzin, Michoacán, C.P. 60220 Tel. (452) 452 3698 Correo electrónico: tatalazaro.uruapan@gmail.com</h6>
                <p>Todos los derechos reservados &copy; 2024 | by: <span class="author">Britani Toral</span></p>
            </div>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function togglePasswordVisibility() {
            var passwordField = document.getElementById("txt6");
            var passwordIcon = document.getElementById("togglePasswordIcon");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                passwordIcon.classList.remove("fa-eye");
                passwordIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                passwordIcon.classList.remove("fa-eye-slash");
                passwordIcon.classList.add("fa-eye");
            }
        }
    </script>
</body>

</html>