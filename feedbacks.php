<?php
//сделать дату для отзывов, выводить отзывы по дате добавления?
//добавить удаление пользователем отзыва?
//сделать страницы для отзывов?
 include("functions.php");
 $title = 'Отзывы';
 $link = ConnectDB('localhost', 'root', 'root', 'CF');
 if ($link){
  $content = "";
  if(!isset($_SESSION)){
   session_start();
  }
  
  //если пользователь авторизирован
  if (isset($_SESSION['auth']) AND $_SESSION['auth']){
   $id = $_SESSION['id'];
   //обработка отправленного отзыва
   if (isset($_POST['feedback'])){
    $review = htmlentities($_POST['feedback']);
    if (FieldCheck($link, 'iduser', $id, 'feedbacks')){
	 $query = "UPDATE feedbacks SET feedback={$review} WHERE iduser={$id}";
	 if (mysqli_query($link, $query)){
	  $content .= "<b>Ваш отзыв отредактирован!</b><br><br>";
	 }
	 else{
	  $content .= "<b>Ошибка! Отзыв не отредактирован!</b><br><br>";
	 }
    }
    else{
     $query = "INSERT INTO feedbacks (iduser, feedback) VALUE ('{$id}', '{$review}')";
     if (mysqli_query($link, $query)){
	  $content .= "<b>Ваш отзыв добавлен!</b><br><br>";
     }
     else{
	  $content .= "<b>Ошибка! Попробуйте еще раз.</b><br><br>";
     }
    }
   }
   
   //вывод формы для отзыва
   $content .= "
	 <form method='POST'>
	  <textarea name='feedback'>";
	if (FieldCheck($link, 'iduser', $id, 'feedbacks')){
	 $query = "SELECT feedback FROM feedbacks WHERE iduser={$id}";
	 $result = mysqli_fetch_assoc(mysqli_query($link, $query));
	 $content .= $result['feedback'];
	 $namebutton = "Редактировать отзыв";
	}
	else{
	 $namebutton = "Отправить отзыв";
	}
	$content .= "</textarea>
	  <br>
	  <input type='submit' value='{$namebutton}'>
	 </form>";
  }
  
  //вывод всех отзывов
  $feedbacks = AllFeedbacks($link);
  if ($feedbacks){
   foreach ($feedbacks as $nm=>$fdbck){
    $content .= "<br>{$nm}<br>{$fdbck}";
   }
  }
 }
 else{
  $content = "<br><br><b>Неизвестная ошибка!</b>";
 }
 
 include('shablon.php');
?>