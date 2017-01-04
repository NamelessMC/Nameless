<?php
class profile_model extends BaseModel{

	public function getPlayerData($playerUUID){
		return new PlayerData($playerUUID, $this->database);
	}
	
	public function getPlayersStartingWith($prefix){
		$query = $this->database->prepare( "SELECT BAT_player FROM BAT_players comments WHERE BAT_player LIKE :prefix;" );
		$query->execute(array("prefix" => $prefix . "%"));
		$players = array();
		while($data = $query->fetch()){
			$players[] = $data['BAT_player'];
		}
		return $players;
	}
	
	public function getPlayersByIp($ip){
		$query = $this->database->prepare( "SELECT BAT_player FROM BAT_players comments WHERE lastip = :ip;" );
		$query->execute(array("ip" => $ip ));
		$players = array();
		while($data = $query->fetch()){
			$players[] = $data['BAT_player'];
		}
		return $players;
	}
}
class PlayerData{
	// Basic informations
	private $player;
	private $uuid;
	
	// Additionals stats
	private $firstlogin;
	private $lastlogin;
	private $lastip;
	
	// Entries of all the modules
	private $banEntries;
	private $muteEntries;
	private $kickEntries;
	private $commentEntries;
	
	public function __construct($playerUUID, $database){
		$this->uuid = $playerUUID;
		
		// Gather additionals stats
		$query = $database->prepare( "SELECT * FROM BAT_players WHERE UUID = :uuid;" );
		$query->execute(array(":uuid" => $this->uuid));
		$data = $query->fetch();
		if($data != false){
			$this->player = $data['BAT_player'];
			$this->firstlogin = $data['firstlogin'];
			$this->lastlogin = $data['lastlogin'];
			$this->lastip = $data['lastip'];
		}else{
			die("Player not found !");
		}
		
		// Gather different modules stats
		$banModel = new ban_model(); $this->banEntries = $banModel->getPlayerBans($this->uuid);
		$muteModel = new mute_model(); $this->muteEntries = $muteModel->getPlayerMutes($this->uuid);
		$kickModel = new kick_model(); $this->kickEntries = $kickModel->getPlayerKicks($this->uuid);
		$commentModel = new comment_model(); $this->commentEntries = $commentModel->getPlayerComments($this->uuid);
	}
	
	public function getData(){
		return array(
			"headUrl" => "https://cravatar.eu/helmhead/".$this->player."/192",
			"player" => $this->player,
			"uuid" => $this->uuid,
			"firstlogin" => $this->firstlogin,
			"lastlogin" => $this->lastlogin,
			"lastip" => $this->lastip,
			"bans" => $this->banEntries,
			"mutes" => $this->muteEntries,
			"kicks" => $this->kickEntries,
			"comments" => $this->commentEntries
		);
	}
}