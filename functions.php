<?php
 function ConnectDB($host, $user, $password, $db){
  $link = mysqli_connect($host, $user, $password);
  if ($link){
   mysqli_query($link, "SET NAMES 'utf8'");
   if (mysqli_select_db($link, $db)){
	return $link;
   }
   else{
	return false;
   }
  }
  else{
   return false;
  }
 }
 
 function CorrectEmail($email){
  if (preg_match('/[\w-_]+@[a-z]+\.[a-z]{2,3}/', $email)){
   return true;
  }
  else{
   return false;
  }
 }
 
 function CorrectName($name){
  if (preg_match('/[^-_\sa-zA-Zа-яА-ЯёЁ]+/', $name)){
   return false;
  }
  else{
   return true;
  }
 }
 
 function HashPassword($password, $salt){
  return md5($password.$salt); //md5 - устаревший, sha1, sha256 - более современные
  //есть функция hash(имя алгоритма, сообщение для хэширования, [вид данных])
 }
 
 function GenerateSalt(){
  $salt = '';
  for ($i=0; $i<8; $i++){
   $salt .= chr(mt_rand(33, 126));
   }
  return $salt;
 }
 
 function FieldCheck($link, $field, $value, $table){//проверка, есть ли в таблице значение какого-либо поля
  $query = "SELECT * FROM {$table} WHERE {$field}='{$value}'";
  $result = mysqli_fetch_assoc(mysqli_query($link, $query));
  if (!empty($result)){
   return true;
  }
  else{
   return false;
  }
 }
 
 function PasswordCheck($link, $id, $password){//проверка, правильный ли пароль
  $query = "SELECT password, salt FROM users WHERE id='{$id}'";
  $result = mysqli_fetch_assoc(mysqli_query($link, $query));
  if (!empty($result)){
   $salt = $result['salt'];
   $correct_password = $result['password'];
   if ($correct_password == HashPassword($password, $salt)){
    return true;
   }
   else{
    return false;
   }
  }
  else{
   return false;
  }
 }
 
 function FieldUpdate($link, $field, $value, $id, $table){//изменение какого-либо поля таблицы users
  $query = "UPDATE {$table} SET {$field}='{$value}' WHERE id={$id}";
  if (mysqli_query($link, $query)){
   return true;
  }
  else{
   return false;
  }
 }
 
 function PasswordUpdate($link, $id, $password){
  $salt = GenerateSalt();
  $passwordhash = HashPassword($password, $salt);
  $query = "UPDATE users SET salt='{$salt}', password='{$passwordhash}' WHERE id={$id}";
  if (mysqli_query($link, $query)){
   return true;
  }
  else{
   return false;
  }
 }
 
 function ReturnId($link, $email){
  $query = "SELECT id FROM users WHERE email='{$email}'";
  $result = mysqli_fetch_assoc(mysqli_query($link, $query));
  return $result['id'];
 }
 
 function ReturnPassword($link, $email){
  $query = "SELECT password FROM users WHERE email='{$email}'";
  $result = mysqli_fetch_assoc(mysqli_query($link, $query));
  return $result['password'];
 }
 
 function Redirect($url, $delay){//функция перенаправления на другую страницу через JavaScript с задержкой $delay(1000=1 сек)
  echo "<script type=\"text/javascript\">
         setTimeout('location.replace(\"$url\")', $delay);
		</script>"; 
 }
 
 function AllFeedbacks($link){//кроме отзывов заблокированных пользователей
  $query = "SELECT users.name, feedbacks.feedback FROM users, feedbacks
   WHERE users.id=feedbacks.iduser AND users.block=false";
  $result = mysqli_query($link, $query);
  while ($res = mysqli_fetch_assoc($result)){
   $feedbacks[$res['name']] = $res['feedback'];
  }
  if (!empty($feedbacks)){
   return $feedbacks;
  }
  else{
   return false;
  }
 }
 
 function AllUsers($link){
  $query = "SELECT * FROM users";
  $result = mysqli_query($link, $query);
  while ($res = mysqli_fetch_assoc($result)){
   $users[$res['id']] = $res['block'];
  }
  if (!empty($user)){
   return $users;
  }
  else{
   return false;
  }
 }
 
 function FeedbacksAndUsers($link){
  $query = "SELECT iduser, feedback FROM feedbacks";
  $result = mysqli_query($link, $query);
  while ($res = mysqli_fetch_assoc($result)){
   $user[$res['iduser']] = $res['feedback'];
  }
  if (!empty($user)){
   return $user;
  }
  else{
   return false;
  }
 }
 
 function BlockStatus($link, $id){
  $query = "SELECT block FROM users WHERE id={$id}";
  $result = mysqli_fetch_assoc(mysqli_query($link, $query));
  return $result['block'];
 }
 
 function ToBlock($link, $id){
  $query = "UPDATE users SET block=true WHERE id={$id}";
  mysqli_query($link, $query);
 }
 
 function Unlock($link){
  $query = "UPDATE users SET block=false";
  mysqli_query($link, $query);
 }
 
 function DeleteUser($link, $id){
  $query = "DELETE FROM users WHERE id={$id}";
  if (mysqli_query($link, $query)){
   return true;
  }
  else{
   return false;
  }
 }
 
 function DeleteFeedback($link, $id){
  $query = "DELETE FROM feedbacks WHERE iduser={$id}";
  if (mysqli_query($link, $query)){
   return true;
  }
  else{
   return false;
  }
 }
?>