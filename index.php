<html>
	<head>
		<script src="js/lunch_menu.js"></script>
	</head>
<body onload="start()">
	<?php 
	require_once('php/functions.php');
	require_once('/home/K1565/dbconfig/db-init.php');
	GetMenus();
	include('navbar.php');
	if(isset($_SESSION['CurrentUser'])) header('Location: index_secure.php');
	?>	   
	<section>
	<ul class="col-md-12 lista nav nav-tabs">
		<li role="presentation" class="vkp" id="1">
			<a onclick="navigate(1)" href="#">Maanantai<br><span id="maanantai"></span></a>
		</li>
		<li role="presentation" class="vkp" id="2">
			<a onclick="navigate(2)" href="#">Tiistai<br><span id="tiistai"></span></a>
		</li>
		<li role="presentation" class="vkp" id="3">
			<a onclick="navigate(3)" href="#">Keskiviikko<br><span id="keskiviikko"></span></a>
		</li>
		<li role="presentation" class="vkp" id="4">
			<a onclick="navigate(4)" href="#">Torstai<br><span id="torstai"></span></a>
		</li>
		<li role="presentation" class="vkp" id="5">
			<a onclick="navigate(5)" href="#">Perjantai<br><span id="perjantai"></span></a>
		</li>
	</ul>
		<?php 
		LisaaRavintola($db, 'aimo', 'Aimo');
		LisaaRavintola($db, 'bitti','Bittipannu');
		LisaaRavintola($db, 'poliisi','Poliisilaitos');
			?>
	</section>
	<footer class="col-md-12">
		<p><i class="fa fa-copyright fa-1x"></i>Veeti Karttunen, Niki Liuhanen, Aaro Lyytinen</p>
	</footer>
</body>
<script src="js/jquery-3.1.1.js"></script>
<script src="js/bootstrap.js"> </script>
</html>