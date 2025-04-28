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
     * Parse the given HTML to include @username tags, and send notifications to mentioned users.
     * Will not mention users who have blocked the author.
     * Will not send notifications unless $notificationType is provided.
     *
     * @param int     $author_id   User ID of post/custom page creator.
     * @param string  $value       Post/custom page content.
     * @param ?string $link        Link back to post for alerts.
     * @param ?string $notificationType Type of notification to send.
     * @param ?LanguageKey $notificationTitle Title of alert.
     *
     * @return string Parsed post content.
     */
    public static function parse(int $author_id, string $value, ?string $link = null, ?string $notificationType = null, ?LanguageKey $notificationTitle = null): string
    {
        if (preg_match_all('/(?<!\/)@([A-Za-z0-9\-_!.]+)/', $value, $matches)) {
            $nicknames = $matches[1];
            $receipients = DB::getInstance()->query(
                'SELECT u.id, u.nickname FROM nl2_users u WHERE u.nickname IN (' . implode(',', array_map(static fn ($_) => '?', $nicknames)) . ') AND NOT EXISTS (SELECT 1 FROM nl2_blocked_users bu WHERE bu.user_id = u.id AND bu.user_blocked_id = ?)', [
                ...$nicknames,
                $author_id,
            ])->results();

            // TODO: emails content?
            // We don't always want to send a notification, e.g. if this is called during custom page creation
            if ($notificationType) {
                $notificationRecipients = array_filter($receipients, fn ($receipient) => $receipient->id !== $author_id);
                $notificationRecipients = array_column($notificationRecipients, 'id');

                $notification = new Notification(
                    $notificationType,
                    $notificationTitle,
                    $value,
                    $notificationRecipients,
                    $author_id,
                    null,
                    false,
                    $link,
                );

                $notification->send();
            }

            // Convert the @username mentions to [user] tags _after_ sending the notification, since we want it to be readable in the email content.
            foreach ($receipients as $receipient) {
                $value = preg_replace('/(?<!\/)' . preg_quote("@$receipient->nickname", '/') . '/', '[user]' . $receipient->id . '[/user]', $value);
            }
        }

        return $value;
    }
}
