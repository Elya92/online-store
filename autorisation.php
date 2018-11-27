<?php
 //если вводишь неправильный e-mail и какой-нибудь пароль, то пишет, что неправильный пароль. Переделать или оставить для лучшей безопасности?
  include ("functions.php");
  $title = 'Авторизация';

 $link = ConnectDB('localhost', 'root', 'root', 'CF');
 if ($link){
  $content = "";
  if (isset($_POST['click'])){
   if (isset($_POST['email']) AND !empty($_POST['email'])){
	$email = htmlentities($_POST['email']);
   }
   if (isset($_POST['password']) AND !empty($_POST['password'])){
    $password = htmlentities($_POST['password']);
   }
   if (isset($_POST['remember']) AND !empty($_POST['remember'])){
    $remember = $_POST['remember'];
   }
  }
  
  //e-mail
  $content .= "
   <form method='POST'>
   <label>
    Введите ваш e-mail:
    <input type='email' name='email' maxlength='30' value='";
  if (!empty($email)){
   $content .= "{$email}'>";
   $flag_email = FieldCheck($link, 'email', $email, 'users');
   if (!$flag_email){
	$content .= "<b>Неверный e-mail!</b>";
   }
  }
  else{
   $content .= "'>";
   if (!empty($password)){
	$content .= "<b>Введите e-mail!</b>";
   }
  }
  
  //пароль
  $content .= "
   </label>
   <br><br>
   <label>
    Введите пароль:
    <input type='password' name='password' maxlength='20'>";
  if (!empty($password)){
   if ($flag_email){
	$id = ReturnId($link, $email);
	if (PasswordCheck($link, $id, $password)){
	 if (!BlockStatus($link, $id)){
	  session_start();
	  $_SESSION['auth'] = true;
	  $_SESSION['id'] = $id;
	  if (!empty($remember) AND $remember==1){
	   $key = GenerateSalt();
	   //установить куки на месяц
	   setcookie('id', $id, time()+60*60*24*30);
	   setcookie('key', $key, time()+60*60*24*30);
	   $query = "UPDATE users SET cookie='{$key}' WHERE id='{$id}'";
	   mysqli_query($link, $query);
	  }
	  Redirect("main.php", 0);
     }
     else {
      $content .= "<br><br><b>Вы заблокированы. За информацией вы можете обратиться в техподдержку: <a href='mailto:support@cf.com'>support@cf.com</a></b>";
     }
	 
	}
	else{
	 $content .= "<b>Неверный пароль!</b>";
	}
   }
   else{
	
   }
  }
  else{
   if (!empty($email)){
	$content .= "<b>Введите пароль!</b>";
   }
  }
  
  //Галочка "Запомнить меня", сделать, чтобы работала
  $content .= "
   </label>
   <br><br>
   <label>
	<input type='checkbox' name='remember' value='1'";
   if (!empty($remember)){
	$content .= " checked";
   }
   $content .= ">
	  Запомнить меня
	 </label>
	 <br><br>
     <input type='submit' value='Войти' name='click'>
     <a href='password-recovery.php'>Забыли пароль?</a>
    </form>";
 }
 else{
  $content = "<b>Неизвестная ошибка!</b>";
 }
 
 include ("shablon.php");
?>