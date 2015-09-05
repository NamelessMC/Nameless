<?php
class Validate {
	private $_passed = false,
			$_errors = array(),
			$_db = null;
			
	public function __construct() {
		$host = Config::get('mysql/host');
		if(!empty($host)){
			$this->_db = DB::getInstance();
		}
	}
	
	public function check($source, $items = array()) {
		foreach($items as $item => $rules) {
			foreach($rules as $rule => $rule_value) {
				
				$value = trim($source[$item]);
				$item = escape($item);
				
				if($rule === 'required' && empty($value)) {
					$this->addError("{$item} is required");
				} else if(!empty($value)) {
					switch($rule){
						case 'min';
							if(strlen($value) < $rule_value) {
								$this->addError("{$item} must be a minimum of {$rule_value} characters.");
							}
						break;
						case 'max';
							if(strlen($value) > $rule_value) {
								$this->addError("{$item} must be a maximum of {$rule_value} characters.");
							}							
						break;
						case 'matches';
							if($value != $source[$rule_value]) {
								$this->addError("{$rule_value} must match {$item}.");
							}
						break;
						case 'equals';
							// TODO : Finish question/answer
							if(strtolower($value) != "answer") {
								$this->addError("The question was not answered correctly.");
							}
						break;
						case 'agree';
							if($value != "1") {
								$this->addError("You must agree to our terms and conditions in order to register.");
							}
						break;
						case 'unique';
							$check = $this->_db->get($rule_value, array($item, '=', $value));
							if($check->count()){
								$this->addError("The username/email {$item} already exists!");
							}
						break;
						case 'isvalid';
							$username = escape($value);
							$username = str_replace(' ', '%20', $username);
							$check_mcUser = file_get_contents('http://www.minecraft.net/haspaid.jsp?user='.($username).'');
							if ($check_mcUser == 'false') {
								$this->addError("Your Minecraft name is not a valid Minecraft account.");
							}
						break;
						case 'isactive';
							$check = $this->_db->get('users', array($item, '=', $value));
							if($check->count()) {
								$isuseractive = $check->first()->active;
								if($isuseractive == 0) {
									$this->addError("That username is inactive. Have you requested a password reset?");
								}
								else {}
							}
						break;
						case 'isbanned';
							$check = $this->_db->get('users', array($item, '=', $value));
							if($check->count()) {
								$isuserbanned = $check->first()->isbanned;
								if($isuserbanned == 1) {
									$this->addError("The username {$item} is banned.");
								}
								else {}
							}
						break;
						case 'isbannedip';
							// check if IP is banned
						break;
					}
				}
				
			}
		}
		
		if(empty($this->_errors)) {
			$this->_passed = true;
		}
		
		return $this;
		
	}
	
	private function addError($error) {
		$this->_errors[] = $error;
	}
	
	public function errors() {
		return $this->_errors;
	}
	
	public function passed() {
		return $this->_passed;
	}
	
}