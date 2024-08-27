<?php
include('../conexion.php'); 

// Definir todas las variables
$txtidAlumno = (isset($_POST['txtidAlumno'])) ? $_POST['txtidAlumno'] : "";
$txtApaternoAlumno = (isset($_POST['txtApaternoAlumno'])) ? $_POST['txtApaternoAlumno'] : "";
$txtAmaternoAlumno = (isset($_POST['txtAmaternoAlumno'])) ? $_POST['txtAmaternoAlumno'] : "";
$txtnombresAlumno = (isset($_POST['txtnombresAlumno'])) ? $_POST['txtnombresAlumno'] : "";
$txtcurpAlumno = (isset($_POST['txtcurpAlumno'])) ? $_POST['txtcurpAlumno'] : "";
$txtfechaNacimientoAlumno = (isset($_POST['txtfechaNacimientoAlumno'])) ? $_POST['txtfechaNacimientoAlumno'] : "";
$txtsexoAlumno = (isset($_POST['txtsexoAlumno'])) ? $_POST['txtsexoAlumno'] : "";

$txtpeso = (isset($_POST['txtpeso'])) ? $_POST['txtpeso'] : "";
$txtestatura = (isset($_POST['txtestatura'])) ? $_POST['txtestatura'] : "";
$txttalla = (isset($_POST['txttalla'])) ? $_POST['txttalla'] : "";
$txtcalzado = (isset($_POST['txtcalzado'])) ? $_POST['txtcalzado'] : "";
$txttipoSangre = (isset($_POST['txttipoSangre'])) ? $_POST['txttipoSangre'] : "";
$txttieneAlergias = (isset($_POST['txttieneAlergias'])) ? $_POST['txttieneAlergias'] : "";
$txttipoAlergia = (isset($_POST['txttipoAlergia'])) ? $_POST['txttipoAlergia'] : "";
$txtservicioMedico = (isset($_POST['txtservicioMedico'])) ? $_POST['txtservicioMedico'] : "";
$txtservicioMedicoVigente = (isset($_POST['txtservicioMedicoVigente'])) ? $_POST['txtservicioMedicoVigente'] : "";
$txtnombreServicioMedico = (isset($_POST['txtnombreServicioMedico'])) ? $_POST['txtnombreServicioMedico'] : "";

$txtpaisNacimiento = (isset($_POST['txtpaisNacimiento'])) ? $_POST['txtpaisNacimiento'] : "";
$txtestadoNacimiento = (isset($_POST['txtestadoNacimiento'])) ? $_POST['txtestadoNacimiento'] : "";
$txtciudadNacimiento = (isset($_POST['txtciudadNacimiento'])) ? $_POST['txtciudadNacimiento'] : "";

$txtcalle = (isset($_POST['txtcalle'])) ? $_POST['txtcalle'] : "";
$txtnumero = (isset($_POST['txtnumero'])) ? $_POST['txtnumero'] : "";
$txtcolonia = (isset($_POST['txtcolonia'])) ? $_POST['txtcolonia'] : "";
$txtciudadDomicilio = (isset($_POST['txtciudadDomicilio'])) ? $_POST['txtciudadDomicilio'] : "";
$txtcp = (isset($_POST['txtcp'])) ? $_POST['txtcp'] : "";

$txtApaternoTutor = (isset($_POST['txtApaternoTutor'])) ? $_POST['txtApaternoTutor'] : "";
$txtAmaternoTutor = (isset($_POST['txtAmaternoTutor'])) ? $_POST['txtAmaternoTutor'] : "";
$txtnombresTutor = (isset($_POST['txtnombresTutor'])) ? $_POST['txtnombresTutor'] : "";
$txttelefonoTutor = (isset($_POST['txttelefonoTutor'])) ? $_POST['txttelefonoTutor'] : "";
$txtfechaNacimientoTutor = (isset($_POST['txtfechaNacimientoTutor'])) ? $_POST['txtfechaNacimientoTutor'] : "";
$txtempresaLaboraTutor = (isset($_POST['txtempresaLaboraTutor'])) ? $_POST['txtempresaLaboraTutor'] : "";
$txtestudiosTutor = (isset($_POST['txtestudiosTutor'])) ? $_POST['txtestudiosTutor'] : "";

$accion = (isset($_POST['accion'])) ? $_POST['accion'] : "";

$accionGuardar = "";
$accionModificar = $accionEliminar = $accionCancelar = "disabled";
$mostrarModal = false;

switch ($accion) {
    case "GUARDAR":
        try {
            $pdo->beginTransaction();

            // Insertar datos del lugar de nacimiento
            $sentenciaLugarNacimiento = $pdo->prepare("INSERT INTO lugarnacimiento (ciudadNacimiento, estadoNacimiento, paisNacimiento) VALUES (:ciudadNacimiento, :estadoNacimiento, :paisNacimiento)");
            $sentenciaLugarNacimiento->bindParam(':ciudadNacimiento', $txtciudadNacimiento);
            $sentenciaLugarNacimiento->bindParam(':estadoNacimiento', $txtestadoNacimiento);
            $sentenciaLugarNacimiento->bindParam(':paisNacimiento', $txtpaisNacimiento);
            $sentenciaLugarNacimiento->execute();
            $idLugarNacimiento = $pdo->lastInsertId();

            // Insertar datos del domicilio
            $sentenciaDomicilio = $pdo->prepare("INSERT INTO domicilio (calle, numero, colonia, ciudadDomicilio, cp) VALUES (:calle, :numero, :colonia, :ciudadDomicilio, :cp)");
            $sentenciaDomicilio->bindParam(':calle', $txtcalle);
            $sentenciaDomicilio->bindParam(':numero', $txtnumero);
            $sentenciaDomicilio->bindParam(':colonia', $txtcolonia);
            $sentenciaDomicilio->bindParam(':ciudadDomicilio', $txtciudadDomicilio);
            $sentenciaDomicilio->bindParam(':cp', $txtcp);
            $sentenciaDomicilio->execute();
            $idDomicilio = $pdo->lastInsertId();

            // Insertar datos del tutor
            $sentenciaTutor = $pdo->prepare("INSERT INTO tutor (nombresTutor, ApaternoTutor, AmaternoTutor, telefonoTutor, fechaNacimientoTutor, empresaLaboraTutor, estudiosTutor) VALUES (:nombresTutor, :ApaternoTutor, :AmaternoTutor, :telefonoTutor, :fechaNacimientoTutor, :empresaLaboraTutor, :estudiosTutor)");
            $sentenciaTutor->bindParam(':nombresTutor', $txtnombresTutor);
            $sentenciaTutor->bindParam(':ApaternoTutor', $txtApaternoTutor);
            $sentenciaTutor->bindParam(':AmaternoTutor', $txtAmaternoTutor);
            $sentenciaTutor->bindParam(':telefonoTutor', $txttelefonoTutor);
            $sentenciaTutor->bindParam(':fechaNacimientoTutor', $txtfechaNacimientoTutor);
            $sentenciaTutor->bindParam(':empresaLaboraTutor', $txtempresaLaboraTutor);
            $sentenciaTutor->bindParam(':estudiosTutor', $txtestudiosTutor);
            $sentenciaTutor->execute();
            $idTutor = $pdo->lastInsertId();

            // Insertar datos del alumno
            $sentenciaAlumno = $pdo->prepare("INSERT INTO alumno (ApaternoAlumno, AmaternoAlumno, nombresAlumno, curpAlumno, sexoAlumno, fechaNacimientoAlumno, idLugarNacimiento, idDomicilio, idTutor) VALUES (:ApaternoAlumno, :AmaternoAlumno, :nombresAlumno, :curpAlumno, :sexoAlumno, :fechaNacimientoAlumno, :idLugarNacimiento, :idDomicilio, :idTutor)");
            $sentenciaAlumno->bindParam(':ApaternoAlumno', $txtApaternoAlumno);
            $sentenciaAlumno->bindParam(':AmaternoAlumno', $txtAmaternoAlumno);
            $sentenciaAlumno->bindParam(':nombresAlumno', $txtnombresAlumno);
            $sentenciaAlumno->bindParam(':curpAlumno', $txtcurpAlumno);
            $sentenciaAlumno->bindParam(':sexoAlumno', $txtsexoAlumno);
            $sentenciaAlumno->bindParam(':fechaNacimientoAlumno', $txtfechaNacimientoAlumno);
            $sentenciaAlumno->bindParam(':idLugarNacimiento', $idLugarNacimiento);
            $sentenciaAlumno->bindParam(':idDomicilio', $idDomicilio);
            $sentenciaAlumno->bindParam(':idTutor', $idTutor);
            $sentenciaAlumno->execute();
            $idAlumno = $pdo->lastInsertId();

            // Insertar datos médicos del alumno
            $sentenciaDatosMedicos = $pdo->prepare("INSERT INTO datosmedicos (idAlumno, peso, estatura, talla, calzado, tipoSangre, tieneAlergias, tipoAlergia, servicioMedico, servicioMedicoVigente, nombreServicioMedico) VALUES (:idAlumno, :peso, :estatura, :talla, :calzado, :tipoSangre, :tieneAlergias, :tipoAlergia, :servicioMedico, :servicioMedicoVigente, :nombreServicioMedico)");
            $sentenciaDatosMedicos->bindParam(':idAlumno', $idAlumno);
            $sentenciaDatosMedicos->bindParam(':peso', $txtpeso);
            $sentenciaDatosMedicos->bindParam(':estatura', $txtestatura);
            $sentenciaDatosMedicos->bindParam(':talla', $txttalla);
            $sentenciaDatosMedicos->bindParam(':calzado', $txtcalzado);
            $sentenciaDatosMedicos->bindParam(':tipoSangre', $txttipoSangre);
            $sentenciaDatosMedicos->bindParam(':tieneAlergias', $txttieneAlergias);
            $sentenciaDatosMedicos->bindParam(':tipoAlergia', $txttipoAlergia);
            $sentenciaDatosMedicos->bindParam(':servicioMedico', $txtservicioMedico);
            $sentenciaDatosMedicos->bindParam(':servicioMedicoVigente', $txtservicioMedicoVigente);
            $sentenciaDatosMedicos->bindParam(':nombreServicioMedico', $txtnombreServicioMedico);
            $sentenciaDatosMedicos->execute();

            $pdo->commit();

            // Redirección después de la inserción
            header('Location: ../modulos/alumnos.php');
            exit(); // Asegurar que el script se detenga después de la redirección
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo "Error al guardar los datos: " . $e->getMessage();
        }
        break;

    case "CANCELAR":
        // Restablecer valores
        $txtidAlumno = "";
        $txtApaternoAlumno = "";
        $txtAmaternoAlumno = "";
        $txtnombresAlumno = "";
        $txtcurpAlumno = "";
        $txtfechaNacimientoAlumno = "";
        $txtsexoAlumno = "";
        $txtpeso = "";
        $txtestatura = "";
        $txttalla = "";
        $txtcalzado = "";
        $txttipoSangre = "";
        $txttieneAlergias = "";
        $txttipoAlergia = "";
        $txtservicioMedico = "";
        $txtservicioMedicoVigente = "";
        $txtnombreServicioMedico = "";
        $txtpaisNacimiento = "";
        $txtestadoNacimiento = "";
        $txtciudadNacimiento = "";
        $txtcalle = "";
        $txtnumero = "";
        $txtcolonia = "";
        $txtciudadDomicilio = "";
        $txtcp = "";
        $txtApaternoTutor = "";
        $txtAmaternoTutor = "";
        $txtnombresTutor = "";
        $txttelefonoTutor = "";
        $txtfechaNacimientoTutor = "";
        $txtempresaLaboraTutor = "";
        $txtestudiosTutor = "";

        $accionGuardar = "";
        $accionModificar = $accionEliminar = $accionCancelar = "disabled";
        break;
}

// Código HTML o estructura adicional aquí

?>
