<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="img/escuela.png" alt="favicon">
  <title>Mi Portal</title>
  <!--Libreria de Boostrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <!-- JQuery Validate library -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Libreria de sweetalert 2-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!--API Google Fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tilt+Warp&display=swap" rel="stylesheet">
    <style type="text/css">
      .navbar-brand{
        font-family: 'Tilt Warp', cursive;
      }.navbar{
        vertical-align: center;
        box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.5);
      }.Main{
        background: rgba(0, 0, 0, 0.5) url(https://www.fenalcoantioquia.com/storage/2023/01/back-to-school-background-with-school-supplies-and-copy-space-on-notebook.jpg) no-repeat center center fixed;
        background-size: cover;
        background-blend-mode: darken;
        height: 100vh;
        display: flex;
        align-self: center;
        align-items: center;
        justify-content: center;
        flex-direction: column;
      }label{
        color: white;
      }input[type=number]::-webkit-inner-spin-button, 
      input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none;  
      }
    </style>
</head>
<body>
<nav class="navbar bg-success fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="inicio.php">
      <img src="https://www.utc.edu.mx/templates/ahead_161207/images/designer/e9b0c2169ea0e793e5c7af6085066418_logout4.png" alt="Logo" width="80" height="44" class="d-inline-block align-text-top">
    </a>
    <a href="inicio.php" class="btn btn-danger">Regresar</a> <!--Boton para regresar al inicio-->
  </div>
</nav>

  <div class="Main">
    <form action="" method="post"> <!--Formulario para registrar alumno-->
      <div class="mb-2">
        <label for="correo" class="form-label">Matrícula</label>
        <input type="number" class="form-control" name="matricula" required placeholder="Introduce la Matrícula"> <!--Campo para la matricula del alumno-->
      </div>

      <div class="mb-4">
        <label for="nombre" class="form-label">Nombre del Alumno</label>
        <input type="text" class="form-control" name="nombre" required placeholder="Introduce el Nombre"> <!--Campo para el nombre del alumno-->
      </div>

      <center><button type="submit" name="guardar" class="btn btn-info">Registrar</button></center> <!--Boton para registrar alumno-->
    </form>
  </div>

    <!--JS Boostrap-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous"></script>
</nav>
</body>
</html>

<?php
require_once 'conexion.php';
//Valida que el usuario hizo clik en el Boton
if(isset($_POST["guardar"])){

  //Variables para los datos del alumno
  $matricula = $_POST['matricula'];
  $nombre = $_POST['nombre'];

  //Por medio del cifrado RSA encriptamos el nombre del alumno, para ello usamos la llave publica
  $keypublica = openssl_pkey_get_public(file_get_contents('keys/publica.key')); // Extrae el contenido del archivo de la llave pública
  openssl_public_encrypt($nombre, $nombre_cifrado, $keypublica); // Método para cifrar los datos 
  $nombre_cifrado_64 = base64_encode($nombre_cifrado); //Traduce cadena de cifrado

  # queremos saber si ya existe el alumno
  $conexion=new conexion();
  $sentencia = $conexion->prepare("SELECT matricula FROM alumno WHERE matricula = :matricula");
  $sentencia->bindParam(':matricula',$matricula);
  $sentencia->execute();
  # Ver cuántas filas devuelve
  $numeroDeFilas = $sentencia->rowCount();

  # Si son 0 o menos, significa que no existe
  if ($numeroDeFilas <= 0) {
  //Validar que las cajas no esten vacias
    if (!empty($nombre) && !empty($matricula)){
    //Insertar datos en la tabla de la db  
      $conexion=new conexion();
      $sql=$conexion->prepare("INSERT INTO alumno (matricula, nombre, correo, carrera, telefono) VALUES (:matricula, :nombre, 'S/N', 'S/N', '0')");
      //Asignar el contenido de las variables a los parametros
      $sql->bindParam(':matricula',$matricula);
      $sql->bindParam(':nombre',$nombre_cifrado_64); //Nombre encriptado
      //Ejecutar la variable $sql
      $sql->execute();
      unset($sql);
      //unset($conexion);
    }

    echo "<script type='text/javascript'>
          Swal.fire({
            icon: 'success',
            title: 'Registro Exitoso',
            text: 'El alumno fue registrado correctamente',
            })
          </script>"; //Terminado el registro eviamos el mensaje de exito

  } else { //si el alumno ya esta registrado enviamos alerta
    echo "<script type='text/javascript'>
          Swal.fire({
            icon: 'error',
            title: 'Error de Registro',
            text: 'La matricula ".$matricula." ya esta registrada',
            })
          </script>";
  }
}
?>
