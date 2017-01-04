<?php
class mute_model extends BaseModel{
	// Maps a column name to its real name in db
	private $sortByColumnMap;
	
	public function __construct(){
		parent::__construct();
		$this->sortByColumnMap = array(
				"player" => "UUID",
				"server" => "mute_server",
				"reason" => "mute_reason",
				"staff" => "mute_staff",
				"date" => "mute_begin DESC",
				"state" => "mute_state",
				"unmute_date" => "mute_unmutedate DESC, mute_end DESC",
				"unmute_staff" => "mute_unmutestaff",
				"unmute_reason" => "mute_unmutereason"
		);
	}
	
	public function getmuteEntries($pageNo, $entriesPerPage, $sortingColumn = "date"){
		if(!array_key_exists($sortingColumn, $this->sortByColumnMap)){
			$sortingColumn = "date";
		}
		
		$orderByColumn = $this->sortByColumnMap[$sortingColumn];
		$query = $this->database->prepare( "SELECT mutes.*, (SELECT players.BAT_player FROM BAT_players players
		WHERE mutes.UUID = players.UUID) as player FROM BAT_mute mutes ORDER BY ".$orderByColumn." LIMIT :offset, :limit;" );
		$offset = (($pageNo - 1) * $entriesPerPage);
		// Must manually bind parameters because of an old bug in PDO which forbid to add parameter to LIMIT statemnt ...
		$query->bindParam(":offset", $offset, PDO::PARAM_INT);
		$query->bindParam(":limit", $entriesPerPage, PDO::PARAM_INT);
		$query->execute();
		$muteEntries = array();
		while ( $data = $query->fetch () ) {
			$muteEntries[] = new MuteEntry($data);
		}
		return $muteEntries;
	}
	
	public function getTotalPages($entriesPerPage){
		$totalPages = 0;
		$result = $this->database->query("SELECT COUNT(*) FROM BAT_mute;");
		while( $data = $result->fetch()){
			$totalPages = ceil($data['COUNT(*)'] / $entriesPerPage);
		}
		if($totalPages < 1){
			$totalPages = 1;
		}
		return $totalPages;
	}
	
	public function getPlayerMutes($uuid){
		$query = $this->database->prepare( "SELECT * FROM BAT_mute WHERE UUID = :uuid ORDER BY mute_begin;" );
		$query->execute(array("uuid" => $uuid));
		$muteEntries = array();
		while ( $data = $query->fetch () ) {
			$muteEntries[] = new MuteEntry($data);
		}
		return $muteEntries;
	}
	
	public function disableMute($muteID, $unmuteReason, $unmuteStaff){
		$query = $this->database->prepare("UPDATE BAT_mute SET mute_state = 0,
				mute_unmutereason = :unmute_reason, mute_unmutestaff = :unmute_staff, mute_unmutedate = NOW()
				WHERE mute_id = :muteID AND mute_state = 1;");
		$query->execute(array(
				"unmute_reason" => $unmuteReason,
				"unmute_staff" => $unmuteStaff,
				"muteID" => $muteID));
		if($query->rowCount() > 0){
			$answer = new AJAXAnswer("Successfully unmuted.", true);
			return $answer->getJSON();
		}else{
			$answer = new AJAXAnswer("Error : No active mute with this id!", false);
			return $answer->getJSON();
		}
	}
	
	public function mute($uuid, $muteServer, $muteExpiration, $muteStaff, $muteReason){
		$query = $this->database->prepare("INSERT INTO `BAT_mute`(UUID, mute_staff, mute_server, mute_end, mute_reason) 
				VALUES (:uuid, :staff, :server, :expiration, :reason)");
		if($muteExpiration == null){
			$query->bindParam(":expiration", $muteExpiration, PDO::PARAM_NULL);
		}else{
			$query->bindParam(":expiration", $muteExpiration);
		}
		$query->bindParam(":uuid", $uuid);
		$query->bindParam(":staff", $muteStaff);
		$query->bindParam(":server", $muteServer);
		$query->bindParam(":reason", $muteReason);
		$query->execute();
		if($query->rowCount() > 0){
			$answer = new AJAXAnswer("Muted successfully!", true);
			return $answer->getJSON();
		}else{
			$answer = new AJAXAnswer("Error : the mute process has failed for unknown reason.", false);
			return $answer->getJSON();
		}
	}
}
class MuteEntry extends PunishmentEntry{
	private $headUrl;
	private $server;
	private $state;
	private $unmuteDate;
	private $unmuteStaff;
	private $unmuteReason;

	function __construct($data){
		$this->id = $data['mute_id'];
		if(isset($data['player'])){
			$this->player = $data['player'];
			$this->headUrl = "https://cravatar.eu/helmhead/".$this->player."/32";
		}else{
			if(isset($data['mute_ip'])){
				$this->markAsIpPunishment();
				$this->player = $data['mute_ip'];
			}else{
				$this->player = $data['UUID'];
				$this->headUrl = "https://cravatar.eu/helmhead/char/32";
			}
		}
		$this->server = ($data ['mute_server'] == "(global)") ? Message::globalPunishment : $data ['mute_server'];
		$this->reason = (empty($data ['mute_reason'])) ? Message::noReason : $data ['mute_reason'];
		$this->staff = $data ['mute_staff'];
		$this->date = $data['mute_begin'];
		$this->state = $data['mute_state'];
		if($this->state){
			if(isset($data['mute_end'])){
				$this->unmuteDate = $data['mute_end'];
				/* If the Bungee server is shutdown, the temp punishment won't be updated.
				 So we do the calculation here, but we don't touch to the database data ! */
				$unmuteDateTime = new DateTime($data['mute_end']);
				$currentTime = new DateTime("now");
				$interval = $unmuteDateTime->diff($currentTime);
				if($unmuteDateTime < $currentTime){
					$this->state = false;
				}
			}else{
				$this->unmuteDate = Message::noData;
			}
		}else{
			if(isset($data['mute_unmutedate'])){
				if(isset($data['mute_end'])){
					$unmuteDateTime = new DateTime($data['mute_unmutedate']);
					$endmuteDateTime = new DateTime($data['mute_end']);
					$interval = $unmuteDateTime->diff($endmuteDateTime);
					$this->unmuteDate = ($unmuteDateTime < $endmuteDateTime) ? $data['mute_unmutedate'] : $data['mute_end'];
				}else{
					$this->unmuteDate = $data['mute_unmutedate'];
				}
			}else{
				$this->unmuteDate = $data['mute_end'];
			}
		}
		$this->unmuteStaff = (isset($data ['mute_unmutestaff'])) ? $data ['mute_unmutestaff'] : Message::noData;
		$this->unmuteReason = (isset($data ['mute_unmutereason'])) ? (($data ['mute_unmutereason'] != "noreason") ? $data ['mute_unmutereason'] : Message::noReason) : Message::noData;
	}

	/**
	 * Get an associative array with tag and their associated data
	 */
	function getData(){
		return array (
				"id" => $this->id,
				"headImg" => (isset($this->headUrl))
				? "<a href='?p=profile&player=$this->player'><img src='$this->headUrl'></a><br>" : "",
				"player" => $this->player,
				"server" => $this->server,
				"reason" => $this->reason,
				"staff" => $this->staff,
				"date" => $this->date,
				"state" => $this->state,
				"unmute_date" => $this->unmuteDate,
				"unmute_staff" => $this->unmuteStaff,
				"unmute_reason" => $this->unmuteReason,
				"ipPunishment" => $this->isIPPunishment()
		);
	}
}
