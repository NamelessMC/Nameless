<?php
/**
 * Handles parsing username mentions in forum posts.
 *
 * @package NamelessMC\Misc
 * @author Samerton
 * @author fetch404
 * @version 2.0.0-pr13
 * @license MIT
 */
class MentionsParser {

    /**
     * Parse the given HTML to include @username tags.
     *
     * @param int $author_id User ID of post creator.
     * @param string $value Post content.
     * @param ?string $link Link back to post.
     * @param ?array $alert_short Short alert info, leave null to not alert user.
     * @param ?array $alert_full Full alert info, leave null to not alert user.
     *
     * @return string Parsed post content.
     */
    public static function parse(int $author_id, string $value, string $link = null, array $alert_short = null, array $alert_full = null): string {
        if (preg_match_all('/@([A-Za-z0-9\-_!.]+)/', $value, $matches)) {
            $matches = $matches[1];

            foreach ($matches as $possible_username) {
                $user = null;

                while (($possible_username != '') && !$user) {
                    $user = new User($possible_username, 'nickname');

                    if ($user->exists()) {
                        $value = preg_replace('/' . preg_quote("@$possible_username", '/') . '/', '[user]' . $user->data()->id . '[/user]', $value);

                        // Check if user is blocked by OP
                        if ($link && ($alert_full && $alert_short) && ($user->data()->id != $author_id) && !$user->isBlocked($user->data()->id, $author_id)) {
                            Alert::create($user->data()->id, 'tag', $alert_short, $alert_full, $link);
                            break;
                        }
                    }

                    // chop last word off of it
                    $new_possible_username = preg_replace('/([^A-Za-z0-9]|[A-Za-z0-9]+)$/', '', $possible_username);
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
