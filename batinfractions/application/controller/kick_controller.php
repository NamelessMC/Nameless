<?php
class kick extends BaseController{
	private $model;
	private $sortByColumn;

	public function __construct($action, $urlData){
		parent::__construct($action, $urlData);
		$sortingColumn = parent::getSortingColumn();
		if(!isset($sortingColumn)){
			$sortingColumn = "date";
		}
		$this->sortByColumn = $sortingColumn;
		$this->model = new kick_model();
	}

	protected function index(){
		echo $this->listKicks();
	}

	private function listKicks(){
		$this->action = "listkicks";
		$kickEntries = $this->model->getKickEntries($this->getPage(), 20, $this->sortByColumn);
		return $this->getView($kickEntries);
	}

	public function getPaginationView(){
		return $this->generatePaginationView($this->getPage(), $this->model->getTotalPages(20));
	}

	public function getSortingColumn(){
		return $this->sortByColumn;
	}
}