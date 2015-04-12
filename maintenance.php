<?php

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
	case 'clear':
		

}


?>


<!doctype html>
<html>
<head>
<title>Site Maintenance</title>
<style>
body { text-align: center; padding: 150px; }
h1 { font-size: 50px; }
body { font: 20px Helvetica, sans-serif; color: #333; }
article { display: block; text-align: left; width: 650px; margin: 0 auto; }
a { color: #dc8100; text-decoration: none; }
a:hover { color: #333; text-decoration: none; }
</style>
</head>
<body> 
<h1>Maintenance!</h1>
<div>
<p><?php echo htmlentities($message) ?></p>
<hr />
<p>&mdash; The Team</p>
<a href="?controller=user&view=login">Admin</a>
</div>
</body>
</html>