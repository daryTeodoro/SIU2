<?php
session_start(); //Abrimos una sesion para saber quien esta logeado
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="img/escuela.png" alt="favicon">
    <title>Mi Portal</title>
    <!--Libreria de Boostrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <!--API Google Fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tilt+Warp&display=swap" rel="stylesheet">
    <!-- JQuery Validate library -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Libreria de sweetalert 2-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!--Codigo CSS-->
    <style type="text/css">
      .Main{
        background: rgba(0, 0, 0, 0.3) url(https://media.istockphoto.com/id/1351451565/es/foto/rueda-de-color-o-concepto-de-teor%C3%ADa-del-color-con-l%C3%A1pices-de-colores.jpg?b=1&s=170667a&w=0&k=20&c=6Y1ORVvNXzDNg4V7nFesGX1l17K0rpxxJggV1S95suA=) no-repeat center center fixed;
        background-size: cover;
        background-blend-mode: darken;
        height: 100vh;
        display: flex;
        align-self: center;
        align-items: center;
        justify-content: center;
        flex-direction: column;
      }
      .Contenedor{
        background-color: white;
        border: 2px solid lightblue;
        text-align: center;
        padding: 20px;
        border: 5px solid #000;
        border-radius: 10px;
      }.Titulo{
        font-family: 'Tilt Warp', cursive;
      }.input-group-text{
        background-color: black;
        color: white;
        border: 2px solid black;
      }.form-control{
        border: 2px solid black;
      }input[type=number]::-webkit-inner-spin-button, 
      input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none;  
      }.a-Cambio{
        color: blue;
      }
    </style>
  </head>
  <body>
    
    <div class="Main" id="Main">
      <div class="Contenedor" id="Contenedor">
        <h1 class="Titulo">Crea una Cuenta</h1>

        <form action="" method="post"> <!--Formulario para registrar usuario-->
          <div class="input-group mb-2">
            <span class="input-group-text"><ion-icon name="person"></ion-icon></span>
            <input type="number" class="form-control" name="R-User" required placeholder="Ingresa un Usuario" aria-label="Username"> <!--Campo para el usuario-->
          </div>

          <div class="input-group mb-2">
            <span class="input-group-text"><ion-icon name="key"></ion-icon></span>
            <input type="text" class="form-control" name="R-Name" required placeholder="Ingresa tu Nombre" aria-label="Username"> <!--Campo para el nombre-->
          </div>

          <div class="input-group mb-3">
            <span class="input-group-text"><ion-icon name="key"></ion-icon></span>
            <input type="password" class="form-control" name="R-Psw" required placeholder="Ingresa una Contraseña" aria-label="Username"> <!--Campo para la contraseña-->
          </div>

          <div class="mb-3">
            ¿Ya tienes cuenta? <a href="index.php" class="a-Cambio">Inicia Sesion</a> <!--ancla para ir al formulario de login-->
          </div>

          <button type="submit" name="Registrar" class="btn btn-primary">Registrar</button> <!--boton para guardar registro-->
        </form>
      </div>
    </div>

    <?php
    //Codigo PHP para el registro
    if (isset($_POST['Registrar'])) {
      require_once "conexion.php";

      //variables para registrar al usuario
      $user=$_POST['R-User'];
      $name=$_POST['R-Name'];
      $psw=$_POST['R-Psw'];

      //Implementacion de cifrado SHA-3
      $hashed_user = hash('sha3-512', $user);
      $hashed_psw = hash('sha3-512', $psw);

      //sentencia SQL para consultar ya existe el usuario
      $conexion= new conexion();
      $query=$conexion->prepare('SELECT * FROM usuarios WHERE usuario=:user');
      $query->bindParam(':user',$hashed_user); //Variable encriptada del usuario
      $query->execute();
      $count=$query->rowCount();

      if ($count){ //si el usuario ya existe se envia una alerta
        echo "<script type='text/javascript'>
          Swal.fire({
            icon: 'error',
            title: 'Error de Registro',
            text: 'El usuario ya existe',
            })
          </script>";
      }else{
        //sentencia SQL para guardar el registro en la bd
        $query1=$conexion->prepare('INSERT INTO usuarios (usuario, nombre, password) VALUES (:user, :name, :psw)');
        $query1->bindParam(':user',$hashed_user); //guardamos el usuario cifrado
        $query1->bindParam(':name',$name); //guardamos el nombre
        $query1->bindParam(':psw',$hashed_psw); //guardamos la contraseña cifrado
        $query1->execute();

        if ($query1){ //si se guarda se redirige al inicio
          $_SESSION['user']=$hashed_user;
          header("Location:inicio.php");
        }else{ //si no se completo el registro envia alerta
          echo "<script type='text/javascript'>
          Swal.fire({
            icon: 'error',
            title: 'Error de Registro',
            text: 'Intentelo nuevamente',
            })
          </script>";
        }

      }
    }
    ?>

    <!--JS Boostrap-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous"></script>
    <!--Libreria de Iconos-->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  </body>
</html>
