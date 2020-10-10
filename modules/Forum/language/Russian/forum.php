<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Russian Language for Forum module
 *  https://github.com/0niel
 */

$language = array(
	/*
	 *  Forums
	 */
	'forum' => 'Форум',
	'forums' => 'Форумы',
	'forum_index' => 'Главная',
	'no_users_online' => 'Нет пользователей онлайн.',
	'online_users' => 'Онлайн пользователи',
	'discussion' => 'Переписка',
	'topic' => 'Топик',
	'stats' => 'Статистика',
	'topics' => 'топики',
	'views' => 'просмотры',
	'posts' => 'посты',
	'x_posts' => '{x} постов', // Don't replace {x}
	'x_topics' => '{x} топиков', // Don't replace {x}
	'registered_x' => 'Регистрация: {x}', // Don't replace {x}
	'by' => '',
	'in' => 'в',
	'last_reply' => 'Последний ответ',
	'latest_discussions' => 'Последние переписки',
	'users_registered' => '<strong>Users Registered:</strong> {x}', // Don't replace "{x}"
	'latest_member' => '<strong>Latest Member:</strong> {x}', // Don't replace "{x}"
	'subforums' => 'Подфорумы',
	'subforum' => 'Подфорум',
	'no_topics' => 'Топики еще не созданы.',
	'no_topics_short' => 'Нет топиков',
	'new_topic' => 'Новый топик',
	'post_successful' => 'Post successful.',
	'post_edited_successfully' => 'Сообщение успешно отредактировано.',
	'user_tag' => 'Вас упомянули в посте.',
	'user_tag_info' => 'Вас упомянул в своем посте пользователь {x}.', // Don't replace "{x}"
	'creating_topic_in_x' => 'Создание топика в {x}', // Don't replace "{x}"
	'topic_title' => 'Заголовок топика',
	'new_reply' => 'Новый ответ',
	're' => 'RE: ',
	'topic_locked' => 'Топик закрыт',
	'mod_actions' => 'Модерация',
	'lock_topic' => 'Закрыть топик',
	'unlock_topic' => 'Открыть топик',
	'merge_topic' => 'Объединить топик',
	'merge_topics' => 'Объединить топики',
	'merge_instructions' => 'Топик для слияния <strong>должен</strong> находиться в пределах одного форума. При необходимости переместите топик.',
	'confirm_delete_short' => 'Подтвердите удаление',
	'confirm_delete_topic' => 'Вы уверены, что хотите удалить этот топик?',
	'confirm_delete_post' => 'Вы уверены, что хотите удалить этот пост?',
	'delete_topic' => 'Удалить топик',
	'move_topic' => 'Переместить топик',
	'move_topic_to' => 'Переместить топик в:',
	'stick_topic' => 'Закрепить топик',
	'unstick_topic' => 'Открепить топик',
	'share' => 'Поделиться',
	'share_twitter' => 'Поделиться в Twitter',
	'share_facebook' => 'Поделиться в Facebook',
	'edit' => 'Редактировать',
	'edit_post' => 'Редактировать пост',
	'last_edited' => 'Последнее редактирование: {x}', // Don't replace "{x}"
	'quote' => 'Цитировать',
	'topic_locked_notice' => 'Этот топик закрыт, но ваши разрешения позволяют вам создать ответ.',
	'title_required' => 'Пожалуйста, введите название топика',
	'content_required' => 'Пожалуйста, введите содержание поста',
	'title_min_2' => 'Название топика должно содержать не менее 2-х символов',
	'title_max_64' => 'Название топика должно быть не длиннее 64 символов',
	'content_min_2' => 'Содержание вашего поста должно быть не менее чем из 2-з символов',
	'content_max_50000' => 'Содержание вашего поста не должно превышать 50000 символов',
	'post_already_reported' => 'Вы уже пожаловались на этот пост!',
	'quoted_post' => 'Пост добавлен к цитируемым сообщениям.',
	'removed_quoted_post' => 'Пост удалён из цитируемыъ сообщений.',
	'insert_quotes' => 'Вставить цитату',
	'quoting_posts' => 'Вставка цитаты..',
	'error_quoting_posts' => 'Извините, но при цитировании этих постов была допущена ошибка.',
	'error_rating_post' => 'Sorry, there was an error rating the post.',
	'topic_stuck' => 'Topic has been stuck.',
	'topic_unstuck' => 'Topic has been unstuck.',
	'spam_wait' => 'Please wait {x} seconds before posting again.',
	'overview' => 'Overview',
	'no_label' => 'No label',
	'forum_redirect_warning' => 'Notice: you are about to leave this site! Are you sure you want to proceed to {x}?',
	'follow' => 'Подписаться',
	'unfollow' => 'Отписаться',
	'now_following_topic' => 'Теперь вы подписаны на этот топик и будете получать уведомления о новых ответах.',
	'no_longer_following_topic' => 'Вы больше не подписаны на этот топик и не будете уведомлены о каких-либо новых ответах.',
	'new_reply_in_topic' => '{x} has replied to topic {y}', // Don't replace {x} (username) or {y} (topic title)
	'started_by_x' => 'Автор топика: {x}', // Don't replace {x}
	'sticky_topics' => 'Закреплённые топики',

	// Homepage
	'latest_announcements' => 'Latest Announcements',
	'read_full_post' => 'Читать...',

	// Admin tab
	'labels' => 'Метки',
	'new_forum' => '<i class="fa fa-plus-circle"></i> Создать форум',
	'new_label' => '<i class="fa fa-plus-circle"></i> Создать метку',
	'new_label_type' => '<i class="fa fa-plus-circle"></i> Создать новый тип меток',
	'label_types' => 'Типы меток',
	'creating_label' => 'Создание новой метки',
	'creating_label_type' => 'Создание нового типа метки',
	'editing_label' => 'Редактирование метки',
	'editing_label_type' => 'Редактирование типа метки',
	'label_name' => 'Название метки',
	'label_type' => 'Тип метки',
	'label_type_name' => 'Название типа метки',
	'label_type_html' => 'HTML для типа метки',
	'label_type_html_help' => 'HTML-код должен содержать {x} в качестве заполнителя для имени метки',
	'label' => 'Метка',
	'label_forums' => 'Label Forums',
	'label_groups' => 'Label Groups',
	'no_forums' => 'No forums',
	'no_labels_defined' => 'No labels have been defined yet.',
	'no_label_types_defined' => 'No label types have been defined yet.',
	'label_edit_success' => 'Label successfully edited.',
	'label_type_edit_success' => 'Label type successfully edited.',
	'label_creation_success' => 'Label created successfully.',
	'label_type_creation_success' => 'Label type created successfully.',
	'label_creation_error' => 'Error creating a label. Please ensure the name is no longer than 32 characters and that you have specified a type.',
	'label_type_creation_error' => 'Error creating a label type. Please ensure the name is no longer than 32 characters and that the HTML is no longer than 64 characters.',
	'creating_forum' => 'Создание нового форума',
	'forum_name' => 'Название форума',
	'forum_description' => 'Описание форума',
	'delete_forum' => 'Удалить форум',
	'move_topics_and_posts_to' => 'Переместить топики и сообщения в...',
	'delete_topics_and_posts' => 'Удалить топики и сообщения',
	'forum_permissions' => 'Настройка прав',
	'select_a_parent_forum' => 'Выберите родительский форум',
	'parent_forum' => 'Родительский форум',
	'parent_forum_x' => 'Родительский форум: {x}', // Don't replace {x}
	'has_no_parent' => 'Нет родительского форума',
	'guests' => 'Гости',
	'group' => 'Группа',
	'can_view_forum' => 'Может просматривать форум?',
	'can_view_other_topics' => 'Может просматривать другие топики?',
	'can_create_topic' => 'Может создавать топики?',
	'can_post_reply' => 'Может создавать ответы?',
	'can_moderate_forum' => 'Может модерировать форум?',
	'display_topics_as_news' => 'Отображать топики из этого форума на главной странице?',
	'forum_created_successfully' => 'Форум успешно создан.',
	'forum_layout' => 'Forum Layout',
	'table_view' => 'Table View',
	'latest_discussions_view' => 'Latest Discussions View',
	'input_forum_title' => 'Please input a forum title.',
	'input_forum_description' => 'Please input a forum description.',
	'forum_name_minimum' => 'The forum name must be a minimum of 2 characters.',
	'forum_description_minimum' => 'The forum description must be a minimum of 2 characters.',
	'forum_name_maximum' => 'The forum name must be a maximum of 150 characters.',
	'forum_description_maximum' => 'The forum description must be a maximum of 255 characters.',
	'forum_type' => 'Тип форума',
	'forum_type_forum' => 'Форум',
	'forum_type_category' => 'Категория',
	'invalid_action' => 'Invalid action',
	'use_reactions' => 'Использовать реакции?',
	'redirect_forum' => 'Это форум-перенаправление?',
	'redirect_url' => 'URL перенаправления',
	'invalid_redirect_url' => 'You have enabled the forum redirect, but you have not entered a valid URL between 1 and 512 characters.',
	'forum_icon' => 'Иконка форума',
	'forum_icon_maximum' => 'The forum icon must be a maximum of 256 characters.',
	'settings_updated_successfully' => 'Settings updated successfully.',
	'forum_updated_successfully' => 'Форум успешно обновлён.',
	'forum_deleted_successfully' => 'Форум успешно удалён.',
	'label_deleted_successfully' => 'Метка успешно удалена.',
	'label_type_deleted_successfully' => 'Тип метки успешно удалён.',
	'topic_placeholder' => 'Topic placeholder',

	// Search
    'forum_search' => 'Поиск по форуму',
	'search_again_in_x_seconds' => 'Пожалуйста, подождите {x} секунд прежде чем снова искать.',
	'search_results' => 'Результаты поиска',
	'new_search' => 'Новый поиск',
	'invalid_search_query' => 'Please enter a search query between 3 and 128 characters long.',
	'no_results_found' => 'Никаких результатов не найдено.',

	// Profile tab
	'user_no_posts' => 'Этот пользователь еще не сделал ни одного сообщения на форуме.',
	'latest_posts' => 'Последние сообщения',

	// UserCP
	'last_7_days_posts' => 'Сообщения на форума (последние 7 дней)',
	'your_posts' => 'Ваше кол-во постов',
	'average_posts' => 'Среднее кол-во постов',
	'total_posts' => 'Общее кол-во постов',

    // Hooks
    'new_topic_hook_info' => 'Новый топик',
    'new_topic_text' => 'Создан топик {x} пользователем {y}', // Don't replace {x} (forum name), optional variable {y} (topic author)
    'include_in_hook' => 'Включите новые темы с этого форума в webhook?',

	// Panel statistics
	'recent_topics' => 'Последние топики',
	'recent_topics_statistic_icon' => '<i class="fas fa-comment"></i>',
	'recent_posts' => 'Последние посты',
	'recent_posts_statistic_icon' => '<i class="far fa-comments"></i>',
	'topics_title' => 'Топики',
	'posts_title' => 'Посты',
);
