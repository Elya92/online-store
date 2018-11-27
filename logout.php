<?php
 include "functions.php";
 if(!isset($_SESSION)){
  session_start();
 }
 if (!empty($_SESSION['auth']) AND $_SESSION['auth']){
  session_destroy();
  setcookie('id', '', time());
  setcookie('key', '', time());
  //сделать удаление куков из БД?
 }
 Redirect("main.php", 0);
?>