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
      }hr{
        color: white;
      }.Title{
        color: yellowgreen;
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
    <!-- FORMULARIO-->
    <form action="" method="post"> <!--Formulario para registrar datos alumno-->
      <div class="mb-3">
        <label for="carrera" class="form-label">Alumno</label>
        <select class="form-select" name="matricula" id="matricula"> <!--Seleccionamos al alumno titular de los datos-->
          <option selected disabled>Selecciona el alumno</option>
          <?php
          // Consulta y Conexión de las matriculas
          require_once 'conexion.php';
          $conexion= new conexion();
          $sql = "SELECT * FROM alumno"; //muestra los alumnos registrados
          $stmt = $conexion->prepare($sql);
          $stmt->execute();
          $consulta = $stmt->fetch(PDO::FETCH_ASSOC);
          ?>
          <option><?php echo $consulta["matricula"] ?></option> <!--matricula de alumno-->
          <?php
          //Ciclo
          while ($mostrar=$stmt->fetch(PDO::FETCH_ASSOC)){
          ?>
          <option><?php echo $mostrar["matricula"] ?></option> <!--matricula de alumno-->
          <?php 
          //Cierre ciclo
          }
          ?>
        </select>
      </div>

      <hr>
      <div class="mb-2 Title" align="center"><b>Datos del Alumno</b></div>

      <div class="mb-3">
        <label for="correo" class="form-label">Correo Electronico</label>
        <input type="email" class="form-control" name="correo" id="correo" placeholder="Introduce un Correo" disabled=""> <!--campo para el correo-->
      </div>

      <div class="mb-3">
        <label for="carrera" class="form-label">Carrera</label>
        <select class="form-select" name="carrera" id="carrera" disabled=""> <!--campo para la carrera-->
          <option selected disabled>Selecciona la Carrera</option>
          <option value="Mecatronica">Mecatronica</option>
          <option value="Tecnologias de la Información">Tecnologias de la Información</option>
          <option value="Administración de Empresas">Administración de Empresas</option>
        </select>
      </div>

      <div class="mb-4">
        <label for="telefono" class="form-label">Numero Telefonico</label>
        <input type="number" class="form-control" name="telefono" id="telefono" placeholder="Introduce un Telefono" disabled=""> <!--campo para el telefono-->
      </div>

      <center><button type="submit" name="guardar" id="guardar" class="btn btn-info" disabled="">Guardar</button></center> <!--Boton para guardar datos-->
    </form>
  </div>

  <?php
  if (isset($_POST['guardar'])){
    //manda a llamar los metodos para encriptar y cadena de conexion
    require_once "Funciones/encriptado.php";
    require_once "conexion.php";

    //Variables de datos del alumno
    $matricula=$_POST['matricula'];
    $correo=$_POST['correo'];
    $carrera=$_POST['carrera'];
    $telefono=$_POST['telefono'];

    //Encripta datos (carrera, correro y telefono) del alumno mediante el cifrado AES
    $carreraEncriptado=Encriptar::encryption($carrera);
    $correoEncriptado=Encriptar::encryption($correo);
    $telefonoEncriptado=Encriptar::encryption($telefono);

    //Sentencia SQL para guardar los datos cifrados del alumno
    $Actualizar=$conexion->prepare("UPDATE alumno SET correo = :correo, carrera = :carrera, telefono = :telefono WHERE matricula= :matricula");
    $Actualizar->bindParam(':correo',$correoEncriptado);
    $Actualizar->bindParam(':carrera',$carreraEncriptado);
    $Actualizar->bindParam(':telefono',$telefonoEncriptado);
    $Actualizar->bindParam(':matricula',$matricula);
    $Actualizar->execute();

    echo "<script type='text/javascript'>
    Swal.fire({
      icon: 'success',
      title: 'Datos Guardados',
      })
    </script>"; //Mensaje de exito al guardar los datos
  }
  ?>

    <!--JS Boostrap-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous"></script>
</nav>
</body>
</html>

<!--Deshabilitar y habilitar-->
<script type="text/javascript">
  function activar()
  {//Inicio Función
  
        matricula = document.getElementById('matricula').value;
        correo = document.getElementById('correo').value;
        carrera = document.getElementById('carrera').value;
        telefono = document.getElementById('telefono').value;

    if (matricula !="Selecciona el alumno") 
    {//IF habilitar formulario
    document.getElementById('correo').disabled=false
    document.getElementById('carrera').disabled=false
    document.getElementById('telefono').disabled=false
    document.getElementById('guardar').disabled=false

        if (correo != "" && carrera!== "Selecciona la Carrera" && telefono!= "") {
              document.getElementById('guardar').disabled=false
            }else{
              document.getElementById('guardar').disabled = true
            }

    }//IF habilitar formulario
    else
    {//ELSE deshabilita formulario
    document.getElementById('correo').disabled=true
    document.getElementById('carrera').disabled=true
    document.getElementById('telefono').disabled=true
    document.getElementById('guardar').disabled=true

    }//ELSE deshabilita formulario

  }//Fin Función

  //EVENTOS
  document.getElementById('matricula').addEventListener('change', activar);
  document.getElementById('correo').addEventListener('keyup', activar);
  document.getElementById('carrera').addEventListener('change', activar);
  document.getElementById('telefono').addEventListener('keyup', activar);
</script>
