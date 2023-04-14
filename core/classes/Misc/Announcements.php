<?php
/**
 * Announcement management class for creating and getting announcements.
 *
 * @package NamelessMC\Misc
 * @author Aberdeener
 * @version 2.1.0
 * @license MIT
 */
class Announcements {

    private Cache $_cache;

    public function __construct(Cache $cache) {
        $this->_cache = $cache;
    }

    /**
     * Get all announcements matching the param filters.
     * If they have a cookie set for an announcement, it will be skipped.
     *
     * @param string|null $page Name of the page they're viewing.
     * @param string|null $custom_page Title of custom page they're viewing.
     * @param array $user_groups All this user's groups.
     *
     * @return Announcement[] Array of announcements they should see on this specific page with their groups.
     */
    public function getAvailable(?string $page = null, ?string $custom_page = null, array $user_groups = [0]): iterable {
        $announcements = [];

        foreach ($this->getAll() as $announcement) {
            if (Cookie::exists('announcement-' . $announcement->id)) {
                continue;
            }

            $pages = json_decode($announcement->pages, true);
            $groups = json_decode($announcement->groups, true);

            if ($page === 'api' || in_array($page, $pages) || in_array($custom_page, $pages)) {
                foreach ($user_groups as $group) {
                    if (in_array($group, $groups)) {
                        $announcements[] = $announcement;
                        break;
                    }
                }
            }
        }

        return $announcements;
    }

    /**
     * Get all announcements for listing in StaffCP.
     *
     * @return Announcement[] All announcements.
     */
    public function getAll(): iterable {
        $this->_cache->setCache('custom_announcements');

        if ($this->_cache->isCached('custom_announcements')) {
            return $this->_cache->retrieve('custom_announcements');
        }

        $rows = DB::getInstance()->query('SELECT * FROM nl2_custom_announcements ORDER BY `order`')->results();
        $announcements = [];
        foreach ($rows as $row) {
            $announcements[] = new Announcement($row);
        }

        $this->_cache->store('custom_announcements', $announcements);

        return $this->_cache->retrieve('custom_announcements');
    }

    /**
     * Get all pages which can have announcements on them (they will have a 'name' attribute).
     *
     * @param Pages $pages Instance of Pages class.
     *
     * @return array<string> Name of all pages announcements can be on.
     */
    public static function getPages(Pages $pages): array {
        $available_pages = [];

        foreach ($pages->returnPages() as $page) {
            if (!empty($page['name'])) {
                $available_pages[] = $page;
            }
        }

        return $available_pages;
    }

    /**
     * Get prettified output of the pages a specific announcement is on.
     *
     * @param string|null $pages_json JSON array of pages to implode.
     *
     * @return string Comma seperated list of page names.
     */
    public static function getPagesCsv(?string $pages_json = null): ?string {
        $pages = json_decode($pages_json);

        if (!$pages) {
            return null;
        }

        return implode(', ', array_map('ucfirst', $pages));
    }

    /**
     * Edit an existing announcement.
     *
     * @param int $id ID of announcement to edit.
     * @param array<string> $pages Array of page names this announcement should be on.
     * @param array<int> $groups Array of group IDs this announcement should be visible to.
     * @param string $text_colour Hex code of text colour to use.
     * @param string $background_colour Hex code of background banner colour of announcement.
     * @param string $icon HTML to use to display icon on announcement.
     * @param bool $closable Whether this announcement should have an "x" to close and hide, or be shown 24/7.
     * @param string $header Header text to show at top of announcement.
     * @param string $message Main text to show in announcement.
     * @param int $order Order of this announcement to use for sorting.
     */
    public function edit(int $id, array $pages, array $groups, string $text_colour, string $background_colour, string $icon, bool $closable, string $header, string $message, int $order): bool {
        DB::getInstance()->update('custom_announcements', $id, [
            'pages' => json_encode($pages),
            'groups' => json_encode($groups),
            'text_colour' => $text_colour,
            'background_colour' => $background_colour,
            'icon' => $icon,
            'closable' => $closable ? 1 : 0,
            'header' => $header,
            'message' => $message,
            'order' => $order
        ]);

        $this->resetCache();

        return true;
    }

    /**
     * Erase and regenerate announcement cache file.
     * Used when creating or editing announcements.
     */
    public function resetCache(): void {
        $this->_cache->setCache('custom_announcements');

        if ($this->_cache->isCached('custom_announcements')) {
            $this->_cache->erase('custom_announcements');
        }

        $this->_cache->store('custom_announcements', $this->getAll());
    }

    /**
     * Create an announcement.
     *
     * @param User $user User who is creating the announcement.
     * @param array<string> $pages Array of page names this announcement should be on.
     * @param array<int> $groups Array of group IDs this announcement should be visible to.
     * @param string $text_colour Hex code of text colour to use.
     * @param string $background_colour Hex code of background banner colour of announcement.
     * @param string $icon HTML to use to display icon on announcement.
     * @param bool $closable Whether this announcement should have an "x" to close and hide, or be shown 24/7.
     * @param string $header Header text to show at top of announcement.
     * @param string $message Main text to show in announcement.
     * @param int $order Order of this announcement to use for sorting.
     */
    public function create(User $user, array $pages, array $groups, string $text_colour, string $background_colour, string $icon, bool $closable, string $header, string $message, int $order): bool {
        DB::getInstance()->insert('custom_announcements', [
            'pages' => json_encode($pages),
            'groups' => json_encode($groups),
            'text_colour' => $text_colour,
            'background_colour' => $background_colour,
            'icon' => $icon,
            'closable' => $closable ? 1 : 0,
            'header' => $header,
            'message' => $message,
            'order' => $order
        ]);

        $this->resetCache();

        EventHandler::executeEvent(new AnnouncementCreatedEvent(
            $user,
            $header,
            $message,
        ));

        return true;
    }
}
