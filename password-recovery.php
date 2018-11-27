<?php
 include ("functions.php");
 $title = 'Восстановление пароля';
 $link = ConnectDB('localhost', 'root', 'root', 'CF');
 if ($link){
  if (isset($_POST['click'])){
   if (isset($_POST['email']) AND !empty($_POST['email'])){
	$email = htmlentities($_POST['email']);
   }
  }
  
  $content = "<form method='POST'>
   Введите e-mail:
   <input type='email' name='email' maxlength='30' value='";
  if (!empty($email)){
   $content .= "{$email}'>";
   $flag_email = FieldCheck($link, 'email', $email, 'users');
   if (!$flag_email){
	$content .= "<b>Неверный e-mail!</b>";
   }
   else{
	$password = ReturnPassword($link, $email);
	$message = 'Ваш пароль: {$}';
	if (mail($email, 'password recovery', $message, 'From: recovery@cf.com')){
	 $content .= "<b>На вашу почту отправлено письмо с паролем.</b>";
	}
	else{
	 $content .= "<b>Произошла ошибка, попробуйте еще раз.</b>";
	}
   }
  }
  else{
   $content .= "'>";
  }
  $content .="<br><br><input type='submit' value='Восстановить пароль' name='click'>
   </form>";
 }
 else{
  $content = "<b>Неизвестная ошибка!</b>";
 }
 include ("shablon.php");
?>

