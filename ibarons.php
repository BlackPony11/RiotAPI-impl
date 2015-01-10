<html>
<?php	$bdd = mysql_connect('mysql51-110.perso', 'easyelobsql', '2BNG5tqTx6UH'); 
	    mysql_select_db('easyelobsql', $bdd); 
      require 'riotapi.class.php';
      include 'functions.php';
      $api = new RiotApi('euw');
      $api->setDebug(false);
?>
<head>
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
  	<link href="style.css" type="text/css" media="all" rel="stylesheet" />
</head>
<body>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
</body>
</html>