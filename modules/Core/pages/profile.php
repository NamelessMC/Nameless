<?php
declare(strict_types=1);
/**
 *    Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.1.0
 *
 *  License: MIT
 *
 *  User profile page
 *
 * @var User $user
 * @var Language $language
 * @var Announcements $announcements
 * @var Smarty $smarty
 * @var Pages $pages
 * @var Cache $cache
 * @var Navigation $navigation
 * @var array $cc_nav
 * @var array $staffcp_nav
 * @var Widgets $widgets
 * @var TemplateBase $template
 * @var Language $forum_language
 * @var string $group
 */

// Always define page name
use GuzzleHttp\Exception\GuzzleException;

const PAGE = 'profile';

$time_ago = new TimeAgo(TIMEZONE);

$profile = explode('/', rtrim($_GET['route'], '/'));
if (!isset($_GET['error']) && count($profile) >= 3 && ($profile[count($profile) - 1] !== 'profile' || $profile[count($profile) - 2] === 'profile')) {
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

require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$template->assets()->include([
    DARK_MODE === true
        ? AssetTree::PRISM_DARK
        : AssetTree::PRISM_LIGHT,
    AssetTree::TINYMCE_SPOILER,
]);

$template->addCSSStyle(
    '.thumbnails li img{
      width: 200px;
    }'
);

if (!isset($_GET['error']) && count($profile) >= 3 && ($profile[count($profile) - 1] !== 'profile' || $profile[count($profile) - 2] === 'profile')) {
    // User specified
    $profile = $profile[count($profile) - 1];

    try {
        $profile_user = new User($profile, 'username');
    } catch (GuzzleException $e) {
    }
    if (!$profile_user->exists()) {
        Redirect::to(URL::build('/profile/&error=not_exist'));
    }
    $query = $profile_user->data();

    // Deal with input
    if (isset($_POST['action']) && Input::exists() && $user->isLoggedIn()) {
        switch ($_POST['action']) {
            case 'banner':
                // 1. Update banner.
                // 2. Check image specified actually exists.
                // 3. Exists.
                // 4. Is it an image file?
                try {
                    if ((isset($_POST['banner'])
                            && $user->data()->username === $profile)
                        && Token::check()
                        && is_file(implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'uploads', 'profile_images', $_POST['banner']]))
                        && in_array(pathinfo(implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'uploads', 'profile_images', $_POST['banner']]),
                            PATHINFO_EXTENSION), ['gif', 'png', 'jpg', 'jpeg'])) {
                        // Yes, update settings
                        $user->update(
                            [
                                'banner' => Output::getClean($_POST['banner'])
                            ]
                        );

                        // Refresh to update banner
                        Redirect::to($profile_user->getProfileURL());
                    }
                } catch (Exception $ignored) {
                }
                break;

            case 'new_post':
                try {
                    if (Token::check()) {
                        try {
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
                        } catch (Exception $ignored) {
                        }

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

                            $default_language = new Language('core', DEFAULT_LANGUAGE);
                            try {
                                EventHandler::executeEvent('userNewProfilePost', [
                                    'username' => $user->getDisplayName(true),
                                    'content' => $default_language->get('user', 'x_posted_on_y_profile', [
                                        'poster' => $user->getDisplayName(),
                                        'user' => $query->username
                                    ]),
                                    'content_full' => strip_tags(str_ireplace(['<br />', '<br>', '<br/>'], "\r\n", Input::get('post'))),
                                    'avatar_url' => $user->getAvatar(128, true),
                                    'title' => $default_language->get('user', 'new_profile_post'),
                                    'url' => URL::getSelfURL() . ltrim(URL::build('/profile/' . urlencode($profile_user->getDisplayName(true)) . '/#post-' . urlencode(DB::getInstance()->lastId())), '/')
                                ]);
                            } catch (GuzzleException $ignored) {
                            }

                            if ($query->id !== $user->data()->id) {
                                // Alert user
                                try {
                                    Alert::create(
                                        $query->id,
                                        'profile_post',
                                        [
                                            'path' => 'core',
                                            'file' => 'user',
                                            'term' => 'new_wall_post',
                                            'replace' => '{{author}}',
                                            'replace_with' => $user->getDisplayName()
                                        ],
                                        [
                                            'path' => 'core',
                                            'file' => 'user',
                                            'term' => 'new_wall_post',
                                            'replace' => '{{author}}',
                                            'replace_with' => $user->getDisplayName()
                                        ],
                                        URL::build('/profile/' . urlencode($profile_user->getDisplayName(true)) . '/#post-' . urlencode(DB::getInstance()->lastId()))
                                    );
                                } catch (Exception $ignored) {
                                }
                            }

                            $cache->setCacheName('profile_posts_widget');
                            $cache->eraseAll();

                            // Redirect to clear input
                            Redirect::to($profile_user->getProfileURL());
                        }

                        // Validation failed
                        $error = $validation->errors()[0];
                    } else {
                        $error = $language->get('general', 'invalid_token');
                    }
                } catch (Exception $ignored) {
                }
                break;

            case 'reply':
                try {
                    if (Token::check()) {
                        try {
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
                                        Validate::RATE_LIMIT => static fn($meta) => $language->get('general', 'rate_limit', $meta),
                                    ]
                                ]);
                        } catch (Exception $e) {
                        }

                        if ($validation->passed()) {
                            // Validation successful

                            // Ensure post exists
                            $post = DB::getInstance()->get('user_profile_wall_posts', ['id', $_POST['post']])->results();
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

                            $default_language = new Language('core', DEFAULT_LANGUAGE);
                            try {
                                EventHandler::executeEvent('userProfilePostReply', [
                                    'username' => $user->getDisplayName(true),
                                    'content' => $default_language->get('user', 'x_replied_on_y_profile', [
                                        'replier' => $user->getDisplayName(),
                                        'user' => $query->username
                                    ]),
                                    'content_full' => strip_tags(str_ireplace(['<br />', '<br>', '<br/>'], "\r\n", Input::get('reply'))),
                                    'avatar_url' => $user->getAvatar(128, true),
                                    'title' => $default_language->get('user', 'profile_post_reply'),
                                    'url' => URL::getSelfURL() . ltrim(URL::build('/profile/' . urlencode($profile_user->getDisplayName(true)) . '/#post-' . urlencode($_POST['post'])), '/')
                                ]);
                            } catch (GuzzleException $ignored) {
                            }

                            if ($post[0]->author_id !== $query->id && $query->id !== $user->data()->id) {
                                try {
                                    Alert::create(
                                        $query->id,
                                        'profile_post',
                                        [
                                            'path' => 'core',
                                            'file' => 'user',
                                            'term' => 'new_wall_post',
                                            'replace' => '{{author}}',
                                            'replace_with' => $user->getDisplayName(),
                                        ],
                                        [
                                            'path' => 'core',
                                            'file' => 'user',
                                            'term' => 'new_wall_post',
                                            'replace' => '{{author}}',
                                            'replace_with' => $user->getDisplayName(),
                                        ],
                                        URL::build('/profile/' . urlencode($profile_user->getDisplayName(true)) . '/#post-' . urlencode($_POST['post']))
                                    );
                                } catch (Exception $ignored) {
                                }
                            } else if ($post[0]->author_id !== $user->data()->id) {
                                // Alert post author
                                if ($post[0]->author_id === $query->id) {
                                    try {
                                        Alert::create(
                                            $query->id,
                                            'profile_post_reply',
                                            [
                                                'path' => 'core',
                                                'file' => 'user',
                                                'term' => 'new_wall_post_reply_your_profile',
                                                'replace' => '{{author}}',
                                                'replace_with' => $user->getDisplayName(),
                                            ],
                                            [
                                                'path' => 'core',
                                                'file' => 'user',
                                                'term' => 'new_wall_post_reply_your_profile',
                                                'replace' => '{{author}}',
                                                'replace_with' => $user->getDisplayName()
                                            ],
                                            URL::build('/profile/' . urlencode($profile_user->getDisplayName(true)) . '/#post-' . urlencode($_POST['post']))
                                        );
                                    } catch (Exception $ignored) {
                                    }
                                } else {
                                    try {
                                        Alert::create(
                                            $post[0]->author_id,
                                            'profile_post_reply',
                                            [
                                                'path' => 'core',
                                                'file' => 'user',
                                                'term' => 'new_wall_post_reply',
                                                'replace' => ['{{author}}', '{{user}}'],
                                                'replace_with' => [$user->getDisplayName(), $profile_user->getDisplayName()]
                                            ],
                                            [
                                                'path' => 'core',
                                                'file' => 'user',
                                                'term' => 'new_wall_post_reply',
                                                'replace' => ['{{author}}', '{{user}}'],
                                                'replace_with' => [$user->getDisplayName(), $profile_user->getDisplayName()]
                                            ],
                                            URL::build('/profile/' . urlencode($profile_user->getDisplayName(true)) . '/#post-' . urlencode($_POST['post']))
                                        );
                                    } catch (Exception $ignored) {
                                    }
                                }
                            }

                            // Redirect to clear input
                            Redirect::to($profile_user->getProfileURL());
                        }

                        // Validation failed
                        $error = $validation->errors()[0];
                    } else {
                        $error = $language->get('general', 'invalid_token');
                    }
                } catch (Exception $ignored) {
                }
                break;

            case 'block':
                try {
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
                } catch (Exception $ignored) {
                }
                break;

            case 'edit':
                // Ensure user is mod or owner of post
                try {
                    if (Token::check()) {
                        if (isset($_POST['post_id']) && is_numeric($_POST['post_id'])) {
                            $post = DB::getInstance()->get('user_profile_wall_posts', ['id', $_POST['post_id']])->results();
                            if (count($post)) {
                                $post = $post[0];
                                if ($user->canViewStaffCP() || $post->author_id === $user->data()->id) {
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
                } catch (Exception $ignored) {
                }
                break;

            case 'delete':
                // Ensure user is mod or owner of post
                try {
                    if (Token::check()) {
                        if (isset($_POST['post_id']) && is_numeric($_POST['post_id'])) {
                            $post = DB::getInstance()->get('user_profile_wall_posts', ['id', $_POST['post_id']])->results();
                            if (count($post)) {
                                $post = $post[0];
                                if ($user->canViewStaffCP() || $post->author_id === $user->data()->id) {
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
                } catch (Exception $ignored) {
                }
                break;

            case 'deleteReply':
                // Ensure user is mod or owner of reply
                try {
                    if (Token::check()) {
                        if (isset($_POST['post_id']) && is_numeric($_POST['post_id'])) {
                            $post = DB::getInstance()->get('user_profile_wall_posts_replies', ['id', $_POST['post_id']])->results();
                            if (count($post)) {
                                $post = $post[0];
                                if ($user->canViewStaffCP() || $post->author_id === $user->data()->id) {
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
                } catch (Exception $ignored) {
                }
                break;
        }
    }

    if (isset($_GET['action']) && $user->isLoggedIn()) {
        switch ($_GET['action']) {
            case 'react':
                if (!isset($_GET['post']) || !is_numeric($_GET['post'])) {
                    // Post ID required
                    Redirect::to($profile_user->getProfileURL());
                }

                // Does the post exist?
                $post = DB::getInstance()->get('user_profile_wall_posts', ['id', $_GET['post']])->results();
                if (!count($post)) {
                    Redirect::to($profile_user->getProfileURL());
                }

                // Can't like our own post
                if ($post[0]->author_id === $user->data()->id) {
                    Redirect::to($profile_user->getProfileURL());
                }

                // Liking or unliking?
                $post_likes = DB::getInstance()->get('user_profile_wall_posts_reactions', ['post_id', $_GET['post']])->results();
                if (count($post_likes)) {
                    foreach ($post_likes as $like) {
                        if ($like->user_id === $user->data()->id) {
                            $has_liked = $like->id;
                            break;
                        }
                    }
                }

                if (isset($has_liked)) {
                    // Unlike
                    DB::getInstance()->delete('user_profile_wall_posts_reactions', ['id', $has_liked]);
                } else {
                    // Like
                    DB::getInstance()->insert('user_profile_wall_posts_reactions', [
                        'user_id' => $user->data()->id,
                        'post_id' => $_GET['post'],
                        'reaction_id' => 1,
                        'time' => date('U')
                    ]);
                }

                // Redirect
                Redirect::to($profile_user->getProfileURL());
                break;

            case 'reset_banner':
                try {
                    if (Token::check($_POST['token'])) {
                        if ($user->hasPermission('modcp.profile_banner_reset')) {
                            try {
                                $profile_user->update([
                                    'banner' => null
                                ]);
                            } catch (Exception $ignored) {
                            }
                        }

                        Redirect::to($profile_user->getProfileURL());
                    }
                } catch (Exception $ignored) {
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

        if ($_GET['p'] === '1') {
            // Avoid bug in pagination class
            Redirect::to($profile_user->getProfileURL());
        }
        $p = $_GET['p'];
    } else {
        $p = 1;
    }

    // View count
    // Check if user is logged in and the viewer is not the owner of this profile.
    if ((COOKIES_ALLOWED && defined('COOKIE_CHECK') && !$user->isLoggedIn())
        || ($user->isLoggedIn() && $user->data()->id !== $query->id)
        // If no one is logged in check if they have accepted the cookies.
    ) {
        if (!Cookie::exists('nl-profile-' . $query->id)) {
            DB::getInstance()->increment('users', $query->id, 'profile_views');
            Cookie::put('nl-profile-' . $query->id, 'true', 3600);
        }
    } else if (!Session::exists('nl-profile-' . $query->id)) {
        DB::getInstance()->increment('users', $query->id, 'profile_views');
        Session::put('nl-profile-' . $query->id, 'true');
    }

    // Set Can view
    if ($profile_user->isPrivateProfile() && !$user->canBypassPrivateProfile()) {
        $smarty->assign([
            'PRIVATE_PROFILE' => $language->get('user', 'private_profile_page'),
            'CAN_VIEW' => false
        ]);
    } else {
        $smarty->assign([
            'CAN_VIEW' => true
        ]);
    }

    // Generate Smarty variables to pass to template
    if ($user->isLoggedIn()) {
        // Form token
        $smarty->assign([
            'TOKEN' => Token::get(),
            'LOGGED_IN' => true,
            'SUBMIT' => $language->get('general', 'submit'),
            'CANCEL' => $language->get('general', 'cancel'),
            'CAN_MODERATE' => $user->canViewStaffCP()
        ]);

        if ($user->hasPermission('profile.private.bypass')) {
            $smarty->assign([
                'CAN_VIEW' => true
            ]);
        }

        if ($user->data()->id === $query->id) {
            // Custom profile banners
            $banners = [];

            $image_path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'uploads', 'profile_images']);
            $images = scandir($image_path);

            // Only display jpeg, png, jpg, gif
            $allowed_extensions = ['gif', 'png', 'jpg', 'jpeg'];

            foreach ($images as $image) {
                $extension = pathinfo($image, PATHINFO_EXTENSION);
                if (!in_array($extension, $allowed_extensions, true)) {
                    continue;
                }

                $banners[] = [
                    'src' => ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'uploads/profile_images/' . Output::getClean($image),
                    'name' => Output::getClean($image),
                    'active' => $user->data()->banner === $image
                ];
            }

            $image_path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'uploads', 'profile_images', $user->data()->id]);

            if (is_dir($image_path)) {
                $images = scandir($image_path);

                foreach ($images as $image) {
                    $extension = pathinfo($image, PATHINFO_EXTENSION);
                    if (!in_array($extension, $allowed_extensions, true)) {
                        continue;
                    }

                    $banners[] = [
                        'src' => ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'uploads/profile_images/' . Output::getClean((string)$user->data()->id) . '/' . Output::getClean($image),
                        'name' => Output::getClean((string)$user->data()->id) . '/' . Output::getClean($image),
                        'active' => $user->data()->banner === $user->data()->id . '/' . $image
                    ];
                }
            }

            $smarty->assign([
                'SELF' => true,
                'SETTINGS_LINK' => URL::build('/user/settings'),
                'CHANGE_BANNER' => $language->get('user', 'change_banner'),
                'BANNERS' => $banners,
                'CAN_VIEW' => true,
            ]);

            if ($user->hasPermission('usercp.profile_banner')) {
                $smarty->assign([
                    'UPLOAD_PROFILE_BANNER' => $language->get('user', 'upload_profile_banner'),
                    'PROFILE_BANNER' => $language->get('user', 'profile_banner'),
                    'BROWSE' => $language->get('general', 'browse'),
                    'UPLOAD' => $language->get('user', 'upload'),
                    'UPLOAD_BANNER_URL' => ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'core/includes/image_upload.php'
                ]);
            }
        } else {
            $smarty->assign([
                'MESSAGE_LINK' => URL::build('/user/messaging/', 'action=new&amp;uid=' . urlencode((string)$query->id)),
                'FOLLOW_LINK' => URL::build('/user/follow/', 'user=' . urlencode((string)$query->id)),
                'CONFIRM' => $language->get('general', 'confirm'),
                'MOD_OR_ADMIN' => $profile_user->canViewStaffCP()
            ]);

            // Is the user blocked?
            if ($user->isBlocked($user->data()->id, $query->id)) {
                $smarty->assign([
                    'UNBLOCK_USER' => $language->get('user', 'unblock_user'),
                    'CONFIRM_UNBLOCK_USER' => $language->get('user', 'confirm_unblock_user')
                ]);
            } else {
                $smarty->assign([
                    'BLOCK_USER' => $language->get('user', 'block_user'),
                    'CONFIRM_BLOCK_USER' => $language->get('user', 'confirm_block_user')
                ]);
            }

            if ($user->hasPermission('modcp.profile_banner_reset')) {
                $smarty->assign([
                    'RESET_PROFILE_BANNER' => $language->get('moderator', 'reset_profile_banner'),
                    'RESET_PROFILE_BANNER_LINK' => URL::build('/profile/' . urlencode($query->username) . '/', 'action=reset_banner')
                ]);
            }
        }
    }

    try {
        $smarty->assign([
            'NICKNAME' => $profile_user->getDisplayName(true),
            'USERNAME' => $profile_user->getDisplayName(),
            'GROUPS' => (isset($query) ? $profile_user->getAllGroupHtml() : [Output::getPurified($group)]),
            'USERNAME_COLOUR' => $profile_user->getGroupStyle(),
            'USER_TITLE' => Output::getClean($query->user_title),
            'FOLLOW' => $language->get('user', 'follow'),
            'AVATAR' => $profile_user->getAvatar(500),
            'BANNER' => ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'uploads/profile_images/' . (($query->banner) ? Output::getClean($query->banner) : 'profile.jpg'),
            'POST_ON_WALL' => $language->get('user', 'post_on_wall', ['user' => Output::getClean($profile_user->getDisplayName())]),
            'FEED' => $language->get('user', 'feed'),
            'ABOUT' => $language->get('user', 'about'),
            'LIKE' => $language->get('user', 'like'),
            //'REACTIONS' => $reactions,
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
    } catch (GuzzleException $ignored) {
    }

    // Wall posts
    $wall_posts = [];
    $wall_posts_query = DB::getInstance()->orderWhere('user_profile_wall_posts', 'user_id = ' . $query->id, 'time', 'DESC')->results();

    if (count($wall_posts_query)) {
        // Pagination
        $paginator = new Paginator(
            $template_pagination ?? null,
            $template_pagination_left ?? null,
            $template_pagination_right ?? null
        );
        $results = $paginator->getLimited($wall_posts_query, 10, $p, count($wall_posts_query));
        $pagination = $paginator->generate(7, URL::build('/profile/' . urlencode($query->username) . '/'));

        $smarty->assign('PAGINATION', $pagination);

        // Display the correct number of posts
        foreach ($results->data as $nValue) {
            $post_user = DB::getInstance()->get('users', ['id', $nValue->author_id])->results();

            if (!count($post_user)) {
                continue;
            }

            // Get reactions/replies
            $reactions = [];
            $replies = [];

            $reactions_query = DB::getInstance()->get('user_profile_wall_posts_reactions', ['post_id', $nValue->id])->results();
            if (count($reactions_query)) {
                if (count($reactions_query) === 1) {
                    $reactions['count'] = $language->get('user', '1_reaction');
                } else {
                    $reactions['count'] = $language->get('user', 'x_reactions', ['count' => count($reactions_query)]);
                }

                foreach ($reactions_query as $reaction) {
                    // Get reaction name and icon
                    // TODO
                    /*
                    $reaction_name = DB::getInstance()->get('reactions', array('id', $reaction->reaction_id))->results();

                    if (!count($reaction_name) || $reaction_name[0]->enabled == 0) continue;
                    $reaction_html = $reaction_name[0]->html;
                    $reaction_name = Output::getClean($reaction_name[0]->name);
                    */

                    try {
                        $target_user = new User($reaction->user_id);
                    } catch (GuzzleException $ignored) {
                    }

                    try {
                        $reactions['reactions'][] = [
                            'user_id' => Output::getClean($reaction->user_id),
                            'username' => $target_user->getDisplayName(true),
                            'nickname' => $target_user->getDisplayName(),
                            'style' => $target_user->getGroupStyle(),
                            'profile' => $target_user->getProfileURL(),
                            'avatar' => $target_user->getAvatar(500),
                            //'reaction_name' => $reaction_name,
                            //'reaction_html' => $reaction_html
                        ];
                    } catch (GuzzleException $ignored) {
                    }
                }
            } else {
                $reactions['count'] = $language->get('user', 'x_reactions', ['count' => 0]);
            }
            $reactions_query = null;

            $replies_query = DB::getInstance()->orderWhere('user_profile_wall_posts_replies', 'post_id = ' . $nValue->id, 'time', 'ASC')->results();
            if (count($replies_query)) {
                if (count($replies_query) === 1) {
                    $replies['count'] = $language->get('user', '1_reply');
                } else {
                    $replies['count'] = $language->get('user', 'x_replies', ['count' => count($replies_query)]);
                }

                foreach ($replies_query as $reply) {
                    try {
                        $target_user = new User($reply->author_id);
                    } catch (GuzzleException $ignored) {
                    }

                    try {
                        $replies['replies'][] = [
                            'user_id' => Output::getClean($reply->author_id),
                            'username' => $target_user->getDisplayName(true),
                            'nickname' => $target_user->getDisplayName(),
                            'style' => $target_user->getGroupStyle(),
                            'profile' => $target_user->getProfileURL(),
                            'avatar' => $target_user->getAvatar(500),
                            'time_friendly' => $time_ago->inWords($reply->time, $language),
                            'time_full' => date(DATE_FORMAT, $reply->time),
                            'content' => Output::getPurified(Output::getDecoded($reply->content)),
                            'self' => (($user->isLoggedIn() && $user->data()->id === $reply->author_id) ? 1 : 0),
                            'id' => $reply->id
                        ];
                    } catch (GuzzleException $ignored) {
                    }
                }
            } else {
                $replies['count'] = $language->get('user', 'x_replies', ['count' => 0]);
            }
            $replies_query = null;

            try {
                $target_user = new User($post_user[0]->id);
            } catch (GuzzleException $ignored) {
            }

            try {
                $wall_posts[] = [
                    'id' => $nValue->id,
                    'user_id' => Output::getClean($post_user[0]->id),
                    'username' => $target_user->getDisplayName(true),
                    'nickname' => $target_user->getDisplayName(),
                    'profile' => $target_user->getProfileURL(),
                    'user_style' => $target_user->getGroupStyle(),
                    'avatar' => $target_user->getAvatar(500),
                    'content' => Output::getPurified(Output::getDecoded($nValue->content)),
                    'date_rough' => $time_ago->inWords($nValue->time, $language),
                    'date' => date(DATE_FORMAT, $nValue->time),
                    'reactions' => $reactions,
                    'replies' => $replies,
                    'self' => $user->isLoggedIn() && $user->data()->id === $nValue->author_id,
                    'reactions_link' => (($user->isLoggedIn() && $post_user[0]->id !== $user->data()->id) ? URL::build('/profile/' . urlencode($query->username) . '/', 'action=react&amp;post=' . urlencode($nValue->id)) : '#')
                ];
            } catch (GuzzleException $ignored) {
            }
        }
    } else {
        $smarty->assign('NO_WALL_POSTS', $language->get('user', 'no_wall_posts'));
    }

    $smarty->assign('WALL_POSTS', $wall_posts);

    if (isset($error)) {
        $smarty->assign('ERROR', $error);
    }

    if (isset($success)) {
        $smarty->assign('SUCCESS', $success);
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
        if ($integrationUser->data()->username !== null && $integrationUser->data()->show_publicly) {
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
    $smarty->assign('INTEGRATIONS', $user_integrations);

    if (!count($fields)) {
        $smarty->assign('NO_ABOUT_FIELDS', $language->get('user', 'no_about_fields'));
    }

    $profile_placeholders = $profile_user->getProfilePlaceholders();
    foreach ($profile_placeholders as $profile_placeholder) {
        $fields[] = [
            'title' => $profile_placeholder->friendly_name,
            'type' => 'text',
            'value' => $profile_placeholder->value,
            'tooltip' => $language->get('admin', 'placeholders_last_updated_time', ['time' => $time_ago->inWords($profile_placeholder->last_updated, $language)]),
        ];
    }

    // Add date registered and last seen
    $fields['registered'] = [
        'title' => $language->get('user', 'registered'),
        'type' => 'text',
        'value' => $time_ago->inWords($query->joined, $language),
        'tooltip' => date(DATE_FORMAT, $query->joined)
    ];
    $fields['last_seen'] = [
        'title' => $language->get('user', 'last_seen'),
        'type' => 'text',
        'value' => $time_ago->inWords($query->last_online, $language),
        'tooltip' => date(DATE_FORMAT, $query->last_online)
    ];

    // Add Profile views
    $fields['profile_views'] = [
        'title' => $language->get('user', 'views'),
        'type' => 'text',
        'value' => $query->profile_views
    ];

    $smarty->assign('ABOUT_FIELDS', $fields);

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
    $smarty->assign('TABS', $tabs);

    if (isset($directories[1]) && !empty($directories[1]) && !isset($_GET['error']) && $user->isLoggedIn() && $user->data()->username === $profile) {
        // Script for banner selector
        $template->assets()->include([
            AssetTree::IMAGE_PICKER,
        ]);
    }

    if (Session::exists('profile_banner_error')) {
        $smarty->assign('ERROR', Session::flash('profile_banner_error'));
    }

    // Load modules + template
    Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

    $template->onPageLoad();

    try {
        $smarty->assign('WIDGETS_LEFT', $widgets->getWidgets('left'));
        $smarty->assign('WIDGETS_RIGHT', $widgets->getWidgets());
    } catch (SmartyException $ignored) {
    }

    require(ROOT_PATH . '/core/templates/navbar.php');
    require(ROOT_PATH . '/core/templates/footer.php');

    // Display template
    try {
        $template->displayTemplate('profile.tpl', $smarty);
    } catch (SmartyException $ignored) {
    }
} else if (isset($_GET['error'])) {
    // User not exist
    $smarty->assign([
        'BACK' => $language->get('general', 'back'),
        'HOME' => $language->get('general', 'home'),
        'NOT_FOUND' => $language->get('user', 'couldnt_find_that_user')
    ]);
    // Load modules + template
    Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

    $template->onPageLoad();

    try {
        $smarty->assign('WIDGETS_LEFT', $widgets->getWidgets('left'));
        $smarty->assign('WIDGETS_RIGHT', $widgets->getWidgets());
    } catch (SmartyException $ignored) {
    }

    require(ROOT_PATH . '/core/templates/navbar.php');
    require(ROOT_PATH . '/core/templates/footer.php');

    // Display template
    try {
        $template->displayTemplate('user_not_exist.tpl', $smarty);
    } catch (SmartyException $ignored) {
    }

    // Search for user
    // TODO
}
