<?php

	class RiotApi {
		//necessaire à cURL
		private $ch;
	    private static $curlOpts = array(
        CURLOPT_HEADER => true,
        CURLINFO_HEADER_OUT => true,
        CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => false
        );
	
		private $key = '4cc2723d-f13c-41dc-85ce-fe5c89534640';
		private $region;
		private $reponse;
		private $debug;
		private $autoRetry = array(429);
		private $timeout = 3;
		private $tries = 5;
		private $baseHost = 'api.pvp.net/api/lol';

		
		public function __construct($region) {
			$this->region = $region;
			$this->ch = curl_init();
			curl_setopt_array($this->ch, self::$curlOpts);
			$debug = false;
		}
		public function __destruct() {
			curl_close($this->ch);
		}
		public function setDebug($var) {
			$this->debug = $var;
		}
		//Requetes
		public function request($req, $version) {
			$this->requestCurl($this->getURL($req, $version, false));
		}
		public function requestStatic($req, $version) {
			$this->requestCurl($this->getURL($req, $version, true));
		}
		
		//Reponses
		public function response() {
			return $this->response;
		}
		public function responseHeaders() {
			return $this->response->headers;
		}
	
		//	Construction de l'URL:
	    private function getURL($req, $version, $static) {
			if ($static) {$region = "global";} else {$region = $this->region;}
			$url = 'http://' . $region . '.' . $this->baseHost;
			if ($static) {$url .= '/static-data';}

			$url .= "/{$this->region}/v$version/$req?api_key={$this->key}";
			$this->printDebug("created url: $url");
			return $url;
		}
		//	Envoi de la requete via curl
    private function requestCurl($url) {
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_HTTPGET, true);

        $tries = 0;

        do {
            $this->printDebug("Requesting url, try #" . ($tries + 1), 2);
            
            $response = curl_exec($this->ch);
            $breakpoint = strpos($response, '{');

            $this->response = new stdClass();

            $this->response->code = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
            $this->response->headers = substr($response, 0, $breakpoint - 1);
            $this->response->sentHeaders = curl_getinfo($this->ch, CURLINFO_HEADER_OUT);
            $this->response->body = json_decode(substr($response, $breakpoint));

        } while (in_array($this->response->code, $this->autoRetry) && ++$tries < $this->tries
            && !sleep($this->timeout));

        if ($this->response->code != 200) {
            $this->printDebug("Max tries exhausted while requesting $url.", 2);
            $this->callback($url, $this->response->code);
            $this->throwException($url, $this->response->code);
        }
    }
		
		private function printDebug($msg) {
			if ($this->debug)
				echo "<strong>[DEBUG]</strong> " . $msg . "<br />";
		}
		
		//	Methodes
		
		public function getIdFromName($name) {
			$this->printDebug("Getting Summoner ID from name: $name");
			$name = strtolower($name);
			$this->request("summoner/by-name/$name", "1.4");
			return $this->response->body->$name->id;
		}
				
		public function getTeams($id) {
			$this->printDebug("Getting teams for summoner id: $id");
			$this->request("team/by-summoner/$id", "2.4");
			return $this->response->body->$id;
		}
	
		public function getSummoner($id) {
			$this->printDebug("Getting summoner infos for id: $id");
			$this->request("summoner/$id", "1.4");
			return $this->response->body->$id;
		}
		
		public function getMatchHistory($id) {
			$this->printDebug("Getting match history for summoner id: $id");
			$this->request("matchhistory/$id", "2.2");
			return $this->response->body->matches;
		}
		public function getChampion($id) {
			$this->printDebug("Getting champion informations with id: $id");
			$this->requestStatic("champion/$id", "1.2");
			return $this->response->body;
		}
		public function getLastGames($id) {
			$this->printDebug("Getting last games for summoner id: $id");
			$this->request("game/by-summoner/$id/recent", "1.3");
			return $this->response->body->games;
		
		}
		public function getLeagueByTeam($id) {
			$this->printDebug("Getting team stats for team id: $id");
			$this->request("league/by-team/$id", "2.5");
			return $this->response->body->games;		  
		}
	}
?>