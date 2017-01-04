<?php
abstract class BaseModel{
	protected $database;
	
	public function __construct(){
		require("application/config/config.inc.php");
		try{
			$this->database = new PDO('mysql:host='.$host.';dbname='.$database, $user, $password);
		}catch(Exception $e)
		{
			die('An error occured connecting to the database. Please check your database login information: '.$e->getMessage());
		}
	}
	
	public function getPlayerUUID($playerName){
		$query = $this->database->prepare( "SELECT UUID FROM BAT_players comments WHERE BAT_player = :pName;" );
		$query->execute(array("pName" => $playerName));
		$uuid = null;
		$data = $query->fetch();
		if($data != false){
			$uuid = $data['UUID'];
		}
		return $uuid;
	}
}
abstract class PunishmentEntry{
	protected $id;
	protected $player;
	protected $reason;
	protected $staff;
	protected $date;
	
	private $ipPunishment = false;
	
	protected function markAsIpPunishment(){
		$this->ipPunishment = true;
	}
	
	protected function isIPPunishment(){
		return $this->ipPunishment;
	}
}
class AJAXAnswer{
	private $message;
	private $mustReload;
	private $urlArgs;
	
	public function __construct($message, $mustReload, $urlArgs = null){
		$this->message = $message;
		$this->mustReload = $mustReload;
		$this->urlArgs = $urlArgs;
	}
	
	public function getJSON(){
		return json_encode(array("message" => $this->message, "mustReload" => $this->mustReload, "urlArgs" => $this->urlArgs));
	}
}
