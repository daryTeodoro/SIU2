<?php
require_once "conexion.php";

  $user=$_POST['User'];
  $psw=$_POST['Psw'];

  //Implementacion de cifrado SHA-3
  $hashed_user = hash('sha3-512', $user);
  $hashed_psw = hash('sha3-512', $psw);

  $conexion= new conexion();
  $query=$conexion->prepare('SELECT * FROM usuarios WHERE matricula=:user AND password=:psw');
  $query->bindParam(':user',$hashed_user);
  $query->bindParam(':psw',$hashed_psw);
  $query->execute();

  $count=$query->rowCount();

  if ($count==1)
  {
    $resultado=1;
  }
  else
  {
    $resultado=0;
  }
  echo $resultado;
?>