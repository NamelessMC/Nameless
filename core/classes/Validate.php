<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr4
 *
 *  License: MIT
 *
 *  Validate class
 */
class Validate {

	// Variables
	private $_passed = false,
			$_errors = array(),
			$_db = null;

	// Construct Validate class
	// No parameters
	public function __construct(){
		// Connect to database in order to check whether user's data
		try {
			$host = Config::get('mysql/host');
		}
		catch(Exception $e) {
			$host = null;
		}

		if(!empty($host)){
			$this->_db = DB::getInstance();
		}
	}

	// Validate an array of inputs
	// Params: $source (array) - the array containing the form input (eg $_POST)
    //         $items (array)  - contains an array of items which need to be validated
	public function check($source, $items = array()){

		// Loop through the items which need validating
		foreach($items as $item => $rules) {

			// Loop through each validation rule for the set item
			foreach($rules as $rule => $rule_value){

				$value = trim($source[$item]);

				// Escape the item's contents just in case
				$item = Util::escape($item);

				// Required rule
				if($rule === 'required' && empty($value)){
					// The post array does not include this value, return an error
					$this->addError("{$item} is required");

				} else if(!empty($value)){
					// The post array does include this value, continue validating
					switch($rule){
						// Minimum of $rule_value characters
						case 'min';
							if(strlen($value) < $rule_value){
								// Not a minumum of $rule_value characters, return an error
								$this->addError("{$item} must be a minimum of {$rule_value} characters.");
							}
						break;

						// Maximum of $rule_value characters
						case 'max';
							if(strlen($value) > $rule_value) {
								// Above the maximum of $rule_value characters, return an error
								$this->addError("{$item} must be a maximum of {$rule_value} characters.");
							}
						break;

						// Check value matches another value
						case 'matches';
							if($value != $source[$rule_value]){
								// Value does not match, return an error
								$this->addError("{$rule_value} must match {$item}.");
							}
						break;

						// Check the user has agreed to the terms and conditions
						case 'agree';
							if($value != 1){
								// The user has not agreed, return an error
								$this->addError("You must agree to our terms and conditions in order to register.");
							}
						break;

						// Check the value has not already been inputted in the database
						case 'unique';
							$check = $this->_db->get($rule_value, array($item, '=', $value));
							if($check->count()){
								// The value has already been inputted, return an error
								$this->addError("The username/email {$item} already exists!");
							}
						break;

						/*
						 * TODO: Fix isvalid
						 *
						case 'isvalid';
							$username = escape($value);
							$username = str_replace(' ', '%20', $username);
							$check_mcUser = file_get_contents('http://www.minecraft.net/haspaid.jsp?user='.($username).'');
							if($check_mcUser == 'false'){
								$this->addError("Your Minecraft name is not a valid Minecraft account.");
							}
						break;
						*/

						// Check if email is valid
						case 'email';
							if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
								// Value is not a valid email
								$this->addError("That is not a valid email.");
							}
						break;


						// Check that the specified user account is set as active (ie validated)
						case 'isactive';
							$check = $this->_db->get('users', array($item, '=', $value));
							if($check->count()){
								$isuseractive = $check->first()->active;
								if($isuseractive == 0) {
									// Not active, return an error
									$this->addError("That username is inactive. Have you validated your account or requested a password reset?");
								}
							}
						break;

						// Check that the specified user account is not banned
						case 'isbanned';
							$check = $this->_db->get('users', array($item, '=', $value));
							if($check->count()){
								$isuserbanned = $check->first()->isbanned;
								if($isuserbanned == 1){
									// The user is banned, return an error
									$this->addError("The username {$item} is banned.");
								}
							}
						break;

						case 'isbannedip';
							// Todo: Check if IP is banned
						break;

						case 'alphanumeric':
							if(!ctype_alnum($value)){
								// $value is not alphanumeric
								$this->addError("{$item} must be alphanumeric.");
							}
							break;
					}
				}

			}
		}

		if(empty($this->_errors)){
			// Only return true if there are no errors
			$this->_passed = true;
		}

		return $this;

	}

	// Add an error to the error array
	// No parameters
	private function addError($error){
		$this->_errors[] = $error;
	}

	// Return the array of errors
	// No parameters
	public function errors(){
		return $this->_errors;
	}

	// Return whether the validation passed (true or false)
	// No parameters
	public function passed(){
		return $this->_passed;
	}
}
