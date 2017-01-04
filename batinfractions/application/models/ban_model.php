<?php
class ban_model extends BaseModel{
	// Maps a column name to its real name in db
	private $sortByColumnMap;
	
	public function __construct(){
		parent::__construct();
		$this->sortByColumnMap = array(
				"player" => "UUID",
				"server" => "ban_server",
				"reason" => "ban_reason",
				"staff" => "ban_staff",
				"date" => "ban_begin DESC",
				"state" => "ban_state",
				"unban_date" => "ban_unbandate DESC, ban_end DESC",
				"unban_staff" => "ban_unbanstaff",
				"unban_reason" => "ban_unbanreason"
		);
	}
	
	public function getBanEntries($pageNo, $entriesPerPage, $sortingColumn = "date"){
		if(!array_key_exists($sortingColumn, $this->sortByColumnMap)){
			$sortingColumn = "date";
		}
		
		$orderByColumn = $this->sortByColumnMap[$sortingColumn];
		$query = $this->database->prepare( "SELECT bans.*, (SELECT players.BAT_player FROM BAT_players players
		WHERE bans.UUID = players.UUID) as player FROM BAT_ban bans ORDER BY ".$orderByColumn." LIMIT :offset, :limit;" );
		$offset = (($pageNo - 1) * $entriesPerPage);
		// Must manually bind parameters because of an old bug in PDO which forbid to add parameter to LIMIT statemnt ...
		$query->bindParam(":offset", $offset, PDO::PARAM_INT);
		$query->bindParam(":limit", $entriesPerPage, PDO::PARAM_INT);
		$query->execute();
		$banEntries = array();
		while ( $data = $query->fetch () ) {
			$banEntries[] = new BanEntry($data);
		}
		return $banEntries;
	}
	
	public function getTotalPages($entriesPerPage){
		$totalPages = 0;
		$result = $this->database->query("SELECT COUNT(*) FROM BAT_ban;");
		while( $data = $result->fetch()){
			$totalPages = ceil($data['COUNT(*)'] / $entriesPerPage);
		}
		if($totalPages < 1){
			$totalPages = 1;
		}
		return $totalPages;
	}
	
	public function getPlayerBans($uuid){
		$query = $this->database->prepare( "SELECT * FROM BAT_ban WHERE UUID = :uuid ORDER BY ban_begin;" );
		$query->execute(array("uuid" => $uuid));
		$banEntries = array();
		while ( $data = $query->fetch () ) {
			$banEntries[] = new BanEntry($data);
		}
		return $banEntries;
	}
	
	public function disableBan($banID, $unbanReason, $unbanStaff){
		$query = $this->database->prepare("UPDATE BAT_ban SET ban_state = 0,
				ban_unbanreason = :unban_reason, ban_unbanstaff = :unban_staff, ban_unbandate = NOW() 
				WHERE ban_id = :banID AND ban_state = 1;");
		$query->execute(array(
				"unban_reason" => $unbanReason,
				"unban_staff" => $unbanStaff,
				"banID" => $banID));
		if($query->rowCount() > 0){
			$answer = new AJAXAnswer("Successfully unbanned.", true);
			return $answer->getJSON();
		}else{
			$answer = new AJAXAnswer("Error : No active ban with this id!", false);
			return $answer->getJSON();
		}
	}
	
	public function ban($uuid, $banServer, $banExpiration, $banStaff, $banReason){
		$query = $this->database->prepare("INSERT INTO `BAT_ban`(UUID, ban_staff, ban_server, ban_end, ban_reason) 
				VALUES (:uuid, :staff, :server, :expiration, :reason)");
		if($banExpiration == null){
			$query->bindParam(":expiration", $banExpiration, PDO::PARAM_NULL);
		}else{
			$query->bindParam(":expiration", $banExpiration);
		}
		$query->bindParam(":uuid", $uuid);
		$query->bindParam(":staff", $banStaff);
		$query->bindParam(":server", $banServer);
		$query->bindParam(":reason", $banReason);
		$query->execute();
		if($query->rowCount() > 0){
			$answer = new AJAXAnswer("Banned successfully!", true);
			return $answer->getJSON();
		}else{
			$answer = new AJAXAnswer("Error : the ban process has failed for unknown reason.", false);
			return $answer->getJSON();
		}
	}
	
}
class BanEntry extends PunishmentEntry{
	private $headUrl;
	private $server;
	private $state;
	private $unbanDate;
	private $unbanStaff;
	private $unbanReason;

	function __construct($data){
		$this->id = $data['ban_id'];
		if(isset($data['player'])){
			$this->player = $data['player'];
			$this->headUrl = "https://cravatar.eu/helmhead/".$this->player."/32";
		}else{
			if(isset($data['ban_ip'])){
				$this->player = $data['ban_ip'];
				$this->markAsIpPunishment();
			}else{
				$this->player = $data['UUID'];
				$this->headUrl = "https://cravatar.eu/helmhead/char/32";
			}
		}
		$this->server = ($data ['ban_server'] == "(global)") ? Message::globalPunishment : $data ['ban_server'];
		$this->reason = (empty($data ['ban_reason'])) ? Message::noReason : $data ['ban_reason'];
		$this->staff = $data ['ban_staff'];
		$this->date = $data['ban_begin'];
		$this->state = $data['ban_state'];
		if($this->state){
			if(isset($data['ban_end'])){
				$this->unbanDate = $data['ban_end'];
				/* If the Bungee server is shutdown, the temp punishment won't be updated.
				 So we do the calculation here, but we don't touch to the database data ! */
				$unbanDateTime = new DateTime($data['ban_end']);
				$currentTime = new DateTime("now");
				$interval = $unbanDateTime->diff($currentTime);
				if($unbanDateTime < $currentTime){
					$this->state = false;
				}
			}else{
				$this->unbanDate = Message::noData;
			}
		}else{
			if(isset($data['ban_unbandate'])){
				if(isset($data['ban_end'])){
					$unbanDateTime = new DateTime($data['ban_unbandate']);
					$endBanDateTime = new DateTime($data['ban_end']);
					$interval = $unbanDateTime->diff($endBanDateTime);
					$this->unbanDate = ($unbanDateTime < $endBanDateTime) ? $data['ban_unbandate'] : $data['ban_end'];
				}else{
					$this->unbanDate = $data['ban_unbandate'];
				}
			}else{
				$this->unbanDate = $data['ban_end'];
			}
		}
		$this->unbanStaff = (isset($data ['ban_unbanstaff'])) ? $data ['ban_unbanstaff'] : Message::noData;
		$this->unbanReason = (isset($data ['ban_unbanreason'])) ? (($data ['ban_unbanreason'] != "noreason") ? $data ['ban_unbanreason'] : Message::noReason) : Message::noData;
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
				"unban_date" => $this->unbanDate,
				"unban_staff" => $this->unbanStaff,
				"unban_reason" => $this->unbanReason,
				"ipPunishment" => $this->isIPPunishment()
		);
	}
}
