<?php
 include ("functions.php");
 $title = 'Администрирование';
 $content = "";
 if ($_POST['password'] == 'admin'){
  $content .= "
   <ul>
    <li><a href='adminfeedback.php'>Отзывы</a></li>
	<li><a href='blockingadmin.php'>Заблокированные пользователи</a></li>
   </ul>";
 }
 include('shablon.php');
?>