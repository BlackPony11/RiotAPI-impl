<?php
require 'riotapi.class.php';
include 'functions.php';

	$api = new RiotApi('euw');
//	$api->setDebug(true);
	
//	$id = $api->getIdFromName('tsleechypriote');
  $id = '20002219';
//	$res = $api->getTeams($id);
	echo $id;
//	print_r($res);
?>
		<?php	$bdd = mysql_connect('mysql51-110.perso', 'easyelobsql', '2BNG5tqTx6UH'); 
			mysql_select_db('easyelobsql', $bdd); ?>
<html>
	<body>
	  <?php $infos = $api->getSummoner($id);
		echo $infos->name . "<br />"; ?>
	  
<?php   $history = $api->getLastGames($id);
 //   var_dump($history);
	      $i = 0;
	      $over = false;
	      while ($i != 10 && !$over) {
			if ($history[$i]->stats->win) {$victory = true;} else {$victory = 0;}
			$requete='INSERT INTO lol_database(MatchId, Player, Type, Date, Duree, Champion, ChampionId, Victoire) 
						VALUES(\'' . $history[$i]->gameId .'\', \''
									.$id. '\', \''
									.$history[$i]->subType. '\', \''
									.$history[$i]->createDate. '\', \''
									.$history[$i]->stats->timePlayed. '\', \''
									.$api->getChampion($history[$i]->championId)->name. '\', \''
									.$history[$i]->championId. '\', \''
									.$victory. '\')';
	  	mysql_query($requete);
    	echo $requete . "<br />";
    	
			$requete = 'INSERT INTO lol_stats(MatchId, Level, Kills, Deaths, Assists, Creeps, Gold, Item0, Item1, Item2, Item3, Item4, Item5, Item6, GreenWards, PinkWards, WardsPlaced, WardsDestroyed)
			      VALUES(\'' . $history[$i]->gameId .'\', \''
									.$history[$i]->stats->level. '\', \''
									.$history[$i]->stats->championsKilled. '\', \''
									.$history[$i]->stats->numDeaths. '\', \''
									.$history[$i]->stats->assists. '\', \''
									.$history[$i]->stats->minionsKilled. '\', \''
									.$history[$i]->stats->goldEarned. '\', \''
									.$history[$i]->stats->item0. '\', \''
									.$history[$i]->stats->item1. '\', \''
									.$history[$i]->stats->item2. '\', \''
									.$history[$i]->stats->item3. '\', \''
									.$history[$i]->stats->item4. '\', \''
									.$history[$i]->stats->item5. '\', \''
									.$history[$i]->stats->item6. '\', \''
									.$history[$i]->stats->sightWardsBought. '\', \''
									.$history[$i]->stats->visionWardsBought. '\', \''
									.$history[$i]->stats->wardPlaced. '\', \''
									.$history[$i]->stats->wardKilled. '\')';
			echo $requete . "<br />";
	  	mysql_query($requete);
	  	
    	$team = getTeam($history[$i]);
    				$requete='INSERT INTO lol_team(MatchId, Self, Player1, Player2, Player3, Player4, SelfChamp, Champ1, Champ2, Champ3, Champ4) 
						VALUES(\'' . $history[$i]->gameId .'\', \''
									.$id. '\', \''
									.$team[0]->summonerId. '\', \''
									.$team[1]->summonerId. '\', \''
									.$team[2]->summonerId. '\', \''
									.$team[3]->summonerId. '\', \''
									.$history[$i]->championId. '\', \''
									.$team[0]->championId. '\', \''
									.$team[1]->championId. '\', \''
									.$team[2]->championId. '\', \''
									.$team[3]->championId.  '\')';
	  	mysql_query($requete);
    	echo $requete . "<br />";
    	
    	$opponent = getOpponent($history[$i]);
			$requete='INSERT INTO lol_opponent(MatchId, Player0, Player1, Player2, Player3, Player4, Champ0, Champ1, Champ2, Champ3, Champ4) 
						VALUES(\'' . $history[$i]->gameId .'\', \''
									.$opponent[0]->summonerId. '\', \''
									.$opponent[1]->summonerId. '\', \''
									.$opponent[2]->summonerId. '\', \''
									.$opponent[3]->summonerId. '\', \''
									.$opponent[4]->summonerId. '\', \''
									.$opponent[0]->championId. '\', \''
									.$opponent[1]->championId. '\', \''
									.$opponent[2]->championId. '\', \''
									.$opponent[3]->championId. '\', \''
									.$opponent[4]->championId.  '\')';
	  	mysql_query($requete);
    	echo $requete . "<br /><br />";
    	
			$i++;
	      }
	  ?>
	  
	 <!--
<?php /* $i = 0;
		while (!empty($res[$i])) { ?>
		<table>
		  <tr>
		    <td><?php echo $res[$i]->name; ?></td>
				<td><?php echo $res[$i]->tag; ?></td>
			<?php if ($res[$i]->teamStatDetails[0]->teamStatType == "RANKED_TEAM_3x3") {$v = 1;} else {$v = 0;} ?>
				<td><?php echo $res[$i]->teamStatDetails[$v]->wins; ?></td>
				<td><?php echo $res[$i]->teamStatDetails[$v]->losses; ?></td>
		  </tr>
				<?php 
				  $j = 0;
				  while (!empty($res[$i]->roster->memberList[$j])) { ?>
			<tr>
			  <?php $infos = $api->getSummoner($res[$i]->roster->memberList[$j]->playerId); ?>
			    <td><?php echo $infos->name; ?></td>
			    <td><?php echo $infos->profileIconId; ?></td>
			    <td><?php echo $infos->summonerLevel ?></td>
			    <td>done</td>
			</tr>
			<?php $j++;
				  } ?>
		</table>
<?php 
		$i++;
		}*/ ?>
-->
	</body>
</html>