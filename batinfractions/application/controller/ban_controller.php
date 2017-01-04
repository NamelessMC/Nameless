<?php
class ban extends BaseController{
	private $model;
	private $sortByColumn;
	
	public function __construct($action, $urlData){
		parent::__construct($action, $urlData);
		$sortingColumn = parent::getSortingColumn();
		if(!isset($sortingColumn)){
			$sortingColumn = "date";
		}
		$this->sortByColumn = $sortingColumn;
		$this->model = new ban_model();
	}
	
	protected function index(){
		echo $this->listBans();
	}

	private function listBans(){
		$this->action = "listbans";
		$banEntries = $this->model->getBanEntries($this->getPage(), 20, $this->sortByColumn);
		return $this->getView($banEntries);
	}
	
	protected function unban(){
		if(!$this->isAdmin()){return;}
		
		if(empty($_POST['ban_id']) || $_POST['ban_id'] < 0 || !isset($_POST['unban_reason'])){
			echo "Invalid ban id";
			return;
		}
		$result = $this->model->disableBan($_POST['ban_id'], $_POST['unban_reason'], $this->getUsername());
		echo $result;
	}
	
	protected function ban(){
		if(!$this->isAdmin()){return;}
		
		if(empty($_POST['player']) || empty($_POST['ban-server']) || empty($_POST['ban-expiration'])
			|| !isset($_POST['ban-reason'])){
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
		$banExpiration;
		if($_POST['ban-expiration'] == "definitive"){
			$banExpiration = null;
		}else{
			$banExpiration = DateTime::createFromFormat("m/d/Y h:i A", $_POST['ban-expiration']);
			$banExpiration = $banExpiration->format("Y-m-d H:i:s");
		}
		
		$result = $this->model->ban($uuid, $_POST['ban-server'], $banExpiration, $this->getUsername(), $_POST['ban-reason']);
		echo $result;
	}
	
	public function getPaginationView(){
		return $this->generatePaginationView($this->getPage(), $this->model->getTotalPages(20));
	}
	
	public function getSortingColumn(){
		return $this->sortByColumn;
	}
}