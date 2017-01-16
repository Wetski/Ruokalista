<?php
include('navbar.php');
require_once("/home/K1565/dbconfig/db-init.php");
require_once("/home/K1565/pwconfig/PasswordLib.phar");
$lib = new PasswordLib\PasswordLib();
$output = <<<OUTPUTEND
	<div class="login">
		<div class="heading">
			<h2>Sign in</h2>
			<form method="POST" id="login" action="{$_SERVER['PHP_SELF']}">
				<div class="input-group input-group-lg">
					<span class="input-group-addon"><i class="fa fa-user"></i></span>
					<input type="text" name="username" class="form-control" placeholder="Username">
				</div>
				<div class="input-group input-group-lg">
					<span class="input-group-addon"><i class="fa fa-lock"></i></span>
					<input type="password" name="password" class="form-control" placeholder="Password">	
				</div>
				<input type ="submit" name="Login" value="Login" class="float">
			</form>	
		</div>
		<div class="input-group input-group-lg">
			<a href='empty_form.php' class="span" >Register now!</a>
		</div>
	</div>
OUTPUTEND;
echo $output;
// username ja password muuttujat
$tunnus   = isset($_REQUEST['username']) ? $_REQUEST['username']:'';
$salasana = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';

$sql = "SELECT salasana FROM kayttajat WHERE tunnus = ?";

$stmt = $db->prepare("$sql");
$stmt->execute(array(htmlspecialchars($tunnus)));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// sisään kirjautuminen
if(isset($_POST['Login']))
{	
	if ($stmt->rowCount() == 1 AND $lib->verifyPasswordHash($salasana, $row['salasana'])){	
		$_SESSION['CurrentUser'] = htmlspecialchars($tunnus);  // luo sessio		
		header('Location: index_secure.php');
        exit;			
		}
		else{
		echo "Username/password not found!";	// Väärä käyttäjätunnus
		}	
}	
?>