<?php
include('../conexion.php');

// Establece la zona horaria a Ciudad de México o Guadalajara
date_default_timezone_set('America/Mexico_City');

// Inicializar variables
$txtIdDocente = isset($_POST['txtIdDocente']) ? $_POST['txtIdDocente'] : "";
$txtIdMateria = isset($_POST['txtIdMateria']) ? $_POST['txtIdMateria'] : "";
$txtIdGrado = isset($_POST['txtIdGrado']) ? $_POST['txtIdGrado'] : "";
$txtIdGrupo = isset($_POST['txtIdGrupo']) ? $_POST['txtIdGrupo'] : "";
$txtCicloEscolar = isset($_POST['txtCicloEscolar']) ? $_POST['txtCicloEscolar'] : "";
$FechaA = isset($_POST['FechaA']) ? $_POST['FechaA'] : date('Y-m-d');
$accion = isset($_POST['accion']) ? $_POST['accion'] : "";

// Inicializar lista de alumnos
$listaAlumnos = [];

switch ($accion) {
    case "CARGAR_ALUMNOS":
        // Código para cargar alumnos según el grado, grupo y materia seleccionados
        $queryAlumnos = "SELECT * FROM alumnos WHERE IdGrado = :IdGrado AND IdGrupo = :IdGrupo";
        $stmtAlumnos = $pdo->prepare($queryAlumnos);
        $stmtAlumnos->bindParam(':IdGrado', $txtIdGrado);
        $stmtAlumnos->bindParam(':IdGrupo', $txtIdGrupo);
        $stmtAlumnos->execute();
        $listaAlumnos = $stmtAlumnos->fetchAll(PDO::FETCH_ASSOC);

        $_SESSION['listaAlumnos'] = $listaAlumnos;
        header('location: ../modulos/asistencias.php');
        exit();
        break;

    case "GUARDAR_ASISTENCIAS":
        $pdo->beginTransaction();
        try {
            // Guardar la lista de asistencias en la base de datos
            foreach ($_POST['asistencia'] as $idAlumno => $estadoAsistencia) {
                $stmt = $pdo->prepare("INSERT INTO asistencias (IdDocente, IdAlumno, IdMateria, IdGrado, IdGrupo, FechaA, Asistencia) 
                                       VALUES (:IdDocente, :IdAlumno, :IdMateria, :IdGrado, :IdGrupo, :FechaA, :Asistencia)
                                       ON DUPLICATE KEY UPDATE Asistencia = :Asistencia");
                $stmt->bindParam(':IdDocente', $txtIdDocente);
                $stmt->bindParam(':IdAlumno', $idAlumno);
                $stmt->bindParam(':IdMateria', $txtIdMateria);
                $stmt->bindParam(':IdGrado', $txtIdGrado);
                $stmt->bindParam(':IdGrupo', $txtIdGrupo);
                $stmt->bindParam(':FechaA', $FechaA);
                $stmt->bindParam(':Asistencia', $estadoAsistencia);
                $stmt->execute();
            }

            $pdo->commit();

            // Redirigir al script de generación de Excel automáticamente después de guardar
            header('location: exportar_asistencias.php?IdGrado=' . $txtIdGrado . '&IdGrupo=' . $txtIdGrupo . '&IdMateria=' . $txtIdMateria);
            exit();
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Failed: " . $e->getMessage();
        }
        break;

    case "CANCELAR":
        header('location: ../modulos/asistencias.php');
        break;

    default:
        break;
}
?>
