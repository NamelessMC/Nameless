<?php

class DefaultUserNotificationPreferencesHook {

    public static function execute(UserRegisteredEvent $event): void {

        $userId = $event->user->data()->id;

        if (!$userId) {
            return;
        }

        self::subscribeUserToDefaultNotifications($userId);
    }

    public static function subscribeUserToDefaultNotifications(int $userId): void {

        $defaultNotifications = array_filter(
            Notification::getTypes(),
            static fn($type) => $type['defaultPreferences']['alert'] || $type['defaultPreferences']['email']
        );

        foreach ($defaultNotifications as $notificationType) {
            DB::getInstance()->insert('users_notification_preferences', [
                'user_id' => $userId,
                'type' => $notificationType['key'],
                'alert' => $notificationType['defaultPreferences']['alert'] === true,
                'email' => $notificationType['defaultPreferences']['email'] === true,
            ]);
        }
    }
}
