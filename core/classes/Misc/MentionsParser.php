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
    private const USER_MENTIONS_REGEX = '/(?<!\/)@([A-Za-z0-9\-_!.]+)/';

    /**
     * Parse the given content to replace @username tags with [user]<id>[/user] bbcode.
     *
     * @param int     $author_id   User ID of post/custom page creator.
     * @param string  $content     Post/custom page content.
     *
     * @return string Parsed post content.
     */
    public static function parse(int $author_id, string $content): string
    {
        $receipients = self::getRecipients($content, $author_id);

        return self::replaceWithBbcode($content, $receipients);
    }

    public static function parseAndNotify(int $author_id, string $content, string $url, string $notificationType, LanguageKey $notificationTitle): string
    {
        $receipients = self::getRecipients($content, $author_id);

        $notificationRecipients = array_filter($receipients, fn ($receipient) => $receipient->id !== $author_id);
        $notificationRecipients = array_column($notificationRecipients, 'id');

        $notification = new Notification(
            $notificationType,
            $notificationTitle,
            // TODO: emails content - right now it will be plaintext and not use a template
            $content,
            $notificationRecipients,
            $author_id,
            null,
            false,
            $url,
        );

        $notification->send();

        return self::replaceWithBbcode($content, $receipients);
    }

    /**
     * Get users from the database based on the provided nicknames. Filters out users have blocked the author.
     */
    private static function getRecipients(string $content, int $author_id): array
    {
        preg_match_all(self::USER_MENTIONS_REGEX, $content, $matches);
        $nicknames = $matches[1];

        return DB::getInstance()->query(
            'SELECT u.id, u.nickname FROM nl2_users u WHERE u.nickname IN (' . implode(',', array_map(static fn ($_) => '?', $nicknames)) . ') AND NOT EXISTS (SELECT 1 FROM nl2_blocked_users bu WHERE bu.user_id = u.id AND bu.user_blocked_id = ?)', [
            ...$nicknames,
            $author_id,
        ])->results();
    }

    /**
     * Replace @username tags with [user]<id>[/user] bbcode.
     *
     * @param string $content     Post/custom page content.
     * @param array  $receipients Array of user objects (with nickname and ID fields).
     *
     * @return string Parsed post content.
     */
    private static function replaceWithBbcode(string $content, array $receipients): string
    {
        foreach ($receipients as $receipient) {
            $content = preg_replace('/(?<!\/)' . preg_quote("@$receipient->nickname", '/') . '/', '[user]' . $receipient->id . '[/user]', $content);
        }

        return $content;
    }
}
