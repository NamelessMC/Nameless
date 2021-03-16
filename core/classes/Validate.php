<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr10
 *
 *  License: MIT
 *
 *  Validate class
 *
 * 	TODO: Regex validation? Example: Discord
 */

class Validate {

    private $_messages = array();
    private $_passed = false;
    private $_errors = array();
    private $_db = null;

    /**
     * Ensure this field is not empty
     */
    const REQUIRED = 'required';
    /**
     * Define minimum characters
     */
    const MIN = 'min';
    /**
     * Define max characters
     */
    const MAX = 'max';
    /**
     * Ensure provided value matches another
     */
    const MATCHES = 'matches';
    /**
     * Check the user has agreed to the terms and conditions
     */
    const AGREE = 'agree';
    /**
     * Check the value has not already been inputted in the database
     */
    const UNIQUE = 'unique';
    /**
     * Check if email is valid
     */
    const EMAIL = 'email';
    /**
     * Check that timezone is valid
     */
    const TIMEZONE = 'timezone';
    /**
     * Check that the specified user account is set as active (ie validated)
     */
    const IS_ACTIVE = 'isactive';
    /**
     * Check that the specified user account is not banned
     */
    const IS_BANNED = 'isbanned';
    /**
     * Check that the value is alphanumeric
     */
    const ALPHANUMERIC = 'alphanumeric';
    /**
     * Check that the value is numeric
     */
    const NUMERIC = 'numeric';

    // Construct Validate class
    // No parameters
    public function __construct() {
        // Connect to database in order to check whether user's data
        try {
            $host = Config::get('mysql/host');
        } catch (Exception $e) {
            $host = null;
        }

        if (!empty($host)) {
            $this->_db = DB::getInstance();
        }
    }

    // Validate an array of inputs
    // Params: $source (array) - the array containing the form input (eg $_POST)
    //         $items (array)  - contains an array of items which need to be validated
    public function check($source, $items = array()) {

        // Loop through the items which need validating
        foreach ($items as $item => $rules) {

            // Loop through each validation rule for the set item
            foreach ($rules as $rule => $rule_value) {

                $value = trim($source[$item]);

                // Escape the item's contents just in case
                $item = Output::getClean($item);

                // Required rule
                if ($rule === Validate::REQUIRED && empty($value)) {
                    // The post array does not include this value, return an error
                    $this->addError($item, "{$item} is required");

                } else if (!empty($value)) {
                    // The post array does include this value, continue validating
                    switch ($rule) {
                        case Validate::MIN;
                            if (mb_strlen($value) < $rule_value) {
                                $this->addError($this->getMessage($item, "{$item} must be a minimum of {$rule_value} characters."));
                            }
                            break;

                        case Validate::MAX;
                            if (mb_strlen($value) > $rule_value) {
                                $this->addError($this->getMessage($item, "{$item} must be a maximum of {$rule_value} characters."));
                            }
                            break;

                        case Validate::MATCHES;
                            if ($value != $source[$rule_value]) {
                                $this->addError($this->getMessage($item, "{$rule_value} must match {$item}."));
                            }
                            break;

                        case Validate::AGREE;
                            if ($value != 1) {
                                $this->addError($this->getMessage($item, "You must agree to our terms and conditions in order to register."));
                            }
                            break;

                        case Validate::UNIQUE;
                            $check = $this->_db->get($rule_value, array($item, '=', $value));
                            if ($check->count()) {
                                $this->addError($this->getMessage($item, "The username/email {$item} already exists!"));
                            }
                            break;

                        // TODO: Fix isvalid
                        /*
                        case 'isvalid';
                            $username = escape($value);
                            $username = str_replace(' ', '%20', $username);
                            $check_mcUser = file_get_contents('http://www.minecraft.net/haspaid.jsp?user='.($username).'');
                            if($check_mcUser == 'false'){
                                $this->addError("Your Minecraft name is not a valid Minecraft account.");
                            }
                        break;
                        */

                        case Validate::EMAIL;
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $this->addError($this->getMessage($item, "{$value} is not a valid email."));
                            }
                            break;

                        case Validate::TIMEZONE:
                            if (!in_array($value, DateTimeZone::listIdentifiers(DateTimeZone::ALL))) {
                                $this->addError($this->getMessage($item, "The timezone {$item} is invalid."));
                            }
                            break;

                        case Validate::IS_ACTIVE;
                            $check = $this->_db->get('users', array($item, '=', $value));
                            if ($check->count()) {
                                $isuseractive = $check->first()->active;
                                if ($isuseractive == 0) {
                                    $this->addError($this->getMessage($item, "That username is inactive. Have you validated your account or requested a password reset?"));
                                }
                            }
                            break;

                        case Validate::IS_BANNED;
                            $check = $this->_db->get('users', array($item, '=', $value));
                            if ($check->count()) {
                                $isuserbanned = $check->first()->isbanned;
                                if ($isuserbanned == 1) {
                                    $this->addError($this->getMessage($item, "The username {$item} is banned."));
                                }
                            }
                            break;

                        // case 'isbannedip';
                        //     TODO: Check if IP is banned
                        //     break;

                        case Validate::ALPHANUMERIC:
                            if (!ctype_alnum($value)) {
                                $this->addError($this->getMessage($item, "{$item} must be alphanumeric."));
                            }
                            break;

                        case Validate::NUMERIC:
                            if (!is_numeric($value)) {
                                $this->addError($this->getMessage($item, "{$item} must be numeric."));
                            }
                            break;
                    }
                }
            }
        }

        if (empty($this->_errors)) {
            // Only return true if there are no errors
            $this->_passed = true;
        }

        return $this;
    }

    /**
     * Add custom messages to this Validator
     * @param array $messages array of input names and strings or arrays to use as messages
     */
    public function messages($messages) {
        $this->_messages = $messages;
        return $this;
    }

    /**
     * Add an error to the error array
     * @param string $error message to add to error array
     */
    private function addError($error) {
        $this->_errors[] = $error;
    }

    /**
     * Get message for provided field
     * @param string $field name of field to search for 
     * @param string $fallback fallback default message if custom message is not supplied
     */
    private function getMessage($field, $fallback) {

    }

    /**
     * Get current errors, if any
     * @return array Any and all errors for this Validator
     */
    public function errors() {
        return $this->_errors;
    }

    /**
     * Get if this Validator passed
     * @return bool whether this Validator passed or not
     */
    public function passed() {
        return $this->_passed;
    }
}
