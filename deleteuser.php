<?php
 include("functions.php");
 $title = 'Удаление аккаунта';
 $link = ConnectDB('localhost', 'root', 'root', 'CF');
 if ($link){
  if(!isset($_SESSION)){
   session_start();
  }
  if (isset($_SESSION['auth']) AND $_SESSION['auth']){
   $id = $_SESSION['id'];
   if (isset($_POST['password']) AND !empty($_POST['password'])){
    $password = htmlentities($_POST['password']);
   }
   $content = "<b>Обратите внимание, что если вы удалите аккаунт, то его уже нельзя будет восстановить!";
   $flag_feedback = FieldCheck($link, 'iduser', $id, 'feedbacks');
   if ($flag_feedback){
	$content .= "<br>Также будет удален ваш отзыв.";
   }
   $content .= "</b><br><br>
    <form method='POST'>
     <label>
	  Введите пароль:
      <input type='password' name='password' maxlength='20'>";
   if (!empty($password)){
    $flag_password = PasswordCheck($link, $id, $password);
    if (!$flag_password){
	 $content .= "<b>Неверный пароль!</b>";
    }
	else{
	 //удаление пользователя
	 if (DeleteUser($link, $id)){
	  if (!empty($_SESSION['auth']) AND $_SESSION['auth']){
	   session_destroy();
	   setcookie('id', '', time());
	   setcookie('key', '', time());
	  }
	  //очистить отзыв, если он есть
	  if ($flag_password){
	   if (DeleteFeedback($link, $id)){
	    Redirect("main.php", 0);
	   }
	   else{
		$content .= "<b>Ошибка удаления! Попробуйте еще раз!</b>";
	   }
	  }
	  else{
	   Redirect("main.php", 0);
	  }
	 }
	 else{
	  $content .= "<b>Ошибка удаления! Попробуйте еще раз!</b>";
	 }
	}
   }
   $content .= "
      <br><br>
      <input type='submit' value='Удалить аккаунт'>
	 </label>
    </form>
   ";
  }
 }
 else{
  $content = "<b>Неизвестная ошибка!</b>";
 }
 
 include ("shablon.php");
?>

