<?php
class mute extends BaseController{
	private $model;
	private $sortByColumn;
	
	public function __construct($action, $urlData){
		parent::__construct($action, $urlData);
		$sortingColumn = parent::getSortingColumn();
		if(!isset($sortingColumn)){
			$sortingColumn = "date";
		}
		$this->sortByColumn = $sortingColumn;
		$this->model = new mute_model();
	}
	
	protected function index(){
		echo $this->listMutes();
	}

	private function listMutes(){
		$this->action = "listmutes";
		$muteEntries = $this->model->getMuteEntries($this->getPage(), 20, $this->sortByColumn);
		return $this->getView($muteEntries);
	}
	
	protected function unmute(){
		if(!$this->isAdmin()){return;}
	
		if(empty($_POST['mute_id']) || $_POST['mute_id'] < 0 || !isset($_POST['unmute_reason'])){
			echo "Invalid mute id";
			return;
		}
		$result = $this->model->disableMute($_POST['mute_id'], $_POST['unmute_reason'], $this->getUsername());
		echo $result;
	}
	
	protected function mute(){
		if(!$this->isAdmin()){return;}
	
		if(empty($_POST['player']) || empty($_POST['mute-server']) || empty($_POST['mute-expiration'])
				|| !isset($_POST['mute-reason'])){
			$answer = new AJAXAnswer("One or many parameters are missing !", false);
			echo $answer->getJSON();
			return;
		}
		$uuid = $this->model->getPlayerUUID($_POST['player']);
		if($uuid == null){
			$answer = new AJAXAnswer("Error : " . $_POST['player'] . "'s UUID can't be found.", false);
			echo $answer->getJSON();
			return;
		}
		$muteExpiration;
		if($_POST['mute-expiration'] == "definitive"){
			$muteExpiration = null;
		}else{
			$muteExpiration = DateTime::createFromFormat("m/d/Y h:i A", $_POST['mute-expiration']);
			$muteExpiration = $muteExpiration->format("Y-m-d H:i:s");
		}
	
		$result = $this->model->mute($uuid, $_POST['mute-server'], $muteExpiration, $this->getUsername(), $_POST['mute-reason']);
		echo $result;
	}
	
	public function getPaginationView(){
		return $this->generatePaginationView($this->getPage(), $this->model->getTotalPages(20));
	}
	
	public function getSortingColumn(){
		return $this->sortByColumn;
	}
}