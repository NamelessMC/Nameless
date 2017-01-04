<?php
class kick_model extends BaseModel{
	// Maps a column name to its real name in db
	private $sortByColumnMap;

	public function __construct(){
		parent::__construct();
		$this->sortByColumnMap = array(
				"player" => "UUID",
				"server" => "kick_server",
				"reason" => "kick_reason",
				"staff" => "kick_staff",
				"date" => "kick_date DESC",
				"state" => "kick_state"
		);
	}

	public function getKickEntries($pageNo, $entriesPerPage, $sortingColumn = "date"){
		if(!array_key_exists($sortingColumn, $this->sortByColumnMap)){
			$sortingColumn = "date";
		}

		$orderByColumn = $this->sortByColumnMap[$sortingColumn];
		$query = $this->database->prepare( "SELECT kicks.*, (SELECT players.BAT_player FROM BAT_players players
		WHERE kicks.UUID = players.UUID) as player FROM BAT_kick kicks ORDER BY ".$orderByColumn." LIMIT :offset, :limit;" );
		$offset = (($pageNo - 1) * $entriesPerPage);
		// Must manually bind parameters because of an old bug in PDO which forbid to add parameter to LIMIT statemnt ...
		$query->bindParam(":offset", $offset, PDO::PARAM_INT);
		$query->bindParam(":limit", $entriesPerPage, PDO::PARAM_INT);
		$query->execute();
		$kickEntries = array();
		while ( $data = $query->fetch () ) {
			$kickEntries[] = new KickEntry($data);
		}
		return $kickEntries;
	}

	public function getTotalPages($entriesPerPage){
		$totalPages = 0;
		$result = $this->database->query("SELECT COUNT(*) FROM BAT_kick;");
		while( $data = $result->fetch()){
			$totalPages = ceil($data['COUNT(*)'] / $entriesPerPage);
		}
		if($totalPages < 1){
			$totalPages = 1;
		}
		return $totalPages;
	}

	public function getPlayerKicks($uuid){
		$query = $this->database->prepare( "SELECT * FROM BAT_kick WHERE UUID = :uuid ORDER BY kick_date;" );
		$query->execute(array("uuid" => $uuid));
		$kickEntries = array();
		while ( $data = $query->fetch () ) {
			$kickEntries[] = new KickEntry($data);
		}
		return $kickEntries;
	}
}
class KickEntry extends PunishmentEntry{
	private $headUrl;
	private $server;
	private $state;

	function __construct($data){
		if(isset($data['player'])){
			$this->player = $data['player'];
			$this->headUrl = "https://cravatar.eu/helmhead/".$this->player."/32";
		}else{
			$this->player = $data['UUID'];
			$this->headUrl = "https://cravatar.eu/helmhead/char/32";
		}
		$this->server = ($data ['kick_server'] == "(global)") ? Message::globalPunishment : $data ['kick_server'];
		$this->reason = ($data ['kick_reason'] == "noreason") ? Message::noReason : $data ['kick_reason'];
		$this->staff = $data ['kick_staff'];
		$this->date = $data['kick_date'];
	}

	/**
	 * Get an associative array with tag and their associated data
	 */
	function getData(){
		return array (
				"headImg" => (isset($this->headUrl))
				? "<a href='?p=profile&player=$this->player'><img src='$this->headUrl'></a><br>" : "",
				"player" => $this->player,
				"server" => $this->server,
				"reason" => $this->reason,
				"staff" => $this->staff,
				"date" => $this->date
		);
	}
}