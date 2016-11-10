<?php
namespace Infojor;

// show_teacher.php <id>
require_once "bootstrap.php";
require_once 'service/entities/Teacher.php';

$id = 1;
?>

<!doctype html>

<html lang="ca">
<head>
  <meta charset="utf-8">

  <title>Show teacher</title>
  <meta name="description" content="Show teacher">
  <meta name="author" content="carles" >

  <link rel="stylesheet" href="css/styles.css?v=1.0">
</head>

<body>
<?php
$teacher = $entityManager->find('Infojor\\Service\\Entities\\Teacher', $id);

if ($teacher === null) {
    echo "No teacher found.\n";
    exit(1);
}
?>
	<h2>User data for id=<?php echo $id?></h2>
	<p>Name: <?php echo $teacher->getCompleteName()?></p>
	<p>Email: <?php echo $teacher->getEmail()?></p>
	<p>Phone: <?php echo $teacher->getPhone()?></p>
	<p>Username: <?php echo $teacher->getUsername()?></p>
	<p>Password: <?php echo $teacher->getPassword()?></p>
	<p>Classrooms: <?php
$classrooms = $teacher->getClassrooms();
while ($classrooms->current()) {
	var_dump($classrooms->current()->getName());
	$classrooms->next(); 
}
?></p>
	<p>Areas: <?php
$areas = $teacher->getAreas();
while ($areas->current()) {
	var_dump($areas->current()->getName());
	$areas->next(); 
}
?></p>
	<p>Is admin: <?php echo $teacher->isAdmin()?></p>
  <script src="js/scripts.js"></script>
</body>
</html>