<?php
/**
 * Mentions parser
 * By: fetch404
 * Date: 7/1/2015
 * License: MIT
 *
 * Modified by Samerton for NamelessMC
 * https://github.com/NamelessMC/Nameless/
 * NamelessMC version 2.0.0-pr9
 */
class MentionsParser {

    /** @var DB */
    private $_db;

    /**
     * Create a new instance of MentionsParser.
     */
    public function __construct() {
        $this->_db = DB::getInstance();
    }

    /**
     * Parse the given HTML to include @username tags.
     * 
     * @param int $author_id User ID of post creator.
     * @param string $value Post content.
     * @param string $link Link back to post.
     * @param string $alert_short Short alert info.
     * @param string $alert_full Full alert info.
     *
     * @throws Exception If alert is not created (see `Alert::create()`).
     *
     * @return string Parsed post content.
     */
    public function parse($author_id, $value, $link, $alert_short, $alert_full) {
        if (preg_match_all("/\@([A-Za-z0-9\-_!\.]+)/", $value, $matches)) {
            $matches = $matches[1];

            foreach ($matches as $possible_username) {
                $user = null;

                while ((strlen($possible_username) > 0) && !$user) {
                    $user = new User($possible_username, 'nickname');

                    if ($user->data()) {
                        $value = preg_replace("/" . preg_quote("@{$possible_username}", "/") . "/", "<a style=\"" . Output::getClean($user->getGroupClass()) . "\" href=\"" . $user->getProfileURL() . "\">@{$possible_username}</a>", $value);

                        // Check if user is blocked by OP
                        if (isset($author_id)) {
                            $user_blocked = $this->_db->get('blocked_users', array('user_id', '=', $user->data()->id));
                            if ($user_blocked->count()) {
                                $user_blocked = $user_blocked->results();

                                foreach ($user_blocked as $item) {
                                    if ($item->user_blocked_id == $author_id) {
                                        break 2;
                                    }
                                }
                            }
                        }

                        Alert::create($user->data()->id, 'tag', $alert_short, $alert_full, $link);

                        break;
                    }

                    // chop last word off of it
                    $new_possible_username = preg_replace("/([^A-Za-z0-9]{1}|[A-Za-z0-9]+)$/", "", $possible_username);
                    if ($new_possible_username !== $possible_username) {
                        $possible_username = $new_possible_username;
                    } else {
                        break;
                    }
                }
            }
        }

        return $value;
    }
}
