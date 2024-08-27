<?php
include('../conexion.php');

// Recibir los datos enviados por POST
$txtIdAlumno = isset($_POST['txtIdAlumno']) ? $_POST['txtIdAlumno'] : "";
$txtNombreA = isset($_POST['txtNombreA']) ? $_POST['txtNombreA'] : "";
$txtApaternoA = isset($_POST['txtApaternoA']) ? $_POST['txtApaternoA'] : "";
$txtAmaternoA = isset($_POST['txtAmaternoA']) ? $_POST['txtAmaternoA'] : "";
$txtCurpA = isset($_POST['txtCurpA']) ? $_POST['txtCurpA'] : "";
$txtIdMateria = isset($_POST['txtIdMateria']) ? $_POST['txtIdMateria'] : [];
$txtIdGrado = isset($_POST['txtIdGrado']) ? $_POST['txtIdGrado'] : "";
$txtIdGrupo = isset($_POST['txtIdGrupo']) ? $_POST['txtIdGrupo'] : "";
$searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : "";

$accion = isset($_POST['accion']) ? $_POST['accion'] : "";

$accionGuardar = "";
$accionModificar = $accionEliminar = $accionCancelar = "disabled";
$mostrarModal = false;

$listaAlumnos = [];  // Inicialización como un array vacío

switch ($accion) {
    case "GUARDAR":
        $pdo->beginTransaction();
        try {
            // Insertar datos del alumno
            $sentencia = $pdo->prepare("INSERT INTO alumnos (ApaternoA, AmaternoA, NombreA, CurpA, IdGrado, IdGrupo) 
                                        VALUES (:ApaternoA, :AmaternoA, :NombreA, :CurpA, :IdGrado, :IdGrupo)");
            $sentencia->bindParam(':ApaternoA', $txtApaternoA);
            $sentencia->bindParam(':AmaternoA', $txtAmaternoA);
            $sentencia->bindParam(':NombreA', $txtNombreA);
            $sentencia->bindParam(':CurpA', $txtCurpA);
            $sentencia->bindParam(':IdGrado', $txtIdGrado);
            $sentencia->bindParam(':IdGrupo', $txtIdGrupo);
            $sentencia->execute();

            // Obtener el ID del alumno recién insertado
            $idAlumno = $pdo->lastInsertId();

            // Insertar las relaciones del alumno con las materias
            foreach ($txtIdMateria as $idMateria) {
                $sentencia = $pdo->prepare("INSERT INTO alumno_materias (IdAlumno, IdMateria) 
                                            VALUES (:IdAlumno, :IdMateria)");
                $sentencia->bindParam(':IdAlumno', $idAlumno);
                $sentencia->bindParam(':IdMateria', $idMateria);
                $sentencia->execute();
            }

            $pdo->commit();

            $_SESSION['mensaje'] = "Alumno guardado exitosamente.";
            header('location: ../modulos/alumnos.php');
            exit();
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Failed: " . $e->getMessage();
        }
        break;

    case "MODIFICAR":
        $pdo->beginTransaction();
        try {
            // Actualizar datos del alumno
            $sentencia = $pdo->prepare("UPDATE alumnos SET 
                                        ApaternoA = :ApaternoA,
                                        AmaternoA = :AmaternoA,
                                        NombreA = :NombreA,
                                        CurpA = :CurpA,
                                        IdGrado = :IdGrado, 
                                        IdGrupo = :IdGrupo
                                        WHERE IdAlumno = :IdAlumno");
            $sentencia->bindParam(':ApaternoA', $txtApaternoA);
            $sentencia->bindParam(':AmaternoA', $txtAmaternoA);
            $sentencia->bindParam(':NombreA', $txtNombreA);
            $sentencia->bindParam(':CurpA', $txtCurpA);
            $sentencia->bindParam(':IdGrado', $txtIdGrado);
            $sentencia->bindParam(':IdGrupo', $txtIdGrupo);
            $sentencia->bindParam(':IdAlumno', $txtIdAlumno);
            $sentencia->execute();

            // Eliminar relaciones anteriores del alumno con las materias
            $sentenciaEliminar = $pdo->prepare("DELETE FROM alumno_materias WHERE IdAlumno = :IdAlumno");
            $sentenciaEliminar->bindParam(':IdAlumno', $txtIdAlumno);
            $sentenciaEliminar->execute();

            // Insertar las nuevas relaciones del alumno con las materias
            foreach ($txtIdMateria as $idMateria) {
                $sentenciaInsertar = $pdo->prepare("INSERT INTO alumno_materias (IdAlumno, IdMateria) 
                                                    VALUES (:IdAlumno, :IdMateria)");
                $sentenciaInsertar->bindParam(':IdAlumno', $txtIdAlumno);
                $sentenciaInsertar->bindParam(':IdMateria', $idMateria);
                $sentenciaInsertar->execute();
            }

            $pdo->commit();

            $_SESSION['mensaje'] = "Alumno modificado exitosamente.";
            header('location: ../modulos/alumnos.php');
            exit();
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Failed: " . $e->getMessage();
        }
        break;

    case "ELIMINAR":
        $pdo->beginTransaction();
        try {
            // Eliminar relaciones del alumno con las materias
            $sentenciaEliminarMaterias = $pdo->prepare("DELETE FROM alumno_materias WHERE IdAlumno = :IdAlumno");
            $sentenciaEliminarMaterias->bindParam(':IdAlumno', $txtIdAlumno);
            $sentenciaEliminarMaterias->execute();

            // Eliminar el registro del alumno
            $sentenciaEliminarAlumno = $pdo->prepare("DELETE FROM alumnos WHERE IdAlumno = :IdAlumno");
            $sentenciaEliminarAlumno->bindParam(':IdAlumno', $txtIdAlumno);
            $sentenciaEliminarAlumno->execute();

            $pdo->commit();

            $_SESSION['mensaje'] = "Alumno eliminado exitosamente.";
            header('location: ../modulos/alumnos.php');
            exit();
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Failed: " . $e->getMessage();
        }
        break;

    case "Seleccionar":
        $accionGuardar = "disabled";
        $accionModificar = $accionEliminar = $accionCancelar = "";

        // Obtener datos del alumno seleccionado
        $sentencia = $pdo->prepare("SELECT * FROM alumnos WHERE IdAlumno = :IdAlumno");
        $sentencia->bindParam(':IdAlumno', $txtIdAlumno);
        $sentencia->execute();
        $alumno = $sentencia->fetch(PDO::FETCH_LAZY);

        if ($alumno) {
            // Asignar datos del alumno a variables
            $txtNombreA = $alumno['NombreA'];
            $txtApaternoA = $alumno['ApaternoA'];
            $txtAmaternoA = $alumno['AmaternoA'];
            $txtCurpA = $alumno['CurpA'];
            $txtIdGrado = $alumno['IdGrado'];
            $txtIdGrupo = $alumno['IdGrupo'];

            // Obtener las materias asociadas al alumno
            $sentenciaMaterias = $pdo->prepare("SELECT IdMateria FROM alumno_materias WHERE IdAlumno = :IdAlumno");
            $sentenciaMaterias->bindParam(':IdAlumno', $txtIdAlumno);
            $sentenciaMaterias->execute();
            $txtIdMateria = $sentenciaMaterias->fetchAll(PDO::FETCH_COLUMN);
        }

        $mostrarModal = true;
        break;

    case "CANCELAR":
        header('location: ../modulos/alumnos.php');
        exit();
        break;

    case "BUSCAR":
        $query = "SELECT a.IdAlumno, a.NombreA, a.ApaternoA, a.AmaternoA, g.NombreGrado, gr.NombreGrupo, 
                  GROUP_CONCAT(DISTINCT m.NombreMateria ORDER BY m.NombreMateria ASC SEPARATOR ', ') AS Materias
                  FROM alumnos a
                  INNER JOIN grados g ON a.IdGrado = g.IdGrado
                  INNER JOIN grupos gr ON a.IdGrupo = gr.IdGrupo
                  LEFT JOIN alumno_materias am ON a.IdAlumno = am.IdAlumno
                  LEFT JOIN materias m ON am.IdMateria = m.IdMateria
                  WHERE CONCAT(a.ApaternoA, ' ', a.AmaternoA, ' ', a.NombreA) LIKE :searchTerm
                  GROUP BY a.IdAlumno, g.NombreGrado, gr.NombreGrupo";
        $stmt = $pdo->prepare($query);
        $searchTerm = "%" . $searchTerm . "%";
        $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        $listaAlumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $_SESSION['listaAlumnos'] = $listaAlumnos;

        break;

    case "MOSTRAR_TODO":
        // Consulta para obtener todos los alumnos registrados
        $query = "SELECT a.IdAlumno, a.NombreA, a.ApaternoA, a.AmaternoA, g.NombreGrado, gr.NombreGrupo, 
                  GROUP_CONCAT(DISTINCT m.NombreMateria ORDER BY m.NombreMateria ASC SEPARATOR ', ') AS Materias
                  FROM alumnos a
                  INNER JOIN grados g ON a.IdGrado = g.IdGrado
                  INNER JOIN grupos gr ON a.IdGrupo = gr.IdGrupo
                  LEFT JOIN alumno_materias am ON a.IdAlumno = am.IdAlumno
                  LEFT JOIN materias m ON am.IdMateria = m.IdMateria
                  GROUP BY a.IdAlumno, g.NombreGrado, gr.NombreGrupo";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $listaAlumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Guardar la lista de alumnos en la sesión
        $_SESSION['listaAlumnos'] = $listaAlumnos;
        break;
    

    default:
        $listaAlumnos = [];  // Inicialización como un array vacío
        break;
}

?>
