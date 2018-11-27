<?php
 include ("functions.php");
 $title = 'Администрирование';
 $content = "
  <form action='adminmain.php' method='POST'>
   <input type='password' name='password'>
   <input type='submit' value='Войти'>
  </form>";
 include('shablon.php');
?>