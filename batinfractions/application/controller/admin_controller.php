<?php
class admin extends BaseController{
	private $model;
	
	public function __construct($action, $urlData){
		parent::__construct($action, $urlData);
		$this->model = new admin_model();
	}
	
	protected function index(){
		if($this->isAdmin()){
			$this->action = "index";
			echo $this->getView(array());
		}else{
			$this->login();
		}
	}
	
	// Login, logout pages
	protected function login(){
		$this->action = "login";
		echo $this->getView(array());
	}
	protected function processlogin(){
		if(empty($_POST['user']) || empty($_POST['password'])){
			$this->login();
			return;
		}
		$result = $this->model->checkCredentials($_POST['user'], $_POST['password']);
		if(!$result){
			$answer = new AJAXAnswer("These logins informations aren't correct.", false);
			echo $answer->getJSON();
		}else{
			$_SESSION['status'] = $result;
			$_SESSION['username'] = $_POST['user'];
			$answer = new AJAXAnswer("You have successfully connected ! Redirection in progress ...", true);
			echo $answer->getJSON();
		}
	}
	protected function logout(){
		if(!$this->isAdmin()){return;}
		
		session_destroy();
		$answer = new AJAXAnswer("You have successfully disconnected !", true, "");
		echo $answer->getJSON();
	}
	
	// SuperUser features
	protected function manageaccounts(){
		if(!$this->isSU()){$this->index();return;}
		
		echo $this->getView(array("users" => $this->model->listUsers()));
	}
	protected function createaccount(){
		if(!$this->isSU()){$this->index();return;}
		if(empty($_POST['user']) || empty($_POST['password'])){
			$answer = new AJAXAnswer("One or many parameters are missing !", false);
			echo $answer->getJSON();
			return;
		}
		
		echo $this->model->createAccount($_POST['user'], $_POST['password']);
	}
	protected function deleteaccount(){
		if(!$this->isSU()){$this->index();return;}
		if(empty($_POST['user'])){
			$answer = new AJAXAnswer("User parameter is missing !", false);
			echo $answer->getJSON();
			return;
		}
		if($this->getUsername() == $_POST['user']) {
			$answer = new AJAXAnswer("You can't delete your own account !", false);
			echo $answer->getJSON();
			return;
		}
		
		echo $this->model->removeAccount($_POST['user']);
	}
	protected function toggleSU(){
		if(!$this->isSU()){$this->index();return;}
		if(empty($_POST['user'])){
			$answer = new AJAXAnswer("User parameter is missing !", false);
			echo $answer->getJSON();
			return;
		}
		if($this->getUsername() == $_POST['user']) {
			$answer = new AJAXAnswer("You can't remove yourself your SU rights !", false);
			echo $answer->getJSON();
			return;
		}
		
		echo $this->model->toogleSU($_POST['user']);
	}
	
}