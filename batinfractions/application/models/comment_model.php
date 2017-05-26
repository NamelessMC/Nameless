<?php
class comment_model extends BaseModel{
	// Maps a column name to its real name in db
	private $sortByColumnMap;

	public function __construct(){
		parent::__construct();
		$this->sortByColumnMap = array(
				"player" => "entity",
				"reason" => "note",
				"staff" => "staff",
				"date" => "date DESC",
				"type" => "type"
		);
	}

	public function getCommentEntries($pageNo, $entriesPerPage, $sortingColumn = "date"){
		if(!array_key_exists($sortingColumn, $this->sortByColumnMap)){
			$sortingColumn = "date";
		}

		$orderByColumn = $this->sortByColumnMap[$sortingColumn];
		$query = $this->database->prepare( "SELECT comments.*, (SELECT players.BAT_player FROM BAT_players players
		WHERE comments.entity = players.UUID) as player FROM bat_comments comments ORDER BY ".$orderByColumn." LIMIT :offset, :limit;" );
		$offset = (($pageNo - 1) * $entriesPerPage);
		// Must manually bind parameters because of an old bug in PDO which forbid to add parameter to LIMIT statemnt ...
		$query->bindParam(":offset", $offset, PDO::PARAM_INT);
		$query->bindParam(":limit", $entriesPerPage, PDO::PARAM_INT);
		$query->execute();
		$commentEntries = array();
		while ( $data = $query->fetch () ) {
			$commentEntries[] = new CommentEntry($data);
		}
		return $commentEntries;
	}

	public function getTotalPages($entriesPerPage){
		$totalPages = 0;
		$result = $this->database->query("SELECT COUNT(*) FROM bat_comments;");
		while( $data = $result->fetch()){
			$totalPages = ceil($data['COUNT(*)'] / $entriesPerPage);
		}
		if($totalPages < 1){
			$totalPages = 1;
		}
		return $totalPages;
	}

	public function getPlayerComments($uuid){
		$query = $this->database->prepare( "SELECT * FROM bat_comments WHERE entity = :uuid ORDER BY date;" );
		$query->execute(array("uuid" => $uuid));
		$commentEntries = array();
		while ( $data = $query->fetch () ) {
			$commentEntries[] = new CommentEntry($data);
		}
		return $commentEntries;
	}
}
class CommentEntry extends PunishmentEntry{
	private $headUrl;
	private $type;

	function __construct($data){
		if(isset($data['player'])){
			$this->player = $data['player'];
			$this->headUrl = "https://cravatar.eu/helmhead/".$this->player."/32";
		}else{
			$this->player = $data['entity'];
			$this->headUrl = "https://cravatar.eu/helmhead/char/32";
		}
		$this->reason = $data ['note'];
		$this->staff = $data ['staff'];
		$this->date = $data['date'];
		$this->type = $data['type'];
	}

	/**
	 * Get an associative array with tag and their associated data
	 */
	function getData(){
		return array (
				"headImg" => (isset($this->headUrl))
				? "<a href='?p=profile&player=$this->player'><img src='$this->headUrl'></a><br>" : "",
				"player" => $this->player,
				"reason" => $this->reason,
				"staff" => $this->staff,
				"date" => $this->date,
				"type" => $this->type
		);
	}
}