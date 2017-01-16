<?php
session_start();
require_once('/home/K1565/dbconfig/db-init.php');

$arvostelu = isset($_POST['rating']) ? $_POST['rating']:NULL;
$teksti = isset($_POST['teksti']) ? $_POST['teksti']:'';
$nimimerkki = isset($_POST['nimimerkki']) ? $_POST['nimimerkki']:NULL;
$ravintola =  isset($_POST['arvostele'])  ? $_POST['arvostele']:NULL;

if ($nimimerkki == NULL)
{
	$nimimerkki = $_SESSION['CurrentUser'];
}
 
$sql = <<<SQLEND
INSERT INTO arvostelut (nimimerkki, teksti, rating, ravintola)
VALUES (:f1, :f2, :f3, :f4)
SQLEND;

echo '<script language="javascript">';
echo 'alert("Annoit ravintolalle " $rating " tähteä")';
echo '</script>';

$stmt = $db->prepare($sql);
$stmt->execute(array(
':f1' => htmlspecialchars($nimimerkki),
':f2' => htmlspecialchars($teksti),
':f3' => $arvostelu,
':f4' => $ravintola));
header('Location: ../success_rating.php');

?>