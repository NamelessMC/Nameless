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
class MentionsParser
{
    /**
     * Parse the given HTML to include @username tags.
     *
     * @param int     $author_id   User ID of post creator.
     * @param string  $value       Post content.
     * @param ?string $link        Link back to post.
     * @param ?string $notificationType Type of notification to send.
     * @param ?LanguageKey $alertTitle Title of alert.
     *
     * @return string Parsed post content.
     */
    public static function parse(int $author_id, string $value, ?string $link = null, ?string $notificationType = null, ?LanguageKey $title = null): string
    {
        if (preg_match_all('/(?<!\/)@([A-Za-z0-9\-_!.]+)/', $value, $matches)) {
            $nicknames = $matches[1];
            $receipients = self::getReceipients($nicknames, $author_id);

            foreach ($receipients as $receipient) {
                $value = preg_replace('/(?<!\/)' . preg_quote("@$receipient->nickname", '/') . '/', '[user]' . $receipient->id . '[/user]', $value);
            }

            // TODO: emails content?
            // We don't always want to send a notification, e.g. if this is called during custom page creation
            if ($notificationType) {
                $notification = new Notification(
                    $notificationType,
                    $title,
                    $value,
                    $receipients,
                    $author_id,
                    null,
                    false,
                    $link,
                );

                $notification->send();
            }
        }

        return $value;
    }

    private static function getReceipients(array $nicknames, int $authorId): array
    {
        return DB::getInstance()->query(
            'SELECT u.id, u.nickname FROM nl2_users u WHERE u.nickname IN (' . implode(',', array_map(static fn ($_) => '?', $nicknames)) . ') AND u.id != ? AND NOT EXISTS (SELECT 1 FROM nl2_users_blocked ub WHERE ub.user_id = u.id AND ub.blocked_user_id = ?)',
            $nicknames,
            $authorId,
            $authorId,
        )->results();
    }
}
