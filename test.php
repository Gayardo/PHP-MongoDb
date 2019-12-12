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
  			<li><a class="active" href="#home">Application Cloud</a></li>
  			<li><a href="#news">Utilisateur</a></li>
  			<li><a href="#contact">Administrateur</a></li>
		</ul>
	</header>	

<?php

    $manager=new MongoDB\Driver\Manager("mongodb://localhost:27017");

    $command = new MongoDB\Driver\Command([

    	'aggregate' => 'movies',
    	'pipeline'=> [
    		['$unwind' => '$list_genres'],
    		['$group' => ['_id' => '$list_genres', 'moyenne' =>  ['$avg' => '$rank']]],
    		['$sort' => ['moyenne'=> -1]]
    	],
    	'cursor' => new stdClass,

    ]);
    $rows = $manager->executeCommand("maDB",$command);
    
    $i=1;

    echo "<h1> TOP 10 des genres de films qui ont en moyenne la meilleure note</h1>";

    echo "<table id='customers'>
    		<tr>
    			<th>rank</th>
    			<th>Catgorie</th>
    			<th>Note moyenne</th>
    		<tr>";
    		
    foreach ($rows as $row) {
    	if($i<11){


      echo  "<tr>".
      			"<td>".$i."</td>".
    			"<td>".$row->_id."</td>".
    			"<td>".$row->moyenne."</td>".
    		"</tr>";
    	}
    	$i=$i+1;
    }

    echo "</table>";

    $commandTwo = new MongoDB\Driver\Command([

    	'aggregate' => 'directors',
    	'pipeline'=> [
    		['$unwind' => '$list_movie_id'],
    		['$group' => ['_id' => '$id',
    		 'firstname' =>['$first' => '$first_name' ] ,
    		 'lastname' =>['$first' => '$last_name' ] , 
    		 'number' =>  ['$sum' => 1]]],
    		['$sort' => ['number'=> -1]]
    	],
    	'cursor' => new stdClass,

    ]);

    $rowsTwo = $manager->executeCommand("maDB",$commandTwo);

    $i=0;
    echo "<h1> Les realisateurs qui ont produit le plus de films</h1>";

    echo "<table id='customers'>
    	<tr>
    		<th>First Name</th>
    		<th>Last  Name</th> 
    		<th>nombre</th>
    	</tr>";
    		
    foreach ($rowsTwo as $row) {
    	if($i<1){
       echo "<tr>".
    			"<td>".$row->firstname."</td>".
    			"<td>".$row->lastname."</td>".
    			"<td>".$row->number."</td>".	
    		"</tr>";
    	}
    	$i=$i+1;
    }

    echo "</table>";

    $commandThree = new MongoDB\Driver\Command([
    	'count' => 'actors',
    	'query' => ['list_roles' => 'James Bond'],
    ]);
    $result = $manager->executeCommand("maDB",$commandThree);
    echo "<h1> Le nombre de fois que le role de James bond a été joué au cinema </h1> ";
    $res= current($result->toArray());
    $count = $res->n; 

    echo "<h2>".$count."</h2>";

?>
	</body>
</html>