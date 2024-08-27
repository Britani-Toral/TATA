<?php
include('../conexion.php');

// Recibir los datos enviados por POST
$txtIdDocente = isset($_POST['txtIdDocente']) ? $_POST['txtIdDocente'] : "";
$txtNombreD = isset($_POST['txtNombreD']) ? $_POST['txtNombreD'] : "";
$txtApaternoD = isset($_POST['txtApaternoD']) ? $_POST['txtApaternoD'] : "";
$txtAmaternoD = isset($_POST['txtAmaternoD']) ? $_POST['txtAmaternoD'] : "";
$txtUsuarioD = isset($_POST['txtUsuarioD']) ? $_POST['txtUsuarioD'] : "";
$txtContrasenaD = isset($_POST['txtContrasenaD']) ? $_POST['txtContrasenaD'] : "";
$txtIdMateria = isset($_POST['txtIdMateria']) ? $_POST['txtIdMateria'] : [];
$txtIdGrado = isset($_POST['txtIdGrado']) ? $_POST['txtIdGrado'] : [];
$txtIdGrupo = isset($_POST['txtIdGrupo']) ? $_POST['txtIdGrupo'] : [];
$txtIdRolUsuario = isset($_POST['txtIdRolUsuario']) ? $_POST['txtIdRolUsuario'] : "";
$searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : "";

$accion = isset($_POST['accion']) ? $_POST['accion'] : "";

$accionGuardar = "";
$accionModificar = $accionEliminar = $accionCancelar = "disabled";
$mostrarModal = false;

$listaDocentes = [];  // Inicialización como un array vacío

switch ($accion) {
    case "GUARDAR":
        $pdo->beginTransaction();
        try {
            // Insertar datos del docente
            $sentencia = $pdo->prepare("INSERT INTO docentes (NombreD, ApaternoD, AmaternoD, UsuarioD, ContrasenaD, IdRolUsuario) 
                                        VALUES (:NombreD, :ApaternoD, :AmaternoD, :UsuarioD, :ContrasenaD, :IdRolUsuario)");

            $sentencia->bindParam(':NombreD', $txtNombreD);
            $sentencia->bindParam(':ApaternoD', $txtApaternoD);
            $sentencia->bindParam(':AmaternoD', $txtAmaternoD);
            $sentencia->bindParam(':UsuarioD', $txtUsuarioD);
            $sentencia->bindParam(':ContrasenaD', $txtContrasenaD);
            $sentencia->bindParam(':IdRolUsuario', $txtIdRolUsuario);
            $sentencia->execute();

            // Obtener el ID del docente insertado
            $idDocente = $pdo->lastInsertId();

            // Insertar relaciones del docente con materias, grados y grupos
            foreach ($txtIdMateria as $materia) {
                foreach ($txtIdGrado as $grado) {
                    foreach ($txtIdGrupo as $grupo) {
                        // Verificar si la relación ya existe antes de insertar
                        $sentencia = $pdo->prepare("SELECT COUNT(*) AS existe FROM docente_materias_grados_grupos 
                                        WHERE IdDocente = :IdDocente 
                                        AND IdMateria = :IdMateria 
                                        AND IdGrado = :IdGrado 
                                        AND IdGrupo = :IdGrupo");
                        $sentencia->bindParam(':IdDocente', $idDocente);
                        $sentencia->bindParam(':IdMateria', $materia);
                        $sentencia->bindParam(':IdGrado', $grado);
                        $sentencia->bindParam(':IdGrupo', $grupo);
                        $sentencia->execute();
                        $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);

                        // Si la relación no existe, insertarla
                        if ($resultado['existe'] == 0) {
                            $sentencia = $pdo->prepare("INSERT INTO docente_materias_grados_grupos (IdDocente, IdMateria, IdGrado, IdGrupo) 
                                            VALUES (:IdDocente, :IdMateria, :IdGrado, :IdGrupo)");
                            $sentencia->bindParam(':IdDocente', $idDocente);
                            $sentencia->bindParam(':IdMateria', $materia);
                            $sentencia->bindParam(':IdGrado', $grado);
                            $sentencia->bindParam(':IdGrupo', $grupo);
                            $sentencia->execute();
                        }
                    }
                }
            }

            // Obtener el registro recién guardado
            $sentencia = $pdo->prepare("SELECT * FROM docentes WHERE IdDocente = :IdDocente");
            $sentencia->bindParam(':IdDocente', $idDocente);
            $sentencia->execute();
            $docenteGuardado = $sentencia->fetchAll(PDO::FETCH_ASSOC);

            // Depuración: Verificar los datos obtenidos
            echo "<pre>";
            print_r($docenteGuardado);
            echo "</pre>";

            if (empty($docenteGuardado)) {
                throw new Exception("No se pudo obtener el docente recién guardado.");
            }

            $_SESSION['mensaje'] = "Docente guardado exitosamente.";
            $_SESSION['listaDocentes'] = $docenteGuardado;

            $pdo->commit();
            header('location: ../modulos/docentes.php');
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Failed: " . $e->getMessage();
        }
        break;

    case "MODIFICAR":
        $pdo->beginTransaction();
        try {
            // Actualizar datos del docente
            $sentencia = $pdo->prepare("UPDATE docentes SET 
                                        NombreD = :NombreD, 
                                        ApaternoD = :ApaternoD, 
                                        AmaternoD = :AmaternoD, 
                                        UsuarioD = :UsuarioD, 
                                        ContrasenaD = :ContrasenaD, 
                                        IdRolUsuario = :IdRolUsuario 
                                        WHERE IdDocente = :IdDocente");

            $sentencia->bindParam(':NombreD', $txtNombreD);
            $sentencia->bindParam(':ApaternoD', $txtApaternoD);
            $sentencia->bindParam(':AmaternoD', $txtAmaternoD);
            $sentencia->bindParam(':UsuarioD', $txtUsuarioD);
            $sentencia->bindParam(':ContrasenaD', $txtContrasenaD);
            $sentencia->bindParam(':IdRolUsuario', $txtIdRolUsuario);
            $sentencia->bindParam(':IdDocente', $txtIdDocente);
            $sentencia->execute();

            // Eliminar relaciones actuales del docente
            $sentencia = $pdo->prepare("DELETE FROM docente_materias_grados_grupos WHERE IdDocente = :IdDocente");
            $sentencia->bindParam(':IdDocente', $txtIdDocente);
            $sentencia->execute();

            // Insertar nuevas relaciones del docente con materias, grados y grupos
            foreach ($txtIdMateria as $materia) {
                foreach ($txtIdGrado as $grado) {
                    foreach ($txtIdGrupo as $grupo) {
                        $sentencia = $pdo->prepare("INSERT INTO docente_materias_grados_grupos (IdDocente, IdMateria, IdGrado, IdGrupo) 
                                                    VALUES (:IdDocente, :IdMateria, :IdGrado, :IdGrupo)");
                        $sentencia->bindParam(':IdDocente', $txtIdDocente);
                        $sentencia->bindParam(':IdMateria', $materia);
                        $sentencia->bindParam(':IdGrado', $grado);
                        $sentencia->bindParam(':IdGrupo', $grupo);
                        $sentencia->execute();
                    }
                }
            }

            // Obtener el registro recién modificado
            $sentencia = $pdo->prepare("SELECT * FROM docentes WHERE IdDocente = :IdDocente");
            $sentencia->bindParam(':IdDocente', $txtIdDocente);
            $sentencia->execute();
            $docenteModificado = $sentencia->fetchAll(PDO::FETCH_ASSOC);

            $_SESSION['mensaje'] = "Docente modificado exitosamente.";
            $_SESSION['listaDocentes'] = $docenteModificado;

            $pdo->commit();
            header('location: ../modulos/docentes.php');
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Failed: " . $e->getMessage();
        }
        break;

    case "ELIMINAR":
        $pdo->beginTransaction();
        try {
            // Eliminar docente
            $sentencia = $pdo->prepare("DELETE FROM docentes WHERE IdDocente = :IdDocente");
            $sentencia->bindParam(':IdDocente', $txtIdDocente);
            $sentencia->execute();

            // Eliminar relaciones del docente con materias, grados y grupos
            $sentencia = $pdo->prepare("DELETE FROM docente_materias_grados_grupos WHERE IdDocente = :IdDocente");
            $sentencia->bindParam(':IdDocente', $txtIdDocente);
            $sentencia->execute();

            $_SESSION['mensaje'] = "Docente eliminado exitosamente.";

            $pdo->commit();
            header('location: ../modulos/docentes.php');
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Failed: " . $e->getMessage();
        }
        break;

    case "Seleccionar":
        $accionGuardar = "disabled";
        $accionModificar = $accionEliminar = $accionCancelar = "";

        // Obtener datos del docente seleccionado
        $sentencia = $pdo->prepare("SELECT * FROM docentes WHERE IdDocente = :IdDocente");
        $sentencia->bindParam(':IdDocente', $txtIdDocente);
        $sentencia->execute();
        $docente = $sentencia->fetch(PDO::FETCH_LAZY);

        // Asignar datos del docente a variables
        $txtNombreD = $docente['NombreD'];
        $txtApaternoD = $docente['ApaternoD'];
        $txtAmaternoD = $docente['AmaternoD'];
        $txtUsuarioD = $docente['UsuarioD'];
        $txtContrasenaD = $docente['ContrasenaD'];
        $txtIdRolUsuario = $docente['IdRolUsuario'];

        // Obtener relaciones del docente con materias, grados y grupos
        $sentencia = $pdo->prepare("SELECT * FROM docente_materias_grados_grupos WHERE IdDocente = :IdDocente");
        $sentencia->bindParam(':IdDocente', $txtIdDocente);
        $sentencia->execute();
        $relaciones = $sentencia->fetchAll(PDO::FETCH_ASSOC);

        $txtIdMateria = array_column($relaciones, 'IdMateria');
        $txtIdGrado = array_column($relaciones, 'IdGrado');
        $txtIdGrupo = array_column($relaciones, 'IdGrupo');

        $mostrarModal = true;
        break;

    case "BUSCAR":
        $searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : "";

        // Consulta para buscar docentes y consolidar materias, grados y grupos
        $query = "SELECT d.IdDocente, d.NombreD, d.ApaternoD, d.AmaternoD, 
                             GROUP_CONCAT(DISTINCT m.NombreMateria ORDER BY m.NombreMateria ASC SEPARATOR ', ') AS Materias, 
                             GROUP_CONCAT(DISTINCT g.NombreGrado ORDER BY g.NombreGrado ASC SEPARATOR ', ') AS Grados, 
                             GROUP_CONCAT(DISTINCT gr.NombreGrupo ORDER BY gr.NombreGrupo ASC SEPARATOR ', ') AS Grupos, 
                             r.NombreRolUsuario
                      FROM docentes d
                      LEFT JOIN docente_materias_grados_grupos dmg ON d.IdDocente = dmg.IdDocente
                      LEFT JOIN materias m ON dmg.IdMateria = m.IdMateria
                      LEFT JOIN grados g ON dmg.IdGrado = g.IdGrado
                      LEFT JOIN grupos gr ON dmg.IdGrupo = gr.IdGrupo
                      LEFT JOIN rolusuarios r ON d.IdRolUsuario = r.IdRolUsuario
                      WHERE d.NombreD LIKE :searchTerm
                         OR d.ApaternoD LIKE :searchTerm
                         OR d.AmaternoD LIKE :searchTerm
                         OR m.NombreMateria LIKE :searchTerm
                         OR g.NombreGrado LIKE :searchTerm
                         OR gr.NombreGrupo LIKE :searchTerm
                         OR r.NombreRolUsuario LIKE :searchTerm
                      GROUP BY d.IdDocente, d.NombreD, d.ApaternoD, d.AmaternoD, r.NombreRolUsuario
                      ORDER BY d.IdDocente";

        $stmt = $pdo->prepare($query);
        $searchTerm = "%" . $searchTerm . "%";
        $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        $listaDocentes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Guardar los resultados en la sesión
        $_SESSION['listaDocentes'] = $listaDocentes;

        // Redirigir a la página de resultados
        header('location: ../modulos/docentes.php');
        exit();
        break;


    case "CANCELAR":
        header('location: ../modulos/docentes.php');
        break;

    case "MOSTRAR_TODO":
        // Consulta para obtener todos los docentes registrados, consolidando materias, grados y grupos
        $query = "SELECT d.IdDocente, d.NombreD, d.ApaternoD, d.AmaternoD, 
                             GROUP_CONCAT(DISTINCT m.NombreMateria ORDER BY m.NombreMateria ASC SEPARATOR ', ') AS Materias, 
                             GROUP_CONCAT(DISTINCT g.NombreGrado ORDER BY g.NombreGrado ASC SEPARATOR ', ') AS Grados, 
                             GROUP_CONCAT(DISTINCT gr.NombreGrupo ORDER BY gr.NombreGrupo ASC SEPARATOR ', ') AS Grupos, 
                             r.NombreRolUsuario
                      FROM docentes d
                      LEFT JOIN docente_materias_grados_grupos dmg ON d.IdDocente = dmg.IdDocente
                      LEFT JOIN materias m ON dmg.IdMateria = m.IdMateria
                      LEFT JOIN grados g ON dmg.IdGrado = g.IdGrado
                      LEFT JOIN grupos gr ON dmg.IdGrupo = gr.IdGrupo
                      LEFT JOIN rolusuarios r ON d.IdRolUsuario = r.IdRolUsuario
                      GROUP BY d.IdDocente, d.NombreD, d.ApaternoD, d.AmaternoD, r.NombreRolUsuario
                      ORDER BY d.IdDocente";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $listaDocentes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Guardar la lista de docentes en la sesión
        $_SESSION['listaDocentes'] = $listaDocentes;
        header('Location: docentes.php'); // Redireccionar para que la tabla se muestre con la lista
        exit();
        break;


    default:
        $listaDocentes = [];  // Inicialización como un array vacío para que no
        break;
}
