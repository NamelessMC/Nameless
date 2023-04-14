<?php
/**
 * Validates an array of data.
 * Often used for POST requests.
 *
 * @package NamelessMC\Core
 * @author Samerton
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
class Validate {

    /**
     * @var string Ensure this field is not empty
     */
    public const REQUIRED = 'required';

    /**
     * @var string Define minimum number of characters
     */
    public const MIN = 'min';

    /**
     * @var string Define max number of characters
     */
    public const MAX = 'max';

    /**
     * @var string Ensure provided value matches another
     */
    public const MATCHES = 'matches';

    /**
     * @var string Check the user has agreed to the terms and conditions
     */
    public const AGREE = 'agree';

    /**
     * @var string Check the numeric value is at least x
     */
    public const AT_LEAST = 'at_least';

    /**
     * @var string Check the numeric value is at most x
     */
    public const AT_MOST = 'at_most';

    /**
     * @var string Check the value has not already been inputted in the database
     */
    public const UNIQUE = 'unique';

    /**
     * @var string Check if email is valid
     */
    public const EMAIL = 'email';

    /**
     * @var string Check that timezone is valid
     */
    public const TIMEZONE = 'timezone';

    /**
     * @var string Check that the specified user account is set as active (ie validated)
     */
    public const IS_ACTIVE = 'isactive';

    /**
     * @var string Check that the specified user account is not banned
     */
    public const IS_BANNED = 'isbanned';

    /**
     * @var string Check that the value is alphanumeric
     */
    public const ALPHANUMERIC = 'alphanumeric';

    /**
     * @var string Check that the value is numeric
     */
    public const NUMERIC = 'numeric';

    /**
     * @var string Check that the value is in of a set of values
     */
    public const IN = 'in';

    /**
     * @var string Check that the value matches a regex pattern
     */
    public const REGEX = 'regex';

    /**
     * @var string Check that the value does not start with a pattern
     */
    public const NOT_START_WITH = 'not_start_with';

    /**
     * @var string Set a rate limit
     */
    public const RATE_LIMIT = 'rate_limit';

    private DB $_db;

    private ?string $_message = null;
    private array $_messages = [];
    private bool $_passed = false;
    private array $_to_convert = [];
    private array $_errors = [];

    /**
     * Create new `Validate` instance
     */
    private function __construct() {
        // Connect to database for rules which need DB access
        try {
            $host = Config::get('mysql.host');
        } catch (Exception $e) {
            $host = null;
        }

        if (!empty($host)) {
            $this->_db = DB::getInstance();
        }
    }

    /**
     * Validate an array of inputs.
     *
     * @param array $source inputs (eg: $_POST)
     * @param array $items subset of inputs to be validated
     *
     * @return Validate New instance of Validate.
     * @throws Exception If provided configuration for a rule is invalid - not if a provided value is invalid!
     */
    public static function check(array $source, array $items = []): Validate {
        $validator = new Validate();

        // Loop through the items which need validating
        foreach ($items as $item => $rules) {

            // Loop through each validation rule for the set item
            foreach ($rules as $rule => $rule_value) {

                $value = trim($source[$item]);

                // Escape the item's contents just in case
                $item = Output::getClean($item);

                // Required rule
                if ($rule === self::REQUIRED) {
                    $missing = false;
                    // If the item is HTML array syntax, check if it exists within the subarray.
                    // Otherwise, check if it's empty.
                    if (str_contains($item, '[') && str_ends_with($item, ']')) {
                        preg_match('/\[(.*?)\]/', $item, $matches);
                        $array = explode('[', $item)[0];
                        if (empty($source[$array][$matches[1]])) {
                            $missing = true;
                        }
                    } else if (empty($value)) {
                        $missing = true;
                    }

                    if ($missing) {
                        // The post array does not include this value, return an error
                        $validator->addError([
                            'field' => $item,
                            'rule' => self::REQUIRED,
                            'fallback' => "$item is required."
                        ]);
                        continue;
                    }
                }

                if (empty($value)) {
                    continue;
                }

                // The post array does include this value, continue validating
                switch ($rule) {

                    case self::MIN:
                        if (mb_strlen($value) < $rule_value) {
                            $validator->addError([
                                'field' => $item,
                                'rule' => self::MIN,
                                'fallback' => "$item must be a minimum of $rule_value characters."
                            ]);
                        }
                        break;

                    case self::MAX:
                        if (mb_strlen($value) > $rule_value) {
                            $validator->addError([
                                'field' => $item,
                                'rule' => self::MAX,
                                'fallback' => "$item must be a maximum of $rule_value characters."
                            ]);
                        }
                        break;

                    case self::MATCHES:
                        if ($value != $source[$rule_value]) {
                            $validator->addError([
                                'field' => $item,
                                'rule' => self::MATCHES,
                                'fallback' => "$rule_value must match $item."
                            ]);
                        }
                        break;

                    case self::AGREE:
                        if ($value != 1) {
                            $validator->addError([
                                'field' => $item,
                                'rule' => self::AGREE,
                                'fallback' => 'You must agree to our terms and conditions in order to register.'
                            ]);
                        }
                        break;

                    case self::AT_LEAST:
                        if (floatval($value) < $rule_value) {
                            $validator->addError([
                                'field' => $item,
                                'rule' => self::AT_LEAST,
                                'fallback' => "$item must have a value of at least $rule_value.",
                                'meta' => ['min' => $rule_value],
                            ]);
                        }
                        break;

                    case self::AT_MOST:
                        if (floatval($value) > $rule_value) {
                            $validator->addError([
                                'field' => $item,
                                'rule' => self::AT_MOST,
                                'fallback' => "$item must have a value of at most $rule_value.",
                                'meta' => ['max' => $rule_value],
                            ]);
                        }
                        break;

                    case self::UNIQUE:
                        if (is_array($rule_value)) {
                            $table = $rule_value[0];
                            [$ignore_col, $ignore_val] = explode(':', $rule_value[1]);
                            $sql =
                                <<<SQL
                                SELECT *
                                FROM nl2_$table
                                WHERE $item = ?
                                  AND $ignore_col <> ?
                                SQL;

                            $check = $validator->_db->query($sql, [$value, $ignore_val]);
                        } else {
                            $table = $rule_value;
                            $check = $validator->_db->get($table, [$item, $value]);
                        }
                        if ($check->count()) {
                            $validator->addError([
                                'field' => $item,
                                'rule' => self::UNIQUE,
                                'fallback' => "The $rule_value.$item $value already exists!"
                            ]);
                        }
                        break;

                    case self::EMAIL:
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $validator->addError([
                                'field' => $item,
                                'rule' => self::EMAIL,
                                'fallback' => "$value is not a valid email."
                            ]);
                        }
                        break;

                    case self::TIMEZONE:
                        if (!in_array($value, DateTimeZone::listIdentifiers())) {
                            $validator->addError([
                                'field' => $item,
                                'rule' => self::TIMEZONE,
                                'fallback' => "The timezone $value is invalid."
                            ]);
                        }
                        break;

                    case self::IS_ACTIVE:
                        $check = $validator->_db->get('users', [$item, $value]);
                        if (!$check->count()) {
                            break;
                        }

                        $isuseractive = $check->first()->active;
                        if ($isuseractive == 0) {
                            $validator->addError([
                                'field' => $item,
                                'rule' => self::IS_ACTIVE,
                                'fallback' => "That $item is inactive. Have you validated your account or requested a password reset?"
                            ]);
                        }
                        break;

                    case self::IS_BANNED:
                        $check = $validator->_db->get('users', [$item, $value]);
                        if (!$check->count()) {
                            break;
                        }

                        $isuserbanned = $check->first()->isbanned;
                        if ($isuserbanned == 1) {
                            $validator->addError([
                                'field' => $item,
                                'rule' => self::IS_BANNED,
                                'fallback' => "The username $value is banned."
                            ]);
                        }
                        break;

                    case self::ALPHANUMERIC:
                        if (!ctype_alnum($value)) {
                            $validator->addError([
                                'field' => $item,
                                'rule' => self::ALPHANUMERIC,
                                'fallback' => "$item must be alphanumeric."
                            ]);
                        }
                        break;

                    case self::NUMERIC:
                        if (!is_numeric($value)) {
                            $validator->addError([
                                'field' => $item,
                                'rule' => self::NUMERIC,
                                'fallback' => "$item must be numeric."
                            ]);
                        }
                        break;

                    case self::REGEX:
                        if (!preg_match($rule_value, $value)) {
                            $validator->addError([
                                'field' => $item,
                                'rule' => self::REGEX,
                                'fallback' => "$item does not match the pattern $rule_value."
                            ]);
                        }
                        break;

                    case self::NOT_START_WITH:
                        $denied_values = is_string($rule_value) ? [$rule_value] : $rule_value;
                        foreach ($denied_values as $denied_value) {
                            if (str_starts_with($value, $denied_value)) {
                                $validator->addError([
                                    'field' => $item,
                                    'rule' => self::NOT_START_WITH,
                                    'fallback' => "$item must not start with $denied_value."
                                ]);
                            }
                            break;
                        }
                        break;

                    case self::IN:
                        $values = is_string($rule_value) ? [$rule_value] : $rule_value;
                        if (!in_array($value, $values)) {
                            $string_values = implode(', ', $values);
                            $validator->addError([
                                'field' => $item,
                                'rule' => self::IN,
                                'fallback' => "$item must be one of $string_values."
                            ]);
                        }
                        break;

                    case self::RATE_LIMIT:
                        if (is_array($rule_value) && count($rule_value) === 2) {
                            // If array treat as [limit, seconds]
                            [$limit, $seconds] = $rule_value;
                        } else if (is_int($rule_value)) {
                            // If integer default seconds to 60
                            [$limit, $seconds] = [$rule_value, 60];
                        }

                        if (!isset($limit) || !isset($seconds)) {
                            throw new Exception('Invalid rate limit configuration');
                        }

                        $key = "rate_limit_{$item}";
                        $session = $_SESSION[$key];
                        $time = date('U');
                        $limit_end = $time + $seconds;

                        if (isset($session) && is_array($session) && count($session) === 2) {
                            [$count, $expires] = $session;
                            $diff = $expires - $time;

                            if (++$count >= $limit && $diff > 0) {
                                $validator->addError([
                                    'field' => $item,
                                    'rule' => self::RATE_LIMIT,
                                    'fallback' => "$item has reached the rate limit which expires in $diff seconds.",
                                    'meta' => ['expires' => $diff],
                                ]);
                                break;
                            }

                            if ($diff <= 0) {
                                // Reset
                                $_SESSION[$key] = [1, $limit_end];
                                break;
                            }

                            $_SESSION[$key] = [$count, $expires];
                        } else {
                            $_SESSION[$key] = [1, $limit_end];
                        }

                        break;
                }
            }
        }

        if (empty($validator->_to_convert)) {
            // Only return true if there are no errors
            $validator->_passed = true;
        }

        return $validator;
    }

    /**
     * Add an array of information to generate an error message to the $_to_convert array.
     * These errors will be translated in the `errors()` function later.
     *
     * @param array $error message to add to error array
     */
    private function addError(array $error): void {
        $this->_to_convert[] = $error;
    }

    /**
     * Add generic message for any failures, specific `messages()` will override this.
     *
     * @param string $message message to show if any failures occur.
     *
     * @return Validate This instance of Validate.
     */
    public function message(string $message): Validate {
        $this->_message = $message;
        return $this;
    }

    /**
     * Add custom messages to this `Validate` instance.
     *
     * @param array $messages array of input names and strings or arrays to use as messages.
     *
     * @return Validate This instance of Validate.
     */
    public function messages(array $messages): Validate {
        $this->_messages = $messages;
        return $this;
    }

    /**
     * Translate temp error information to their specific or generic or fallback messages and return.
     *
     * @return array Any and all errors for this `Validate` instance.
     */
    public function errors(): array {

        // If errors have already been translated, don't waste time redoing it
        if (!empty($this->_errors)) {
            return $this->_errors;
        }

        // Loop all errors to convert and get their custom messages
        foreach ($this->_to_convert as $error) {

            $message = $this->getMessage($error['field'], $error['rule'], $error['fallback'], $error['meta']);

            // If there is no generic `message()` set or the translated message is not equal to generic message
            // we can continue without worrying about duplications
            if ($this->_message === null || ($message != $this->_message && !in_array($message, $this->_errors))) {
                $this->_errors[] = $message;
                continue;
            }

            // If this new error is the generic message AND it has not already been added, add it
            if ($message == $this->_message && !in_array($this->_message, $this->_errors)) {
                $this->_errors[] = $this->_message;
            }
        }

        return $this->_errors;
    }

    /**
     * Get the error message for a field.
     * Priority:
     *  - Message is set for the field and rule
     *  - Message for field, not rule specific
     *  - Result of callable if "*" rule exists
     *  - Generic message set with `message(...)`
     *  - Fallback message for rule
     *
     * @param string $field name of field to search for.
     * @param string $rule rule which check failed. should be from the constants defined above.
     * @param string $fallback fallback default message if custom message and generic message are not supplied.
     * @param ?array $meta optional meta to provide to message.
     *
     * @return string Message for this field and rule.
     */
    private function getMessage(string $field, string $rule, string $fallback, ?array $meta = []): string {

        // No custom messages defined for this field
        if (!isset($this->_messages[$field])) {
            if (isset($this->_messages['*'])) {
                $message = $this->_messages['*']($field);
                if ($message !== null) {
                    return $message;
                }
            }

            return $this->_message ?? $fallback;
        }

        // Generic custom message for this field supplied - but not rule specific
        if (!is_array($this->_messages[$field])) {
            return $this->_messages[$field];
        }

        // Array of custom messages supplied, but none of their rules matches this rule
        if (!array_key_exists($rule, $this->_messages[$field])) {
            return $this->_message ?? $fallback;
        }

        // If the message is a callback function, provide it with meta
        if (is_callable($this->_messages[$field][$rule])) {
            return $this->_messages[$field][$rule]($meta);
        }

        // Rule-specific custom message was supplied
        return $this->_messages[$field][$rule];
    }

    /**
     * Get if this `Validate` instance passed.
     *
     * @return bool whether this 'Validate' passed or not.
     */
    public function passed(): bool {
        return $this->_passed;
    }

}
