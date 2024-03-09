<?php

class MassMessage extends Task {

    public function run(): string {
        $limit = 20;

        $start = $this->getFragmentNext() ?? 0;
        $end = $start + $limit;
        $nextStatus = Task::STATUS_READY;

        if ($end > $this->getFragmentTotal()) {
            $end = $this->getFragmentTotal();
            $nextStatus = Task::STATUS_COMPLETED;
        }

        $where = '';
        $whereVars = [];
        if (!empty($this->getData()['users'])) {
            $whereIn = implode(',', array_map(static fn ($u) => '?', $this->getData()['users']));
            $where = "WHERE id IN ($whereIn)";
            $whereVars = array_map(static fn ($u) => $u['id'], $this->getData()['users']);
        }

        $recipients = DB::getInstance()->query(
            <<<SQL
            SELECT `id`
            FROM nl2_users
            $where
            LIMIT $start, 50
            SQL,
            $whereVars
        );

        $notification = new Notification(
            $this->getData()['type'],
            $this->getData()['title'],
            $this->getData()['content'],
            array_map(static fn ($r) => $r->id, $recipients->results()),
            $this->getUserId(),
            $this->getData()['callback'],
            $this->getData()['skip_purify'] ?? false
        );
        $notification->send();

        $this->setOutput(['userIds' => $whereVars, 'start' => $start, 'end' => $end, 'next_status' => $nextStatus]);
        $this->setFragmentNext($end);

        return $nextStatus;
    }

    public static function parseContent(int $userId, string $title, string $content, bool $skipPurify = false): string {
        $user = new User($userId);
        $event = EventHandler::executeEvent(new GenerateNotificationContentEvent($content, $title, $user, $skipPurify));

        return $event['content'];
    }
}
