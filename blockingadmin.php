<?php
//сделать проверку, если пользователь случайно попал на страницу, чтобы у него не было доступа
 include ("functions.php");
 $title = 'Управление блокировкой пользователей';
 $link = ConnectDB('localhost', 'root', 'root', 'CF');
 if ($link){
  if (isset($_POST['click'])){
   Unlock($link);
   if (isset($_POST['block'])){
	$arrblock = $_POST['block'];
	foreach ($arrblock as $iduser){
	 ToBlock($link, $iduser);
	}
   }
  }
  
  $content = "<table>
   <tr>
    <th>ID пользователя</th>
	<th>Статус</th>
   </tr>
   <tr>";
  $users = AllUsers($link);
  foreach ($users as $id=>$status){
   $content .= "<td>{$id}</td>
    <td><label>
	 <input type='checkbox' name='block[]' form='formblock' value='{$id}'";
   if ($status){
	$content .= " checked>Заблокирован";
   }
   else{
	$content .= ">Не заблокирован";
   }
   $content .= "</label>
     </td>
	</tr>";
  }
  $content .= "</table>
   <form method='POST' id='formblock'>
    <input type='submit' value='Изменить статус(-ы)' name='click'>
   </form>
   ";
 }
 else{
  $content = "<b>Ошибка подключения к базе данных!</b>";
 }
 include('shablon.php');
?>