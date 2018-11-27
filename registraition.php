<?php
 include ("functions.php");
 $title = 'Регистрация';
 
 $link = ConnectDB('localhost', 'root', 'root', 'CF');
 if ($link){
  //mysqli_query($link, 'DELETE FROM users');
  //mysqli_query($link, 'DELETE FROM feedbacks');
  if (isset($_POST['registration'])){
   if (isset($_POST['name'])){
    $name = htmlentities($_POST['name']);
   }
   if (isset($_POST['email'])){
    $email = htmlentities($_POST['email']);
   }
   if (isset($_POST['password1'])){
    $password1 = htmlentities($_POST['password1']);
   }
   if (isset($_POST['password2'])){
    $password2 = htmlentities($_POST['password2']);
   }
   if (isset($_POST['gender'])){
    $gender = $_POST['gender'];
   }
   $flag_name = $flag_email = $flag_password = false;
  }
   
  //форма и имя
  $content = "
   <form method='POST'>
    <label>
     Введите имя:
     <input type='text' name='name' maxlength='30' value='";	
  if(!empty($name)){
   $content .= $name."'>";
   if (!CorrectName($name)){
    $content .= "<b>Некорректное имя!</b>";
   }
   else{
    $flag_name = true;
   }
  }
  else{
   $content .= "'>";
   if (!empty($email) OR !empty($password1) OR !empty($password2)){
    $content .= "<b>Введите имя!</b>";
   }
  }
  
  //пол
  $content .= "
   <br><br>
   Ваш пол:
   <label>
	<input type='radio' name='gender' value='female' checked>
	Женский
   </label>
   <label>
	<input type='radio' name='gender' value='male'>
	Мужской
   </label>";
   
  //e-mail
  $content .= "
   <br><br>
   <label>
    Введите ваш e-mail:
    <input type='email' name='email' maxlength='30' value='";
  if (!empty($email)){
   $content .= $email."'>";
   if (!CorrectEmail($email)){
	$content .= "<b>Некорректный email!</b>";
   }
   else{
	if (FieldCheck($link, 'email', $email, 'users')){
	 $content .= "<b>Данный e-mail уже зарегистрирован!</b>";
	}
	else{
	 $flag_email = true;
	}
   }
  }
  else{
   $content .= "'>";
   if (!empty($name) OR !empty($password1) OR !empty($password2)){
	$content .= "<b>Введите email!</b>";
   }
  }
  
  //пароль  
  $content .= "
   </label>
   <br><br>
   <label>
    Введите пароль:
    <input type='password' name='password1' maxlength='20' placeholder='не менее 4 символов'>";
  if (empty($password1)){
   if (!empty($name) OR !empty($email) OR !empty($password2)){
	$content .= "<b>Введите пароль!</b>";
   }
  }
  else{
   if (strlen($password1)<4){
	$content .= "<b>Слишком короткий пароль!</b>";
   }
  }
  
  //повтор пароля  
  $content .= "
   </label>
   <br><br>
   <label>
    Повторите ввод пароля:
    <input type='password' name='password2' maxlength='20'>";
  if (!empty($password1)){
   if (!empty($password2)){
    if ($password1 == $password2){
	 $flag_password = true;
	}
   }
   else{
	$content .= "<b>Пароли не совпадают!</b>";
   }
  }
  $content .= "
    </label>
    <br><br>
    <input type='submit' value='Зарегистрироваться' name='registration'>
   </form>";
  
  if (isset($_POST['registration'])){
   if ($flag_name AND $flag_email AND $flag_password){
    $salt = GenerateSalt();
    $password = HashPassword($password1, $salt);
    $query = "INSERT INTO users (name, gender, email, password, salt, block) VALUE
	          ('{$name}', '{$gender}', '{$email}', '{$password}', '{$salt}', false)";
    if (mysqli_query($link, $query)){
	 $content .= "<br><br><b>Вы успешно зарегистрировались!</b>";
	 Redirect("autorisation.php", 2000);
    }
    else{
	 $content .= "<br><br><b>Ошибка регистрации, попробуйте еще раз!</b>";
    }
   }
  }
 }
 else{
  $content = "<b>Неизвестная ошибка!</b>";
 }
 
 /*function PrintAllClients($link){
  $query = "SELECT * FROM users";
  $result = mysqli_query($link, $query);
  while ($user = mysqli_fetch_assoc($result)){
   echo "<br>{$user['id']} {$user['name']} {$user['email']}";
  }
 }
 echo PrintAllClients($link);*/
 
 include('shablon.php');
?>

