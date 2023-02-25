<?php

abstract class MemberListProvider {

    private bool $_enabled;
    protected string $_name;
    protected string $_friendly_name;
    protected string $_module;
    protected ?string $_icon = null;

    public function getName(): string {
        return $this->_name;
    }

    public function getFriendlyName(): string {
        return $this->_friendly_name;
    }

    public function getModule(): string {
        return $this->_module;
    }

    public function getIcon(): ?string {
        return $this->_icon;
    }

    public function url(): string {
        return URL::build('/members', 'list=' . $this->getName());
    }

    public function isEnabled(): bool {
        if (isset($this->_enabled)) {
            return $this->_enabled;
        }

        $enabled = DB::getInstance()->get('member_lists', ['name', $this->getName()])->first()->enabled;
        if ($enabled === null) {
            DB::getInstance()->insert('member_lists', [
                'name' => $this->getName(),
                'friendly_name' => $this->getFriendlyName(),
                'module' => $this->getModule(),
                'enabled' => true,
            ]);

            return true;
        }

        return $this->_enabled = $enabled;
    }

    abstract protected function generateMembers(): array;

    public function getMembers(bool $overview, int $page): array {
        [$sql, $id_column, $count_column] = $this->generateMembers();

        $rows = DB::getInstance()->query($sql)->results();
        if (Util::getSetting('member_list_hide_banned', false, 'Members')) {
            $rows = $this->filterBanned($rows, $id_column);
        }

        $rows = array_slice($rows, ($page - 1) * 20);

        $list_members = [];
        $limit = $overview ? 5 : 20;


        foreach ($rows as $row) {
            if (count($list_members) === $limit) {
                break;
            }

            $user_id = $row->{$id_column};
            $member = new User($user_id);

            $list_members[] = array_merge(
                [
                    'username' => $member->data()->username,
                    'avatar_url' => $member->getAvatar(),
                    'group_style' => $member->getGroupStyle(),
                    'profile_url' => $member->getProfileURL(),
                    'count' => $count_column ? $row->{$count_column} : null,
                ],
                $overview
                    ? []
                    : [
                        'group_html' => implode('', $member->getAllGroupHtml()),
                        'metadata' => MemberListManager::getInstance()->getMemberMetadata($member),
                    ],
            );
        }

        return $list_members;
    }

    public function getMemberCount(): int {
        [$sql, $id_column] = $this->generateMembers();
        $rows = DB::getInstance()->query($sql)->results();

        if (Util::getSetting('member_list_hide_banned', false, 'Members')) {
            $rows = $this->filterBanned($rows, $id_column);
        }

        return count($rows);
    }

    private function filterBanned(array $rows, string $id_column): array {
        $ids = implode(',', array_map(static fn ($row) => $row->{$id_column}, $rows));
        $banned = DB::getInstance()->query("SELECT id, isbanned FROM nl2_users WHERE id IN ($ids)")->results();

        return array_filter($rows, fn ($row) => !$this->isBanned($row->{$id_column}, $banned));
    }

    private function isBanned(int $user_id, array $bans): bool {
        foreach ($bans as $ban) {
            if ($ban->id == $user_id) {
                return $ban->isbanned == 1;
            }
        }

        return false;
    }
}
