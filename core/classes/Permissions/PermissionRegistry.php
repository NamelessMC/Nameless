<?php
/**
 * Allows modules to define permissions.
 *
 * @package NamelessMC\Core
 * @author Samerton
 * @version 2.0.0-pr8
 * @license MIT
 */
class PermissionRegistry {

    public const DEFAULT_GROUP_PERMISSIONS = [
        // Member
        1 => ["usercp.messaging" => PermissionTristate::TRUE, "usercp.signature" => PermissionTristate::TRUE, "usercp.nickname" => PermissionTristate::TRUE, "usercp.private_profile" => PermissionTristate::TRUE, "usercp.profile_banner" => PermissionTristate::TRUE],
        // Admin
        2 => ["administrator" => PermissionTristate::TRUE, "admincp.core" => PermissionTristate::TRUE, "admincp.core.api" => PermissionTristate::TRUE, "admincp.core.seo" => PermissionTristate::TRUE, "admincp.core.general" => PermissionTristate::TRUE, "admincp.core.avatars" => PermissionTristate::TRUE, "admincp.core.fields" => PermissionTristate::TRUE, "admincp.core.debugging" => PermissionTristate::TRUE, "admincp.core.emails" => PermissionTristate::TRUE, "admincp.core.queue" => PermissionTristate::TRUE, "admincp.core.navigation" => PermissionTristate::TRUE, "admincp.core.announcements" => PermissionTristate::TRUE, "admincp.core.reactions" => PermissionTristate::TRUE, "admincp.core.registration" => PermissionTristate::TRUE, "admincp.core.social_media" => PermissionTristate::TRUE, "admincp.core.terms" => PermissionTristate::TRUE, "admincp.errors" => PermissionTristate::TRUE, "admincp.core.placeholders" => PermissionTristate::TRUE, "admincp.members" => PermissionTristate::TRUE, "admincp.integrations" => PermissionTristate::TRUE, "admincp.integrations.edit" => PermissionTristate::TRUE, "admincp.discord" => PermissionTristate::TRUE, "admincp.minecraft" => PermissionTristate::TRUE, "admincp.minecraft.authme" => PermissionTristate::TRUE, "admincp.minecraft.servers" => PermissionTristate::TRUE, "admincp.minecraft.query_errors" => PermissionTristate::TRUE, "admincp.minecraft.banners" => PermissionTristate::TRUE, "admincp.modules" => PermissionTristate::TRUE, "admincp.pages" => PermissionTristate::TRUE, "admincp.security" => PermissionTristate::TRUE, "admincp.security.acp_logins" => PermissionTristate::TRUE, "admincp.security.template" => PermissionTristate::TRUE, "admincp.styles" => PermissionTristate::TRUE, "admincp.styles.panel_templates" => PermissionTristate::TRUE, "admincp.styles.templates" => PermissionTristate::TRUE, "admincp.styles.templates.edit" => PermissionTristate::TRUE, "admincp.styles.images" => PermissionTristate::TRUE, "admincp.update" => PermissionTristate::TRUE, "admincp.users" => PermissionTristate::TRUE, "admincp.users.edit" => PermissionTristate::TRUE, "admincp.groups" => PermissionTristate::TRUE, "admincp.groups.self" => PermissionTristate::TRUE, "admincp.widgets" => PermissionTristate::TRUE, "modcp.ip_lookup" => PermissionTristate::TRUE, "modcp.punishments" => PermissionTristate::TRUE, "modcp.punishments.warn" => PermissionTristate::TRUE, "modcp.punishments.ban" => PermissionTristate::TRUE, "modcp.punishments.banip" => PermissionTristate::TRUE, "modcp.punishments.revoke" => PermissionTristate::TRUE, "modcp.reports" => PermissionTristate::TRUE, "modcp.profile_banner_reset" => PermissionTristate::TRUE, "usercp.messaging" => PermissionTristate::TRUE, "usercp.signature" => PermissionTristate::TRUE, "admincp.forums" => PermissionTristate::TRUE, "usercp.private_profile" => PermissionTristate::TRUE, "usercp.nickname" => PermissionTristate::TRUE, "usercp.profile_banner" => PermissionTristate::TRUE, "profile.private.bypass",  "admincp.security.all" => PermissionTristate::TRUE, "admincp.core.hooks" => PermissionTristate::TRUE, "admincp.security.group_sync" => PermissionTristate::TRUE, "admincp.core.emails_mass_message" => PermissionTristate::TRUE, "modcp.punishments.reset_avatar" => PermissionTristate::TRUE, "usercp.gif_avatar"  => PermissionTristate::TRUE],
        // Moderator
        3 => ["modcp.ip_lookup" => PermissionTristate::TRUE, "modcp.punishments" => PermissionTristate::TRUE, "modcp.punishments.warn" => PermissionTristate::TRUE, "modcp.punishments.ban" => PermissionTristate::TRUE, "modcp.punishments.banip" => PermissionTristate::TRUE, "modcp.punishments.revoke" => PermissionTristate::TRUE, "modcp.reports" => PermissionTristate::TRUE, "admincp.users" => PermissionTristate::TRUE, "modcp.profile_banner_reset" => PermissionTristate::TRUE, "usercp.messaging" => PermissionTristate::TRUE, "usercp.signature" => PermissionTristate::TRUE, "usercp.private_profile" => PermissionTristate::TRUE, "usercp.nickname" => PermissionTristate::TRUE, "usercp.profile_banner" => PermissionTristate::TRUE, "profile.private.bypass" => PermissionTristate::TRUE],
        // Unconfirmed Member
        4 => [],
    ];

    /**
     * @var array<string, array<string, string>> All registered permissions.
     */
    private static array $_permissions;

    /**
     *  Register a permission for display in the StaffCP.
     *
     * @param string $section Permission section to add permission to.
     * @param array $permissions List of unique permissions to register.
     */
    public static function registerPermissions(string $section, array $permissions): void {
        foreach ($permissions as $permission => $title) {
            if (!isset(self::$_permissions[$section][$permission])) {
                self::$_permissions[$section][$permission] = $title;
            }
        }
    }

    /**
     * Get all registered permissions.
     *
     * @return array<string, array<string, string>> Permission array.
     */
    public static function getPermissions(): array {
        return self::$_permissions;
    }
}
