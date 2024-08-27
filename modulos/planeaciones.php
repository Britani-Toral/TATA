<?php
session_start();
include("../conexionbd/bdplaneaciones.php");

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
                <li class="nav-item">
                        <a class="nav-link" href="planeaciones.php"><i class="fas fa-file-alt"></i> Planeaciones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="asistencias.php"><i class="fas fa-tasks"></i> Asistencias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="nosotros.php"><i class="fas fa-info-circle"></i> Acerca de nosotros</a>
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
            <h1 class="text-center">Planeaciones docentes.</h1>
            <!-- Formulario de registro -->
            <form action="" method="post">
                <!-- Campos del formulario -->
                <div class="d-flex justify-content-between">
                    <div class="form-group">
                        <label for="">ID:</label>
                        <input type="text" class="form-control" name="txtIdPlaneacion" value="<?php echo $txtIdPlaneacion; ?>" placeholder="" id="txt1" readonly>
                    </div>
                    <div class="form-group">
                        <label for="">Docente:</label>
                        <input type="text" class="form-control" value="<?php echo $_SESSION['nombre_docente']; ?>" readonly>
                        <input type="hidden" name="txtIdDocente" value="<?php echo $_SESSION['docente_id']; ?>">
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="form-group">
                        <label for="">Asignatura:</label>
                        <select class="form-control" name="txtIdMateria" placeholder="" id="txt3" required>
                            <option value="">Seleccionar</option>
                            <?php
                            // Obtener las materias
                            $queryMaterias = "SELECT * FROM materias";
                            $stmtMaterias = $pdo->prepare($queryMaterias);
                            $stmtMaterias->execute();
                            $Materias = $stmtMaterias->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($Materias as $Materia) {
                                echo '<option value="' . $Materia['IdMateria'] . '" ' . $Materia . '>' . $Materia['NombreMateria'] . '</option>';
                            }
                            ?>
                        </select>
                        <input type="hidden" name="IdMateria" value="">
                    </div>
                    <div class="form-group">
                        <label for="">Grado:</label>
                        <select class="form-control" name="txtIdGrado" placeholder="" id="txt4" required>
                            <option value="">Seleccionar</option>
                            <?php
                            // Obtener los grados
                            $queryGrados = "SELECT * FROM grados";
                            $stmtGrados = $pdo->prepare($queryGrados);
                            $stmtGrados->execute();
                            $Grados = $stmtGrados->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($Grados as $Grado) {
                                echo '<option value="' . $Grado['IdGrado'] . '" ' . $Grado . '>' . $Grado['NombreGrado'] . '</option>';
                            }
                            ?>
                        </select>
                        <input type="hidden" name="IdGrado" value="">
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="form-group">
                        <label>Grupo:</label>
                        <select class="form-control" name="txtIdGrupo[]" placeholder="" id="txt9" required>
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
                    <div class="form-group col-sm-5">
                        <label for="">Numero de bloque:</label>
                        <input type="number" step="1" min="1" max="15" class="form-control" name="txtNumeroBloqueP" value="<?php echo $txtNumeroBloqueP; ?>" placeholder="" id="txt6" required>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="form-group">
                        <label for="">Ciclo Escolar:</label>
                        <input type="text" class="form-control" name="txtCicloEscolarP" value="<?php echo $txtCicloEscolarP; ?>" placeholder="" id="txt7" required>
                    </div>
                    <div class="form-group">
                        <label for="">Periodo:</label>
                        <input type="text" class="form-control" name="txtPeriodoP" value="<?php echo $txtPeriodoP; ?>" placeholder="" id="txt8" required>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="form-group">
                        <label for="">Espacio Curricular:</label>
                        <input type="text" class="form-control" name="txtEspacioCurricularP" value="<?php echo $txtEspacioCurricularP; ?>" placeholder="" id="txt9" required>
                    </div>
                    <div class="form-group">
                        <label for="">Fecha:</label>
                        <input type="date" class="form-control" name="txtFechaP" value="<?php echo $txtFechaP; ?>" placeholder="" id="txt10" required>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="form-group">
                        <label>Práctica Social del Lenguaje:</label>
                        <input type="text" class="form-control" name="txtPracticaSocialLenguajeP" value="<?php echo $txtPracticaSocialLenguajeP; ?>" placeholder="" id="txt11" required>
                    </div>
                    <div class="form-group col-sm-5">
                        <label>Ámbito:</label>
                        <input type="text" class="form-control" name="txtAmbitoP" value="<?php echo $txtAmbitoP; ?>" placeholder="" id="txt12" required>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="form-group">
                        <label for="">Aprendizaje Esperado:</label>
                        <input type="text" class="form-control" name="txtAprendizajeEsperadoP" value="<?php echo $txtAprendizajeEsperadoP; ?>" placeholder="" id="txt13" required>
                    </div>
                    <div class="form-group">
                        <label for="">Modalidad:</label>
                        <input type="text" class="form-control" name="txtModalidadP" value="<?php echo $txtModalidadP; ?>" placeholder="" id="txt14" required>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="form-group">
                        <label for="">Intención Didáctica:</label>
                        <input type="text" class="form-control" name="txtIntencionDidacticaP" value="<?php echo $txtIntencionDidacticaP; ?>" placeholder="" id="txt15" required>
                    </div>
                    <div class="form-group">
                        <label for="">Tiempo de Realización:</label>
                        <input type="text" class="form-control" name="txtTiempoRealizacionP" value="<?php echo $txtTiempoRealizacionP; ?>" placeholder="" id="txt16" required>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="form-group">
                        <label>Sesión:</label>
                        <input type="text" class="form-control" name="txtSesionP" value="<?php echo $txtSesionP; ?>" placeholder="" id="txt17" required>
                    </div>
                    <div class="form-group col-sm-5">
                        <label>Actividad:</label>
                        <input type="text" class="form-control" name="txtActividadP" value="<?php echo $txtActividadP; ?>" placeholder="" id="txt18" required>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <!-- Botones -->
                    <button type="submit" name="accion" <?php echo $accionCancelar; ?> value="CANCELAR" class="btn btn-primary btn-block">CANCELAR</button>
                    <button type="submit" name="accion" <?php echo $accionModificar; ?> value="MODIFICAR" class="btn btn-primary btn-block">MODIFICAR</button>
                    <button type="submit" name="accion" <?php echo $accionEliminar; ?> value="ELIMINAR" class="btn btn-primary btn-block">ELIMINAR</button>
                    <!--<button type="reset" name="accion"  class="btn btn-primary btn-block">LIMPIAR</button>-->
                    <button type="submit" name="accion" <?php echo $accionGuardar; ?> value="GUARDAR" class="btn btn-primary btn-block">GUARDAR</button>
                </div>
            </form>

            <div class="d-flex justify-content-between mt-4">
                <form action="" method="post" class="form-inline mb-3">
                    <input type="text" class="form-control mr-2" name="searchTerm" placeholder="Buscar por Ciclo Escolar">
                    <button type="submit" name="accion" value="BUSCAR" class="btn btn-primary">Buscar</button>
                </form>
            </div>

        </div>
        <div class="container col-md-6">
            <h1 class="text-center">Usuarios Registrados</h1>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Docente</th>
                        <th>Materia</th>
                        <th>Grado</th>
                        <th>Grupo</th>
                        <th>Número de Bloque</th>
                        <th>Ciclo Escolar</th>
                        <th>Periodo</th>
                        <th>Espacio Curricular</th>
                        <th>Fecha</th>
                        <th>Práctica Social del Lenguaje</th>
                        <th>Ámbito</th>
                        <th>Aprendizaje Esperado</th>
                        <th>Modalidad</th>
                        <th>Intención Didáctica</th>
                        <th>Tiempo de Realización</th>
                        <th>Sesión</th>
                        <th>Actividad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listaPlaneaciones as $planeacion) { ?>
                        <tr>
                            <td><?php echo $planeacion['NombreDocente']; ?></td>
                            <td><?php echo $planeacion['NombreMateria']; ?></td>
                            <td><?php echo $planeacion['NombreGrado']; ?></td>
                            <td><?php echo $planeacion['NombreGrupo']; ?></td>
                            <td><?php echo $planeacion['NumeroBloqueP']; ?></td>
                            <td><?php echo $planeacion['CicloEscolarP']; ?></td>
                            <td><?php echo $planeacion['PeriodoP']; ?></td>
                            <td><?php echo $planeacion['EspacioCurricularP']; ?></td>
                            <td><?php echo $planeacion['FechaP']; ?></td>
                            <td><?php echo $planeacion['PracticaSocialLenguajeP']; ?></td>
                            <td><?php echo $planeacion['AmbitoP']; ?></td>
                            <td><?php echo $planeacion['AprendizajeEsperadoP']; ?></td>
                            <td><?php echo $planeacion['ModalidadP']; ?></td>
                            <td><?php echo $planeacion['IntencionDidacticaP']; ?></td>
                            <td><?php echo $planeacion['TiempoRealizacionP']; ?></td>
                            <td><?php echo $planeacion['SesionP']; ?></td>
                            <td><?php echo $planeacion['ActividadP']; ?></td>
                            <td>
                                <form action="" method="post">
                                    <input type="hidden" name="txtIdPlaneacion" value="<?php echo $planeacion['IdPlaneacion']; ?>">
                                    <input type="hidden" name="txtIdDocente" value="<?php echo $planeacion['IdDocente']; ?>">
                                    <input type="hidden" name="txtIdMateria" value="<?php echo $planeacion['IdMateria']; ?>">
                                    <input type="hidden" name="txtIdGrado" value="<?php echo $planeacion['IdGrado']; ?>">
                                    <input type="hidden" name="txtIdGrupo" value="<?php echo $planeacion['IdGrupo']; ?>">
                                    <input type="hidden" name="txtNumeroBloqueP" value="<?php echo $planeacion['NumeroBloqueP']; ?>">
                                    <input type="hidden" name="txtCicloEscolarP" value="<?php echo $planeacion['CicloEscolarP']; ?>">
                                    <input type="hidden" name="txtPeriodoP" value="<?php echo $planeacion['PeriodoP']; ?>">
                                    <input type="hidden" name="txtEspacioCurricularP" value="<?php echo $planeacion['EspacioCurricularP']; ?>">
                                    <input type="hidden" name="txtFechaP" value="<?php echo $planeacion['FechaP']; ?>">
                                    <input type="hidden" name="txtPracticaSocialLenguajeP" value="<?php echo $planeacion['PracticaSocialLenguajeP']; ?>">
                                    <input type="hidden" name="txtAmbitoP" value="<?php echo $planeacion['AmbitoP']; ?>">
                                    <input type="hidden" name="txtAprendizajeEsperadoP" value="<?php echo $planeacion['AprendizajeEsperadoP']; ?>">
                                    <input type="hidden" name="txtModalidadP" value="<?php echo $planeacion['ModalidadP']; ?>">
                                    <input type="hidden" name="txtIntencionDidacticaP" value="<?php echo $planeacion['IntencionDidacticaP']; ?>">
                                    <input type="hidden" name="txtTiempoRealizacionP" value="<?php echo $planeacion['TiempoRealizacionP']; ?>">
                                    <input type="hidden" name="txtSesionP" value="<?php echo $planeacion['SesionP']; ?>">
                                    <input type="hidden" name="txtActividadP" value="<?php echo $planeacion['ActividadP']; ?>">
                                    <button value="Seleccionar" type="submit" name="accion" class="btn btn-primary btn-block">Seleccionar</button>
                                    <!--<button value="ELIMINAR" type="submit" name="accion" class="btn btn-primary btn-block">Eliminar</button>-->
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <nav>
                <ul class="pagination">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>">Anterior</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>">Siguiente</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>


        </div>

    </div>
    </div>

    <!-- Bootstrap JS (opcional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- jQuery desde Google CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Select2 desde CDNJS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

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