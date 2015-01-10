<html>
<?php	$bdd = mysql_connect('mysql51-110.perso', 'easyelobsql', '2BNG5tqTx6UH'); 
	    mysql_select_db('easyelobsql', $bdd); 
      require 'riotapi.class.php';
      include 'functions.php';
      $api = new RiotApi('euw');
      $api->setDebug(false);
?>
<head>
  	<link href="style.css" type="text/css" media="all" rel="stylesheet" />
</head>
<body>
<div class="left_panel">
  <table>
  <tr>
    <td>Type</td>
    <td>Champion</td>
    <td>KDA</td>
    <td>Resultat</td>
  </tr>
<?php $select = mysql_query('SELECT * FROM lol_database WHERE Player=20002219 ORDER BY MatchId DESC');
    while ($data = mysql_fetch_assoc($select)) { ?>
    <tr>
      <td><?php if ($data['Type'] != 'NONE'){echo $data['Type'];}else{echo 'CUSTOM';} ?></td>
      <td><?php echo $data['Champion']; ?></td>
      <?php $req = 'SELECT Kills, Deaths, Assists FROM lol_stats WHERE MatchId=' . $data['MatchId'];
        $sel = mysql_query($req);
        $stats = mysql_fetch_assoc($sel); ?>
      <td><?php echo $stats['Kills'] . '/' . $stats['Deaths'] . '/' . $stats['Assists']; ?></td>
      <td><?php if ($data['Victoire'] == 1){echo 'WIN';}else{echo 'LOSE';}?></td>
    </tr>
<?php } ?>
</table>
</div>
<div class="right_panel">
  <?php $infos = $api->getSummoner(20002219); 
        $team = $api->getTeams(20002219); ?>
  <h3><?php echo $infos->name; ?></h3>
  <table>
    <tr>
      <th>Name</th><th>Wins</th><th>Losses</th><th>League</th><th>L-Name</th>
    </tr>
    <?php $i = 0; ?>
    <?php // while ($team[$i]) { 
    ?>
          <tr>
            <td><?php echo $team[$i]->name; ?></td>
            <td><?php if ($team[$i]->teamStatDetails[0]->teamStatType == "RANKED_TEAM_5x5") {
                echo $team[$i]->teamStatDetails[0]->wins;
            } else {
              echo $team[$i]->teamStatDetails[1]->wins;
            } ?>
            </td>
            <td><?php if ($team[$i]->teamStatDetails[0]->teamStatType == "RANKED_TEAM_5x5") {
                echo $team[$i]->teamStatDetails[0]->losses;
            } else {
              echo $team[$i]->teamStatDetails[1]->losses;
            } ?>
            </td>
            <td>
              <?php
                $tid = $team[$i]->fullId;
                $teamstats = $api->getLeagueByTeam($tid);
                echo $teamstats->tier;
                $o = 0;
                print_r(teamstats);
               // while ($teamstats->entries[$o]->playerOrTeamId != $tid) {$o++;}
                echo $teamstats->entries[$o]->division; ?>
            </td>  
          </tr>
    <?php  //  $i++; }
    ?>
  </table>
</div>
</body>
</html>