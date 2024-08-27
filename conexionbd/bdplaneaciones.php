<?php
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

// Verificar si el usuario tiene el rol de Administrador
if ($_SESSION['rol'] !== 'Docente') {
    echo '
    <script>
    alert("No tienes permiso para acceder a esta sección");
    window.location = "/TATA/modulos/navegacion.php";
    </script>
    ';
    die();
}

// Recibir los datos enviados por POST
$txtIdPlaneacion = isset($_POST['txtIdPlaneacion']) ? $_POST['txtIdPlaneacion'] : "";
$txtIdDocente = isset($_POST['txtIdDocente']) ? $_POST['txtIdDocente'] : "";
$txtIdMateria = isset($_POST['txtIdMateria']) ? $_POST['txtIdMateria'] : "";
$txtIdGrado = isset($_POST['txtIdGrado']) ? $_POST['txtIdGrado'] : "";
$txtIdGrupo = isset($_POST['txtIdGrupo']) ? $_POST['txtIdGrupo'] : [];
$txtNumeroBloqueP = isset($_POST['txtNumeroBloqueP']) ? $_POST['txtNumeroBloqueP'] : "";
$txtCicloEscolarP = isset($_POST['txtCicloEscolarP']) ? $_POST['txtCicloEscolarP'] : "";
$txtPeriodoP = isset($_POST['txtPeriodoP']) ? $_POST['txtPeriodoP'] : "";
$txtEspacioCurricularP = isset($_POST['txtEspacioCurricularP']) ? $_POST['txtEspacioCurricularP'] : "";
$txtFechaP = isset($_POST['txtFechaP']) ? $_POST['txtFechaP'] : "";
$txtPracticaSocialLenguajeP = isset($_POST['txtPracticaSocialLenguajeP']) ? $_POST['txtPracticaSocialLenguajeP'] : "";
$txtAmbitoP = isset($_POST['txtAmbitoP']) ? $_POST['txtAmbitoP'] : "";
$txtAprendizajeEsperadoP = isset($_POST['txtAprendizajeEsperadoP']) ? $_POST['txtAprendizajeEsperadoP'] : "";
$txtModalidadP = isset($_POST['txtModalidadP']) ? $_POST['txtModalidadP'] : "";
$txtIntencionDidacticaP = isset($_POST['txtIntencionDidacticaP']) ? $_POST['txtIntencionDidacticaP'] : "";
$txtTiempoRealizacionP = isset($_POST['txtTiempoRealizacionP']) ? $_POST['txtTiempoRealizacionP'] : "";
$txtSesionP = isset($_POST['txtSesionP']) ? $_POST['txtSesionP'] : "";
$txtActividadP = isset($_POST['txtActividadP']) ? $_POST['txtActividadP'] : "";

$accion = isset($_POST['accion']) ? $_POST['accion'] : "";

$accionGuardar = "";
$accionModificar = $accionEliminar = $accionCancelar = "disabled";
$mostrarModal = false;

$listaPlaneaciones = [];  // Inicialización como un array vacío


$mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : "";
unset($_SESSION['mensaje']);

// Definir la cantidad de registros por página
$limit = 10; // Puedes cambiar este valor para mostrar más o menos registros por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Obtiene la página actual o asigna 1 por defecto
$offset = ($page - 1) * $limit; // Calcula el desplazamiento

// Inicializar lista de planeaciones como vacío
$listaPlaneaciones = [];

$accion = isset($_POST['accion']) ? $_POST['accion'] : "";

$accionGuardar = "";
$accionModificar = $accionEliminar = $accionCancelar = "disabled";
$mostrarModal = false;

switch ($accion) {
    case "GUARDAR":
        $pdo->beginTransaction();
        try {
            // Insertar datos de la planeación
            $sentencia = $pdo->prepare("INSERT INTO planeaciones 
                (IdDocente, IdMateria, IdGrado, NumeroBloqueP, CicloEscolarP, PeriodoP, EspacioCurricularP, FechaP, PracticaSocialLenguajeP, AmbitoP, AprendizajeEsperadoP, ModalidadP, IntencionDidacticaP, TiempoRealizacionP, SesionP, ActividadP) 
                VALUES 
                (:IdDocente, :IdMateria, :IdGrado, :NumeroBloqueP, :CicloEscolarP, :PeriodoP, :EspacioCurricularP, :FechaP, :PracticaSocialLenguajeP, :AmbitoP, :AprendizajeEsperadoP, :ModalidadP, :IntencionDidacticaP, :TiempoRealizacionP, :SesionP, :ActividadP)");

            $sentencia->bindParam(':IdDocente', $txtIdDocente);
            $sentencia->bindParam(':IdMateria', $txtIdMateria);
            $sentencia->bindParam(':IdGrado', $txtIdGrado);
            $sentencia->bindParam(':NumeroBloqueP', $txtNumeroBloqueP);
            $sentencia->bindParam(':CicloEscolarP', $txtCicloEscolarP);
            $sentencia->bindParam(':PeriodoP', $txtPeriodoP);
            $sentencia->bindParam(':EspacioCurricularP', $txtEspacioCurricularP);
            $sentencia->bindParam(':FechaP', $txtFechaP);
            $sentencia->bindParam(':PracticaSocialLenguajeP', $txtPracticaSocialLenguajeP);
            $sentencia->bindParam(':AmbitoP', $txtAmbitoP);
            $sentencia->bindParam(':AprendizajeEsperadoP', $txtAprendizajeEsperadoP);
            $sentencia->bindParam(':ModalidadP', $txtModalidadP);
            $sentencia->bindParam(':IntencionDidacticaP', $txtIntencionDidacticaP);
            $sentencia->bindParam(':TiempoRealizacionP', $txtTiempoRealizacionP);
            $sentencia->bindParam(':SesionP', $txtSesionP);
            $sentencia->bindParam(':ActividadP', $txtActividadP);
            $sentencia->execute();

            $idPlaneacion = $pdo->lastInsertId();

            // Insertar datos en planeaciones_grupos
            foreach ($txtIdGrupo as $grupo) {
                $sentenciaGrupo = $pdo->prepare("INSERT INTO planeaciones_grupos (IdPlaneacion, IdGrupo) VALUES (:IdPlaneacion, :IdGrupo)");
                $sentenciaGrupo->bindParam(':IdPlaneacion', $idPlaneacion);
                $sentenciaGrupo->bindParam(':IdGrupo', $grupo);
                $sentenciaGrupo->execute();
            }

            $pdo->commit();
            header('location: ../modulos/planeaciones.php?page=1');
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Failed: " . $e->getMessage();
        }
        break;

    case "MODIFICAR":
        $pdo->beginTransaction();
        try {
            // Actualizar datos en planeaciones
            $sentencia = $pdo->prepare("UPDATE planeaciones 
                SET IdDocente = :IdDocente, 
                    IdMateria = :IdMateria, 
                    IdGrado = :IdGrado, 
                    NumeroBloqueP = :NumeroBloqueP, 
                    CicloEscolarP = :CicloEscolarP, 
                    PeriodoP = :PeriodoP, 
                    EspacioCurricularP = :EspacioCurricularP, 
                    FechaP = :FechaP, 
                    PracticaSocialLenguajeP = :PracticaSocialLenguajeP, 
                    AmbitoP = :AmbitoP, 
                    AprendizajeEsperadoP = :AprendizajeEsperadoP, 
                    ModalidadP = :ModalidadP, 
                    IntencionDidacticaP = :IntencionDidacticaP, 
                    TiempoRealizacionP = :TiempoRealizacionP, 
                    SesionP = :SesionP, 
                    ActividadP = :ActividadP
                WHERE IdPlaneacion = :IdPlaneacion");

            $sentencia->bindParam(':IdDocente', $txtIdDocente);
            $sentencia->bindParam(':IdMateria', $txtIdMateria);
            $sentencia->bindParam(':IdGrado', $txtIdGrado);
            $sentencia->bindParam(':NumeroBloqueP', $txtNumeroBloqueP);
            $sentencia->bindParam(':CicloEscolarP', $txtCicloEscolarP);
            $sentencia->bindParam(':PeriodoP', $txtPeriodoP);
            $sentencia->bindParam(':EspacioCurricularP', $txtEspacioCurricularP);
            $sentencia->bindParam(':FechaP', $txtFechaP);
            $sentencia->bindParam(':PracticaSocialLenguajeP', $txtPracticaSocialLenguajeP);
            $sentencia->bindParam(':AmbitoP', $txtAmbitoP);
            $sentencia->bindParam(':AprendizajeEsperadoP', $txtAprendizajeEsperadoP);
            $sentencia->bindParam(':ModalidadP', $txtModalidadP);
            $sentencia->bindParam(':IntencionDidacticaP', $txtIntencionDidacticaP);
            $sentencia->bindParam(':TiempoRealizacionP', $txtTiempoRealizacionP);
            $sentencia->bindParam(':SesionP', $txtSesionP);
            $sentencia->bindParam(':ActividadP', $txtActividadP);
            $sentencia->bindParam(':IdPlaneacion', $txtIdPlaneacion);
            $sentencia->execute();

            // Eliminar asociaciones actuales en planeaciones_grupos
            $sentencia = $pdo->prepare("DELETE FROM planeaciones_grupos WHERE IdPlaneacion = :IdPlaneacion");
            $sentencia->bindParam(':IdPlaneacion', $txtIdPlaneacion);
            $sentencia->execute();

            // Insertar nuevas asociaciones en planeaciones_grupos
            foreach ($txtIdGrupo as $grupo) {
                $sentenciaGrupo = $pdo->prepare("INSERT INTO planeaciones_grupos (IdPlaneacion, IdGrupo) VALUES (:IdPlaneacion, :IdGrupo)");
                $sentenciaGrupo->bindParam(':IdPlaneacion', $txtIdPlaneacion);
                $sentenciaGrupo->bindParam(':IdGrupo', $grupo);
                $sentenciaGrupo->execute();
            }

            $pdo->commit();
            header('location: ../modulos/planeaciones.php?page=1');
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Failed: " . $e->getMessage();
        }
        break;

    case "ELIMINAR":
        $pdo->beginTransaction();
        try {
            // Eliminar la planeación
            $sentencia = $pdo->prepare("DELETE FROM planeaciones WHERE IdPlaneacion = :IdPlaneacion");
            $sentencia->bindParam(':IdPlaneacion', $txtIdPlaneacion);
            $sentencia->execute();

            // Eliminar las asociaciones de grupos
            $sentencia = $pdo->prepare("DELETE FROM planeaciones_grupos WHERE IdPlaneacion = :IdPlaneacion");
            $sentencia->bindParam(':IdPlaneacion', $txtIdPlaneacion);
            $sentencia->execute();

            $pdo->commit();
            header('location: ../modulos/planeaciones.php?page=1');
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Failed: " . $e->getMessage();
        }
        break;

    case "Seleccionar":
        $accionGuardar = "disabled";
        $accionModificar = $accionEliminar = $accionCancelar = "";

        // Obtener datos de la planeación seleccionada
        $sentencia = $pdo->prepare("SELECT * FROM planeaciones WHERE IdPlaneacion = :IdPlaneacion");
        $sentencia->bindParam(':IdPlaneacion', $txtIdPlaneacion);
        $sentencia->execute();
        $planeacion = $sentencia->fetch(PDO::FETCH_LAZY);

        // Asignar datos de la planeación a variables
        $txtIdDocente = $planeacion['IdDocente'];
        $txtIdMateria = $planeacion['IdMateria'];
        $txtIdGrado = $planeacion['IdGrado'];
        $txtNumeroBloqueP = $planeacion['NumeroBloqueP'];
        $txtCicloEscolarP = $planeacion['CicloEscolarP'];
        $txtPeriodoP = $planeacion['PeriodoP'];
        $txtEspacioCurricularP = $planeacion['EspacioCurricularP'];
        $txtFechaP = $planeacion['FechaP'];
        $txtPracticaSocialLenguajeP = $planeacion['PracticaSocialLenguajeP'];
        $txtAmbitoP = $planeacion['AmbitoP'];
        $txtAprendizajeEsperadoP = $planeacion['AprendizajeEsperadoP'];
        $txtModalidadP = $planeacion['ModalidadP'];
        $txtIntencionDidacticaP = $planeacion['IntencionDidacticaP'];
        $txtTiempoRealizacionP = $planeacion['TiempoRealizacionP'];
        $txtSesionP = $planeacion['SesionP'];
        $txtActividadP = $planeacion['ActividadP'];

        // Obtener asociaciones actuales en planeaciones_grupos
        $sentencia = $pdo->prepare("SELECT * FROM planeaciones_grupos WHERE IdPlaneacion = :IdPlaneacion");
        $sentencia->bindParam(':IdPlaneacion', $txtIdPlaneacion);
        $sentencia->execute();
        $relaciones = $sentencia->fetchAll(PDO::FETCH_ASSOC);

        $txtIdGrupo = array_column($relaciones, 'IdGrupo');

        $mostrarModal = true;
        break;

        case "BUSCAR":
            $searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : "";
        
            // Consulta para buscar planeaciones por ciclo escolar
            $query = "SELECT planeaciones.*, 
                             docentes.NombreD AS NombreDocente, 
                             materias.NombreMateria, 
                             grados.NombreGrado, 
                             GROUP_CONCAT(DISTINCT grupos.NombreGrupo ORDER BY grupos.NombreGrupo ASC SEPARATOR ', ') AS Grupos
                      FROM planeaciones
                      LEFT JOIN docentes ON planeaciones.IdDocente = docentes.IdDocente
                      LEFT JOIN materias ON planeaciones.IdMateria = materias.IdMateria
                      LEFT JOIN grados ON planeaciones.IdGrado = grados.IdGrado
                      LEFT JOIN planeaciones_grupos ON planeaciones.IdPlaneacion = planeaciones_grupos.IdPlaneacion
                      LEFT JOIN grupos ON planeaciones_grupos.IdGrupo = grupos.IdGrupo
                      WHERE planeaciones.CicloEscolarP LIKE :searchTerm
                      GROUP BY planeaciones.IdPlaneacion, 
                               docentes.NombreD, 
                               materias.NombreMateria, 
                               grados.NombreGrado
                      ORDER BY planeaciones.IdPlaneacion";
        
            $stmt = $pdo->prepare($query);
            $searchTerm = "%" . $searchTerm . "%";
            $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
            $stmt->execute();
            $listaPlaneaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            // Guardar los resultados en la sesión
            $_SESSION['listaPlaneaciones'] = $listaPlaneaciones;
        
            // Redirigir a la página de resultados
            header('location: planeaciones.php?page=1');
            exit();
            break;
        

    case "CANCELAR":
        header('location: ../modulos/planeaciones.php?page=1');
        break;

    case "MOSTRAR_TODO":
        $query = "SELECT planeaciones.*, 
                         docentes.NombreD AS NombreDocente, 
                         materias.NombreMateria, 
                         grados.NombreGrado, 
                         grupos.NombreGrupo
                  FROM planeaciones
                  LEFT JOIN docentes ON planeaciones.IdDocente = docentes.IdDocente
                  LEFT JOIN materias ON planeaciones.IdMateria = materias.IdMateria
                  LEFT JOIN grados ON planeaciones.IdGrado = grados.IdGrado
                  LEFT JOIN planeaciones_grupos ON planeaciones.IdPlaneacion = planeaciones_grupos.IdPlaneacion
                  LEFT JOIN grupos ON planeaciones_grupos.IdGrupo = grupos.IdGrupo
                  ORDER BY planeaciones.IdPlaneacion
                  LIMIT :limit OFFSET :offset";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $listaPlaneaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $_SESSION['listaPlaneaciones'] = $listaPlaneaciones;
        header('Location: planeaciones.php?page=' . $page);
        exit();
        break;

    default:
        // Al cargar la página por primera vez o sin acción específica, mostrar el listado con paginación
        $query = "SELECT planeaciones.*, 
                         docentes.NombreD AS NombreDocente, 
                         materias.NombreMateria, 
                         grados.NombreGrado, 
                         grupos.NombreGrupo
                  FROM planeaciones
                  LEFT JOIN docentes ON planeaciones.IdDocente = docentes.IdDocente
                  LEFT JOIN materias ON planeaciones.IdMateria = materias.IdMateria
                  LEFT JOIN grados ON planeaciones.IdGrado = grados.IdGrado
                  LEFT JOIN planeaciones_grupos ON planeaciones.IdPlaneacion = planeaciones_grupos.IdPlaneacion
                  LEFT JOIN grupos ON planeaciones_grupos.IdGrupo = grupos.IdGrupo
                  ORDER BY planeaciones.IdPlaneacion
                  LIMIT :limit OFFSET :offset";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $listaPlaneaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $_SESSION['listaPlaneaciones'] = $listaPlaneaciones;
        break;
}

// Obtener el número total de registros para paginación
$total_query = "SELECT COUNT(*) FROM planeaciones";
$total_stmt = $pdo->query($total_query);
$total_rows = $total_stmt->fetchColumn();
$total_pages = ceil($total_rows / $limit);

?>
