<?php

if ($s < 9) {
    $conn = DB_Custom::getInstance($_SESSION['db_address'], $_SESSION['db_name'], $_SESSION['db_username'], $_SESSION['db_password'], $_SESSION['db_port']);
}

$queries = new Queries();
$cache = new Cache();

switch ($s) {

    case 0:
        // Alerts -> custom page permissions
        // Alerts
        try {
            $old = $conn->get('nl1_alerts', array('id', '<>', 0));
            if ($old->count()) {
                $old = $old->results();

                foreach ($old as $item) {
                    $queries->create('alerts', array(
                        'id' => $item->id,
                        'user_id' => $item->user_id,
                        'type' => $item->type,
                        'url' => $item->url,
                        'content' => $item->content,
                        'content_short' => ((strlen($item->content) > 64) ? substr($item->content, 0, 64) : $item->content),
                        'created' => $item->created,
                        'read' => $item->read
                    ));
                }
            }
        } catch (Exception $e) {
            $errors[] = 'Unable to convert alerts: ' . $e->getMessage();
        }

        // Custom pages
        try {
            $old = $conn->get('nl1_custom_pages', array('id', '<>', 0));
            if ($old->count()) {
                $old = $old->results();

                foreach ($old as $item) {
                    $queries->create('custom_pages', array(
                        'id' => $item->id,
                        'url' => $item->url,
                        'title' => $item->title,
                        'content' => $item->content,
                        'link_location' => $item->link_location,
                        'redirect' => $item->redirect,
                        'link' => $item->link
                    ));
                }
            }
        } catch (Exception $e) {
            $errors[] = 'Unable to convert custom pages: ' . $e->getMessage();
        }

        // Custom page permissions
        try {
            $old = $conn->get('nl1_custom_pages_permissions', array('id', '<>', 0));
            if ($old->count()) {
                $old = $old->results();

                foreach ($old as $item) {
                    $queries->create('custom_pages_permissions', array(
                        'id' => $item->id,
                        'page_id' => $item->page_id,
                        'group_id' => $item->group_id,
                        'view' => $item->view
                    ));
                }
            }
        } catch (Exception $e) {
            $errors[] = 'Unable to convert custom page permissions: ' . $e->getMessage();
        }

        break;

    case 1:
        // Forums -> groups
        // Forums
        try {
            $old = $conn->get('nl1_forums', array('id', '<>', 0));
            if ($old->count()) {
                $old = $old->results();

                foreach ($old as $item) {
                    $queries->create('forums', array(
                        'id' => $item->id,
                        'forum_title' => $item->forum_title,
                        'forum_description' => $item->forum_description,
                        'forum_type' => $item->forum_type,
                        'last_post_date' => ($item->last_post_date) ? strtotime($item->last_post_date) : null,
                        'last_user_posted' => $item->last_user_posted,
                        'last_topic_posted' => $item->last_topic_posted,
                        'parent' => $item->parent,
                        'forum_order' => $item->forum_order,
                        'news' => $item->news
                    ));
                }
            }
        } catch (Exception $e) {
            $errors[] = 'Unable to convert forums: ' . $e->getMessage();
        }

        // Forum permissions
        try {
            $old = $conn->get('nl1_forums_permissions', array('id', '<>', 0));
            if ($old->count()) {
                $old = $old->results();

                foreach ($old as $item) {
                    $queries->create('forums_permissions', array(
                        'id' => $item->id,
                        'group_id' => $item->group_id,
                        'forum_id' => $item->forum_id,
                        'view' => $item->view,
                        'create_topic' => $item->create_topic,
                        'create_post' => $item->create_post,
                        'view_other_topics' => 1
                    ));
                }
            }
        } catch (Exception $e) {
            $errors[] = 'Unable to convert forum permissions: ' . $e->getMessage();
        }

        // Forum topic labels
        try {
            $old = $conn->get('nl1_forums_topic_labels', array('id', '<>', 0));
            if ($old->count()) {
                $old = $old->results();

                foreach ($old as $item) {
                    $queries->create('forums_topic_labels', array(
                        'id' => $item->id,
                        'fids' => $item->fids,
                        'name' => $item->name,
                        'label' => $item->label
                    ));
                }
            }
        } catch (Exception $e) {
            $errors[] = 'Unable to convert forum topic labels: ' . $e->getMessage();
        }

        // Friends/followers
        try {
            $old = $conn->get('nl1_friends', array('id', '<>', 0));
            if ($old->count()) {
                $old = $old->results();

                foreach ($old as $item) {
                    $queries->create('friends', array(
                        'id' => $item->id,
                        'user_id' => $item->user_id,
                        'friend_id' => $item->friend_id
                    ));
                }
            }
        } catch (Exception $e) {
            $errors[] = 'Unable to convert friends: ' . $e->getMessage();
        }

        // Groups
        try {
            $old = $conn->get('nl1_groups', array('id', '<>', 0));
            if ($old->count()) {
                $old = $old->results();

                foreach ($old as $item) {
                    $queries->create('groups', array(
                        'id' => $item->id,
                        'name' => $item->name,
                        'group_html' => $item->group_html,
                        'group_html_lg' => $item->group_html_lg,
                        'admin_cp' => (($item->staff) ? 1 : 0),
                        'default_group' => (($item->id == 1) ? 1 : 0)
                    ));
                }

                $queries->update('groups', 1, array('permissions' => '{"usercp.messaging":1,"usercp.signature":1,"usercp.nickname":1,"usercp.private_profile":1,"usercp.profile_banner":1}'));
                $queries->update('groups', 2, array('permissions' => '{"admincp.core":1,"admincp.core.api":1,"admincp.core.seo":1,"admincp.core.general":1,"admincp.core.avatars":1,"admincp.core.fields":1,"admincp.core.debugging":1,"admincp.core.emails":1,"admincp.core.navigation":1,"admincp.core.announcements":1,"admincp.core.reactions":1,"admincp.core.registration":1,"admincp.core.social_media":1,"admincp.core.terms":1,"admincp.errors":1,"admincp.integrations":1,"admincp.discord":1,"admincp.minecraft":1,"admincp.minecraft.authme":1,"admincp.minecraft.verification":1,"admincp.minecraft.servers":1,"admincp.minecraft.query_errors":1,"admincp.minecraft.banners":1,"admincp.modules":1,"admincp.pages":1,"admincp.security":1,"admincp.security.acp_logins":1,"admincp.security.template":1,"admincp.styles":1,"admincp.styles.panel_templates":1,"admincp.styles.templates":1,"admincp.styles.templates.edit":1,"admincp.styles.images":1,"admincp.update":1,"admincp.users":1,"admincp.users.edit":1,"admincp.groups":1,"admincp.groups.self":1,"admincp.widgets":1,"modcp.ip_lookup":1,"modcp.punishments":1,"modcp.punishments.warn":1,"modcp.punishments.ban":1,"modcp.punishments.banip":1,"modcp.punishments.revoke":1,"modcp.reports":1,"modcp.profile_banner_reset":1,"usercp.messaging":1,"usercp.signature":1,"admincp.forums":1,"usercp.private_profile":1,"usercp.nickname":1,"usercp.profile_banner":1,"profile.private.bypass":1, "admincp.security.all":1,"admincp.core.hooks":1,"admincp.core.emails_mass_message":1,"usercp.gif_avatar":1}'));
                $queries->update('groups', 3, array('permissions' => '{"modcp.ip_lookup":1,"modcp.punishments":1,"modcp.punishments.warn":1,"modcp.punishments.ban":1,"modcp.punishments.banip":1,"modcp.punishments.revoke":1,"modcp.reports":1,"admincp.users":1,"modcp.profile_banner_reset":1,"usercp.messaging":1,"usercp.signature":1,"usercp.private_profile":1,"usercp.nickname":1,"usercp.profile_banner":1,"profile.private.bypass":1}'));
            }
        } catch (Exception $e) {
            $errors[] = 'Unable to convert groups: ' . $e->getMessage();
        }

        break;

    case 2:
        // Infractions -> posts
        // Infractions
        try {
            $old = $conn->get('nl1_infractions', array('id', '<>', 0));
            if ($old->count()) {
                $old = $old->results();

                foreach ($old as $item) {
                    $queries->create('infractions', array(
                        'id' => $item->id,
                        'type' => $item->type,
                        'punished' => $item->punished,
                        'staff' => $item->staff,
                        'reason' => $item->reason,
                        'infraction_date' => $item->infraction_date,
                        'acknowledged' => $item->acknowledged
                    ));
                }
            }
        } catch (Exception $e) {
            $errors[] = 'Unable to convert site punishments: ' . $e->getMessage();
        }

        // Minecraft servers
        try {
            $old = $conn->get('nl1_mc_servers', array('id', '<>', 0));
            if ($old->count()) {
                $old = $old->results();

                foreach ($old as $item) {
                    $queries->create('mc_servers', array(
                        'id' => $item->id,
                        'ip' => $item->ip,
                        'query_ip' => $item->query_ip,
                        'name' => $item->name,
                        'is_default' => $item->is_default,
                        'display' => $item->display,
                        'pre' => $item->pre,
                        'player_list' => $item->player_list
                    ));
                }
            }
        } catch (Exception $e) {
            $errors[] = 'Unable to convert Minecraft servers: ' . $e->getMessage();
        }

        // Posts
        try {
            $old = $conn->get('nl1_posts', array('id', '<>', 0));
            if ($old->count()) {
                $old = $old->results();

                foreach ($old as $item) {
                    $queries->create('posts', array(
                        'id' => $item->id,
                        'forum_id' => $item->forum_id,
                        'topic_id' => $item->topic_id,
                        'post_creator' => $item->post_creator,
                        'post_content' => $item->post_content,
                        'created' => strtotime($item->post_date),
                        'deleted' => $item->deleted
                    ));
                }
            }
        } catch (Exception $e) {
            $errors[] = 'Unable to convert posts: ' . $e->getMessage();
        }

        break;

    case 3:
        // Private messages -> private message users
        // Private messages
        try {
            $old = $conn->get('nl1_private_messages', array('id', '<>', 0));
            if ($old->count()) {
                $old = $old->results();

                foreach ($old as $item) {
                    $queries->create('private_messages', array(
                        'id' => $item->id,
                        'author_id' => $item->author_id,
                        'title' => $item->title,
                        'created' => 0, // will update later
                        'last_reply_user' => $item->author_id, // will update later
                        'last_reply_date' => $item->updated
                    ));
                }
            }
        } catch (Exception $e) {
            $errors[] = 'Unable to convert private messages: ' . $e->getMessage();
        }

        // Private message replies
        $private_messages = array();
        try {
            $old = $conn->get('nl1_private_messages_replies', array('id', '<>', 0));
            if ($old->count()) {
                $old = $old->results();

                foreach ($old as $item) {
                    if (!isset($private_messages[$item->pm_id])) {
                        $private_messages[$item->pm_id] = array(
                            'created' => $item->created,
                            'updated' => $item->created,
                            'last_reply_user' => $item->user_id
                        );
                    } else {
                        if ($private_messages[$item->pm_id]['created'] > $item->created)
                            $private_messages[$item->pm_id]['created'] = $item->created;

                        else if ($private_messages[$item->pm_id]['updated'] < $item->created) {
                            $private_messages[$item->pm_id]['updated'] = $item->created;
                            $private_messages[$item->pm_id]['last_reply_user'] = $item->user_id;
                        }
                    }

                    $queries->create('private_messages_replies', array(
                        'id' => $item->id,
                        'pm_id' => $item->pm_id,
                        'author_id' => $item->user_id,
                        'created' => $item->created,
                        'content' => $item->content
                    ));
                }
            }
        } catch (Exception $e) {
            $errors[] = 'Unable to convert private message replies: ' . $e->getMessage();
        }

        // Private message users
        try {
            $old = $conn->get('nl1_private_messages_users', array('id', '<>', 0));
            if ($old->count()) {
                $old = $old->results();

                foreach ($old as $item) {
                    $queries->create('private_messages_users', array(
                        'id' => $item->id,
                        'pm_id' => $item->pm_id,
                        'user_id' => $item->user_id,
                        'read' => $item->read
                    ));
                }
            }
        } catch (Exception $e) {
            $errors[] = 'Unable to convert private message users: ' . $e->getMessage();
        }

        // Update private message columns
        foreach ($private_messages as $key => $message) {
            try {
                $queries->update('private_messages', $key, array(
                    'created' => $message['created'],
                    'last_reply_user' => $message['last_reply_user']
                ));
            } catch (Exception $e) {
                $errors[] = 'Unable to convert update private message columns: ' . $e->getMessage();
            }
        }

        break;

    case 4:
        // Query errors -> settings
        // Query errors
        try {
            $old = $conn->get('nl1_query_errors', array('id', '<>', 0));
            if ($old->count()) {
                $old = $old->results();

                foreach ($old as $item) {
                    $queries->create('query_errors', array(
                        'id' => $item->id,
                        'date' => $item->date,
                        'error' => $item->error,
                        'ip' => $item->ip,
                        'port' => $item->port
                    ));
                }
            }
        } catch (Exception $e) {
            $errors[] = 'Unable to convert query errors: ' . $e->getMessage();
        }

        // Reports
        try {
            $old = $conn->get('nl1_reports', array('id', '<>', 0));
            if ($old->count()) {
                $old = $old->results();

                foreach ($old as $item) {
                    $queries->create('reports', array(
                        'id' => $item->id,
                        'type' => $item->type,
                        'reporter_id' => $item->reporter_id,
                        'reported_id' => $item->reported_id,
                        'status' => $item->status,
                        'date_reported' => $item->date_reported,
                        'date_updated' => $item->date_updated,
                        'report_reason' => $item->report_reason,
                        'updated_by' => $item->updated_by,
                        'reported_post' => $item->reported_post,
                        'reported_mcname' => $item->reported_mcname,
                        'reported_uuid' => $item->reported_uuid
                    ));
                }
            }
        } catch (Exception $e) {
            $errors[] = 'Unable to convert reports: ' . $e->getMessage();
        }

        // Report comments
        try {
            $old = $conn->get('nl1_reports_comments', array('id', '<>', 0));
            if ($old->count()) {
                $old = $old->results();

                foreach ($old as $item) {
                    $queries->create('reports_comments', array(
                        'id' => $item->id,
                        'report_id' => $item->report_id,
                        'commenter_id' => $item->commenter_id,
                        'comment_date' => $item->comment_date,
                        'comment_content' => $item->comment_content
                    ));
                }
            }
        } catch (Exception $e) {
            $errors[] = 'Unable to convert report comments: ' . $e->getMessage();
        }

        // Reputation
        try {
            $old = $conn->get('nl1_reputation', array('id', '<>', 0));
            if ($old->count()) {
                $old = $old->results();

                foreach ($old as $item) {
                    $queries->create('forums_reactions', array(
                        'id' => $item->id,
                        'post_id' => $item->post_id,
                        'user_received' => $item->user_received,
                        'user_given' => $item->user_given,
                        'reaction_id' => 1,
                        'time' => strtotime($item->time_given)
                    ));
                }
            }
        } catch (Exception $e) {
            $errors[] = 'Unable to convert reputation: ' . $e->getMessage();
        }

        // Settings
        try {
            $old = $conn->get('nl1_settings', array('id', '<>', 0));
            if ($old->count()) {
                $old = $old->results();

                foreach ($old as $item) {
                    $queries->create('settings', array(
                        'id' => $item->id,
                        'name' => $item->name,
                        'value' => $item->value
                    ));
                }
            }
        } catch (Exception $e) {
            $errors[] = 'Unable to convert settings: ' . $e->getMessage();
        }

        break;

    case 5:
        // Topics -> users
        try {
            $old = $conn->get('nl1_topics', array('id', '<>', 0));
            if ($old->count()) {
                $old = $old->results();

                foreach ($old as $item) {
                    $queries->create('topics', array(
                        'id' => $item->id,
                        'forum_id' => $item->forum_id,
                        'topic_title' => $item->topic_title,
                        'topic_creator' => $item->topic_creator,
                        'topic_last_user' => $item->topic_last_user,
                        'topic_date' => $item->topic_date,
                        'topic_reply_date' => $item->topic_reply_date,
                        'topic_views' => $item->topic_views,
                        'locked' => $item->locked,
                        'sticky' => $item->sticky,
                        'label' => $item->label
                    ));
                }
            }
        } catch (Exception $e) {
            $errors[] = 'Unable to convert topics: ' . $e->getMessage();
        }

        // Users
        try {
            $old = $conn->get('nl1_users', array('id', '<>', 0));
            if ($old->count()) {
                $old = $old->results();

                foreach ($old as $item) {
                    $queries->create('users', array(
                        'id' => $item->id,
                        'username' => $item->mcname,
                        'nickname' => $item->username,
                        'password' => $item->password,
                        'pass_method' => $item->pass_method,
                        'uuid' => $item->uuid,
                        'joined' => $item->joined,
                        'email' => $item->email,
                        'isbanned' => $item->isbanned,
                        'lastip' => (is_null($item->lastip) ? 'none' : $item->lastip),
                        'active' => $item->active,
                        'signature' => $item->signature,
                        'reputation' => $item->reputation,
                        'reset_code' => $item->reset_code,
                        'has_avatar' => $item->has_avatar,
                        'gravatar' => $item->gravatar,
                        'last_online' => $item->last_online,
                        'last_username_update' => $item->last_username_update,
                        'user_title' => $item->user_title,
                        'tfa_enabled' => $item->tfa_enabled,
                        'tfa_type' => $item->tfa_type,
                        'tfa_secret' => $item->tfa_secret,
                        'tfa_complete' => $item->tfa_complete
                    ));

                    $queries->create('users_groups', array(
                        'user_id' => $item->id,
                        'group_id' => $item->group_id
                    ));
                }
            }
        } catch (Exception $e) {
            $errors[] = 'Unable to convert users: ' . $e->getMessage();
        }

        break;

    case 6:
        // User admin session -> user profile wall replies
        // User admin sessions
        try {
            $old = $conn->get('nl1_users_admin_session', array('id', '<>', 0));
            if ($old->count()) {
                $old = $old->results();

                foreach ($old as $item) {
                    $queries->create('users_admin_session', array(
                        'id' => $item->id,
                        'user_id' => $item->user_id,
                        'hash' => $item->hash
                    ));
                }
            }
        } catch (Exception $e) {
            $errors[] = 'Unable to convert user admin sessions: ' . $e->getMessage();
        }

        // User sessions
        try {
            $old = $conn->get('nl1_users_session', array('id', '<>', 0));
            if ($old->count()) {
                $old = $old->results();

                foreach ($old as $item) {
                    $queries->create('users_session', array(
                        'id' => $item->id,
                        'user_id' => $item->user_id,
                        'hash' => $item->hash
                    ));
                }
            }
        } catch (Exception $e) {
            $errors[] = 'Unable to convert user sessions: ' . $e->getMessage();
        }

        // Username history
        try {
            $old = $conn->get('nl1_users_username_history', array('id', '<>', 0));
            if ($old->count()) {
                $old = $old->results();

                foreach ($old as $item) {
                    $queries->create('users_username_history', array(
                        'id' => $item->id,
                        'user_id' => $item->user_id,
                        'changed_to' => $item->changed_to,
                        'changed_at' => $item->changed_at,
                        'original' => $item->original
                    ));
                }
            }
        } catch (Exception $e) {
            $errors[] = 'Unable to convert username history: ' . $e->getMessage();
        }

        // Profile wall posts
        try {
            $old = $conn->get('nl1_user_profile_wall_posts', array('id', '<>', 0));
            if ($old->count()) {
                $old = $old->results();

                foreach ($old as $item) {
                    $queries->create('user_profile_wall_posts', array(
                        'id' => $item->id,
                        'user_id' => $item->user_id,
                        'author_id' => $item->author_id,
                        'time' => $item->time,
                        'content' => $item->content
                    ));
                }
            }
        } catch (Exception $e) {
            $errors[] = 'Unable to convert user profile wall posts: ' . $e->getMessage();
        }

        // Profile wall likes
        try {
            $old = $conn->get('nl1_user_profile_wall_posts_likes', array('id', '<>', 0));
            if ($old->count()) {
                $old = $old->results();

                foreach ($old as $item) {
                    $queries->create('user_profile_wall_posts_reactions', array(
                        'id' => $item->id,
                        'user_id' => $item->user_id,
                        'post_id' => $item->post_id,
                        'reaction_id' => 1,
                        'time' => 0
                    ));
                }
            }
        } catch (Exception $e) {
            $errors[] = 'Unable to convert user profile wall likes: ' . $e->getMessage();
        }

        // Profile wall replies
        try {
            $old = $conn->get('nl1_user_profile_wall_posts_replies', array('id', '<>', 0));
            if ($old->count()) {
                $old = $old->results();

                foreach ($old as $item) {
                    $queries->create('user_profile_wall_posts_replies', array(
                        'id' => $item->id,
                        'post_id' => $item->post_id,
                        'author_id' => $item->author_id,
                        'time' => $item->time,
                        'content' => $item->content
                    ));
                }
            }
        } catch (Exception $e) {
            $errors[] = 'Unable to convert user profile wall replies: ' . $e->getMessage();
        }

        break;

    case 7:
        // UUID cache
        try {
            $old = $conn->get('nl1_uuid_cache', array('id', '<>', 0));
            if ($old->count()) {
                $old = $old->results();

                foreach ($old as $item) {
                    $queries->create('uuid_cache', array(
                        'id' => $item->id,
                        'mcname' => $item->mcname,
                        'uuid' => $item->uuid
                    ));
                }
            }
        } catch (Exception $e) {
            $errors[] = 'Unable to convert UUID cache: ' . $e->getMessage();
        }

        break;

    case 8:
        // New settings/initialise cache
        // Site name
        $sitename = $queries->getWhere('settings', array('name', '=', 'sitename'));
        if (!count($sitename)) {
            $cache->setCache('sitenamecache');
            $cache->store('sitename', 'NamelessMC');
        } else {
            $cache->setCache('sitenamecache');
            $cache->store('sitename', Output::getClean($sitename[0]->value));
        }

        // Languages
        $queries->create('languages', array(
            'name' => 'EnglishUK',
            'is_default' => 1
        ));
        $queries->create('languages', array(
            'name' => 'Chinese',
            'is_default' => 0
        ));
        $queries->create('languages', array(
            'name' => 'Czech',
            'is_default' => 0
        ));
        $queries->create('languages', array(
            'name' => 'Danish',
            'is_default' => 0
        ));
        $queries->create('languages', array(
            'name' => 'Dutch',
            'is_default' => 0
        ));
        $queries->create('languages', array(
            'name' => 'EnglishUS',
            'is_default' => 0
        ));
        $queries->create('languages', array(
            'name' => 'German',
            'is_default' => 0
        ));
        $queries->create('languages', array(
            'name' => 'Greek',
            'is_default' => 0
        ));
        $queries->create('languages', array(
            'name' => 'Japanese',
            'is_default' => 0
        ));
        $queries->create('languages', array(
            'name' => 'Lithuanian',
            'is_default' => 0
        ));
        $queries->create('languages', array(
            'name' => 'Norwegian',
            'is_default' => 0
        ));
        $queries->create('languages', array(
            'name' => 'Polish',
            'is_default' => 0
        ));
        $queries->create('languages', array(
            'name' => 'Portuguese',
            'is_default' => 0
        ));
        $queries->create('languages', array(
            'name' => 'Romanian',
            'is_default' => 0
        ));
        $queries->create('languages', array(
            'name' => 'Slovak',
            'is_default' => 0
        ));
        $queries->create('languages', array(
            'name' => 'Spanish',
            'is_default' => 0
        ));
        $queries->create('languages', array(
            'name' => 'SwedishSE',
            'is_default' => 0
        ));
        $queries->create('languages', array(
            'name' => 'Turkish',
            'is_default' => 0
        ));
        $queries->create('languages', array(
            'name' => 'Thai',
            'is_default' => 0
        ));
        $cache->setCache('languagecache');
        $cache->store('language', 'EnglishUK');

        // Modules
        $queries->create('modules', array(
            'name' => 'Core',
            'enabled' => 1
        ));
        $queries->create('modules', array(
            'name' => 'Forum',
            'enabled' => 1
        ));
        $cache->setCache('modulescache');
        $cache->store('enabled_modules', array(
            array('name' => 'Core', 'priority' => 1),
            array('name' => 'Forum', 'priority' => 4)
        ));
        $cache->store('module_core', true);
        $cache->store('module_forum', true);

        // Reactions
        $queries->create('reactions', array(
            'name' => 'Like',
            'html' => '<i class="fas fa-thumbs-up text-success"></i>',
            'enabled' => 1,
            'type' => 2
        ));
        $queries->create('reactions', array(
            'name' => 'Dislike',
            'html' => '<i class="fas fa-thumbs-down text-danger"></i>',
            'enabled' => 1,
            'type' => 0
        ));
        $queries->create('reactions', array(
            'name' => 'Meh',
            'html' => '<i class="fas fa-meh text-warning"></i>',
            'enabled' => 1,
            'type' => 1
        ));

        // Forum Labels
        $queries->create('forums_labels', array(
            'name' => 'Default',
            'html' => '<span class="badge badge-default">{x}</span>'
        ));
        $queries->create('forums_labels', array(
            'name' => 'Primary',
            'html' => '<span class="badge badge-primary">{x}</span>'
        ));
        $queries->create('forums_labels', array(
            'name' => 'Success',
            'html' => '<span class="badge badge-success">{x}</span>'
        ));
        $queries->create('forums_labels', array(
            'name' => 'Info',
            'html' => '<span class="badge badge-info">{x}</span>'
        ));
        $queries->create('forums_labels', array(
            'name' => 'Warning',
            'html' => '<span class="badge badge-warning">{x}</span>'
        ));
        $queries->create('forums_labels', array(
            'name' => 'Danger',
            'html' => '<span class="badge badge-danger">{x}</span>'
        ));

        // Settings
        $queries->create('settings', array(
            'name' => 'registration_enabled',
            'value' => 1
        ));

        $queries->create('settings', array(
            'name' => 'recaptcha_login',
            'value' => 'false'
        ));

        $queries->create('settings', array(
            'name' => 'recaptcha_type',
            'value' => 'reCaptcha'
        ));

        $version = $queries->getWhere('settings', array('name', '=', 'version'));
        if (count($version)) {
            $queries->update('settings', $version[0]->id, array(
                'name' => 'nameless_version',
                'value' => '2.0.0-pr12'
            ));
        } else {
            $queries->create('settings', array(
                'name' => 'nameless_version',
                'value' => '2.0.0-pr12'
            ));
        }

        $version_update = $queries->getWhere('settings', array('name', '=', 'version_update'));
        if (count($version_update)) {
            $queries->update('settings', $version_update[0]->id, array(
                'value' => 'false'
            ));
        } else {
            $queries->create('settings', array(
                'name' => 'version_update',
                'value' => 'false'
            ));
        }

        $mcassoc = $queries->getWhere('settings', array('name', '=', 'use_mcassoc'));
        if (count($mcassoc)) {
            $queries->update('settings', $mcassoc[0]->id, array(
                'name' => 'verify_accounts'
            ));
        } else {
            $queries->create('settings', array(
                'name' => 'verify_accounts',
                'value' => 0
            ));
        }

        $avatar_site = $queries->getWhere('settings', array('name', '=', 'avatar_api'));
        if (count($avatar_site)) {
            $queries->update('settings', $avatar_site[0]->id, array(
                'name' => 'avatar_site'
            ));
        } else {
            $queries->create('settings', array(
                'name' => 'avatar_site',
                'value' => 'cravatar'
            ));
        }

        $queries->create('settings', array(
            'name' => 'mc_integration',
            'value' => 1
        ));

        $queries->create('settings', array(
            'name' => 'portal',
            'value' => 0
        ));
        $cache->setCache('portal_cache');
        $cache->store('portal', 0);

        $queries->create('settings', array(
            'name' => 'forum_reactions',
            'value' => 1
        ));

        $queries->create('settings', array(
            'name' => 'formatting_type',
            'value' => 'html'
        ));
        $cache->setCache('post_formatting');
        $cache->store('formatting', 'html');

        $error_reporting = $queries->getWhere('settings', array('name', '=', 'error_reporting'));
        if (count($error_reporting)) {
            $cache->setCache('error_cache');
            $cache->store('error_reporting', $error_reporting[0]->value);
        } else {
            $queries->create('settings', array(
                'name' => 'error_reporting',
                'value' => 0
            ));
            $cache->setCache('error_cache');
            $cache->store('error_reporting', 0);
        }

        $queries->create('settings', array(
            'name' => 'page_loading',
            'value' => 0
        ));
        $cache->setCache('page_load_cache');
        $cache->store('page_load', 0);

        $use_plugin = $queries->getWhere('settings', array('name', '=', 'use_plugin'));
        if (count($use_plugin)) {
            $queries->update('settings', $use_plugin[0]->id, array(
                'name' => 'use_api'
            ));
        } else {
            $queries->create('settings', array(
                'name' => 'use_api',
                'value' => 0
            ));
        }

        $queries->create('settings', array(
            'name' => 'timezone',
            'value' => 'Europe/London'
        ));
        $cache->setCache('timezone_cache');
        $cache->store('timezone', 'Europe/London');

        $queries->create('settings', array(
            'name' => 'maintenance_message',
            'value' => 'This website is currently in maintenance mode.'
        ));
        $cache->setCache('maintenance_cache');
        $cache->store('maintenance', array('maintenance' => 'false', 'message' => 'This website is currently in maintenance mode.'));

        $queries->create('settings', array(
            'name' => 'authme',
            'value' => 0
        ));

        $queries->create('settings', array(
            'name' => 'authme_db',
            'value' => null
        ));

        $queries->create('settings', array(
            'name' => 'default_avatar_type',
            'value' => 'minecraft'
        ));

        $queries->create('settings', array(
            'name' => 'custom_default_avatar',
            'value' => null
        ));

        $queries->create('settings', array(
            'name' => 'private_profile',
            'value' => 1
        ));

        $queries->create('settings', array(
            'name' => 'registration_disabled_message',
            'value' => null
        ));

        $queries->create('settings', array(
            'name' => 'discord_hooks',
            'value' => '{}'
        ));

        $queries->create('settings', array(
            'name' => 'api_verification',
            'value' => '1'
        ));

        $queries->create('settings', array(
            'name' => 'validate_user_action',
            'value' => '{"action":"activate"}'
        ));

        $queries->create('settings', array(
            'name' => 'login_method',
            'value' => 'email'
        ));

        $queries->create('settings', array(
            'name' => 'username_sync',
            'value' => '1'
        ));

        $queries->create('privacy_terms', array(
            'name' => 'privacy',
            'value' => 'The following privacy policy outlines how your data is used on our website.<br /><br /><strong>Data</strong><br />Basic non-identifiable information about your user on the website is collected; the majority of which is provided during registration, such as email addresses and usernames.<br />In addition to this, IP addresses for registered users are stored within the system to aid with moderation duties. This includes spam prevention, and detecting alternative accounts.<br /><br />Accounts can be deleted by a site administrator upon request, which will remove all data relating to your user from our system.<br /><br /><strong>Cookies</strong><br />Cookies are used to store small pieces of non-identifiable information with your consent. In order to consent to the use of cookies, you must either close the cookie notice (as explained within the notice) or register on our website.<br />Data stored by cookies include any recently viewed topic IDs, along with a unique, unidentifiable hash upon logging in and selecting &quot;Remember Me&quot; to automatically log you in next time you visit.'
        ));

        $terms = $queries->getWhere('settings', array('name', '=', 't_and_c_site'));
        if (count($terms)) {
            $queries->create('privacy_terms', array(
                'name' => 'terms',
                'value' => $terms[0]->value
            ));
        }

        $queries->create('settings', array(
            'name' => 'status_page',
            'value' => '1'
        ));

        $queries->create('settings', array(
            'name' => 'discord_integration',
            'value' => 0,
        ));

        $queries->create('settings', array(
            'name' => 'discord_bot_url',
            'value' => null
        ));

        $queries->create('settings', array(
            'name' => 'discord_bot_username',
            'value' => null
        ));
        
        $queries->create('settings', array(
            'name' => 'placeholders',
            'value' => '0'
        ));

        // Templates
        $queries->create('templates', array(
            'name' => 'Default',
            'enabled' => 1,
            'is_default' => 0
        ));

        $queries->create('templates', array(
            'name' => 'DefaultRevamp',
            'enabled' => 1,
            'is_default' => 1
        ));

        $cache->setCache('templatecache');
        $cache->store('default', 'DefaultRevamp');

        $queries->create('panel_templates', array(
            'name' => 'Default',
            'enabled' => 1,
            'is_default' => 1
        ));
        $cache->store('panel_default', 'Default');

        // Widgets - initialise just a few default ones for now
        $queries->create('widgets', array(
            'name' => 'Online Staff',
            'enabled' => 1,
            'pages' => '["index","forum"]'
        ));

        $queries->create('widgets', array(
            'name' => 'Online Users',
            'enabled' => 1,
            'pages' => '["index","forum"]'
        ));

        $queries->create('widgets', array(
            'name' => 'Statistics',
            'enabled' => 1,
            'pages' => '["index","forum"]'
        ));

        $cache->setCache('Core-widgets');
        $cache->store('enabled', array(
            'Online Staff' => 1,
            'Online Users' => 1,
            'Statistics' => 1
        ));

        $cache->setCache('backgroundcache');
        $cache->store('banner_image', '/uploads/template_banners/homepage_bg_trimmed.jpg');

        unset($_SESSION['db_address']);
        unset($_SESSION['db_port']);
        unset($_SESSION['db_username']);
        unset($_SESSION['db_password']);
        unset($_SESSION['db_name']);

        break;

    case 9:
        // Complete
        $message = 'Upgrade complete!';
        break;
}
