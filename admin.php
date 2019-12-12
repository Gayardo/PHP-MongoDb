<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
        <link rel="stylesheet" href="style.css" />
        <title>Application Cloud</title>
	</head>
	<body>
	<header>
		
        <ul>
  			<li><a class="active" href="admin.php">Application Cloud</a></li>
  			<li><a href="user.php">Utilisateur</a></li>
  			<li><a href="admin.php">Administrateur</a></li>
		</ul>
	</header>
<?php

    $manager=new MongoDB\Driver\Manager("mongodb://devincimdb2010.westeurope.cloudapp.azure.com:30000");



    $command = new MongoDB\Driver\Command(['listDatabases' => 1]);
    $rows = $manager->executeCommand("admin",$command);

    $response = $rows->toArray()[0];
 

    $memRS1= round($response->databases[2]->shards->RS1/1000000, 2);
    $memRS2= round($response->databases[2]->shards->RS2/1000000, 2);
    $memRS3= round($response->databases[2]->shards->RS3/1000000, 2);
    $memRS4= round($response->databases[2]->shards->RS4/1000000, 2);
    $memRS5= round($response->databases[2]->shards->RS5/1000000, 2);
    $memRS6= round($response->databases[2]->shards->RS6/1000000, 2);

    //echo "<h1> </h1>";
    echo "<h1> Répartition des données sur les différents shards </h1>";

    echo "<table id='customers'>
    		<tr>
    			<th>Numéro de shard</th>
    			<th>Données en octets </th>
    			<th>Nombre de replicats sets</th>
    		<tr>";

      echo  "<tr>".
      			"<td> RS1 </td>".
    			"<td>".$memRS1." mo</td>".
    			"<td>1</td>".
    		"</tr>".
    		"<tr>".
      			"<td>RS2</td>".
    			"<td>".$memRS2." mo</td>".
    			"<td>1</td>".
    		"</tr>".
    		"<tr>".
      			"<td>RS3</td>".
    			"<td>".$memRS3." mo</td>".
    			"<td>1</td>".
    		"</tr>".
    		"<tr>".
      			"<td>RS4</td>".
    			"<td>".$memRS4." mo</td>".
    			"<td>1</td>".
    		"</tr>".
    		"<tr>".
      			"<td>RS5</td>".
    			"<td>".$memRS5." mo</td>".
    			"<td>1</td>".
    		"</tr>".
    		"<tr>".
      			"<td>RS6</td>".
    			"<td>".$memRS6." mo</td>".
    			"<td>1</td>".
    		"</tr>";

    echo "</table>";
    		
    echo "<h1>Statistiques sur la base de données </h1>";

    $commandActors = new MongoDB\Driver\Command([
    	'count' => 'actors',
    ]);
    $result = $manager->executeCommand("sharddemo",$commandActors);
    $res= current($result->toArray());
    $count = $res->n; 

    $commandMovies = new MongoDB\Driver\Command([
    	'count' => 'movies',
    ]);
    $result2 = $manager->executeCommand("sharddemo",$commandMovies);
    $res2= current($result2->toArray());
    $count2 = $res2->n; 

    $commandDirectors = new MongoDB\Driver\Command([
    	'count' => 'directors',
    ]);
    $result3 = $manager->executeCommand("sharddemo",$commandDirectors);
    $res3= current($result3->toArray());
    $count3 = $res3->n; 



    $total=$memRS1+$memRS2+$memRS3+$memRS4+$memRS5+$memRS6;
    $totalSecond=round($total,2);
    $moyenne=$total/6;
    $moyenne=round($moyenne,2);

echo "<table id='customers'>
			<tr>
    			<th>Taille totale de la base</th>
    			<td>".$total." mo</th>
    		<tr>
    		<tr>
    			<th>Nombre de shards</th>
    			<td>6</th>
    		<tr>
    		<tr>
    			<th>Données par shard</th>
    			<td>".$moyenne." mo/shard</th>
    		<tr>";

      echo  "<tr>".
      			"<th> Nombre d'acteurs  </th>".
    			"<td>".$count."</td>".
    		"</tr>".
    		"<tr>".
      			"<th>Nombre de films</th>".
    			"<td>".$count2."</td>".
    		"</tr>".
    		"<tr>".
      			"<th>Nombre de realisateurs</th>".
    			"<td>".$count3."</td>".
    		"</tr>".
    		"<tr>".
      			"<th>Nombre de genres de films</th>".
    			"<td> 21 </td>".
    		"</tr>
    		<tr>".
      			"<th>Nombre de films par réalisateur</th>".
    			"<td>4,4</td>".
    		"</tr>".
    		"<tr>".
      			"<th>Nombre d'acteurs par film</th>".
    			"<td>8,9</td>".
    		"</tr>";

    echo "</table>";


?>
	</body>
</html>