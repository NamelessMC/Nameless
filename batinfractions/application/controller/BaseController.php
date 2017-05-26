<?php
abstract class BaseController{
	protected $action;
	protected $urlData;
	
	function __construct($action, $urlData){
		$this->action = $action;
		$this->urlData = $urlData;
	}
	
	/**
	 * Execute the given action
	 */
	public function executeAction(){
		return $this->{$this->action}();
	}
	
	protected function getView($data){
		$viewloc = 'application/views/' . get_class($this) . '/' . $this->action . '.php';
		ob_start();
		require("application/views/_template/header.php");
		require($viewloc);
		$paginationView = $this->getPaginationView();
		require("application/views/_template/footer.php");
		$view = ob_get_contents();
		ob_end_clean();
		return $view;
	}
	
	protected function getPage(){
		$pageNo = 1;
		if(isset($this->urlData['pageNo']) && $this->urlData['pageNo'] > 0){
			$pageNo = $this->urlData['pageNo'];
		}
		return $pageNo;
	}
	
	protected function getErrorPage($errorDetails){
		ob_start();
		require("application/views/_template/header.php");
		require('application/views/_template/errorPage.php');
		require("application/views/_template/footer.php");
		$view = ob_get_contents();
		ob_end_clean();
		return $view;
	}
	
	protected function getSortingColumn(){
		$sortingColumn = null;
		if(!empty($this->urlData['sortBy'])){
			$sortingColumn = $this->urlData['sortBy'];
		}
		return $sortingColumn;
	}
	
	protected function generatePaginationView($currentPage, $totalPages){
		$display = "<center><ul class='pagination'>";
		if($currentPage == 1){
			$display .= "<li class='active'><a href='#' onClick='choosePage(1)'>1</a></li>";
		}
		else{
			$display .= "<li><a href='#' onClick='choosePage(1)'>1</a></li>";
			if($currentPage == 2){
				$display .= "<li class='active'><a href='#' onClick='choosePage(2)'>2</a></li>";
			}else{
				$display .= "<li><a href='#' onClick='openPageSelector()'>...</a></li>";
				$display .= "<li class='active'><a href='#' onClick='choosePage($currentPage)'>$currentPage</a></li>";
			}
		}
		if($currentPage + 1 < $totalPages){
			$display .= "<li><a href='#' onClick='openPageSelector()'>...</a></li>";
		}
		if($currentPage != $totalPages){
			$display .= "<li><a href='#' onClick='choosePage($totalPages)'>$totalPages</a></li>";
		}
		$display .= "</ul></center>";
		return $display;
	}
	
	/**
	 * Should be override if there is a pagination system
	 */
	public function getPaginationView(){
		return null;
	}
	
	protected function isAdmin(){
		return isset($_SESSION['username']);
	}
	
	protected function isSU(){
		return $this->isAdmin() && $_SESSION['status'] == "superuser";
	}
	
	protected function getUsername(){
		return ($this->isAdmin()) ? $_SESSION['username'] : "non-auth-user";
	}
	
	/**
	 * Display index page of the Controller
	 */
	protected abstract function index();
}