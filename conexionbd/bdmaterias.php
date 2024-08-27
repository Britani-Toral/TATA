<?php
include('../conexion.php');

// Recibir los datos enviados por POST
$txtIdMateria = isset($_POST['txtIdMateria']) ? $_POST['txtIdMateria'] : "";
$txtNombreMateria = isset($_POST['txtNombreMateria']) ? $_POST['txtNombreMateria'] : "";
$searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : "";

$accion = isset($_POST['accion']) ? $_POST['accion'] : "";

$accionGuardar = "";
$accionModificar = $accionEliminar = $accionCancelar = "disabled";
$mostrarModal = false;

$listaMaterias = [];  // Inicialización como un array vacío

// Dependiendo de la acción, realiza la operación correspondiente
switch ($accion) {
    case "GUARDAR":
        $pdo->beginTransaction();
        try {
            // Insertar datos de la materia
            $sentencia = $pdo->prepare("INSERT INTO materias (NombreMateria) VALUES (:NombreMateria)");

            $sentencia->bindParam(':NombreMateria', $txtNombreMateria);
            $sentencia->execute();

            $pdo->commit();

            // Obtener el registro recién guardado
            $sentencia = $pdo->prepare("SELECT * FROM materias WHERE NombreMateria = :NombreMateria");
            $sentencia->bindParam(':NombreMateria', $txtNombreMateria);
            $sentencia->execute();
            $materiaGuardada = $sentencia->fetchAll(PDO::FETCH_ASSOC);

            $_SESSION['mensaje'] = "Materia guardada exitosamente.";
            $_SESSION['listaMaterias'] = $materiaGuardada;

            header('location: ../modulos/materias.php');
            exit();
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Failed: " . $e->getMessage();
        }
        break;

    case "MODIFICAR":
        $pdo->beginTransaction();
        try {
            // Actualizar datos de la materia
            $sentencia = $pdo->prepare("UPDATE materias SET NombreMateria = :NombreMateria WHERE IdMateria = :IdMateria");

            $sentencia->bindParam(':NombreMateria', $txtNombreMateria);
            $sentencia->bindParam(':IdMateria', $txtIdMateria);
            $sentencia->execute();

            $pdo->commit();

            // Obtener el registro recién modificado
            $sentencia = $pdo->prepare("SELECT * FROM materias WHERE IdMateria = :IdMateria");
            $sentencia->bindParam(':IdMateria', $txtIdMateria);
            $sentencia->execute();
            $materiaModificada = $sentencia->fetchAll(PDO::FETCH_ASSOC);

            $_SESSION['mensaje'] = "Materia modificada exitosamente.";
            $_SESSION['listaMaterias'] = $materiaModificada;

            header('location: ../modulos/materias.php');
            exit();
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Failed: " . $e->getMessage();
        }
        break;

    case "ELIMINAR":
        $pdo->beginTransaction();
        try {
            // Eliminar materia
            $sentencia = $pdo->prepare("DELETE FROM materias WHERE IdMateria = :IdMateria");
            $sentencia->bindParam(':IdMateria', $txtIdMateria);
            $sentencia->execute();
            $materiaEliminada = $sentencia->fetchAll(PDO::FETCH_ASSOC);

            $_SESSION['mensaje'] = "Materia eliminada exitosamente.";
            $_SESSION['listaMaterias'] = $materiaEliminada;

            $pdo->commit();
            header('location: ../modulos/materias.php');
            exit();
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Failed: " . $e->getMessage();
        }
        break;

    case "Seleccionar":
        $accionGuardar = "disabled";
        $accionModificar = $accionEliminar = $accionCancelar = "";

        // Obtener datos de la materia seleccionada
        $sentencia = $pdo->prepare("SELECT * FROM materias WHERE IdMateria = :IdMateria");
        $sentencia->bindParam(':IdMateria', $txtIdMateria);
        $sentencia->execute();
        $materia = $sentencia->fetch(PDO::FETCH_LAZY);

        // Asignar datos de la materia a variables
        $txtNombreMateria = $materia['NombreMateria'];

        $mostrarModal = true;
        break;

    case "CANCELAR":
        header('location: ../modulos/materias.php');
        exit();
        break;

    case "BUSCAR":
        $query = "SELECT * FROM materias WHERE NombreMateria LIKE :searchTerm ORDER BY IdMateria";
        $stmt = $pdo->prepare($query);
        $searchTerm = "%" . $searchTerm . "%";
        $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        $listaMaterias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $_SESSION['listaMaterias'] = $listaMaterias;

        break;

    case "MOSTRAR_TODO":
        // Consulta para obtener todas las materias registradas
        $query = "SELECT * FROM materias ORDER BY IdMateria";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $listaMaterias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Guardar la lista de materias en la sesión
        $_SESSION['listaMaterias'] = $listaMaterias;
        break;

    default:
        $listaMaterias = [];  // No cargar materias por defecto, lista inicial vacía
        break;
}
?>