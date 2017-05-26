<?php
class admin_model extends BaseModel{
	
	public function getSalt($user){
		$query = $this->database->prepare("SELECT * FROM BAT_web WHERE user = :user;");
		$query->execute(array("user" => $user));
		$salt = null;
		if(($data = $query->fetch()) != false){
			$salt = $data['salt'];
		}
		return $salt;
	}
	public function checkCredentials($user, $pwd){
		$salt = $this->getSalt($user);
		if($salt == null){
			return false;
		}
		
		$hash = hash("sha512", $pwd . $salt);
		$query = $this->database->prepare("SELECT * FROM BAT_web WHERE user = :user AND password = :pwd;");
		$query->execute(array("user" => $user, "pwd" => $hash));
		if($query->rowCount() > 0){
			$row = $query->fetch();
			return ($row['superuser']) ? "superuser" : "admin";
		}
		return false;
	}
	
	public function createAccount($user, $password){
		if($this->getSalt($user) != null){
			$answer = new AJAXAnswer("Error: an account with this username already exists.", false);
			return $answer->getJSON();
		}
	
		if(strlen($user) > 32){
			$answer = new AJAXAnswer("Error: the username length must be 32 characters or less.", false);
			return $answer->getJSON();
		}
		if(strlen($password) < 6){
			$answer = new AJAXAnswer("Error: the password must be 6 characters or longer", false);
			return $answer->getJSON();
		}
	
		$salt = substr(md5(uniqid(rand(), true)), 0, 16);
		$hash = hash("sha512", $password . $salt);
		$query = $this->database->prepare("INSERT INTO BAT_web (user, password, salt)
				VALUES (:user, :pwd, :salt);");
		$query->execute(array(
				"user" => $user,
				"pwd" => $hash,
				"salt" => $salt));
		$answer = new AJAXAnswer("Account successfully created!", true);
		return $answer->getJSON();
	}
	public function removeAccount($user){
		$query = $this->database->prepare("DELETE FROM BAT_web WHERE user = :user;");
		$query->execute(array("user" => $user));
		if($query->rowCount() > 0){
			$answer = new AJAXAnswer("Account successfully deleted!", true);
			return $answer->getJSON();
		}else{
			$answer = new AJAXAnswer("Error: there is no account with that name!", true);
			return $answer->getJSON();
		}
	}
	public function toogleSU($user){
		$query = $this->database->prepare("UPDATE BAT_web SET superuser = !superuser WHERE user = :user;");
		$query->execute(array("user" => $user));
		if($query->rowCount() > 0){
			$answer = new AJAXAnswer($user . "'s SuperUser rights have been updated!", true);
			return $answer->getJSON();
		}else{
			$answer = new AJAXAnswer("Error: there is no account with that name!", true);
			return $answer->getJSON();
		}
	}
	
	public function listUsers(){
		$query = $this->database->prepare("SELECT * FROM BAT_web;");
		$query->execute();
		$users = array();
		while($row = $query->fetch()){
			$adminProfile = new AdminProfile($row['user'], $row['superuser']);
			$users[] = $adminProfile->getData();
		}
		return $users;
	}
	
}
class AdminProfile{
	private $username;
	private $superuser;
	
	public function __construct($username, $isSU){
		$this->username = $username;
		$this->superuser = $isSU;
	}
	
	public function getData(){
		return array(
			"username" => $this->username,
			"superuser" => $this->superuser
		);
	}
}
