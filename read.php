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
      }.codigo{
        font-size: 12px;
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
    <form action="" method="post"> <!--Formulario para consultar datos del alumno-->
      <div class="mb-3">
        <label for="matricula" class="form-label text-white">Buscar Alumno</label>
        <select class="form-select" name="matricula" id="matricula"> <!--Seleccionamos al alumno que queremos consultar-->
          <option value="0" selected>Selecciona el alumno</option>
          <?php
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
        <div class="m-3" align="center">
          <button type="submit" class="btn btn-info" name="buscar" id="buscar">Buscar</button> <!--Boton para consultar datos del alumno-->
        </div>
      </div>
    </form>

        <?php
        require_once "Funciones/encriptado.php";
        if(isset($_POST['buscar'])){
          $matricula=$_POST['matricula']; //Matricula del alumno a consultar
          $query = $conexion->prepare("SELECT * FROM alumno WHERE matricula=:matricula"); //Sentencio SQL para consultar datos del alumno
          $query->bindParam(':matricula',$matricula);
          $query->execute();
          $campo = $query->fetch(PDO::FETCH_ASSOC);

          $dato_cifrado=$campo['nombre']; //Nombre cifrado del alumno
          $nombre_descifrado64 = base64_decode($dato_cifrado);//Traduccion de la cadena

          // Metodo para decifrar los datos cifrados con RSA (Nombre)
          $keyprivada = openssl_pkey_get_private(file_get_contents('keys/privada.key')); // Extræe el contenido del archivo de la llave privada
          openssl_private_decrypt($nombre_descifrado64, $dato_descifrado, $keyprivada); // Método para descifrar los datos

          // Metodo para decifrar los datos cifrados con AES (correo, carrra y telefono)
          $correoDesencriptado=Encriptar::decryption($campo["correo"]); //Correo desencriptado del alumno
          $carreraDesencriptado=Encriptar::decryption($campo["carrera"]); //Carrera desencriptado del alumno
          $telefonoDesencriptado=Encriptar::decryption($campo["telefono"]); //telefono desencriptado del alumno

          //Pintamos los datos del alumno
          echo '<div class="border rounded border-dark border-4 p-4 bg-light box">';
          echo '<h5><b>Datos del Alumno</b></h5><hr>';
          echo '<b>Matricula: </b>' . $campo['matricula'] . '<br>';
          echo '<b style="color:darkgreen">Nombre Desencriptado: </b>' . $dato_descifrado . '<br>';
          echo '<b style="color:darkred">Nombre Encriptado(RSA): </b><i class="codigo">' . $campo['nombre'] . '</i><br>';
          echo '<b style="color:darkgreen">Correo Desencriptado: </b>' . $correoDesencriptado . '<br>';
          echo '<b style="color:darkred">Correo Encriptado(AES): </b><i class="codigo">' . $campo['correo'] . '</i><br>';   
          echo '<b style="color:darkgreen">Carrera Desencriptado: </b>' . $carreraDesencriptado . '<br>';
          echo '<b style="color:darkred">Carrera Encriptado(AES): </b><i class="codigo">' . $campo['carrera'] . '</i><br>';   
          echo '<b style="color:darkgreen">Telefono Desencriptado: </b>' . $telefonoDesencriptado . '<br>';
          echo '<b style="color:darkred">Telefono Encriptado(AES): </b><i class="codigo">' . $campo['telefono'] . '</i>';    
          echo '</div>';
        }
        ?>

  </div>

    <!--JS Boostrap-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous"></script>
</nav>
</body>
</html>

<script type="text/javascript">
$(document).ready(function() {

    $("#buscar").click(function() { //si se da clic en buscar
        if ($("#matricula").val() == 0) { //verifica que se seleccione a un alumno

            Swal.fire({
              icon: 'warning',
              title: '¡Selecciona un alumno!',
            })
            return false;
        }

    });

});
</script>

