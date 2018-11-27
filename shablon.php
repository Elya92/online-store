<!DOCTYPE html>
<html lang='ru'>
 <head>
  <meta charset="utf-8">
  <title>
   <?php
    echo $title;
   ?>
  </title>
 </head>
 
 <body>
  <header>
   <a href='main.php'>
    <img src='images/Logo-big.png' alt='Главная страница' title='Главная страница'>
   </a>
   <form action='catalog.php'>
    <input type='search' name='search' placeholder='Поиск по сайту'>
	<input type='submit' value='Искать'>
   </form>
   <?php
    if(!isset($_SESSION)){
     session_start();
	}
    if (empty($_SESSION['auth']) OR !$_SESSION['auth']){
	 if (!empty($_COOKIE['id']) AND !empty($_COOKIE['key'])){
	  $id = $_COOKIE['id'];
	  $key = $_COOKIE['key'];
	  include ("functions.php");
	  $link = ConnectDB('localhost', 'root', 'root', 'CF');
	  if ($link){
	   $query = "SELECT * FROM users WHERE id='{$id}' AND cookie='{$key}'";
	   $result = mysqli_fetch_assoc(mysqli_query($link, $query));
	   if (!empty($result)){
		session_start();
		$_SESSION['auth'] = true;
		$_SESSION['id'] = $id;
	   }
	  }
	 }
	}
	if (isset($_SESSION['auth']) AND $_SESSION['auth']){
	 $id = $_SESSION['id'];
     echo "<a href='cabinet.php?id={$id}'>Личный&nbsp;кабинет</a>/<a href='logout.php'>Выйти</a>";
    }
    else{
	 echo "<a href='registraition.php'>Регистрация</a> ";
	 echo "<a href='autorisation.php'>Войти</a>";
    }
   ?>
   <a href='basket.php'>Корзина</a>
  </header>
  
  <!--Ссылки на категории-->
  <nav>
   <ul>
    <li>
	 <a href='catalog.php?g=women'>Женщинам</a>
	 <ul>
	  <li>
	   <a href='catalog.php?g=women&t=clothes'>Одежда</a>
	  </li>
	  <li>
	   <a href='catalog.php?g=women&t=footwear'>Обувь</a>
	  </li>
	 </ul>
	</li>
    <li>
	 <a href='catalog.php?g=men'>Мужчинам</a>
	 <ul>
	  <li>
	   <a href='catalog.php?g=men&t=clothes'>Одежда</a>
	  </li>
	  <li>
	   <a href='catalog.php?g=men&t=footwear'>Обувь</a>
	  </li>
	 </ul>
	</li>
    <li>
	 <a href='catalog.php?g=children'>Детям</a>
	 <ul>
	  <li>
	   <a href='catalog.php?g=children&t=clothes'>Одежда</a>
	  </li>
	  <li>
	   <a href='catalog.php?g=children&t=footwear'>Обувь</a>
	  </li>
	 </ul>
	</li>
	<li>
	 <a href='sale.php'>Распродажа</a>
	</li>
   </ul>
  </nav>
   
  <main>
   <?php echo $content;?>
  </main>
  
  <footer>
   <a href='payment-delivery.php'>Оплата&nbsp;и&nbsp;доставка</a>
   <a href='about_us.php'>О&nbsp;нас</a>
   <a href='feedbacks.php'>Отзывы</a>
   <div>Телефон: <a href='tel:+375291234567'>+375(29)123-45-67</a></div>
   <div>
    <time datetime='2017'>2017</time>&nbsp;-&nbsp;
    <time datetime='<?php echo date("Y");?>'><?php echo date("Y");?></time>,
    Clothes&nbsp;and&nbsp;Footwear&reg; - интернет-магазин одежды и обуви.
   </div>
  </footer>
 </body>
</html>