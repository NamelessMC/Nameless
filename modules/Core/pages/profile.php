<?php
/**
 * Profile page
 *
 * @author Samerton
 * @license MIT
 * @version 2.2.0
 *
 * @var Cache $cache
 * @var FakeSmarty $smarty
 * @var Language $language
 * @var Navigation $cc_nav
 * @var Navigation $navigation
 * @var Navigation $staffcp_nav
 * @var Pages $pages
 * @var TemplateBase $template
 * @var User $user
 * @var Widgets $widgets
 */

// Always define page name
const PAGE = 'profile';

$timeago = new TimeAgo(TIMEZONE);

$profile = explode('/', rtrim($_GET['route'], '/'));
if (count($profile) >= 3 && ($profile[count($profile) - 1] != 'profile' || $profile[count($profile) - 2] == 'profile') && !isset($_GET['error'])) {
    // User specified
    $md_profile = $profile[count($profile) - 1];

    $page_metadata = DB::getInstance()->get('page_descriptions', ['page', '/profile'])->results();
    if (count($page_metadata)) {
        define('PAGE_DESCRIPTION', str_replace(['{site}', '{profile}'], [Output::getClean(SITE_NAME), Output::getClean($md_profile)], $page_metadata[0]->description));
        define('PAGE_KEYWORDS', $page_metadata[0]->tags);
    }

    $page_title = $language->get('user', 'profile') . ' - ' . Output::getClean($md_profile);
} else {
    $page_title = $language->get('user', 'profile');
}

require_once ROOT_PATH . '/core/templates/frontend_init.php';

$template->assets()->include([
    DARK_MODE
        ? AssetTree::PRISM_DARK
        : AssetTree::PRISM_LIGHT,
    AssetTree::TINYMCE_SPOILER,
]);

$template->addCSSStyle('
    .thumbnails li img {
      width: 200px;
    }
');

if (count($profile) >= 3 && ($profile[count($profile) - 1] != 'profile' || $profile[count($profile) - 2] == 'profile') && !isset($_GET['error'])) {
    // User specified
    $profile = $profile[count($profile) - 1];

    $profile_user = new User($profile, 'username');
    if (!$profile_user->exists()) {
        Redirect::to(URL::build('/profile/&error=not_exist'));
    }
    $query = $profile_user->data();

    // Deal with input
    if (Input::exists() && $user->isLoggedIn()) {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'banner':
                    if ($user->data()->username == $profile) {
                        if (Token::check()) {
                            // Update banner
                            if (isset($_POST['banner'])) {
                                // Check image specified actually exists
                                if (is_file(implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'uploads', 'profile_images', $_POST['banner']]))) {
                                    // Exists
                                    // Is it an image file?
                                    if (in_array(pathinfo(implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'uploads', 'profile_images', $_POST['banner']]), PATHINFO_EXTENSION), ['gif', 'png', 'jpg', 'jpeg'])) {
                                        // Yes, update settings
                                        $user->update(
                                            [
                                                'banner' => Output::getClean($_POST['banner'])
                                            ]
                                        );

                                        // Refresh to update banner
                                        Redirect::to($profile_user->getProfileURL());
                                    }
                                }
                            }
                        }
                    }
                    break;

                case 'new_post':
                    if (Token::check()) {
                        if ($user->hasPermission('profile.post')) {
                            $validation = Validate::check($_POST, [
                                'post' => [
                                    Validate::REQUIRED => true,
                                    Validate::MIN => 1,
                                    Validate::MAX => 10000,
                                    Validate::RATE_LIMIT => 3,
                                ],
                            ])
                                ->message($language->get('user', 'invalid_wall_post'))
                                ->messages([
                                    'post' => [
                                        Validate::RATE_LIMIT => static fn($meta) => $language->get('general', 'rate_limit', $meta),
                                    ]
                                ]);

                            if ($validation->passed()) {
                                // Validation successful
                                // Input into database
                                DB::getInstance()->insert(
                                    'user_profile_wall_posts',
                                    [
                                        'user_id' => $query->id,
                                        'author_id' => $user->data()->id,
                                        'time' => date('U'),
                                        'content' => Input::get('post')
                                    ]
                                );

                                EventHandler::executeEvent(new UserProfilePostCreatedEvent(
                                    $user,
                                    $profile_user,
                                    Input::get('post'),
                                ));

                                if ($query->id !== $user->data()->id) {
                                    // Alert user
                                    Alert::create(
                                        $query->id,
                                        'profile_post',
                                        [
                                            'path' => 'core',
                                            'file' => 'user',
                                            'term' => 'new_wall_post',
                                            'replace' => '{{author}}',
                                            'replace_with' => $user->getDisplayname()
                                        ],
                                        [
                                            'path' => 'core',
                                            'file' => 'user',
                                            'term' => 'new_wall_post',
                                            'replace' => '{{author}}',
                                            'replace_with' => $user->getDisplayname()
                                        ],
                                        URL::build('/profile/' . urlencode($profile_user->getDisplayname(true)) . '/#post-' . urlencode(DB::getInstance()->lastId()))
                                    );
                                }

                                $cache->setCache('profile_posts_widget');
                                $cache->eraseAll();

                                // Redirect to clear input
                                Redirect::to($profile_user->getProfileURL());
                            }

                            // Validation failed
                            $error = $validation->errors()[0];

                        } else {
                            $error = $language->get('errors', 'no_permission');
                        }
                    } else {
                        $error = $language->get('general', 'invalid_token');
                    }
                    break;

                case 'reply':
                    if (Token::check()) {
                        if ($user->hasPermission('profile.post')) {
                            $validation = Validate::check($_POST, [
                                'reply' => [
                                    Validate::REQUIRED => true,
                                    Validate::MIN => 1,
                                    Validate::MAX => 10000
                                ],
                                'post' => [
                                    Validate::REQUIRED => true,
                                    Validate::RATE_LIMIT => 3,
                                ]
                            ])
                                ->message($language->get('user', 'invalid_wall_post'))
                                ->messages([
                                    'post' => [
                                        Validate::RATE_LIMIT => static fn($meta) => $language->get(
                                            'general',
                                            'rate_limit',
                                            $meta
                                        ),
                                    ]
                                ]);

                            if ($validation->passed()) {
                                // Validation successful

                                // Ensure post exists
                                $post = DB::getInstance()->get('user_profile_wall_posts', ['id', $_POST['post']]
                                )->results();
                                if (!count($post)) {
                                    Redirect::to($profile_user->getProfileURL());
                                }

                                // Input into database
                                DB::getInstance()->insert(
                                    'user_profile_wall_posts_replies',
                                    [
                                        'post_id' => $_POST['post'],
                                        'author_id' => $user->data()->id,
                                        'time' => date('U'),
                                        'content' => Input::get('reply')
                                    ]
                                );

                                EventHandler::executeEvent(
                                    new UserProfilePostReplyCreatedEvent(
                                        $user,
                                        $profile_user,
                                        Input::get('reply'),
                                    )
                                );

                                if ($post[0]->author_id != $query->id && $query->id != $user->data()->id) {
                                    Alert::create(
                                        $query->id,
                                        'profile_post',
                                        [
                                            'path' => 'core',
                                            'file' => 'user',
                                            'term' => 'new_wall_post',
                                            'replace' => '{{author}}',
                                            'replace_with' => $user->getDisplayname(),
                                        ],
                                        [
                                            'path' => 'core',
                                            'file' => 'user',
                                            'term' => 'new_wall_post',
                                            'replace' => '{{author}}',
                                            'replace_with' => $user->getDisplayname(),
                                        ],
                                        URL::build(
                                            '/profile/' . urlencode(
                                                $profile_user->getDisplayname(true)
                                            ) . '/#post-' . urlencode($_POST['post'])
                                        )
                                    );
                                } else {
                                    if ($post[0]->author_id != $user->data()->id) {
                                        // Alert post author
                                        if ($post[0]->author_id == $query->id) {
                                            Alert::create(
                                                $query->id,
                                                'profile_post_reply',
                                                [
                                                    'path' => 'core',
                                                    'file' => 'user',
                                                    'term' => 'new_wall_post_reply_your_profile',
                                                    'replace' => '{{author}}',
                                                    'replace_with' => $user->getDisplayname(),
                                                ],
                                                [
                                                    'path' => 'core',
                                                    'file' => 'user',
                                                    'term' => 'new_wall_post_reply_your_profile',
                                                    'replace' => '{{author}}',
                                                    'replace_with' => $user->getDisplayname()
                                                ],
                                                URL::build(
                                                    '/profile/' . urlencode(
                                                        $profile_user->getDisplayname(true)
                                                    ) . '/#post-' . urlencode($_POST['post'])
                                                )
                                            );
                                        } else {
                                            Alert::create(
                                                $post[0]->author_id,
                                                'profile_post_reply',
                                                [
                                                    'path' => 'core',
                                                    'file' => 'user',
                                                    'term' => 'new_wall_post_reply',
                                                    'replace' => ['{{author}}', '{{user}}'],
                                                    'replace_with' => [
                                                        $user->getDisplayname(),
                                                        $profile_user->getDisplayname()
                                                    ]
                                                ],
                                                [
                                                    'path' => 'core',
                                                    'file' => 'user',
                                                    'term' => 'new_wall_post_reply',
                                                    'replace' => ['{{author}}', '{{user}}'],
                                                    'replace_with' => [
                                                        $user->getDisplayname(),
                                                        $profile_user->getDisplayname()
                                                    ]
                                                ],
                                                URL::build(
                                                    '/profile/' . urlencode(
                                                        $profile_user->getDisplayname(true)
                                                    ) . '/#post-' . urlencode($_POST['post'])
                                                )
                                            );
                                        }
                                    }
                                }

                                // Redirect to clear input
                                Redirect::to($profile_user->getProfileURL());
                            }

                            // Validation failed
                            $error = $validation->errors()[0];

                        } else {
                            $error = $language->get('errors', 'no_permission');
                        }
                    } else {
                        $error = $language->get('general', 'invalid_token');
                    }
                    break;

                case 'block':
                    if (Token::check()) {
                        if ($user->isBlocked($user->data()->id, $query->id)) {
                            // Unblock
                            $row = DB::getInstance()->get('blocked_users', [['user_id', $user->data()->id], ['user_blocked_id', $query->id]])->first();
                            if ($row) {
                                DB::getInstance()->delete('blocked_users', [['user_id', $user->data()->id], ['user_blocked_id', $query->id]]);
                                $success = $language->get('user', 'user_unblocked');
                            }
                        } else {
                            // Block
                            DB::getInstance()->insert('blocked_users', [
                                'user_id' => $user->data()->id,
                                'user_blocked_id' => $query->id
                            ]);
                            $success = $language->get('user', 'user_blocked');
                        }
                    } else {
                        $error = $language->get('general', 'invalid_token');
                    }
                    break;

                case 'edit':
                    // Ensure user is mod or owner of post
                    if (Token::check()) {
                        if (isset($_POST['post_id']) && is_numeric($_POST['post_id'])) {
                            $post = DB::getInstance()->get('user_profile_wall_posts', ['id', $_POST['post_id']])->results();
                            if (count($post)) {
                                $post = $post[0];
                                if ($user->canViewStaffCP() || $post->author_id == $user->data()->id) {
                                    if (isset($_POST['content']) && strlen($_POST['content']) < 10000 && strlen($_POST['content']) >= 1) {
                                        try {
                                            DB::getInstance()->update('user_profile_wall_posts', $_POST['post_id'], [
                                                'content' => $_POST['content']
                                            ]);
                                        } catch (Exception $e) {
                                            $error = $e->getMessage();
                                        }
                                    } else {
                                        $error = $language->get('user', 'invalid_wall_post');
                                    }
                                }
                            }
                        }
                    } else {
                        $error = $language->get('general', 'invalid_token');
                    }
                    break;

                case 'delete':
                    // Ensure user is mod or owner of post
                    if (Token::check()) {
                        if (isset($_POST['post_id']) && is_numeric($_POST['post_id'])) {
                            $post = DB::getInstance()->get('user_profile_wall_posts', ['id', $_POST['post_id']])->results();
                            if (count($post)) {
                                $post = $post[0];
                                if ($user->canViewStaffCP() || $post->author_id == $user->data()->id) {
                                    try {
                                        DB::getInstance()->delete('user_profile_wall_posts', ['id', $_POST['post_id']]);
                                        DB::getInstance()->delete('user_profile_wall_posts_replies', ['post_id', $_POST['post_id']]);
                                    } catch (Exception $e) {
                                        $error = $e->getMessage();
                                    }
                                }
                            }
                        }
                    } else {
                        $error = $language->get('general', 'invalid_token');
                    }
                    break;

                case 'deleteReply':
                    // Ensure user is mod or owner of reply
                    if (Token::check()) {
                        if (isset($_POST['post_id']) && is_numeric($_POST['post_id'])) {
                            $post = DB::getInstance()->get('user_profile_wall_posts_replies', ['id', $_POST['post_id']])->results();
                            if (count($post)) {
                                $post = $post[0];
                                if ($user->canViewStaffCP() || $post->author_id == $user->data()->id) {
                                    try {
                                        DB::getInstance()->delete('user_profile_wall_posts_replies', ['id', $_POST['post_id']]);
                                    } catch (Exception $e) {
                                        $error = $e->getMessage();
                                    }
                                }
                            }
                        }
                    } else {
                        $error = $language->get('general', 'invalid_token');
                    }
                    break;
            }
        }
    }

    if (isset($_GET['action']) && $user->isLoggedIn()) {
        switch ($_GET['action']) {
            case 'reset_banner':
                if (Token::check($_POST['token'])) {
                    if ($user->hasPermission('modcp.profile_banner_reset')) {
                        $profile_user->update([
                            'banner' => null
                        ]);
                    }

                    Redirect::to($profile_user->getProfileURL());
                }

                $error = $language->get('general', 'invalid_token');

                break;
        }
    }

    // Get page
    if (isset($_GET['p'])) {
        if (!is_numeric($_GET['p'])) {
            Redirect::to($profile_user->getProfileURL());
        }

        if ($_GET['p'] == 1) {
            // Avoid bug in pagination class
            Redirect::to($profile_user->getProfileURL());
        }
        $p = $_GET['p'];
    } else {
        $p = 1;
    }

    // View count
    // Check if user is logged in and the viewer is not the owner of this profile.
    if (($user->isLoggedIn() && $user->data()->id != $query->id)
        // If no one is logged in check if they have accepted the cookies.
        || (!$user->isLoggedIn() && (defined('COOKIE_CHECK') && COOKIES_ALLOWED))
    ) {
        if (!Cookie::exists('nl-profile-' . $query->id)) {
            DB::getInstance()->increment('users', $query->id, 'profile_views');
            Cookie::put('nl-profile-' . $query->id, 'true', 3600);
        }
    } else {
        if (!Session::exists('nl-profile-' . $query->id)) {
            DB::getInstance()->increment('users', $query->id, 'profile_views');
            Session::put('nl-profile-' . $query->id, 'true');
        }
    }

    // Set Can view
    if ($profile_user->isPrivateProfile() && !$user->canBypassPrivateProfile()) {
        $template->getEngine()->addVariables([
            'PRIVATE_PROFILE' => $language->get('user', 'private_profile_page'),
            'CAN_VIEW' => false
        ]);
    } else {
        $template->getEngine()->addVariables([
            'CAN_VIEW' => true
        ]);
    }

    // Generate Smarty variables to pass to template
    if ($user->isLoggedIn()) {
        // Form token
        $template->getEngine()->addVariables([
            'TOKEN' => Token::get(),
            'LOGGED_IN' => true,
            'SUBMIT' => $language->get('general', 'submit'),
            'CANCEL' => $language->get('general', 'cancel'),
            'CAN_MODERATE' => $user->canViewStaffCP()
        ]);

        if ($user->hasPermission('profile.private.bypass')) {
            $template->getEngine()->addVariables([
                'CAN_VIEW' => true
            ]);
        }

        if ($user->data()->id == $query->id) {
            // Custom profile banners
            $banners = [];

            $image_path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'uploads', 'profile_images']);
            $images = scandir($image_path);

            // Only display jpeg, png, jpg, gif
            $allowed_exts = ['gif', 'png', 'jpg', 'jpeg'];

            foreach ($images as $image) {
                $ext = pathinfo($image, PATHINFO_EXTENSION);
                if (!in_array($ext, $allowed_exts)) {
                    continue;
                }

                $banners[] = [
                    'src' => ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'uploads/profile_images/' . Output::getClean($image),
                    'name' => Output::getClean($image),
                    'active' => $user->data()->banner == $image
                ];
            }

            $image_path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'uploads', 'profile_images', $user->data()->id]);

            if (is_dir($image_path)) {
                $images = scandir($image_path);

                foreach ($images as $image) {
                    $ext = pathinfo($image, PATHINFO_EXTENSION);
                    if (!in_array($ext, $allowed_exts)) {
                        continue;
                    }

                    $banners[] = [
                        'src' => ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'uploads/profile_images/' . Output::getClean($user->data()->id) . '/' . Output::getClean($image),
                        'name' => Output::getClean($user->data()->id) . '/' . Output::getClean($image),
                        'active' => $user->data()->banner == $user->data()->id . '/' . $image
                    ];
                }
            }

            $template->getEngine()->addVariables([
                'SELF' => true,
                'SETTINGS_LINK' => URL::build('/user/settings'),
                'CHANGE_BANNER' => $language->get('user', 'change_banner'),
                'BANNERS' => $banners,
                'CAN_VIEW' => true,
            ]);

            if ($user->hasPermission('usercp.profile_banner')) {
                $template->getEngine()->addVariables([
                    'UPLOAD_PROFILE_BANNER' => $language->get('user', 'upload_profile_banner'),
                    'PROFILE_BANNER' => $language->get('user', 'profile_banner'),
                    'BROWSE' => $language->get('general', 'browse'),
                    'UPLOAD' => $language->get('user', 'upload'),
                    'UPLOAD_BANNER_URL' => ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'core/includes/image_upload.php'
                ]);
            }
        } else {
            $template->getEngine()->addVariables([
                'MESSAGE_LINK' => URL::build('/user/messaging/', 'action=new&amp;uid=' . urlencode($query->id)),
                'FOLLOW_LINK' => URL::build('/user/follow/', 'user=' . urlencode($query->id)),
                'CONFIRM' => $language->get('general', 'confirm'),
                'MOD_OR_ADMIN' => $profile_user->canViewStaffCP()
            ]);

            // Is the user blocked?
            if ($user->isBlocked($user->data()->id, $query->id)) {
                $template->getEngine()->addVariables([
                    'UNBLOCK_USER' => $language->get('user', 'unblock_user'),
                    'CONFIRM_UNBLOCK_USER' => $language->get('user', 'confirm_unblock_user')
                ]);
            } else {
                $template->getEngine()->addVariables([
                    'BLOCK_USER' => $language->get('user', 'block_user'),
                    'CONFIRM_BLOCK_USER' => $language->get('user', 'confirm_block_user')
                ]);
            }

            if ($user->hasPermission('modcp.profile_banner_reset')) {
                $template->getEngine()->addVariables([
                    'RESET_PROFILE_BANNER' => $language->get('moderator', 'reset_profile_banner'),
                    'RESET_PROFILE_BANNER_LINK' => URL::build('/profile/' . urlencode($query->username) . '/', 'action=reset_banner')
                ]);
            }
        }
    }

    $template->getEngine()->addVariables([
        'NICKNAME' => $profile_user->getDisplayname(true),
        'USERNAME' => $profile_user->getDisplayname(),
        'GROUPS' => $profile_user->getAllGroupHtml(),
        'USERNAME_COLOUR' => $profile_user->getGroupStyle(),
        'USER_TITLE' => Output::getClean($query->user_title),
        'FOLLOW' => $language->get('user', 'follow'),
        'AVATAR' => $profile_user->getAvatar(500),
        'BANNER' => ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'uploads/profile_images/' . (($query->banner) ? Output::getClean($query->banner) : 'profile.jpg'),
        'POST_ON_WALL' => $language->get('user', 'post_on_wall', ['user' => Output::getClean($profile_user->getDisplayname())]),
        'FEED' => $language->get('user', 'feed'),
        'ABOUT' => $language->get('user', 'about'),
        'LIKE' => $language->get('user', 'like'),
        'CLOSE' => $language->get('general', 'close'),
        'REPLIES_TITLE' => $language->get('user', 'replies'),
        'NO_REPLIES' => $language->get('user', 'no_replies_yet'),
        'NEW_REPLY' => $language->get('user', 'new_reply'),
        'DELETE' => $language->get('general', 'delete'),
        'CONFIRM_DELETE' => $language->get('general', 'confirm_deletion'),
        'EDIT' => $language->get('general', 'edit'),
        'SUCCESS_TITLE' => $language->get('general', 'success'),
        'ERROR_TITLE' => $language->get('general', 'error'),
        'REPLY' => $language->get('user', 'reply'),
        'EDIT_POST' => $language->get('general', 'edit'),
        'VIEWER_ID' => $user->isLoggedIn() ? $user->data()->id : 0,
    ]);

    // Wall posts
    $wall_posts = [];
    $wall_posts_query = DB::getInstance()->orderWhere('user_profile_wall_posts', 'user_id = ' . $query->id, 'time', 'DESC')->results();

    $reactions_by_user = [];
    $all_reactions = Reaction::find(true, 'enabled');
    if (count($wall_posts_query)) {
        // Pagination
        $paginator = new Paginator(
            $template_pagination ?? null,
            $template_pagination_left ?? null,
            $template_pagination_right ?? null
        );
        $results = $paginator->getLimited($wall_posts_query, 10, $p, count($wall_posts_query));
        $pagination = $paginator->generate(7, URL::build('/profile/' . urlencode($query->username) . '/'));

        $template->getEngine()->addVariable('PAGINATION', $pagination);

        // Display the correct number of posts
        foreach ($results->data as $nValue) {
            // Get reactions
            $post_reactions = [];
            $reactions_query = DB::getInstance()->get('user_profile_wall_posts_reactions', ['post_id', $nValue->id])->results();
            if (count($reactions_query)) {
                $reactions['count'] = count($reactions_query) === 1
                    ? $language->get('user', '1_reaction')
                    : $language->get('user', 'x_reactions', ['count' => count($reactions_query)]);

                foreach ($reactions_query as $wall_post_reaction) {
                    if ($wall_post_reaction->user_id == $user->data()->id) {
                        $reactions_by_user[$nValue->id][] = $wall_post_reaction->reaction_id;
                    }

                    // Get reaction name and icon
                    $reaction = $all_reactions[$wall_post_reaction->reaction_id];
                    if (!isset($post_reactions[$reaction->id])) {
                        $post_reactions[$reaction->id] = [
                            'id' => $reaction->id,
                            'name' => $reaction->name,
                            'html' => $reaction->html,
                            'order' => $reaction->order,
                            'count' => 1,
                        ];
                    } else {
                        $post_reactions[$reaction->id]['count']++;
                    }
                }
            }

            // Sort reactions by their order
            usort($post_reactions, static function ($a, $b) {
                return $a['order'] - $b['order'];
            });

            // Get replies
            $replies = [];
            $replies_query = DB::getInstance()->orderWhere('user_profile_wall_posts_replies', 'post_id = ' . $nValue->id, 'time', 'ASC')->results();
            if (count($replies_query)) {
                if (count($replies_query) == 1) {
                    $replies['count'] = $language->get('user', '1_reply');
                } else {
                    $replies['count'] = $language->get('user', 'x_replies', ['count' => count($replies_query)]);
                }

                foreach ($replies_query as $reply) {
                    $reply_user = new User($reply->author_id);
                    $content = EventHandler::executeEvent('renderProfilePost', ['content' => $reply->content])['content'];

                    $replies['replies'][] = [
                        'user_id' => Output::getClean($reply->author_id),
                        'username' => $reply_user->getDisplayname(true),
                        'nickname' => $reply_user->getDisplayname(),
                        'style' => $reply_user->getGroupStyle(),
                        'profile' => $reply_user->getProfileURL(),
                        'avatar' => $reply_user->getAvatar(500),
                        'time_friendly' => $timeago->inWords($reply->time, $language),
                        'time_full' => date(DATE_FORMAT, $reply->time),
                        'content' => $content,
                        'self' => (($user->isLoggedIn() && $user->data()->id == $reply->author_id) ? 1 : 0),
                        'id' => $reply->id
                    ];
                }
            } else {
                $replies['count'] = $language->get('user', 'x_replies', ['count' => 0]);
            }

            $post_user = new User($nValue->author_id);
            $content = EventHandler::executeEvent('renderProfilePost', ['content' => $nValue->content])['content'];
            $wall_posts[] = [
                'id' => $nValue->id,
                'user_id' => Output::getClean($post_user->data()->id),
                'username' => $post_user->getDisplayname(true),
                'nickname' => $post_user->getDisplayname(),
                'profile' => $post_user->getProfileURL(),
                'user_style' => $post_user->getGroupStyle(),
                'avatar' => $post_user->getAvatar(),
                'content' => $content,
                'date_rough' => $timeago->inWords($nValue->time, $language),
                'date' => date(DATE_FORMAT, $nValue->time),
                'reactions' => $post_reactions,
                'replies' => $replies,
                'self' => $user->isLoggedIn() && $user->data()->id == $nValue->author_id,
            ];
        }
    } else {
        $template->getEngine()->addVariable('NO_WALL_POSTS', $language->get('user', 'no_wall_posts'));
    }

    $template->getEngine()->addVariable('WALL_POSTS', $wall_posts);

    if (isset($error)) {
        $template->getEngine()->addVariable('ERROR', $error);
    }

    if (isset($success)) {
        $template->getEngine()->addVariable('SUCCESS', $success);
    }

    // About tab
    $fields = [];

    // Get profile fields
    foreach ($profile_user->getProfileFields() as $id => $profile_field) {
        if (!$profile_field->value) {
            continue;
        }

        // Get field type
        switch ($profile_field->type) {
            case Fields::TEXT:
                $type = 'text';
                break;
            case Fields::TEXTAREA:
                $type = 'textarea';
                break;
            case Fields::DATE:
                $type = 'date';
                break;
        }

        $fields[] = [
            'title' => Output::getClean($profile_field->name),
            'type' => $type,
            'value' => Output::getClean($profile_field->value)
        ];
    }

    // User Integrations
    $user_integrations = [];
    foreach ($profile_user->getIntegrations() as $key => $integrationUser) {
        if ($integrationUser->data()->username != null && $integrationUser->data()->show_publicly) {
            $fields[] = [
                'title' => Output::getClean($key),
                'type' => 'text',
                'value' => Output::getClean($integrationUser->data()->username)
            ];

            $user_integrations[$key] = [
                'username' => Output::getClean($integrationUser->data()->username),
                'identifier' => Output::getClean($integrationUser->data()->identifier)
            ];
        }
    }
    $template->getEngine()->addVariable('INTEGRATIONS', $user_integrations);

    if (!count($fields)) {
        $template->getEngine()->addVariable('NO_ABOUT_FIELDS', $language->get('user', 'no_about_fields'));
    }

    $profile_placeholders = $profile_user->getProfilePlaceholders();
    foreach ($profile_placeholders as $profile_placeholder) {
        $fields[] = [
            'title' => $profile_placeholder->friendly_name,
            'type' => 'text',
            'value' => $profile_placeholder->value,
            'tooltip' => $language->get('admin', 'placeholders_last_updated_time', ['time' => $timeago->inWords($profile_placeholder->last_updated, $language)]),
        ];
    }

    // Add date registered and last seen
    $fields['registered'] = [
        'title' => $language->get('user', 'registered'),
        'type' => 'text',
        'value' => $timeago->inWords($query->joined, $language),
        'tooltip' => date(DATE_FORMAT, $query->joined)
    ];
    $fields['last_seen'] = [
        'title' => $language->get('user', 'last_seen'),
        'type' => 'text',
        'value' => $timeago->inWords($query->last_online, $language),
        'tooltip' => date(DATE_FORMAT, $query->last_online)
    ];

    // Add Profile views
    $fields['profile_views'] = [
        'title' => $language->get('user', 'views'),
        'type' => 'text',
        'value' => $query->profile_views
    ];

    $template->getEngine()->addVariable('ABOUT_FIELDS', $fields);

    $template->getEngine()->addVariables([
        'CAN_PROFILE_POST' => $user->isLoggedIn() && $user->hasPermission('profile.post'),
        'REACTIONS' => $all_reactions,
        'REACTIONS_BY_USER' => $reactions_by_user,
        'REACTIONS_TEXT' => $language->get('user', 'reactions'),
        'REACTIONS_URL' => URL::build('/queries/reactions'),
        'USER_ID' => (($user->isLoggedIn()) ? $user->data()->id : 0),
    ]);

    // Custom tabs
    $tabs = [];
    if (isset($profile_tabs) && count($profile_tabs)) {
        foreach ($profile_tabs as $key => $tab) {
            $tabs[$key] = ['title' => $tab['title'], 'include' => $tab['smarty_template']];
            if (is_file($tab['require'])) {
                require($tab['require']);
            }
        }
    }

    // Assign profile tabs
    $template->getEngine()->addVariable('TABS', $tabs);

    if (isset($directories[1]) && !empty($directories[1]) && !isset($_GET['error']) && $user->isLoggedIn() && $user->data()->username == $profile) {
        // Script for banner selector
        $template->assets()->include([
            AssetTree::IMAGE_PICKER,
        ]);
    }

    if (Session::exists('profile_banner_error')) {
        $template->getEngine()->addVariable('ERROR', Session::flash('profile_banner_error'));
    }

    // Load modules + template
    Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

    $template->onPageLoad();

    $template->getEngine()->addVariables([
        'WIDGETS_LEFT' => $widgets->getWidgets('left', $profile_user),
        'WIDGETS_RIGHT' => $widgets->getWidgets('right', $profile_user),
    ]);

    require ROOT_PATH . '/core/templates/navbar.php';
    require ROOT_PATH . '/core/templates/footer.php';

    // Display template
    $template->displayTemplate('profile');
} else {
    if (isset($_GET['error'])) {
        // User does not exist
        $template->getEngine()->addVariables([
            'BACK' => $language->get('general', 'back'),
            'HOME' => $language->get('general', 'home'),
            'NOT_FOUND' => $language->get('user', 'couldnt_find_that_user'),
        ]);
        // Load modules + template
        Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

        $template->onPageLoad();

        $template->getEngine()->addVariables([
            'WIDGETS_LEFT' => $widgets->getWidgets('left'),
            'WIDGETS_RIGHT' => $widgets->getWidgets('right'),
        ]);

        require ROOT_PATH . '/core/templates/navbar.php';
        require ROOT_PATH . '/core/templates/footer.php';

        // Display template
        $template->displayTemplate('user_not_exist');

        // Search for user
        // TODO
    }
}
