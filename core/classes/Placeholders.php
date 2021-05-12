<?php
/*
 *	Made by Aberdeener
 *
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr10
 *
 *  License: MIT
 *
 *  Plceholders class
 */

class Placeholders {

    /** @var Placeholders */
    private static $_instance = null;

    /** @var DB */
    private $_db = null;

    private $_all_placeholders;
    private $_leaderboard_data;

    public static function getInstance() {
        if(!isset(self::$_instance)) {
            self::$_instance = new Placeholders();
        }

        return self::$_instance;
    }

    public function __construct() {
        $this->_db = DB::getInstance();

        $placeholders_query = $this->_db->get('placeholders_settings', ['name', '<>', ''])->results();
        $placeholders = [];

        foreach ($placeholders_query as $placeholder) {
            $data = new stdClass();

            $sort = $placeholder->leaderboard_sort;

            if (!array_key_exists($sort, ['ASC', 'DESC'])) {
                $sort = 'DESC';
            }

            $data->name = $placeholder->name;
            $data->safe_name = sha1($placeholder->name);
            $data->friendly_name = isset($placeholder->friendly_name) ? $placeholder->friendly_name : $placeholder->name;
            $data->show_on_profile = $placeholder->show_on_profile;
            $data->show_on_forum = $placeholder->show_on_forum;
            $data->leaderboard = $placeholder->leaderboard;
            $data->leaderboard_title = isset($placeholder->leaderboard_title) ? $placeholder->leaderboard_title : $data->friendly_name;
            $data->leaderboard_sort = $sort;
            $data->leaderboard_settings_url = URL::build('/panel/core/placeholders', 'leaderboard=' . $data->safe_name);

            $placeholders[] = $data;
        }

        $this->_all_placeholders = $placeholders;
    }

    /**
     * Get all registered placeholders.
     * 
     * @return array All placeholders.
     */
    public function getAllPlaceholders() {
        return $this->_all_placeholders;
    }

    /**
     * Get placeholder data by name of placeholder.
     * 
     * @param string $placeholder_name Name of placeholder - must be hashed with sha1.
     * @return object|null This placeholder's data, null if not exist.
     */
    public function getPlaceholderByName($placeholder_name) {
        foreach ($this->_all_placeholders as $placeholder) {
            if ($placeholder->safe_name == $placeholder_name) {
                return $placeholder;
            }
        }

        return null;
    }

    /**
     * Create a new row in nl2_placeholders_settings if a row with the "name" of $name does not exist.
     * 
     * @param string $name Name of placeholder
     */
    public function registerPlaceholder($name) {
        $this->_db->query("INSERT IGNORE INTO nl2_placeholders_settings (name) VALUES (?)", [$name]);
    }

    /**
     * Load placeholders for this specific user.
     * 
     * @param string $uuid Their valid Minecraft uuid to use for lookup.
     * @return array Their placeholders.
     */
    public function loadUserPlaceholders($uuid) {

        $user_placeholders = [];

        $placeholders = $this->_db->query('SELECT * FROM nl2_users_placeholders up JOIN nl2_placeholders_settings ps ON up.name = ps.name WHERE up.uuid = ?', [$uuid]);

        if (!$placeholders->count()) {
            return $user_placeholders;
        }

        $placeholders = $placeholders->results();
        foreach ($placeholders as $placeholder) {
            $data = new stdClass();

            $data->server_id = $placeholder->server_id;
            $data->name = Output::getClean($placeholder->name);
            $data->friendly_name = isset($placeholder->friendly_name) ? Output::getClean($placeholder->friendly_name) : Output::getClean($placeholder->name);
            $data->value = Output::getClean($placeholder->value);
            $data->last_updated = $placeholder->last_updated;
            $data->show_on_profile = $placeholder->show_on_profile;
            $data->show_on_forum = $placeholder->show_on_forum;

            $user_placeholders[$data->name] = $data;
        }

        return $user_placeholders;
    }

    /**
     * Get all placeholders which are set to have leaderboards.
     * 
     * @return array Array of placeholders which have leaderboard enabled.
     */
    public function getLeaderboardPlaceholders() {
        return array_filter($this->_all_placeholders, function ($placeholder) {
            return $placeholder->leaderboard;
        });
    }

    /**
     * Get (cached) leaderboard data for a specific leaderboard.
     * 
     * @param string $placeholder_name Unique name of placeholder to get data for.
     * @return array|null Array of data or null if this placeholder is not setup for leaderboards.
     */
    public function getLeaderboardData($placeholder_name) {
        if (!in_array($placeholder_name, array_column($this->getLeaderboardPlaceholders(), 'name'))) {
            return null;
        }

        if (isset($this->_leaderboard_data[$placeholder_name])) {
            return $this->_leaderboard_data[$placeholder_name];
        }

        $sort = $this->getPlaceholderByName($placeholder_name)->leaderboard_sort;

        $leaderboard_data = $this->_db->query("SELECT * FROM nl2_users_placeholders WHERE name = ? ORDER BY value {$sort} LIMIT 50", [$placeholder_name]);

        if (!$leaderboard_data->count()) {
            return [];
        }

        $this->_leaderboard_data[$placeholder_name] = $leaderboard_data->results();

        return $this->_leaderboard_data[$placeholder_name];
    }
}