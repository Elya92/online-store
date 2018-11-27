<?php
//сделать проверку, если пользователь случайно попал на страницу, чтобы у него не было доступа
 include ("functions.php");
 $title = 'Управление отзывами';
 $link = ConnectDB('localhost', 'root', 'root', 'CF');
 if ($link){
  $content = "";
  if(isset($_POST['clickdel'])){
	  echo '1';
   if (isset($_POST['del'])){
	   echo '2';
    $arrdelete = $_POST['del'];
    foreach ($arrdelete as $iduser){
		echo '3';
	 $res = DeleteFeedback($link, $iduser);
    }
   }
  }
  
  if(isset($_POST['clickblock'])){
   Unlock($link);
   if (isset($_POST['block'])){
    $arrblock = $_POST['block'];
    foreach ($arrblock as $iduser){
	 ToBlock($link, $iduser);
    }
   }
  }
  
  $feedbacks = FeedbacksAndUsers($link);

  $content .= "<form id='formdelete'>
   </form>
   <table>
    <tr>
     <th>Отзыв</th>
	 <th>Удаление отзыва</th>
	 <th>ID пользователя</th>
	 <th>Статус пользователя</th>
    </tr>";
  if ($feedbacks){
   foreach ($feedbacks as $id=>$feedback){
    $content .= "<tr>
     <td>{$feedback}</td>
     <td>
	  <label>
	   <input type='checkbox' name='del[]' form='formdel' value='{$id}'>
	   Удалить
	  </label>
	 </td>
     <td>{$id}</td><td>
      <label>
	   <input type='checkbox' name='block[]' form='formblock' value='{$id}'";
    if (BlockStatus($link, $id)){
	 $content .= " checked>Заблокирован";
    }
    else{
	 $content .= ">Не заблокирован";
    }
    $content .= "</label></td></tr>";
   }
  }
  $content .= "</table>
   <form id='formdel' method='POST'>
    <input type='submit' value='Удалить отмеченный(-е) отзыв(-ы)' name='clickdel'>
   </form>
   <form id='formblock' method='POST'>
    <input type='submit' value='Изменить статус(-ы)' name='clickblock'>
   </form>
   ";
 }
 else{
  $content = "<b>Ошибка подключения к базе данных!</b>";
 }
 include('shablon.php');
?>