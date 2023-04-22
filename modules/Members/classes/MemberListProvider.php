<?php

/**
 * Base class for member list providers.
 *
 * @package Modules\Members
 * @author Aberdener
 * @version 2.1.0
 * @license MIT
 */
abstract class MemberListProvider {

    private bool $_enabled;
    protected string $_name;
    protected string $_friendly_name;
    protected string $_module;
    protected ?string $_icon = null;
    protected bool $_display_on_overview = true;

    /**
     * @return string The name of the member list provider. This will be used for URLs and database queries.
     */
    public function getName(): string {
        return $this->_name;
    }

    /**
     * @return string The friendly name of the member list provider, displayed in navigation and user-facing pages
     */
    public function getFriendlyName(): string {
        return $this->_friendly_name;
    }

    /**
     * @return string The name of the module that the member list provider belongs to
     */
    public function getModule(): string {
        return $this->_module;
    }

    /**
     * @return string|null The icon to display next to the member list provider's name in the members page navigation sidebar
     */
    public function getIcon(): ?string {
        return $this->_icon;
    }

    /**
     * @return bool Whether the member list provider should be displayed on the member list overview page
     */
    public function displayOnOverview(): bool {
        return $this->_display_on_overview;
    }

    /**
     * @return string A URL to this specific member list page
     */
    public function url(): string {
        return URL::build('/members', 'list=' . $this->getName());
    }

    /**
     * Determine whether the member list provider is enabled or not. Will automatically enable the member list if it is not already enabled.
     * @return bool Whether the member list provider is enabled
     */
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

    /**
     * Get the information needed to generate the member list.
     *
     * @return array An array containing the SQL query to run, the column name of the user ID, and optionally the
     * column name of the "count" value for this list. Count values are used to display the number of posts, likes, etc.
     */
    abstract protected function generator(): array;

    /**
     * Get an array of members to display on the member list page.
     *
     * @param bool $overview Whether the member list is being displayed on the overview page. If true, only 5 members will be returned, otherwise 20.
     * @param int $page The page number to display, starting at 1 - pages are 20 members long
     * @return array An array of members to display on the member list page
     */
    public function getMembers(bool $overview, int $page): array {
        [$sql, $id_column, $count_column] = $this->generator();

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
                    'username' => Output::getClean($member->data()->username),
                    'avatar_url' => $member->getAvatar(),
                    'group_style' => $member->getGroupStyle(),
                    'profile_url' => $member->getProfileURL(),
                    'count' => $count_column ? $row->{$count_column} : null,
                ],
                $overview
                    ? []
                    : [
                        'group_html' => $member->getAllGroupHtml(),
                        'metadata' => MemberListManager::getInstance()->getMemberMetadata($member),
                    ],
            );
        }

        return $list_members;
    }

    /**
     * Determine the total number of members in this list.
     * @return int The total number of members in this list
     */
    public function getMemberCount(): int {
        [$sql, $id_column] = $this->generator();
        $rows = DB::getInstance()->query($sql)->results();

        if (Util::getSetting('member_list_hide_banned', false, 'Members')) {
            $rows = $this->filterBanned($rows, $id_column);
        }

        return count($rows);
    }

    /**
     * Filter out banned users from a list of members.
     *
     * @param array $rows Rows returned from the member list query
     * @param string $id_column The name of the column in each row containing the user ID
     * @return array The rows with banned users filtered out
     */
    private function filterBanned(array $rows, string $id_column): array {
        $ids = implode(',', array_map(static fn ($row) => $row->{$id_column}, $rows));
        if (empty($ids)) {
            return [];
        }

        $banned_users = DB::getInstance()->query("SELECT id, isbanned FROM nl2_users WHERE id IN ($ids) AND isbanned = 1")->results();
        return array_filter($rows, static function ($row) use ($banned_users, $id_column) {
            foreach ($banned_users as $banned_user) {
                if ($banned_user->id == $row->{$id_column}) {
                    return false;
                }
            }

            return true;
        });
    }
}
