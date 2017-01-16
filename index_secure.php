<?php
require_once('php/functions.php');
require_once('/home/K1565/dbconfig/db-init.php');
?>
<html>
<head>
	<script src="js/lunch_menu.js"></script>
</head>
<body onload="start()">
<?php include('navbar.php');
if(isset($_SESSION['CurrentUser']) == 0){
	header('Location: index.php');
}
?>
	<section>
	<ul class="col-md-12 lista nav nav-tabs">
		<li role="presentation" class="vkp" id="1">
			<a onclick="navigate(1)" href="#/">Maanantai<br><span id="maanantai"></span></a>
		</li>
		<li role="presentation" class="vkp" id="2">
			<a onclick="navigate(2)" href="#/">Tiistai<br><span id="tiistai"></span></a>
		</li>
		<li role="presentation" class="vkp" id="3">
			<a onclick="navigate(3)" href="#/">Keskiviikko<br><span id="keskiviikko"></span></a>
		</li>
		<li role="presentation" class="vkp" id="4">
			<a onclick="navigate(4)" href="#/">Torstai<br><span id="torstai"></span></a>
		</li>
		<li role="presentation" class="vkp" id="5">
			<a onclick="navigate(5)" href="#/">Perjantai<br><span id="perjantai"></span></a>
		</li>
	</ul>
	<?php
	$sql = <<<SQLEND
	SELECT ravintola FROM kayttaja_asetukset WHERE kayttaja_avain=(SELECT avain FROM kayttajat WHERE tunnus = ?)
SQLEND;
	$stmt = $db->prepare($sql);
	$stmt->execute(array($_SESSION['CurrentUser']));
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$output = <<<OUTPUTEND
		<div class="col-md-4 col-xs-6 col-sm-4">
			<h1>{$row['ravintola']}</h1>
            <img href="#collapse_{$row['ravintola']}" data-toggle="collapse" src="img/{$row['ravintola']}.jpg" height="150" width="300" alt="" /><br>
OUTPUTEND;
			
			echo $output;
			AverageRating($db, $row['ravintola']);
			echo "<br>";
			ArvosteleRavintola($row['ravintola']);
			LueArvostelu($db, $row['ravintola']);
			$output = <<<OUTPUTEND
			<div id="collapse_{$row['ravintola']}" class="collapse">
				<table align="center" id="{$row['ravintola']}">
				</table>
			</div>
		</div>
OUTPUTEND;
			echo $output;
	}
	?>		
	</section>
	<footer class="col-md-12">
		<p><i class="fa fa-copyright fa-1x"></i>2016 Veeti Karttunen, Niki Liuhanen, Aaro Lyytinen</p>
	</footer>
</body>
<script src="js/jquery-3.1.1.js"></script>
<script src="js/bootstrap.js"> </script>
</html>