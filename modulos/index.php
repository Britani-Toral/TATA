<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/TATA/imagenes/ICON.png" type="image/x-icon">
    <title>ESFU 5</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Enlace al archivo CSS -->
    <link href="/TATA/estilos/index.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <header>
        <div class="text-center">
            <img src="/TATA/imagenes/SINFONDO.png" class="img-fluid" alt="ICON">
        </div>
    </header>
    <div class="background-image">
        <div class="container col-md-4">
            <h1 class="text-center mt-3">BIENVENIDO</h1>
            <form action="/TATA/conexionbd/bdindex.php" method="post">
                <div class="escudo col-md-4">
                    <img src="/TATA/imagenes/ICON.png" class="img-fluid" alt="ICON">
                </div>
                <div class="form-group">
                    <label for="username">Usuario</label>
                    <input type="text" class="form-control" id="username" name="nombreUsuario" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="contrasenaUsuario" required>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility()">
                                <i class="fa fa-eye" id="togglePasswordIcon"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
            </form>
        </div>
    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="/TATA/js/script.js"></script>
    <footer>
        <div class="contenedor d-flex justify-content-center">
            <div class="lzc">
                <img src="/TATA/imagenes/Lazaro.png" class="img-fluid" alt="ICON">
            </div>
            <div>
                <h6><br><br>Avenida Paricutin sin número, Caltzontzin, Michoacán, C.P. 60220 Tel. (452) 452 3698 Correo electrónico: tatalazaro.uruapan@gmail.com</h6>
                <p>Todos los derechos reservados &copy; 2024 | by: <span class="author">Britani Toral</span></p>
            </div>
        </div>
    </footer>

    <script>
        function togglePasswordVisibility() {
            var passwordField = document.getElementById("password");
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