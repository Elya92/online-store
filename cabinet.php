<?php
 include("functions.php");
 $title = 'Личный кабинет';
 $link = ConnectDB('localhost', 'root', 'root', 'CF');
 if ($link){
  if(!isset($_SESSION)){
   session_start();
  }
  if (isset($_SESSION['auth']) AND $_SESSION['auth']){
   $id = $_SESSION['id'];
   if (isset($_POST['emailchange'])){
    if (isset($_POST['oldemail']) AND !empty($_POST['oldemail'])){
     $oldemail = htmlentities($_POST['oldemail']);
    }
    if (isset($_POST['password']) AND !empty($_POST['password'])){
     $password = htmlentities($_POST['password']);
    }
    if (isset($_POST['newemail']) AND !empty($_POST['newemail'])){
     $newemail = htmlentities($_POST['newemail']);
    }
	$flag_oldemail = $flag_password = false;
   }
   if (isset($_POST['passwordchange'])){
    if (isset($_POST['oldpassword']) AND !empty($_POST['oldpassword'])){
     $oldpassword = htmlentities($_POST['oldpassword']);
    }
    if (isset($_POST['newpassword1']) AND !empty($_POST['newpassword1'])){
     $newpassword1 = htmlentities($_POST['newpassword1']);
    }
    if (isset($_POST['newpassword2']) AND !empty($_POST['newpassword2'])){
     $newpassword2 = htmlentities($_POST['newpassword2']);
    }
    $flag_oldpassword = $flag_newpassword = false;
   }
   
   //Изменение e-mail
   $content = "
    <form method='POST'>
     <b>Изменить e-mail:</b>
     <br><br>
     <label>
      Введите старый e-mail:
      <input type='email' name='oldemail' maxlength='30' value='";
   if (!empty($oldemail)){
    $content .= "{$oldemail}'>";
    $flag_oldemail = FieldCheck($link, 'email', $oldemail, 'users');
    if (!$flag_oldemail){
	 $content .= "<b>Неправильный e-mail!</b>";
    }
   }
   else{
    $content .= "'>";
    if (!empty($password) OR !empty($newemail)){
	 $content .= "<b>Введите старый e-mail!</b>";
    }
   }
   $content .= "
    </label>
    <br><br>
    <label>
     Введите пароль:
     <input type='password' name='password' maxlength='20'>";
   if (!empty($password)){
    if ($flag_oldemail){
	 $flag_password = PasswordCheck($link, $id, $password);
	 if (!$flag_password){
	  $content .= "<b>Неверный пароль!</b>";
	 }
    }
   }
   else{
    if (!empty($oldemail) OR !empty($newemail)){
     $content .= "<b>Введите пароль!</b>";
    }
   }
   $content .= "
    </label>
    <br><br>
    <label>
     Введите новый e-mail:
     <input type='email' name='newemail' maxlength='30' value='";
   if (!empty($newemail)){
    $content .= "{$newemail}'>";
    $flag_newemail = CorrectEmail($newemail);
    if ($flag_newemail){
     if ($flag_password){
	  if (FieldUpdate($link, 'email', $newemail, $id, 'users')){
	   $content .= "<b>E-mail успешно изменен!</b>";
	  }
	  else{
	   $content .= "<b>Ошибка, ваш e-mail не изменен! Попробуйте еще раз.</b>";
	  }
     }
    }
    else{
	 $content .= "<b>Некорректный e-mail!</b>";
    }
   }
   else{
    $content .= "'>";
    if (!empty($oldemail) OR !empty($password)){
	 $content .= "<b>Введите новый e-mail!</b>";
    }
   }
   $content .= "
     </label>
     <br><br>
     <input type='submit' value='Изменить' name='emailchange'>
    </form>";
   
   //Изменение пароля
   $content .= "
    <br><br>
    <form method='POST'>
     <b>Изменить пароль:</b>
     <br><br>
     <label>
      Введите старый пароль:
      <input type='password' name='oldpassword' maxlength='20'>";
    if (!empty($oldpassword)){
	 $flag_oldpassword = PasswordCheck($link, $id, $oldpassword);
	 if (!$flag_oldpassword){
	  $content .= "<b>Неверный пароль!</b>";
	 }
    }
    else{
	 if (!empty($newpassword1) OR !empty($newpassword2)){
	  $content .= "<b>Введите пароль!</b>";
	 }
    } 
    $content .= "
     </label>
     <br><br>
     <label>
      Введите новый пароль:
      <input type='password' name='newpassword1' maxlength='20' placeholder='не менее 4 символов'>";
    if (empty($newpassword1) AND (!empty($oldpassword) OR !empty($newpassword2))){
	 $content .= "<b>Введите новый пароль!</b>";
    }
	if (!empty($newpassword1) AND strlen($newpassword1)<4){
	 $content .= "<b>Слишком короткий пароль!</b>";
	}
    $content .= "
     </label>
     <br><br>
     <label>
      Повторите ввод:
      <input type='password' name='newpassword2' maxlength='20'>";
    if (!empty($newpassword1) AND !empty($newpassword2)){
	 if ($newpassword1 == $newpassword2){
	  if ($flag_oldpassword){
	   if (PasswordUpdate($link, $id, $password)){
	    $content .= "<b>Пароль успешно изменен.</b>";
	   }
	   else{
	    $content .= "<b>Ошибка! Пароль не изменен! Попробуйте еще раз.</b>";
	   }
	  }
	 }
	 else{
	  $content .= "<b>Пароли не совпадают!</b>";
	 }
    }
    $content .= "
      </label>
      <br><br>
      <input type='submit' value='Изменить' name='passwordchange'>
     </form>
     <a href='deleteuser.php'><b>Удалить аккаунт</b></a>";
  }
 }
 else{
  $content = "<b>Неизвестная ошибка!</b>";
 }
 
 include('shablon.php');
?>