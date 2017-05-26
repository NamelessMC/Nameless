<?php
class comment extends BaseController{
	private $model;
	private $sortByColumn;

	public function __construct($action, $urlData){
		parent::__construct($action, $urlData);
		$sortingColumn = parent::getSortingColumn();
		if(!isset($sortingColumn)){
			$sortingColumn = "date";
		}
		$this->sortByColumn = $sortingColumn;
		$this->model = new comment_model();
	}

	protected function index(){
		echo $this->listComments();
	}

	private function listComments(){
		$this->action = "listcomments";
		$commentEntries = $this->model->getCommentEntries($this->getPage(), 20, $this->sortByColumn);
		return $this->getView($commentEntries);
	}

	public function getPaginationView(){
		return $this->generatePaginationView($this->getPage(), $this->model->getTotalPages(20));
	}

	public function getSortingColumn(){
		return $this->sortByColumn;
	}
}