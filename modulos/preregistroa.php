<?php
session_start();
include("../conexionbd/bdalumnos.php");
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
            <img src="/TATA/imagenes/Nav.png" class="img-fluid" alt="ICON">
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
                        <a class="nav-link" href="gradosconmaterias.php"><i class="fas fa-book-reader"></i> Materias y Grados</a>
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
        <div class="container col-md-4.5">
            <h1 class="text-center">Registro del alumno.</h1>
            <!-- Formulario de registro -->
            <form action="/TATA/conexionbd/crudalumno.php" method="post">
                <!-- Campos del formulario -->
                <div class="d-flex justify-content-between">
                    <div class="form-group">
                        <label>Primer apellido:</label>
                        <input type="text" class="form-control" name="ApaternoAlumno" required>
                    </div>
                    <div class="form-group">
                        <label>Segundo apellido:</label>
                        <input type="text" class="form-control" name="AmaternoAlumno" required>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="form-group">
                        <label>Nombre(s):</label>
                        <input type="text" class="form-control" name="nombresAlumno" required>
                    </div>
                    <div class="form-group">
                        <label>CURP:</label>
                        <input type="text" class="form-control" name="curpAlumno" required>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="form-group">
                        <label>Fecha de nacimiento:</label>
                        <input type="date" class="form-control" name="fechaNacimientoAlumno" required>
                    </div>
                    <div class="form-group col-sm-5">
                        <label>Sexo:</label>
                        <select name="sexoRef" class="form-control" name="sexoAlumno" required>
                            <option value="0">Seleccionar</option>
                            <option value="Femenino">Femenino</option>
                            <option value="Masculino">Masculino</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="form-group col-sm-5">
                        <label>Peso (Kg):</label>
                        <input type="number" step="0.01" min="20" max="99.99" class="form-control" name="peso" required>
                    </div>
                    <div class="form-group col-sm-5">
                        <label>Estatura (m):</label>
                        <input type="number" step="0.01" min="1" max="2.00" class="form-control" name="estatura" required>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="form-group col-sm-5">
                        <label>Talla de ropa:</label>
                        <select name="tallaRef" class="form-control" required>
                            <option value="0">Seleccionar</option>
                            <option value="XS">XS</option>
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                            <option value="XXL">XXL</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-5">
                        <label>Número de calzado:</label>
                        <input type="number" step="0.5" min="20" max="30.5" class="form-control" name="calzado" required>
                    </div>
                </div>
            </form>
        </div>
        <div class="container col-md-4.5">
            <h3>Datos medicos.</h3>
            <div class="d-flex justify-content-between">
                <div class="form-group col-sm-5">
                    <label for="segundo_apellido">Tipo de sangre:</label>
                    <select name="tipoSanRef" class="form-control" required>
                        <option value="0">Seleccionar</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                </div>
                <div class="form-group col-sm-5">
                    <label>¿Tiene alergias?:</label>
                    <select name="alergiasRef" class="form-control" required>
                        <option value="0">Seleccionar</option>
                        <option value="Sí">Sí</option>
                        <option value="No">No</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="segundo_apellido">¿Cuál alergia?:</label>
                <input type="text" class="form-control" name="tipoAlergia" required>
            </div>
            <div class="d-flex justify-content-between">
                <div class="form-group col-sm-5">
                    <label>¿Tiene servicio médico?:</label>
                    <select name="servmedRef" class="form-control" required>
                        <option value="0">Seleccionar</option>
                        <option value="Sí">Sí</option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="form-group col-sm-5">
                    <label>¿Esta vigente?:</label>
                    <select name="vigenciaRef" class="form-control" required>
                        <option value="0">Seleccionar</option>
                        <option value="Sí">Sí</option>
                        <option value="No">No</option>
                    </select>
                </div>
            </div>
            <div class="form-group col-sm-5">
                <label>Nombre del servicio médico:</label>
                <select name="nommedicRef" class="form-control" required>
                    <option value="">Seleccionar</option>
                    <option value="IMSS">IMSS</option>
                    <option value="ISSSTE">ISSSTE</option>
                    <option value="PEMEX">PEMEX</option>
                    <option value="SEDENA">SEDENA</option>
                    <option value="SEMAR">SEMAR</option>
                    <option value="ISSFAM">ISSFAM</option>
                    <option value="Privado">Privado</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>
        </div>
    </div>

    <div class="background-image">
        <div class="container col-md-4.5">
            <h3>Lugar de nacimiento.</h3>
            <!-- Formulario de registro -->
            <form action="guardar_datos.php" method="post">
                <!-- Campos del formulario -->
                <div class="d-flex justify-content-between">
                    <div class="form-group col-sm-5">
                        <label>País:</label>
                        <input type="text" class="form-control" name="paisNacimiento" required>
                    </div>
                    <div class="form-group col-sm-5">
                        <label>Estado:</label>
                        <input type="text" class="form-control" name="estadoNacimiento" required>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="form-group col-sm-5">
                        <label>Ciudad:</label>
                        <input type="text" class="form-control" name="ciudadNacimiento" required>
                    </div>
                    
                </div>
        </div>

        <div class="container col-md-4.5">
            <h3>Domicilio del alumno.</h3>
            <div class="form-group">
                <label>Calle:</label>
                <input type="text" class="form-control" name="calle" required>
            </div>
            <div class="d-flex justify-content-between">
                <div class="form-group">
                    <label>Número:</label>
                    <input type="number" class="form-control" name="numero" required>
                </div>
                <div class="form-group">
                    <label>Colonia:</label>
                    <input type="text" class="form-control" name="municipio" required>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <div class="form-group">
                    <label>Ciudad:</label>
                    <input type="text" class="form-control" name="ciudadDomicilio" required>
                </div>
                <div class="form-group">
                    <label>C.P.:</label>
                    <input type="number" class="form-control" name="cp" required>
                </div>
            </div>
        </div>
    </div>
    <div class="background-image">
        <div class="container col-md-5">
            <h3>Datos de la madre, padre o tutor.</h3>
            <div class="d-flex justify-content-between">
                <div class="form-group">
                    <label>Primer apellido:</label>
                    <input type="text" class="form-control" name="ApaternoTutor" required>
                </div>
                <div class="form-group">
                    <label for="segundo_apellido">Segundo apellido:</label>
                    <input type="text" class="form-control" name="AmaternoTutor" required>
                </div>
            </div>
            <div class="form-group">
                <label for="segundo_apellido">Nombre(s):</label>
                <input type="text" class="form-control" name="nombresTutor" required>
            </div>
            <div class="d-flex justify-content-between">
                <div class="form-group">
                    <label for="primer_apellido">Número de teléfono:</label>
                    <input type="number" class="form-control"  name="telefonoTutor" required>
                </div>
                <div class="form-group">
                    <label for="fecha_nacimiento">Fecha de nacimiento:</label>
                    <input type="date" class="form-control"  name="fechaNacimientoTutor" required>
                </div>
            </div>
            <div class="form-group">
                <label for="segundo_apellido">Empresa donde labora:</label>
                <input type="text" class="form-control"  name="empresaLaboraTutor" required>
            </div>
            <div class="form-group col-sm-5">
                <label>Nivel máximo de estudios:</label>
                <select name="nestudioRef" class="form-control" required>
                    <option value="0">Seleccionar</option>
                    <option value="Ninguno">Ninguno</option>
                    <option value="Primaria">Primaria</option>
                    <option value="Secundaria">Secundaria</option>
                    <option value="Preparatoria">Preparatoria</option>
                    <option value="Licenciatura">Licenciatura</option>
                    <option value="Maestría">Maestría</option>
                    <option value="Doctorado">Doctorado</option>
                </select>
            </div>
        </div>

        <div class="d-flex justify-content-between">

            <div class="container col-md-4.5">
                <!-- Botones -->
                <button type="submit" class="btn btn-primary btn-block">GUARDAR</button>
                <button type="submit" class="btn btn-link btn-block">DESCARGAR</button>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS (opcional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <<footer>
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