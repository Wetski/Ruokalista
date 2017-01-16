<!DOCTYPE html>
<html>

<?php include('navbar.php'); ?>

<div class="login">
  <div class="heading">
	<h2>Register</h2>
	<form method="post" action="php/add_user.php">
		<div class="input-group input-group-lg">
			<span class="input-group-addon"><i class="fa fa-user"></i></span>
			<input type="text" name="tunnus" class="form-control" placeholder="Choose Your Username">			
        </div>
		
        <div class="input-group input-group-lg">
			<span class="input-group-addon"><i class="fa fa-lock"></i></span>
			<input type="password" name="salasana" class="form-control" placeholder="Make up Password">	
        </div>
			<input type ="submit" name="action" value="send" class="float">
		</form>
 </div>
</html>	