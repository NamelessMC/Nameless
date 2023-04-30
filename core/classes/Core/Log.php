<?php
/**
 * Log handling class
 *
 * @package NamelessMC\Core
 * @author Timothy Gibbons
 * @version 2.0.0-pr13
 * @license MIT
 */
class Log extends Instanceable {

    /**
     * @var array Mapping of log Actions to their language key
     */
    private static array $_actions = [
        'admin' => [
            'login' => 'acp_login',
            'core' => [
                'general' => 'acp_core_update',
                'avatar' => 'acp_avatar_update',
                'profile' => [
                    'add' => 'acp_core_profile_add',
                    'delete' => 'acp_core_profile_delete',
                    'update' => 'acp_core_profile_update',
                ],
                'maintenance' => [
                    'enable' => 'acp_maintenance_enable',
                    'disable' => 'acp_maintenance_disable',
                    'update' => 'acp_maintenance_update',
                ],
                'email' => [
                    'update' => 'acp_email_update',
                    'test' => 'acp_email_test',
                    'mass_message' => 'acp_email_mass_message'
                ],
                'nav' => 'admin_nav_update',
                'reaction' => [
                    'update' => 'acp_reaction_update',
                    'add' => 'acp_reaction_add',
                    'delete' => 'acp_reaction_remove'
                ],
                'social' => 'acp_social_update',
                'term' => 'acp_term_update',
                'queue' => [
                    'cancel_task' => 'acp_cancel_task',
                    'requeue_task' => 'acp_requeue_task',
                ],
            ],
            'api' => [
                'change' => 'acp_api_change',
            ],
            'group' => [
                'create' => 'acp_group_create',
                'update' => 'acp_group_update',
                'delete' => 'acp_group_delte',
            ],
            'bgimage' => [
                'submit' => 'acp_bgimage_submit',
                'reset' => 'acp_bgimage_reset',
            ],
            'mc' => [
                'update' => 'acp_mc_update',
            ],
            'authme' => [
                'update' => 'acp_authme_update',
            ],
            'server' => [
                'update' => 'acp_server_update',
                'delete' => 'acp_server_delete',
                'add' => 'acp_server_add',
                'default' => 'acp_server_default_update',
                'banner' => 'acp_server_banner_update',
            ],
            'module' => [
                'install' => 'acp_module_install',
                'enable' => 'acp_module_enable',
                'disable' => 'acp_module_disable',
            ],
            'pages' => [
                'new' => 'acp_pages_new',
                'edit' => 'acp_pages_edit',
                'delete' => 'acp_pages_delete',
            ],
            'template' => [
                'update' => 'acp_template_update',
                'install' => 'acp_template_install',
                'default' => 'acp_template_default_change',
                'activate' => 'acp_template_activate',
                'deactivate' => 'acp_template_deactivate',
                'delete' => 'acp_template_delete',
            ],
            'user' => [
                'create' => 'acp_user_add',
                'delete' => 'acp_user_remove',
                'update' => 'acp_user_update',
                'register' => 'acp_register_change',
            ],
            'widget' => [
                'update' => 'acp_widget_update',
            ],
        ],
        'mod' => [
            'iplookup' => 'mcp_ip_lookup',
            'punishment' => [
                'create' => 'mcp_punishment_create',
                'revoke' => 'mcp_punishment_revoke',
            ],
            'report' => [
                'comment' => 'mcp_report_comment',
                'open' => 'mcp_report_open',
                'close' => 'mcp_report_close',
            ],
        ],
        'user' => [
            'login' => 'user_login',
            'logout' => 'user_logout',
            'register' => 'user_register',
            'acknowledge' => 'user_acknowledge',
            'ucp' => [
                'update' => 'ucp_update',
            ],
            'tfa' => [
                'key' => [
                    'sent' => 'tfa_key_sent',
                ],
            ],
        ],
        'forums' => [
            'topic' => [
                'delete' => 'forums_topic_delete',
                'edit' => 'forums_topic_edit',
                'lock' => 'forums_topic_lock',
                'create' => 'forums_topic_create',
                'search' => 'forums_topic_search',
                'stick' => 'forums_topic_stick',
                'view' => 'forums_topic_view',
                'move' => 'forums_topic_move',
                'merge' => 'forums_topic_merge',
                'react' => 'forums_topic_react',
                'unstick' => 'forum_topic_unstick',
            ],
            'post' => [
                'delete' => 'forums_post_delete',
                'edit' => 'forums_post_edit',
                'lock' => 'forums_post_lock',
                'create' => 'forums_post_create',
                'search' => 'forums_post_search',
                'stick' => 'forums_post_stick',
                'unstick' => 'forum_topic_unstick',
                'view' => 'forums_post_view',
                'move' => 'forums_post_move',
                'merge' => 'forums_post_merge',
                'react' => 'forums_post_react',
            ],
            'delete' => 'forum_delete',
            'edit' => 'forum_edit',
            'lock' => 'forum_lock',
            'create' => 'forum_create',
            'search' => 'forum_search',
            'stick' => 'forum_stick',
            'view' => 'forum_view',
            'move' => 'forum_move',
            'merge' => 'forum_merge',
            'react' => 'forum_react',
        ],
        'misc' => [
            'report' => 'report',
            'curl_error' => 'curl_error'
        ],
        'api' => [
            // TODO
        ],
        'discord' => [
            'bot_request_failed' => 'discord_bot_request_failed',
            'role_set' => 'discord_role_set',
        ],
        'mc_group_sync' => [
            'role_set' => 'mc_group_sync_set'
        ]
    ];

    private DB $_db;

    public function __construct() {
        $this->_db = DB::getInstance();
    }

    /**
     * Get an action from the Action array.
     *
     * @param string $path The path to the action.
     * @return string|array The keys
     */
    public static function Action(string $path) {
        $path = explode('/', $path);
        $config = self::$_actions;

        foreach ($path as $bit) {
            if (isset($config[$bit])) {
                $config = $config[$bit];
            }
        }

        return $config;
    }

    /**
     * Logs an action.
     *
     * @param string $action The action being logged
     * @param string $info Some more information about what the action is about
     * @param ?int $user The User ID who is doing the action
     * @return bool Return true or false if inserted into the database.
     */
    public function log(string $action, string $info = '', ?int $user = null): bool {
        if ($user === null) {
            $userTemp = new User();
            $user = ($userTemp->isLoggedIn() ? $userTemp->data()->id : 0);
        }

        $ip = HttpUtils::getRemoteAddress();

        return $this->_db->insert('logs', [
            'time' => date('U'),
            'action' => $action,
            'user_id' => $user,
            'ip' => $ip ?? 'unknown',
            'info' => $info,
        ]);
    }
}
