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

    public static function parseMembers(array $members, bool $overview = true): array {
        if (Util::getSetting('member_list_hide_banned')) {
            $members = array_filter($members, static function ($member) {
                if (is_array($member)) {
                    $member = $member[0];
                }
                return !$member->data()->isbanned;
            });
        }

        $members = array_slice($members, 0, $overview ? 20 : 5);

        $list_members = [];
        foreach ($members as $member) {
            $count = null;
            if (is_array($member)) {
                [$member, $count] = $member;
            }
            if (!($member instanceof User)) {
                throw new RuntimeException('Provider must return an array of User objects');
            }

            $list_members[] = array_merge(
                [
                    'username' => $member->data()->username,
                    'avatar_url' => $member->getAvatar(32),
                    'group_style' => $member->getGroupStyle(),
                    'profile_url' => $member->getProfileURL(),
                    'count' => $count,
                ],
                !$overview ? [] : [
                    'group' => $member->getMainGroup()->name,
                    'metadata' => MemberList::getInstance()->getMemberMetadata($member),
                ],
            );
        }

        return $list_members;
    }
}
