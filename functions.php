<?php
  function    getTeam($history) {
    for ($i = 0; $i < 9; $i++) {
      if ($history->fellowPlayers[$i]->teamId == $history->teamId) {$team[] = $history->fellowPlayers[$i];}
      }
      return $team;
    }
    
    function    getOpponent($history) {
    for ($i = 0; $i < 9; $i++) {
      if ($history->fellowPlayers[$i]->teamId != $history->teamId) {$team[] = $history->fellowPlayers[$i];}
      }
      return $team;
    }
?>