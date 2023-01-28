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

    public function getMembers(bool $only): array {
        $members = array_slice($this->generateMembers(), 0, $only ? 20 : 5);

        $list_members = [];
        foreach ($members as $member) {
            $count = null;
            if (is_array($member)) {
                [$member, $count] = $member;
            }
            if (!($member instanceof User)) {
                throw new RuntimeException('Provider must return an array of User objects');
            }

            $list_members[] = array_merge([
                'username' => $member->data()->username,
                'avatar_url' => $member->getAvatar(32),
                'group_style' => $member->getGroupStyle(),
                'profile_url' => $member->getProfileURL(),
                'count' => $count,
            ], !$only ? [] : [
                'group' => $member->getMainGroup()->name,
                'metadata' => MemberList::getInstance()->getMemberMetadata($member),
            ]);
        }

        return $list_members;
    }

    abstract protected function generateMembers(): array;
}