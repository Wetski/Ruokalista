<?php session_start(); ?>
<title>LounasMenu</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="icon" type="image/png" href="img/icon.png">
<?php
if(isset($_SESSION['CurrentUser'])){
	$output = <<<OUTPUTEND
		<header>  
			<nav class="navbar navbar-custom col-md-6" role="navigation">
				<ul class="lista nav nav-pills"> 
					<li class="navbar-text pull-left welcome">
					Welcome  {$_SESSION['CurrentUser']}	</li>	
					<li><a href="./index_secure.php"> <i class="fa fa-home fa-1x" > </i> Home </a></li>
					<li><a href="./profile.php"> <i class="fa fa-user fa-1x"> </i> Profile</a></li>
					<li><a href="docs/dokumentaatio.html"> <i class="fa fa-file-text-o"> </i> </i> Documentation</a></li> 
					<li><a href="./php/logout.php"> <i class="fa fa-sign-out fa-1x"> </i> </i> Log Out</a></li> 
				</ul>  
            </nav>
		</header>
		<section>
			<img class="col md-12 header-image" src="./img/banneri.jpg" alt=""/>
		</section>
OUTPUTEND;
	echo $output;
}
else{
	$output = <<<OUTPUTEND
		<header>  
			<nav class="navbar navbar-custom col-md-6" role="navigation">
				<ul class="lista nav nav-pills">  
					<li class="navbar-text pull-left welcome"></li>
					<li><a href="./index.php"> <i class="fa fa-home fa-1x" > </i> Home </a></li>
					<li><a href="./login.php"> <i class="fa fa-sign-in fa-1x"> </i> Log in</a></li>
					<li><a href="docs/dokumentaatio.html"> <i class="fa fa-file-text-o"> </i> </i> Documentation</a></li> 
				</ul>  
            </nav>
		</header>
		<section>
			<img class="col md-12 header-image" src="./img/banneri.jpg" alt=""/>
		</section>
OUTPUTEND;
	echo $output;
}	
?>


