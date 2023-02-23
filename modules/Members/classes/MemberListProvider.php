<?php

abstract class MemberListProvider {

    private bool $_enabled;
    protected string $_name;
    protected string $_friendly_name;
    protected string $_module;
    protected ?string $_icon = null;

    abstract public function __construct(Language $language);

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

    public function getMembers(bool $overview): array {
        return self::parseMembers(
            $this->generateMembers(),
            $overview
        );
    }

    abstract protected function generateMembers(): array;

    public static function parseMembers(array $generator, bool $overview = true): array {
        [$sql, $id_column, $count_column] = $generator;

        $rows = DB::getInstance()->query($sql)->results();
        $list_members = [];
        $limit = $overview ? 5 : 20;

        foreach ($rows as $row) {
            if (count($list_members) >= $limit) {
                break;
            }

            $user_id = $row->{$id_column};
            if (Util::getSetting('member_list_hide_banned', false, 'Members')) {
                $is_banned = DB::getInstance()->get('users', ['id', $user_id])->first()->isbanned == 1;
                if ($is_banned) {
                    continue;
                }
            }

            $member = new User($user_id);

            $list_members[] = array_merge(
                [
                    'username' => $member->data()->username,
                    'avatar_url' => $member->getAvatar(),
                    'group_style' => $member->getGroupStyle(),
                    'profile_url' => $member->getProfileURL(),
                    'count' => $count_column ? $row->{$count_column} : null,
                ],
                $overview ? [] : [
                    'group' => $member->getMainGroup()->name,
                    'group_html' => implode('', $member->getAllGroupHtml()),
                    'metadata' => MemberList::getInstance()->getMemberMetadata($member),
                ],
            );
        }

        return $list_members;
    }
}
