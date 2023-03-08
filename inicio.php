<?php
session_start(); //Abrimos una sesion para saber quien esta logeado
require_once "conexion.php";
$sesion = $_SESSION['user']; //Usuario del que esta logueado

//sentencia SQL para consultar datos del usuario logueado
$conexion= new conexion();
$query=$conexion->prepare('SELECT * FROM usuarios WHERE usuario=:user');
$query->bindParam(':user',$sesion);
$query->execute();
$row=$query->fetch(PDO::FETCH_ASSOC);
?>
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
      }.Option{
        text-decoration: none;
        color: black;
        background-color: #e9fffe;
        border: 4px solid #000;
        border-radius: 0px 50px;
        padding: 25px 20px;
        width: 30vw;
        text-align: center;
        cursor: pointer;
      }.Option:hover{
        background-color: black;
        color: white;
      }

      @media (max-width: 760px) {
        .Option{
          width: 50%;
        }
      }
    </style>
</head>
<body>
<nav class="navbar bg-success fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="inicio.php">
      <img src="https://www.utc.edu.mx/templates/ahead_161207/images/designer/e9b0c2169ea0e793e5c7af6085066418_logout4.png" alt="Logo" width="80" height="44" class="d-inline-block align-text-top">
    </a>
    <form action="" method="post">
      <button type="submit" name="Exit" class="btn btn-danger">Salir</button> <!--Boton para cerrar sesion-->
    </form>
  </div>
</nav>

  <div class="Main">

    <div class="mb-4 text-white">
      <h4>Hola! <?php echo $row['nombre']; ?></h4>  <!--Nombre del usuario logueado-->
    </div>

    <a href="Read.php" class="Option mb-4" id="read"> <!--opcion para consultar alumno-->
      <h5>Consultar Alumnos</h5>
    </a>
    <a href="Create-Asimetrico.php" class="Option mb-4" id="create-a"> <!--opcion para agregar alumno-->
      <h5>Agregar Alumnos</h5>
    </a>
    <a href="Create-Simetrico.php" class="Option" id="create-s"> <!--opcion para agregar datos del alumno-->
      <h5>Datos de Alumnos</h5>
    </a>
  </div>

    <!--JS Boostrap-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous"></script>
</nav>
</body>
</html>

<?php
if (isset($_POST['Exit'])) {
  session_destroy(); //cerrar sesion
  header("Location:index.php"); //redirigir al index
}
?>
