<?php
class profile extends BaseController{
	private $model;
	
	public function __construct($action, $urlData){
		parent::__construct($action, $urlData);
		$this->model = new profile_model();
	}
	
	protected function index(){
		$player = (isset($this->urlData['player'])) ? $this->urlData['player'] : null;
		if(empty($player)){
			echo $this->getErrorPage("<strong>Please specify a player to view his profile ...</strong>");
			return;
		}
		// Check if the player arg is an IP and trigger IP search if that's the case
		if(preg_match('/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)){3}$/', 
			$player)){
			echo $this->listPlayersByIp($player);
		}else{
			echo $this->viewprofile($player);
		}
	}

	public function searchplayer(){
		if(empty($this->urlData['term']) || strlen($this->urlData['term']) < 3){
			return;
		}
		$players = $this->model->getPlayersStartingWith($this->urlData['term']);
		$dataSet = array();
		$i = 0;
		foreach($players as $player){
			$entry = array(
				"id" => $i,
				"value" => $player
			);
			$i++;
			$dataSet[] = $entry;
		}
		echo json_encode($dataSet);
	}
	
	private function listPlayersByIp($ip){
		if(!$this->isAdmin()){
			echo $this->getErrorPage("<strong>You must be authenticated to search player by IP ...</strong>");
			return;
		}
		$this->action = "playersByIp";
		$players = $this->model->getPlayersByIp($ip);
		if(empty($players)){
			echo $this->getErrorPage("<strong>No player with the specified IP (" . $ip . ") was found in the database ...</strong>");
			return;
		}
		return $this->getView(array(
				"ip" => $ip,
				"players" => $players
		));
	}
	
	private function viewprofile($player){
		$this->action = ($this->isAdmin()) ? "../admin/administrateprofile" : "viewprofile";
		$pUUID = $this->model->getPlayerUUID($player);
		if($pUUID == null){
			echo $this->getErrorPage("<strong>This player was not found in the database ...</strong>");
			return;
		}
		$pData = $this->model->getPlayerData($pUUID);
		return $this->getView($pData->getData());
	}
}