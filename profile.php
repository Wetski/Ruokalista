<?php
require_once("/home/K1565/dbconfig/db-init.php");
require_once("/home/K1565/pwconfig/PasswordLib.phar");
$lib = new PasswordLib\PasswordLib();
include('navbar.php');
if (isset($_SESSION['CurrentUser']) == FALSE ) {header("Location: index.php");}

?>
<!DOCTYPE html>
<html>
	<title>Profile</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/font-awesome.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="icon" type="image/png" href="img/icon.png">

<body>
<?php
	$array = array('aimo','bitti','poliisi');
	$salasana = isset($_POST['password']) ? $_POST['password'] : '';
	$uusisalasana = isset($_POST['newpassword']) ? $_POST['newpassword'] : '';
	$hash = $lib->createPasswordHash($salasana,  '$2a$', array('cost' => 12));
	$newhash = $lib->createPasswordHash($uusisalasana,  '$2a$', array('cost' => 12));
	$sql = "SELECT salasana FROM kayttajat WHERE tunnus=?";
	$stmt = $db->prepare($sql);
	$stmt->execute(array($_SESSION['CurrentUser']));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	
	if(isset($_POST['nappi']))
	{	
		if ($stmt->rowCount() == 1 AND $lib->verifyPasswordHash($_POST['password'], $row['salasana'])){	
			$sql = <<<SQLEND
			UPDATE kayttajat 		
			SET salasana=:f1
			WHERE tunnus = :f2
SQLEND;

			$stmt = $db->prepare($sql);
			$stmt->execute(array(
			':f1' => $newhash,
			':f2' => $_SESSION['CurrentUser']));					
			header('Location: success.php');
		}
		else{
			echo "Wrong password";
		}	
	}	
	if(isset($_POST['poista']))
	{
		$sql = <<<SQLEND
		DELETE FROM kayttaja_asetukset
		WHERE kayttaja_avain=(SELECT avain from kayttajat WHERE tunnus=?)
SQLEND;
		$stmt = $db->prepare($sql);
		$stmt->execute(array($_SESSION['CurrentUser']));
		$sql = <<<SQLEND
		DELETE FROM kayttajat
		WHERE tunnus=?
SQLEND;
		$stmt = $db->prepare($sql);
		$stmt->execute(array($_SESSION['CurrentUser']));
		session_destroy();
		header('Location: success.php');
	}
	if(isset($_POST['add'])) {
		$sql = <<<SQLEND
		INSERT INTO kayttaja_asetukset(kayttaja_avain, ravintola)
		VALUES((select avain from kayttajat where tunnus = :f1), :f2)
SQLEND;
		foreach($_POST['ravintolat'] as $arvo){
			$stmt = $db->prepare($sql);
			$stmt->execute(array(
			':f1' => $_SESSION['CurrentUser'],
			':f2' => $arvo));
		}
	}
	if(isset($_POST['remove'])) {
		$sql= <<<SQLEND
		delete from kayttaja_asetukset where ravintola=:f1 AND kayttaja_avain=(select avain from kayttajat where tunnus=:f2)
SQLEND;
		foreach($_POST['valitut'] as $arvo){
		$stmt = $db->prepare($sql);
			$stmt->execute(array(
			':f1' => $arvo,
			':f2' => $_SESSION['CurrentUser']));
		}
	}
?>
<div class="col-md-12">
	<div class="col-md-4 col-md-offset-1" style="text-align:center;">
		<form centered action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
			<table>
				<tr>
					<td>Username:</td>
					<td><?php echo $_SESSION['CurrentUser']; ?></td>
				</tr>
				<tr>
					<td>Password:</td>
					<td><input type="password" name="password"></td>
				</tr>
				<tr>
					<td>New password:</td>
					<td><input type="password" name="newpassword"></td>
				</tr>
				<tr>
					<td><input type="submit" value="Tallenna" name="nappi"></td>
				</tr>
				<tr>
					<td><input type="submit" value="Poista tunnuksesi" name="poista"></td>
				</tr>
			</table>
		</form>
	</div>

	<div class="col-md-6">
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
		<div class="col-md-4" style="padding:0px;">
			<select name="ravintolat[]" size="20" style="width:200px;" multiple>
				<?php
				$sql = <<<SQLEND
				select * from kayttaja_asetukset where ravintola=:f1 AND kayttaja_avain=(select avain from kayttajat where tunnus=:f2)
SQLEND;
				
				
				for ($i = 0; $i < count($array); $i++){
					$stmt = $db->prepare($sql);
					$stmt->execute(array(
					':f1' => $array[$i],
					':f2' => $_SESSION['CurrentUser']));
					$row = $stmt->fetch(PDO::FETCH_ASSOC);
					if($row == 0) {echo "<option value='{$array[$i]}'>{$array[$i]}</option>";}
					
				}
				?>
			</select>
			</div>
			<div style="height:335px;padding-top:135px; padding-left:30px;" class="col-md-1">
				<input type="submit" value=">" name="add"><br>
				<input type="submit" value="<" name="remove">
			</div>
			<div class="col-md-4">
			<select name="valitut[]" size="20"  style="width:200px;" multiple>
				<?php
				$sql = <<<SQLEND
				select * from kayttaja_asetukset where kayttaja_avain=(select avain from kayttajat where tunnus=?)
SQLEND;
				$stmt = $db->prepare($sql);
				$stmt->execute(array($_SESSION['CurrentUser']));
				
				while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					$output = <<<OUTPUTEND
					<option value"{$row['ravintola']}">{$row['ravintola']}</option>
OUTPUTEND;
					echo $output;
				}
				?>
			</select>
			</div>
		</form>
	</div>
</div>

</body>
<script src="js/jquery-3.1.1.js"></script>
<script src="js/bootstrap.js"> </script>
<script>
function navigateProfile (event, index) {
	var tabcontent, tablinks;
	var inactive = document.getElementById(1);
	inactive.setAttribute("class","");
	var inactive = document.getElementById(2);
	inactive.setAttribute("class","");
	 var active = document.getElementById(index);
	 active.setAttribute("class", "active");
	 
	 tabcontent = document.getElementByClassName("tabcontent");
	 for(var i = 0; i< tabcontent.length; i++) {
		 tabcontent[i].style.display = "none";
	 }
	 document.getElementById(index).style.display = "block";
	 evt.currentTarget.className += "active";
}
</script>
</html>
