<?php
require_once('/home/K1565/dbconfig/db-init.php');
require_once("/home/K1565/pwconfig/PasswordLib.phar");
$lib = new PasswordLib\PasswordLib();

$tunnus   = isset($_REQUEST['tunnus'])   ? $_REQUEST['tunnus']   : '';
$salasana = isset($_REQUEST['salasana']) ? $_REQUEST['salasana'] : '';
$sql = "SELECT tunnus FROM kayttajat WHERE tunnus =?";
$stmt = $db->prepare("$sql");
$stmt->execute(array($tunnus));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$hash = $lib->createPasswordHash($salasana,  '$2a$', array('cost' => 12));
 
$sql = <<<SQLEND
INSERT INTO kayttajat(tunnus, salasana)
VALUES (:f1, :f2)
SQLEND;
if(preg_match("/.+/",$tunnus) AND preg_match("/.+/",$salasana)){
	if ($row != 0 ){
		echo '<script language="javascript">';
		echo 'alert("Username taken, choose another one.")';
		echo '</script>';
		echo "<script>setTimeout(\"location.href ='../empty_form.php'; \",100);</script>";
	}
	else{ 
		echo '<script language="javascript">';
		echo 'alert("Account succesfully created.")';
		echo '</script>';
		$stmt = $db->prepare($sql);
		$stmt->execute(array(
		':f1' => htmlspecialchars($tunnus), 
		':f2' => $hash));
		echo $stmt->rowCount() . "Succesfully created!";
		header('Location: ../success.php');
	}
}
else {
	echo '<script language="javascript">';
	echo 'alert("Username/password needs to be at least 1 character long.")';
	echo '</script>';
	echo "<script>setTimeout(\"location.href ='../empty_form.php'; \",100);</script>";
}


?>