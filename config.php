<!doctype html>

<?php
if (isset($_POST['host']) && isset($_POST['dbname']) && isset($_POST['driver']) && isset($_POST['user']) && isset($_POST['password'])) {
	$dbParams = array(
    'host' => $_POST['host'],
    'dbname' => $_POST['dbname'],
    'driver' => $_POST['driver'],
    'user' => $_POST['user'],
    'password' => $_POST['password'],
	'charset'  => 'utf8',
	'driverOptions' => array(
		1002 => 'SET NAMES utf8'
		),
	);
	file_put_contents('config/dbparams.config', serialize($dbParams));
	$conn = mysqli_connect($dbParams['host'], $dbParams['user'], $dbParams['password'], $dbParams['dbname']);
	$message = $conn ? "Connexió OK" : "Error de connexió";
}
$dbParams = unserialize(file_get_contents('config/dbparams.config'));

?>
<html lang="ca">
<head>
  <meta charset="utf-8">

  <title>Configuration</title>
  <meta name="description" content="tfg Configuration">
  <meta name="author" content="carles" >
</head>

<body>
	<h2>Data base connection parametres</h2>
	<form method="POST">
		<div>
			<label for="host">Host: </label>
			<input type="text" name="host" value="<?php echo $dbParams['host']?>" />
		</div>
		<div>
			<label for="dbname">DB name: </label>
			<input type="text" name="dbname" value="<?php echo $dbParams['dbname']?>" />
		</div>
		<div>
			<label for="driver">Driver: </label>
			<input type="text" name="driver" value="<?php echo $dbParams['driver']?>" />
		</div>
		<div>
			<label for="user">Usuari: </label>
			<input type="text" name="user" value="<?php echo $dbParams['user']?>" />
		</div>
		<div>
			<label for="password">Contrasenya: </label>
			<input type="password" name="password" value="<?php echo $dbParams['password']?>" />
		</div>
		<button>Submit</button>
	</form>
	<p><?php echo $message ?>
	</p>
  <script src="js/scripts.js"></script>
</body>
</html>