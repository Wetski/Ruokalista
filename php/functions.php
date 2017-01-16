	<?php
	function ArvosteleRavintola($ravintola)
	{
		$output = <<<OUTPUTEND
		<a class="" data-toggle="collapse" data-parent="#accordion" href="#{$ravintola}-rate">Arvostele!</a><br>
		<div class='panel-collapse collapse' id='{$ravintola}-rate'>
		<form method="POST" action="php/add_rate.php">
			<input type="text" name="nimimerkki" placeholder="Nimimerkki" maxlength="25"><br>
			<textarea name="teksti" rows="4" cols="50" maxlength="1000"> </textarea>
			<fieldset class="rating {$ravintola}r">
				<input type="radio" id="{$ravintola}star5" name='rating' value="5" /><label for="{$ravintola}star5" title="Rocks!"></label>
				<input type="radio" id="{$ravintola}star4" name='rating' value="4" /><label for="{$ravintola}star4" title="Pretty good"></label>
				<input type="radio" id="{$ravintola}star3" name='rating' value="3" /><label for="{$ravintola}star3" title="Meh"></label>
				<input type="radio" id="{$ravintola}star2" name='rating' value="2" /><label for="{$ravintola}star2" title="Kinda bad"></label>
				<input type="radio" id="{$ravintola}star1" name='rating' value="1" /><label for="{$ravintola}star1" title="Sucks big time"></label>
			</fieldset>				
OUTPUTEND;
		echo $output;
		echo "<button type='submit' name='arvostele' value='{$ravintola}'>arvostele!</button></form></div>";
	}
	
	function LueArvostelu($db, $ravintola)
	{
		$luesql = "SELECT * FROM arvostelut WHERE ravintola = ?";
		$luestmt = $db->prepare($luesql);
		$luestmt->execute(array($ravintola));
		echo "<a data-toggle='collapse' href='#{$ravintola}-lue'>Lue arvosteluja</a><div class='panel-collapse collapse' id='{$ravintola}-lue'><table>";
		while($luerow = $luestmt->fetch(PDO::FETCH_ASSOC)) {
			$output = <<<OUTPUTEND
				<tr>
					<td style="font-weight: bold;">{$luerow['nimimerkki']}</td>
				</tr>
				<tr>
					<td>Arvosana: {$luerow['rating']}/5</td>
				</tr>
				<tr>
					<td style="padding-bottom: 10px;">{$luerow['teksti']}</td>
				</tr>
OUTPUTEND;
			echo $output;
		}
		echo "</table></div>";
	}
	
	function LisaaRavintola($db,$id, $name){
		$output = <<<OUTPUTEND
		<div class="col-md-4">
		<h1 href="#collapse_{$id}" data-toggle="collapse">{$name}</h1>
		<img href="#collapse_{$id}" data-toggle="collapse" src="img/{$id}.jpg" height="150" width="300" alt="" /><br>
OUTPUTEND;
		echo $output;
		AverageRating($db, $id);
		echo "<br>";
		LueArvostelu($db, $id);		
		$output = <<<OUTPUTEND
		<div id="collapse_{$id}" class="collapse">
		<table align="center" id="{$id}">
		</table>
		</div>
		</div>
OUTPUTEND;
		echo $output;
	}
	
	function GetMenus() {
		date_default_timezone_set('Europe/Helsinki');
		$checkDate = "menus/aimo/amica_1.json";
		$modifiedDate = date( "Y/m/d", filemtime($checkDate));
		$date = new DateTime();
		$dweek = new DateTime();
		$orig_week = $dweek->format("W");
		$result = $date->format("Y/m/d");
		for($i = 0; $i < 7; $i++) {
			$week = $dweek->format("W");
			if($orig_week != $week) {
				getJSON();
			}
			if($result == $modifiedDate){
				break;
			}
			$date->modify("-1 day");
			$dweek->modify("-1 day");
			$result = $date->format("Y/m/d");
			if($i == 6){
				getJSON();
			}
		}
	}

	function getJSON() {
		$date = new DateTime();
		$date->modify('monday this week');
		$result = $date->format('Y/m/d');
		$urlArray = array();
		$fileArray = array();
		for($i = 0; $i < 5; $i++) {
			$aimo = "http://www.amica.fi/modules/json/json/Index?costNumber=0350&language=fi&firstDay={$result}";
			$bitti = "http://www.sodexo.fi/ruokalistat/output/daily_json/5865/{$result}/fi";
			$poliisi = "http://www.sodexo.fi/ruokalistat/output/daily_json/56/{$result}/fi";
			$fAimo = "menus/aimo/amica_" . $i . ".json";
			$fBitti = "menus/bitti/bitti_" . $i . ".json";
			$fPoliisi = "menus/poliisi/poliisi_" . $i . ".json";
			array_push($fileArray, $fAimo);
			array_push($fileArray, $fBitti);
			array_push($fileArray, $fPoliisi);
			array_push($urlArray, $aimo);
			array_push($urlArray, $bitti);
			array_push($urlArray, $poliisi);
			$date->modify("+1 day");
			$result = $date->format('Y/m/d');
		}
		$urlArray_size = count($urlArray);
		for ($i = 0; $i < $urlArray_size; $i++) {
			$html = file_get_contents($urlArray[$i]);
			file_put_contents($fileArray[$i], $html);
		}
	}
	
	function AverageRating($db, $ravintola) {
		$rsql = "SELECT AVG(rating) as average FROM arvostelut where ravintola='{$ravintola}'";
		$rstmt = $db->query($rsql);
		$rrow = $rstmt->fetchObject();
		$average = $rrow->average;	
		echo round($average,2) . "/5 rating";
	}
	?>
	

  